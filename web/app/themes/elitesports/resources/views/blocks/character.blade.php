@php
  $imageUrl = $image['url'] ?? '';
  $imageAlt = $image['alt'] ?? '';
@endphp

<section id="character" {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="section-frame character-section-frame">
      @if ($imageUrl)
        <img src="{{ $imageUrl }}" alt="" aria-hidden="true" class="character-section-texture" loading="lazy" decoding="async">
      @endif

      <div class="grid gap-10 lg:grid-cols-[minmax(0,0.85fr)_minmax(0,1fr)] lg:items-start">
        <x-section-heading :eyebrow="$eyebrow" :title="$title" :body="$body" />

        @if ($framework)
          <div class="character-framework-panel">
            @if ($intro)
              <p class="character-framework-intro">{{ $intro }}</p>
            @endif
            <div class="character-framework-list">
              @foreach ($framework as $item)
                <div class="character-framework-item">
                  <span class="character-framework-icon">
                    @if (! empty($item['icon_image']['url']))
                      <img src="{{ $item['icon_image']['url'] }}" alt="{{ $item['icon_image']['alt'] ?? '' }}" class="h-8 w-8 object-contain" loading="lazy" decoding="async">
                    @else
                      <x-icon :name="$item['icon'] ?? 'shield'" class="h-8 w-8" />
                    @endif
                  </span>
                  <div>
                    @if (! empty($item['title']))
                      <h3>{{ $item['title'] }}</h3>
                    @endif
                    @if (! empty($item['copy']))
                      {!! $item['copy'] !!}
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
