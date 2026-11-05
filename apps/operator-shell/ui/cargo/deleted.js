/**
 * C-P1-03 Deletes audit.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-cargo-deleted-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var state = { page: 1, perPage: 50, total: 0 };

  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }
  function money(n) { return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 }); }
  function showError(msg) {
    var el = document.getElementById('tds-del-error');
    el.hidden = !msg;
    el.textContent = msg || '';
  }
  function load() {
    showError('');
    document.getElementById('tds-del-loading').hidden = false;
    var p = new URLSearchParams();
    p.set('page', String(state.page));
    p.set('per_page', String(state.perPage));
    var q = document.getElementById('tds-del-q').value.trim();
    if (q) p.set('q', q);
    fetch('/cargo/api/console/deleted_luggages.php?' + p.toString(), { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (res) { return res.json().then(function (body) { if (!res.ok || !body.ok) throw new Error((body.error && body.error.message) || 'Request failed'); return body; }); })
      .then(function (body) {
        var data = body.data || {};
        var items = data.items || [];
        state.total = Number(data.total || 0);
        var tbody = document.getElementById('tds-del-tbody');
        var wrap = document.getElementById('tds-del-wrap');
        var empty = document.getElementById('tds-del-empty');
        var pager = document.getElementById('tds-del-pager');
        tbody.innerHTML = items.map(function (it) {
          var note = String(it.notification || '').toLowerCase();
          var badge = note === 'delete' ? 'tds-badge-due' : 'tds-badge-info';
          return '<tr><td>' + esc(it.sender_name) + '</td><td>' + esc(it.receiver_name) + '</td><td class="tds-mono">' +
            esc(it.code) + '</td><td>' + esc(it.date) + '</td><td class="tds-mono">' + money(it.amount) + '</td><td class="tds-mono">' +
            money(it.paid) + '</td><td class="tds-mono">' + money(it.to_pay) + '</td><td>' + esc(it.what_changed) +
            '</td><td><span class="tds-badge ' + badge + '">' + esc(it.notification || it.status) + '</span></td><td>' +
            esc(it.actor) + '</td><td>' + esc(it.when) + '</td></tr>';
        }).join('');
        wrap.hidden = items.length === 0;
        empty.hidden = items.length !== 0;
        var pages = Math.max(1, Math.ceil(state.total / state.perPage));
        pager.hidden = state.total === 0;
        document.getElementById('tds-del-meta').textContent = 'Page ' + state.page + ' of ' + pages + ' · ' + state.total.toLocaleString();
        document.getElementById('tds-del-prev').disabled = state.page <= 1;
        document.getElementById('tds-del-next').disabled = state.page >= pages;
      })
      .catch(function (e) { showError(e.message || 'Could not load audit'); })
      .then(function () { document.getElementById('tds-del-loading').hidden = true; });
  }
  document.getElementById('tds-del-apply').addEventListener('click', function () { state.page = 1; load(); });
  document.getElementById('tds-del-prev').addEventListener('click', function () { if (state.page > 1) { state.page -= 1; load(); } });
  document.getElementById('tds-del-next').addEventListener('click', function () { state.page += 1; load(); });
  load();
})();
