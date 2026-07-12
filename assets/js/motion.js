/* ====================================================================
   SnowPuri — motion.js
   Reveal on scroll + count-up animations.
   ==================================================================== */
(function () {
  'use strict';

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ── Reveal on scroll ──
  function initReveal() {
    const items = document.querySelectorAll('.reveal');
    if (!items.length) return;

    if (prefersReduced || !('IntersectionObserver' in window)) {
      items.forEach((el) => el.classList.add('is-visible'));
      return;
    }

    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target);
          }
        });
      },
      // 트리거를 후순위로: 화면에 1px만 보여도 발동, 하단 마진 없이 위로 확장.
      // threshold 0 + rootMargin '0px 0px 0px 0px' 가 가장 관대함.
      { rootMargin: '0px 0px 0px 0px', threshold: 0 }
    );

    items.forEach((el) => io.observe(el));

    // 안전망: 레이아웃/이미지 로드로 인해 viewport 안에 들어왔는데도
    // observer 콜백이 누락된 요소를 500ms 뒤 한 번 더 평가한다.
    setTimeout(() => {
      items.forEach((el) => {
        if (el.classList.contains('is-visible')) return;
        const r = el.getBoundingClientRect();
        const vh = window.innerHeight || document.documentElement.clientHeight;
        if (r.bottom > 0 && r.top < vh) {
          el.classList.add('is-visible');
          io.unobserve(el);
        }
      });
    }, 500);
  }

  // ── Count-up ──
  function formatNum(n) {
    return Math.round(n).toLocaleString('en-US');
  }

  function animateCount(el) {
    const target = parseFloat(el.dataset.count || '0');
    if (isNaN(target)) return;
    const dur = 1400;
    const start = performance.now();
    const startVal = 0;

    function step(now) {
      const t = Math.min(1, (now - start) / dur);
      // ease-out-cubic
      const eased = 1 - Math.pow(1 - t, 3);
      const val = startVal + (target - startVal) * eased;
      el.textContent = formatNum(val);
      if (t < 1) requestAnimationFrame(step);
      else el.textContent = formatNum(target);
    }

    if (prefersReduced) {
      el.textContent = formatNum(target);
      return;
    }
    requestAnimationFrame(step);
  }

  function initCounters() {
    const items = document.querySelectorAll('[data-count]');
    if (!items.length) return;

    // data-count 를 가진 요소 안의 실제 숫자 타깃을 찾는다.
    // - 자식에 [data-counter] 가 있으면 그걸 카운트업 (Hero pill 패턴)
    // - 없으면 자기 자신 (Stats / Featured stat 패턴)
    function pickTarget(host) {
      const inner = host.querySelector('[data-counter]');
      return inner || host;
    }

    if (prefersReduced || !('IntersectionObserver' in window)) {
      items.forEach((host) => animateCount(pickTarget(host)));
      return;
    }

    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            animateCount(pickTarget(entry.target));
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.4 }
    );

    items.forEach((el) => io.observe(el));
  }

  // ── Nav scroll state ──
  function initNavScroll() {
    const nav = document.querySelector('[data-component="nav"]');
    if (!nav) return;
    const onScroll = () => {
      if (window.scrollY > 12) nav.classList.add('is-scrolled');
      else nav.classList.remove('is-scrolled');
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // ── Mobile nav toggle ──
  function initNavToggle() {
    const burger = document.querySelector('[data-action="toggle-nav"]');
    const links  = document.querySelector('.nav-links');
    if (!burger || !links) return;

    burger.addEventListener('click', () => {
      const open = links.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', String(open));
    });

    links.addEventListener('click', (e) => {
      if (e.target.tagName === 'A') {
        links.classList.remove('is-open');
        burger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // ── Featured slider (5s autoplay + prev/next + hover pause) ──
  function initFeaturedSlider() {
    const root = document.querySelector('[data-featured-slider]');
    if (!root) return;
    const slides = root.querySelectorAll('.featured-slide');
    const dots   = root.querySelectorAll('.featured-slider__dot');
    const prev   = root.querySelector('[data-prev]');
    const next   = root.querySelector('[data-next]');
    if (slides.length < 2) return;

    const total = slides.length;
    let idx = 0;
    let timer = null;
    const INTERVAL = 5000;
    const reduced = prefersReduced;

    function go(n) {
      idx = ((n % total) + total) % total;
      slides.forEach((s, i) => {
        const on = i === idx;
        s.classList.toggle('is-active', on);
        s.setAttribute('aria-hidden', on ? 'false' : 'true');
      });
      dots.forEach((d, i) => {
        const on = i === idx;
        d.classList.toggle('is-active', on);
        d.setAttribute('aria-selected', String(on));
      });
    }

    function start() {
      stop();
      if (reduced) return;
      timer = setInterval(() => go(idx + 1), INTERVAL);
    }
    function stop() { if (timer) { clearInterval(timer); timer = null; } }

    if (prev) prev.addEventListener('click', () => { go(idx - 1); start(); });
    if (next) next.addEventListener('click', () => { go(idx + 1); start(); });
    dots.forEach((d) => {
      d.addEventListener('click', () => {
        go(parseInt(d.dataset.dot, 10) || 0);
        start();
      });
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    root.addEventListener('focusin',  stop);
    root.addEventListener('focusout', start);

    document.addEventListener('visibilitychange', () => {
      if (document.hidden) stop();
      else start();
    });

    start();
  }

  document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initCounters();
    initNavScroll();
    initNavToggle();
    initFeaturedSlider();
  });
})();
