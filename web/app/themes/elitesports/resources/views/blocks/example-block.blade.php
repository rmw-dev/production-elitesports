@unless ($block->preview)
  <div {{ $attributes->merge(['class' => trim("$paddingClasses $backgroundClass")]) }}>
@endunless

@if ($items)
  <ul>
    @foreach ($items as $item)
      <li>{{ $item['item'] }}</li>
    @endforeach
  </ul>
@else
  <p>{{ $block->preview ? 'Add an item...' : 'No items found!' }}</p>
@endif

<div>
  <InnerBlocks template="{{ $block->template }}" />
</div>

@unless ($block->preview)
  </div>
@endunless
