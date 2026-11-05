/**
 * T-P1-08 Charge summary.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-charge-sum-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-charge-sum-root-body') || root;
  var today = new Date().toISOString().slice(0, 10);
  body.innerHTML =
    '<div class="tds-toolbar tds-date-range">' +
    '<div class="tds-field"><label class="tds-label" for="cs-from">From trip date</label><input class="tds-input" id="cs-from" type="date" value="' + today + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="cs-to">To trip date</label><input class="tds-input" id="cs-to" type="date" value="' + today + '"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="cs-go">Search</button></div>' +
    '<div class="tds-alert tds-alert-danger" id="cs-err" hidden></div>' +
    '<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Sellers</p><p class="tds-kpi-value" id="cs-sellers">—</p></div>' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Lines</p><p class="tds-kpi-value" id="cs-lines">—</p></div>' +
    '<div class="tds-kpi"><p class="tds-kpi-label">Total sales</p><p class="tds-kpi-value tds-mono" id="cs-total">—</p></div></div>' +
    '<div class="tds-table-panel"><div id="cs-empty" class="tds-empty" hidden><p class="tds-empty-title">No charges in this range</p></div>' +
    '<div class="table-responsive"><table class="tds-table"><thead><tr><th>Seller</th><th>Route</th><th>Tickets</th><th>Sales</th></tr></thead><tbody id="cs-body"></tbody></table></div></div>';

  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  function load() {
    var p = new URLSearchParams({
      date_from: document.getElementById('cs-from').value,
      date_to: document.getElementById('cs-to').value,
    });
    fetch('/tickets/api/console/charge_summary.php?' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) {
        return r.json();
      })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Failed');
        var d = body.data || {};
        var s = d.summary || {};
        document.getElementById('cs-err').hidden = true;
        document.getElementById('cs-sellers').textContent = String(s.sellers || 0);
        document.getElementById('cs-lines').textContent = String(s.lines || 0);
        document.getElementById('cs-total').textContent = money(s.total_sales);
        var items = d.items || [];
        document.getElementById('cs-empty').hidden = items.length > 0;
        document.getElementById('cs-body').innerHTML = items
          .map(function (it) {
            return (
              '<tr><td>' +
              esc(it.booker) +
              '</td><td>' +
              esc(it.route) +
              '</td><td class="tds-mono">' +
              esc(it.tickets) +
              '</td><td class="tds-mono">' +
              money(it.sales) +
              '</td></tr>'
            );
          })
          .join('');
      })
      .catch(function (e) {
        var err = document.getElementById('cs-err');
        err.hidden = false;
        err.textContent = e.message || 'Failed';
      });
  }
  document.getElementById('cs-go').onclick = load;
  load();
})();
