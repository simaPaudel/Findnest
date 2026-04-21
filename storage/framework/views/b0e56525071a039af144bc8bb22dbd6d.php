<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'inline',
    'size' => 'md',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'variant' => 'inline',
    'size' => 'md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
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
?>

<span <?php echo e($attributes->merge(['style' => $wrapperStyle . 'width:' . $width . ';'])); ?>>
    <img
        src="<?php echo e(asset('images/findnest-logo.png')); ?>"
        alt="FindNest"
        style="display:block;width:100%;height:auto;object-fit:contain;"
    >
</span>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/components/findnest-logo.blade.php ENDPATH**/ ?>