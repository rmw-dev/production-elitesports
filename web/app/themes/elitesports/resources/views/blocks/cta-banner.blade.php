<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-[linear-gradient(135deg,rgba(246,140,41,0.14),rgba(124,68,188,0.18),rgba(7,7,12,0.98))] px-6 py-10 shadow-[0_34px_90px_rgba(0,0,0,0.28)] md:px-10 md:py-12">
      <div class="relative grid gap-8 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
        <div>
          @if ($eyebrow)
            <p class="kicker">{{ $eyebrow }}</p>
          @endif
          @if ($title)
            <x-heading as="h2" :text="$title" :uppercase="$titleUppercase" class="mt-4 max-w-3xl text-5xl leading-none md:text-6xl" />
          @endif
          @if ($body)
            <div class="mt-5 max-w-2xl space-y-4 text-lg leading-8 text-white/78 [&_a]:text-[#ffd3a4] [&_a]:underline">{!! $body !!}</div>
          @endif
        </div>

        <x-buttons :buttons="$buttons" class="lg:min-w-[350px] !flex-col" />
      </div>
    </div>
  </div>
</section>
