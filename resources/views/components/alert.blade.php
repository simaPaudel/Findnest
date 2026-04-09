<!-- Reusable Alert Component -->
<!-- Usage:
    @include('components.alert', [
        'type' => 'success',  // 'success', 'error', 'warning', 'info'
        'message' => 'Operation completed successfully!',
        'dismissible' => true  // optional - adds close button
    ])
-->

<style>
    .fn-alert {
        padding: 16px 20px;
        border-radius: 8px;
        border-left: 4px solid;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 16px;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fn-alert-icon {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        margin-top: 2px;
    }

    .fn-alert-content {
        flex: 1;
    }

    .fn-alert-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .fn-alert-close:hover {
        opacity: 1;
    }

    /* Success */
    .fn-alert-success {
        background-color: #dcfce7;
        border-color: #22c55e;
        color: #166534;
    }

    .fn-alert-success .fn-alert-icon {
        fill: currentColor;
    }

    /* Error */
    .fn-alert-error {
        background-color: #fee2e2;
        border-color: #ef4444;
        color: #7f1d1d;
    }

    .fn-alert-error .fn-alert-icon {
        fill: currentColor;
    }

    /* Warning */
    .fn-alert-warning {
        background-color: #fef3c7;
        border-color: #eab308;
        color: #78350f;
    }

    .fn-alert-warning .fn-alert-icon {
        fill: currentColor;
    }

    /* Info */
    .fn-alert-info {
        background-color: #dbeafe;
        border-color: #3b82f6;
        color: #1e3a8a;
    }

    .fn-alert-info .fn-alert-icon {
        fill: currentColor;
    }

    .fn-alert-title {
        font-weight: 600;
        margin-bottom: 4px;
    }

    .fn-alert-message {
        font-size: 0.875rem;
        line-height: 1.5;
    }
</style>

<div class="fn-alert fn-alert-{{ $type ?? 'info' }}" role="alert">
    <svg class="fn-alert-icon" viewBox="0 0 24 24">
        @if(($type ?? 'info') === 'success')
        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
        @elseif(($type ?? 'info') === 'error')
        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
        @elseif(($type ?? 'info') === 'warning')
        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z" />
        @else
        <!-- info -->
        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
        @endif
    </svg>
    <div class="fn-alert-content">
        @if(isset($title))
        <div class="fn-alert-title">{{ $title }}</div>
        @endif
        <div class="fn-alert-message">{{ $message ?? 'Alert message' }}</div>
    </div>
    @if(isset($dismissible) && $dismissible)
    <button class="fn-alert-close" onclick="this.parentElement.remove();" aria-label="Close alert">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    @endif
</div>