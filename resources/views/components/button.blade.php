<!-- Reusable Button Component -->
<!-- Usage:
    @include('components.button', [
        'type' => 'primary',  // 'primary' or 'secondary'
        'text' => 'Click Me',
        'href' => url('/path'),  // optional - makes it a link
        'class' => 'additional-classes'  // optional
    ])

    Or in forms:
    @include('components.button', [
        'type' => 'primary',
        'text' => 'Submit',
        'isSubmit' => true
    ])
-->

<style>
    .fn-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .fn-btn-primary {
        background-color: #ff385c;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(255, 56, 92, 0.25);
        border: 1px solid #ff385c;
    }

    .fn-btn-primary:hover {
        background-color: #e11d48;
        border-color: #e11d48;
        box-shadow: 0 4px 12px rgba(255, 56, 92, 0.35);
        transform: translateY(-1px);
    }

    .fn-btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 1px 4px rgba(255, 56, 92, 0.2);
    }

    .fn-btn-primary:disabled {
        background-color: #fca5b0;
        border-color: #fca5b0;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .fn-btn-secondary {
        background-color: transparent;
        color: #ff385c;
        border: 1px solid #ff385c;
    }

    .fn-btn-secondary:hover {
        background-color: rgba(255, 56, 92, 0.05);
        border-color: #e11d48;
        color: #e11d48;
    }

    .fn-btn-secondary:active {
        background-color: rgba(255, 56, 92, 0.1);
    }

    .fn-btn-secondary:disabled {
        color: #fca5b0;
        border-color: #fca5b0;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .fn-btn-lg {
        padding: 12px 24px;
        font-size: 1rem;
    }

    .fn-btn-sm {
        padding: 8px 16px;
        font-size: 0.75rem;
    }

    .fn-btn-block {
        width: 100%;
    }

    .fn-btn-loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .fn-btn-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }

    a.fn-btn {
        text-decoration: none;
        display: inline-flex;
    }

    button.fn-btn:focus,
    a.fn-btn:focus {
        outline: 2px solid #ff385c;
        outline-offset: 2px;
    }
</style>

@if(isset($href))
<!-- Link Button -->
<a href="{{ $href }}"
    class="fn-btn fn-btn-{{ $type ?? 'primary' }} {{ isset($size) ? 'fn-btn-' . $size : '' }} {{ $class ?? '' }}"
    @if(isset($disabled) && $disabled) disabled onclick="return false;" style="cursor: not-allowed; opacity: 0.6;" @endif>
    @if(isset($icon))
    <svg class="fn-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {{ $icon }}
    </svg>
    @endif
    {{ $text ?? 'Button' }}
</a>
@else
<!-- Submit/Regular Button -->
<button type="{{ $isSubmit ? 'submit' : 'button' }}"
    class="fn-btn fn-btn-{{ $type ?? 'primary' }} {{ isset($size) ? 'fn-btn-' . $size : '' }} {{ $class ?? '' }}"
    @if(isset($disabled)) disabled @endif>
    @if(isset($icon))
    <svg class="fn-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {{ $icon }}
    </svg>
    @endif
    {{ $text ?? 'Button' }}
</button>
@endif