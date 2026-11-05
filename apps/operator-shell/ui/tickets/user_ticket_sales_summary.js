/**
 * T-P1-03 User ticket sales — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-user-sales-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var state = { page: 1, perPage: 100, total: 0 };

  var errEl = document.getElementById('tds-user-sales-error');
  var loadingEl = document.getElementById('tds-user-sales-loading');
  var emptyEl = document.getElementById('tds-user-sales-empty');
  var wrapEl = document.getElementById('tds-user-sales-wrap');
  var tbody = document.getElementById('tds-user-sales-tbody');
  var pager = document.getElementById('tds-user-sales-pager');
  var metaEl = document.getElementById('tds-user-sales-meta');
  var bookerEl = document.getElementById('tds-user-sales-booker');
  var bookerWrap = document.getElementById('tds-user-sales-booker-wrap');
  var routeEl = document.getElementById('tds-user-sales-route');
  var fromEl = document.getElementById('tds-user-sales-from');
  var toEl = document.getElementById('tds-user-sales-to');
  var qEl = document.getElementById('tds-user-sales-q');
  var sellerEl = document.getElementById('tds-user-sales-seller');
  var countEl = document.getElementById('tds-user-sales-count');
  var totalEl = document.getElementById('tds-user-sales-total');

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function setLoading(on) {
    if (loadingEl) loadingEl.hidden = !on;
    if (wrapEl) wrapEl.hidden = !!on;
    if (pager && on) pager.hidden = true;
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
    return fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body || body.ok === false) {
          throw new Error((body && body.error && body.error.message) || 'Request failed');
        }
        return body;
      });
    });
  }
  function filterParams() {
    var params = new URLSearchParams();
    params.set('page', String(state.page));
    params.set('per_page', String(state.perPage));
    if (bookerEl && bookerEl.value) params.set('booker', bookerEl.value);
    if (routeEl && routeEl.value) params.set('route_id', routeEl.value);
    if (fromEl && fromEl.value) params.set('date_from', fromEl.value);
    if (toEl && toEl.value) params.set('date_to', toEl.value);
    if (qEl && qEl.value) params.set('q', qEl.value);
    return params;
  }
  function renderRows(items, page, perPage) {
    tbody.innerHTML = '';
    if (!items || !items.length) {
      if (emptyEl) emptyEl.hidden = false;
      if (wrapEl) wrapEl.hidden = true;
      if (pager) pager.hidden = true;
      return;
    }
    if (emptyEl) emptyEl.hidden = true;
    if (wrapEl) wrapEl.hidden = false;
    items.forEach(function (it, idx) {
      var n = (page - 1) * perPage + idx + 1;
      var tr = document.createElement('tr');
      tr.innerHTML =
        '<td class="tds-mono">' + n + '</td>' +
        '<td class="tds-mono">' + esc(String(it.id)) + '</td>' +
        '<td>' + esc(it.bus) + '</td>' +
        '<td>' + esc(it.seat_no) + '</td>' +
        '<td>' + esc(it.fullname) + '</td>' +
        '<td class="tds-mono">' + money(it.cost) + '</td>' +
        '<td>' + esc(it.trip_date) + '</td>' +
        '<td>' + esc(it.start_location) + '</td>' +
        '<td>' + esc(it.ending_location) + '</td>' +
        '<td>' + esc(it.issuer) + '</td>';
      tbody.appendChild(tr);
    });
  }
  function renderPager() {
    if (!pager || !metaEl) return;
    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (state.total <= state.perPage) {
      pager.hidden = true;
      return;
    }
    pager.hidden = false;
    metaEl.textContent = 'Page ' + state.page + ' of ' + pages + ' · ' + state.total + ' tickets';
    var prev = document.getElementById('tds-user-sales-prev');
    var next = document.getElementById('tds-user-sales-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page >= pages;
  }
  function loadList() {
    setLoading(true);
    showError('');
    return fetchJson(API + '/user_ticket_sales.php?' + filterParams().toString())
      .then(function (body) {
        var data = body.data || {};
        var meta = body.meta || {};
        state.total = Number(data.total || 0);
        state.page = Number(meta.page || state.page);
        var summary = data.summary || {};
        if (sellerEl) sellerEl.textContent = data.booker || '—';
        if (countEl) countEl.textContent = String(summary.tickets != null ? summary.tickets : state.total);
        if (totalEl) totalEl.textContent = money(summary.total_sales);
        renderRows(data.items || [], state.page, state.perPage);
        renderPager();
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load sales');
        if (tbody) tbody.innerHTML = '';
        if (emptyEl) emptyEl.hidden = false;
        if (wrapEl) wrapEl.hidden = true;
      })
      .finally(function () {
        setLoading(false);
      });
  }
  function loadFilters() {
    return fetchJson(API + '/user_ticket_sales_filters.php').then(function (body) {
      var data = body.data || {};
      var users = data.users || [];
      var routes = data.routes || [];
      if (bookerEl) {
        bookerEl.innerHTML = users
          .map(function (u) {
            return '<option value="' + esc(u) + '">' + esc(u) + '</option>';
          })
          .join('');
        if (data.default_booker) bookerEl.value = data.default_booker;
      }
      if (bookerWrap) bookerWrap.hidden = !data.can_pick_user;
      if (routeEl) {
        routeEl.innerHTML =
          '<option value="0">All routes</option>' +
          routes
            .map(function (r) {
              return '<option value="' + esc(String(r.id)) + '">' + esc(r.name) + '</option>';
            })
            .join('');
        if (data.default_route_id) routeEl.value = String(data.default_route_id);
      }
      if (fromEl) fromEl.value = data.default_date_from || new Date().toISOString().slice(0, 10);
      if (toEl) toEl.value = data.default_date_to || fromEl.value;
    });
  }

  document.getElementById('tds-user-sales-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-user-sales-prev').addEventListener('click', function () {
    if (state.page <= 1) return;
    state.page -= 1;
    loadList();
  });
  document.getElementById('tds-user-sales-next').addEventListener('click', function () {
    state.page += 1;
    loadList();
  });
  var searchTimer = null;
  if (qEl) {
    qEl.addEventListener('input', function () {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(function () {
        state.page = 1;
        loadList();
      }, 250);
    });
  }

  loadFilters()
    .then(loadList)
    .catch(function (err) {
      showError(err.message || 'Failed to load filters');
    });
})();
