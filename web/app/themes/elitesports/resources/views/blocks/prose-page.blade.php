<section {{ $attributes->merge(['class' => trim("relative overflow-x-clip $paddingClasses $backgroundClass")]) }}>
  <div class="pointer-events-none absolute inset-0">
    <div class="ambient-orb ambient-orb-left"></div>
    <div class="ambient-orb ambient-orb-right"></div>
    <div class="ambient-grid"></div>
  </div>

  <div class="relative mx-auto max-w-5xl px-6 pb-20 pt-16 lg:px-8 lg:pb-28 lg:pt-24">
    <article class="privacy-policy-page">
      @if ($eyebrow)
        <p class="kicker">{{ $eyebrow }}</p>
      @endif
      @if ($title)
        <x-heading as="h1" :text="$title" :uppercase="$titleUppercase" class="mt-4 font-display" />
      @endif
      @if ($body)
        <div class="privacy-policy-content">{!! $body !!}</div>
      @endif
    </article>
  </div>
</section>
