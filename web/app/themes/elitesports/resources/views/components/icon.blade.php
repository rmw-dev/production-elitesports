@props(['name' => '', 'class' => 'h-6 w-6'])

@php
  // Each icon: viewBox, stroke-width and the inner SVG markup, ported to match
  // the React reference's custom icon set exactly.
  $icons = [
    // --- Character framework (32x32) ---
    'shield' => ['vb' => '0 0 32 32', 'sw' => '1.8', 'p' => '<path d="M16 4.5 7 8v7.2c0 5.8 3.5 10.2 9 12.3 5.5-2.1 9-6.5 9-12.3V8l-9-3.5Z"/><path d="m12.5 16.2 2.3 2.3 5-5.5"/>'],
    'scales' => ['vb' => '0 0 32 32', 'sw' => '1.8', 'p' => '<path d="M16 7v18"/><path d="M8 11h16"/><path d="M11 11 7 19h8l-4-8Z"/><path d="M21 11 17 19h8l-4-8Z"/><path d="M12 25h8"/><path d="M10 28h12"/>'],
    'community' => ['vb' => '0 0 32 32', 'sw' => '1.8', 'p' => '<circle cx="16" cy="10" r="3.2"/><path d="M10.5 25v-2.2c0-3.2 2.2-5.4 5.5-5.4s5.5 2.2 5.5 5.4V25"/><circle cx="7.8" cy="13.2" r="2.5"/><path d="M4.2 25v-1.5c0-2.4 1.5-4.1 3.9-4.3"/><circle cx="24.2" cy="13.2" r="2.5"/><path d="M27.8 25v-1.5c0-2.4-1.5-4.1-3.9-4.3"/>'],

    // --- What comes next / future (64x64) ---
    'college' => ['vb' => '0 0 64 64', 'sw' => '2.35', 'p' => '<path d="M14 26h36"/><path d="M17 26v22"/><path d="M27 26v22"/><path d="M37 26v22"/><path d="M47 26v22"/><path d="M11 48h42"/><path d="M8 54h48"/><path d="M32 10 10 22h44L32 10Z"/><path d="M32 15v3"/>'],
    'trophy' => ['vb' => '0 0 64 64', 'sw' => '2.35', 'p' => '<path d="M22 14h20v12c0 7-4.4 12-10 12S22 33 22 26V14Z"/><path d="M22 19h-7v4c0 5 3 8 8 9"/><path d="M42 19h7v4c0 5-3 8-8 9"/><path d="M32 38v8"/><path d="M24 46h16"/><path d="M20 52h24"/><path d="m32 20 1.8 3.5 3.9.6-2.8 2.7.7 3.8-3.6-1.8-3.6 1.8.7-3.8-2.8-2.7 3.9-.6L32 20Z"/>'],
    'megaphone' => ['vb' => '0 0 64 64', 'sw' => '2.35', 'p' => '<path d="M17 36h-4a5 5 0 0 1 0-10h4"/><path d="M17 26 43 14v34L17 36V26Z"/><path d="M22 38 26 52h8l-5-11"/><path d="M49 23c2 2.1 3 5 3 8s-1 5.9-3 8"/><path d="M54 18c3.6 3.6 5.5 8.2 5.5 13S57.6 40.4 54 44"/>'],
    'sunrise' => ['vb' => '0 0 64 64', 'sw' => '2.35', 'p' => '<path d="M8 42h48"/><path d="M22 42c1.5-7 5-11 10-11s8.5 4 10 11"/><path d="M32 25v-5"/><path d="M23 28 19.5 24.5"/><path d="M41 28l3.5-3.5"/><path d="M18 54c3.8-6 7.6-9 11.5-9"/><path d="M46 54c-3.8-6-7.6-9-11.5-9"/><path d="M29.5 45 27 54"/><path d="M34.5 45 37 54"/>'],

    // --- Training pillars (24x24) ---
    'technique' => ['vb' => '0 0 24 24', 'sw' => '1.75', 'p' => '<circle cx="12" cy="12" r="7.2"/><circle cx="12" cy="12" r="2.15"/><path d="M12 4.2V7"/><path d="M12 17v2.8"/><path d="M4.2 12H7"/><path d="M17 12h2.8"/>'],
    'zap' => ['vb' => '0 0 24 24', 'sw' => '1.75', 'p' => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'],
    'heart-pulse' => ['vb' => '0 0 24 24', 'sw' => '1.75', 'p' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/>'],
    'brain' => ['vb' => '0 0 24 24', 'sw' => '1.75', 'p' => '<path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-2.04Z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24A2.5 2.5 0 0 0 14.5 2Z"/>'],
  ];

  $icon = $icons[$name] ?? null;
@endphp

@if ($icon)
  <svg {{ $attributes->merge(['class' => $class]) }} viewBox="{{ $icon['vb'] }}" fill="none" stroke="currentColor" stroke-width="{{ $icon['sw'] }}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $icon['p'] !!}
  </svg>
@endif
