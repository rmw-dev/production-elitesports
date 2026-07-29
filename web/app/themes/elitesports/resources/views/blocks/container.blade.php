<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass " . ($firstBlock ? 'pt-16 lg:pt-24' : ''))]) }}>
  <div class="gutenberg-content mx-auto max-w-7xl px-6 lg:px-8">
    @if ($boxed)
      <div class="section-frame">
        @if ($eyebrow)
          <p class="kicker mb-4">{{ $eyebrow }}</p>
        @endif
        <InnerBlocks template="{{ $block->template }}" />
      </div>
    @else
      @if ($eyebrow)
        <p class="kicker mb-4">{{ $eyebrow }}</p>
      @endif
      <InnerBlocks template="{{ $block->template }}" />
    @endif
  </div>
</section>
