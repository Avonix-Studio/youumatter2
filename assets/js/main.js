/**
 * youumatter2 main script.
 * Started Alpine explicitly (we use WP `strategy=defer` rather than Alpine's
 * own auto-start), and exposes a tiny Calendly helper used by Book buttons.
 */
(function () {
  'use strict';

  function start() {
    if (window.Alpine && typeof window.Alpine.start === 'function' && !window.__yum2AlpineStarted) {
      window.__yum2AlpineStarted = true;
      window.Alpine.start();
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start, { once: true });
  } else {
    start();
  }

  /**
   * Open the Calendly popup for the configured event URL.
   * Usage in markup: <a href="#" onclick="return yum2OpenCalendly(YUM2.calendlyUrl)">Book</a>
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
