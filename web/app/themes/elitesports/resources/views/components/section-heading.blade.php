@props([
    'eyebrow' => '',
    'title' => '',
    'titleUppercase' => false,
    'body' => '',
    'align' => 'left',
    'class' => '',
])

@php
    $alignClass = $align === 'center' ? 'mx-auto text-center items-center' : '';
@endphp

<div {{ $attributes->merge(['class' => trim("flex flex-col gap-4 max-w-2xl $alignClass $class")]) }}>
    @if ($eyebrow)
        <p class="kicker">{{ $eyebrow }}</p>
    @endif

    @if ($title)
        <x-heading as="h2" :text="$title" :uppercase="$titleUppercase" class="font-display text-4xl leading-none text-white md:text-5xl" />
    @endif

    @if ($body)
        <div class="prose-brand prose-lead max-w-xl">{!! $body !!}</div>
    @endif
</div>
