@props([
    'as' => 'h2',
    'text' => '',
    'uppercase' => false,
])

{{-- Renders a heading with author newlines preserved (escaped, then nl2br) and
     an optional forced-uppercase treatment driven by a per-heading toggle.
     Tag is configurable via `as`; extra classes pass through `$attributes`. --}}
<{{ $as }} {{ $attributes->class(['uppercase' => (bool) $uppercase, 'normal-case' => ! (bool) $uppercase]) }}>{!! nl2br(e($text)) !!}</{{ $as }}>
