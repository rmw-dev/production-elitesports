<section id="form" {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-gradient-panel px-6 py-10 shadow-[0_34px_90px_rgba(0,0,0,0.28)] md:px-10 md:py-12">
      <div class="pointer-events-none absolute inset-y-0 right-0 w-1/2 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.11),transparent_60%)]"></div>

      <div class="relative grid gap-10 lg:grid-cols-[minmax(0,26rem)_minmax(0,1fr)] lg:items-start">
        <div>
          @if ($eyebrow)
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#ffd3a4]">{{ $eyebrow }}</p>
          @endif
          @if ($title)
            <x-heading as="h2" :text="$title" :uppercase="$titleUppercase" class="mt-4 max-w-2xl font-display text-5xl leading-none text-white md:text-6xl" />
          @endif
          @if ($body)
            <div class="mt-5 max-w-xl text-lg leading-8 text-white/78">{!! $body !!}</div>
          @endif
        </div>

        <div class="esa-form relative">
          @if ($formHtml)
            {!! $formHtml !!}
          @else
            <p class="text-white/60">No form selected. Choose a Contact Form 7 form in the block settings.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
