<footer class="site-footer content-info">
  <div class="site-footer-inner">
    <div class="site-footer-main">
      <div class="site-footer-identity">
        <p class="site-footer-name">{{ $footerIdentity['name'] }}</p>
        @foreach ($footerIdentity['lines'] as $line)
          <p>{{ $line }}</p>
        @endforeach
      </div>

      @if ($footerNav)
        <nav class="site-footer-nav" aria-label="Footer navigation">
          @foreach ($footerNav as $group)
            <div class="site-footer-nav-group">
              <h2 class="sr-only">{{ $group['label'] }}</h2>
              <div>
                @php($links = ! empty($group['children']) ? $group['children'] : [$group])
                @foreach ($links as $link)
                  <a
                    href="{{ $link['url'] }}"
                    @if ($link['target']) target="{{ $link['target'] }}" @endif
                    @if ($link['rel']) rel="{{ $link['rel'] }}" @endif
                  >{{ $link['label'] }}</a>
                @endforeach
              </div>
            </div>
          @endforeach
        </nav>
      @endif

      @if ($socialLinks)
        <div class="site-footer-social" aria-label="Social links">
          @foreach ($socialLinks as $link)
            <a
              href="{{ $link['url'] }}"
              target="_blank"
              rel="noreferrer"
              aria-label="{{ $link['label'] }}"
              title="{{ $link['label'] }}"
            >
              <x-social-icon :type="$link['icon']" />
            </a>
          @endforeach
        </div>
      @endif
    </div>

    <div class="site-footer-legal">
      <div class="site-footer-legal-row">
        <p>{{ $footerLegal['copyright'] }}</p>
        <div class="site-footer-legal-side">
          <div class="site-footer-legal-links">
            <a href="{{ $footerLegal['terms']['url'] }}">{{ $footerLegal['terms']['label'] }}</a>
            <span aria-hidden="true">•</span>
            <a href="{{ $footerLegal['privacy']['url'] }}">{{ $footerLegal['privacy']['label'] }}</a>
          </div>
          <p class="site-footer-media-notice">
            @foreach ($footerLegal['mediaNotice'] as $line)
              <span>{{ $line }}</span>
            @endforeach
          </p>
        </div>
      </div>
    </div>
  </div>
</footer>
