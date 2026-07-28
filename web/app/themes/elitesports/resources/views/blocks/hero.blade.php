@php
  $posterUrl = $media['poster']['url'] ?? '';
  $posterAlt = $media['poster']['alt'] ?? '';
  $videoLandscape = $media['video_landscape'] ?? '';
  $videoPortrait = $media['video_portrait'] ?? '';
  $soundLabel = $media['sound_label'] ?? 'Play Film';
  $soundLabelActive = $media['sound_label_active'] ?? 'Sound Off';

  $cardHeading = $card['heading'] ?? '';
  $cardSupport = $card['support'] ?? '';

  $locationText = $meta['location_text'] ?? '';
  $mapUrl = $meta['map_url'] ?? '';
  $phoneLabel = $meta['phone_label'] ?? '';
  $phoneUrl = $meta['phone_url'] ?? '';
@endphp

<section
  {{ $attributes->merge(['class' => trim("hero-section relative overflow-hidden $backgroundClass")]) }}
  data-hero
>
  <div class="absolute inset-0 bg-[linear-gradient(180deg,#08080d_0%,#0c0c14_100%)]"></div>

  @if ($videoPortrait)
    <video
      class="absolute inset-0 h-full w-full object-cover object-[52%_56%] sm:hidden"
      autoplay muted loop playsinline preload="auto"
      @if ($posterUrl) poster="{{ $posterUrl }}" @endif
      data-hero-video
    >
      <source src="{{ $videoPortrait }}" type="video/mp4">
    </video>
  @endif

  @if ($videoLandscape)
    <video
      class="absolute inset-0 h-full w-full object-cover object-[54%_18%] {{ $videoPortrait ? 'hidden sm:block' : '' }}"
      autoplay muted loop playsinline preload="auto"
      @if ($posterUrl) poster="{{ $posterUrl }}" @endif
      data-hero-video
    >
      <source src="{{ $videoLandscape }}" type="video/mp4">
    </video>
  @elseif ($posterUrl)
    <img
      class="absolute inset-0 h-full w-full object-cover object-[54%_18%]"
      src="{{ $posterUrl }}"
      alt="{{ $posterAlt }}"
      loading="eager"
      decoding="async"
    >
  @endif

  {{-- Readability gradient + brand glow --}}
  <div
    class="absolute inset-0 transition duration-700 bg-[linear-gradient(180deg,rgba(5,5,8,0.3)_0%,rgba(5,5,8,0.24)_46%,rgba(5,5,8,0.34)_68%,rgba(5,5,8,0.54)_84%,rgba(5,5,8,0.86)_100%)]"
    data-hero-scrim
  ></div>
  <div
    class="absolute inset-0 transition-opacity duration-700"
    style="background:radial-gradient(circle at 8% 62%, rgba(246,140,41,0.29), transparent 34%),radial-gradient(circle at 92% 34%, rgba(114,64,173,0.35), transparent 36%),radial-gradient(circle at 50% 100%, rgba(114,64,173,0.14), transparent 42%);"
    data-hero-glow
  ></div>

  @if ($videoLandscape || $videoPortrait)
    <div class="hero-sound-control pointer-events-none absolute z-20">
      <button
        type="button"
        class="hero-sound-button pointer-events-auto inline-flex items-center gap-3 rounded-full border border-white/14 bg-black/34 px-4 py-3 text-xs font-semibold uppercase text-white/82 backdrop-blur-md transition hover:border-white/28 hover:bg-black/42 hover:text-white"
        aria-pressed="false"
        data-hero-sound
        data-active-label="{{ $soundLabelActive }}"
      >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-4 w-4" data-hero-sound-icon>
          <path d="M11 5 6 9H2v6h4l5 4V5Z" />
          <line x1="22" y1="9" x2="16" y2="15" data-hero-sound-x />
          <line x1="16" y1="9" x2="22" y2="15" data-hero-sound-x />
        </svg>
        <span data-hero-sound-label>{{ $soundLabel }}</span>
      </button>
    </div>
  @endif

  <div class="hero-shell relative z-10 mx-auto max-w-7xl">
    <div class="hero-grid w-full">
      <div class="hero-copy">
        @if ($eyebrow)
          <p class="kicker">{{ $eyebrow }}</p>
        @endif

        @if ($headline)
          <h1 class="hero-title mt-5">{{ $headline }}</h1>
        @endif

        @if ($brandLine)
          <p class="hero-brand mt-4 font-display uppercase text-white/74">{{ $brandLine }}</p>
        @endif

        @if ($subhead)
          <div class="hero-subhead prose-brand mt-6">{!! $subhead !!}</div>
        @endif

        @if ($micro)
          <p class="hero-micro mt-4 uppercase text-[#ffd3a4]">{{ $micro }}</p>
        @endif

        <x-buttons :buttons="$buttons" class="hero-ctas mt-8" />

        @if ($locationText || $phoneLabel)
          <div class="hero-meta mt-8 hidden flex-wrap items-center gap-3 text-sm text-white/72 md:flex">
            @if ($locationText)
              <a
                @if ($mapUrl) href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" @endif
                class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-black/28 px-4 py-2 transition hover:border-white/24 hover:text-white"
                aria-label="Open campus location in Google Maps"
              >
                <span class="h-2 w-2 shrink-0 rounded-full bg-[#f68c29]"></span>
                {{ $locationText }}
              </a>
            @endif
            @if ($phoneLabel)
              <a
                href="{{ $phoneUrl ?: '#' }}"
                class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-black/28 px-4 py-2 transition hover:border-white/24 hover:text-white"
              >Call {{ $phoneLabel }}</a>
            @endif
          </div>
        @endif
      </div>

      @if ($cardHeading || $cardSupport || $stats)
        <div class="hero-card">
          <div class="surface-card relative overflow-hidden p-3.5 sm:p-4 xl:p-5 2xl:p-7">
            <div class="absolute inset-x-0 top-0 h-px bg-[linear-gradient(90deg,transparent,rgba(255,255,255,0.55),transparent)]"></div>

            @if ($cardHeading)
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/48">{{ $cardHeading }}</p>
            @endif

            @if ($cardSupport)
              <div class="mt-3 text-lg leading-tight text-white sm:mt-4 text-xl">{!! $cardSupport !!}</div>
            @endif

            @if ($stats)
              <div class="mt-5 grid gap-2.5 sm:grid-cols-3 lg:mt-6 lg:grid-cols-1">
                @foreach ($stats as $stat)
                  <div class="rounded-[22px] border border-white/12 bg-black/24 px-3.5 py-2.5 lg:py-3 xl:px-4 xl:py-3.5">
                    @if (! empty($stat['value']))
                      <p class="font-display text-[2rem] uppercase leading-none text-white sm:text-[2.4rem] xl:text-3xl 2xl:text-4xl">{{ $stat['value'] }}</p>
                    @endif
                    @if (! empty($stat['label']))
                      <p class="mt-1.5 text-[0.6rem] uppercase tracking-[0.2em] text-white/56 sm:text-[0.68rem] xl:text-[0.7rem] 2xl:text-xs">{{ $stat['label'] }}</p>
                    @endif
                    @if (! empty($stat['link_label']) && ! empty($stat['link_url']))
                      <a
                        href="{{ $stat['link_url'] }}"
                        class="mt-2 inline-flex max-w-full shrink-0 items-center rounded-full border border-white/14 bg-[rgba(255,255,255,0.08)] px-2.5 py-1 text-[0.5rem] font-semibold uppercase leading-none tracking-[0.12em] text-white/72 backdrop-blur-xl transition hover:border-white/24 hover:bg-[rgba(255,255,255,0.12)] hover:text-white"
                      >{{ $stat['link_label'] }}</a>
                    @endif
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      @endif
    </div>
  </div>
</section>
