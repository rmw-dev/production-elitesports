@php
  $colClass = [
    2 => 'sm:grid-cols-2',
    3 => 'sm:grid-cols-2 lg:grid-cols-3',
    4 => 'sm:grid-cols-2 lg:grid-cols-4',
  ][$columns] ?? 'sm:grid-cols-2 lg:grid-cols-4';
@endphp

<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    @if ($eyebrow || $title || $body)
      <x-section-heading :eyebrow="$eyebrow" :title="$title" :body="$body" class="mb-12" />
    @endif

    @if ($items)
      <div class="grid gap-4 {{ $colClass }}">
        @foreach ($items as $i => $item)
          @php $accent = $i % 2 === 0 ? 'orange' : 'purple'; @endphp
          <div class="surface-card flex h-full flex-col gap-4 p-6">
            @if ($showNumbers)
              <span class="font-display text-3xl leading-none text-{{ $accent }}">{{ sprintf('%02d', $i + 1) }}</span>
            @endif
            @if (! empty($item['title']))
              <h3 class="font-display text-xl tracking-tight text-white">{{ $item['title'] }}</h3>
            @endif
            @if (! empty($item['copy']))
              <div class="prose-brand text-sm">{!! $item['copy'] !!}</div>
            @endif
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>
