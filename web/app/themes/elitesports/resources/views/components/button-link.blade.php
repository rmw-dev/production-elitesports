@props([
    'href' => '#',
    'variant' => 'primary',
    'external' => false,
])

@php
    $variants = [
        // Glass pill with animated brand-gradient border + glow.
        'primary' => 'button-link-primary text-white',
        // Subtle translucent glass pill.
        'secondary' => 'button-link-secondary text-white',
        // Solid brand-gradient fill.
        'tertiary' => 'button-link-tertiary text-white',
        // Text-only, no surface.
        'ghost' => 'button-link-ghost border-transparent bg-transparent text-white/72 hover:text-white',
    ];

    $base = 'button-link inline-flex min-h-12 max-w-full items-center justify-center gap-2 rounded-full border px-5 py-3 text-center text-sm leading-tight font-semibold tracking-[0.08em] uppercase transition duration-300 ease-out sm:whitespace-nowrap';

    $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<a
    href="{{ $href }}"
    @if ($external) target="_blank" rel="noreferrer" @endif
    {{ $attributes->merge(['class' => trim("$base $variantClass")]) }}
>
    <span class="button-link-content">{{ $slot }}</span>
</a>
