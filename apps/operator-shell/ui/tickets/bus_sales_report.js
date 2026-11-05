/**
 * T-P1-02 Bus income — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-bus-sales-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var state = { page: 1, perPage: 100, total: 0 };

  var errEl = document.getElementById('tds-bus-sales-error');
  var loadingEl = document.getElementById('tds-bus-sales-loading');
  var emptyEl = document.getElementById('tds-bus-sales-empty');
  var wrapEl = document.getElementById('tds-bus-sales-wrap');
  var tbody = document.getElementById('tds-bus-sales-tbody');
  var pager = document.getElementById('tds-bus-sales-pager');
  var metaEl = document.getElementById('tds-bus-sales-meta');
  var vehicleEl = document.getElementById('tds-bus-sales-vehicle');
  var fromEl = document.getElementById('tds-bus-sales-from');
  var toEl = document.getElementById('tds-bus-sales-to');
  var qEl = document.getElementById('tds-bus-sales-q');
  var countEl = document.getElementById('tds-bus-sales-count');
  var totalEl = document.getElementById('tds-bus-sales-total');

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
    if (vehicleEl && vehicleEl.value) params.set('vehicle', vehicleEl.value);
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
        '<td class="tds-mono">' +
        n +
        '</td>' +
        '<td class="tds-mono">' +
        esc(String(it.id)) +
        '</td>' +
        '<td>' +
        esc(it.bus) +
        '</td>' +
        '<td>' +
        esc(it.seat_no) +
        '</td>' +
        '<td>' +
        esc(it.fullname) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.cost) +
        '</td>' +
        '<td>' +
        esc(it.trip_date) +
        '</td>' +
        '<td>' +
        esc(it.book_date) +
        (it.book_time ? ' ' + esc(it.book_time) : '') +
        '</td>' +
        '<td>' +
        esc(it.start_location) +
        '</td>' +
        '<td>' +
        esc(it.ending_location) +
        '</td>' +
        '<td>' +
        esc(it.issuer) +
        '</td>';
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
    var prev = document.getElementById('tds-bus-sales-prev');
    var next = document.getElementById('tds-bus-sales-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page >= pages;
  }

  function loadList() {
    setLoading(true);
    showError('');
    return fetchJson(API + '/bus_sales.php?' + filterParams().toString())
      .then(function (body) {
        var data = body.data || {};
        var meta = body.meta || {};
        state.total = Number(data.total || 0);
        state.page = Number(meta.page || state.page);
        var summary = data.summary || {};
        if (countEl) countEl.textContent = String(summary.tickets != null ? summary.tickets : state.total);
        if (totalEl) totalEl.textContent = money(summary.total_sales);
        renderRows(data.items || [], state.page, state.perPage);
        renderPager();
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load bus income');
        if (tbody) tbody.innerHTML = '';
        if (emptyEl) emptyEl.hidden = false;
        if (wrapEl) wrapEl.hidden = true;
      })
      .finally(function () {
        setLoading(false);
      });
  }

  function loadFilters() {
    return fetchJson(API + '/bus_sales_filters.php').then(function (body) {
      var data = body.data || {};
      var vehicles = data.vehicles || [];
      if (vehicleEl) {
        vehicleEl.innerHTML =
          '<option value="">All vehicles</option>' +
          vehicles
            .map(function (v) {
              return '<option value="' + esc(v) + '">' + esc(v) + '</option>';
            })
            .join('');
        if (data.default_vehicle) vehicleEl.value = data.default_vehicle;
      }
      if (fromEl) fromEl.value = data.default_date_from || new Date().toISOString().slice(0, 10);
      if (toEl) toEl.value = data.default_date_to || fromEl.value;
    });
  }

  var applyBtn = document.getElementById('tds-bus-sales-apply');
  if (applyBtn) {
    applyBtn.addEventListener('click', function () {
      state.page = 1;
      loadList();
    });
  }
  var prevBtn = document.getElementById('tds-bus-sales-prev');
  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      if (state.page <= 1) return;
      state.page -= 1;
      loadList();
    });
  }
  var nextBtn = document.getElementById('tds-bus-sales-next');
  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      state.page += 1;
      loadList();
    });
  }
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
