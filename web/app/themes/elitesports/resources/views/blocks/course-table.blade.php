@php
  $count = max(count($columns), 1);
@endphp

<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="surface-card overflow-hidden p-0">
      @if ($eyebrow || $title)
        <div class="px-6 pt-7 md:px-8">
          @if ($eyebrow)
            <p class="kicker">{{ $eyebrow }}</p>
          @endif
          @if ($title)
            <x-heading as="h2" :text="$title" :uppercase="$titleUppercase" class="mt-4 font-display text-4xl leading-none text-white md:text-6xl" />
          @endif
        </div>
      @endif

      @if ($columns)
        <div class="mt-6 overflow-x-auto">
          <div class="grid min-w-[860px]" style="grid-template-columns: repeat({{ $count }}, minmax(0, 1fr));">
            @foreach ($columns as $column)
              <div class="border-l border-white/8 first:border-l-0">
                <div class="bg-white/6 px-5 py-4">
                  <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#ffd3a4]">{{ $column['heading'] ?? '' }}</p>
                </div>
                @foreach (($column['items'] ?? []) as $item)
                  <div class="min-h-16 border-b border-white/8 px-5 py-4 text-base leading-7 text-white/78">{{ $item['text'] ?? '' }}</div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</section>
