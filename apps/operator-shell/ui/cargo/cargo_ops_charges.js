/**
 * Cargo charge variants — one script, kind on the page root.
 */
(function () {
  'use strict';

  var roots = document.querySelectorAll('.tds-page[data-kind]');
  roots.forEach(boot);

  function boot(root) {
    if (!root || root.getAttribute('data-booted') === '1') return;
    root.setAttribute('data-booted', '1');

    var kind = root.getAttribute('data-kind') || 'detail';
    var state = { page: 1, perPage: 50, total: 0 };
    var q = function (role) {
      return root.querySelector('[data-role="' + role + '"]');
    };
    var errEl = q('error');
    var loadingEl = q('loading');
    var emptyEl = q('empty');
    var wrapEl = q('wrap');
    var pager = q('pager');

    function showError(msg) {
      if (!errEl) return;
      errEl.hidden = !msg;
      errEl.textContent = msg || '';
    }
    function setLoading(on) {
      if (loadingEl) loadingEl.hidden = !on;
      if (on) {
        if (wrapEl) wrapEl.hidden = true;
        if (emptyEl) emptyEl.hidden = true;
        if (pager) pager.hidden = true;
      }
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
    function fetchJson(url, options) {
      return fetch(url, options || { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body || body.ok === false) {
            var message = body && body.error && body.error.message ? body.error.message : 'Request failed';
            throw new Error(message);
          }
          return body;
        });
      });
    }
    function monthBounds() {
      var now = new Date();
      var y = now.getFullYear();
      var m = String(now.getMonth() + 1).padStart(2, '0');
      var last = new Date(y, now.getMonth() + 1, 0).getDate();
      return { from: y + '-' + m + '-01', to: y + '-' + m + '-' + String(last).padStart(2, '0') };
    }
    function fillSelect(el, values, placeholder) {
      if (!el) return;
      var current = el.value;
      var html = placeholder ? '<option value="">' + esc(placeholder) + '</option>' : '';
      (values || []).forEach(function (v) {
        var value = typeof v === 'string' ? v : v.username;
        var label = typeof v === 'string' ? v : v.display_name || v.username;
        html += '<option value="' + esc(value) + '">' + esc(label) + '</option>';
      });
      el.innerHTML = html;
      if (current) el.value = current;
    }

    function endpoint() {
      if (kind === 'summary') return '/cargo/api/console/user_charge_summary.php';
      if (kind === 'multi') return '/cargo/api/console/user_charges_multi.php';
      if (kind === 'manifest-user') return '/cargo/api/console/manifest_lines.php';
      if (kind === 'manifest-mine') return '/cargo/api/console/manifest_lines.php';
      return '/cargo/api/console/user_charge.php';
    }

    function filterParams() {
      var params = new URLSearchParams();
      params.set('page', String(state.page));
      params.set('per_page', String(state.perPage));
      if (kind === 'manifest-user') params.set('scope', 'user');
      if (kind === 'manifest-mine') params.set('scope', 'mine');
      var userEl = q('user');
      var routeEl = q('route');
      var busEl = q('bus');
      var dateEl = q('date');
      var fromEl = q('from');
      var toEl = q('to');
      var qEl = q('q');
      if (userEl && userEl.value && q('user-wrap') && !q('user-wrap').hidden) params.set('user', userEl.value);
      if (routeEl && routeEl.value) params.set('route', routeEl.value);
      if (busEl && busEl.value) params.set('bus', busEl.value);
      if (dateEl && dateEl.value) params.set('date', dateEl.value);
      if (fromEl && fromEl.value) params.set('date_from', fromEl.value);
      if (toEl && toEl.value) params.set('date_to', toEl.value);
      if (qEl && qEl.value.trim()) params.set('q', qEl.value.trim());
      return params;
    }

    function headers() {
      var thead = q('thead');
      if (!thead) return;
      var cols;
      if (kind === 'summary' || kind === 'multi') {
        cols = ['Seller', 'Date', 'Route', 'Receipts', 'Paid', 'To be paid', 'Amount'];
      } else if (kind === 'manifest-user' || kind === 'manifest-mine') {
        cols = ['Owner', 'Route', 'Vehicle', 'Trip date', 'Detail', 'Code', 'Amount', 'Paid', 'To pay', 'Qty'];
      } else {
        cols = ['Sender', 'Receiver', 'Route', 'Date', 'Detail', 'Code', 'Amount', 'Paid', 'To pay', 'Qty'];
      }
      thead.innerHTML = '<tr>' + cols.map(function (c) { return '<th>' + esc(c) + '</th>'; }).join('') + '</tr>';
    }

    function rowHtml(it) {
      if (kind === 'summary' || kind === 'multi') {
        return '<td>' + esc(it.username) + '</td><td>' + esc(it.date_label || it.date) + '</td><td>' + esc(it.route) +
          '</td><td>' + esc(it.receipts) + '</td><td class="tds-mono">' + money(it.paid) + '</td><td class="tds-mono">' +
          money(it.to_be) + '</td><td class="tds-mono">' + money(it.amount) + '</td>';
      }
      if (kind === 'manifest-user' || kind === 'manifest-mine') {
        return '<td>' + esc(it.owner) + '</td><td>' + esc(it.route) + '</td><td>' + esc(it.bus) + '</td><td>' +
          esc(it.date_label) + '</td><td>' + esc(it.detail) + '</td><td class="tds-mono"><strong>' + esc(it.code) +
          '</strong></td><td class="tds-mono">' + money(it.amount) + '</td><td class="tds-mono">' + money(it.paid) +
          '</td><td class="tds-mono">' + money(it.to_be) + '</td><td>' + esc(it.quantity) + '</td>';
      }
      return '<td>' + esc(it.sender_name) + '</td><td>' + esc(it.receiver_name) + '</td><td>' + esc(it.route) +
        '</td><td>' + esc(it.date_label) + '</td><td>' + esc(it.detail) + '</td><td class="tds-mono"><strong>' +
        esc(it.code) + '</strong></td><td class="tds-mono">' + money(it.amount) + '</td><td class="tds-mono">' +
        money(it.paid) + '</td><td class="tds-mono">' + money(it.to_be) + '</td><td>' + esc(it.quantity) + '</td>';
    }

    function renderRows(items) {
      var tbody = q('tbody');
      if (!tbody) return;
      tbody.innerHTML = '';
      if (!items || !items.length) {
        if (emptyEl) emptyEl.hidden = false;
        if (wrapEl) wrapEl.hidden = true;
        if (pager) pager.hidden = true;
        return;
      }
      if (emptyEl) emptyEl.hidden = true;
      if (wrapEl) wrapEl.hidden = false;
      items.forEach(function (it) {
        var tr = document.createElement('tr');
        tr.innerHTML = rowHtml(it);
        tbody.appendChild(tr);
      });
    }

    function renderPager() {
      var metaEl = q('meta');
      if (!pager || !metaEl) return;
      var pages = Math.max(1, Math.ceil(state.total / state.perPage));
      if (state.total === 0) {
        pager.hidden = true;
        return;
      }
      pager.hidden = false;
      metaEl.textContent = 'Page ' + state.page + ' of ' + pages + ' · ' + state.total.toLocaleString() + ' rows';
      var prev = q('prev');
      var next = q('next');
      if (prev) prev.disabled = state.page <= 1;
      if (next) next.disabled = state.page >= pages;
    }

    function setSummary(data) {
      var summary = data.summary || {};
      var sellerEl = q('seller');
      var countEl = q('count');
      var paidEl = q('paid');
      var tobeEl = q('tobe');
      if (sellerEl) {
        sellerEl.textContent = kind === 'multi' ? 'All sellers' : (data.seller || '—');
      }
      if (countEl) countEl.textContent = String(summary.count != null ? summary.count : (data.total || 0));
      if (paidEl) paidEl.textContent = money(summary.paid);
      if (tobeEl) tobeEl.textContent = money(summary.to_be);
    }

    function loadList() {
      showError('');
      setLoading(true);
      fetchJson(endpoint() + '?' + filterParams().toString())
        .then(function (body) {
          var data = body.data || {};
          state.total = Number(data.total || 0);
          if (body.meta && body.meta.page) state.page = Number(body.meta.page);
          setSummary(data);
          renderRows(data.items || []);
          renderPager();
          if (data.buses) fillSelect(q('bus'), data.buses, 'All vehicles');
        })
        .catch(function (e) {
          showError(e.message || 'Could not load charges');
          if (emptyEl) emptyEl.hidden = true;
          if (wrapEl) wrapEl.hidden = true;
        })
        .then(function () {
          setLoading(false);
        });
    }

    headers();
    var bounds = monthBounds();
    var fromEl = q('from');
    var toEl = q('to');
    var dateEl = q('date');
    if (fromEl && !fromEl.value) fromEl.value = bounds.from;
    if (toEl && !toEl.value) toEl.value = bounds.to;
    if (dateEl && !dateEl.value) dateEl.value = bounds.to;

    var countLabel = q('count-label');
    if (countLabel) {
      countLabel.textContent = kind === 'detail' ? 'Parcels' : (kind.indexOf('manifest') === 0 ? 'Lines' : 'Rows');
    }

    var apply = q('apply');
    if (apply) {
      apply.addEventListener('click', function () {
        state.page = 1;
        loadList();
      });
    }
    var prev = q('prev');
    var next = q('next');
    if (prev) prev.addEventListener('click', function () { if (state.page > 1) { state.page -= 1; loadList(); } });
    if (next) next.addEventListener('click', function () { state.page += 1; loadList(); });

    fetchJson('/cargo/api/console/ops_filters.php')
      .then(function (body) {
        var data = body.data || {};
        var userWrap = q('user-wrap');
        if (userWrap && !data.can_pick_user && kind !== 'manifest-user') {
          userWrap.hidden = true;
        }
        fillSelect(q('user'), data.users || [], '');
        fillSelect(q('route'), data.routes || [], 'All my routes');
        fillSelect(q('bus'), data.buses || [], 'All vehicles');
        if (data.me && q('user')) q('user').value = data.me;
      })
      .catch(function () {})
      .then(loadList);
  }
})();
