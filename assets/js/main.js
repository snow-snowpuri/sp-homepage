/* ====================================================================
   SnowPuri — main.js
   Global UI (smooth anchor scroll, year stamp if needed, etc.)
   ==================================================================== */
(function () {
  'use strict';

  function initSmoothAnchors() {
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a[href^="#"]');
      if (!a) return;
      const id = a.getAttribute('href');
      if (id === '#' || id.length < 2) return;
      const target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      const headerH = document.querySelector('[data-component="nav"]')?.offsetHeight || 60;
      const top = target.getBoundingClientRect().top + window.scrollY - headerH - 8;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  }

  document.addEventListener('DOMContentLoaded', initSmoothAnchors);
})();
