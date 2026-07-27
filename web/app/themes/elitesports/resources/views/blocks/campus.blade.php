@php
  $mapUrl = $map['url'] ?? '';
  $mapAlt = $map['alt'] ?? '';
  $captionTitle = $addressLines[0]['text'] ?? '';
  $captionAddress = $addressLines[1]['text'] ?? '';
@endphp

<section id="campus" {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="section-frame">
      <div class="grid gap-10 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:items-start">
        <x-section-heading :eyebrow="$eyebrow" :title="$title" :body="$body" />

        @if ($stats)
          <div class="mt-8 grid gap-4 sm:grid-cols-3">
            @foreach ($stats as $stat)
              <div class="rounded-[24px] border border-white/10 bg-black/24 px-5 py-5">
                <p class="font-display text-5xl leading-none text-white">{{ $stat['value'] ?? '' }}</p>
                <p class="mt-2 text-xs uppercase tracking-[0.2em] text-white/56">{{ $stat['label'] ?? '' }}</p>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      @if ($mapUrl)
        <div class="campus-map-card mt-10">
          <img src="{{ $mapUrl }}" alt="{{ $mapAlt }}" class="campus-map-image" loading="lazy" decoding="async">
          @if ($captionTitle || $captionAddress)
            <div class="campus-map-caption">
              @if ($captionTitle)
                <p class="campus-map-caption-title font-display">{{ $captionTitle }}</p>
              @endif
              @if ($captionAddress)
                <p class="campus-map-caption-address">{{ $captionAddress }}</p>
              @endif
            </div>
          @endif
        </div>
      @endif

      @if ($features)
        <div class="campus-amenities">
          <p class="campus-amenities-label">Campus Features</p>
          <div class="campus-amenities-grid">
            @foreach ($features as $feature)
              @if (! empty($feature['text']))
                <div class="campus-amenity">{{ $feature['text'] }}</div>
              @endif
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</section>
