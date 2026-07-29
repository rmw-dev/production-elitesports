<section {{ $attributes->merge(['class' => trim("relative overflow-x-clip $paddingClasses $backgroundClass")]) }}>
  <div class="pointer-events-none absolute inset-0">
    <div class="ambient-orb ambient-orb-left"></div>
    <div class="ambient-orb ambient-orb-right"></div>
    <div class="ambient-grid"></div>
  </div>

  <div class="relative mx-auto max-w-7xl px-6 pb-14 pt-16 lg:px-8 lg:pb-20 lg:pt-24">
    <div class="coaches-hero section-frame px-6 py-10 md:px-10 md:py-14 lg:px-12 lg:py-16">
      @if ($eyebrow)
        <p class="kicker">{{ $eyebrow }}</p>
      @endif
      @if ($title)
        <x-heading as="h1" :text="$title" :uppercase="$titleUppercase" class="font-display mt-4 max-w-[7.5ch] text-5xl leading-[0.88] text-white sm:max-w-4xl sm:text-7xl lg:text-8xl" />
      @endif
      @if ($body)
        <p class="mt-6 max-w-2xl text-lg leading-8 text-white/72">{{ $body }}</p>
      @endif
    </div>

    @if ($faculty)
      <div class="faculty-list mt-10">
        @foreach ($faculty as $member)
          @php
            $isOdd = $loop->index % 2 === 1;
            $accent = $isOdd ? 'faculty-card--purple' : 'faculty-card--orange';
            $reverse = $isOdd ? 'faculty-card--reverse' : '';
            $photo = $member['photo'] ?? [];
            $photoUrl = $photo['url'] ?? '';
            $photoAlt = $photo['alt'] ?? ($member['name'] ?? '');
            $focus = $member['object_position'] ?? '50% 30%';
            $bio = trim((string) ($member['bio'] ?? ''));
          @endphp
          <article class="faculty-card surface-card {{ $accent }} {{ $reverse }}">
            @if ($photoUrl)
              <div class="faculty-portrait-wrap">
                <img
                  src="{{ $photoUrl }}"
                  alt="{{ $photoAlt }}"
                  class="faculty-portrait"
                  style="object-position: {{ $focus }};"
                  loading="lazy"
                  decoding="async"
                >
              </div>
            @endif
            <div class="min-w-0 flex-1">
              @if (! empty($member['category']))
                <p class="faculty-tag">{{ $member['category'] }}</p>
              @endif
              <x-heading as="h2" :text="$member['name'] ?? ''" :uppercase="$member['name_uppercase'] ?? false" class="font-display mt-3 text-4xl leading-[0.92] text-white sm:text-5xl" />
              @if (! empty($member['title']))
                <p class="faculty-title">{{ $member['title'] }}</p>
              @endif
              @if ($bio)
                <div class="faculty-bio" data-faculty-bio>
                  {!! $bio !!}
                </div>
                <button type="button" class="faculty-readmore" data-faculty-readmore aria-expanded="false">
                  <span data-faculty-readmore-label>Read Full Bio</span>
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="6 9 12 15 18 9" />
                  </svg>
                </button>
              @endif
            </div>
          </article>
        @endforeach
      </div>
    @endif
  </div>
</section>
