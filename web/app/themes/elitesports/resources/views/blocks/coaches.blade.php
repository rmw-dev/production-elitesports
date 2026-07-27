<section {{ $attributes->merge(['class' => trim("relative overflow-x-clip $paddingClasses $backgroundClass")]) }}>
  <div class="pointer-events-none absolute inset-0">
    <div class="ambient-orb ambient-orb-left"></div>
    <div class="ambient-orb ambient-orb-right"></div>
    <div class="ambient-grid"></div>
  </div>

  <div class="relative mx-auto max-w-7xl px-6 pb-14 pt-36 lg:px-8 lg:pb-20 lg:pt-44">
    <div class="coaches-hero section-frame px-6 py-10 md:px-10 md:py-14 lg:px-12 lg:py-16">
      @if ($eyebrow)
        <p class="kicker">{{ $eyebrow }}</p>
      @endif
      @if ($title)
        <h1 class="font-display mt-5 max-w-[7.5ch] text-5xl uppercase leading-[0.88] text-white sm:max-w-4xl sm:text-7xl lg:text-8xl">{!! $title !!}</h1>
      @endif
      @if ($body)
        <p class="mt-6 max-w-2xl text-lg leading-8 text-white/72">{{ $body }}</p>
      @endif
    </div>

    @if ($coaches)
      <div class="mt-10 grid gap-5 lg:gap-6">
        @foreach ($coaches as $coach)
          @php
            $accent = ($coach['accent'] ?? 'orange') === 'purple' ? 'coach-card--purple' : 'coach-card--orange';
            $photo = $coach['photo'] ?? [];
            $photoUrl = $photo['url'] ?? '';
            $photoAlt = $photo['alt'] ?? ($coach['name'] ?? '');
            $focus = $coach['object_position'] ?? '50% 28%';
          @endphp
          <article class="coach-card surface-card {{ $accent }}">
            <div class="coach-card-accent"></div>
            @if ($photoUrl)
              <div class="coach-portrait-wrap">
                <img
                  src="{{ $photoUrl }}"
                  alt="{{ $photoAlt }}"
                  class="coach-portrait"
                  style="object-position: {{ $focus }};"
                  loading="lazy"
                  decoding="async"
                >
              </div>
            @endif
            <div class="min-w-0 flex-1">
              <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <h2 class="font-display text-4xl leading-[0.92] text-white sm:text-5xl">{{ $coach['name'] ?? '' }}</h2>
                @if (! empty($coach['role']))
                  <p class="coach-role-badge">{{ $coach['role'] }}</p>
                @endif
              </div>
              @if (! empty($coach['bio']))
                <p class="mt-5 max-w-4xl text-base leading-8 text-white/74 md:text-lg">{{ $coach['bio'] }}</p>
              @endif
            </div>
          </article>
        @endforeach
      </div>
    @endif
  </div>
</section>
