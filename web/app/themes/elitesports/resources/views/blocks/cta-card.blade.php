@php
  $imageUrl = $image['url'] ?? '';
  $imageAlt = $image['alt'] ?? '';
  $orderImage = $imageSide === 'right' ? 'lg:order-2' : 'lg:order-1';
  $orderCopy = $imageSide === 'right' ? 'lg:order-1' : 'lg:order-2';
@endphp

<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-[linear-gradient(135deg,rgba(246,140,41,0.14),rgba(124,68,188,0.18),rgba(7,7,12,0.98))] p-5 shadow-[0_34px_90px_rgba(0,0,0,0.28)] md:p-7 lg:p-8">
      <div class="pointer-events-none absolute -left-20 bottom-0 h-64 w-64 rounded-full bg-[#f68c29]/12 blur-3xl"></div>
      <div class="pointer-events-none absolute -right-16 top-0 h-72 w-72 rounded-full bg-[#7c44bc]/16 blur-3xl"></div>

      <div class="relative grid items-center gap-8 lg:grid-cols-2">
        @if ($imageUrl)
          <div class="overflow-hidden rounded-[28px] border border-white/12 bg-black/24 shadow-[0_24px_70px_rgba(0,0,0,0.34)] {{ $orderImage }}">
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
          </div>
        @endif

        <div class="px-2 py-4 md:px-4 lg:px-6 {{ $orderCopy }}">
          @if ($label)
            <p class="kicker">{{ $label }}</p>
          @endif
          @if ($title)
            <h2 class="mt-4 max-w-3xl font-display text-5xl leading-none text-white md:text-6xl">{{ $title }}</h2>
          @endif
          @if ($body)
            <div class="mt-6 max-w-2xl space-y-4 text-lg leading-8 text-white/78 [&_a]:text-[#ffd3a4] [&_a]:underline">{!! $body !!}</div>
          @endif
          <x-buttons :buttons="$buttons" class="mt-8" />
        </div>
      </div>
    </div>
  </div>
</section>
