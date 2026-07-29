<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="relative overflow-hidden rounded-[36px] border border-white/10 bg-[#2d203e] px-6 py-8 shadow-[0_34px_90px_rgba(0,0,0,0.28)] md:px-10 md:py-10">
      <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(105deg,rgba(246,140,41,0.94)_0%,rgba(198,104,39,0.72)_43%,rgba(76,43,91,0.82)_100%)]"></div>
      <div class="pointer-events-none absolute inset-y-0 right-0 w-1/3 bg-[linear-gradient(90deg,transparent,rgba(45,32,62,0.9))]"></div>
      <div class="relative grid gap-6 lg:grid-cols-[auto_minmax(0,1fr)] lg:items-center lg:gap-10">
        @if ($label)
          <x-heading as="p" :text="$label" :uppercase="$labelUppercase" class="font-display text-4xl leading-none text-[#3b245f] md:text-5xl" />
        @endif
        @if ($body)
          <p class="max-w-4xl text-lg leading-8 text-white">{{ $body }}</p>
        @endif
      </div>
    </div>
  </div>
</section>
