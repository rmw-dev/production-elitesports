@php
  $imageUrl = $image['url'] ?? '';
  $imageAlt = $image['alt'] ?? '';
  $sportsImage = $sports['image'] ?? [];
  $sportsImageUrl = $sportsImage['url'] ?? '';
  $sportsImageAlt = $sportsImage['alt'] ?? '';
  $sportsGroups = $sports['groups'] ?? [];

  // Hardcoded pillar visuals, matching the reference (icon + faded number + tone by index).
  $pillarVisuals = [
    ['number' => '01', 'icon' => 'technique', 'tone' => 'orange'],
    ['number' => '02', 'icon' => 'zap', 'tone' => 'purple'],
    ['number' => '03', 'icon' => 'heart-pulse', 'tone' => 'orange'],
    ['number' => '04', 'icon' => 'brain', 'tone' => 'purple'],
  ];
@endphp

<section id="training" {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="section-frame">
      <div class="grid gap-8 lg:grid-cols-2 lg:items-start">
        <div>
          @if ($eyebrow)
            <p class="kicker">{{ $eyebrow }}</p>
          @endif
          @if ($title)
            <x-heading as="h2" :text="$title" :uppercase="$titleUppercase" class="mt-4 max-w-4xl font-display text-4xl leading-none text-white md:text-5xl" />
          @endif
          @if ($body)
            <div class="training-body prose-brand prose-lead mt-5 max-w-2xl">{!! $body !!}</div>
          @endif
        </div>

        @if ($imageUrl)
          <div class="relative aspect-[1711/1536] w-full overflow-hidden rounded-[28px] border border-white/10 bg-black/18 shadow-[0_22px_56px_rgba(0,0,0,0.24)]">
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="h-full w-full object-cover object-center" loading="lazy" decoding="async">
          </div>
        @endif
      </div>

      @if ($pillars)
        <div class="mt-9 flex items-center gap-3">
          <p class="text-[0.72rem] font-semibold uppercase tracking-[0.28em] text-white/42">Performance Model</p>
        </div>

        <div class="mt-5 grid gap-5 md:grid-cols-2">
          @foreach ($pillars as $i => $pillar)
            @php
              $v = $pillarVisuals[$i] ?? $pillarVisuals[$i % 4];
              $tone = $v['tone'];
              $badge = $tone === 'purple'
                ? 'border-[#8b5cf6]/28 text-[#a78bfa] group-hover:border-[#8b5cf6]/45'
                : 'border-[#f6a65d]/32 text-[#f6a65d] group-hover:border-[#f6a65d]/50';
            @endphp
            <div class="training-pillar-card group">
              <div class="relative grid grid-cols-[auto_minmax(0,1fr)_auto] items-center gap-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border bg-white/[0.035] shadow-[inset_0_1px_0_rgba(255,255,255,0.12),0_16px_34px_rgba(0,0,0,0.2)] backdrop-blur-xl transition duration-300 group-hover:scale-[1.03] {{ $badge }}">
                  @if (! empty($pillar['icon_image']['url']))
                    <img src="{{ $pillar['icon_image']['url'] }}" alt="{{ $pillar['icon_image']['alt'] ?? '' }}" class="h-5 w-5 object-contain" loading="lazy" decoding="async">
                  @else
                    <x-icon :name="$v['icon']" class="h-5 w-5" />
                  @endif
                </span>
                @if (! empty($pillar['title']))
                  <x-heading as="h3" :text="$pillar['title']" :uppercase="$pillar['title_uppercase'] ?? false" class="font-display text-3xl leading-none text-white" />
                @endif
                <span class="font-display text-3xl leading-none text-white/18">{{ $v['number'] }}</span>
              </div>
              @if (! empty($pillar['copy']))
                <div class="relative mt-4 max-w-lg text-base leading-7 text-white/68">{!! $pillar['copy'] !!}</div>
              @endif
            </div>
          @endforeach
        </div>
      @endif

      @if ($sportsGroups)
        <div class="mt-12 border-t border-white/10 pt-10">
          <div>
            @if (! empty($sports['label']))
              <p class="kicker">{{ $sports['label'] }}</p>
            @endif
            @if (! empty($sports['title']))
              <x-heading as="h3" :text="$sports['title']" :uppercase="$sports['title_uppercase'] ?? false" class="mt-4 max-w-3xl font-display text-3xl leading-none text-white md:text-4xl" />
            @endif
          </div>

          @if ($sportsImageUrl)
            <div class="mt-7 overflow-hidden rounded-[28px] border border-white/10 bg-black/24 shadow-[0_24px_60px_rgba(0,0,0,0.26)]">
              <img src="{{ $sportsImageUrl }}" alt="{{ $sportsImageAlt }}" class="aspect-[16/9] w-full object-cover object-center" loading="lazy" decoding="async">
            </div>
          @endif

          @if (! empty($sports['intro']))
            <p class="!mt-7 text-lg font-semibold leading-7 text-white/74">{{ $sports['intro'] }}</p>
          @endif

          <div class="mt-6 grid gap-7 md:grid-cols-3">
            @foreach ($sportsGroups as $group)
              <div class="border-t border-white/10 pt-5">
                @if (! empty($group['label']))
                  <h3 class="text-[0.72rem] font-semibold uppercase tracking-[0.28em] text-[#f6a65d]/72">{{ $group['label'] }}</h3>
                @endif
                @if (! empty($group['items']))
                  <div class="mt-4 flex flex-col gap-2">
                    @foreach ($group['items'] as $sport)
                      @if (! empty($sport['name']))
                        <p class="m-0 font-display text-3xl leading-none text-white md:text-4xl">{{ $sport['name'] }}</p>
                      @endif
                    @endforeach
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</section>
