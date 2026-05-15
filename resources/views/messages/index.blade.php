@extends($routePrefix === 'owner' ? 'owner.layout' : 'user.layout')

@section('title', 'Messages')
@section('page-title', 'Messages')

@section('content')
    <style>
        .mn-shell {
            display: grid;
            grid-template-columns: minmax(280px, 320px) minmax(0, 1fr);
            gap: 18px;
            min-height: 640px;
            max-width: 1440px;
            margin: 0 auto;
            min-width: 0;
        }

        .mn-panel,
        .mn-chat {
            min-width: 0;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.97);
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .mn-chat {
            display: flex;
            min-height: 0;
            flex-direction: column;
        }

        .mn-chat-header,
        .mn-chat-footer {
            flex: 0 0 auto;
        }

        .mn-chat-body {
            flex: 1 1 auto;
        }

        .mn-thread,
        .mn-message,
        .mn-bubble,
        .mn-message-image-link {
            min-width: 0;
        }

        .mn-panel-header,
        .mn-chat-header {
            padding: 16px 18px;
            border-bottom: 1px solid #eef2f7;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
        }

        .mn-panel-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .mn-panel-title h2 {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #475569;
        }

        .mn-panel-count {
            min-width: 24px;
            height: 24px;
            padding: 0 8px;
            border-radius: 999px;
            border: 1px solid rgba(255, 56, 92, 0.16);
            background: #fff7f9;
            font-size: 0.72rem;
            font-weight: 700;
            color: #ff385c;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .mn-thread-list {
            max-height: 560px;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .mn-thread {
            display: flex;
            gap: 12px;
            padding: 12px;
            border-radius: 16px;
            text-decoration: none;
            color: inherit;
            border: 1px solid transparent;
            background: #ffffff;
            transition: transform 0.16s ease, box-shadow 0.16s ease, background 0.16s ease, border-color 0.16s ease;
        }

        .mn-thread:hover {
            background: #ffffff;
            border-color: #eef2f7;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
            transform: translateY(-1px);
        }

        .mn-thread.active {
            background: linear-gradient(180deg, rgba(255, 56, 92, 0.08) 0%, rgba(255, 56, 92, 0.04) 100%);
            border-color: rgba(255, 56, 92, 0.18);
            box-shadow: 0 10px 22px rgba(255, 56, 92, 0.08);
        }

        .mn-thread-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
            border: 1px solid #e2e8f0;
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
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mn-thread-time {
            font-size: 0.72rem;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .mn-thread-preview {
            margin-top: 4px;
            font-size: 0.8rem;
            line-height: 1.35;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mn-thread-meta {
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .mn-thread-type {
            font-size: 0.68rem;
            font-weight: 700;
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
            font-size: 0.98rem;
            font-weight: 700;
            color: #0f172a;
        }

        .mn-chat-header-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mn-chat-header-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
            border: 1px solid #e2e8f0;
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
            font-size: 0.8rem;
            color: #64748b;
        }

        .mn-chat-body {
            height: 460px;
            overflow-y: auto;
            padding: 20px;
            background: linear-gradient(180deg, #f8fafc 0%, #fcfdff 100%);
        }

        .mn-message {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
            max-width: 72%;
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
            border-radius: 18px;
            padding: 11px 14px;
            font-size: 0.88rem;
            line-height: 1.55;
            white-space: pre-wrap;
            word-break: break-word;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }

        .mn-message.mine .mn-bubble {
            background: linear-gradient(135deg, #ff385c 0%, #ff5577 100%);
            color: #fff;
            border-bottom-right-radius: 6px;
            box-shadow: 0 10px 24px rgba(255, 56, 92, 0.18);
        }

        .mn-message.other .mn-bubble {
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            border-bottom-left-radius: 6px;
        }

        .mn-message-image-link {
            display: block;
            max-width: min(280px, 100%);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        }

        .mn-message.mine .mn-message-image-link {
            border-bottom-right-radius: 6px;
        }

        .mn-message.other .mn-message-image-link {
            border-bottom-left-radius: 6px;
        }

        .mn-message-image {
            display: block;
            width: 100%;
            height: auto;
            max-height: 320px;
            object-fit: cover;
            background: #f8fafc;
        }

        .mn-message-image-link + .mn-bubble {
            margin-top: 8px;
        }

        .mn-message-time {
            margin-top: 4px;
            font-size: 0.68rem;
            color: #94a3b8;
        }

        .mn-chat-footer {
            border-top: 1px solid #eef2f7;
            padding: 14px 16px 16px;
            background: #fff;
        }

        .mn-form {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: 10px;
            align-items: end;
        }

        .mn-attach {
            min-width: 48px;
            min-height: 48px;
            border: 1px solid #dbe4ee;
            border-radius: 14px;
            background: #fff;
            color: #475569;
            font-size: 1.2rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color 0.16s ease, color 0.16s ease, box-shadow 0.16s ease;
        }

        .mn-attach:hover,
        .mn-attach:focus-within {
            border-color: rgba(255, 56, 92, 0.42);
            color: #ff385c;
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .mn-attach-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .mn-attachment-preview {
            grid-column: 1 / -1;
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 9px 10px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            color: #475569;
            font-size: 0.78rem;
        }

        .mn-attachment-preview.is-visible {
            display: flex;
        }

        .mn-attachment-name {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .mn-attachment-remove {
            border: 0;
            background: transparent;
            color: #ff385c;
            font-size: 0.76rem;
            font-weight: 700;
            cursor: pointer;
            flex-shrink: 0;
        }

        .mn-input {
            width: 100%;
            min-height: 48px;
            max-height: 120px;
            border: 1px solid #dbe4ee;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 0.9rem;
            font-family: inherit;
            resize: vertical;
            outline: none;
            background: #fff;
            color: #0f172a;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
        }

        .mn-input:focus {
            border-color: rgba(255, 56, 92, 0.42);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .mn-send {
            min-height: 48px;
            padding: 0 18px;
            border: 1px solid transparent;
            border-radius: 14px;
            background: #ff385c;
            color: #fff;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(255, 56, 92, 0.16);
            transition: transform 0.16s ease, background 0.16s ease, box-shadow 0.16s ease;
        }

        .mn-send:hover {
            background: #e11d48;
            box-shadow: 0 12px 24px rgba(255, 56, 92, 0.2);
            transform: translateY(-1px);
        }

        .mn-send:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .mn-empty h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .mn-empty p {
            max-width: 280px;
            font-size: 0.84rem;
            line-height: 1.5;
            color: #64748b;
        }

        .mn-thread-list::-webkit-scrollbar,
        .mn-chat-body::-webkit-scrollbar {
            width: 8px;
        }

        .mn-thread-list::-webkit-scrollbar-thumb,
        .mn-chat-body::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.35);
            border-radius: 999px;
        }

        .mn-thread-list::-webkit-scrollbar-thumb:hover,
        .mn-chat-body::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 56, 92, 0.35);
        }

        @media (max-width: 1024px) {
            .mn-shell {
                grid-template-columns: 1fr;
                min-height: 0;
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

        @media (max-width: 640px) {
            .mn-shell {
                gap: 14px;
                width: 100%;
            }

            .mn-panel,
            .mn-chat {
                border-radius: 18px;
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.055);
            }

            .mn-panel-header,
            .mn-chat-header,
            .mn-chat-footer {
                padding-left: 12px;
                padding-right: 12px;
            }

            .mn-panel-header,
            .mn-chat-header {
                padding-top: 13px;
                padding-bottom: 13px;
            }

            .mn-panel-title h2 {
                font-size: 0.72rem;
                letter-spacing: 0.11em;
            }

            .mn-thread-list {
                max-height: 34vh;
                padding: 8px;
                gap: 7px;
            }

            .mn-thread {
                gap: 10px;
                padding: 11px 10px;
                border-radius: 15px;
            }

            .mn-thread-avatar {
                width: 38px;
                height: 38px;
                font-size: 0.78rem;
            }

            .mn-thread-top {
                gap: 8px;
            }

            .mn-thread-title {
                font-size: 0.86rem;
            }

            .mn-thread-time {
                font-size: 0.66rem;
            }

            .mn-thread-preview {
                font-size: 0.76rem;
            }

            .mn-thread-meta {
                margin-top: 7px;
            }

            .mn-thread-type {
                font-size: 0.62rem;
                letter-spacing: 0.06em;
            }

            .mn-chat-header-row {
                gap: 10px;
            }

            .mn-chat-header-avatar {
                width: 38px;
                height: 38px;
                font-size: 0.78rem;
            }

            .mn-chat-header h3 {
                font-size: 0.92rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mn-chat-subtitle {
                font-size: 0.74rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mn-chat-body {
                height: clamp(340px, 54dvh, 520px);
                padding: 14px 12px;
            }

            .mn-message {
                max-width: 92%;
                margin-bottom: 10px;
            }

            .mn-bubble {
                border-radius: 16px;
                padding: 10px 12px;
                font-size: 0.84rem;
                line-height: 1.5;
            }

            .mn-message-image-link {
                max-width: min(230px, 86vw);
                border-radius: 14px;
            }

            .mn-message-image {
                max-height: 260px;
            }

            .mn-message-time {
                max-width: 100%;
                font-size: 0.64rem;
                line-height: 1.4;
                word-break: break-word;
            }

            .mn-chat-footer {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .mn-form {
                grid-template-columns: 44px minmax(0, 1fr) auto;
                gap: 8px;
                align-items: end;
            }

            .mn-attach {
                min-width: 44px;
                min-height: 44px;
                width: 44px;
                height: 44px;
                border-radius: 13px;
            }

            .mn-send {
                min-height: 44px;
                padding: 0 14px;
                border-radius: 13px;
                font-size: 0.8rem;
            }

            .mn-input {
                min-height: 44px;
                max-height: 96px;
                border-radius: 13px;
                padding: 11px 12px;
                font-size: 0.84rem;
                resize: none;
            }

            .mn-attachment-preview {
                padding: 8px 9px;
                font-size: 0.74rem;
            }

            .mn-empty {
                min-height: 320px;
                padding: 22px 16px;
            }
        }

        @media (max-width: 380px) {
            .mn-panel-header,
            .mn-chat-header,
            .mn-chat-footer {
                padding-left: 10px;
                padding-right: 10px;
            }

            .mn-thread-list {
                max-height: 30vh;
                padding: 7px;
            }

            .mn-thread-avatar,
            .mn-chat-header-avatar {
                width: 34px;
                height: 34px;
            }

            .mn-chat-body {
                height: clamp(310px, 52dvh, 460px);
                padding: 12px 10px;
            }

            .mn-form {
                grid-template-columns: 40px minmax(0, 1fr) auto;
                gap: 7px;
            }

            .mn-attach {
                min-width: 40px;
                min-height: 40px;
                width: 40px;
                height: 40px;
            }

            .mn-send {
                min-height: 40px;
                padding: 0 11px;
            }

            .mn-input {
                min-height: 40px;
                padding: 10px 11px;
            }

            .mn-message {
                max-width: 94%;
            }

            .mn-message-image-link {
                max-width: min(210px, 84vw);
            }
        }
    </style>

    <div class="mn-shell">
        @php
            $resolveProfilePhotoUrl = function ($path) {
                if (empty($path)) {
                    return null;
                }

                if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                    return $path;
                }

                if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
                    return asset($path);
                }

                if (file_exists(public_path($path))) {
                    return asset($path);
                }

                if (file_exists(storage_path('app/public/' . ltrim($path, '/')))) {
                    return asset('storage/' . ltrim($path, '/'));
                }

                return asset($path);
            };
        @endphp

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
                            $previewText = $latestMessage
                                ? ($latestMessage->message ?: ($latestMessage->image_path ? 'Photo attachment' : 'No messages yet'))
                                : 'No messages yet';
                            $avatarUrl = $resolveProfilePhotoUrl($otherParticipant?->profile_photo);
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

                    $chatAvatarUrl = $resolveProfilePhotoUrl($selectedOtherParticipant?->profile_photo);

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
                            @if($message->image_url)
                                <a class="mn-message-image-link" href="{{ $message->image_url }}" target="_blank" rel="noopener noreferrer">
                                    <img class="mn-message-image" src="{{ $message->image_url }}" alt="{{ $message->image_original_name ?: 'Shared image' }}" loading="lazy">
                                </a>
                            @endif

                            @if(!empty($message->message))
                                <div class="mn-bubble">{{ $message->message }}</div>
                            @endif
                            <span class="mn-message-time">{{ $message->created_at->format('M d, h:i A') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mn-chat-footer">
                    <form id="mn-send-form" class="mn-form" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <label class="mn-attach" for="mn-image-input" aria-label="Attach image" title="Attach image">
                            +
                            <input class="mn-attach-input" type="file" name="image" id="mn-image-input" accept="image/jpeg,image/png,image/webp,image/gif">
                        </label>
                        <textarea class="mn-input" name="message" id="mn-message-input" rows="1" maxlength="5000" placeholder="Type a message..."></textarea>
                        <button class="mn-send" id="mn-send-button" type="submit">Send</button>
                        <div class="mn-attachment-preview" id="mn-attachment-preview" aria-live="polite">
                            <span class="mn-attachment-name" id="mn-attachment-name"></span>
                            <button class="mn-attachment-remove" id="mn-attachment-remove" type="button">Remove</button>
                        </div>
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
                        const imageInput = document.getElementById('mn-image-input');
                        const attachmentPreview = document.getElementById('mn-attachment-preview');
                        const attachmentName = document.getElementById('mn-attachment-name');
                        const attachmentRemove = document.getElementById('mn-attachment-remove');
                        const sendButton = document.getElementById('mn-send-button');
                        const csrfToken = sendForm.querySelector('input[name="_token"]').value;
                        const allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                        const maxImageBytes = 4 * 1024 * 1024;
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
                            const safeImageUrl = escapeHtml(message.image_url || '');
                            const safeImageName = escapeHtml(message.image_original_name || 'Shared image');
                            const imageMarkup = safeImageUrl
                                ? `<a class="mn-message-image-link" href="${safeImageUrl}" target="_blank" rel="noopener noreferrer"><img class="mn-message-image" src="${safeImageUrl}" alt="${safeImageName}" loading="lazy"></a>`
                                : '';
                            const textMarkup = safeMessage ? `<div class="mn-bubble">${safeMessage}</div>` : '';

                            return `
                                <div class="mn-message ${mineClass}" data-message-id="${message.id}" data-message-time="${messageTime}">
                                    ${imageMarkup}
                                    ${textMarkup}
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

                        const clearAttachment = () => {
                            imageInput.value = '';
                            attachmentName.textContent = '';
                            attachmentPreview.classList.remove('is-visible');
                        };

                        imageInput.addEventListener('change', function () {
                            const file = imageInput.files && imageInput.files[0] ? imageInput.files[0] : null;

                            if (!file) {
                                clearAttachment();
                                return;
                            }

                            if (!allowedImageTypes.includes(file.type)) {
                                clearAttachment();
                                alert('Please attach a JPG, PNG, WEBP, or GIF image.');
                                return;
                            }

                            if (file.size > maxImageBytes) {
                                clearAttachment();
                                alert('Please attach an image smaller than 4 MB.');
                                return;
                            }

                            attachmentName.textContent = file.name;
                            attachmentPreview.classList.add('is-visible');
                        });

                        attachmentRemove.addEventListener('click', clearAttachment);

                        sendForm.addEventListener('submit', async function (event) {
                            event.preventDefault();

                            const message = messageInput.value.trim();
                            const image = imageInput.files && imageInput.files[0] ? imageInput.files[0] : null;

                            if (!message && !image) {
                                return;
                            }

                            const formData = new FormData();
                            formData.append('message', message);

                            if (image) {
                                formData.append('image', image);
                            }

                            sendButton.disabled = true;

                            try {
                                const response = await fetch(`${window.location.origin}/${routePrefix}/conversations/${conversationId}/messages`, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: formData
                                });

                                const data = await response.json();

                                if (!response.ok) {
                                    throw new Error(data.message || 'Unable to send message.');
                                }

                                setMessages(data.messages || []);
                                messageInput.value = '';
                                clearAttachment();
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
