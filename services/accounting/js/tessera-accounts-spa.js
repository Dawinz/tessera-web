/**
 * Accounts SPA — keep header chrome; swap main view via spa.php fragments.
 */
(function () {
  'use strict';

  var cfg = window.__TESSERA_ACCOUNTS_SPA__;
  if (!cfg) return;

  var viewport = document.getElementById('sr-spa-viewport');
  var mount = document.getElementById('sr-spa-mount');
  var loading = false;

  function applyPageStyles(html) {
    document.querySelectorAll('[data-sr-spa-style]').forEach(function (el) {
      el.parentNode && el.parentNode.removeChild(el);
    });
    if (!html) return;
    var box = document.createElement('div');
    box.innerHTML = html;
    Array.prototype.slice.call(box.querySelectorAll('link[rel="stylesheet"], style')).forEach(function (node) {
      node.setAttribute('data-sr-spa-style', '1');
      document.head.appendChild(node);
    });
  }

  function accountsPath(href) {
    try {
      var u = new URL(href, window.location.origin);
      if (u.origin !== window.location.origin) return null;
      if (!u.pathname.startsWith('/accounts/')) return null;
      var base = u.pathname.replace(/^\/accounts\//, '');
      if (base === '' || base === 'app.php') return { page: 'manifest.php', query: {} };
      if (!/\.php$/i.test(base)) return null;
      if (base === 'spa.php' || base === 'log_out.php') return null;
      var q = {};
      u.searchParams.forEach(function (v, k) {
        if (k !== 'spa') q[k] = v;
      });
      return { page: base.split('/').pop(), query: q };
    } catch (e) {
      return null;
    }
  }

  function spaFetchUrl(page, query) {
    var u = new URL(cfg.spaUrl, window.location.origin + '/accounts/');
    u.searchParams.set('page', page);
    u.searchParams.set('spa', '1');
    Object.keys(query || {}).forEach(function (k) {
      u.searchParams.set(k, query[k]);
    });
    return u.toString();
  }

  function runScripts(scripts) {
    var chain = Promise.resolve();
    (scripts || []).forEach(function (s) {
      chain = chain.then(function () {
        return new Promise(function (resolve) {
          if (s.src) {
            var el = document.createElement('script');
            el.src = s.src;
            el.onload = function () { resolve(); };
            el.onerror = function () { resolve(); };
            (mount || document.body).appendChild(el);
            return;
          }
          if (s.inline) {
            try {
              var inline = document.createElement('script');
              inline.text = s.inline;
              (mount || document.body).appendChild(inline);
            } catch (e) { /* ignore */ }
          }
          resolve();
        });
      });
    });
    return chain;
  }

  function setNavActive(mainPage, pagePath) {
    var nav = document.querySelector('#page-topbar .topnav .navbar-nav');
    if (!nav) return;
    nav.querySelectorAll('.nav-item').forEach(function (li) {
      li.classList.remove('active');
      var link = li.querySelector(':scope > .nav-link');
      if (!link) return;
      link.classList.remove('active');
    });
    nav.querySelectorAll('.dropdown-item').forEach(function (a) {
      a.classList.remove('active');
    });

    var targetName = mainPage || '';
    if (targetName) {
      nav.querySelectorAll('.nav-item').forEach(function (li) {
        var label = li.querySelector(':scope > .nav-link span[data-key]');
        if (label && label.textContent.trim() === targetName) {
          li.classList.add('active');
          li.querySelector(':scope > .nav-link')?.classList.add('active');
        }
      });
    }
    if (pagePath) {
      nav.querySelectorAll('.dropdown-item[href]').forEach(function (a) {
        try {
          var p = accountsPath(a.href);
          if (p && p.page === pagePath) {
            a.classList.add('active');
            var li = a.closest('.nav-item.dropdown');
            if (li) {
              li.classList.add('active');
              li.querySelector(':scope > .nav-link')?.classList.add('active');
            }
          }
        } catch (e) { /* ignore */ }
      });
    }
  }

  function applyPayload(payload, pushUrl) {
    if (!viewport || !payload) return Promise.resolve();
    viewport.innerHTML = payload.html || '';
    applyPageStyles(payload.styles || '');
    document.title = payload.title || document.title;
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

    return runScripts(payload.scripts).then(function () {
      if (pushUrl) {
        var u = new URL('/accounts/app.php', window.location.origin);
        u.searchParams.set('page', payload.path);
        if (payload.main_page) {
          u.searchParams.set('main_page', payload.main_page);
        }
        history.pushState({ spa: true, page: payload.path }, '', u.pathname + u.search);
      }
    });
  }

  function loadPage(page, query, push) {
    if (loading) return Promise.resolve();
    loading = true;
    viewport.classList.add('sr-spa-loading');
    return fetch(spaFetchUrl(page, query), {
      credentials: 'same-origin',
      headers: { 'X-Tessera-Accounts-Spa': '1', Accept: 'application/json' }
    })
      .then(function (r) {
        if (!r.ok) throw new Error('SPA load failed');
        return r.json();
      })
      .then(function (payload) {
        if (payload.error) throw new Error(payload.detail || payload.error);
        return applyPayload(payload, push);
      })
      .catch(function () {
        window.location.href = '/accounts/' + page + (query && Object.keys(query).length
          ? '?' + new URLSearchParams(query).toString()
          : '');
      })
      .finally(function () {
        loading = false;
        viewport.classList.remove('sr-spa-loading');
      });
  }

  function onLinkClick(e) {
    var a = e.target.closest('a[href]');
    if (!a || a.target === '_blank' || a.hasAttribute('download')) return;
    if (a.dataset.srSpa === 'off' || a.classList.contains('sr-spa-skip')) return;
    var href = a.getAttribute('href');
    if (!href || href.charAt(0) === '#') return;

    if (a.closest('#page-topbar .navbar-header') && a.closest('.navbar-nav') === null) {
      return;
    }
    if (href.indexOf('/tickets/') !== -1 || href.indexOf('/cargo/') !== -1 || href.indexOf('/control/') !== -1) {
      return;
    }
    if (href.indexOf('log_out') !== -1 || href.indexOf('/login') !== -1) return;

    var parsed = accountsPath(a.href);
    if (!parsed) return;

    e.preventDefault();
    loadPage(parsed.page, parsed.query, true);
  }

  document.addEventListener('click', onLinkClick, true);

  window.addEventListener('popstate', function (ev) {
    if (!ev.state || !ev.state.page) return;
    loadPage(ev.state.page, {}, false);
  });

  document.addEventListener('DOMContentLoaded', function () {
    if (cfg.initial) {
      setNavActive(cfg.initial.main_page || '', cfg.initial.path || '');
      history.replaceState({ spa: true, page: cfg.initial.path }, '', window.location.href);
      if (cfg.initial.scripts && cfg.initial.scripts.length) {
        runScripts(cfg.initial.scripts);
      }
    }
  });
})();
