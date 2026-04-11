@extends($routePrefix === 'owner' ? 'owner.layout' : 'user.layout')

@section('title', 'Messages')
@section('page-title', 'Messages')

@section('content')
    <style>
        .mn-shell {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 16px;
            min-height: 620px;
        }

        .mn-panel,
        .mn-chat {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #ffffff;
            overflow: hidden;
        }

        .mn-panel-header,
        .mn-chat-header {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            background: #fcfcfd;
        }

        .mn-panel-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .mn-panel-title h2 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
        }

        .mn-panel-count {
            min-width: 24px;
            padding: 0 8px;
            height: 22px;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            background: #fff;
            font-size: 0.72rem;
            font-weight: 600;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .mn-thread-list {
            max-height: 560px;
            overflow-y: auto;
        }

        .mn-thread {
            display: flex;
            gap: 10px;
            padding: 12px 14px;
            border-bottom: 1px solid #f8fafc;
            text-decoration: none;
            color: inherit;
            transition: background 0.15s ease;
        }

        .mn-thread:hover {
            background: #f8fafc;
        }

        .mn-thread.active {
            background: #fff3f5;
        }

        .mn-thread-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.84rem;
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
        }

        .mn-thread-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mn-thread-body {
            min-width: 0;
            flex: 1;
        }

        .mn-thread-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .mn-thread-title {
            font-size: 0.86rem;
            font-weight: 600;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mn-thread-time {
            font-size: 0.7rem;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .mn-thread-preview {
            margin-top: 4px;
            font-size: 0.78rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mn-thread-meta {
            margin-top: 7px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .mn-thread-type {
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .mn-thread-unread {
            min-width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #ff385c;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }

        .mn-chat-header h3 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
        }

        .mn-chat-header-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mn-chat-header-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #475569;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.88rem;
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
        }

        .mn-chat-header-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mn-chat-header-copy {
            min-width: 0;
        }

        .mn-chat-subtitle {
            margin-top: 3px;
            font-size: 0.78rem;
            color: #64748b;
        }

        .mn-chat-body {
            height: 460px;
            overflow-y: auto;
            padding: 16px;
            background: #f8fafc;
        }

        .mn-message {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
            max-width: 75%;
        }

        .mn-message.mine {
            margin-left: auto;
            align-items: flex-end;
        }

        .mn-message.other {
            margin-right: auto;
            align-items: flex-start;
        }

        .mn-bubble {
            border-radius: 14px;
            padding: 10px 12px;
            font-size: 0.84rem;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .mn-message.mine .mn-bubble {
            background: #ff385c;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .mn-message.other .mn-bubble {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            border-bottom-left-radius: 4px;
        }

        .mn-message-time {
            margin-top: 3px;
            font-size: 0.68rem;
            color: #94a3b8;
        }

        .mn-chat-footer {
            border-top: 1px solid #f1f5f9;
            padding: 12px;
            background: #fff;
        }

        .mn-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 8px;
            align-items: end;
        }

        .mn-input {
            width: 100%;
            min-height: 42px;
            max-height: 120px;
            border: 1px solid #dbe4ee;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 0.84rem;
            font-family: inherit;
            resize: vertical;
            outline: none;
        }

        .mn-input:focus {
            border-color: rgba(255, 56, 92, 0.4);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .mn-send {
            min-height: 42px;
            padding: 0 14px;
            border: 1px solid transparent;
            border-radius: 12px;
            background: #ff385c;
            color: #fff;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .mn-send:hover {
            background: #e11d48;
        }

        .mn-send:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .mn-empty {
            height: 100%;
            min-height: 540px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 6px;
            text-align: center;
            color: #64748b;
            padding: 24px;
        }

        .mn-empty h4 {
            font-size: 0.95rem;
            color: #111827;
        }

        @media (max-width: 1024px) {
            .mn-shell {
                grid-template-columns: 1fr;
            }

            .mn-panel {
                min-height: 180px;
            }

            .mn-thread-list {
                max-height: 260px;
            }

            .mn-chat-body {
                height: 360px;
            }
        }
    </style>

    <div class="mn-shell">
        <aside class="mn-panel">
            <div class="mn-panel-header">
                <div class="mn-panel-title">
                    <h2>Conversations</h2>
                    <span class="mn-panel-count">{{ $conversations->count() }}</span>
                </div>
            </div>

            @if($conversations->isEmpty())
                <div class="mn-empty" style="min-height: 220px;">
                    <h4>No conversations yet</h4>
                    <p>Start a chat from property details using the Contact Owner button.</p>
                </div>
            @else
                <div class="mn-thread-list">
                    @foreach($conversations as $conversation)
                        @php
                            $otherParticipant = $conversation->participants->firstWhere('user_id', '!=', $currentUserId)?->user;

                            if ($conversation->type === 'property') {
                                $threadTitle = $otherParticipant->name ?? 'Property conversation';
                                $threadSubtitle = $conversation->property->title ?? 'Property chat';
                            } else {
                                $threadTitle = $otherParticipant->name ?? 'Roommate conversation';
                                $threadSubtitle = 'Roommate chat';
                            }

                            $latestMessage = $conversation->latestMessage;
                            $previewText = $latestMessage ? $latestMessage->message : 'No messages yet';
                            $avatarPath = $otherParticipant?->profile_photo;
                            $avatarUrl = null;

                            if ($avatarPath) {
                                if (\Illuminate\Support\Str::startsWith($avatarPath, ['http://', 'https://'])) {
                                    $avatarUrl = $avatarPath;
                                } elseif (\Illuminate\Support\Str::startsWith($avatarPath, 'storage/')) {
                                    $avatarUrl = asset($avatarPath);
                                } else {
                                    $avatarUrl = asset('storage/' . $avatarPath);
                                }
                            }
                        @endphp

                        <a href="{{ route($routePrefix . '.messages.index', ['conversation' => $conversation->id]) }}" class="mn-thread {{ (int) $conversation->id === (int) $selectedConversationId ? 'active' : '' }}">
                            <div class="mn-thread-avatar">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ $threadTitle }}">
                                @else
                                    {{ strtoupper(substr($otherParticipant->name ?? $threadTitle, 0, 1)) }}
                                @endif
                            </div>

                            <div class="mn-thread-body">
                                <div class="mn-thread-top">
                                    <p class="mn-thread-title">{{ $threadTitle }}</p>
                                    <span class="mn-thread-time">{{ $latestMessage?->created_at?->format('M d') }}</span>
                                </div>
                                <p class="mn-thread-preview">{{ \Illuminate\Support\Str::limit($previewText, 70) }}</p>
                                <div class="mn-thread-meta">
                                    <span class="mn-thread-type">{{ $threadSubtitle }}</span>
                                    @if(($conversation->unread_count ?? 0) > 0)
                                        <span class="mn-thread-unread">{{ $conversation->unread_count > 99 ? '99+' : $conversation->unread_count }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </aside>

        <section class="mn-chat">
            @if($selectedConversation)
                @php
                    $selectedOtherParticipant = $selectedConversation->participants->firstWhere('user_id', '!=', $currentUserId)?->user;
                    $chatTitle = $selectedConversation->type === 'property'
                        ? ($selectedOtherParticipant->name ?? 'Conversation')
                        : ($selectedOtherParticipant->name ?? 'Roommate conversation');

                    $chatSubtitle = $selectedConversation->type === 'property'
                        ? ($selectedConversation->property->title ?? 'Property chat')
                        : 'Roommate chat';

                    $chatAvatarPath = $selectedOtherParticipant?->profile_photo;
                    $chatAvatarUrl = null;

                    if ($chatAvatarPath) {
                        if (\Illuminate\Support\Str::startsWith($chatAvatarPath, ['http://', 'https://'])) {
                            $chatAvatarUrl = $chatAvatarPath;
                        } elseif (\Illuminate\Support\Str::startsWith($chatAvatarPath, 'storage/')) {
                            $chatAvatarUrl = asset($chatAvatarPath);
                        } else {
                            $chatAvatarUrl = asset('storage/' . $chatAvatarPath);
                        }
                    }

                    $lastMessageTimestamp = optional($selectedConversation->messages->last()?->created_at)->toIso8601String();
                @endphp

                <div class="mn-chat-header">
                    <div class="mn-chat-header-row">
                        <div class="mn-chat-header-avatar">
                            @if($chatAvatarUrl)
                                <img src="{{ $chatAvatarUrl }}" alt="{{ $chatTitle }}">
                            @else
                                {{ strtoupper(substr($chatTitle ?? 'C', 0, 1)) }}
                            @endif
                        </div>

                        <div class="mn-chat-header-copy">
                            <h3>{{ $chatTitle }}</h3>
                            <p class="mn-chat-subtitle">{{ $chatSubtitle }}</p>
                        </div>
                    </div>
                </div>

                <div class="mn-chat-body" id="mn-chat-body">
                    @foreach($selectedConversation->messages as $message)
                        @php
                            $isMine = (int) $message->sender_id === (int) $currentUserId;
                        @endphp
                        <div class="mn-message {{ $isMine ? 'mine' : 'other' }}" data-message-id="{{ $message->id }}" data-message-time="{{ optional($message->created_at)->toIso8601String() }}">
                            <div class="mn-bubble">{{ $message->message }}</div>
                            <span class="mn-message-time">{{ $message->created_at->format('M d, h:i A') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mn-chat-footer">
                    <form id="mn-send-form" class="mn-form" autocomplete="off">
                        @csrf
                        <textarea class="mn-input" name="message" id="mn-message-input" rows="1" maxlength="5000" placeholder="Type a message..." required></textarea>
                        <button class="mn-send" id="mn-send-button" type="submit">Send</button>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const routePrefix = @json($routePrefix);
                        const conversationId = @json((int) $selectedConversation->id);
                        const currentUserId = @json((int) $currentUserId);
                        const chatBody = document.getElementById('mn-chat-body');
                        const sendForm = document.getElementById('mn-send-form');
                        const messageInput = document.getElementById('mn-message-input');
                        const sendButton = document.getElementById('mn-send-button');
                        const csrfToken = sendForm.querySelector('input[name="_token"]').value;
                        let lastMessageTime = @json($lastMessageTimestamp);

                        const escapeHtml = (value) => {
                            const temp = document.createElement('div');
                            temp.textContent = value ?? '';
                            return temp.innerHTML;
                        };

                        const formatTime = (value) => {
                            if (!value) {
                                return '';
                            }

                            const date = new Date(value);
                            if (Number.isNaN(date.getTime())) {
                                return '';
                            }

                            return date.toLocaleString(undefined, {
                                month: 'short',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        };

                        const renderMessage = (message) => {
                            const mineClass = Number(message.sender_id) === Number(currentUserId) ? 'mine' : 'other';
                            const messageTime = message.created_at || '';
                            const safeMessage = escapeHtml(message.message || '');

                            return `
                                <div class="mn-message ${mineClass}" data-message-id="${message.id}" data-message-time="${messageTime}">
                                    <div class="mn-bubble">${safeMessage}</div>
                                    <span class="mn-message-time">${formatTime(messageTime)}</span>
                                </div>
                            `;
                        };

                        const scrollToBottom = () => {
                            chatBody.scrollTop = chatBody.scrollHeight;
                        };

                        const setMessages = (messages) => {
                            chatBody.innerHTML = (messages || []).map(renderMessage).join('');

                            if (messages && messages.length) {
                                lastMessageTime = messages[messages.length - 1].created_at || lastMessageTime;
                            }

                            scrollToBottom();
                        };

                        const appendMessages = (messages) => {
                            if (!messages || !messages.length) {
                                return;
                            }

                            const existingIds = new Set(
                                Array.from(chatBody.querySelectorAll('[data-message-id]')).map((element) => Number(element.getAttribute('data-message-id')))
                            );

                            const freshMessages = messages.filter((message) => !existingIds.has(Number(message.id)));
                            if (!freshMessages.length) {
                                return;
                            }

                            chatBody.insertAdjacentHTML('beforeend', freshMessages.map(renderMessage).join(''));
                            lastMessageTime = freshMessages[freshMessages.length - 1].created_at || lastMessageTime;
                            scrollToBottom();
                        };

                        scrollToBottom();

                        sendForm.addEventListener('submit', async function (event) {
                            event.preventDefault();

                            const message = messageInput.value.trim();
                            if (!message) {
                                return;
                            }

                            sendButton.disabled = true;

                            try {
                                const response = await fetch(`${window.location.origin}/${routePrefix}/conversations/${conversationId}/messages`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: JSON.stringify({ message })
                                });

                                const data = await response.json();

                                if (!response.ok) {
                                    throw new Error(data.message || 'Unable to send message.');
                                }

                                setMessages(data.messages || []);
                                messageInput.value = '';
                                messageInput.focus();
                            } catch (error) {
                                alert(error.message || 'Unable to send message.');
                            } finally {
                                sendButton.disabled = false;
                            }
                        });

                        const pollNewMessages = async () => {
                            try {
                                const params = new URLSearchParams({
                                    conversation_id: String(conversationId)
                                });

                                if (lastMessageTime) {
                                    params.set('last_message_time', lastMessageTime);
                                }

                                const response = await fetch(`${window.location.origin}/${routePrefix}/conversations/poll?${params.toString()}`, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });

                                if (!response.ok) {
                                    return;
                                }

                                const data = await response.json();
                                appendMessages(data.messages || []);
                            } catch (error) {
                                // silent retry on next poll
                            }
                        };

                        window.setInterval(pollNewMessages, 5000);
                    });
                </script>
            @else
                <div class="mn-empty">
                    <h4>Select a conversation</h4>
                    <p>Open a thread from the left panel to view and send messages.</p>
                </div>
            @endif
        </section>
    </div>
@endsection
