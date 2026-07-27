@php
  $imageUrl = $image['url'] ?? '';
  $imageAlt = $image['alt'] ?? '';
@endphp

<section id="model" {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="section-frame">
      <div class="why-esa-grid">
        <div class="why-esa-copy-column">
          <div>
            @if ($eyebrow)
              <p class="kicker">{{ $eyebrow }}</p>
            @endif
            @if ($title)
              <h2 class="mt-4 max-w-4xl font-display text-4xl leading-none text-white md:text-5xl">{{ $title }}</h2>
            @endif
          </div>

          @if ($body)
            <div class="why-esa-body">{!! $body !!}</div>
          @endif

          @if ($payoff)
            <div class="why-esa-payoff-wrap">
              <p class="why-esa-payoff font-display">
                @foreach ($payoff as $line)
                  @if (! empty($line['text']))
                    @php
                      $c = $line['color'] ?? 'white';
                      $colorClass = $c === 'orange' ? 'text-[#ff9a3d]' : ($c === 'purple' ? 'text-[#8b5cf6]' : 'text-white');
                    @endphp
                    <span class="block {{ $colorClass }}">{{ $line['text'] }}</span>
                  @endif
                @endforeach
              </p>
            </div>
          @endif
        </div>

        @if ($imageUrl)
          <div class="relative mx-auto aspect-[941/1314] w-full max-w-[32rem] overflow-hidden rounded-[30px] border border-white/10 bg-black/24 shadow-[0_28px_70px_rgba(0,0,0,0.34)] lg:justify-self-end">
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="h-full w-full object-cover object-center" loading="lazy" decoding="async">
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
