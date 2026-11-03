/**
 * Tessera operator shell - Tickets | Cargo | Accounts without full document reload.
 */
(function () {
  'use strict';

  var cfg = window.__TESSERA_OPERATOR_SHELL__;
  if (!cfg) return;

  /**
   * SPA-injected page scripts often wait for DOMContentLoaded, which already fired.
   * Run those listeners on the next microtask so schedule/calendar/inits still boot.
   */
  (function patchDomContentLoadedForSpaScripts() {
    try {
      if (document.__srSpaDomReadyPatched) return;
      var orig = document.addEventListener.bind(document);
      document.addEventListener = function (type, listener, options) {
        if (
          type === 'DOMContentLoaded' &&
          typeof listener === 'function' &&
          document.readyState !== 'loading'
        ) {
          Promise.resolve().then(function () {
            try {
              listener.call(document, new Event('DOMContentLoaded'));
            } catch (e) { /* page script error surfaces via console */ }
          });
          return;
        }
        return orig(type, listener, options);
      };
      document.__srSpaDomReadyPatched = true;
    } catch (e) { /* ignore */ }
  })();

  /** Legacy scripts use `foo.php + location.search`; strip shell router params on /operator/app.php. */
  (function patchShellLocationSearch() {
    try {
      var proto = window.Location && window.Location.prototype;
      if (!proto || proto.__srShellSearchPatched) return;
      var desc = Object.getOwnPropertyDescriptor(proto, 'search');
      if (!desc || typeof desc.get !== 'function') return;
      var origGet = desc.get;
      Object.defineProperty(proto, 'search', {
        get: function () {
          var raw = origGet.call(this);
          if (this.pathname !== '/operator/app.php') return raw;
          var u = new URL(this.href);
          ['module', 'page', 'main_page', 'spa'].forEach(function (k) {
            u.searchParams.delete(k);
          });
          return u.search;
        },
        set: desc.set,
        configurable: true,
        enumerable: desc.enumerable
      });
      proto.__srShellSearchPatched = true;
    } catch (e) { /* ignore */ }
  })();

  var viewport = document.getElementById('sr-spa-viewport');
  var mount = document.getElementById('sr-spa-mount');
  var topnavMount = document.getElementById('topnav-menu-content');
  var loading = false;
  var pendingNav = null;
  var navAbort = null;
  var currentModule = cfg.module || (cfg.initial && cfg.initial.module) || 'tickets';
  var lastTopnavModule = null;
  var currentViewKey = '';
  /** Soft cache so revisiting a page paints instantly (no spinner / PHP round-trip). */
  var VIEW_CACHE_TTL_MS = 120000;
  var VIEW_CACHE_MAX = 16;
  var viewCache = Object.create(null);
  var viewCacheOrder = [];

  function viewCacheKey(module, page, query) {
    var parts = [module || '', page || ''];
    Object.keys(query || {})
      .sort()
      .forEach(function (k) {
        if (k === 'spa' || k === 'full' || k === '_v' || k === '_') return;
        parts.push(k + '=' + query[k]);
      });
    return parts.join('|');
  }

  function viewCacheGet(key) {
    var hit = viewCache[key];
    if (!hit) return null;
    if (Date.now() - hit.at > VIEW_CACHE_TTL_MS) {
      delete viewCache[key];
      viewCacheOrder = viewCacheOrder.filter(function (k) { return k !== key; });
      return null;
    }
    return hit.payload;
  }

  function viewCacheSet(key, payload) {
    if (!key || !payload || payload.error) return;
    /* Store a shallow clone so later mutations don't corrupt the cache. */
    var copy;
    try {
      copy = JSON.parse(JSON.stringify(payload));
    } catch (e) {
      copy = payload;
    }
    if (!viewCache[key]) {
      viewCacheOrder.push(key);
      while (viewCacheOrder.length > VIEW_CACHE_MAX) {
        var evict = viewCacheOrder.shift();
        delete viewCache[evict];
      }
    }
    viewCache[key] = { at: Date.now(), payload: copy };
  }

  /** Shell already ships Dashonic core CSS - do not re-inject duplicates. */
  function isShellCoreStylesheet(href) {
    if (!href) return true;
    return /bootstrap\.min\.css|icons\.min\.css|app\.min\.css|sr-platform-shell\.css|tessera-operator-shell\.css|tessera-accounts-layout\.css|sweetalert2|flatpickr\.min\.css|select2\.min\.css|jquery-ui\.min\.css/i.test(href);
  }

  /**
   * Apply page stylesheets/inline CSS into <head>.
   * Previous bug: dumping <link> tags inside <style id="sr-spa-page-styles"> - browsers ignore them,
   * so Dashonic page CSS (home-dashboard, schedule, datatables, ...) never applied.
   */
  function applyPageStyles(html) {
    document.querySelectorAll('[data-sr-spa-style]').forEach(function (el) {
      el.parentNode && el.parentNode.removeChild(el);
    });
    if (!html) return;
    var box = document.createElement('div');
    box.innerHTML = html;
    Array.prototype.slice.call(box.querySelectorAll('link[rel="stylesheet"], style')).forEach(function (node) {
      if (node.tagName === 'LINK') {
        var href = node.getAttribute('href') || '';
        if (isShellCoreStylesheet(href)) return;
        var abs = href;
        try {
          abs = new URL(href, window.location.origin).href;
        } catch (e) { /* keep href */ }
        if (
          Array.prototype.some.call(document.querySelectorAll('link[rel="stylesheet"]'), function (l) {
            return l.href === abs || l.getAttribute('href') === href;
          })
        ) {
          return;
        }
      }
      node.setAttribute('data-sr-spa-style', '1');
      document.head.appendChild(node);
    });
  }

  var modulePrefixes = {
    tickets: '/tickets/',
    cargo: '/cargo/',
    accounts: '/accounts/'
  };
  var activeModulePrefix = modulePrefixes[currentModule] || '/tickets/';

  /** Rewrite only same-origin legacy PHP paths; never strip CDN hosts or /operator assets. */
  function rewriteLegacyUrl(raw) {
    if (!raw || typeof raw !== 'string') return raw;
    try {
      var prefix = activeModulePrefix;
      var moduleBase = window.location.origin + prefix;
      var u = new URL(raw, moduleBase);
      if (u.origin !== window.location.origin) {
        return u.href;
      }
      if (/^\/operator\/(js|css)\//.test(u.pathname) || u.pathname === '/operator/spa.php' || u.pathname === '/operator/app.php') {
        return u.pathname + u.search + u.hash;
      }
      if (u.pathname.indexOf('/operator/') === 0 && /\.php$/i.test(u.pathname)) {
        u.pathname = prefix.replace(/\/$/, '') + u.pathname.slice('/operator'.length);
      } else if (/^\/[^/]+\.php$/i.test(u.pathname)) {
        u = new URL(u.pathname.replace(/^\//, '') + u.search + u.hash, moduleBase);
      }
      ['module', 'page', 'main_page', 'spa'].forEach(function (k) {
        u.searchParams.delete(k);
      });
      return u.pathname + u.search + u.hash;
    } catch (e) {
      return raw;
    }
  }

  function patchLegacyAjax(module) {
    activeModulePrefix = modulePrefixes[module] || '/tickets/';
    if (!window.jQuery || !window.jQuery.ajaxPrefilter) return;
    var $ = window.jQuery;
    if (!$.ajaxPrefilter.__srOperatorShell) {
      $.ajaxPrefilter.__srOperatorShell = true;
      $.ajaxPrefilter(function (options) {
        if (!options.url || typeof options.url !== 'string') return;
        options.url = rewriteLegacyUrl(options.url);
      });
    }
    if ($.fn.load && !$.fn.load.__srOperatorShell) {
      var origLoad = $.fn.load;
      $.fn.load = function (url) {
        if (typeof url === 'string') {
          arguments[0] = rewriteLegacyUrl(url);
        }
        return origLoad.apply(this, arguments);
      };
      $.fn.load.__srOperatorShell = true;
    }
  }

  function moduleFromPathname(pathname) {
    if (pathname.indexOf('/tickets/') === 0) return 'tickets';
    if (pathname.indexOf('/cargo/') === 0) return 'cargo';
    if (pathname.indexOf('/accounts/') === 0) return 'accounts';
    return null;
  }

  function parseOperatorTarget(href) {
    try {
      if (!href || href === '#' || href.charAt(0) === '#') return null;
      var u = new URL(href, window.location.origin);
      if (u.origin !== window.location.origin) return null;

      if (u.pathname === '/operator/app.php' || u.pathname === '/operator/app.php/') {
        var mod = u.searchParams.get('module') || 'tickets';
        var page = u.searchParams.get('page') || '';
        var q = {};
        u.searchParams.forEach(function (v, k) {
          if (k !== 'module' && k !== 'page' && k !== 'spa') q[k] = v;
        });
        return { module: mod, page: page, query: q };
      }

      var mod2 = moduleFromPathname(u.pathname);
      if (!mod2) return null;

      var base = u.pathname.replace(new RegExp('^' + modulePrefixes[mod2]), '');
      if (base === '' || base === 'app.php') {
        return { module: mod2, page: defaultPage(mod2), query: {} };
      }
      if (!/\.php$/i.test(base)) return null;
      var leaf = base.split('/').pop();
      if (leaf === 'spa.php' || leaf === 'index.php') return null;

      var q2 = {};
      u.searchParams.forEach(function (v, k) {
        if (k !== 'spa' && k !== 'full') q2[k] = v;
      });
      return { module: mod2, page: leaf, query: q2 };
    } catch (e) {
      return null;
    }
  }

  function defaultPage(module) {
    if (module === 'accounts') return 'manifest.php';
    return 'home.php';
  }

  function spaFetchUrl(module, page, query) {
    var u = new URL(cfg.spaUrl || '/operator/spa.php', window.location.origin);
    u.searchParams.set('module', module);
    u.searchParams.set('page', page);
    u.searchParams.set('spa', '1');
    Object.keys(query || {}).forEach(function (k) {
      u.searchParams.set(k, query[k]);
    });
    return u.toString();
  }

  /** Vendor/libs load once. Page boots (home, vehicle manager, tables) must re-run each visit. */
  function isLibraryScript(src) {
    if (!src) return false;
    return /\/assets\/libs\/|\/vendors\/|apexcharts|jquery\.dataTables|datatables\.net|dataTables\.|select2|flatpickr|sweetalert|feather\.min|moment\.min|chart\.umd|jquery-ui|bootstrap\.bundle|app\.min\.js/i.test(
      src
    );
  }

  function scriptAlreadyLoaded(src) {
    if (!src) return false;
    if (!isLibraryScript(src)) return false;
    try {
      var u = new URL(src, window.location.origin).href;
      return Array.prototype.some.call(document.querySelectorAll('script[src]'), function (el) {
        try {
          return new URL(el.getAttribute('src'), window.location.origin).href === u;
        } catch (e) {
          return false;
        }
      });
    } catch (e) {
      return false;
    }
  }

  function clearPageBootScripts() {
    var root = mount || document.body;
    if (!root) return;
    root.querySelectorAll('script[data-sr-spa-boot="1"]').forEach(function (el) {
      if (el.parentNode) el.parentNode.removeChild(el);
    });
  }

  function runScripts(scripts) {
    var list = scripts || [];
    var chain = Promise.resolve();
    clearPageBootScripts();

    // Preserve original page order (src and inline interleaved). Separating them
    // broke boots that assign window.SR_* then call helpers defined in prior srcs.
    list.forEach(function (s) {
      if (s.src) {
        chain = chain.then(function () {
          return new Promise(function (resolve) {
            var src = s.src;
            if (src && src.indexOf('//') === 0 && src.indexOf('://') === -1) {
              src = 'https:' + src;
            }
            if (scriptAlreadyLoaded(src)) {
              resolve();
              return;
            }
            var el = document.createElement('script');
            el.src = src;
            if (!isLibraryScript(src)) {
              el.setAttribute('data-sr-spa-boot', '1');
            }
            el.onload = function () { resolve(); };
            el.onerror = function () { resolve(); };
            (mount || document.body).appendChild(el);
          });
        });
        return;
      }
      if (s.inline) {
        chain = chain.then(function () {
          return new Promise(function (resolve) {
            try {
              var inline = document.createElement('script');
              inline.setAttribute('data-sr-spa-boot', '1');
              inline.text = s.inline;
              (mount || document.body).appendChild(inline);
            } catch (e) { /* ignore */ }
            resolve();
          });
        });
      }
    });

    return chain;
  }

  function setDocumentBase(module) {
    var baseEl = document.getElementById('sr-spa-base');
    if (!baseEl) {
      baseEl = document.createElement('base');
      baseEl.id = 'sr-spa-base';
      document.head.insertBefore(baseEl, document.head.firstChild);
    }
    baseEl.setAttribute('href', modulePrefixes[module] || '/tickets/');
  }

  function setModulePillsActive(module) {
    document.querySelectorAll('#page-topbar .sr-module-link').forEach(function (a) {
      var t = parseOperatorTarget(a.href);
      var isActive = t && t.module === module;
      a.classList.toggle('is-active', isActive);
      a.classList.toggle('active', isActive);
    });
    document.body.dataset.srModule = module;
  }

  function setNavActive(mainPage, pagePath) {
    var nav = document.querySelector('#page-topbar .topnav .navbar-nav');
    if (!nav) return;
    nav.querySelectorAll(':scope > .nav-item').forEach(function (li) {
      li.classList.remove('active', 'sr-nav-current');
      var link = li.querySelector(':scope > .nav-link');
      if (link) link.classList.remove('active');
    });
    nav.querySelectorAll('.dropdown-item').forEach(function (a) {
      a.classList.remove('active');
    });

    var targetName = mainPage || '';
    if (targetName) {
      nav.querySelectorAll(':scope > .nav-item').forEach(function (li) {
        var label = li.querySelector(':scope > .nav-link span[data-key]');
        if (label && label.textContent.trim() === targetName) {
          li.classList.add('active', 'sr-nav-current');
        }
      });
    }
    if (pagePath) {
      nav.querySelectorAll(':scope > .nav-item > .nav-link[href], :scope > .nav-item .dropdown-item[href]').forEach(function (a) {
        try {
          var raw = a.getAttribute('href') || '';
          if (!raw || raw === '#' || raw.charAt(0) === '#') return;
          var p = parseOperatorTarget(a.href);
          if (p && p.page === pagePath) {
            if (a.classList.contains('dropdown-item')) {
              a.classList.add('active');
            }
            var li = a.closest('.navbar-nav > .nav-item');
            if (li) {
              li.classList.add('active', 'sr-nav-current');
            }
          }
        } catch (e) { /* ignore */ }
      });
    }
  }

  function applyPayload(payload, pushUrl) {
    if (!viewport || !payload) return Promise.resolve();

    var nextModule = payload.module || currentModule;
    var sameModule = nextModule === currentModule && lastTopnavModule === nextModule;
    currentModule = nextModule;
    setDocumentBase(currentModule);
    patchLegacyAjax(currentModule);

    /* Same-module page switches keep the ops topnav - only refresh active states.
       Rebuilding the whole nav on every revisit was a big source of “heavy” feel. */
    if (topnavMount && payload.topnav_html && (!sameModule || !topnavMount.children.length)) {
      if (window.bootstrap && bootstrap.Dropdown) {
        topnavMount.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function (toggle) {
          var inst = bootstrap.Dropdown.getInstance(toggle);
          if (inst) {
            try { inst.dispose(); } catch (e) { /* ignore */ }
          }
        });
      }
      topnavMount.innerHTML = payload.topnav_html;
      lastTopnavModule = currentModule;
    }

    viewport.innerHTML = payload.html || '';
    applyPageStyles(payload.styles || '');
    document.title = payload.title || document.title;
    setModulePillsActive(currentModule);
    setNavActive(payload.main_page || '', payload.path || '');

    if (window.feather && typeof feather.replace === 'function') {
      feather.replace();
    }
    if (typeof window.srInitAccountsDatepickers === 'function') {
      window.srInitAccountsDatepickers(viewport);
    }
    if (typeof window.srCleanupOffcanvasBackdrop === 'function') {
      window.srCleanupOffcanvasBackdrop();
    }
    if (typeof window.srInitTopnavDropdowns === 'function') {
      window.srInitTopnavDropdowns();
    }
    if (typeof window.srTopnavExclusiveDropdowns === 'function') {
      window.srTopnavExclusiveDropdowns();
    }
    if (typeof window.srCloseOpsNavDropdowns === 'function') {
      window.srCloseOpsNavDropdowns(null);
    } else if (typeof window.srCloseTopnavDropdowns === 'function') {
      window.srCloseTopnavDropdowns(null);
    }

    return runScripts(payload.scripts).then(function () {
      if (pushUrl) {
        var u = new URL('/operator/app.php', window.location.origin);
        u.searchParams.set('module', currentModule);
        u.searchParams.set('page', payload.path);
        if (payload.main_page) {
          u.searchParams.set('main_page', payload.main_page);
        }
        history.pushState(
          { spa: true, module: currentModule, page: payload.path },
          '',
          u.pathname + u.search
        );
      }
    });
  }

  function showSpaError(module, page, query, push, detail) {
    var msg = detail || 'This page could not be loaded.';
    viewport.innerHTML =
      '<div class="container-fluid py-5">' +
      '<div class="card border-0 shadow-sm mx-auto" style="max-width:520px">' +
      '<div class="card-body text-center p-4">' +
      '<div class="mb-2 text-danger"><i class="uil uil-exclamation-circle" style="font-size:2rem"></i></div>' +
      '<h5 class="mb-2">Could not load page</h5>' +
      '<p class="text-muted mb-3">' + msg + '</p>' +
      '<button type="button" class="btn btn-primary btn-sm" id="sr-spa-retry">Retry</button>' +
      '</div></div></div>';
    var btn = document.getElementById('sr-spa-retry');
    if (btn) {
      btn.addEventListener('click', function () {
        loadView(module, page, query, push);
      });
    }
  }

  function loadView(module, page, query, push) {
    if (!page) page = defaultPage(module);
    var q = query || {};
    var key = viewCacheKey(module, page, q);
    /* Warm revisit: paint cached shell immediately (no spinner, no PHP wait). */
    var cached = viewCacheGet(key);
    if (cached) {
      if (loading) {
        pendingNav = { module: module, page: page, query: q, push: push };
        if (navAbort) {
          try { navAbort.abort(); } catch (e) { /* ignore */ }
        }
        return Promise.resolve();
      }
      currentViewKey = key;
      viewport.classList.remove('sr-spa-loading');
      viewport.removeAttribute('aria-busy');
      return applyPayload(cached, push);
    }

    /* If a navigation is already in flight, queue the latest request instead of
       silently dropping it (that caused intermittent "page didn't load"). */
    if (loading) {
      pendingNav = { module: module, page: page, query: q, push: push };
      if (navAbort) {
        try { navAbort.abort(); } catch (e) { /* ignore */ }
      }
      return Promise.resolve();
    }
    loading = true;
    currentViewKey = key;
    if (navAbort) {
      try { navAbort.abort(); } catch (e) { /* ignore */ }
    }
    navAbort = typeof AbortController !== 'undefined' ? new AbortController() : null;
    viewport.classList.add('sr-spa-loading');
    viewport.setAttribute('aria-busy', 'true');
    return fetch(spaFetchUrl(module, page, q), {
      credentials: 'same-origin',
      headers: { 'X-Tessera-Operator-Spa': '1', Accept: 'application/json' },
      signal: navAbort ? navAbort.signal : undefined
    })
      .then(function (r) {
        if (r.status === 401 || r.status === 403) {
          window.location.href = (modulePrefixes[module] || '/tickets/') + 'index.php';
          throw new Error('auth');
        }
        if (!r.ok) throw new Error('SPA load failed (' + r.status + ')');
        return r.json();
      })
      .then(function (payload) {
        if (payload.error) throw new Error(payload.detail || payload.error);
        viewCacheSet(key, payload);
        if (currentViewKey !== key) return;
        return applyPayload(payload, push);
      })
      .catch(function (err) {
        if (err && err.name === 'AbortError') return;
        if (err && err.message === 'auth') return;
        if (currentViewKey !== key) return;
        showSpaError(module, page, q, push, err && err.message ? String(err.message) : '');
      })
      .finally(function () {
        loading = false;
        viewport.classList.remove('sr-spa-loading');
        viewport.removeAttribute('aria-busy');
        if (pendingNav) {
          var next = pendingNav;
          pendingNav = null;
          loadView(next.module, next.page, next.query, next.push);
        }
      });
  }

  function onLinkClick(e) {
    var a = e.target.closest('a[href]');
    if (!a || a.target === '_blank' || a.hasAttribute('download')) return;
    if (a.dataset.srSpa === 'off' || a.classList.contains('sr-spa-skip')) return;
    var href = a.getAttribute('href');
    if (!href || href.charAt(0) === '#') return;

    if (href.indexOf('/control/') !== -1 || href.indexOf('/settings/') === 0 || href.indexOf('/login') !== -1) {
      return;
    }
    if (href.indexOf('log_out') !== -1) return;

    var parsed = parseOperatorTarget(a.href);
    if (!parsed || !parsed.page) return;

    e.preventDefault();
    loadView(parsed.module, parsed.page, parsed.query, true);
  }

  patchLegacyAjax(currentModule);

  document.addEventListener('click', onLinkClick, true);

  window.addEventListener('popstate', function (ev) {
    if (!ev.state || !ev.state.page) return;
    loadView(ev.state.module || currentModule, ev.state.page, {}, false);
  });

  document.addEventListener('DOMContentLoaded', function () {
    if (cfg.initial) {
      currentModule = cfg.module || cfg.initial.module || currentModule;
      setDocumentBase(currentModule);
      patchLegacyAjax(currentModule);
      setModulePillsActive(currentModule);
      setNavActive(cfg.initial.main_page || '', cfg.initial.path || '');
      lastTopnavModule = currentModule;
      currentViewKey = viewCacheKey(currentModule, cfg.initial.path || defaultPage(currentModule), {});
      viewCacheSet(currentViewKey, cfg.initial);
      history.replaceState(
        { spa: true, module: currentModule, page: cfg.initial.path },
        '',
        window.location.href
      );
      if (cfg.initial.scripts && cfg.initial.scripts.length) {
        runScripts(cfg.initial.scripts);
      }
    }
  });
})();
