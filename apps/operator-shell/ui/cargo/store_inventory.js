/**
 * C-P0-03 Store inventory — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-store-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/cargo/api/console';
  var state = { page: 1, perPage: 50, total: 0, store: 'not_out' };

  var errEl = document.getElementById('tds-store-error');
  var loadingEl = document.getElementById('tds-store-loading');
  var emptyEl = document.getElementById('tds-store-empty');
  var wrapEl = document.getElementById('tds-store-wrap');
  var tbody = document.getElementById('tds-store-tbody');
  var pager = document.getElementById('tds-store-pager');
  var metaEl = document.getElementById('tds-store-meta');
  var qEl = document.getElementById('tds-store-q');
  var routeEl = document.getElementById('tds-store-route');
  var fromEl = document.getElementById('tds-store-from');
  var toEl = document.getElementById('tds-store-to');

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function setLoading(on) {
    if (loadingEl) loadingEl.hidden = !on;
    if (on) {
      if (wrapEl) wrapEl.hidden = true;
      if (emptyEl) emptyEl.hidden = true;
      if (pager) pager.hidden = true;
    }
  }
  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  function storeBadge(store) {
    if (store === 'half_out') return 'tds-badge tds-badge-warn';
    return 'tds-badge tds-badge-info';
  }
  function payBadge(badge) {
    if (badge === 'paid') return 'tds-badge tds-badge-paid';
    if (badge === 'due') return 'tds-badge tds-badge-due';
    if (badge === 'warn') return 'tds-badge tds-badge-warn';
    return 'tds-badge tds-badge-neutral';
  }

  function fetchJson(url) {
    return fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(
      function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body || body.ok === false) {
            var message =
              body && body.error && body.error.message ? body.error.message : 'Request failed';
            throw new Error(message);
          }
          return body;
        });
      }
    );
  }

  function filterParams() {
    var params = new URLSearchParams();
    params.set('page', String(state.page));
    params.set('per_page', String(state.perPage));
    params.set('store', state.store || 'not_out');
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    if (routeEl && routeEl.value) params.set('route', routeEl.value);
    if (fromEl && fromEl.value) params.set('date_from', fromEl.value);
    if (toEl && toEl.value) params.set('date_to', toEl.value);
    return params;
  }

  function renderRows(items) {
    tbody.innerHTML = '';
    if (!items || !items.length) {
      if (emptyEl) emptyEl.hidden = false;
      if (wrapEl) wrapEl.hidden = true;
      if (pager) pager.hidden = true;
      return;
    }
    if (emptyEl) emptyEl.hidden = true;
    if (wrapEl) wrapEl.hidden = false;

    items.forEach(function (it) {
      var tr = document.createElement('tr');
      tr.innerHTML =
        '<td class="tds-mono"><strong>' +
        esc(it.code) +
        '</strong></td>' +
        '<td><a class="' +
        storeBadge(it.store) +
        '" href="' +
        esc(it.view_url) +
        '" target="_blank" rel="noopener">' +
        esc(it.store_label) +
        '</a></td>' +
        '<td>' +
        esc(it.sender_name) +
        '</td>' +
        '<td>' +
        esc(it.receiver_name) +
        '</td>' +
        '<td>' +
        esc(it.date_label) +
        '</td>' +
        '<td>' +
        esc(it.weight) +
        '</td>' +
        '<td>' +
        esc(it.detail) +
        '</td>' +
        '<td>' +
        esc(it.quantity) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.to_be) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.paid) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.amount) +
        ' <span class="' +
        payBadge(it.payment_badge) +
        '" style="margin-left:0.35rem">' +
        esc(it.payment_label) +
        '</span></td>' +
        '<td><div class="tds-table-actions">' +
        '<a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
        esc(it.print_url) +
        '" target="_blank" rel="noopener">Print</a>' +
        '<a class="tds-btn tds-btn-secondary tds-btn-sm" href="' +
        esc(it.edit_url) +
        '" target="_blank" rel="noopener">Edit</a>' +
        '</div></td>';
      tbody.appendChild(tr);
    });
  }

  function renderPager() {
    if (!pager || !metaEl) return;
    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (state.total === 0) {
      pager.hidden = true;
      return;
    }
    pager.hidden = false;
    metaEl.textContent =
      'Page ' + state.page + ' of ' + pages + ' · ' + state.total.toLocaleString() + ' parcels';
    var prev = document.getElementById('tds-store-prev');
    var next = document.getElementById('tds-store-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page >= pages;
  }

  function loadList() {
    showError('');
    setLoading(true);
    fetchJson(API + '/store_inventory.php?' + filterParams().toString())
      .then(function (body) {
        var data = body.data || {};
        state.total = Number(data.total || 0);
        if (body.meta && body.meta.page) state.page = Number(body.meta.page);
        renderRows(data.items || []);
        renderPager();
      })
      .catch(function (e) {
        showError(e.message || 'Could not load store inventory');
        if (emptyEl) emptyEl.hidden = true;
        if (wrapEl) wrapEl.hidden = true;
      })
      .then(function () {
        setLoading(false);
      });
  }

  function loadFilters() {
    return fetchJson(API + '/store_inventory_filters.php').then(function (body) {
      var routes = (body.data && body.data.routes) || [];
      if (!routeEl) return;
      var keep = routeEl.value;
      routeEl.innerHTML = '<option value="">All my routes</option>';
      routes.forEach(function (name) {
        var o = document.createElement('option');
        o.value = name;
        o.textContent = name;
        routeEl.appendChild(o);
      });
      if (keep) routeEl.value = keep;
    });
  }

  document.querySelectorAll('#tds-store-chips .tds-chip').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('#tds-store-chips .tds-chip').forEach(function (b) {
        b.classList.remove('is-active');
      });
      btn.classList.add('is-active');
      state.store = btn.getAttribute('data-store') || 'not_out';
      state.page = 1;
      loadList();
    });
  });

  document.getElementById('tds-store-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-store-reset').addEventListener('click', function () {
    if (qEl) qEl.value = '';
    if (routeEl) routeEl.value = '';
    if (fromEl) fromEl.value = '';
    if (toEl) toEl.value = '';
    state.store = 'not_out';
    state.page = 1;
    document.querySelectorAll('#tds-store-chips .tds-chip').forEach(function (b) {
      b.classList.toggle('is-active', (b.getAttribute('data-store') || '') === 'not_out');
    });
    loadList();
  });
  document.getElementById('tds-store-prev').addEventListener('click', function () {
    if (state.page > 1) {
      state.page -= 1;
      loadList();
    }
  });
  document.getElementById('tds-store-next').addEventListener('click', function () {
    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (state.page < pages) {
      state.page += 1;
      loadList();
    }
  });
  if (qEl) {
    qEl.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        state.page = 1;
        loadList();
      }
    });
  }

  if (fromEl && !fromEl.value) {
    var d = new Date();
    d.setDate(d.getDate() - 30);
    fromEl.value = d.toISOString().slice(0, 10);
  }

  loadFilters()
    .then(loadList)
    .catch(function (e) {
      showError(e.message || 'Could not load filters');
      loadList();
    });
})();
