/**
 * C-P1-01 Cargo home KPIs.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-cargo-home-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var routeEl = document.getElementById('tds-cargo-home-route');
  var errEl = document.getElementById('tds-cargo-home-error');
  var loadingEl = document.getElementById('tds-cargo-home-loading');

  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }

  function paint(data) {
    var k = data.kpis || {};
    var lead = document.getElementById('tds-cargo-home-lead');
    if (lead) lead.textContent = 'Monthly charges and receipts for ' + (data.month_label || 'this month') + '.';
    var map = {
      'tds-cargo-kpi-total': k.total,
      'tds-cargo-kpi-paid': k.paid,
      'tds-cargo-kpi-tobe': k.to_be
    };
    Object.keys(map).forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.textContent = money(map[id]);
    });
    var rec = document.getElementById('tds-cargo-kpi-receipts');
    if (rec) rec.textContent = String(k.receipts || 0);

    var routes = data.by_route || [];
    var wrap = document.getElementById('tds-cargo-home-wrap');
    var empty = document.getElementById('tds-cargo-home-empty');
    var body = document.getElementById('tds-cargo-home-routes');
    if (body) {
      body.innerHTML = routes.map(function (r) {
        return '<tr><td>' + esc(r.route) + '</td><td class="tds-mono">' + money(r.paid) + '</td><td class="tds-mono">' +
          money(r.to_be) + '</td><td class="tds-mono">' + money(r.total) + '</td><td>' + esc(r.receipts) + '</td></tr>';
      }).join('');
    }
    if (wrap) wrap.hidden = routes.length === 0;
    if (empty) empty.hidden = routes.length !== 0;

    var daily = data.daily || [];
    var dWrap = document.getElementById('tds-cargo-home-daily-wrap');
    var dEmpty = document.getElementById('tds-cargo-home-daily-empty');
    var dBody = document.getElementById('tds-cargo-home-daily');
    if (dBody) {
      dBody.innerHTML = daily.map(function (d) {
        return '<tr><td>' + esc(d.date_label || d.date) + '</td><td>' + esc(d.paid_receipts) + '</td><td>' +
          esc(d.to_be_receipts) + '</td><td>' + esc(d.due_receipts) + '</td></tr>';
      }).join('');
    }
    if (dWrap) dWrap.hidden = daily.length === 0;
    if (dEmpty) dEmpty.hidden = daily.length !== 0;

    if (routeEl && data.routes) {
      var current = routeEl.value || data.route || '';
      routeEl.innerHTML = data.routes.map(function (name) {
        return '<option value="' + esc(name) + '">' + esc(name) + '</option>';
      }).join('') || '<option value="">No routes</option>';
      if (current) routeEl.value = current;
    }
  }

  function load() {
    showError('');
    if (loadingEl) loadingEl.hidden = false;
    var params = new URLSearchParams();
    if (routeEl && routeEl.value) params.set('route', routeEl.value);
    fetch('/cargo/api/console/home.php?' + params.toString(), { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (res) { return res.json().then(function (body) { if (!res.ok || !body.ok) throw new Error((body.error && body.error.message) || 'Request failed'); return body; }); })
      .then(function (body) { paint(body.data || {}); })
      .catch(function (e) { showError(e.message || 'Could not load dashboard'); })
      .then(function () { if (loadingEl) loadingEl.hidden = true; });
  }

  if (routeEl) routeEl.addEventListener('change', load);
  load();
})();
