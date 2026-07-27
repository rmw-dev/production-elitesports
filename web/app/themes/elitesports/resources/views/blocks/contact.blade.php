<section id="contact" {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-[linear-gradient(135deg,rgba(246,140,41,0.14),rgba(124,68,188,0.18),rgba(7,7,12,0.98))] px-6 py-10 shadow-[0_34px_90px_rgba(0,0,0,0.28)] md:px-10 md:py-12">
      <div class="pointer-events-none absolute inset-y-0 right-0 w-1/2 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.11),transparent_60%)]"></div>

      <div class="relative grid gap-10 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
        <div>
          @if ($title)
            <h2 class="max-w-3xl font-display text-5xl leading-none text-white md:text-6xl">{{ $title }}</h2>
          @endif
          @if ($body)
            <div class="mt-5 max-w-2xl text-lg leading-8 text-white/78">{!! $body !!}</div>
          @endif

          <div class="mt-8 space-y-1 text-base text-white/76">
            @if ($addressLines)
              @foreach ($addressLines as $line)
                <p>{{ $line['text'] ?? '' }}</p>
              @endforeach
            @endif
            @if ($phoneLabel)
              <a href="{{ $phoneUrl ?: '#' }}" class="block pt-3 text-white transition hover:text-[#ffd3a4]">{{ $phoneLabel }}</a>
            @endif
            @if ($emailLabel)
              <a href="{{ $emailUrl ?: '#' }}" class="block pt-2 text-white transition hover:text-[#ffd3a4]">{{ $emailLabel }}</a>
            @endif
          </div>
        </div>

        <div class="relative flex flex-col gap-3 lg:min-w-[240px]">
          <x-buttons :buttons="$buttons" class="flex-col" />
        </div>
      </div>

      @if ($note)
        <p class="relative mt-8 text-xs uppercase tracking-[0.18em] text-white/44">{{ $note }}</p>
      @endif
    </div>
  </div>
</section>
