/**
 * TesseraTour — role-aware driver.js guidelines for operator console + Control.
 * Expects window.TesseraTourConfig = { module, role, roleLabel, assetBase, autoStart }
 */
(function (global) {
  'use strict';

  var STORAGE_ENABLED = 'tessera.tour.enabled';
  var packs = Object.create(null);
  var activeDriver = null;

  function cfg() {
    return global.TesseraTourConfig || {};
  }

  function roleKey() {
    return String(cfg().role || 'tickets_agent').toLowerCase();
  }

  function moduleKey() {
    return String(cfg().module || 'tickets').toLowerCase();
  }

  function doneKey() {
    return 'tessera.tour.done.' + moduleKey() + '.' + roleKey();
  }

  function isEnabled() {
    try {
      var v = localStorage.getItem(STORAGE_ENABLED);
      if (v === null || v === '') return false;
      return v === '1' || v === 'true';
    } catch (e) {
      return false;
    }
  }

  function setEnabled(on) {
    try {
      localStorage.setItem(STORAGE_ENABLED, on ? '1' : '0');
    } catch (e) { /* private mode */ }
    syncUi();
  }

  function isDone() {
    try {
      return localStorage.getItem(doneKey()) === '1';
    } catch (e) {
      return false;
    }
  }

  function markDone() {
    try {
      localStorage.setItem(doneKey(), '1');
    } catch (e) { /* ignore */ }
  }

  function clearDone() {
    try {
      localStorage.removeItem(doneKey());
    } catch (e) { /* ignore */ }
  }

  function register(name, factory) {
    packs[name] = factory;
  }

  function roleMatches(stepRoles) {
    if (!stepRoles || !stepRoles.length) return true;
    if (stepRoles.indexOf('*') !== -1) return true;
    var r = roleKey();
    for (var i = 0; i < stepRoles.length; i++) {
      if (String(stepRoles[i]).toLowerCase() === r) return true;
    }
    return false;
  }

  /** Open Bootstrap dropdown that contains el so tour can highlight menu items. */
  function revealDropdownFor(el) {
    if (!el || !el.closest) return;
    var dropdown = el.closest('.dropdown');
    if (!dropdown) return;
    var toggle = dropdown.querySelector('[data-bs-toggle="dropdown"], [data-toggle="dropdown"]');
    var menu = dropdown.querySelector('.dropdown-menu');
    dropdown.classList.add('show');
    if (menu) {
      menu.classList.add('show');
      menu.style.display = 'block';
    }
    if (toggle) {
      toggle.setAttribute('aria-expanded', 'true');
      toggle.classList.add('show');
    }
  }

  function resolveSteps(raw) {
    var out = [];
    (raw || []).forEach(function (step) {
      if (!roleMatches(step.roles)) return;
      var sel = step.element;
      if (sel && typeof sel === 'string' && sel.charAt(0) !== '#' && sel.charAt(0) !== '.' && sel.indexOf('[') === -1) {
        sel = '[data-tour="' + sel + '"]';
      }
      if (sel && !document.querySelector(sel)) {
        return; // skip missing anchors so tours stay resilient
      }
      var built = {
        element: sel || undefined,
        popover: Object.assign(
          {
            side: step.side || 'bottom',
            align: step.align || 'start',
          },
          step.popover || {}
        ),
      };
      if (step.revealDropdown && sel) {
        built.onHighlightStarted = function (el) {
          revealDropdownFor(el);
          if (typeof step.onHighlightStarted === 'function') {
            step.onHighlightStarted(el);
          }
        };
      } else if (typeof step.onHighlightStarted === 'function') {
        built.onHighlightStarted = step.onHighlightStarted;
      }
      out.push(built);
    });
    return out;
  }

  function getDriverFactory() {
    if (global.driver && global.driver.js && typeof global.driver.js.driver === 'function') {
      return global.driver.js.driver;
    }
    return null;
  }

  function destroyActive() {
    if (activeDriver && typeof activeDriver.destroy === 'function') {
      try { activeDriver.destroy(); } catch (e) { /* ignore */ }
    }
    activeDriver = null;
  }

  function start(packName, options) {
    options = options || {};
    var factory = getDriverFactory();
    if (!factory) {
      console.warn('TesseraTour: driver.js not loaded');
      return;
    }
    var packFactory = packs[packName || moduleKey()];
    if (typeof packFactory !== 'function') {
      console.warn('TesseraTour: missing pack', packName || moduleKey());
      return;
    }
    var steps = resolveSteps(packFactory(cfg()));
    if (!steps.length) {
      console.warn('TesseraTour: no steps for role', roleKey());
      return;
    }
    destroyActive();
    activeDriver = factory({
      showProgress: true,
      animate: true,
      allowClose: true,
      overlayOpacity: 0.62,
      stagePadding: 8,
      stageRadius: 10,
      popoverClass: 'tessera-tour-popover',
      nextBtnText: 'Next',
      prevBtnText: 'Back',
      doneBtnText: 'Done',
      progressText: '{{current}} of {{total}}',
      onDestroyed: function () {
        markDone();
        activeDriver = null;
      },
      steps: steps,
    });
    if (options.resetDone) clearDone();
    activeDriver.drive();
  }

  function syncUi() {
    var on = isEnabled();
    document.querySelectorAll('[data-tour-toggle-label]').forEach(function (el) {
      el.textContent = on ? 'Guidelines: On' : 'Guidelines: Off';
    });
    document.querySelectorAll('[data-tour-toggle-dot]').forEach(function (el) {
      el.classList.toggle('is-on', on);
    });
    var fab = document.getElementById('tessera-tour-fab');
    if (fab) fab.classList.toggle('is-visible', on);
  }

  function toggleEnabled() {
    setEnabled(!isEnabled());
  }

  function ensureFab() {
    if (document.getElementById('tessera-tour-fab')) return;
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.id = 'tessera-tour-fab';
    btn.setAttribute('aria-label', 'Start guided tour');
    btn.innerHTML = '<span aria-hidden="true">◎</span><span>Tour</span>';
    btn.addEventListener('click', function () {
      start(moduleKey(), { resetDone: true });
    });
    document.body.appendChild(btn);
  }

  function bindChrome() {
    document.querySelectorAll('[data-tour-action="toggle"]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        toggleEnabled();
      });
    });
    document.querySelectorAll('[data-tour-action="start"]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        setEnabled(true);
        start(moduleKey(), { resetDone: true });
      });
    });
  }

  function autoStart() {
    var c = cfg();
    if (c.autoStart === false) return;
    if (!isEnabled()) return;
    if (isDone()) return;
    setTimeout(function () {
      start(moduleKey());
    }, 700);
  }

  function boot() {
    ensureFab();
    bindChrome();
    syncUi();
    autoStart();
  }

  global.TesseraTour = {
    register: register,
    start: start,
    isEnabled: isEnabled,
    setEnabled: setEnabled,
    toggleEnabled: toggleEnabled,
    syncUi: syncUi,
    boot: boot,
    roleKey: roleKey,
    moduleKey: moduleKey,
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})(window);
