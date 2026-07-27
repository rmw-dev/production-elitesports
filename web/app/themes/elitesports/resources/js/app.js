/**
 * Site header: scroll glass effect, mobile menu, and nav dropdowns.
 */
function initHeader() {
  const header = document.querySelector('[data-site-header]');

  if (!header) {
    return;
  }

  const onScroll = () => {
    const scrolled = window.scrollY > 20;
    header.classList.toggle('is-scrolled', scrolled);
    header.classList.toggle('bg-transparent', !scrolled);
  };

  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* Desktop dropdowns (hover handled in CSS; this adds click + a11y). */
  const dropdowns = header.querySelectorAll('[data-dropdown]');

  const closeDesktopDropdowns = (except) => {
    dropdowns.forEach((dropdown) => {
      if (dropdown === except) {
        return;
      }

      dropdown.querySelector('[data-dropdown-panel]')?.classList.remove('is-open');
      dropdown.querySelector('[data-dropdown-trigger]')?.setAttribute('aria-expanded', 'false');
      dropdown.querySelector('[data-dropdown-chevron]')?.classList.remove('rotate-180', 'is-active');
    });
  };

  dropdowns.forEach((dropdown) => {
    const trigger = dropdown.querySelector('[data-dropdown-trigger]');
    const panel = dropdown.querySelector('[data-dropdown-panel]');
    const chevron = dropdown.querySelector('[data-dropdown-chevron]');

    trigger?.addEventListener('click', () => {
      const isOpen = panel.classList.toggle('is-open');
      trigger.setAttribute('aria-expanded', String(isOpen));
      chevron?.classList.toggle('rotate-180', isOpen);
      chevron?.classList.toggle('is-active', isOpen);
      closeDesktopDropdowns(dropdown);
    });
  });

  document.addEventListener('click', (event) => {
    if (!event.target.closest('[data-dropdown]')) {
      closeDesktopDropdowns(null);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeDesktopDropdowns(null);
    }
  });

  /* Mobile menu. */
  const toggle = header.querySelector('[data-menu-toggle]');
  const mobileNav = header.querySelector('[data-mobile-nav]');
  const bars = header.querySelectorAll('[data-menu-bar]');

  const setMenu = (open) => {
    mobileNav?.classList.toggle('hidden', !open);
    toggle?.setAttribute('aria-expanded', String(open));

    if (bars.length === 3) {
      bars[0].classList.toggle('translate-y-2', open);
      bars[0].classList.toggle('rotate-45', open);
      bars[1].classList.toggle('opacity-0', open);
      bars[2].classList.toggle('-translate-y-2', open);
      bars[2].classList.toggle('-rotate-45', open);
    }
  };

  toggle?.addEventListener('click', () => {
    setMenu(mobileNav?.classList.contains('hidden') ?? false);
  });

  header.querySelectorAll('[data-menu-close]').forEach((link) => {
    link.addEventListener('click', () => setMenu(false));
  });

  /* Mobile dropdowns. */
  header.querySelectorAll('[data-mobile-dropdown]').forEach((dropdown) => {
    const trigger = dropdown.querySelector('[data-mobile-dropdown-trigger]');
    const panel = dropdown.querySelector('[data-mobile-dropdown-panel]');
    const chevron = dropdown.querySelector('[data-mobile-dropdown-chevron]');

    trigger?.addEventListener('click', () => {
      const isOpen = panel.classList.toggle('hidden');
      trigger.setAttribute('aria-expanded', String(!isOpen));
      chevron?.classList.toggle('rotate-180', !isOpen);
      chevron?.classList.toggle('is-active', !isOpen);
    });
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
      setMenu(false);
    }
  });
}

if (document.readyState !== 'loading') {
  initHeader();
} else {
  document.addEventListener('DOMContentLoaded', initHeader);
}

/**
 * Scroll-reveal fade-in for blocks (mirrors the React source `Reveal`
 * component). Each top-level block section fades up as it enters the
 * viewport; sections already on screen at load show immediately.
 */
function initReveal() {
  const sections = document.querySelectorAll('main section[class*="wp-block-"]');

  if (!sections.length) {
    return;
  }

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (prefersReducedMotion || !('IntersectionObserver' in window)) {
    sections.forEach((section) => section.classList.add('reveal', 'is-visible'));
    return;
  }

  const viewportHeight = window.innerHeight || document.documentElement.clientHeight;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.18 },
  );

  sections.forEach((section) => {
    section.classList.add('reveal');

    const rect = section.getBoundingClientRect();
    const isInitiallyInView = rect.top < viewportHeight && rect.bottom > 0;

    if (isInitiallyInView) {
      section.classList.add('is-visible');
      return;
    }

    section.classList.add('reveal-can-animate');
    observer.observe(section);
  });
}

if (document.readyState !== 'loading') {
  initReveal();
} else {
  document.addEventListener('DOMContentLoaded', initReveal);
}

