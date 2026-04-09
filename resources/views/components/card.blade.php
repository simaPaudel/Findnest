<!-- Reusable Card Component -->
<!-- Usage:
    @include('components.card', [
        'title' => 'Card Title',
        'subtitle' => 'Optional subtitle',
        'class' => 'additional-classes',
        'slot' => 'Card content goes here'
    ])

    Or with slot syntax:
    <x-card title="Settings" subtitle="Manage your account">
        Your content here
    </x-card>
-->

<style>
    .fn-card {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .fn-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .fn-card-header {
        padding: 20px;
        border-bottom: 1px solid #f3f4f6;
    }

    .fn-card-title {
        font-size: 1.125rem;
        font-weight: 650;
        color: #1f2937;
        margin: 0;
    }

    .fn-card-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 4px 0 0 0;
    }

    .fn-card-body {
        padding: 20px;
    }

    .fn-card-footer {
        padding: 16px 20px;
        border-top: 1px solid #f3f4f6;
        background-color: #fafbfc;
        border-radius: 0 0 12px 12px;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    /* Variants */
    .fn-card-ghost {
        background-color: transparent;
        border: 1px solid #e5e7eb;
        box-shadow: none;
    }

    .fn-card-ghost:hover {
        background-color: #fafbfc;
    }

    .fn-card-flat {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        box-shadow: none;
    }

    .fn-card-accent {
        border-left: 4px solid #ff385c;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .fn-card {
            border-radius: 8px;
        }

        .fn-card-header,
        .fn-card-body,
        .fn-card-footer {
            padding: 16px;
        }

        .fn-card-footer {
            flex-direction: column;
        }
    }
</style>

<div class="fn-card {{ isset($variant) ? 'fn-card-' . $variant : '' }} {{ $class ?? '' }}">
    @if(isset($title) || isset($subtitle))
    <div class="fn-card-header">
        @if(isset($title))
        <h3 class="fn-card-title">{{ $title }}</h3>
        @endif
        @if(isset($subtitle))
        <p class="fn-card-subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @endif

    @if(isset($slot) && !empty($slot))
    <div class="fn-card-body">
        {{ $slot }}
    </div>
    @elseif(isset($content))
    <div class="fn-card-body">
        {{ $content }}
    </div>
    @endif

    @if(isset($footer))
    <div class="fn-card-footer">
        {{ $footer }}
    </div>
    @endif
</div>