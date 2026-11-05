/**
 * C-P1-02 My luggages.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-my-luggages-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var state = { page: 1, perPage: 50, total: 0, store: '' };
  var errEl = document.getElementById('tds-my-error');
  var loadingEl = document.getElementById('tds-my-loading');
  var emptyEl = document.getElementById('tds-my-empty');
  var wrapEl = document.getElementById('tds-my-wrap');
  var tbody = document.getElementById('tds-my-tbody');
  var pager = document.getElementById('tds-my-pager');

  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function showError(msg) {
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function badge(kind, label) {
    var cls = kind === 'paid' ? 'tds-badge-paid' : kind === 'due' ? 'tds-badge-due' : kind === 'warn' ? 'tds-badge-warn' : 'tds-badge-neutral';
    return '<span class="tds-badge ' + cls + '">' + esc(label) + '</span>';
  }
  function fetchJson(url) {
    return fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body || body.ok === false) throw new Error((body && body.error && body.error.message) || 'Request failed');
        return body;
      });
    });
  }
  function params() {
    var p = new URLSearchParams();
    p.set('page', String(state.page));
    p.set('per_page', String(state.perPage));
    if (state.store) p.set('store', state.store);
    var route = document.getElementById('tds-my-route');
    var from = document.getElementById('tds-my-from');
    var to = document.getElementById('tds-my-to');
    var q = document.getElementById('tds-my-q');
    if (route && route.value) p.set('route', route.value);
    if (from && from.value) p.set('date_from', from.value);
    if (to && to.value) p.set('date_to', to.value);
    if (q && q.value.trim()) p.set('q', q.value.trim());
    return p;
  }
  function render(items) {
    tbody.innerHTML = '';
    if (!items || !items.length) {
      emptyEl.hidden = false;
      wrapEl.hidden = true;
      pager.hidden = true;
      return;
    }
    emptyEl.hidden = true;
    wrapEl.hidden = false;
    items.forEach(function (it) {
      var tr = document.createElement('tr');
      tr.innerHTML = '<td class="tds-mono"><strong>' + esc(it.code) + '</strong></td><td>' + esc(it.sender_name) +
        '</td><td>' + esc(it.receiver_name) + '</td><td>' + esc(it.route) + '</td><td>' + esc(it.date_label) +
        '</td><td>' + badge('info', it.store_label) + '</td><td>' + badge(it.payment_badge, it.payment_label) +
        '</td><td>' + esc(it.quantity) + '</td><td><a class="tds-btn tds-btn-ghost tds-btn-sm" href="' + esc(it.view_url) +
        '">Status</a> <a class="tds-btn tds-btn-ghost tds-btn-sm" href="' + esc(it.edit_url) + '">Update</a></td>';
      tbody.appendChild(tr);
    });
    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    pager.hidden = state.total === 0;
    document.getElementById('tds-my-meta').textContent = 'Page ' + state.page + ' of ' + pages + ' · ' + state.total.toLocaleString();
    document.getElementById('tds-my-prev').disabled = state.page <= 1;
    document.getElementById('tds-my-next').disabled = state.page >= pages;
  }
  function load() {
    showError('');
    loadingEl.hidden = false;
    wrapEl.hidden = true;
    emptyEl.hidden = true;
    fetchJson('/cargo/api/console/my_luggages.php?' + params().toString())
      .then(function (body) {
        var data = body.data || {};
        state.total = Number(data.total || 0);
        if (body.meta && body.meta.page) state.page = Number(body.meta.page);
        render(data.items || []);
      })
      .catch(function (e) { showError(e.message || 'Could not load luggages'); })
      .then(function () { loadingEl.hidden = true; });
  }
  document.getElementById('tds-my-apply').addEventListener('click', function () { state.page = 1; load(); });
  document.getElementById('tds-my-prev').addEventListener('click', function () { if (state.page > 1) { state.page -= 1; load(); } });
  document.getElementById('tds-my-next').addEventListener('click', function () { state.page += 1; load(); });
  document.getElementById('tds-my-store-chips').addEventListener('click', function (ev) {
    var btn = ev.target.closest('[data-store]');
    if (!btn) return;
    state.store = btn.getAttribute('data-store') || '';
    state.page = 1;
    root.querySelectorAll('#tds-my-store-chips .tds-chip').forEach(function (el) { el.classList.toggle('is-active', el === btn); });
    load();
  });
  fetchJson('/cargo/api/console/ops_filters.php').then(function (body) {
    var routes = (body.data && body.data.routes) || [];
    var sel = document.getElementById('tds-my-route');
    routes.forEach(function (name) {
      var opt = document.createElement('option');
      opt.value = name;
      opt.textContent = name;
      sel.appendChild(opt);
    });
  }).catch(function () {}).then(load);
})();
