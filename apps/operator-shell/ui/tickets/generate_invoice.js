/**
 * T-P3-01 Hire invoice preview.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-tk-hire');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-tk-hire-body');
  var err = document.getElementById('tds-tk-hire-error');
  var qs = new URLSearchParams(window.location.search);
  function val(name) {
    return qs.get(name) || '';
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
  body.innerHTML =
    '<div class="tds-toolbar">' +
    '<div class="tds-field"><label class="tds-label" for="h-bus">Bus</label><input class="tds-input" id="h-bus" value="' + esc(val('bus')) + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="h-route">Route</label><input class="tds-input" id="h-route" value="' + esc(val('route')) + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="h-date">Date</label><input class="tds-input" id="h-date" type="date" value="' + esc(val('date')) + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="h-time">Time</label><input class="tds-input" id="h-time" value="' + esc(val('time')) + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="h-class">Class</label><input class="tds-input" id="h-class" value="' + esc(val('class')) + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="h-seats">Seats</label><input class="tds-input" id="h-seats" inputmode="numeric" value="' + esc(val('seats')) + '"></div>' +
    '<div class="tds-field"><label class="tds-label" for="h-fare">Fare</label><input class="tds-input" id="h-fare" inputmode="decimal" value="' + esc(val('fare')) + '"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="h-go">Preview</button></div>' +
    '<div id="h-sheet"></div>';

  function paint(d) {
    var items = d.items || [];
    var rows = items
      .map(function (it) {
        return (
          '<tr><td>' + esc(it.description) + '<div class="tds-muted">' + esc(it.detail) + '</div></td><td class="tds-mono">' +
          esc(it.qty) + '</td><td class="tds-mono">' + money(it.unit) + '</td><td class="tds-mono">' + money(it.amount) + '</td></tr>'
        );
      })
      .join('');
    var empty = items.length
      ? ''
      : '<div class="tds-empty" id="h-empty"><p class="tds-empty-title">No lines yet</p><p>Enter seats and a fare, then preview.</p></div>';
    document.getElementById('h-sheet').innerHTML =
      '<div class="tds-card" style="margin-top:var(--tds-space-4)"><div class="tds-card-body">' +
      '<p class="tds-muted" id="h-company">' + esc(d.company || 'Company') + '</p>' +
      '<p>Invoice <span class="tds-mono" id="h-no">' + esc(d.invoice_no) + '</span> · Issued ' + esc(d.issue_date) + ' · Due ' + esc(d.due_date) + '</p>' +
      empty +
      '<div class="table-responsive"><table class="tds-table"><thead><tr><th>Description</th><th>Qty</th><th>Fare</th><th>Amount</th></tr></thead><tbody>' +
      rows +
      '</tbody></table></div>' +
      '<p style="margin-top:var(--tds-space-3)">Total <strong class="tds-mono" id="tds-tk-hire-total">' + money(d.total) + '</strong></p>' +
      '</div></div>';
  }

  function load() {
    var p = new URLSearchParams({
      bus: document.getElementById('h-bus').value,
      route: document.getElementById('h-route').value,
      date: document.getElementById('h-date').value,
      time: document.getElementById('h-time').value,
      class: document.getElementById('h-class').value,
      seats: document.getElementById('h-seats').value || '0',
      fare: document.getElementById('h-fare').value || '0',
    });
    fetch('/tickets/api/console/hire_invoice.php?' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Failed');
        err.hidden = true;
        paint(body.data || {});
        root.setAttribute('data-ready', '1');
      })
      .catch(function (e) {
        err.hidden = false;
        err.textContent = e.message || 'Failed';
        root.setAttribute('data-ready', '0');
      });
  }

  document.getElementById('h-go').onclick = load;
  var printBtn = document.getElementById('tds-tk-hire-print');
  if (printBtn) printBtn.onclick = function () { window.print(); };
  load();
})();
