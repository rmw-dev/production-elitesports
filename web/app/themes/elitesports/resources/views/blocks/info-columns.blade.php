@php
  $cols = $columns === 3 ? 'md:grid-cols-3' : ($columns === 1 ? '' : 'md:grid-cols-2');
  $isSplit = $layout === 'split';
  $cardBase = $cardStyle === 'outline'
    ? 'rounded-[30px] border border-white/10 bg-black/24 p-6 md:p-8'
    : 'surface-card p-6 md:p-8';
@endphp

<section {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="@if($isSplit) section-frame @endif">
      <div class="@if($isSplit) grid gap-10 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)] lg:items-start @endif">
        @if ($eyebrow || $title || $body || $introExtra || ! empty($buttons))
          <div>
            <x-section-heading :eyebrow="$eyebrow" :title="$title" :body="$body" />
            @if ($introExtra)
              <p class="mt-6 max-w-xl text-base leading-7 text-white/70 md:text-lg">{{ $introExtra }}</p>
            @endif
            <x-buttons :buttons="$buttons" class="mt-7" />
          </div>
        @endif

        @if ($cards)
          <div class="@if(! $isSplit && ($eyebrow || $title)) mt-10 @endif grid gap-5 {{ $cols }}">
            @foreach ($cards as $card)
              <div class="{{ $cardBase }}">
                @if (! empty($card['title']))
                  @if ($titleStyle === 'kicker')
                    <p class="kicker">{{ $card['title'] }}</p>
                  @elseif ($titleStyle === 'display')
                    <p class="font-display text-4xl uppercase leading-none text-white">{{ $card['title'] }}</p>
                  @else
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#ffd3a4]">{{ $card['title'] }}</p>
                  @endif
                @endif
                @if (! empty($card['body']))
                  <div class="mt-4 text-lg leading-8 text-white/80 [&_a]:text-[#ffd3a4] [&_a]:underline">{!! $card['body'] !!}</div>
                @endif
                @if (! empty($card['bullets']))
                  <div class="mt-6 grid gap-3">
                    @foreach ($card['bullets'] as $bullet)
                      @if (! empty($bullet['text']))
                        <div class="flex gap-3 text-base leading-7 text-white/76">
                          <span class="mt-2.5 h-2 w-2 shrink-0 rounded-full bg-[#f68c29]"></span>
                          <span>{{ $bullet['text'] }}</span>
                        </div>
                      @endif
                    @endforeach
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
