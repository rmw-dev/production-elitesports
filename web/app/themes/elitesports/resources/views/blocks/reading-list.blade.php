<section {{ $attributes->merge(['class' => trim("relative $paddingClasses $backgroundClass")]) }}>
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="section-frame">
      <div class="grid gap-10 lg:grid-cols-[minmax(0,0.72fr)_minmax(0,1fr)] lg:items-start">
        <x-section-heading :eyebrow="$eyebrow" :title="$title" :body="$body" />

        @if ($rows)
          <div class="grid gap-4">
            @foreach ($rows as $row)
              <div class="grid gap-3 rounded-[28px] border border-white/10 bg-black/24 p-5 md:grid-cols-[8rem_minmax(0,1fr)] md:gap-5">
                <p class="font-display text-3xl text-white/42 md:text-4xl">{{ $row['label'] ?? '' }}</p>
                <div class="grid gap-3">
                  @foreach (($row['items'] ?? []) as $item)
                    <p class="text-base leading-7 text-white/76">{{ $item['text'] ?? '' }}</p>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
