@php
  $imageUrl = $image['url'] ?? '';
  $imageAlt = $image['alt'] ?? '';
@endphp

<section id="faq" {{ $attributes->merge(['class' => trim("relative scroll-mt-24 $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto grid max-w-7xl items-start gap-10 px-6 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16 lg:px-8">
    <div class="flex flex-col gap-4 lg:sticky lg:top-28">
      @if ($eyebrow)
        <p class="kicker">{{ $eyebrow }}</p>
      @endif
      @if ($title)
        <h2 class="font-display text-4xl leading-none text-white md:text-5xl">{{ $title }}</h2>
      @endif
      @if ($imageUrl)
        <div class="surface-card overflow-hidden">
          <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="h-full w-full object-cover" loading="lazy" decoding="async">
        </div>
      @endif
    </div>

    @if ($items)
      <div class="flex flex-col gap-3">
        @foreach ($items as $item)
          @if (! empty($item['question']))
            <details class="group surface-card overflow-hidden">
              <summary class="flex cursor-pointer items-start justify-between gap-5 p-6 text-white marker:content-none">
                <x-heading as="span" :text="$item['question']" :uppercase="$item['question_uppercase'] ?? false" class="text-2xl leading-tight text-white" />
                <span class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-white/14 text-orange transition group-open:rotate-45">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="h-4 w-4" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                </span>
              </summary>
              @if (! empty($item['answer']))
                <div class="prose-brand border-t border-white/10 px-6 pb-6 pt-4">{!! $item['answer'] !!}</div>
              @endif
            </details>
          @endif
        @endforeach
      </div>
    @endif
  </div>
</section>
