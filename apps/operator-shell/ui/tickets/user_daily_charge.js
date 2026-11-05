/**
 * T-P1-10 User daily charge (admin) — reuses agent daily charge APIs with seller always visible.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-user-charge-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-user-charge-root-body') || root;
  var today = new Date().toISOString().slice(0, 10);
  body.innerHTML =
    '<div class="tds-toolbar tds-date-range">' +
    '<div class="tds-field" style="min-width:12rem"><label class="tds-label" for="ud-booker">Seller</label><select class="tds-select" id="ud-booker"></select></div>' +
    '<div class="tds-field"><label class="tds-label" for="ud-from">From date</label><input class="tds-input" id="ud-from" type="date" value="' + today + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="ud-to">To date</label><input class="tds-input" id="ud-to" type="date" value="' + today + '"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="ud-go">Search</button></div>' +
    '<div class="tds-alert tds-alert-danger" id="ud-err" hidden></div>' +
    '<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Seller</p><p class="tds-kpi-value" id="ud-seller" style="font-size:1.1rem">—</p></div>' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Tickets</p><p class="tds-kpi-value" id="ud-count">—</p></div>' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Total sales</p><p class="tds-kpi-value tds-mono" id="ud-total">—</p></div></div>' +
    '<div class="tds-table-panel"><div id="ud-empty" class="tds-empty" hidden><p class="tds-empty-title">No ticket sales</p></div>' +
    '<div class="table-responsive"><table class="tds-table"><thead><tr><th>#</th><th>Ticket</th><th>Seat</th><th>Passenger</th><th>Booked</th><th>Route</th><th>Vehicle</th><th>Cost</th><th></th></tr></thead><tbody id="ud-body"></tbody></table></div>' +
    '<div class="tds-pager" id="ud-pager" hidden><p class="tds-pager-meta" id="ud-meta"></p>' +
    '<div class="tds-row"><button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="ud-prev">Previous</button>' +
    '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="ud-next">Next</button></div></div></div>';

  var state = { page: 1, perPage: 100, total: 0 };
  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  function loadFilters() {
    return fetch('/tickets/api/console/agent_daily_charge_filters.php', {
      credentials: 'same-origin',
      headers: { Accept: 'application/json' },
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Failed');
        var d = body.data || {};
        var users = d.users || [d.default_booker || ''];
        document.getElementById('ud-booker').innerHTML = users
          .map(function (u) {
            return '<option value="' + esc(u) + '">' + esc(u) + '</option>';
          })
          .join('');
        if (d.default_booker) document.getElementById('ud-booker').value = d.default_booker;
        if (d.default_date_from) document.getElementById('ud-from').value = d.default_date_from;
        if (d.default_date_to) document.getElementById('ud-to').value = d.default_date_to;
      });
  }
  function load() {
    var p = new URLSearchParams({
      page: String(state.page),
      per_page: String(state.perPage),
      booker: document.getElementById('ud-booker').value || '',
      date_from: document.getElementById('ud-from').value,
      date_to: document.getElementById('ud-to').value,
    });
    fetch('/tickets/api/console/agent_daily_charge.php?' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) {
        return r.json();
      })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Failed');
        var d = body.data || {};
        var s = d.summary || {};
        state.total = Number(d.total || 0);
        document.getElementById('ud-err').hidden = true;
        document.getElementById('ud-seller').textContent = d.booker || '—';
        document.getElementById('ud-count').textContent = String(s.ticket_count != null ? s.ticket_count : state.total);
        document.getElementById('ud-total').textContent = money(s.total_sales);
        var items = d.items || [];
        document.getElementById('ud-empty').hidden = items.length > 0;
        document.getElementById('ud-body').innerHTML = items
          .map(function (it, i) {
            return (
              '<tr><td class="tds-mono">' +
              ((state.page - 1) * state.perPage + i + 1) +
              '</td><td class="tds-mono">' +
              esc(it.id) +
              '</td><td>' +
              esc(it.seat_no) +
              '</td><td>' +
              esc(it.fullname) +
              '</td><td>' +
              esc(it.book_date) +
              (it.book_time ? ' ' + esc(it.book_time) : '') +
              '</td><td>' +
              esc(it.start_route) +
              ' → ' +
              esc(it.end_route) +
              '</td><td>' +
              esc(it.bus) +
              '</td><td class="tds-mono">' +
              money(it.cost) +
              '</td><td><a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
              esc(it.print_url) +
              '" target="_blank" rel="noopener">Print</a></td></tr>'
            );
          })
          .join('');
        var pages = Math.max(1, Math.ceil(state.total / state.perPage));
        document.getElementById('ud-pager').hidden = state.total <= state.perPage;
        document.getElementById('ud-meta').textContent = 'Page ' + state.page + ' of ' + pages;
      })
      .catch(function (e) {
        var err = document.getElementById('ud-err');
        err.hidden = false;
        err.textContent = e.message || 'Failed';
      });
  }
  document.getElementById('ud-go').onclick = function () {
    state.page = 1;
    load();
  };
  document.getElementById('ud-prev').onclick = function () {
    if (state.page > 1) {
      state.page--;
      load();
    }
  };
  document.getElementById('ud-next').onclick = function () {
    state.page++;
    load();
  };
  loadFilters()
    .then(load)
    .catch(function (e) {
      var err = document.getElementById('ud-err');
      err.hidden = false;
      err.textContent = e.message || 'Failed';
    });
})();
