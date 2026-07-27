<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="section-frame overflow-hidden px-6 py-10 md:px-10 md:py-12 lg:px-12">
      <div class="pointer-events-none absolute right-0 top-0 h-56 w-56 rounded-full bg-[#7c44bc]/16 blur-3xl"></div>
      <div class="relative grid gap-10 lg:grid-cols-[minmax(0,0.96fr)_1px_minmax(280px,0.58fr)] lg:items-center">
        <div>
          <x-section-heading :eyebrow="$eyebrow" :title="$title" :body="$body" />
          @if ($introExtra)
            <p class="mt-6 max-w-2xl text-base leading-7 text-white/68 md:text-lg">{{ $introExtra }}</p>
          @endif
          <x-buttons :buttons="$buttons" class="mt-7" />
        </div>

        <div class="hidden h-full min-h-72 bg-gradient-to-b from-transparent via-white/12 to-transparent lg:block"></div>

        <div class="surface-card relative overflow-hidden bg-[rgba(5,5,12,0.66)] p-6 shadow-[0_26px_70px_rgba(0,0,0,0.28)] backdrop-blur-md md:p-7">
          <div class="pointer-events-none absolute inset-x-6 top-0 h-px bg-gradient-to-r from-transparent via-[#f68c29]/80 to-transparent"></div>
          <div class="pointer-events-none absolute -right-14 -top-14 h-36 w-36 rounded-full bg-[#7c44bc]/24 blur-3xl"></div>
          <div class="relative">
            @if ($snapshotLabel)
              <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#ffd3a4]">{{ $snapshotLabel }}</p>
            @endif
            @if ($snapshotValue)
              <p class="mt-5 font-display text-7xl leading-none text-white md:text-8xl">{{ $snapshotValue }}</p>
            @endif
            @if ($snapshotCaption)
              <p class="mt-2 text-sm font-semibold uppercase tracking-[0.18em] text-white/58">{{ $snapshotCaption }}</p>
            @endif
            @if ($snapshotBody)
              <p class="mt-5 text-base leading-7 text-white/70">{{ $snapshotBody }}</p>
            @endif

            @if ($snapshotRows)
              <div class="mt-6 grid gap-2.5">
                @foreach ($snapshotRows as $row)
                  @if (! empty($row['text']))
                    <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.035] px-4 py-3 text-sm leading-5 text-white/76">
                      <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#f68c29]"></span>
                      <span>{{ $row['text'] }}</span>
                    </div>
                  @endif
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>

      @if ($disclaimer)
        <p class="relative mt-8 max-w-4xl border-t border-white/10 pt-5 text-sm leading-6 text-white/48">{{ $disclaimer }}</p>
      @endif
    </div>
  </div>
</section>
