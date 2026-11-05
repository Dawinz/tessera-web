/**
 * T-P3-02 Earnings profile.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-tk-earnings');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-tk-earnings-body');
  var err = document.getElementById('tds-tk-earnings-error');
  var initial = new URLSearchParams(window.location.search).get('u') || '';
  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  body.innerHTML =
    '<div class="tds-toolbar"><div class="tds-field" style="flex:1"><label class="tds-label" for="e-user">Username</label>' +
    '<input class="tds-input" id="e-user" value="' + esc(initial) + '" placeholder="Leave blank for you"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="e-go">Show</button></div>' +
    '<div id="e-out"></div>';

  function load() {
    var u = document.getElementById('e-user').value.trim();
    var p = u ? '?u=' + encodeURIComponent(u) : '';
    fetch('/tickets/api/console/earnings_profile.php' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res.ok) throw new Error((res.error && res.error.message) || 'Failed');
        var d = res.data || {};
        var person = d.person || {};
        var name = (person.firstname || person.surname) ? (person.firstname + ' ' + person.surname).trim() : person.username;
        var items = d.items || [];
        var rows = items.map(function (it) {
          return '<tr><td class="tds-mono">' + esc(it.id) + '</td><td>' + esc(it.passenger) + '</td><td>' + esc(it.seat_no) +
            '</td><td class="tds-mono">' + money(it.cost) + '</td><td>' + esc(it.trip_date) + '</td><td>' + esc(it.bus) + '</td></tr>';
        }).join('');
        var empty = items.length ? '' : '<div class="tds-empty"><p class="tds-empty-title">No tickets this month</p></div>';
        err.hidden = true;
        document.getElementById('e-out').innerHTML =
          '<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">' +
          '<div class="tds-kpi"><p class="tds-kpi-label">Staff</p><p class="tds-kpi-value" id="e-name">' + esc(name || '—') + '</p></div>' +
          '<div class="tds-kpi"><p class="tds-kpi-label" id="e-month">' + esc(d.month_label || 'This month') + '</p><p class="tds-kpi-value tds-mono" id="e-total">' + money(d.month_total) + '</p></div>' +
          '<div class="tds-kpi"><p class="tds-kpi-label">Tickets</p><p class="tds-kpi-value" id="e-count">' + esc(d.tickets) + '</p></div></div>' +
          '<p class="tds-muted">' + esc(person.phone || '') + (person.found ? '' : ' · No matching staff username') + '</p>' +
          empty +
          '<div class="table-responsive"><table class="tds-table"><thead><tr><th>#</th><th>Passenger</th><th>Seat</th><th>Cost</th><th>Trip</th><th>Bus</th></tr></thead><tbody>' +
          rows + '</tbody></table></div>';
        root.setAttribute('data-ready', '1');
      })
      .catch(function (e) {
        err.hidden = false;
        err.textContent = e.message || 'Failed';
        root.setAttribute('data-ready', '0');
      });
  }
  document.getElementById('e-go').onclick = load;
  load();
})();
