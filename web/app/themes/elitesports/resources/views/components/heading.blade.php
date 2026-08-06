@props([
    'as' => 'h2',
    'text' => '',
    'uppercase' => false,
])

@php
    $isUppercase = filter_var($uppercase, FILTER_VALIDATE_BOOLEAN);
    $existingStyle = trim((string) $attributes->get('style'));
    $textTransform = 'text-transform: ' . ($isUppercase ? 'uppercase' : 'none') . ';';
    $style = $existingStyle !== '' ? rtrim($existingStyle, ';') . '; ' . $textTransform : $textTransform;
@endphp

{{-- Renders a heading with author newlines preserved (escaped, then nl2br) and
     an optional forced-uppercase treatment driven by a per-heading toggle.
     Tag is configurable via `as`; extra classes pass through `$attributes`. --}}
<{{ $as }} {{ $attributes->class(['uppercase' => $isUppercase, 'normal-case' => ! $isUppercase])->merge(['style' => $style]) }}>{!! nl2br(e($text)) !!}</{{ $as }}>
