/**
 * C-P1-11 vehicle report and C-P1-12 checkout.
 * Checkout posts to the existing C-P0-05 toggle.
 */
(function () {
  'use strict';
  document.querySelectorAll('.tds-page[data-mode]').forEach(boot);

  function boot(root) {
    if (!root || root.id.indexOf('tds-vehicle-report') !== 0 || root.getAttribute('data-booted') === '1') return;
    root.setAttribute('data-booted', '1');
    var edit = root.getAttribute('data-mode') === 'edit';
    var state = { page: 1, perPage: 50, total: 0, date: '', bus: '' };
    var q = function (role) { return root.querySelector('[data-role="' + role + '"]'); };

    function esc(s) {
      return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function money(n) { return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 }); }
    function showError(msg) {
      var el = q('error');
      if (!el) return;
      el.hidden = !msg;
      el.textContent = msg || '';
    }
    function fetchJson(url, options) {
      return fetch(url, options || { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body || body.ok === false) throw new Error((body && body.error && body.error.message) || 'Request failed');
          return body;
        });
      });
    }
    function readUrl() {
      var params = new URLSearchParams(window.location.search);
      state.date = params.get('date') || '';
      state.bus = params.get('bus') || params.get('date3') || '';
    }
    function syncLinks(date, bus) {
      var qs = 'date=' + encodeURIComponent(date || '') + '&date3=' + encodeURIComponent(bus || '');
      var back = document.getElementById('tds-vr-back');
      var editLink = document.getElementById('tds-vr-edit');
      if (back) back.href = '/operator/app.php?module=cargo&page=vehicle_report_new.php&' + qs;
      if (editLink) editLink.href = '/operator/app.php?module=cargo&page=vehicle_report_new_edit.php&' + qs;
    }
    function paint(data) {
      var header = data.header || {};
      var summary = data.summary || {};
      state.date = header.date || state.date;
      state.bus = header.bus || state.bus;
      state.total = Number(data.total || 0);
      if (q('bus')) q('bus').textContent = header.bus || '—';
      if (q('date')) q('date').textContent = header.date_label || header.date || '—';
      if (q('crew')) q('crew').textContent = [header.driver, header.conductor].filter(Boolean).join(' · ') || '—';
      if (q('count')) q('count').textContent = String(summary.count || 0);
      if (q('checked')) q('checked').textContent = (summary.checked_out || 0) + ' checked out';
      if (q('paid')) q('paid').textContent = money(summary.paid);
      if (q('tobe')) q('tobe').textContent = money(summary.to_be);
      if (q('amount')) q('amount').textContent = money(summary.amount);
      if (q('code')) q('code').textContent = header.code || header.route || '—';
      syncLinks(header.date, header.bus);

      var trips = data.trips || [];
      var tripEl = q('trip');
      if (tripEl && trips.length && tripEl.options.length === 0) {
        tripEl.innerHTML = trips.map(function (t) {
          var key = t.date + '|' + t.bus;
          return '<option value="' + esc(key) + '">' + esc((t.date_label || t.date) + ' · ' + t.bus) + '</option>';
        }).join('');
      }
      if (tripEl && header.date && header.bus) tripEl.value = header.date + '|' + header.bus;

      var routes = data.by_route || [];
      if (q('routes')) {
        q('routes').innerHTML = routes.map(function (r) {
          return '<tr><td>' + esc(r.route) + '</td><td>' + esc(r.count) + '</td><td class="tds-mono">' + money(r.paid) +
            '</td><td class="tds-mono">' + money(r.to_be) + '</td><td class="tds-mono">' + money(r.loan) + '</td></tr>';
        }).join('');
      }
      if (q('route-wrap')) q('route-wrap').hidden = routes.length === 0;
      if (q('route-empty')) q('route-empty').hidden = routes.length !== 0;

      var items = data.items || [];
      if (q('lines')) {
        q('lines').innerHTML = items.map(function (it) {
          var out = String(it.checked || '') === 'out';
          var action = edit
            ? '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-check="' + esc(it.id) + '">' + (out ? 'Undo checkout' : 'Check out') + '</button>'
            : esc(out ? ('Out' + (it.taken_by ? ' · ' + it.taken_by : '')) : 'Pending');
          return '<tr data-line="' + esc(it.id) + '"><td class="tds-mono"><strong>' + esc(it.code) + '</strong></td><td>' +
            esc(it.owner) + '</td><td>' + esc(it.route) + '</td><td>' + esc(it.detail) + '</td><td>' + esc(it.status) +
            '</td><td class="tds-mono">' + money(it.paid) + '</td><td class="tds-mono">' + money(it.to_be) + '</td><td>' +
            esc(it.quantity) + '</td><td>' + action + '</td></tr>';
        }).join('');
      }
      if (q('wrap')) q('wrap').hidden = items.length === 0;
      if (q('empty')) q('empty').hidden = items.length !== 0;
      var pages = Math.max(1, Math.ceil(state.total / state.perPage));
      if (q('pager')) q('pager').hidden = state.total === 0;
      if (q('meta')) q('meta').textContent = 'Page ' + state.page + ' of ' + pages + ' · ' + state.total.toLocaleString();
      if (q('prev')) q('prev').disabled = state.page <= 1;
      if (q('next')) q('next').disabled = state.page >= pages;
    }
    function load() {
      showError('');
      if (q('loading')) q('loading').hidden = false;
      var params = new URLSearchParams();
      params.set('page', String(state.page));
      params.set('per_page', String(state.perPage));
      if (state.date) params.set('date', state.date);
      if (state.bus) params.set('bus', state.bus);
      fetchJson('/cargo/api/console/vehicle_report.php?' + params.toString())
        .then(function (body) { paint(body.data || {}); })
        .catch(function (e) { showError(e.message || 'Could not load report'); })
        .then(function () { if (q('loading')) q('loading').hidden = true; });
    }
    if (q('apply')) {
      q('apply').addEventListener('click', function () {
        var parts = (q('trip') && q('trip').value ? q('trip').value : '').split('|');
        state.date = parts[0] || '';
        state.bus = parts.slice(1).join('|');
        state.page = 1;
        load();
      });
    }
    if (q('prev')) q('prev').addEventListener('click', function () { if (state.page > 1) { state.page -= 1; load(); } });
    if (q('next')) q('next').addEventListener('click', function () { state.page += 1; load(); });
    if (edit && q('lines')) {
      q('lines').addEventListener('click', function (ev) {
        var btn = ev.target.closest('[data-check]');
        if (!btn) return;
        btn.disabled = true;
        fetchJson('/cargo/api/console/checkout_toggle.php', {
          method: 'POST',
          credentials: 'same-origin',
          headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: Number(btn.getAttribute('data-check')) })
        }).then(function () { load(); }).catch(function (e) {
          showError(e.message || 'Checkout failed');
          btn.disabled = false;
        });
      });
    }
    readUrl();
    load();
  }
})();
