/**
 * Faculty block: "Read Full Bio" expand/collapse.
 *
 * Loaded on demand via App\Blocks\BaseBlock::assets() only when the faculty
 * block is present. Long bios are clamped; the toggle is only revealed when the
 * bio actually overflows, so short bios show no button.
 */
function initFaculty() {
  const bios = document.querySelectorAll('[data-faculty-bio]');

  if (!bios.length) {
    return;
  }

  bios.forEach((bio) => {
    const card = bio.closest('.faculty-card');
    const button = card?.querySelector('[data-faculty-readmore]');
    const label = button?.querySelector('[data-faculty-readmore-label]');

    if (!button || !label) {
      return;
    }

    const expandedLabel = 'Show Less';
    const collapsedLabel = label.textContent || 'Read Full Bio';

    const evaluate = () => {
      /* Clamp first so we can measure the overflow accurately. */
      bio.setAttribute('data-clamped', 'true');

      const overflows = bio.scrollHeight - bio.clientHeight > 4;

      if (!overflows) {
        bio.removeAttribute('data-clamped');
        button.style.display = 'none';
        button.setAttribute('aria-expanded', 'false');
        label.textContent = collapsedLabel;
        return;
      }

      /* Preserve an already-expanded state across re-evaluation (resize). */
      if (button.getAttribute('aria-expanded') === 'true') {
        bio.removeAttribute('data-clamped');
      }

      button.style.display = 'inline-flex';
    };

    button.addEventListener('click', () => {
      const expanded = button.getAttribute('aria-expanded') === 'true';

      if (expanded) {
        bio.setAttribute('data-clamped', 'true');
        button.setAttribute('aria-expanded', 'false');
        label.textContent = collapsedLabel;
      } else {
        bio.removeAttribute('data-clamped');
        button.setAttribute('aria-expanded', 'true');
        label.textContent = expandedLabel;
      }
    });

    evaluate();

    let resizeTimer;
    window.addEventListener('resize', () => {
      window.clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(evaluate, 150);
    });
  });
}

if (document.readyState !== 'loading') {
  initFaculty();
} else {
  document.addEventListener('DOMContentLoaded', initFaculty);
}
