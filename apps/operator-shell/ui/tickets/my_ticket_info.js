/**
 * T-P1-05 My ticket info.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-info-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-info-root-body') || root;
  var today = new Date().toISOString().slice(0, 10);
  body.innerHTML =
    '<div class="tds-toolbar tds-date-range">' +
    '<div class="tds-field"><label class="tds-label" for="f-from">From trip date</label><input class="tds-input" id="f-from" type="date" value="' + today + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="f-to">To trip date</label><input class="tds-input" id="f-to" type="date" value="' + today + '"></div>' +
    '<div class="tds-field" style="flex:1"><label class="tds-label" for="f-q">Search</label><input class="tds-input" id="f-q" type="search" placeholder="Seat, passenger, phone…"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="f-go">Search</button></div>' +
    '<div class="tds-alert tds-alert-danger" id="f-err" hidden></div>' +
    '<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)"><div class="tds-kpi"><p class="tds-kpi-label">Tickets</p><p class="tds-kpi-value" id="f-count">—</p></div>' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Total</p><p class="tds-kpi-value tds-mono" id="f-total">—</p></div></div>' +
    '<div class="tds-table-panel"><div id="f-empty" class="tds-empty" hidden><p class="tds-empty-title">No bookings</p></div>' +
    '<div class="table-responsive"><table class="tds-table"><thead><tr><th>#</th><th>Ticket</th><th>Seat</th><th>Passenger</th><th>Cost</th><th>Trip</th><th>Vehicle</th><th>Status</th><th></th></tr></thead><tbody id="f-body"></tbody></table></div>' +
    '<div class="tds-pager" id="f-pager" hidden><p class="tds-pager-meta" id="f-meta"></p>' +
    '<div class="tds-row"><button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="f-prev">Previous</button>' +
    '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="f-next">Next</button></div></div></div>';

  var state = { page: 1, perPage: 100, total: 0 };
  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  function load() {
    var p = new URLSearchParams({
      page: String(state.page),
      per_page: String(state.perPage),
      date_from: document.getElementById('f-from').value,
      date_to: document.getElementById('f-to').value,
      q: document.getElementById('f-q').value || '',
    });
    fetch('/tickets/api/console/my_ticket_info.php?' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) {
        return r.json();
      })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Failed');
        var d = body.data || {};
        state.total = Number(d.total || 0);
        document.getElementById('f-err').hidden = true;
        document.getElementById('f-count').textContent = String((d.summary && d.summary.tickets) || state.total);
        document.getElementById('f-total').textContent = money(d.summary && d.summary.total_sales);
        var items = d.items || [];
        var tb = document.getElementById('f-body');
        document.getElementById('f-empty').hidden = items.length > 0;
        tb.innerHTML = items
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
              '</td><td class="tds-mono">' +
              money(it.cost) +
              '</td><td>' +
              esc(it.trip_date) +
              '</td><td>' +
              esc(it.bus) +
              '</td><td><span class="tds-badge">' +
              esc(it.status) +
              '</span></td><td><a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
              esc(it.print_url) +
              '" target="_blank" rel="noopener">Print</a></td></tr>'
            );
          })
          .join('');
        var pages = Math.max(1, Math.ceil(state.total / state.perPage));
        var pager = document.getElementById('f-pager');
        pager.hidden = state.total <= state.perPage;
        document.getElementById('f-meta').textContent = 'Page ' + state.page + ' of ' + pages;
      })
      .catch(function (e) {
        var err = document.getElementById('f-err');
        err.hidden = false;
        err.textContent = e.message || 'Failed';
      });
  }
  document.getElementById('f-go').onclick = function () {
    state.page = 1;
    load();
  };
  document.getElementById('f-prev').onclick = function () {
    if (state.page > 1) {
      state.page--;
      load();
    }
  };
  document.getElementById('f-next').onclick = function () {
    state.page++;
    load();
  };
  load();
})();
