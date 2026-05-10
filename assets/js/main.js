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
   * Scroll progress: drives the header underline via transform: scaleX,
   * and toggles .yum2-scrolled on <html> past 8px so the sticky header
   * can darken its background.
   *
   * Uses requestAnimationFrame to coalesce scroll events and write to the
   * DOM only once per frame.
   */
  function initScrollProgress() {
    var html = document.documentElement;
    var bars = document.querySelectorAll('[data-yum2-progress]');
    var ticking = false;

    var paint = function () {
      var max = html.scrollHeight - window.innerHeight;
      var ratio = max > 0 ? Math.min(1, Math.max(0, window.scrollY / max)) : 0;
      html.classList.toggle('yum2-scrolled', window.scrollY > 8);
      for (var i = 0; i < bars.length; i++) {
        bars[i].style.transform = 'scaleX(' + ratio + ')';
      }
      ticking = false;
    };

    var schedule = function () {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(paint);
      }
    };

    paint();
    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule);
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
   * Alpine factory for the FAQ page. Holds activeGroup, query, openKey,
   * and helper methods used by template-parts/faq/* partials.
   *
   * @param {string} firstGroupId Default active group id.
   */
  window.yum2FAQ = function (firstGroupId) {
    return {
      activeGroup: firstGroupId || '',
      query: '',
      openKey: null,
      matches: function (text) {
        var s = (this.query || '').trim().toLowerCase();
        if (!s) return true;
        return ('' + text).toLowerCase().indexOf(s) !== -1;
      },
      selectGroup: function (groupId) {
        this.activeGroup = groupId;
        this.query = '';
      },
      isOpen: function (key) { return this.openKey === key; },
      toggle: function (key) { this.openKey = this.openKey === key ? null : key; },
    };
  };

  /**
   * Alpine factory for an animated count-up. Holds at 0 until the element
   * scrolls into view, then eases up to the target over ~1.8s. Respects
   * prefers-reduced-motion (snaps straight to target).
   *
   * @param {number} target Final integer to count to.
   */
  window.yum2CountUp = function (target) {
    return {
      display: 0,
      _done: false,
      init: function () {
        var self = this;
        var t = Number(target) || 0;
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduce) {
          self.display = t;
          self._done = true;
          return;
        }
        if (!('IntersectionObserver' in window)) {
          self.display = t;
          self._done = true;
          return;
        }
        var obs = new IntersectionObserver(function (entries) {
          entries.forEach(function (e) {
            if (!e.isIntersecting || self._done) return;
            self._done = true;
            var start = performance.now();
            var dur = 1800;
            var tick = function (now) {
              var p = Math.min(1, (now - start) / dur);
              var eased = 1 - Math.pow(1 - p, 3);
              self.display = Math.round(eased * t);
              if (p < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
            obs.disconnect();
          });
        }, { rootMargin: '-15% 0px' });
        obs.observe(self.$el);
      },
    };
  };

  /**
   * Alpine factory for the About-page sticky belief blocks. Adds an
   * IntersectionObserver to track when each block is centred in the
   * viewport, exposing `active` for opacity / scale animation.
   */
  window.yum2BeliefBlock = function () {
    return {
      active: false,
      init: function () {
        var self = this;
        if (!('IntersectionObserver' in window)) {
          self.active = true;
          return;
        }
        var obs = new IntersectionObserver(function (entries) {
          entries.forEach(function (e) { self.active = e.isIntersecting; });
        }, { rootMargin: '-40% 0px -40% 0px' });
        obs.observe(self.$refs.root);
      },
    };
  };

  /**
   * Alpine factory for the homepage testimonials carousel. Native CSS
   * scroll-snap drives the layout; this just tracks page state for the
   * dot/arrow controls and exposes goTo() for programmatic scrolling.
   *
   * @param {number} count Number of testimonial cards.
   */
  window.yum2TestimonialsCarousel = function (count) {
    return {
      total: Number(count) || 0,
      page: 0,
      pages: 1,
      _resize: null,
      _scroll: null,
      init: function () {
        var self = this;
        var compute = function () {
          var pv = window.matchMedia('(min-width: 768px)').matches ? 2 : 1;
          self.pages = Math.max(1, Math.ceil(self.total / pv));
          if (self.page >= self.pages) self.page = self.pages - 1;
        };
        compute();
        this._resize = compute;
        window.addEventListener('resize', this._resize);

        var el = this.$refs.scroller;
        if (el) {
          this._scroll = function () {
            var w = el.clientWidth;
            if (!w) return;
            self.page = Math.max(0, Math.min(self.pages - 1, Math.round(el.scrollLeft / w)));
          };
          el.addEventListener('scroll', this._scroll, { passive: true });
        }
      },
      goTo: function (p) {
        var el = this.$refs.scroller;
        if (!el) return;
        var w = el.clientWidth;
        var target = Math.max(0, Math.min(this.pages - 1, p));
        el.scrollTo({ left: target * w, behavior: 'smooth' });
        this.page = target;
      },
    };
  };

  /**
   * Alpine component for the desktop floating TOC pill (single-post only).
   * Tracks the current visible h2 via IntersectionObserver and the page's
   * scroll progress (0..1) for the circular ring.
   *
   * Items array is passed in from the partial via x-data="yum2FloatingToc(...)".
   */
  window.yum2FloatingToc = function (items) {
    return {
      open: false,
      current: 0,
      items: Array.isArray(items) ? items : [],
      progress: 0,
      _observer: null,
      init: function () {
        var self = this;
        this.computeProgress();
        window.addEventListener('scroll', function () { self.computeProgress(); }, { passive: true });
        window.addEventListener('resize', function () { self.computeProgress(); }, { passive: true });

        // Observe h2 ids matching our items list to update `current`.
        if ('IntersectionObserver' in window && this.items.length) {
          var headings = this.items
            .map(function (it) { return document.getElementById(it.id); })
            .filter(Boolean);
          if (!headings.length) return;
          this._observer = new IntersectionObserver(
            function (entries) {
              var visible = entries
                .filter(function (e) { return e.isIntersecting; })
                .sort(function (a, b) { return a.boundingClientRect.top - b.boundingClientRect.top; });
              if (visible[0]) {
                var idx = self.items.findIndex(function (it) { return it.id === visible[0].target.id; });
                if (idx !== -1) self.current = idx;
              }
            },
            { rootMargin: '-96px 0px -60% 0px', threshold: [0, 1] }
          );
          headings.forEach(function (h) { self._observer.observe(h); });
        }
      },
      computeProgress: function () {
        var html = document.documentElement;
        var max = html.scrollHeight - window.innerHeight;
        this.progress = max > 0 ? Math.min(1, Math.max(0, window.scrollY / max)) : 0;
      },
    };
  };

  /**
   * Copy a value to the clipboard. Reads from the data-yum2-copy attribute
   * of the triggering button, falling back to window.location.href.
   *
   * Used by template-parts/post/share.php's "Copy link" button. The Alpine
   * binding handles the visual "Copied!" toast separately.
   *
   * @param {HTMLElement} btn
   */
  window.yum2.copyLink = function (btn) {
    var value = (btn && btn.getAttribute && btn.getAttribute('data-yum2-copy')) || window.location.href;
    if (navigator && navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(value).catch(function () {});
      return;
    }
    // Legacy fallback: hidden textarea + execCommand.
    try {
      var ta = document.createElement('textarea');
      ta.value = value;
      ta.style.position = 'fixed';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    } catch (e) {}
  };

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
