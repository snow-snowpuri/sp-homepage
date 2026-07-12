/* ====================================================================
   SnowPuri — services.js
   Service grid category filter.
   ==================================================================== */
(function () {
  'use strict';

  function initFilter() {
    const root = document.querySelector('[data-component="filter"]');
    if (!root) return;

    const chips = root.querySelectorAll('.chip');
    const hosts = document.querySelectorAll('[data-filter-host]');

    function setActive(filter) {
      chips.forEach((c) => {
        const on = c.dataset.filter === filter;
        c.classList.toggle('is-active', on);
        c.setAttribute('aria-selected', String(on));
      });
    }

    function applyFilter(filter) {
      let visible = 0;
      hosts.forEach((host) => {
        const cat = host.dataset.category;
        const match = filter === 'all' || cat === filter;
        if (match) {
          host.removeAttribute('hidden');
          host.style.display = '';
          visible++;
        } else {
          // 부드러운 fade-out
          host.style.transition = 'opacity 200ms ease, transform 200ms ease';
          host.style.opacity = '0';
          host.style.transform = 'translateY(8px)';
          setTimeout(() => {
            host.setAttribute('hidden', '');
            host.style.opacity = '';
            host.style.transform = '';
          }, 180);
        }
      });
      setActive(filter);
    }

    root.addEventListener('click', (e) => {
      const chip = e.target.closest('.chip');
      if (!chip) return;
      applyFilter(chip.dataset.filter || 'all');
    });

    // 키보드 접근성 (←/→)
    root.addEventListener('keydown', (e) => {
      if (!['ArrowLeft', 'ArrowRight'].includes(e.key)) return;
      const current = root.querySelector('.chip.is-active');
      if (!current) return;
      const list = Array.from(chips);
      const i = list.indexOf(current);
      const next = e.key === 'ArrowRight'
        ? list[(i + 1) % list.length]
        : list[(i - 1 + list.length) % list.length];
      next.focus();
      applyFilter(next.dataset.filter || 'all');
    });
  }

  document.addEventListener('DOMContentLoaded', initFilter);
})();
