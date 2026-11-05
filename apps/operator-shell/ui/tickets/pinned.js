/**
 * T-P3-05 / T-P3-06 Pinned seats.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-tk-pinned') || document.getElementById('tds-tk-pinned-few');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var limit = root.getAttribute('data-limit') || '100';
  var err = document.getElementById(root.id + '-error');
  var body = document.getElementById(root.id + '-body');
  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function paint(data) {
    var items = data.items || [];
    if (!items.length) {
      body.innerHTML = '<div class="tds-empty"><p class="tds-empty-title">No pinned trips</p><p>Pins you save while booking show up here.</p></div>';
      return;
    }
    body.innerHTML = '<div class="tds-table-panel"><div class="table-responsive"><table class="tds-table"><thead><tr><th>Trip</th><th>Date</th><th>Day</th><th></th></tr></thead><tbody>' +
      items.map(function (it) {
        return '<tr data-pin="' + esc(it.id) + '"><td>' + esc(it.route) + '</td><td class="tds-mono">' + esc(it.date) +
          '</td><td>' + esc(it.day) + '</td><td class="tds-row"><a class="tds-btn tds-btn-secondary tds-btn-sm" href="' +
          esc(it.book_url) + '">Book</a><button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" data-unpin="' +
          esc(it.id) + '">Remove</button></td></tr>';
      }).join('') +
      '</tbody></table></div><p class="tds-muted" style="margin-top:var(--tds-space-3)">Showing ' + items.length + ' of ' + esc(data.total) + '</p></div>';
    body.querySelectorAll('[data-unpin]').forEach(function (btn) {
      btn.onclick = function () {
        fetch('/tickets/api/console/pinned_delete.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
          body: JSON.stringify({ id: Number(btn.getAttribute('data-unpin')) }),
        })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            if (!res.ok) throw new Error((res.error && res.error.message) || 'Failed');
            load();
          })
          .catch(function (e) {
            err.hidden = false;
            err.textContent = e.message || 'Failed';
          });
      };
    });
  }
  function load() {
    fetch('/tickets/api/console/pinned_seats.php?limit=' + encodeURIComponent(limit), {
      credentials: 'same-origin',
      headers: { Accept: 'application/json' },
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (!res.ok) throw new Error((res.error && res.error.message) || 'Failed');
        err.hidden = true;
        paint(res.data || {});
        root.setAttribute('data-ready', '1');
      })
      .catch(function (e) {
        err.hidden = false;
        err.textContent = e.message || 'Failed';
        root.setAttribute('data-ready', '0');
      });
  }
  load();
})();
