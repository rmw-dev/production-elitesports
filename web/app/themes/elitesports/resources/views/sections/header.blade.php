<header
  class="banner fixed inset-x-0 top-0 z-50 bg-transparent transition duration-300"
  data-site-header
>
  <div class="mx-auto flex max-w-[118rem] items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8 lg:py-4">
    <a href="{{ home_url('/#home') }}" class="flex shrink-0 items-center gap-3 sm:gap-4">
      <img
        src="{{ $logoUrl }}"
        alt="{{ $brandName }} logo"
        class="h-14 w-14 shrink-0 rounded-full border border-white/12 bg-white/6 p-1 sm:h-16 sm:w-16 sm:p-1.5 lg:h-18 lg:w-18 2xl:h-22 2xl:w-22"
      >
      <div class="hidden sm:block">
        <p
          class="font-display text-[1.05rem] uppercase leading-[0.86] tracking-[0.12em] text-white lg:text-[1.15rem] lg:leading-[0.82] lg:tracking-[0.12em] 2xl:text-[1.35rem] 2xl:tracking-[0.14em]"
          aria-label="{{ $brandName }}"
        >
          @foreach (explode(' ', $brandName) as $word)
            <span aria-hidden="true" class="block">{{ $word }}</span>
          @endforeach
        </p>
      </div>
    </a>

    @if ($primaryNav)
      <nav class="hidden flex-1 items-center justify-center gap-4 lg:flex xl:gap-5 2xl:gap-8" aria-label="Primary navigation">
        @foreach ($primaryNav as $item)
          @if (! empty($item['children']))
            <div class="site-nav-dropdown" data-dropdown>
              <button
                type="button"
                class="site-nav-dropdown-trigger"
                aria-haspopup="true"
                aria-expanded="false"
                data-dropdown-trigger
              >
                <span>{{ $item['label'] }}</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-3.5 w-3.5 transition duration-200" data-dropdown-chevron>
                  <path d="m6 9 6 6 6-6" />
                </svg>
              </button>
              <div class="site-nav-dropdown-panel" data-dropdown-panel>
                @foreach ($item['children'] as $child)
                  <a
                    href="{{ $child['url'] }}"
                    class="site-nav-dropdown-link"
                    @if ($child['target']) target="{{ $child['target'] }}" @endif
                    @if ($child['rel']) rel="{{ $child['rel'] }}" @endif
                  >{{ $child['label'] }}</a>
                @endforeach
              </div>
            </div>
          @else
            <a
              href="{{ $item['url'] }}"
              class="whitespace-nowrap rounded-full px-1 py-3 text-[0.78rem] uppercase tracking-[0.14em] text-white/72 outline-none transition hover:text-white focus-visible:text-white focus-visible:ring-2 focus-visible:ring-[#f6a65d]/55 focus-visible:ring-offset-2 focus-visible:ring-offset-[#07070c] xl:text-[0.82rem] 2xl:text-sm 2xl:tracking-[0.18em]"
              @if ($item['target']) target="{{ $item['target'] }}" @endif
              @if ($item['rel']) rel="{{ $item['rel'] }}" @endif
            >{{ $item['label'] }}</a>
          @endif
        @endforeach
      </nav>
    @endif

    <div class="hidden shrink-0 items-center gap-3 xl:ml-2 xl:flex 2xl:ml-6 2xl:gap-3.5">
      <x-button-link
        :href="$ctas['tour']['url']"
        variant="secondary"
        external
        class="h-12 px-4 text-[0.64rem] tracking-[0.12em] 2xl:px-6 2xl:text-[0.68rem] 2xl:tracking-[0.14em]"
      >{{ $ctas['tour']['label'] }}</x-button-link>
      <x-button-link
        :href="$ctas['apply']['url']"
        external
        class="h-12 px-4 text-[0.64rem] tracking-[0.12em] 2xl:px-6 2xl:text-[0.68rem] 2xl:tracking-[0.14em]"
      >{{ $ctas['apply']['label'] }}</x-button-link>
    </div>

    <button
      type="button"
      class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/12 bg-white/6 lg:hidden"
      aria-label="Toggle navigation"
      aria-expanded="false"
      aria-controls="mobile-navigation"
      data-menu-toggle
    >
      <span class="flex flex-col gap-1.5">
        <span class="h-0.5 w-5 bg-white transition" data-menu-bar></span>
        <span class="h-0.5 w-5 bg-white transition" data-menu-bar></span>
        <span class="h-0.5 w-5 bg-white transition" data-menu-bar></span>
      </span>
    </button>
  </div>

  <div
    id="mobile-navigation"
    class="hidden border-t border-white/10 bg-[rgba(10,10,16,0.95)] px-6 py-5 backdrop-blur-xl lg:hidden"
    data-mobile-nav
  >
    <div class="mx-auto flex max-w-7xl flex-col gap-4">
      @foreach ($primaryNav as $item)
        @if (! empty($item['children']))
          <div class="mobile-nav-dropdown" data-mobile-dropdown>
            <button
              type="button"
              class="mobile-nav-dropdown-trigger"
              aria-haspopup="true"
              aria-expanded="false"
              data-mobile-dropdown-trigger
            >
              <span>{{ $item['label'] }}</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-4 w-4 transition duration-200" data-mobile-dropdown-chevron>
                <path d="m6 9 6 6 6-6" />
              </svg>
            </button>
            <div class="mobile-nav-dropdown-panel hidden" data-mobile-dropdown-panel>
              @foreach ($item['children'] as $child)
                <a
                  href="{{ $child['url'] }}"
                  class="mobile-nav-dropdown-link"
                  @if ($child['target']) target="{{ $child['target'] }}" @endif
                  @if ($child['rel']) rel="{{ $child['rel'] }}" @endif
                  data-menu-close
                >{{ $child['label'] }}</a>
              @endforeach
            </div>
          </div>
        @else
          <a
            href="{{ $item['url'] }}"
            class="text-left text-sm uppercase tracking-[0.18em] text-white/72 outline-none transition hover:text-white focus-visible:text-white"
            @if ($item['target']) target="{{ $item['target'] }}" @endif
            @if ($item['rel']) rel="{{ $item['rel'] }}" @endif
            data-menu-close
          >{{ $item['label'] }}</a>
        @endif
      @endforeach

      <div class="mt-2 grid gap-3 sm:grid-cols-2">
        <x-button-link :href="$ctas['tour']['url']" variant="secondary" external>
          {{ $ctas['tour']['label'] }}
        </x-button-link>
        <x-button-link :href="$ctas['apply']['url']" external>
          {{ $ctas['apply']['label'] }}
        </x-button-link>
      </div>
    </div>
  </div>
</header>
