@props([
    'buttons' => [],
    'class' => '',
])

@if (! empty($buttons))
    <div {{ $attributes->merge(['class' => trim('flex flex-col gap-3 sm:flex-row sm:flex-wrap ' . $class)]) }}>
        @foreach ($buttons as $button)
            @php
                $label = $button['label'] ?? '';
                $url = $button['url'] ?? '#';
                $variant = $button['variant'] ?? 'primary';
                $newTab = ! empty($button['new_tab']);
            @endphp

            @if ($label)
                <x-button-link :href="$url" :variant="$variant" :external="$newTab" class="sm:min-w-60">
                    {{ $label }}
                </x-button-link>
            @endif
        @endforeach
    </div>
@endif
