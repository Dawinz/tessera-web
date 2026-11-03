/**
 * Tessera operator application states — shared JS helpers.
 * Exposes window.TesseraStates
 */
(function (global) {
  'use strict';

  var ICONS = {
    empty: '📭',
    search: '🔍',
    error: '⚠',
    offline: '📡',
    lock: '🔒',
    forbidden: '⛔',
    notFound: '🧭',
    maintenance: '🛠',
    success: '✓',
    loading: '…',
    payment: '💳',
    network: '🌐'
  };

  function el(tag, className, text) {
    var node = document.createElement(tag);
    if (className) node.className = className;
    if (text != null && text !== '') node.textContent = text;
    return node;
  }

  function ensureToastHost() {
    var host = document.getElementById('ts-toast-host');
    if (host) return host;
    host = el('div', 'ts-toast-host');
    host.id = 'ts-toast-host';
    document.body.appendChild(host);
    return host;
  }

  function ensureOfflineBanner() {
    var banner = document.getElementById('ts-offline-banner');
    if (banner) return banner;
    banner = el('div', 'ts-banner ts-banner--offline');
    banner.id = 'ts-offline-banner';
    banner.setAttribute('role', 'status');
    banner.innerHTML =
      '<span>You are offline. Some actions may fail until connection is restored.</span>' +
      '<button type="button" data-ts-retry>Retry</button>';
    banner.querySelector('[data-ts-retry]').addEventListener('click', function () {
      if (global.navigator.onLine) {
        global.location.reload();
      } else {
        TesseraStates.toast({
          tone: 'warn',
          title: 'Still offline',
          body: 'Check your connection, then try again.'
        });
      }
    });
    var topbar = document.getElementById('page-topbar');
    if (topbar && topbar.parentNode) {
      topbar.parentNode.insertBefore(banner, topbar.nextSibling);
    } else {
      document.body.insertBefore(banner, document.body.firstChild);
    }
    return banner;
  }

  function button(label, opts) {
    opts = opts || {};
    var btn = el('button', 'btn ' + (opts.className || 'btn-primary'), label);
    btn.type = opts.type || 'button';
    if (opts.href) {
      var a = el('a', 'btn ' + (opts.className || 'btn-primary'), label);
      a.href = opts.href;
      return a;
    }
    if (typeof opts.onClick === 'function') {
      btn.addEventListener('click', opts.onClick);
    }
    return btn;
  }

  function renderState(target, config) {
    if (!target) return null;
    var cfg = config || {};
    var tone = cfg.tone || 'info';
    var root = el('div', 'ts-state ts-state--' + tone + (cfg.flush ? ' ts-state--flush' : ''));
    root.setAttribute('data-ts-state', cfg.kind || tone);

    var icon = el('div', 'ts-state__icon', cfg.icon != null ? cfg.icon : ICONS[cfg.kind] || ICONS.empty);
    var title = el('h3', 'ts-state__title', cfg.title || 'Nothing here yet');
    var body = el('p', 'ts-state__body', cfg.body || '');
    root.appendChild(icon);
    root.appendChild(title);
    if (cfg.body) root.appendChild(body);

    if (cfg.actions && cfg.actions.length) {
      var actions = el('div', 'ts-state__actions');
      cfg.actions.forEach(function (action) {
        actions.appendChild(button(action.label, action));
      });
      root.appendChild(actions);
    }

    if (cfg.replace !== false) {
      target.innerHTML = '';
    }
    target.appendChild(root);
    return root;
  }

  function skeleton(target, rows) {
    if (!target) return;
    rows = rows || 4;
    var wrap = el('div', 'ts-skeleton');
    wrap.setAttribute('aria-busy', 'true');
    wrap.setAttribute('aria-label', 'Loading');
    for (var i = 0; i < rows; i++) {
      wrap.appendChild(el('div', 'ts-skeleton__line ts-skeleton__line--' + (i % 3 === 0 ? 'sm' : i % 2 ? 'md' : 'lg')));
    }
    wrap.appendChild(el('div', 'ts-skeleton__block'));
    target.innerHTML = '';
    target.appendChild(wrap);
  }

  function toast(config) {
    var cfg = typeof config === 'string' ? { title: config } : (config || {});
    var host = ensureToastHost();
    var node = el('div', 'ts-toast ts-toast--' + (cfg.tone || 'info'));
    var copy = el('div', '');
    copy.appendChild(el('p', 'ts-toast__title', cfg.title || 'Done'));
    if (cfg.body) copy.appendChild(el('p', 'ts-toast__body', cfg.body));
    node.appendChild(copy);
    host.appendChild(node);
    var ttl = cfg.ttl != null ? cfg.ttl : 4200;
    if (ttl > 0) {
      setTimeout(function () {
        if (node.parentNode) node.parentNode.removeChild(node);
      }, ttl);
    }
    return node;
  }

  function empty(target, opts) {
    opts = opts || {};
    return renderState(target, {
      kind: 'empty',
      tone: 'info',
      icon: opts.icon || ICONS.empty,
      title: opts.title || 'No records yet',
      body: opts.body || 'When data appears here, it will show up in this list.',
      actions: opts.actions || [],
      flush: opts.flush
    });
  }

  function searchEmpty(target, opts) {
    opts = opts || {};
    return renderState(target, {
      kind: 'search',
      tone: 'info',
      icon: opts.icon || ICONS.search,
      title: opts.title || 'No results found',
      body: opts.body || 'Try a different keyword or clear filters.',
      actions: opts.actions || [],
      flush: opts.flush
    });
  }

  function error(target, opts) {
    opts = opts || {};
    var actions = opts.actions || [];
    if (!actions.length && opts.retry !== false) {
      actions = [{
        label: opts.retryLabel || 'Retry',
        className: 'btn-primary',
        onClick: typeof opts.onRetry === 'function' ? opts.onRetry : function () { global.location.reload(); }
      }];
    }
    return renderState(target, {
      kind: opts.kind || 'error',
      tone: 'danger',
      icon: opts.icon || ICONS.error,
      title: opts.title || 'Something went wrong',
      body: opts.body || 'Please try again. If this keeps happening, contact support.',
      actions: actions,
      flush: opts.flush
    });
  }

  function success(target, opts) {
    opts = opts || {};
    return renderState(target, {
      kind: 'success',
      tone: 'ok',
      icon: opts.icon || ICONS.success,
      title: opts.title || 'Done',
      body: opts.body || '',
      actions: opts.actions || [],
      flush: opts.flush
    });
  }

  function http(target, code, opts) {
    opts = opts || {};
    var map = {
      401: { kind: 'lock', title: 'Session expired', body: 'Sign in again to continue.', actions: [{ label: 'Sign in', href: opts.loginHref || '/login/', className: 'btn-primary' }] },
      403: { kind: 'forbidden', title: "You don't have permission", body: 'Ask an administrator if you need access to this area.', actions: [{ label: 'Go back', href: opts.homeHref || '/', className: 'btn-secondary' }] },
      404: { kind: 'notFound', title: 'Page not found', body: 'This link may be broken or the page may have moved.', actions: [{ label: 'Go home', href: opts.homeHref || '/', className: 'btn-primary' }] },
      408: { kind: 'network', title: 'Request timed out', body: 'The server took too long to respond.', retry: true },
      429: { kind: 'warn', title: 'Too many requests', body: 'Wait a moment, then try again.', retry: true },
      500: { kind: 'error', title: 'Server error', body: 'Something failed on our side. Please try again shortly.', retry: true },
      502: { kind: 'error', title: 'Bad gateway', body: 'Upstream service is unavailable.', retry: true },
      503: { kind: 'maintenance', title: 'Service unavailable', body: 'Tessera may be under maintenance. Try again soon.', retry: true },
      504: { kind: 'network', title: 'Gateway timeout', body: 'The upstream service timed out.', retry: true }
    };
    var preset = map[code] || { kind: 'error', title: 'Unexpected error', body: 'Error code ' + code + '.', retry: true };
    if (preset.retry) {
      return error(target, {
        kind: preset.kind,
        icon: ICONS[preset.kind] || ICONS.error,
        title: opts.title || preset.title,
        body: opts.body || preset.body,
        onRetry: opts.onRetry,
        flush: opts.flush
      });
    }
    return renderState(target, {
      kind: preset.kind,
      tone: code === 403 || code === 401 ? 'warn' : 'danger',
      icon: ICONS[preset.kind] || ICONS.error,
      title: opts.title || preset.title,
      body: opts.body || preset.body,
      actions: opts.actions || preset.actions || [],
      flush: opts.flush
    });
  }

  var TABLE_LOAD_LABELS = {
    passenger_info: 'Loading bookings…',
    example: 'Loading records…',
    empTable: 'Loading charges…',
    manifest_table: 'Loading manifest…'
  };

  var loadingTables = new Set();

  function tableLoadingKey(settings) {
    if (!settings) return 'unknown';
    if (settings.sTableId) return settings.sTableId;
    if (settings.nTable && settings.nTable.id) return settings.nTable.id;
    if (!settings._tsLoadKey) {
      settings._tsLoadKey = 'dt-' + String(Math.random()).slice(2);
    }
    return settings._tsLoadKey;
  }

  function resolveTablePanel(table) {
    if (!table) return null;
    return table.closest('.sr-table-panel') || table.closest('.card') || null;
  }

  function resolveLoadingLabel(settings, table, panel) {
    if (panel && panel.getAttribute('data-sr-loading-label')) {
      return panel.getAttribute('data-sr-loading-label');
    }
    if (table && table.id && TABLE_LOAD_LABELS[table.id]) {
      return TABLE_LOAD_LABELS[table.id];
    }
    if (settings && settings.oLanguage && settings.oLanguage.sProcessing) {
      var raw = String(settings.oLanguage.sProcessing).replace(/<[^>]+>/g, '').trim();
      if (raw) return raw;
    }
    return 'Loading…';
  }

  function ensureTableLoaderBanner() {
    var banner = document.getElementById('ts-table-loading-banner');
    if (banner) return banner;
    banner = el('div', 'ts-table-loading-banner');
    banner.id = 'ts-table-loading-banner';
    banner.setAttribute('role', 'status');
    banner.setAttribute('aria-live', 'polite');
    banner.setAttribute('aria-busy', 'false');
    banner.innerHTML =
      '<span class="ts-table-loading-banner__spinner" aria-hidden="true"></span>' +
      '<span class="ts-table-loading-banner__text">Loading…</span>';
    var topbar = document.getElementById('page-topbar');
    if (topbar && topbar.parentNode) {
      topbar.parentNode.insertBefore(banner, topbar.nextSibling);
    } else {
      document.body.insertBefore(banner, document.body.firstChild);
    }
    return banner;
  }

  function ensurePanelLoader(panel, label) {
    if (!panel) return null;
    var loader = panel.querySelector('.sr-table-loader');
    if (!loader) {
      loader = el('div', 'sr-table-loader');
      loader.setAttribute('role', 'status');
      loader.setAttribute('aria-live', 'polite');
      loader.innerHTML =
        '<span class="sr-table-loader__spinner" aria-hidden="true"></span>' +
        '<span class="sr-table-loader__text"></span>';
      panel.appendChild(loader);
    }
    var text = loader.querySelector('.sr-table-loader__text');
    if (text) text.textContent = label || 'Loading…';
    return loader;
  }

  function syncTableLoadingBanner(label) {
    var banner = ensureTableLoaderBanner();
    var text = banner.querySelector('.ts-table-loading-banner__text');
    if (loadingTables.size > 0) {
      banner.classList.add('is-visible');
      banner.setAttribute('aria-busy', 'true');
      if (text) text.textContent = label || 'Loading…';
    } else {
      banner.classList.remove('is-visible');
      banner.setAttribute('aria-busy', 'false');
    }
  }

  function bindDatatableLoading() {
    if (bindDatatableLoading._bound) return;
    if (!global.jQuery) return;
    bindDatatableLoading._bound = true;
    ensureTableLoaderBanner();
    document.querySelectorAll('.sr-table-panel[data-sr-loading-label]').forEach(function (panel) {
      ensurePanelLoader(panel, panel.getAttribute('data-sr-loading-label'));
    });
    global.jQuery(document).on('processing.dt', function (e, settings, processing) {
      var table = settings.nTable;
      var panel = resolveTablePanel(table);
      var label = resolveLoadingLabel(settings, table, panel);
      var key = tableLoadingKey(settings);
      if (processing) {
        loadingTables.add(key);
        if (panel) {
          ensurePanelLoader(panel, label);
          panel.classList.add('is-loading');
        }
      } else {
        loadingTables.delete(key);
        if (panel) panel.classList.remove('is-loading');
      }
      syncTableLoadingBanner(label);
    });
  }

  function datatableLanguage(overrides) {
    return Object.assign({
      emptyTable: 'No records yet',
      zeroRecords: 'No matching records found',
      loadingRecords: 'Loading…',
      processing: 'Loading…',
      infoEmpty: 'No entries to show',
      infoFiltered: '(filtered from _MAX_ total entries)'
    }, overrides || {});
  }

  function bindConnectivity() {
    var banner = ensureOfflineBanner();
    function sync() {
      if (global.navigator.onLine) {
        banner.classList.remove('is-visible');
      } else {
        banner.classList.add('is-visible');
      }
    }
    global.addEventListener('online', function () {
      sync();
      toast({ tone: 'ok', title: 'Connection restored', body: 'You are back online.', ttl: 2800 });
    });
    global.addEventListener('offline', sync);
    sync();
  }

  function mountPageState(kind, opts) {
    opts = opts || {};
    var page = el('div', 'ts-page');
    var host = el('div', '');
    page.appendChild(host);
    document.body.innerHTML = '';
    document.body.appendChild(page);
    if (kind === 'maintenance') {
      return renderState(host, {
        kind: 'maintenance',
        tone: 'warn',
        icon: ICONS.maintenance,
        title: opts.title || 'System under maintenance',
        body: opts.body || 'Tessera is temporarily unavailable. Please check back shortly.',
        actions: opts.actions || []
      });
    }
    if (typeof kind === 'number') {
      return http(host, kind, opts);
    }
    return error(host, opts);
  }

  function toastOk(title, body) {
    return toast({ tone: 'ok', title: title || 'Done', body: body || '' });
  }

  function toastErr(title, body) {
    return toast({ tone: 'danger', title: title || 'Something went wrong', body: body || '' });
  }

  function toastWarn(title, body) {
    return toast({ tone: 'warn', title: title || 'Check this', body: body || '' });
  }

  function loadError(title) {
    return function () {
      toastErr(title || 'Could not load data', 'Check your connection, then retry.');
    };
  }

  function listLanguage(emptyTable, zeroRecords) {
    return datatableLanguage({
      emptyTable: emptyTable || 'No records yet',
      zeroRecords: zeroRecords || 'No matching records found'
    });
  }

  var TesseraStates = {
    icons: ICONS,
    render: renderState,
    skeleton: skeleton,
    empty: empty,
    searchEmpty: searchEmpty,
    error: error,
    success: success,
    http: http,
    toast: toast,
    toastOk: toastOk,
    toastErr: toastErr,
    toastWarn: toastWarn,
    loadError: loadError,
    listLanguage: listLanguage,
    datatableLanguage: datatableLanguage,
    bindConnectivity: bindConnectivity,
    bindDatatableLoading: bindDatatableLoading,
    mountPageState: mountPageState
  };

  global.TesseraStates = TesseraStates;

  function bootTesseraStates() {
    bindConnectivity();
    bindDatatableLoading();
    if (!bindDatatableLoading._bound && global.jQuery) {
      global.jQuery(bindDatatableLoading);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootTesseraStates);
  } else {
    bootTesseraStates();
  }

  global.addEventListener('load', bindDatatableLoading);
})(window);
