/**
 * youumatter2 main script.
 * - Starts Alpine explicitly (we use WP `strategy=defer` rather than Alpine's auto-start).
 * - Exposes a tiny Calendly helper used by Book buttons.
 * - Initialises Swiper for the home "you might be feeling" carousel.
 */
(function () {
  'use strict';

  window.yum2 = window.yum2 || {};

  function startAlpine() {
    if (window.Alpine && typeof window.Alpine.start === 'function' && !window.__yum2AlpineStarted) {
      window.__yum2AlpineStarted = true;
      window.Alpine.start();
    }
  }

  function initFeelingSwiper() {
    var el = document.querySelector('.yum2-feeling-swiper');
    if (!el || !window.Swiper || window.yum2.feelingSwiper) return;

    var prev = document.querySelector('.yum2-feeling-prev');
    var next = document.querySelector('.yum2-feeling-next');
    var pagination = document.querySelector('.yum2-feeling-pagination');
    var current = document.querySelector('.yum2-feeling-current');

    var swiper = new window.Swiper(el, {
      slidesPerView: 1,
      slidesPerGroup: 1,
      spaceBetween: 16,
      breakpoints: {
        768: { slidesPerView: 2, slidesPerGroup: 2, spaceBetween: 24 },
      },
      navigation: prev && next ? { prevEl: prev, nextEl: next } : false,
      pagination: pagination ? {
        el: pagination,
        clickable: true,
        bulletClass: 'yum2-bullet',
        bulletActiveClass: 'yum2-bullet-active',
        renderBullet: function (i, cls) {
          return '<button type="button" class="' + cls + '" aria-label="Go to page ' + (i + 1) + '"></button>';
        },
      } : false,
      on: {
        slideChange: function () {
          if (current) current.textContent = String(this.realIndex + 1);
          window.dispatchEvent(new CustomEvent('yum2:feelingSlideChange', { detail: { index: this.realIndex } }));
        },
      },
    });

    window.yum2.feelingSwiper = swiper;
  }

  /**
   * Scroll progress: writes 0..1 to --yum2-scroll on <html> and toggles
   * .yum2-scrolled past 8px so the sticky header can darken its background.
   */
  function initScrollProgress() {
    var html = document.documentElement;
    var update = function () {
      var max = html.scrollHeight - window.innerHeight;
      var ratio = max > 0 ? Math.min(1, window.scrollY / max) : 0;
      html.style.setProperty('--yum2-scroll', String(ratio));
      html.classList.toggle('yum2-scrolled', window.scrollY > 8);
    };
    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
  }

  /**
   * Focus trap for the mobile drawer. Stores the previously focused
   * element, focuses the first focusable child of `panel`, and intercepts
   * Tab / Shift-Tab to cycle inside the panel until released.
   *
   * @param {HTMLElement} panel
   * @returns {() => void} release function — restores prior focus.
   */
  window.yum2.trapFocus = function (panel) {
    if (!panel) return function () {};
    var prevFocus = document.activeElement;
    var selector = 'a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])';
    var focusables = function () {
      return Array.prototype.slice
        .call(panel.querySelectorAll(selector))
        .filter(function (el) { return el.offsetParent !== null; });
    };
    var first = focusables()[0];
    if (first) first.focus();

    var onKey = function (e) {
      if (e.key !== 'Tab') return;
      var nodes = focusables();
      if (!nodes.length) return;
      var firstNode = nodes[0];
      var lastNode = nodes[nodes.length - 1];
      if (e.shiftKey && document.activeElement === firstNode) {
        e.preventDefault();
        lastNode.focus();
      } else if (!e.shiftKey && document.activeElement === lastNode) {
        e.preventDefault();
        firstNode.focus();
      }
    };
    panel.addEventListener('keydown', onKey);

    return function release() {
      panel.removeEventListener('keydown', onKey);
      if (prevFocus && typeof prevFocus.focus === 'function') {
        prevFocus.focus();
      }
    };
  };

  function start() {
    startAlpine();
    initFeelingSwiper();
    initScrollProgress();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start, { once: true });
  } else {
    start();
  }

  /**
   * Open the Calendly popup for a given event URL.
   *
   * @param {string} url Calendly event URL.
   * @returns {boolean} false so anchor clicks don't navigate.
   */
  window.yum2OpenCalendly = function (url) {
    if (window.Calendly && url) {
      var color = (window.YUM2 && window.YUM2.calendlyColor) || '1a4d2e';
      window.Calendly.initPopupWidget({
        url: url,
        prefill: {},
        utm: {},
        branding: undefined,
        color: color,
      });
    }
    return false;
  };
})();
