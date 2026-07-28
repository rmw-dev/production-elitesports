@php
  $imageUrl = $image['url'] ?? '';
  $imageAlt = $image['alt'] ?? '';
  $hasStats = ! empty($stats);
@endphp

<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="relative mx-auto max-w-7xl px-6 pb-14 pt-16 lg:px-8 lg:pb-20 lg:pt-24">
    <div class="section-frame overflow-hidden px-6 py-10 md:px-10 md:py-14 lg:px-12 lg:py-16">
      @if ($imageUrl)
        <img
          src="{{ $imageUrl }}"
          alt="{{ $imageAlt }}"
          aria-hidden="true"
          class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-60"
          loading="eager"
          decoding="async"
        >
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(120deg,rgba(7,7,12,0.92),rgba(7,7,12,0.55))]"></div>
      @endif

      <div class="relative grid gap-10 @if($hasStats) lg:grid-cols-[minmax(0,1.05fr)_minmax(320px,420px)] lg:items-end @endif">
        <div class="max-w-3xl">
          @if ($eyebrow)
            <p class="kicker">{{ $eyebrow }}</p>
          @endif
          @if ($title)
            <h1 class="mt-5 max-w-[12ch] text-5xl leading-[0.9] sm:max-w-4xl sm:text-7xl lg:text-8xl">{!! $title !!}</h1>
          @endif
          @if ($body)
            <p class="mt-6 max-w-2xl text-lg leading-8 text-white/74">{{ $body }}</p>
          @endif
        </div>

        @if ($hasStats)
          <div class="grid w-full gap-4 sm:grid-cols-2 lg:grid-cols-1 lg:justify-self-end">
            @foreach ($stats as $stat)
              <div class="surface-card bg-[rgba(5,5,8,0.64)] px-5 py-5 backdrop-blur-md">
                <p class="font-display text-4xl leading-none text-white sm:text-5xl lg:text-[2.65rem]">{{ $stat['value'] ?? '' }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.2em] text-white/56">{{ $stat['label'] ?? '' }}</p>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
