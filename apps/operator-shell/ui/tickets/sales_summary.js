/**
 * T-P1-09 Sales summary (trip report).
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-trip-sales-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-trip-sales-root-body') || root;
  var params = new URLSearchParams(window.location.search);
  var routeDayId = params.get('id') || params.get('route_day_id') || '';
  var tripDate = params.get('trip_date') || '';
  var manifest = params.get('manifest') || params.get('manifest_id') || '';

  body.innerHTML =
    '<div class="tds-toolbar tds-date-range">' +
    '<div class="tds-field"><label class="tds-label" for="ss-rd">Route day id</label><input class="tds-input" id="ss-rd" type="number" value="' +
    routeDayId.replace(/"/g, '') +
    '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="ss-date">Trip date</label><input class="tds-input" id="ss-date" type="date" value="' +
    tripDate.replace(/"/g, '') +
    '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="ss-man">Manifest id</label><input class="tds-input" id="ss-man" type="number" value="' +
    manifest.replace(/"/g, '') +
    '" placeholder="Optional"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="ss-go">Load</button>' +
    '<button type="button" class="tds-btn tds-btn-secondary" id="ss-print">Print</button></div>' +
    '<div class="tds-alert tds-alert-danger" id="ss-err" hidden></div>' +
    '<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)" id="ss-ctx">' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Route</p><p class="tds-kpi-value" id="ss-route" style="font-size:1rem">—</p></div>' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Vehicle</p><p class="tds-kpi-value" id="ss-bus" style="font-size:1rem">—</p></div>' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Tickets</p><p class="tds-kpi-value" id="ss-count">—</p></div>' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Total</p><p class="tds-kpi-value tds-mono" id="ss-total">—</p></div></div>' +
    '<p class="tds-muted" id="ss-crew"></p>' +
    '<div class="tds-table-panel"><div id="ss-empty" class="tds-empty" hidden><p class="tds-empty-title">Open from a manifest Sales link, or enter a route day + trip date</p></div>' +
    '<div class="table-responsive"><table class="tds-table"><thead><tr><th>Seat</th><th>Ticket</th><th>Passenger</th><th>Phone</th><th>Issuer</th><th>Cost</th></tr></thead><tbody id="ss-body"></tbody></table></div></div>';

  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  function load() {
    var rd = document.getElementById('ss-rd').value;
    var dt = document.getElementById('ss-date').value;
    var man = document.getElementById('ss-man').value;
    if (!rd || !dt) {
      document.getElementById('ss-empty').hidden = false;
      return;
    }
    var p = new URLSearchParams({ route_day_id: rd, trip_date: dt });
    if (man) p.set('manifest', man);
    fetch('/tickets/api/console/sales_summary.php?' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) {
        return r.json();
      })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Failed');
        var d = body.data || {};
        var ctx = d.context || {};
        var sum = d.summary || {};
        document.getElementById('ss-err').hidden = true;
        document.getElementById('ss-route').textContent = ctx.route || '—';
        document.getElementById('ss-bus').textContent = ctx.bus || '—';
        document.getElementById('ss-count').textContent = String(sum.tickets || 0);
        document.getElementById('ss-total').textContent = money(sum.total_sales);
        document.getElementById('ss-crew').textContent =
          'Driver: ' + (ctx.driver || '—') + ' · Conductor: ' + (ctx.conductor || '—') + ' · Trip ' + (ctx.trip_date || '');
        var items = d.items || [];
        document.getElementById('ss-empty').hidden = items.length > 0;
        document.getElementById('ss-body').innerHTML = items
          .map(function (it) {
            return (
              '<tr><td>' +
              esc(it.seat_no) +
              '</td><td class="tds-mono">' +
              esc(it.id) +
              '</td><td>' +
              esc(it.fullname) +
              '</td><td>' +
              esc(it.phone) +
              '</td><td>' +
              esc(it.issuer) +
              '</td><td class="tds-mono">' +
              money(it.cost) +
              '</td></tr>'
            );
          })
          .join('');
      })
      .catch(function (e) {
        var err = document.getElementById('ss-err');
        err.hidden = false;
        err.textContent = e.message || 'Failed';
      });
  }
  document.getElementById('ss-go').onclick = load;
  document.getElementById('ss-print').onclick = function () {
    window.print();
  };
  if (routeDayId && tripDate) load();
})();
