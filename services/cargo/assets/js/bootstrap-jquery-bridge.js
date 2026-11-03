/**
 * Bootstrap 5 has no jQuery plugins; legacy Tessera pages call $.fn.modal / $.fn.carousel.
 * Load immediately after bootstrap.bundle.min.js (and jQuery).
 */
(function ($) {
  'use strict';
  if (!$ || typeof bootstrap === 'undefined') {
    return;
  }

  if (!$.fn.modal) {
    $.fn.modal = function (option) {
      return this.each(function () {
        var el = this;
        if (typeof option === 'object') {
          bootstrap.Modal.getOrCreateInstance(el, option);
          return;
        }
        var inst = bootstrap.Modal.getOrCreateInstance(el);
        if (option === 'show') {
          inst.show();
        } else if (option === 'hide') {
          inst.hide();
        } else if (option === 'toggle') {
          inst.toggle();
        } else if (option === 'dispose') {
          inst.dispose();
        }
      });
    };
  }

  if (!$.fn.carousel) {
    $.fn.carousel = function (option) {
      var args = [].slice.call(arguments, 1);
      return this.each(function () {
        var el = this;
        if (typeof option === 'object') {
          bootstrap.Carousel.getOrCreateInstance(el, option);
          return;
        }
        var inst = bootstrap.Carousel.getOrCreateInstance(el);
        if (option === 'cycle') {
          inst.cycle();
        } else if (option === 'pause') {
          inst.pause();
        } else if (option === 'next') {
          inst.next();
        } else if (option === 'prev') {
          inst.prev();
        } else if (option === 'to') {
          inst.to.apply(inst, args);
        }
      });
    };
  }

  function srCloseTopnavDropdowns(exceptDropdown) {
    var scope = document.querySelector('#page-topbar .topnav');
    if (!scope) return;
    scope.querySelectorAll('.dropdown').forEach(function (dd) {
      if (exceptDropdown && dd === exceptDropdown) return;
      dd.classList.remove('show');
      var menu = dd.querySelector(':scope > .dropdown-menu');
      var toggle = dd.querySelector('[data-bs-toggle="dropdown"], [data-toggle="dropdown"]');
      if (menu) {
        menu.classList.remove('show');
        menu.style.removeProperty('display');
      }
      if (toggle) {
        toggle.classList.remove('show');
        toggle.setAttribute('aria-expanded', 'false');
        if (bootstrap.Dropdown) {
          var inst = bootstrap.Dropdown.getInstance(toggle);
          if (inst) {
            try { inst.hide(); } catch (e) { /* ignore */ }
          }
        }
      }
    });
  }

  window.srCloseTopnavDropdowns = srCloseTopnavDropdowns;

  window.srInitTopnavDropdowns = function srInitTopnavDropdowns() {
    var scope = document.querySelector('#page-topbar .topnav');
    if (!scope) return;

    scope.querySelectorAll('[data-toggle="dropdown"]').forEach(function (el) {
      if (!el.getAttribute('data-bs-toggle')) {
        el.setAttribute('data-bs-toggle', 'dropdown');
      }
    });

    if (bootstrap.Dropdown) {
      scope.querySelectorAll('.navbar-nav > .nav-item.dropdown > [data-bs-toggle="dropdown"]').forEach(function (toggle) {
        bootstrap.Dropdown.getOrCreateInstance(toggle, { display: 'static', autoClose: true });
      });
    }

    if (!document.documentElement.dataset.srTopnavBridge) {
      document.documentElement.dataset.srTopnavBridge = '1';
      document.addEventListener('click', function (e) {
        var topnav = document.querySelector('#page-topbar .topnav');
        if (!topnav || !topnav.contains(e.target)) {
          srCloseTopnavDropdowns(null);
        }
      });
      document.addEventListener('show.bs.dropdown', function (e) {
        var open = e.target && e.target.closest ? e.target.closest('.navbar-nav > .nav-item.dropdown') : null;
        if (open) srCloseTopnavDropdowns(open);
      });
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', window.srInitTopnavDropdowns);
  } else {
    window.srInitTopnavDropdowns();
  }
})(window.jQuery);
