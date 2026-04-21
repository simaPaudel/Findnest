

<?php $__env->startSection('title', 'Messages'); ?>
<?php $__env->startSection('page-title', 'Messages'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .mn-shell {
            display: grid;
            grid-template-columns: minmax(280px, 320px) minmax(0, 1fr);
            gap: 18px;
            min-height: 640px;
            max-width: 1440px;
            margin: 0 auto;
        }

        .mn-panel,
        .mn-chat {
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.97);
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.06);
            overflow: hidden;
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
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 10px;
            align-items: end;
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
            .mn-panel-header,
            .mn-chat-header,
            .mn-chat-footer {
                padding-left: 14px;
                padding-right: 14px;
            }

            .mn-thread {
                padding: 11px;
            }

            .mn-message {
                max-width: 88%;
            }

            .mn-form {
                grid-template-columns: 1fr;
            }

            .mn-send {
                width: 100%;
            }
        }
    </style>

    <div class="mn-shell">
        <?php
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
        ?>

        <aside class="mn-panel">
            <div class="mn-panel-header">
                <div class="mn-panel-title">
                    <h2>Conversations</h2>
                    <span class="mn-panel-count"><?php echo e($conversations->count()); ?></span>
                </div>
            </div>

            <?php if($conversations->isEmpty()): ?>
                <div class="mn-empty" style="min-height: 220px;">
                    <h4>No conversations yet</h4>
                    <p>Start a chat from property details using the Contact Owner button.</p>
                </div>
            <?php else: ?>
                <div class="mn-thread-list">
                    <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
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
                            $avatarUrl = $resolveProfilePhotoUrl($otherParticipant?->profile_photo);
                        ?>

                        <a href="<?php echo e(route($routePrefix . '.messages.index', ['conversation' => $conversation->id])); ?>" class="mn-thread <?php echo e((int) $conversation->id === (int) $selectedConversationId ? 'active' : ''); ?>">
                            <div class="mn-thread-avatar">
                                <?php if($avatarUrl): ?>
                                    <img src="<?php echo e($avatarUrl); ?>" alt="<?php echo e($threadTitle); ?>">
                                <?php else: ?>
                                    <?php echo e(strtoupper(substr($otherParticipant->name ?? $threadTitle, 0, 1))); ?>

                                <?php endif; ?>
                            </div>

                            <div class="mn-thread-body">
                                <div class="mn-thread-top">
                                    <p class="mn-thread-title"><?php echo e($threadTitle); ?></p>
                                    <span class="mn-thread-time"><?php echo e($latestMessage?->created_at?->format('M d')); ?></span>
                                </div>
                                <p class="mn-thread-preview"><?php echo e(\Illuminate\Support\Str::limit($previewText, 70)); ?></p>
                                <div class="mn-thread-meta">
                                    <span class="mn-thread-type"><?php echo e($threadSubtitle); ?></span>
                                    <?php if(($conversation->unread_count ?? 0) > 0): ?>
                                        <span class="mn-thread-unread"><?php echo e($conversation->unread_count > 99 ? '99+' : $conversation->unread_count); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </aside>

        <section class="mn-chat">
            <?php if($selectedConversation): ?>
                <?php
                    $selectedOtherParticipant = $selectedConversation->participants->firstWhere('user_id', '!=', $currentUserId)?->user;
                    $chatTitle = $selectedConversation->type === 'property'
                        ? ($selectedOtherParticipant->name ?? 'Conversation')
                        : ($selectedOtherParticipant->name ?? 'Roommate conversation');

                    $chatSubtitle = $selectedConversation->type === 'property'
                        ? ($selectedConversation->property->title ?? 'Property chat')
                        : 'Roommate chat';

                    $chatAvatarUrl = $resolveProfilePhotoUrl($selectedOtherParticipant?->profile_photo);

                    $lastMessageTimestamp = optional($selectedConversation->messages->last()?->created_at)->toIso8601String();
                ?>

                <div class="mn-chat-header">
                    <div class="mn-chat-header-row">
                        <div class="mn-chat-header-avatar">
                            <?php if($chatAvatarUrl): ?>
                                <img src="<?php echo e($chatAvatarUrl); ?>" alt="<?php echo e($chatTitle); ?>">
                            <?php else: ?>
                                <?php echo e(strtoupper(substr($chatTitle ?? 'C', 0, 1))); ?>

                            <?php endif; ?>
                        </div>

                        <div class="mn-chat-header-copy">
                            <h3><?php echo e($chatTitle); ?></h3>
                            <p class="mn-chat-subtitle"><?php echo e($chatSubtitle); ?></p>
                        </div>
                    </div>
                </div>

                <div class="mn-chat-body" id="mn-chat-body">
                    <?php $__currentLoopData = $selectedConversation->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isMine = (int) $message->sender_id === (int) $currentUserId;
                        ?>
                        <div class="mn-message <?php echo e($isMine ? 'mine' : 'other'); ?>" data-message-id="<?php echo e($message->id); ?>" data-message-time="<?php echo e(optional($message->created_at)->toIso8601String()); ?>">
                            <div class="mn-bubble"><?php echo e($message->message); ?></div>
                            <span class="mn-message-time"><?php echo e($message->created_at->format('M d, h:i A')); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mn-chat-footer">
                    <form id="mn-send-form" class="mn-form" autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <textarea class="mn-input" name="message" id="mn-message-input" rows="1" maxlength="5000" placeholder="Type a message..." required></textarea>
                        <button class="mn-send" id="mn-send-button" type="submit">Send</button>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const routePrefix = <?php echo json_encode($routePrefix, 15, 512) ?>;
                        const conversationId = <?php echo json_encode((int) $selectedConversation->id, 15, 512) ?>;
                        const currentUserId = <?php echo json_encode((int) $currentUserId, 15, 512) ?>;
                        const chatBody = document.getElementById('mn-chat-body');
                        const sendForm = document.getElementById('mn-send-form');
                        const messageInput = document.getElementById('mn-message-input');
                        const sendButton = document.getElementById('mn-send-button');
                        const csrfToken = sendForm.querySelector('input[name="_token"]').value;
                        let lastMessageTime = <?php echo json_encode($lastMessageTimestamp, 15, 512) ?>;

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
            <?php else: ?>
                <div class="mn-empty">
                    <h4>Select a conversation</h4>
                    <p>Open a thread from the left panel to view and send messages.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($routePrefix === 'owner' ? 'owner.layout' : 'user.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FindNest\resources\views/messages/index.blade.php ENDPATH**/ ?>