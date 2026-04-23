/**
 * Parallax Zoom-Out Effect
 * Makes elements feel like they're zooming out as you scroll
 * Creates a subtle, cinematic depth effect without scaling the entire page
 */

class ParallaxScroller {
  constructor() {
    this.elements = [];
    this.scrollY = 0;
    this.maxScrollDistance = 0;
    this.isScrolling = false;
    this.morphProgress = {};

    this.init();
    this.setupEventListeners();
  }

  init() {
    // Find all elements with parallax effect
    this.elements = Array.from(document.querySelectorAll('[data-parallax]'));

    // Calculate max scroll distance from the bottom-most parallax element
    if (this.elements.length > 0) {
      const lastElement = this.elements[this.elements.length - 1];
      const bottomPosition = lastElement.offsetTop + lastElement.offsetHeight;
      this.maxScrollDistance = bottomPosition - window.innerHeight;
    }

    // Initialize morph progress trackers
    this.elements.forEach((el) => {
      this.morphProgress[el.dataset.parallaxId || Math.random()] = 0;
    });
  }

  setupEventListeners() {
    // Passive scroll listener for performance
    window.addEventListener('scroll', () => this.onScroll(), { passive: true });

    // Reinitialize on resize
    window.addEventListener('resize', () => this.init());

    // Initial animation frame update
    this.updateFrame();
  }

  onScroll() {
    this.scrollY = window.scrollY;
    this.isScrolling = true;
  }

  updateFrame() {
    if (this.elements.length === 0) {
      requestAnimationFrame(() => this.updateFrame());
      return;
    }

    this.elements.forEach((element, index) => {
      // Get element's position relative to viewport
      const rect = element.getBoundingClientRect();
      const elementTop = rect.top;
      const elementHeight = rect.height;
      const viewportHeight = window.innerHeight;

      // Calculate element's scroll progress (0 = off-screen top, 1 = off-screen bottom)
      const elementProgress =
        (viewportHeight - elementTop) / (viewportHeight + elementHeight);

      // Clamp progress between 0 and 1
      const clampedProgress = Math.max(0, Math.min(1, elementProgress));

      // Smooth transition using easing
      const easeProgress = this.easeInOutCubic(clampedProgress);

      // Apply parallax effect
      // Scale: 1.03 → 1.0 (very subtle)
      // TranslateY: 8px → 0px (subtle vertical movement for depth)
      const scale = 1.03 - easeProgress * 0.03;
      const translateY = 8 - easeProgress * 8;

      // Apply transform
      element.style.transform = `scale(${scale}) translateY(${translateY}px)`;

      // Smooth out transition
      if (!element.classList.contains('parallax-active')) {
        element.classList.add('parallax-active');
      }
    });

    requestAnimationFrame(() => this.updateFrame());
  }

  /**
   * Cubic easing function for smooth transitions
   * Creates a natural-feeling animation
   */
  easeInOutCubic(t) {
    return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
  }
}

// Initialize parallax effect when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    new ParallaxScroller();
  });
} else {
  new ParallaxScroller();
}
