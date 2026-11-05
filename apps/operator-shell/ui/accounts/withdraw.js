/**
 * A-P0-04 Accounts Withdraw — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-acc-withdraw-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/accounts/api/console';
  var state = { page: 1, perPage: 25, total: 0, route: '', routes: [] };

  var errEl = document.getElementById('tds-acc-withdraw-error');
  var okEl = document.getElementById('tds-acc-withdraw-success');
  var loadingEl = document.getElementById('tds-acc-withdraw-loading');
  var emptyEl = document.getElementById('tds-acc-withdraw-empty');
  var wrapEl = document.getElementById('tds-acc-withdraw-wrap');
  var tbody = document.getElementById('tds-acc-withdraw-tbody');
  var pager = document.getElementById('tds-acc-withdraw-pager');
  var metaEl = document.getElementById('tds-acc-withdraw-meta');
  var routeEl = document.getElementById('tds-acc-withdraw-route');
  var qEl = document.getElementById('tds-acc-withdraw-q');
  var dialog = document.getElementById('tds-acc-withdraw-dialog');
  var form = document.getElementById('tds-acc-withdraw-form');
  var formRoute = document.getElementById('tds-acc-withdraw-form-route');
  var formDate = document.getElementById('tds-acc-withdraw-form-date');
  var formAmount = document.getElementById('tds-acc-withdraw-form-amount');
  var formAuthorizer = document.getElementById('tds-acc-withdraw-form-authorizer');

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    if (!okEl) return;
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
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

  function fillRoutes(select, includeAll, selected) {
    if (!select) return;
    var html = includeAll ? '<option value="">All routes</option>' : '';
    state.routes.forEach(function (r) {
      var sel = r.route === selected ? ' selected' : '';
      html += '<option value="' + esc(r.route) + '"' + sel + '>' + esc(r.label || r.route) + '</option>';
    });
    select.innerHTML = html;
  }

  function renderSummary(summary) {
    summary = summary || {};
    var c = document.getElementById('tds-acc-withdraw-count');
    var d = document.getElementById('tds-acc-withdraw-deposited');
    var r = document.getElementById('tds-acc-withdraw-remain');
    if (c) c.textContent = String(summary.count || 0);
    if (d) d.textContent = money(summary.deposited);
    if (r) r.textContent = money(summary.latest_remain);
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
          '<td>' +
          esc(it.open_date_label || it.open_date) +
          '</td>' +
          '<td>' +
          esc(it.route) +
          '</td>' +
          '<td>' +
          esc(it.authorizer) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.amount) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.amount_remain) +
          '</td>' +
          '<td>' +
          esc(it.issuer) +
          '</td>' +
          '<td class="tds-muted">' +
          esc(it.date_insert) +
          (it.time_insert ? ' · ' + esc(it.time_insert) : '') +
          '</td>' +
          '</tr>'
        );
      })
      .join('');

    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (pager) {
      pager.hidden = state.total <= state.perPage;
      if (metaEl) {
        metaEl.textContent =
          'Page ' + state.page + ' of ' + pages + ' · ' + state.total + ' deposits';
      }
    }
  }

  function loadList() {
    setLoading(true);
    showError('');
    var url =
      API +
      '/withdraw_list.php?page=' +
      state.page +
      '&per_page=' +
      state.perPage +
      '&route=' +
      encodeURIComponent(state.route) +
      '&q=' +
      encodeURIComponent((qEl && qEl.value) || '');
    return fetchJson(url)
      .then(function (body) {
        var data = body.data || {};
        state.total = Number(data.total || 0);
        renderSummary(data.summary || {});
        renderRows(data.items || []);
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load cash');
        renderRows([]);
      })
      .finally(function () {
        setLoading(false);
      });
  }

  function openDialog() {
    fillRoutes(formRoute, false, state.route || (state.routes[0] && state.routes[0].route) || '');
    if (formDate) formDate.value = new Date().toISOString().slice(0, 10);
    if (formAmount) formAmount.value = '';
    if (formAuthorizer) formAuthorizer.value = '';
    if (dialog && typeof dialog.showModal === 'function') dialog.showModal();
  }

  var addBtn = document.getElementById('tds-acc-withdraw-open-add');
  if (addBtn) addBtn.addEventListener('click', openDialog);

  var cancelBtn = document.getElementById('tds-acc-withdraw-cancel');
  if (cancelBtn) {
    cancelBtn.addEventListener('click', function () {
      if (dialog && typeof dialog.close === 'function') dialog.close();
    });
  }

  if (form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      fetchJson(API + '/withdraw_create.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          route: (formRoute && formRoute.value) || '',
          date: (formDate && formDate.value) || '',
          amount: (formAmount && formAmount.value) || '',
          authorizer: (formAuthorizer && formAuthorizer.value) || '',
        }),
      })
        .then(function () {
          if (dialog && typeof dialog.close === 'function') dialog.close();
          showOk('Cash deposit saved');
          state.page = 1;
          if (formRoute && formRoute.value) {
            state.route = formRoute.value;
            if (routeEl) routeEl.value = state.route;
          }
          return loadList();
        })
        .catch(function (err) {
          showError(err.message || 'Save failed');
        });
    });
  }

  function syncAndLoad() {
    state.route = (routeEl && routeEl.value) || '';
    state.page = 1;
    loadList();
  }

  var applyBtn = document.getElementById('tds-acc-withdraw-apply');
  if (applyBtn) applyBtn.addEventListener('click', syncAndLoad);
  if (routeEl) routeEl.addEventListener('change', syncAndLoad);

  var searchTimer = null;
  if (qEl) {
    qEl.addEventListener('input', function () {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(syncAndLoad, 250);
    });
  }

  var prevBtn = document.getElementById('tds-acc-withdraw-prev');
  var nextBtn = document.getElementById('tds-acc-withdraw-next');
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

  fetchJson(API + '/withdraw_bootstrap.php')
    .then(function (body) {
      state.routes = (body.data && body.data.routes) || [];
      fillRoutes(routeEl, true, '');
      return loadList();
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load withdraw');
    });
})();
