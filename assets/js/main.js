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

  function start() {
    startAlpine();
    initFeelingSwiper();
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
