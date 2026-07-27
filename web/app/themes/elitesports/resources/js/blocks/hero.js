/**
 * Hero block: ensure muted autoplay and wire the sound toggle.
 *
 * Loaded on demand via App\Blocks\BaseBlock::assets() only when the hero block
 * is present on the page.
 */
function initHero() {
  const hero = document.querySelector('[data-hero]');

  if (!hero) {
    return;
  }

  const videos = hero.querySelectorAll('[data-hero-video]');
  const soundButton = hero.querySelector('[data-hero-sound]');
  const soundLabel = hero.querySelector('[data-hero-sound-label]');
  const soundIconX = hero.querySelectorAll('[data-hero-sound-x]');
  const scrim = hero.querySelector('[data-hero-scrim]');
  const glow = hero.querySelector('[data-hero-glow]');

  const mutedLabel = soundLabel?.textContent ?? 'Play Film';
  const activeLabel = soundButton?.dataset.activeLabel ?? 'Sound Off';

  /* Some browsers block autoplay until a play() call. */
  videos.forEach((video) => {
    video.muted = true;
    const attempt = video.play();
    if (attempt && typeof attempt.catch === 'function') {
      attempt.catch(() => {});
    }
  });

  if (!soundButton) {
    return;
  }

  let soundOn = false;

  const visibleVideo = () =>
    Array.from(videos).find((video) => video.offsetParent !== null) || videos[0];

  soundButton.addEventListener('click', () => {
    soundOn = !soundOn;
    const video = visibleVideo();

    if (video) {
      video.muted = !soundOn;
      if (soundOn) {
        const attempt = video.play();
        if (attempt && typeof attempt.catch === 'function') {
          attempt.catch(() => {});
        }
      }
    }

    soundButton.setAttribute('aria-pressed', String(soundOn));
    if (soundLabel) {
      soundLabel.textContent = soundOn ? activeLabel : mutedLabel;
    }
    soundIconX.forEach((line) => line.classList.toggle('hidden', soundOn));
    scrim?.classList.toggle('opacity-0', soundOn);
    glow?.classList.toggle('opacity-0', soundOn);
  });
}

if (document.readyState !== 'loading') {
  initHero();
} else {
  document.addEventListener('DOMContentLoaded', initHero);
}
