/**
 * A-P0-03 Accounts Daily expenses — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-acc-daily-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/accounts/api/console';
  var state = {
    route: '',
    month: '',
    year: new Date().getFullYear(),
    items: [],
    bootstrap: { months: [], routes: [], categories: [], particulars: {}, vehicles: [], accounts: [], years: [] },
    mode: 'create',
  };

  var errEl = document.getElementById('tds-acc-daily-error');
  var okEl = document.getElementById('tds-acc-daily-success');
  var warnEl = document.getElementById('tds-acc-daily-warn');
  var loadingEl = document.getElementById('tds-acc-daily-loading');
  var emptyEl = document.getElementById('tds-acc-daily-empty');
  var wrapEl = document.getElementById('tds-acc-daily-wrap');
  var tbody = document.getElementById('tds-acc-daily-tbody');
  var routeEl = document.getElementById('tds-acc-daily-route');
  var monthEl = document.getElementById('tds-acc-daily-month');
  var yearEl = document.getElementById('tds-acc-daily-year');
  var qEl = document.getElementById('tds-acc-daily-q');
  var dialog = document.getElementById('tds-acc-daily-dialog');
  var form = document.getElementById('tds-acc-daily-form');
  var formCategory = document.getElementById('tds-acc-daily-form-category');
  var formParticular = document.getElementById('tds-acc-daily-form-particular');
  var formDate = document.getElementById('tds-acc-daily-form-date');
  var formAmount = document.getElementById('tds-acc-daily-form-amount');
  var formAccount = document.getElementById('tds-acc-daily-form-account');
  var formVehicle = document.getElementById('tds-acc-daily-form-vehicle');
  var formDetail = document.getElementById('tds-acc-daily-form-detail');
  var formId = document.getElementById('tds-acc-daily-edit-id');
  var dialogTitle = document.getElementById('tds-acc-daily-dialog-title');

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
  function showWarn(msg) {
    if (!warnEl) return;
    warnEl.hidden = !msg;
    warnEl.textContent = msg || '';
  }
  function setLoading(on) {
    if (loadingEl) loadingEl.hidden = !on;
    if (on) {
      if (wrapEl) wrapEl.hidden = true;
      if (emptyEl) emptyEl.hidden = true;
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

  function fillSelect(el, options, selected, emptyLabel) {
    if (!el) return;
    var html = '';
    if (emptyLabel != null) {
      html += '<option value="">' + esc(emptyLabel) + '</option>';
    }
    (options || []).forEach(function (opt) {
      var val = typeof opt === 'object' ? opt.value : opt;
      var label = typeof opt === 'object' ? opt.label : opt;
      var sel = String(val) === String(selected) ? ' selected' : '';
      html += '<option value="' + esc(val) + '"' + sel + '>' + esc(label) + '</option>';
    });
    el.innerHTML = html;
  }

  function fillParticulars(category, selected) {
    var list = (state.bootstrap.particulars && state.bootstrap.particulars[category]) || [];
    fillSelect(formParticular, list, selected || '', 'Choose particular');
  }

  function renderSummary(summary, cash) {
    summary = summary || {};
    var c = document.getElementById('tds-acc-daily-count');
    var e = document.getElementById('tds-acc-daily-expenses');
    var cashEl = document.getElementById('tds-acc-daily-cash');
    if (c) c.textContent = String(summary.count || 0);
    if (e) e.textContent = money(summary.expenses);
    if (cashEl) cashEl.textContent = money(summary.cash_remain != null ? summary.cash_remain : (cash && cash.amount_remain) || 0);
    if (!cash) {
      showWarn(state.route ? 'No cash float for this route — open classic Withdraw to add cash first.' : '');
    } else {
      showWarn('');
    }
  }

  function renderRows() {
    if (!tbody) return;
    if (!state.items.length) {
      if (wrapEl) wrapEl.hidden = true;
      if (emptyEl) emptyEl.hidden = false;
      return;
    }
    if (emptyEl) emptyEl.hidden = true;
    if (wrapEl) wrapEl.hidden = false;
    tbody.innerHTML = state.items
      .map(function (it) {
        return (
          '<tr>' +
          '<td>' +
          esc(it.date_label || it.date) +
          '</td>' +
          '<td>' +
          esc(it.category) +
          '</td>' +
          '<td>' +
          esc(it.particular) +
          '</td>' +
          '<td class="tds-mono">' +
          esc(it.vehicle || '—') +
          '</td>' +
          '<td>' +
          esc(it.account) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.amount) +
          '</td>' +
          '<td class="tds-mono">' +
          money(it.amount_remain) +
          '</td>' +
          '<td class="tds-row" style="gap:var(--tds-space-1);justify-content:flex-end">' +
          '<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" data-edit="' +
          it.id +
          '">Edit</button>' +
          '<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" data-del="' +
          it.id +
          '">Delete</button>' +
          '</td>' +
          '</tr>'
        );
      })
      .join('');
  }

  function loadList() {
    if (!state.route) {
      state.items = [];
      renderSummary({ count: 0, expenses: 0, cash_remain: 0 }, null);
      renderRows();
      return Promise.resolve();
    }
    setLoading(true);
    showError('');
    var url =
      API +
      '/daily_list.php?route=' +
      encodeURIComponent(state.route) +
      '&month=' +
      encodeURIComponent(state.month) +
      '&year=' +
      encodeURIComponent(state.year) +
      '&q=' +
      encodeURIComponent((qEl && qEl.value) || '');
    return fetchJson(url)
      .then(function (body) {
        var data = body.data || {};
        state.items = data.items || [];
        renderSummary(data.summary || {}, data.cash);
        renderRows();
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load expenses');
        state.items = [];
        renderRows();
      })
      .finally(function () {
        setLoading(false);
      });
  }

  function openDialog(item) {
    state.mode = item ? 'edit' : 'create';
    if (formId) formId.value = item ? String(item.id) : '';
    if (dialogTitle) dialogTitle.textContent = item ? 'Edit expense' : 'Add expense';
    fillSelect(formCategory, state.bootstrap.categories || [], item ? item.category : '', 'Choose category');
    fillParticulars(item ? item.category : '', item ? item.particular : '');
    fillSelect(
      formVehicle,
      (state.bootstrap.vehicles || []).map(function (v) {
        return v;
      }),
      item ? item.vehicle : '',
      'None'
    );
    if (formDate) formDate.value = item ? item.date : new Date().toISOString().slice(0, 10);
    if (formAmount) formAmount.value = item ? String(item.amount) : '';
    if (formAccount) formAccount.value = (item && item.account) || 'Cash';
    if (formDetail) formDetail.value = item ? item.detail || '' : '';
    if (dialog && typeof dialog.showModal === 'function') dialog.showModal();
  }

  function findItem(id) {
    id = Number(id);
    for (var i = 0; i < state.items.length; i++) {
      if (Number(state.items[i].id) === id) return state.items[i];
    }
    return null;
  }

  if (formCategory) {
    formCategory.addEventListener('change', function () {
      fillParticulars(formCategory.value || '');
    });
  }

  var addBtn = document.getElementById('tds-acc-daily-open-add');
  if (addBtn) {
    addBtn.addEventListener('click', function () {
      if (!state.route) {
        showError('Choose a route first');
        return;
      }
      openDialog(null);
    });
  }

  var cancelBtn = document.getElementById('tds-acc-daily-cancel');
  if (cancelBtn) {
    cancelBtn.addEventListener('click', function () {
      if (dialog && typeof dialog.close === 'function') dialog.close();
    });
  }

  if (tbody) {
    tbody.addEventListener('click', function (ev) {
      var editBtn = ev.target.closest('[data-edit]');
      var delBtn = ev.target.closest('[data-del]');
      if (editBtn) {
        var item = findItem(editBtn.getAttribute('data-edit'));
        if (item) openDialog(item);
        return;
      }
      if (delBtn) {
        if (!window.confirm('Delete this expense?')) return;
        fetchJson(API + '/daily_delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            id: Number(delBtn.getAttribute('data-del')),
            route: state.route,
            month: state.month,
            year: state.year,
          }),
        })
          .then(function (body) {
            var data = body.data || {};
            state.items = data.items || [];
            renderSummary(data.summary || {}, data.cash);
            renderRows();
            showOk('Expense deleted');
          })
          .catch(function (err) {
            showError(err.message || 'Delete failed');
          });
      }
    });
  }

  if (form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var payload = {
        route: state.route,
        category: (formCategory && formCategory.value) || '',
        particular: (formParticular && formParticular.value) || '',
        date: (formDate && formDate.value) || '',
        amount: (formAmount && formAmount.value) || '',
        account: (formAccount && formAccount.value) || 'Cash',
        vehicle: (formVehicle && formVehicle.value) || '',
        detail: (formDetail && formDetail.value) || '',
      };
      var url = API + '/daily_create.php';
      if (state.mode === 'edit') {
        url = API + '/daily_update.php';
        payload.id = Number((formId && formId.value) || 0);
      }
      fetchJson(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      })
        .then(function () {
          if (dialog && typeof dialog.close === 'function') dialog.close();
          showOk(state.mode === 'edit' ? 'Expense updated' : 'Expense saved');
          return loadList();
        })
        .catch(function (err) {
          showError(err.message || 'Save failed');
        });
    });
  }

  function syncFiltersFromUi() {
    state.route = (routeEl && routeEl.value) || '';
    state.month = (monthEl && monthEl.value) || '';
    state.year = Number((yearEl && yearEl.value) || new Date().getFullYear());
  }

  var applyBtn = document.getElementById('tds-acc-daily-apply');
  if (applyBtn) applyBtn.addEventListener('click', function () { syncFiltersFromUi(); loadList(); });
  if (routeEl) routeEl.addEventListener('change', function () { syncFiltersFromUi(); loadList(); });
  if (monthEl) monthEl.addEventListener('change', function () { syncFiltersFromUi(); loadList(); });
  if (yearEl) yearEl.addEventListener('change', function () { syncFiltersFromUi(); loadList(); });

  var searchTimer = null;
  if (qEl) {
    qEl.addEventListener('input', function () {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(function () {
        syncFiltersFromUi();
        loadList();
      }, 250);
    });
  }

  fetchJson(API + '/daily_bootstrap.php')
    .then(function (body) {
      state.bootstrap = body.data || state.bootstrap;
      var monthNow = new Date().toLocaleString('en-US', { month: 'long' });
      state.month = monthNow;
      state.year = new Date().getFullYear();
      state.route = (state.bootstrap.routes && state.bootstrap.routes[0]) || '';
      fillSelect(routeEl, state.bootstrap.routes || [], state.route);
      fillSelect(monthEl, state.bootstrap.months || [], state.month);
      fillSelect(yearEl, state.bootstrap.years || [state.year], state.year);
      return loadList();
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load daily expenses');
    });
})();
