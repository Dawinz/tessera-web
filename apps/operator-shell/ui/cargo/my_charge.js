/**
 * C-P0-06 My charges — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-charges-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/cargo/api/console';
  var state = { page: 1, perPage: 50, total: 0 };

  var errEl = document.getElementById('tds-charges-error');
  var loadingEl = document.getElementById('tds-charges-loading');
  var emptyEl = document.getElementById('tds-charges-empty');
  var wrapEl = document.getElementById('tds-charges-wrap');
  var tbody = document.getElementById('tds-charges-tbody');
  var pager = document.getElementById('tds-charges-pager');
  var metaEl = document.getElementById('tds-charges-meta');
  var qEl = document.getElementById('tds-charges-q');
  var routeEl = document.getElementById('tds-charges-route');
  var userEl = document.getElementById('tds-charges-user');
  var userWrap = document.getElementById('tds-charges-user-wrap');
  var fromEl = document.getElementById('tds-charges-from');
  var toEl = document.getElementById('tds-charges-to');

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
    if (userEl && userEl.value) params.set('user', userEl.value);
    if (routeEl && routeEl.value) params.set('route', routeEl.value);
    if (fromEl && fromEl.value) params.set('date_from', fromEl.value);
    if (toEl && toEl.value) params.set('date_to', toEl.value);
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    return params;
  }

  function setSummary(summary, seller) {
    var sellerEl = document.getElementById('tds-charges-seller');
    var countEl = document.getElementById('tds-charges-count');
    var paidEl = document.getElementById('tds-charges-paid');
    var tobeEl = document.getElementById('tds-charges-tobe');
    if (sellerEl) sellerEl.textContent = seller || '—';
    if (!summary) return;
    if (countEl) countEl.textContent = String(summary.count || 0);
    if (paidEl) paidEl.textContent = money(summary.paid);
    if (tobeEl) tobeEl.textContent = money(summary.to_be);
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
        '<td>' +
        esc(it.sender_name) +
        '</td>' +
        '<td>' +
        esc(it.receiver_name) +
        '</td>' +
        '<td>' +
        esc(it.route) +
        '</td>' +
        '<td>' +
        esc(it.date_label) +
        '</td>' +
        '<td>' +
        esc(it.detail) +
        '</td>' +
        '<td class="tds-mono"><strong>' +
        esc(it.code) +
        '</strong></td>' +
        '<td class="tds-mono">' +
        money(it.amount) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.loan) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.paid) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.to_be) +
        '</td>' +
        '<td>' +
        esc(it.quantity) +
        '</td>' +
        '<td><a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
        esc(it.print_url) +
        '" target="_blank" rel="noopener">Print</a></td>';
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
    var prev = document.getElementById('tds-charges-prev');
    var next = document.getElementById('tds-charges-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page >= pages;
  }

  function loadList() {
    showError('');
    setLoading(true);
    fetchJson(API + '/charges.php?' + filterParams().toString())
      .then(function (body) {
        var data = body.data || {};
        state.total = Number(data.total || 0);
        if (body.meta && body.meta.page) state.page = Number(body.meta.page);
        setSummary(data.summary, data.seller);
        renderRows(data.items || []);
        renderPager();
      })
      .catch(function (e) {
        showError(e.message || 'Could not load charges');
        if (emptyEl) emptyEl.hidden = true;
        if (wrapEl) wrapEl.hidden = true;
      })
      .then(function () {
        setLoading(false);
      });
  }

  function monthBounds() {
    var now = new Date();
    var y = now.getFullYear();
    var m = String(now.getMonth() + 1).padStart(2, '0');
    var last = new Date(y, now.getMonth() + 1, 0).getDate();
    return {
      from: y + '-' + m + '-01',
      to: y + '-' + m + '-' + String(last).padStart(2, '0'),
    };
  }

  function loadFilters() {
    return fetchJson(API + '/charges_filters.php').then(function (body) {
      var data = body.data || {};
      var routes = data.routes || [];
      var users = data.users || [];
      if (routeEl) {
        routeEl.innerHTML = '<option value="">All my routes</option>';
        routes.forEach(function (name) {
          var o = document.createElement('option');
          o.value = name;
          o.textContent = name;
          routeEl.appendChild(o);
        });
        if (routes.length === 1) routeEl.value = routes[0];
      }
      if (data.can_pick_user && userWrap && userEl) {
        userWrap.hidden = false;
        userEl.innerHTML = '';
        users.forEach(function (u) {
          var o = document.createElement('option');
          o.value = u.username;
          o.textContent = u.display_name || u.username;
          userEl.appendChild(o);
        });
        if (data.me) userEl.value = data.me;
      }
    });
  }

  document.getElementById('tds-charges-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-charges-reset').addEventListener('click', function () {
    var b = monthBounds();
    if (qEl) qEl.value = '';
    if (routeEl) routeEl.value = routeEl.options.length === 2 ? routeEl.options[1].value : '';
    if (fromEl) fromEl.value = b.from;
    if (toEl) toEl.value = b.to;
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-charges-prev').addEventListener('click', function () {
    if (state.page > 1) {
      state.page -= 1;
      loadList();
    }
  });
  document.getElementById('tds-charges-next').addEventListener('click', function () {
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

  var bounds = monthBounds();
  if (fromEl && !fromEl.value) fromEl.value = bounds.from;
  if (toEl && !toEl.value) toEl.value = bounds.to;

  loadFilters()
    .then(loadList)
    .catch(function (e) {
      showError(e.message || 'Could not load filters');
      loadList();
    });
})();
