/**
 * landing.js — behaviour for the Basira AI landing page only.
 *
 * Sections:
 *   1. Navbar scroll effect
 *   2. Smooth-scroll for anchor links
 *   3. Stat counter animation
 *   4. Intersection-observer reveal
 */

(function () {
  'use strict';

  /* ── 1. Navbar scroll effect ────────────────────── */
  const navbar = document.getElementById('landing-navbar');

  function onScroll() {
    if (!navbar) return;
    if (window.scrollY > 20) {
      navbar.classList.add('navbar-scrolled');
    } else {
      navbar.classList.remove('navbar-scrolled');
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll(); // run once on load in case page is already scrolled

  /* ── 2. Smooth scroll for anchor links ─────────── */
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  /* ── 3. Stat counter animation ──────────────────── */
  function animateCounter(el) {
    const raw    = el.dataset.target || '0';
    const prefix = raw.startsWith('+') ? '+' : '';
    const suffix = raw.endsWith('/7') ? '/7' : (raw.endsWith('k') ? 'k' : '');
    const end    = parseInt(raw.replace(/\D/g, ''), 10);
    const duration = 1400; // ms
    const start    = performance.now();

    function step(now) {
      const progress = Math.min((now - start) / duration, 1);
      // ease-out cubic
      const eased = 1 - Math.pow(1 - progress, 3);
      const current = Math.floor(eased * end);
      el.textContent = prefix + current.toLocaleString('ar-SA') + suffix;
      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = prefix + end.toLocaleString('ar-SA') + suffix;
    }

    requestAnimationFrame(step);
  }

  /* ── 4. Intersection-observer (reveal + counters) ── */
  const revealObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      entry.target.classList.add('visible');
      revealObserver.unobserve(entry.target);
    });
  }, { threshold: 0.12 });

  const counterObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (!entry.isIntersecting) return;
      animateCounter(entry.target);
      counterObserver.unobserve(entry.target);
    });
  }, { threshold: 0.5 });

  // Observe all reveal elements
  document.querySelectorAll('.reveal').forEach(function (el) {
    revealObserver.observe(el);
  });

  // Observe stat counters
  document.querySelectorAll('[data-target]').forEach(function (el) {
    counterObserver.observe(el);
  });

}());
