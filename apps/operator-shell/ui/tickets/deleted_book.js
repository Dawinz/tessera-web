/**
 * T-P1-06 Deleted tickets.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-deleted-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-deleted-root-body') || root;
  body.innerHTML =
    '<div class="tds-toolbar"><div class="tds-field" style="flex:1"><label class="tds-label" for="d-q">Search</label>' +
    '<input class="tds-input" id="d-q" type="search" placeholder="Seat, user, phone…"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="d-go">Search</button></div>' +
    '<div class="tds-alert tds-alert-danger" id="d-err" hidden></div>' +
    '<div class="tds-alert tds-alert-success" id="d-ok" hidden></div>' +
    '<div class="tds-table-panel" style="margin-top:var(--tds-space-4)"><div id="d-empty" class="tds-empty" hidden><p class="tds-empty-title">No deleted tickets</p></div>' +
    '<div class="table-responsive"><table class="tds-table"><thead><tr><th>ID</th><th>Seat</th><th>Trip</th><th>Passenger user</th><th>Deleted by</th><th>When</th><th>Phone</th><th></th></tr></thead><tbody id="d-body"></tbody></table></div>' +
    '<div class="tds-pager" id="d-pager" hidden><p class="tds-pager-meta" id="d-meta"></p>' +
    '<div class="tds-row"><button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="d-prev">Previous</button>' +
    '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="d-next">Next</button></div></div></div>';

  var state = { page: 1, perPage: 100, total: 0 };
  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function load() {
    var p = new URLSearchParams({ page: String(state.page), per_page: String(state.perPage), q: document.getElementById('d-q').value || '' });
    fetch('/tickets/api/console/deleted_tickets.php?' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) {
        return r.json();
      })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Failed');
        var d = body.data || {};
        state.total = Number(d.total || 0);
        document.getElementById('d-err').hidden = true;
        var items = d.items || [];
        document.getElementById('d-empty').hidden = items.length > 0;
        document.getElementById('d-body').innerHTML = items
          .map(function (it) {
            var action = it.can_restore
              ? '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-restore="' + it.id + '">Restore</button>'
              : '<span class="tds-muted">Restored</span>';
            return (
              '<tr><td class="tds-mono">' +
              esc(it.id) +
              '</td><td>' +
              esc(it.seat_no) +
              ' <span class="tds-mono">#' +
              esc(it.seat_id) +
              '</span></td><td>' +
              esc(it.trip_date) +
              '</td><td>' +
              esc(it.ticket_user || it.username) +
              '</td><td>' +
              esc(it.ticket_deleter) +
              '</td><td>' +
              esc(it.delete_date) +
              '</td><td>' +
              esc(it.phone_no) +
              '</td><td>' +
              action +
              '</td></tr>'
            );
          })
          .join('');
        var pages = Math.max(1, Math.ceil(state.total / state.perPage));
        document.getElementById('d-pager').hidden = state.total <= state.perPage;
        document.getElementById('d-meta').textContent = 'Page ' + state.page + ' of ' + pages;
      })
      .catch(function (e) {
        var err = document.getElementById('d-err');
        err.hidden = false;
        err.textContent = e.message || 'Failed';
      });
  }
  document.getElementById('d-go').onclick = function () {
    state.page = 1;
    load();
  };
  document.getElementById('d-prev').onclick = function () {
    if (state.page > 1) {
      state.page--;
      load();
    }
  };
  document.getElementById('d-next').onclick = function () {
    state.page++;
    load();
  };
  document.getElementById('d-body').addEventListener('click', function (ev) {
    var btn = ev.target.closest('[data-restore]');
    if (!btn) return;
    if (!window.confirm('Restore this ticket?')) return;
    fetch('/tickets/api/console/deleted_tickets.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: Number(btn.getAttribute('data-restore')) }),
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Restore failed');
        var ok = document.getElementById('d-ok');
        ok.hidden = false;
        ok.textContent = 'Ticket restored';
        load();
      })
      .catch(function (e) {
        var err = document.getElementById('d-err');
        err.hidden = false;
        err.textContent = e.message || 'Restore failed';
      });
  });
  load();
})();
