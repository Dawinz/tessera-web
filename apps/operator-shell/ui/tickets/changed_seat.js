/**
 * T-P1-07 Changed seats.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-changed-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var body = document.getElementById('tds-changed-root-body') || root;
  body.innerHTML =
    '<div class="tds-toolbar"><div class="tds-field" style="flex:1"><label class="tds-label" for="c-q">Search</label>' +
    '<input class="tds-input" id="c-q" type="search" placeholder="Ticket, seat, user…"></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="c-go">Search</button></div>' +
    '<div class="tds-alert tds-alert-danger" id="c-err" hidden></div>' +
    '<div class="tds-table-panel" style="margin-top:var(--tds-space-4)"><div id="c-empty" class="tds-empty" hidden><p class="tds-empty-title">No seat changes</p></div>' +
    '<div class="table-responsive"><table class="tds-table"><thead><tr><th>ID</th><th>Ticket</th><th>Change</th><th>By</th><th>When</th></tr></thead><tbody id="c-body"></tbody></table></div>' +
    '<div class="tds-pager" id="c-pager" hidden><p class="tds-pager-meta" id="c-meta"></p>' +
    '<div class="tds-row"><button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="c-prev">Previous</button>' +
    '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="c-next">Next</button></div></div></div>';

  var state = { page: 1, perPage: 100, total: 0 };
  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function load() {
    var p = new URLSearchParams({ page: String(state.page), per_page: String(state.perPage), q: document.getElementById('c-q').value || '' });
    fetch('/tickets/api/console/changed_seats.php?' + p, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) {
        return r.json();
      })
      .then(function (body) {
        if (!body.ok) throw new Error((body.error && body.error.message) || 'Failed');
        var d = body.data || {};
        state.total = Number(d.total || 0);
        document.getElementById('c-err').hidden = true;
        var items = d.items || [];
        document.getElementById('c-empty').hidden = items.length > 0;
        document.getElementById('c-body').innerHTML = items
          .map(function (it) {
            return (
              '<tr><td class="tds-mono">' +
              esc(it.id) +
              '</td><td class="tds-mono">' +
              esc(it.ticket_id) +
              '</td><td>' +
              esc(it.summary) +
              '</td><td>' +
              esc(it.username) +
              '</td><td>' +
              esc(it.changed_time) +
              '</td></tr>'
            );
          })
          .join('');
        var pages = Math.max(1, Math.ceil(state.total / state.perPage));
        document.getElementById('c-pager').hidden = state.total <= state.perPage;
        document.getElementById('c-meta').textContent = 'Page ' + state.page + ' of ' + pages;
      })
      .catch(function (e) {
        var err = document.getElementById('c-err');
        err.hidden = false;
        err.textContent = e.message || 'Failed';
      });
  }
  document.getElementById('c-go').onclick = function () {
    state.page = 1;
    load();
  };
  document.getElementById('c-prev').onclick = function () {
    if (state.page > 1) {
      state.page--;
      load();
    }
  };
  document.getElementById('c-next').onclick = function () {
    state.page++;
    load();
  };
  load();
})();
