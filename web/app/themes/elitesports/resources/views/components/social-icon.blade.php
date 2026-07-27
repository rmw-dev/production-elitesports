@props(['type' => 'instagram'])

@switch($type)
    @case('instagram')
        <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
            <rect x="5.2" y="5.2" width="13.6" height="13.6" rx="4" fill="none" stroke="currentColor" stroke-width="1.7" />
            <circle cx="12" cy="12" r="3.2" fill="none" stroke="currentColor" stroke-width="1.7" />
            <circle cx="16.1" cy="7.9" r="0.9" fill="currentColor" />
        </svg>
        @break

    @case('facebook')
        <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
            <path
                d="M13.4 19v-6.2h2.1l.4-2.6h-2.5V8.6c0-.7.3-1.3 1.4-1.3H16V5.1c-.5-.1-1.4-.2-2.3-.2-2.4 0-3.8 1.4-3.8 3.9v1.4H7.7v2.6h2.2V19h3.5Z"
                fill="currentColor"
            />
        </svg>
        @break

    @default
        <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
            <rect x="4.5" y="6.8" width="15" height="10.4" rx="3" fill="none" stroke="currentColor" stroke-width="1.7" />
            <path d="m10.5 10 4 2-4 2v-4Z" fill="currentColor" />
        </svg>
@endswitch
