<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\Property;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ConversationController extends Controller
{
    /**
     * Render inbox UI with conversations for the authenticated user/owner.
     */
    public function inbox(Request $request): View
    {
        $userId = (int) Auth::id();
        $routePrefix = $request->routeIs('owner.*') ? 'owner' : 'user';

        $conversations = Conversation::query()
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with([
                'property:id,title,city,address',
                'participants.user:id,name,email,profile_photo,role',
                'latestMessage.sender:id,name,email,profile_photo',
            ])
            ->orderByDesc(
                Message::query()
                    ->select('created_at')
                    ->whereColumn('messages.conversation_id', 'conversations.id')
                    ->latest('created_at')
                    ->limit(1)
            )
            ->orderByDesc('updated_at')
            ->get();

        $unreadByConversation = $this->buildUnreadCountMap($userId);

        $conversations->each(function ($conversation) use ($unreadByConversation) {
            $conversation->setAttribute('unread_count', (int) ($unreadByConversation[$conversation->id] ?? 0));
        });

        $selectedConversationId = (int) $request->query('conversation', 0);

        if ($selectedConversationId <= 0 && $conversations->isNotEmpty()) {
            $selectedConversationId = (int) $conversations->first()->id;
        }

        $selectedConversation = null;

        if ($selectedConversationId > 0) {
            $isAccessibleConversation = $conversations->contains(function ($conversation) use ($selectedConversationId) {
                return (int) $conversation->id === $selectedConversationId;
            });

            if ($isAccessibleConversation) {
                $selectedConversation = Conversation::query()
                    ->with([
                        'property:id,title,city,address',
                        'participants.user:id,name,email,profile_photo,role',
                        'messages' => function ($query) {
                            $query->with(['sender:id,name,email,profile_photo'])
                                ->orderBy('created_at', 'asc');
                        },
                    ])
                    ->find($selectedConversationId);

                ConversationParticipant::query()
                    ->where('conversation_id', $selectedConversationId)
                    ->where('user_id', $userId)
                    ->update(['last_read_at' => now()]);

                $conversations->each(function ($conversation) use ($selectedConversationId) {
                    if ((int) $conversation->id === $selectedConversationId) {
                        $conversation->setAttribute('unread_count', 0);
                    }
                });
            }
        }

        return view('messages.index', [
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
            'selectedConversationId' => $selectedConversationId,
            'routePrefix' => $routePrefix,
            'currentUserId' => $userId,
        ]);
    }

    /**
     * Create or open a property conversation between current user and property owner.
     */
    public function createOrOpenPropertyConversation($propertyId): JsonResponse
    {
        $userId = (int) Auth::id();
        $property = Property::verified()->select(['id', 'owner_id'])->find($propertyId);

        if (! $property) {
            return response()->json([
                'message' => 'This property is not available for messaging.',
            ], 422);
        }

        $ownerId = (int) $property->owner_id;

        if ($userId === $ownerId) {
            return response()->json([
                'message' => 'You cannot start a conversation with yourself.',
            ], 422);
        }

        $conversation = DB::transaction(function () use ($property, $userId, $ownerId) {
            $existing = Conversation::query()
                ->where('type', 'property')
                ->where('property_id', $property->id)
                ->whereHas('participants', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereHas('participants', function ($query) use ($ownerId) {
                    $query->where('user_id', $ownerId);
                })
                ->whereDoesntHave('participants', function ($query) use ($userId, $ownerId) {
                    $query->whereNotIn('user_id', [$userId, $ownerId]);
                })
                ->first();

            if ($existing) {
                return $existing;
            }

            $newConversation = new Conversation();
            $newConversation->type = 'property';
            $newConversation->property_id = $property->id;
            $newConversation->created_by = $userId;
            $newConversation->save();

            $participantA = new ConversationParticipant();
            $participantA->conversation_id = $newConversation->id;
            $participantA->user_id = $userId;
            $participantA->save();

            $participantB = new ConversationParticipant();
            $participantB->conversation_id = $newConversation->id;
            $participantB->user_id = $ownerId;
            $participantB->save();

            return $newConversation;
        });

        return response()->json([
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Send a message in an existing conversation.
     */
    public function sendMessage(Request $request, $conversationId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'nullable|required_without:image|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ], [
            'message.required_without' => 'Please enter a message or attach an image.',
            'message.max' => 'Messages may not be greater than 5000 characters.',
            'image.image' => 'The attachment must be a valid image file.',
            'image.mimes' => 'Images must be JPG, JPEG, PNG, WEBP, or GIF files.',
            'image.max' => 'Images may not be larger than 4 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = (int) Auth::id();
        $conversation = Conversation::query()->with('property')->findOrFail($conversationId);

        $isParticipant = ConversationParticipant::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->exists();

        if (!$isParticipant) {
            return response()->json([
                'message' => 'You are not a participant of this conversation.',
            ], 403);
        }

        $validated = $validator->validated();
        $imagePath = null;
        $image = $request->file('image');

        if ($image) {
            $imagePath = $image->store('messages', 'public');
        }

        try {
            $newMessage = new Message();
            $newMessage->conversation_id = $conversation->id;
            $newMessage->sender_id = $userId;
            $newMessage->message = trim((string) ($validated['message'] ?? ''));
            $newMessage->image_path = $imagePath;
            $newMessage->image_original_name = $image?->getClientOriginalName();
            $newMessage->image_mime_type = $image?->getMimeType();
            $newMessage->image_size = $image?->getSize();
            $newMessage->save();
        } catch (Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        ConversationParticipant::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);

        $messages = Message::query()
            ->where('conversation_id', $conversation->id)
            ->with(['sender:id,name,email,profile_photo'])
            ->orderBy('created_at')
            ->get([
                'id',
                'conversation_id',
                'sender_id',
                'message',
                'image_path',
                'image_original_name',
                'image_mime_type',
                'image_size',
                'created_at',
                'updated_at',
            ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $messages,
        ], 201);
    }

    /**
     * Show a conversation with participants and messages.
     */
    public function showConversation($conversationId): JsonResponse
    {
        $userId = (int) Auth::id();
        $conversation = Conversation::query()->with('property')->findOrFail($conversationId);

        $isParticipant = ConversationParticipant::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->exists();

        if (!$isParticipant) {
            return response()->json([
                'message' => 'You are not a participant of this conversation.',
            ], 403);
        }

        $conversation->load([
            'participants.user:id,name,email,profile_photo',
            'messages' => function ($query) {
                $query->with(['sender:id,name,email,profile_photo'])
                    ->orderBy('created_at', 'asc');
            },
        ]);

        ConversationParticipant::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);

        return response()->json([
            'conversation' => $conversation,
        ]);
    }

    /**
     * Create or open a roommate conversation between current user and another user.
     */
    public function createOrOpenRoommateConversation($userId): JsonResponse
    {
        $currentUserId = (int) Auth::id();
        $targetUserId = (int) $userId;

        if ($currentUserId === $targetUserId) {
            return response()->json([
                'message' => 'You cannot start a conversation with yourself.',
            ], 422);
        }

        User::query()->findOrFail($targetUserId);

        $conversation = DB::transaction(function () use ($currentUserId, $targetUserId) {
            $existing = Conversation::query()
                ->where('type', 'roommate')
                ->whereNull('property_id')
                ->whereHas('participants', function ($query) use ($currentUserId) {
                    $query->where('user_id', $currentUserId);
                })
                ->whereHas('participants', function ($query) use ($targetUserId) {
                    $query->where('user_id', $targetUserId);
                })
                ->whereDoesntHave('participants', function ($query) use ($currentUserId, $targetUserId) {
                    $query->whereNotIn('user_id', [$currentUserId, $targetUserId]);
                })
                ->first();

            if ($existing) {
                return $existing;
            }

            $newConversation = new Conversation();
            $newConversation->type = 'roommate';
            $newConversation->property_id = null;
            $newConversation->created_by = $currentUserId;
            $newConversation->save();

            $participantA = new ConversationParticipant();
            $participantA->conversation_id = $newConversation->id;
            $participantA->user_id = $currentUserId;
            $participantA->save();

            $participantB = new ConversationParticipant();
            $participantB->conversation_id = $newConversation->id;
            $participantB->user_id = $targetUserId;
            $participantB->save();

            return $newConversation;
        });

        return response()->json([
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Get unread message counts for the authenticated user's conversations.
     *
     * Unread rules:
     * - message created after participant.last_read_at (or all if last_read_at is null)
     * - exclude messages sent by current user
     */
    public function getUnreadCount(): JsonResponse
    {
        $userId = (int) Auth::id();
        $unreadByConversation = collect($this->buildUnreadCountMap($userId));

        return response()->json([
            'total_unread' => (int) $unreadByConversation->sum(),
            'unread_by_conversation' => $unreadByConversation,
        ]);
    }

    /**
     * Poll for new messages in a conversation after a given timestamp.
     */
    public function pollNewMessages(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'conversation_id' => 'required|integer|exists:conversations,id',
            'last_message_time' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $userId = (int) Auth::id();
        $conversationId = (int) $validated['conversation_id'];

        $isParticipant = ConversationParticipant::query()
            ->where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->exists();

        if (!$isParticipant) {
            return response()->json([
                'message' => 'You are not a participant of this conversation.',
            ], 403);
        }

        $conversation = Conversation::query()->with('property')->findOrFail($conversationId);
        $query = Message::query()
            ->where('conversation_id', $conversationId)
            ->with(['sender:id,name,email,profile_photo'])
            ->orderBy('created_at', 'asc');

        if (!empty($validated['last_message_time'])) {
            $query->where('created_at', '>', $validated['last_message_time']);
        }

        $messages = $query->get([
            'id',
            'conversation_id',
            'sender_id',
            'message',
            'image_path',
            'image_original_name',
            'image_mime_type',
            'image_size',
            'created_at',
            'updated_at',
        ]);

        return response()->json([
            'conversation_id' => $conversationId,
            'messages' => $messages,
        ]);
    }

    /**
     * Build unread count map keyed by conversation_id for a single user.
     *
     * @return array<int, int>
     */
    private function buildUnreadCountMap(int $userId): array
    {
        return Message::query()
            ->join('conversation_participants as cp', function ($join) use ($userId) {
                $join->on('cp.conversation_id', '=', 'messages.conversation_id')
                    ->where('cp.user_id', '=', $userId);
            })
            ->join('conversations', 'conversations.id', '=', 'messages.conversation_id')
            ->where('messages.sender_id', '!=', $userId)
            ->where(function ($query) {
                $query->whereNull('cp.last_read_at')
                    ->orWhereColumn('messages.created_at', '>', 'cp.last_read_at');
            })
            ->selectRaw('messages.conversation_id, COUNT(messages.id) as unread_count')
            ->groupBy('messages.conversation_id')
            ->get()
            ->mapWithKeys(function ($row) {
                return [(int) $row->conversation_id => (int) $row->unread_count];
            })
            ->all();
    }

}
