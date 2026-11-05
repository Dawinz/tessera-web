/**
 * A-P0-05 Accounts Trip profitability — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-acc-trips-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/accounts/api/console';
  var state = { page: 1, perPage: 25, total: 0 };

  var errEl = document.getElementById('tds-acc-trips-error');
  var loadingEl = document.getElementById('tds-acc-trips-loading');
  var emptyEl = document.getElementById('tds-acc-trips-empty');
  var wrapEl = document.getElementById('tds-acc-trips-wrap');
  var tbody = document.getElementById('tds-acc-trips-tbody');
  var pager = document.getElementById('tds-acc-trips-pager');
  var metaEl = document.getElementById('tds-acc-trips-meta');
  var qEl = document.getElementById('tds-acc-trips-q');
  var routeEl = document.getElementById('tds-acc-trips-route');
  var vehicleEl = document.getElementById('tds-acc-trips-vehicle');
  var fromEl = document.getElementById('tds-acc-trips-from');
  var toEl = document.getElementById('tds-acc-trips-to');

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
  function badgeClass(badge) {
    if (badge === 'paid') return 'tds-badge tds-badge-paid';
    if (badge === 'warn' || badge === 'danger') return 'tds-badge tds-badge-warn';
    return 'tds-badge tds-badge-neutral';
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
  function defaultDates() {
    var now = new Date();
    var from = new Date(now.getFullYear(), now.getMonth(), 1);
    var monthEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    var qFrom = queryParam('date_from');
    var qTo = queryParam('date_to');
    if (fromEl && !fromEl.value) fromEl.value = qFrom || isoLocal(from);
    if (toEl && !toEl.value) toEl.value = qTo || isoLocal(monthEnd);
  }

  function filterParams() {
    return (
      'q=' +
      encodeURIComponent((qEl && qEl.value) || '') +
      '&route=' +
      encodeURIComponent((routeEl && routeEl.value) || '') +
      '&vehicle=' +
      encodeURIComponent((vehicleEl && vehicleEl.value) || '') +
      '&date_from=' +
      encodeURIComponent((fromEl && fromEl.value) || '') +
      '&date_to=' +
      encodeURIComponent((toEl && toEl.value) || '') +
      '&page=' +
      state.page +
      '&per_page=' +
      state.perPage
    );
  }

  function renderSummary(summary) {
    summary = summary || {};
    var c = document.getElementById('tds-acc-trips-count');
    var r = document.getElementById('tds-acc-trips-revenue');
    var n = document.getElementById('tds-acc-trips-net');
    var v = document.getElementById('tds-acc-trips-review');
    if (c) c.textContent = String(summary.count || 0);
    if (r) r.textContent = money(summary.revenue);
    if (n) {
      n.textContent = money(summary.net);
      n.title = (summary.margin != null ? summary.margin : 0) + '% average margin';
    }
    if (v) v.textContent = String(summary.review || 0);
  }

  function renderRows(items) {
    if (!tbody) return;
    if (!items || !items.length) {
      if (wrapEl) wrapEl.hidden = true;
      if (emptyEl) emptyEl.hidden = false;
      if (pager) pager.hidden = true;
      return;
    }
    if (emptyEl) emptyEl.hidden = true;
    if (wrapEl) wrapEl.hidden = false;
    tbody.innerHTML = items
      .map(function (it) {
        return (
          '<tr>' +
          '<td class="tds-mono"><strong>' +
          esc(it.code || '—') +
          '</strong></td>' +
          '<td>' +
          esc(it.date_label || it.date) +
          '</td>' +
          '<td>' +
          esc(it.route) +
          '</td>' +
          '<td class="tds-mono">' +
          esc(it.vehicle) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.ticket) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.cargo) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.revenue) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.expenses) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.net) +
          '</td>' +
          '<td class="tds-mono">' +
          esc(String(it.margin)) +
          '%</td>' +
          '<td><span class="' +
          badgeClass(it.status_badge) +
          '">' +
          esc(it.status_label) +
          '</span></td>' +
          '<td><a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
          esc(it.compose_url) +
          '">Compose</a></td>' +
          '</tr>'
        );
      })
      .join('');

    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (pager) {
      pager.hidden = state.total <= state.perPage;
      if (metaEl) {
        metaEl.textContent = 'Page ' + state.page + ' of ' + pages + ' · ' + state.total + ' trips';
      }
    }
  }

  function loadList() {
    setLoading(true);
    showError('');
    return fetchJson(API + '/trips_report.php?' + filterParams())
      .then(function (body) {
        var data = body.data || {};
        state.total = Number(data.total || 0);
        renderSummary(data.summary || {});
        renderRows(data.items || []);
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load trips');
        renderRows([]);
      })
      .finally(function () {
        setLoading(false);
      });
  }

  function fillFilters(data) {
    data = data || {};
    if (routeEl) {
      var routes = data.routes || [];
      routeEl.innerHTML =
        '<option value="">All routes</option>' +
        routes
          .map(function (r) {
            var name = r.name || r;
            var label = r.label || name;
            return '<option value="' + esc(name) + '">' + esc(label) + '</option>';
          })
          .join('');
    }
    if (vehicleEl) {
      var vehicles = data.vehicles || [];
      vehicleEl.innerHTML =
        '<option value="">All vehicles</option>' +
        vehicles
          .map(function (v) {
            return '<option value="' + esc(v) + '">' + esc(v) + '</option>';
          })
          .join('');
    }
  }

  function apply() {
    state.page = 1;
    loadList();
  }

  var applyBtn = document.getElementById('tds-acc-trips-apply');
  var resetBtn = document.getElementById('tds-acc-trips-reset');
  if (applyBtn) applyBtn.addEventListener('click', apply);
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      if (qEl) qEl.value = '';
      if (routeEl) routeEl.value = '';
      if (vehicleEl) vehicleEl.value = '';
      if (fromEl) fromEl.value = '';
      if (toEl) toEl.value = '';
      defaultDates();
      apply();
    });
  }
  if (routeEl) routeEl.addEventListener('change', apply);
  if (vehicleEl) vehicleEl.addEventListener('change', apply);

  var searchTimer = null;
  if (qEl) {
    qEl.addEventListener('input', function () {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(apply, 250);
    });
  }

  var prevBtn = document.getElementById('tds-acc-trips-prev');
  var nextBtn = document.getElementById('tds-acc-trips-next');
  if (prevBtn) {
    prevBtn.addEventListener('click', function () {
      if (state.page > 1) {
        state.page -= 1;
        loadList();
      }
    });
  }
  if (nextBtn) {
    nextBtn.addEventListener('click', function () {
      var pages = Math.max(1, Math.ceil(state.total / state.perPage));
      if (state.page < pages) {
        state.page += 1;
        loadList();
      }
    });
  }

  defaultDates();
  fetchJson(API + '/trips_report_filters.php')
    .then(function (body) {
      fillFilters(body.data || {});
      return loadList();
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load trip report');
    });
})();
