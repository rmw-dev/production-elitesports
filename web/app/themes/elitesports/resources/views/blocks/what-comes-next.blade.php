@php
  $imageUrl = $image['url'] ?? '';
  $imageAlt = $image['alt'] ?? '';
@endphp

<section id="future" {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="section-frame">
      <div class="grid gap-8 xl:grid-cols-[minmax(0,1.08fr)_minmax(28rem,1.32fr)] xl:items-start">
        <x-section-heading :eyebrow="$eyebrow" :title="$title" :title-uppercase="$titleUppercase" :body="$body" class="future-intro" />

        @if ($imageUrl)
          <div class="future-feature-image">
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="h-full w-full object-contain object-center" loading="lazy" decoding="async">
          </div>
        @endif
      </div>

      @if ($items)
        <div class="future-card-grid mt-10">
          @foreach ($items as $item)
            <div class="future-card group">
              <div class="future-card-header">
                @if (! empty($item['icon_image']['url']))
                  <img src="{{ $item['icon_image']['url'] }}" alt="{{ $item['icon_image']['alt'] ?? '' }}" class="future-card-icon object-contain" loading="lazy" decoding="async">
                @else
                  <x-icon :name="$item['icon'] ?? 'college'" class="future-card-icon" />
                @endif
                @if (! empty($item['title']))
                  <x-heading as="h3" :text="$item['title']" :uppercase="$item['title_uppercase'] ?? false" class="text-3xl leading-none text-white" />
                @endif
              </div>
              @if (! empty($item['copy']))
                <div class="relative mt-4 text-base leading-7 text-white/68">{!! $item['copy'] !!}</div>
              @endif
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</section>
