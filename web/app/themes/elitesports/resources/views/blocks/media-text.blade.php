@php
  $imageUrl = $image['url'] ?? '';
  $imageAlt = $image['alt'] ?? '';
  $orderImage = $imageSide === 'left' ? 'lg:order-1' : 'lg:order-2';
  $orderCopy = $imageSide === 'left' ? 'lg:order-2' : 'lg:order-1';
@endphp

<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="section-frame p-6 md:p-8 lg:p-10">
      <div class="grid items-center gap-10 lg:grid-cols-[0.92fr_1.08fr] lg:gap-16">
        <div class="flex flex-col gap-6 {{ $orderCopy }}">
          <x-section-heading :eyebrow="$eyebrow" :title="$title" :title-uppercase="$titleUppercase" :body="$body" />

          @if ($bullets)
            <ul class="flex max-w-2xl flex-col gap-3">
              @foreach ($bullets as $bullet)
                @if (! empty($bullet['text']))
                  <li class="flex items-start gap-3 font-semibold text-white/82">
                    <span class="mt-2.5 h-2.5 w-2.5 shrink-0 rounded-full bg-orange"></span>
                    <span>{{ $bullet['text'] }}</span>
                  </li>
                @endif
              @endforeach
            </ul>
          @endif

          <x-buttons :buttons="$buttons" class="mt-2" />
        </div>

        @if ($imageUrl)
          <div class="relative aspect-[16/10] w-full min-w-0 overflow-hidden rounded-[30px] border border-white/10 bg-black/24 shadow-[0_28px_70px_rgba(0,0,0,0.34)] lg:aspect-auto lg:h-[29rem] {{ $orderImage }}">
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="h-full w-full object-cover object-center" loading="lazy" decoding="async">
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
