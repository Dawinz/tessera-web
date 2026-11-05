/**
 * A-P0-06 Accounts Profit & loss — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-acc-pnl-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/accounts/api/console';
  var errEl = document.getElementById('tds-acc-pnl-error');
  var loadingEl = document.getElementById('tds-acc-pnl-loading');
  var statementWrap = document.getElementById('tds-acc-pnl-statement-wrap');
  var statementBody = document.getElementById('tds-acc-pnl-statement');
  var compareWrap = document.getElementById('tds-acc-pnl-compare-wrap');
  var compareEmpty = document.getElementById('tds-acc-pnl-compare-empty');
  var compareBody = document.getElementById('tds-acc-pnl-compare');
  var routeEl = document.getElementById('tds-acc-pnl-route');
  var fromEl = document.getElementById('tds-acc-pnl-from');
  var toEl = document.getElementById('tds-acc-pnl-to');
  var periodEl = document.getElementById('tds-acc-pnl-period');
  var statementTitle = document.getElementById('tds-acc-pnl-statement-title');
  var prevLabel = document.getElementById('tds-acc-pnl-prev-label');

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
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
  function isoLocal(d) {
    var y = d.getFullYear();
    var m = String(d.getMonth() + 1).padStart(2, '0');
    var day = String(d.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + day;
  }
  function queryParam(name) {
    try {
      return new URLSearchParams(window.location.search).get(name) || '';
    } catch (e) {
      return '';
    }
  }

  function fetchJson(url, opts) {
    opts = opts || {};
    var attempt = opts._attempt || 0;
    return fetch(url, {
      credentials: 'same-origin',
      headers: Object.assign({ Accept: 'application/json' }, opts.headers || {}),
      method: opts.method || 'GET',
      body: opts.body || undefined,
      redirect: 'manual',
    }).then(function (res) {
      if (res.type === 'opaqueredirect' || res.status === 0 || (res.status >= 300 && res.status < 400)) {
        throw new Error('Session expired — sign in again');
      }
      return res.json().then(function (body) {
        if (res.status === 401 && attempt < 1) {
          return new Promise(function (resolve) {
            setTimeout(resolve, 150);
          }).then(function () {
            return fetchJson(url, Object.assign({}, opts, { _attempt: attempt + 1 }));
          });
        }
        if (!res.ok || !body || body.ok === false) {
          var message =
            body && body.error && body.error.message ? body.error.message : 'Request failed';
          throw new Error(message);
        }
        return body;
      });
    });
  }

  function defaultDates() {
    var now = new Date();
    var from = new Date(now.getFullYear(), now.getMonth(), 1);
    var monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    if (fromEl && !fromEl.value) fromEl.value = queryParam('date_from') || isoLocal(from);
    if (toEl && !toEl.value) toEl.value = queryParam('date_to') || isoLocal(monthEnd);
  }

  function renderSummary(summary) {
    summary = summary || {};
    var map = {
      'tds-acc-pnl-revenue': summary.revenue,
      'tds-acc-pnl-direct': summary.direct,
      'tds-acc-pnl-gross': summary.gross,
      'tds-acc-pnl-net': summary.net,
    };
    Object.keys(map).forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.textContent = money(map[id]);
    });
  }

  function renderStatement(rows) {
    if (!statementBody) return;
    if (!rows || !rows.length) {
      if (statementWrap) statementWrap.hidden = true;
      return;
    }
    if (statementWrap) statementWrap.hidden = false;
    statementBody.innerHTML = rows
      .map(function (row) {
        if (row.type === 'heading') {
          return (
            '<tr><td colspan="2"><strong style="text-transform:uppercase;letter-spacing:.04em;font-size:.75rem">' +
            esc(row.label) +
            '</strong></td></tr>'
          );
        }
        var amount = row.amount == null ? '' : money(row.amount);
        var weight = row.type === 'total' || row.type === 'subtotal' ? 'font-weight:600' : '';
        var mono = row.type === 'total' || row.type === 'subtotal' ? 'tds-mono' : 'tds-mono';
        return (
          '<tr style="' +
          weight +
          '"><td>' +
          esc(row.label) +
          '</td><td class="' +
          mono +
          '" style="text-align:right">' +
          amount +
          '</td></tr>'
        );
      })
      .join('');
  }

  function renderCompare(rows) {
    if (!compareBody) return;
    if (!rows || !rows.length) {
      if (compareWrap) compareWrap.hidden = true;
      if (compareEmpty) compareEmpty.hidden = false;
      return;
    }
    if (compareEmpty) compareEmpty.hidden = true;
    if (compareWrap) compareWrap.hidden = false;
    compareBody.innerHTML = rows
      .map(function (row) {
        return (
          '<tr>' +
          '<td>' +
          esc(row.line) +
          '</td>' +
          '<td class="tds-mono">' +
          money(row.now) +
          '</td>' +
          '<td class="tds-mono">' +
          money(row.before) +
          '</td>' +
          '<td class="tds-mono">' +
          money(row.change) +
          '</td>' +
          '<td class="tds-mono">' +
          esc(String(row.change_pct)) +
          '%</td>' +
          '</tr>'
        );
      })
      .join('');
  }

  function loadReport() {
    if (loadingEl) loadingEl.hidden = false;
    if (statementWrap) statementWrap.hidden = true;
    showError('');
    var url =
      API +
      '/pnl_report.php?date_from=' +
      encodeURIComponent((fromEl && fromEl.value) || '') +
      '&date_to=' +
      encodeURIComponent((toEl && toEl.value) || '') +
      '&route=' +
      encodeURIComponent((routeEl && routeEl.value) || '');
    return fetchJson(url)
      .then(function (body) {
        var data = body.data || {};
        renderSummary(data.summary || {});
        renderStatement(data.statement || []);
        renderCompare(data.compare || []);
        if (statementTitle && data.period) {
          statementTitle.textContent = 'Statement for ' + (data.period.label || '');
        }
        if (prevLabel && data.previous) {
          prevLabel.textContent = data.previous.label || '';
        }
        if (periodEl) {
          var trips = (data.summary && data.summary.trips) || 0;
          periodEl.textContent =
            (data.period && data.period.label ? data.period.label : '') +
            ' · ' +
            trips +
            ' trip' +
            (trips === 1 ? '' : 's') +
            (data.summary
              ? ' · gross margin ' +
                data.summary.gross_pct +
                '% · net margin ' +
                data.summary.net_pct +
                '%'
              : '');
        }
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load profit & loss');
      })
      .finally(function () {
        if (loadingEl) loadingEl.hidden = true;
      });
  }

  function fillRoutes(routes) {
    if (!routeEl) return;
    routeEl.innerHTML =
      '<option value="">All routes</option>' +
      (routes || [])
        .map(function (r) {
          var name = r.name || r;
          var label = r.label || name;
          return '<option value="' + esc(name) + '">' + esc(label) + '</option>';
        })
        .join('');
  }

  function apply() {
    loadReport();
  }

  var applyBtn = document.getElementById('tds-acc-pnl-apply');
  var resetBtn = document.getElementById('tds-acc-pnl-reset');
  if (applyBtn) applyBtn.addEventListener('click', apply);
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      if (routeEl) routeEl.value = '';
      if (fromEl) fromEl.value = '';
      if (toEl) toEl.value = '';
      defaultDates();
      apply();
    });
  }
  if (routeEl) routeEl.addEventListener('change', apply);

  defaultDates();
  fetchJson(API + '/pnl_filters.php')
    .then(function (body) {
      fillRoutes((body.data && body.data.routes) || []);
      return loadReport();
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load profit & loss');
    });
})();
