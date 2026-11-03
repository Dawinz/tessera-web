/**
 * Tessera Control — slim Dashonic vertical init (avoids theme-customizer deps in app.js).
 */
(function () {
  'use strict';

  function initMetis() {
    var el = document.getElementById('side-menu');
    if (el && typeof MetisMenu !== 'undefined') {
      new MetisMenu('#side-menu');
    }
  }

  function initFeather() {
    if (window.feather) feather.replace();
  }

  function initSidebarToggle() {
    var buttons = document.querySelectorAll('.vertical-menu-btn');
    var body = document.body;
    buttons.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        body.classList.toggle('sidebar-enable');
        if (window.innerWidth >= 992) {
          var size = body.getAttribute('data-sidebar-size');
          if (!size || size === 'lg') {
            body.setAttribute('data-sidebar-size', 'sm');
          } else {
            body.setAttribute('data-sidebar-size', 'lg');
          }
        }
      });
    });
  }

  function initSlugPreview() {
    var name = document.getElementById('operator_name');
    var slug = document.getElementById('operator_slug');
    var preview = document.getElementById('slug_preview');
    if (!slug || !preview) return;

    function slugify(v) {
      return String(v || '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '')
        .slice(0, 64);
    }

    function updatePreview() {
      var s = slug.value || 'your-slug';
      preview.textContent = s + '.tessera.co.tz';
    }

    if (name && slug) {
      name.addEventListener('blur', function () {
        if (!slug.value) {
          slug.value = slugify(name.value);
          updatePreview();
        }
      });
    }
    slug.addEventListener('input', updatePreview);
    updatePreview();
  }

  document.addEventListener('DOMContentLoaded', function () {
    initMetis();
    initFeather();
    initSidebarToggle();
    initSlugPreview();
  });
})();
