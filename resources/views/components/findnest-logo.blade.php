@props([
    'variant' => 'inline',
    'size' => 'md',
])

@php
    $variant = $variant === 'stacked' ? 'stacked' : 'inline';
    $size = in_array($size, ['sm', 'md', 'lg'], true) ? $size : 'md';

    $widthPresets = [
        'sm' => [
            'inline' => '112px',
            'stacked' => '120px',
        ],
        'md' => [
            'inline' => '156px',
            'stacked' => '184px',
        ],
        'lg' => [
            'inline' => '232px',
            'stacked' => '320px',
        ],
    ];

    $width = $widthPresets[$size][$variant];
    $wrapperStyle = $variant === 'stacked'
        ? 'display:flex;align-items:center;justify-content:center;line-height:0;max-width:100%;'
        : 'display:inline-flex;align-items:center;justify-content:flex-start;line-height:0;max-width:100%;';
@endphp

<span {{ $attributes->merge(['style' => $wrapperStyle . 'width:' . $width . ';']) }}>
    <img
        src="{{ asset('images/findnest-logo.png') }}"
        alt="FindNest"
        style="display:block;width:100%;height:auto;object-fit:contain;"
    >
</span>
