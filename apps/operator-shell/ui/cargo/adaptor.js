/**
 * C-P3-01 Adaptor.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-cg-adaptor');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-cg-adaptor-body');
  var err = document.getElementById('tds-cg-adaptor-error');
  var today = new Date().toISOString().slice(0, 10);
  var qsDate = new URLSearchParams(window.location.search).get('date') || today;
  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  body.innerHTML =
    '<div class="tds-toolbar"><div class="tds-field"><label class="tds-label" for="a-date">Date</label><input class="tds-input" id="a-date" type="date" value="' + esc(qsDate) + '"></div>' +
    '<div class="tds-field" style="flex:1"><label class="tds-label" for="a-q">Code or name</label><input class="tds-input" id="a-q" type="search"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="a-go">Search</button></div><div id="a-out"></div>';

  function load() {
    var p = new URLSearchParams({
      date: document.getElementById('a-date').value,
      q: document.getElementById('a-q').value || '',
    });
    fetch('/cargo/api/console/adaptor.php?' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res.ok) throw new Error((res.error && res.error.message) || 'Failed');
        var d = res.data || {};
        var items = d.items || [];
        err.hidden = true;
        var rows = items.map(function (it) {
          return '<tr><td class="tds-mono">' + esc(it.code) + '</td><td>' + esc(it.sender) + '</td><td>' + esc(it.receiver) +
            '</td><td>' + esc(it.route) + '</td><td class="tds-mono">' + money(it.paid) + '</td><td class="tds-mono">' + money(it.to_be) +
            '</td><td class="tds-mono">' + money(it.amount) + '</td><td><a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
            esc(it.edit_url) + '">Open</a></td></tr>';
        }).join('');
        var empty = items.length ? '' : '<div class="tds-empty"><p class="tds-empty-title">No luggage on this day</p></div>';
        document.getElementById('a-out').innerHTML =
          '<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">' +
          '<div class="tds-kpi"><p class="tds-kpi-label">Lines</p><p class="tds-kpi-value" id="a-count">' + esc(d.total) + '</p></div>' +
          '<div class="tds-kpi"><p class="tds-kpi-label">Paid</p><p class="tds-kpi-value tds-mono">' + money(d.paid) + '</p></div>' +
          '<div class="tds-kpi"><p class="tds-kpi-label">To pay</p><p class="tds-kpi-value tds-mono">' + money(d.to_be) + '</p></div></div>' +
          empty +
          '<div class="table-responsive"><table class="tds-table"><thead><tr><th>Code</th><th>Sender</th><th>Receiver</th><th>Route</th><th>Paid</th><th>To pay</th><th>Amount</th><th></th></tr></thead><tbody>' +
          rows + '</tbody></table></div>';
        root.setAttribute('data-ready', '1');
      })
      .catch(function (e) {
        err.hidden = false;
        err.textContent = e.message || 'Failed';
        root.setAttribute('data-ready', '0');
      });
  }
  document.getElementById('a-go').onclick = load;
  load();
})();
