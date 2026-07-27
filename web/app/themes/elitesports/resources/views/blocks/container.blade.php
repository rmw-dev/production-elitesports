<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass " . ($firstBlock ? 'pt-36 lg:pt-44' : ''))]) }}>
  <div class="gutenberg-content mx-auto max-w-7xl px-6 lg:px-8">
    @if ($boxed)
      <div class="section-frame">
        @if ($eyebrow)
          <p class="kicker mb-6">{{ $eyebrow }}</p>
        @endif
        <InnerBlocks template="{{ $block->template }}" />
      </div>
    @else
      @if ($eyebrow)
        <p class="kicker mb-6">{{ $eyebrow }}</p>
      @endif
      <InnerBlocks template="{{ $block->template }}" />
    @endif
  </div>
</section>
