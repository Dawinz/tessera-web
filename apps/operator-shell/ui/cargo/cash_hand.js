/**
 * C-P3-02 Cash in hand.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-cg-cash-hand');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-cg-cash-hand-body');
  var err = document.getElementById('tds-cg-cash-hand-error');
  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  fetch('/cargo/api/console/cash_hand.php', { credentials: 'same-origin', headers: { Accept: 'application/json' } })
    .then(function (r) { return r.json(); })
    .then(function (res) {
      if (!res.ok) throw new Error((res.error && res.error.message) || 'Failed');
      var d = res.data || {};
      var items = d.items || [];
      var note = '<p class="tds-muted">' + esc(d.note || '') + '</p>';
      if (!d.available || !items.length) {
        body.innerHTML = note + '<div class="tds-empty"><p class="tds-empty-title">No cash-in-hand rows</p><p>Use daily expenses in Accounts to record office money.</p></div>';
      } else {
        var rows = items.map(function (it) {
          return '<tr><td>' + esc(it.authorizer) + '</td><td class="tds-mono">' + esc(it.date) + '</td><td class="tds-mono">' +
            money(it.amount) + '</td><td class="tds-mono">' + money(it.remain) + '</td><td class="tds-mono">' + esc(it.voucher) +
            '</td><td>' + esc(it.time) + '</td></tr>';
        }).join('');
        body.innerHTML = note + '<div class="table-responsive"><table class="tds-table"><thead><tr><th>Authorizer</th><th>Date</th><th>Amount</th><th>Remain</th><th>Voucher</th><th>Time</th></tr></thead><tbody>' +
          rows + '</tbody></table></div>';
      }
      err.hidden = true;
      root.setAttribute('data-ready', '1');
    })
    .catch(function (e) {
      err.hidden = false;
      err.textContent = e.message || 'Failed';
      root.setAttribute('data-ready', '0');
    });
})();
