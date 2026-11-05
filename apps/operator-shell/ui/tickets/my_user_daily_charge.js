/**
 * T-P0-07 Agent daily charge — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-charge-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var state = { page: 1, perPage: 100, total: 0 };

  var errEl = document.getElementById('tds-charge-error');
  var loadingEl = document.getElementById('tds-charge-loading');
  var emptyEl = document.getElementById('tds-charge-empty');
  var wrapEl = document.getElementById('tds-charge-wrap');
  var tbody = document.getElementById('tds-charge-tbody');
  var pager = document.getElementById('tds-charge-pager');
  var metaEl = document.getElementById('tds-charge-meta');
  var bookerEl = document.getElementById('tds-charge-booker');
  var fromEl = document.getElementById('tds-charge-from');
  var toEl = document.getElementById('tds-charge-to');
  var userWrap = document.getElementById('tds-charge-user-wrap');

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
    if (bookerEl && bookerEl.value) params.set('booker', bookerEl.value);
    if (fromEl && fromEl.value) params.set('date_from', fromEl.value);
    if (toEl && toEl.value) params.set('date_to', toEl.value);
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
        esc(it.seat_no) +
        '</td>' +
        '<td>' +
        esc(it.fullname) +
        '</td>' +
        '<td>' +
        esc(it.book_date) +
        (it.book_time ? ' ' + esc(it.book_time) : '') +
        '</td>' +
        '<td>' +
        esc((it.start_route || '—') + ' → ' + (it.end_route || '—')) +
        '</td>' +
        '<td>' +
        esc(it.bus) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(money(it.cost)) +
        '</td>' +
        '<td><a class="tds-btn tds-btn-secondary tds-btn-sm" href="' +
        esc(it.print_url) +
        '" target="_blank" rel="noopener">Print</a></td>';
      tbody.appendChild(tr);
    });
  }

  function updatePager() {
    if (!pager || !metaEl) return;
    var total = state.total;
    var from = total === 0 ? 0 : (state.page - 1) * state.perPage + 1;
    var to = Math.min(state.page * state.perPage, total);
    metaEl.textContent = total
      ? 'Showing ' + from + '–' + to + ' of ' + total.toLocaleString()
      : 'No results';
    pager.hidden = total === 0;
    document.getElementById('tds-charge-prev').disabled = state.page <= 1;
    document.getElementById('tds-charge-next').disabled = state.page * state.perPage >= total;
  }

  function loadList() {
    showError('');
    setLoading(true);
    if (emptyEl) emptyEl.hidden = true;
    return fetchJson(API + '/agent_daily_charge.php?' + filterParams().toString())
      .then(function (body) {
        var d = body.data || {};
        state.total = d.total || 0;
        if (body.meta && body.meta.page) state.page = body.meta.page;
        document.getElementById('tds-charge-seller').textContent = d.booker || '—';
        document.getElementById('tds-charge-count').textContent = String(
          (d.summary && d.summary.ticket_count) || 0
        );
        document.getElementById('tds-charge-total').textContent =
          (d.summary && d.summary.total_sales_label) || '0 Tsh';
        renderRows(d.items || [], state.page, state.perPage);
        updatePager();
      })
      .catch(function (err) {
        showError(err.message || 'Could not load daily charges');
        renderRows([]);
      })
      .finally(function () {
        setLoading(false);
        updatePager();
      });
  }

  function loadFilters() {
    return fetchJson(API + '/agent_daily_charge_filters.php').then(function (body) {
      var d = body.data || {};
      if (fromEl && !fromEl.value) fromEl.value = d.default_date_from || '';
      if (toEl && !toEl.value) toEl.value = d.default_date_to || '';
      if (d.can_pick_user && userWrap && bookerEl) {
        userWrap.hidden = false;
        bookerEl.innerHTML = '';
        (d.users || []).forEach(function (u) {
          var o = document.createElement('option');
          o.value = u;
          o.textContent = u;
          bookerEl.appendChild(o);
        });
        bookerEl.value = d.default_booker || '';
      } else if (bookerEl) {
        bookerEl.innerHTML = '';
        var o = document.createElement('option');
        o.value = d.default_booker || '';
        o.textContent = d.default_booker || 'Me';
        bookerEl.appendChild(o);
      }
    });
  }

  document.getElementById('tds-charge-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-charge-prev').addEventListener('click', function () {
    if (state.page > 1) {
      state.page -= 1;
      loadList();
    }
  });
  document.getElementById('tds-charge-next').addEventListener('click', function () {
    if (state.page * state.perPage < state.total) {
      state.page += 1;
      loadList();
    }
  });

  try {
    var u = new URL(window.location.href);
    if (u.searchParams.get('date_from') && fromEl) fromEl.value = u.searchParams.get('date_from');
    if (u.searchParams.get('date_to') && toEl) toEl.value = u.searchParams.get('date_to');
    if (u.searchParams.get('booker') && bookerEl) bookerEl.value = u.searchParams.get('booker');
  } catch (e) {}

  loadFilters()
    .catch(function () {})
    .then(function () {
      return loadList();
    });
})();
