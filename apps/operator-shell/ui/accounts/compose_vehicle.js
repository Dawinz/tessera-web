/**
 * A-P0-02 Accounts Compose vehicle — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-acc-compose-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/accounts/api/console';
  var state = {
    route: root.getAttribute('data-route') || '',
    vehicle: root.getAttribute('data-vehicle') || '',
    date: root.getAttribute('data-date') || '',
    point: '',
    currentRoute: '',
    locked: false,
    passwordLocked: false,
    points: [],
    items: [],
    attachments: [],
    bootstrap: {
      income_categories: [],
      expense_categories: [],
      particulars: {},
      currencies: [],
      rates: {},
      default_currency: 'Tsh',
    },
    displayCurrency: 'Default',
    mode: 'create',
    entryType: 'income',
  };

  var errEl = document.getElementById('tds-acc-compose-error');
  var okEl = document.getElementById('tds-acc-compose-success');
  var warnEl = document.getElementById('tds-acc-compose-warn');
  var loadingEl = document.getElementById('tds-acc-compose-loading');
  var emptyEl = document.getElementById('tds-acc-compose-empty');
  var wrapEl = document.getElementById('tds-acc-compose-wrap');
  var tbody = document.getElementById('tds-acc-compose-tbody');
  var pointsEl = document.getElementById('tds-acc-compose-points');
  var qEl = document.getElementById('tds-acc-compose-q');
  var fxEl = document.getElementById('tds-acc-compose-fx');
  var dialog = document.getElementById('tds-acc-compose-dialog');
  var form = document.getElementById('tds-acc-compose-form');
  var formType = document.getElementById('tds-acc-compose-form-type');
  var formCategory = document.getElementById('tds-acc-compose-form-category');
  var formParticular = document.getElementById('tds-acc-compose-form-particular');
  var formAmount = document.getElementById('tds-acc-compose-form-amount');
  var formCurrency = document.getElementById('tds-acc-compose-form-currency');
  var formDetail = document.getElementById('tds-acc-compose-form-detail');
  var formId = document.getElementById('tds-acc-compose-edit-id');
  var dialogTitle = document.getElementById('tds-acc-compose-dialog-title');
  var classicLink = document.getElementById('tds-acc-compose-classic');
  var btnIncome = document.getElementById('tds-acc-compose-add-income');
  var btnExpense = document.getElementById('tds-acc-compose-add-expense');
  var btnLock = document.getElementById('tds-acc-compose-lock');
  var btnTransfer = document.getElementById('tds-acc-compose-transfer');
  var transferDialog = document.getElementById('tds-acc-compose-transfer-dialog');
  var transferForm = document.getElementById('tds-acc-compose-transfer-form');
  var transferTarget = document.getElementById('tds-acc-compose-transfer-target');
  var btnUnlock = document.getElementById('tds-acc-compose-unlock');
  var btnUpload = document.getElementById('tds-acc-compose-upload');
  var btnPull = document.getElementById('tds-acc-compose-pull');
  var btnCharges = document.getElementById('tds-acc-compose-user-charge');
  var lockDialog = document.getElementById('tds-acc-compose-lock-dialog');
  var lockForm = document.getElementById('tds-acc-compose-lock-form');
  var lockPassword = document.getElementById('tds-acc-compose-lock-password');
  var uploadDialog = document.getElementById('tds-acc-compose-upload-dialog');
  var fileInput = document.getElementById('tds-acc-compose-file');
  var attachmentsEl = document.getElementById('tds-acc-compose-attachments');
  var pullDialog = document.getElementById('tds-acc-compose-pull-dialog');
  var pullForm = document.getElementById('tds-acc-compose-pull-form');
  var pullDate = document.getElementById('tds-acc-compose-pull-date');
  var chargesDialog = document.getElementById('tds-acc-compose-charges-dialog');
  var chargesBody = document.getElementById('tds-acc-compose-charges-tbody');

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
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 2 });
  }

  function fxConvert(amount, fromCurrency) {
    var to = state.displayCurrency || 'Default';
    if (!to || to === 'Default') return Number(amount || 0);
    var from = fromCurrency || state.bootstrap.default_currency || 'Tsh';
    if (String(from).toLowerCase() === String(to).toLowerCase()) return Number(amount || 0);
    var rates = state.bootstrap.rates || {};
    var key = from + '-' + to;
    var rate = Number(rates[key] || 0);
    if (rate > 0) return Number(amount || 0) / rate;
    var inv = Number(rates[to + '-' + from] || 0);
    if (inv > 0) return Number(amount || 0) * inv;
    return Number(amount || 0);
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

  function tripParams() {
    return (
      'route=' +
      encodeURIComponent(state.route) +
      '&vehicle=' +
      encodeURIComponent(state.vehicle) +
      '&date=' +
      encodeURIComponent(state.date)
    );
  }

  function setWriteEnabled(on) {
    if (btnIncome) btnIncome.disabled = !on;
    if (btnExpense) btnExpense.disabled = !on;
    if (btnPull) btnPull.disabled = !on;
    if (btnTransfer) btnTransfer.disabled = !on;
    if (btnUpload) btnUpload.disabled = false;
  }

  function updateLockButtons() {
    if (btnLock) btnLock.hidden = !!state.locked;
    if (btnUnlock) btnUnlock.hidden = !state.locked;
  }

  function renderContext(ctx) {
    if (!ctx) return;
    var vehicleEl = document.getElementById('tds-acc-compose-vehicle');
    var dateEl = document.getElementById('tds-acc-compose-date');
    var routeEl = document.getElementById('tds-acc-compose-route');
    var incomeEl = document.getElementById('tds-acc-compose-income');
    var expensesEl = document.getElementById('tds-acc-compose-expenses');
    var balanceEl = document.getElementById('tds-acc-compose-balance');
    var lead = document.getElementById('tds-acc-compose-lead');
    if (vehicleEl) vehicleEl.textContent = ctx.vehicle || '—';
    if (dateEl) dateEl.textContent = ctx.date_label || ctx.date || '—';
    if (routeEl) routeEl.textContent = ctx.route || '—';
    if (incomeEl) incomeEl.textContent = money(ctx.income);
    if (expensesEl) expensesEl.textContent = money(ctx.expenses);
    if (balanceEl) balanceEl.textContent = money(ctx.balance);
    state.locked = !!ctx.locked;
    state.passwordLocked = !!ctx.password_locked;
    if (lead) {
      if (state.passwordLocked) {
        lead.textContent = 'This manifest is password-locked and analysed. Unlock to edit.';
      } else if (ctx.analysed) {
        lead.textContent = 'Analysed (' + ctx.analysed + ') — edits are blocked until unlock or point uncheck.';
      } else {
        lead.textContent = 'Trip income and expenses by boarding point.';
      }
    }
    setWriteEnabled(!state.locked && !!state.point);
    showWarn(state.locked ? 'Manifest is analysed or locked — edits are blocked.' : '');
    updateLockButtons();
    if (classicLink && ctx.classic_url) classicLink.setAttribute('href', ctx.classic_url);
  }

  function renderPoints() {
    if (!pointsEl) return;
    if (!state.points.length) {
      pointsEl.innerHTML = '<p class="tds-muted" style="margin:0">No boarding points for this route.</p>';
      return;
    }
    pointsEl.innerHTML = state.points
      .map(function (p) {
        var active = p.point === state.point;
        var checked = !!p.checked;
        return (
          '<div class="tds-row" style="gap:var(--tds-space-1);align-items:stretch">' +
          '<button type="button" class="tds-btn ' +
          (active ? 'tds-btn-primary' : 'tds-btn-ghost') +
          ' tds-btn-sm" data-point="' +
          esc(p.point) +
          '" data-current-route="' +
          esc(p.route_name || state.route) +
          '" style="justify-content:flex-start;flex:1">' +
          esc(p.point) +
          (checked ? ' · checked' : '') +
          '</button>' +
          '<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" data-point-check="' +
          esc(p.point) +
          '" title="Mark point reviewed" ' +
          (state.passwordLocked ? 'disabled' : '') +
          '>' +
          (checked ? 'Uncheck' : 'Check') +
          '</button>' +
          '</div>'
        );
      })
      .join('');
  }

  function renderSummary(summary) {
    summary = summary || {};
    var i = document.getElementById('tds-acc-compose-point-income');
    var e = document.getElementById('tds-acc-compose-point-expenses');
    var b = document.getElementById('tds-acc-compose-point-balance');
    var c = document.getElementById('tds-acc-compose-point-count');
    if (i) i.textContent = money(fxConvert(summary.income || 0, state.bootstrap.default_currency));
    if (e) e.textContent = money(fxConvert(summary.expenses || 0, state.bootstrap.default_currency));
    if (b) b.textContent = money(fxConvert(summary.balance || 0, state.bootstrap.default_currency));
    if (c) c.textContent = String(summary.count || 0);
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
        var isIncome = String(it.status || '').toLowerCase() === 'income' || Number(it.income) > 0;
        var amount = isIncome ? it.income : it.expenses;
        var shown = fxConvert(amount, it.currency || state.bootstrap.default_currency);
        var typeLabel = isIncome ? 'Income' : 'Expense';
        var actions = state.locked
          ? ''
          : '<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" data-edit="' +
            it.id +
            '">Edit</button>' +
            '<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" data-del="' +
            it.id +
            '">Delete</button>';
        return (
          '<tr>' +
          '<td><input type="checkbox" data-line="' +
          it.id +
          '" aria-label="Select line" ' +
          (state.locked ? 'disabled' : '') +
          '></td>' +
          '<td><span class="tds-badge">' +
          esc(typeLabel) +
          '</span></td>' +
          '<td>' +
          esc(it.category) +
          '</td>' +
          '<td>' +
          esc(it.particular) +
          '</td>' +
          '<td class="tds-mono">' +
          money(shown) +
          '</td>' +
          '<td>' +
          esc(state.displayCurrency === 'Default' ? it.currency : state.displayCurrency) +
          '</td>' +
          '<td>' +
          esc(it.detail) +
          '</td>' +
          '<td class="tds-row" style="gap:var(--tds-space-1);justify-content:flex-end">' +
          actions +
          '</td>' +
          '</tr>'
        );
      })
      .join('');
  }

  function fillFxSelect() {
    if (!fxEl) return;
    var list = state.bootstrap.currencies || [];
    fxEl.innerHTML =
      '<option value="Default">Default</option>' +
      list
        .map(function (c) {
          return '<option value="' + esc(c.name) + '">' + esc(c.name) + '</option>';
        })
        .join('');
    fxEl.value = state.displayCurrency || 'Default';
  }

  function fillCurrencies() {
    if (!formCurrency) return;
    var list = state.bootstrap.currencies || [];
    formCurrency.innerHTML = list
      .map(function (c) {
        return '<option value="' + esc(c.name) + '">' + esc(c.name) + '</option>';
      })
      .join('');
  }

  function fillCategories(type) {
    if (!formCategory) return;
    var list =
      type === 'expense' ? state.bootstrap.expense_categories || [] : state.bootstrap.income_categories || [];
    formCategory.innerHTML =
      '<option value="">Choose category</option>' +
      list
        .map(function (name) {
          return '<option value="' + esc(name) + '">' + esc(name) + '</option>';
        })
        .join('');
    fillParticulars('');
  }

  function fillParticulars(category, selected) {
    if (!formParticular) return;
    var list = (state.bootstrap.particulars && state.bootstrap.particulars[category]) || [];
    formParticular.innerHTML =
      '<option value="">Choose particular</option>' +
      list
        .map(function (name) {
          var sel = name === selected ? ' selected' : '';
          return '<option value="' + esc(name) + '"' + sel + '>' + esc(name) + '</option>';
        })
        .join('');
  }

  function openDialog(type, item) {
    if (state.locked) {
      showError('Manifest is analysed and locked');
      return;
    }
    if (!state.point) {
      showError('Choose a boarding point first');
      return;
    }
    state.entryType = type === 'expense' ? 'expense' : 'income';
    state.mode = item ? 'edit' : 'create';
    if (formType) formType.value = state.entryType;
    if (formId) formId.value = item ? String(item.id) : '';
    if (dialogTitle) {
      dialogTitle.textContent = item
        ? state.entryType === 'expense'
          ? 'Edit expense'
          : 'Edit income'
        : state.entryType === 'expense'
          ? 'Add expense'
          : 'Add income';
    }
    fillCategories(state.entryType);
    fillCurrencies();
    if (item) {
      if (formCategory) formCategory.value = item.category || '';
      fillParticulars(item.category || '', item.particular || '');
      if (formAmount) formAmount.value = String(item.amount || item.income || item.expenses || '');
      if (formCurrency) formCurrency.value = item.currency || state.bootstrap.default_currency || 'Tsh';
      if (formDetail) formDetail.value = item.detail || '';
    } else {
      if (formAmount) formAmount.value = '';
      if (formDetail) formDetail.value = '';
      if (formCurrency) formCurrency.value = state.bootstrap.default_currency || 'Tsh';
    }
    if (dialog && typeof dialog.showModal === 'function') dialog.showModal();
  }

  function renderAttachments() {
    if (!attachmentsEl) return;
    if (!state.attachments.length) {
      attachmentsEl.innerHTML = '<p class="tds-muted" style="margin:0">No attachments yet.</p>';
      return;
    }
    attachmentsEl.innerHTML = state.attachments
      .map(function (a) {
        return (
          '<div class="tds-row" style="justify-content:space-between;gap:var(--tds-space-2)">' +
          '<a href="' +
          esc(a.url) +
          '" target="_blank" rel="noopener">' +
          esc(a.original_name || a.file_name) +
          '</a>' +
          '<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" data-att-del="' +
          a.id +
          '">Delete</button>' +
          '</div>'
        );
      })
      .join('');
  }

  function loadAttachments() {
    return fetchJson(API + '/compose_attachments.php?' + tripParams()).then(function (body) {
      state.attachments = (body.data && body.data.items) || [];
      renderAttachments();
    });
  }

  function loadLedger() {
    if (!state.route || !state.vehicle || !state.date || !state.point) {
      state.items = [];
      renderSummary({ income: 0, expenses: 0, balance: 0, count: 0 });
      renderRows();
      return Promise.resolve();
    }
    setLoading(true);
    showError('');
    var url =
      API +
      '/compose_ledger.php?' +
      tripParams() +
      '&point=' +
      encodeURIComponent(state.point) +
      '&q=' +
      encodeURIComponent((qEl && qEl.value) || '');
    return fetchJson(url)
      .then(function (body) {
        var data = body.data || {};
        state.items = data.items || [];
        renderSummary(data.summary || {});
        renderRows();
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load ledger');
        state.items = [];
        renderRows();
      })
      .finally(function () {
        setLoading(false);
      });
  }

  function applyContextPayload(data) {
    if (data.points) state.points = data.points;
    if (data.context) renderContext(data.context);
    renderPoints();
    setWriteEnabled(!state.locked && !!state.point);
  }

  function loadContext() {
    if (!state.route || !state.vehicle || !state.date) {
      showError('Open compose from a manifest (route, vehicle, and date required).');
      return Promise.resolve();
    }
    return fetchJson(API + '/compose_context.php?' + tripParams()).then(function (body) {
      var data = body.data || {};
      state.points = data.points || [];
      renderContext(data.context || {});
      if (!state.point && state.points.length) {
        state.point = state.points[0].point;
        state.currentRoute = state.points[0].route_name || state.route;
      }
      renderPoints();
      setWriteEnabled(!state.locked && !!state.point);
    });
  }

  function loadBootstrap() {
    return fetchJson(API + '/compose_bootstrap.php').then(function (body) {
      state.bootstrap = Object.assign(state.bootstrap, body.data || {});
      fillFxSelect();
    });
  }

  function findItem(id) {
    id = Number(id);
    for (var i = 0; i < state.items.length; i++) {
      if (Number(state.items[i].id) === id) return state.items[i];
    }
    return null;
  }

  if (pointsEl) {
    pointsEl.addEventListener('click', function (ev) {
      var checkBtn = ev.target.closest('[data-point-check]');
      if (checkBtn) {
        if (state.passwordLocked) return;
        var checkPoint = checkBtn.getAttribute('data-point-check') || '';
        fetchJson(API + '/compose_point_check.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            route: state.route,
            vehicle: state.vehicle,
            date: state.date,
            point: checkPoint,
          }),
        })
          .then(function (body) {
            applyContextPayload(body.data || {});
            showOk('Point review updated');
            return loadLedger();
          })
          .catch(function (err) {
            showError(err.message || 'Point check failed');
          });
        return;
      }
      var btn = ev.target.closest('[data-point]');
      if (!btn) return;
      state.point = btn.getAttribute('data-point') || '';
      state.currentRoute = btn.getAttribute('data-current-route') || state.route;
      renderPoints();
      setWriteEnabled(!state.locked && !!state.point);
      loadLedger();
    });
  }

  if (tbody) {
    tbody.addEventListener('click', function (ev) {
      var editBtn = ev.target.closest('[data-edit]');
      var delBtn = ev.target.closest('[data-del]');
      if (editBtn) {
        var item = findItem(editBtn.getAttribute('data-edit'));
        if (!item) return;
        var type =
          String(item.status || '').toLowerCase() === 'expenses' || Number(item.expenses) > 0
            ? 'expense'
            : 'income';
        openDialog(type, item);
        return;
      }
      if (delBtn) {
        if (state.locked) return;
        if (!window.confirm('Delete this ledger line?')) return;
        var delId = Number(delBtn.getAttribute('data-del'));
        fetchJson(API + '/compose_entry_delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            id: delId,
            route: state.route,
            vehicle: state.vehicle,
            date: state.date,
            point: state.point,
          }),
        })
          .then(function (body) {
            var data = body.data || {};
            state.items = data.items || [];
            renderSummary(data.summary || {});
            renderRows();
            if (data.context) renderContext(data.context);
            showOk('Entry deleted');
          })
          .catch(function (err) {
            showError(err.message || 'Delete failed');
          });
      }
    });
  }

  if (btnIncome) btnIncome.addEventListener('click', function () { openDialog('income'); });
  if (btnExpense) btnExpense.addEventListener('click', function () { openDialog('expense'); });

  if (formCategory) {
    formCategory.addEventListener('change', function () {
      fillParticulars(formCategory.value || '');
    });
  }

  if (fxEl) {
    fxEl.addEventListener('change', function () {
      state.displayCurrency = fxEl.value || 'Default';
      renderRows();
      renderSummary({
        income: state.items.reduce(function (s, it) { return s + Number(it.income || 0); }, 0),
        expenses: state.items.reduce(function (s, it) { return s + Number(it.expenses || 0); }, 0),
        balance: 0,
        count: state.items.length,
      });
      var inc = state.items.reduce(function (s, it) { return s + Number(it.income || 0); }, 0);
      var exp = state.items.reduce(function (s, it) { return s + Number(it.expenses || 0); }, 0);
      renderSummary({ income: inc, expenses: exp, balance: inc - exp, count: state.items.length });
    });
  }

  var cancelBtn = document.getElementById('tds-acc-compose-cancel');
  if (cancelBtn) {
    cancelBtn.addEventListener('click', function () {
      if (dialog && typeof dialog.close === 'function') dialog.close();
    });
  }

  if (form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      if (state.locked) return;
      var payload = {
        type: state.entryType,
        route: state.route,
        vehicle: state.vehicle,
        date: state.date,
        point: state.point,
        current_route: state.currentRoute || state.route,
        category: (formCategory && formCategory.value) || '',
        particular: (formParticular && formParticular.value) || '',
        amount: (formAmount && formAmount.value) || '',
        currency: (formCurrency && formCurrency.value) || 'Tsh',
        detail: (formDetail && formDetail.value) || '',
      };
      var url = API + '/compose_entry.php';
      if (state.mode === 'edit') {
        url = API + '/compose_entry_update.php';
        payload.id = Number((formId && formId.value) || 0);
      }
      fetchJson(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      })
        .then(function (body) {
          if (dialog && typeof dialog.close === 'function') dialog.close();
          showOk(state.mode === 'edit' ? 'Entry updated' : 'Entry saved');
          var data = body.data || {};
          if (data.context) renderContext(data.context);
          return loadLedger();
        })
        .catch(function (err) {
          showError(err.message || 'Save failed');
        });
    });
  }

  if (btnLock) {
    btnLock.addEventListener('click', function () {
      if (lockPassword) lockPassword.value = '';
      if (lockDialog && typeof lockDialog.showModal === 'function') lockDialog.showModal();
    });
  }
  var lockCancel = document.getElementById('tds-acc-compose-lock-cancel');
  if (lockCancel) {
    lockCancel.addEventListener('click', function () {
      if (lockDialog && typeof lockDialog.close === 'function') lockDialog.close();
    });
  }
  if (lockForm) {
    lockForm.addEventListener('submit', function (ev) {
      ev.preventDefault();
      fetchJson(API + '/compose_lock.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          route: state.route,
          vehicle: state.vehicle,
          date: state.date,
          action: 'lock',
          password: (lockPassword && lockPassword.value) || '',
        }),
      })
        .then(function (body) {
          if (lockDialog && typeof lockDialog.close === 'function') lockDialog.close();
          applyContextPayload(body.data || {});
          showOk('Manifest locked');
          return loadLedger();
        })
        .catch(function (err) {
          showError(err.message || 'Lock failed');
        });
    });
  }
  if (btnTransfer) {
    btnTransfer.addEventListener('click', function () {
      if (state.locked) {
        showError('Manifest is analysed and locked');
        return;
      }
      var picked = tbody
        ? Array.prototype.map
            .call(tbody.querySelectorAll('[data-line]:checked'), function (box) {
              return Number(box.getAttribute('data-line'));
            })
            .filter(function (n) {
              return n > 0;
            })
        : [];
      if (!picked.length) {
        showError('Select at least one ledger line');
        return;
      }
      var q = new URLSearchParams({
        route: state.route,
        vehicle: state.vehicle,
        date: state.date,
      });
      fetchJson(API + '/compose_transfer.php?' + q.toString())
        .then(function (body) {
          var targets = (body.data && body.data.targets) || [];
          if (transferTarget) {
            transferTarget.innerHTML = targets
              .map(function (trip) {
                return (
                  '<option value="' +
                  esc(trip.bus) +
                  '" data-route="' +
                  esc(trip.route) +
                  '" data-date="' +
                  esc(trip.trip_date) +
                  '">' +
                  esc(trip.label) +
                  '</option>'
                );
              })
              .join('');
          }
          if (!targets.length) {
            showError('No other trip is available');
            return;
          }
          if (transferDialog && typeof transferDialog.showModal === 'function') transferDialog.showModal();
        })
        .catch(function (err) {
          showError(err.message || 'Could not load trips');
        });
    });
  }
  var transferCancel = document.getElementById('tds-acc-compose-transfer-cancel');
  if (transferCancel) {
    transferCancel.addEventListener('click', function () {
      if (transferDialog && typeof transferDialog.close === 'function') transferDialog.close();
    });
  }
  if (transferForm) {
    transferForm.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var option = transferTarget && transferTarget.selectedOptions ? transferTarget.selectedOptions[0] : null;
      if (!option) {
        showError('Choose a target trip');
        return;
      }
      var picked = tbody
        ? Array.prototype.map
            .call(tbody.querySelectorAll('[data-line]:checked'), function (box) {
              return Number(box.getAttribute('data-line'));
            })
            .filter(function (n) {
              return n > 0;
            })
        : [];
      fetchJson(API + '/compose_transfer.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          route: state.route,
          vehicle: state.vehicle,
          date: state.date,
          target_route: option.getAttribute('data-route') || '',
          target_vehicle: option.value,
          target_date: option.getAttribute('data-date') || '',
          ids: picked,
        }),
      })
        .then(function (body) {
          if (transferDialog && typeof transferDialog.close === 'function') transferDialog.close();
          showOk('Moved ' + ((body.data && body.data.moved) || 0) + ' lines');
          if (body.data && body.data.context) renderContext(body.data.context);
          return loadLedger();
        })
        .catch(function (err) {
          showError(err.message || 'Transfer failed');
        });
    });
  }

  if (btnUnlock) {
    btnUnlock.addEventListener('click', function () {
      if (!window.confirm('Unlock this manifest and allow edits again?')) return;
      fetchJson(API + '/compose_lock.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          route: state.route,
          vehicle: state.vehicle,
          date: state.date,
          action: 'unlock',
        }),
      })
        .then(function (body) {
          applyContextPayload(body.data || {});
          showOk('Manifest unlocked');
          return loadLedger();
        })
        .catch(function (err) {
          showError(err.message || 'Unlock failed');
        });
    });
  }

  if (btnUpload) {
    btnUpload.addEventListener('click', function () {
      loadAttachments()
        .then(function () {
          if (uploadDialog && typeof uploadDialog.showModal === 'function') uploadDialog.showModal();
        })
        .catch(function (err) {
          showError(err.message || 'Failed to load attachments');
        });
    });
  }
  var uploadClose = document.getElementById('tds-acc-compose-upload-close');
  if (uploadClose) {
    uploadClose.addEventListener('click', function () {
      if (uploadDialog && typeof uploadDialog.close === 'function') uploadDialog.close();
    });
  }
  var uploadSave = document.getElementById('tds-acc-compose-upload-save');
  if (uploadSave) {
    uploadSave.addEventListener('click', function () {
      if (!fileInput || !fileInput.files || !fileInput.files[0]) {
        showError('Choose a file first');
        return;
      }
      var fd = new FormData();
      fd.append('file', fileInput.files[0]);
      fd.append('route', state.route);
      fd.append('vehicle', state.vehicle);
      fd.append('date', state.date);
      fd.append('point', state.point || '');
      fd.append('current_route', state.currentRoute || state.route);
      fetchJson(API + '/compose_attachments.php', { method: 'POST', body: fd })
        .then(function (body) {
          state.attachments = (body.data && body.data.items) || [];
          renderAttachments();
          if (fileInput) fileInput.value = '';
          showOk('Attachment uploaded');
        })
        .catch(function (err) {
          showError(err.message || 'Upload failed');
        });
    });
  }
  if (attachmentsEl) {
    attachmentsEl.addEventListener('click', function (ev) {
      var del = ev.target.closest('[data-att-del]');
      if (!del) return;
      if (!window.confirm('Delete this attachment?')) return;
      fetchJson(API + '/compose_attachments.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'delete',
          id: Number(del.getAttribute('data-att-del')),
          route: state.route,
          vehicle: state.vehicle,
          date: state.date,
        }),
      })
        .then(function (body) {
          state.attachments = (body.data && body.data.items) || [];
          renderAttachments();
          showOk('Attachment deleted');
        })
        .catch(function (err) {
          showError(err.message || 'Delete failed');
        });
    });
  }

  if (btnPull) {
    btnPull.addEventListener('click', function () {
      if (state.locked) {
        showError('Manifest is analysed and locked');
        return;
      }
      fetchJson(
        API +
          '/compose_pull.php?current_route=' +
          encodeURIComponent(state.currentRoute || state.route) +
          '&vehicle=' +
          encodeURIComponent(state.vehicle)
      )
        .then(function (body) {
          var dates = (body.data && body.data.dates) || [];
          if (!pullDate) return;
          if (!dates.length) {
            dates = [state.date];
          }
          pullDate.innerHTML = dates
            .map(function (d) {
              return '<option value="' + esc(d) + '">' + esc(d) + '</option>';
            })
            .join('');
          if (state.date) pullDate.value = state.date;
          if (pullDialog && typeof pullDialog.showModal === 'function') pullDialog.showModal();
        })
        .catch(function (err) {
          showError(err.message || 'Failed to load source dates');
        });
    });
  }
  var pullCancel = document.getElementById('tds-acc-compose-pull-cancel');
  if (pullCancel) {
    pullCancel.addEventListener('click', function () {
      if (pullDialog && typeof pullDialog.close === 'function') pullDialog.close();
    });
  }
  if (pullForm) {
    pullForm.addEventListener('submit', function (ev) {
      ev.preventDefault();
      fetchJson(API + '/compose_pull.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          route: state.route,
          current_route: state.currentRoute || state.route,
          vehicle: state.vehicle,
          date: state.date,
          source_date: (pullDate && pullDate.value) || state.date,
          point: state.point,
        }),
      })
        .then(function (body) {
          if (pullDialog && typeof pullDialog.close === 'function') pullDialog.close();
          var data = body.data || {};
          if (data.context) renderContext(data.context);
          state.items = data.items || state.items;
          renderSummary(data.summary || {});
          renderRows();
          showOk('Pulled ' + (data.created || 0) + ' line(s)');
          return loadLedger();
        })
        .catch(function (err) {
          showError(err.message || 'Pull failed');
        });
    });
  }

  if (btnCharges) {
    btnCharges.addEventListener('click', function () {
      fetchJson(
        API +
          '/compose_user_charges.php?' +
          tripParams() +
          '&current_route=' +
          encodeURIComponent(state.currentRoute || state.route)
      )
        .then(function (body) {
          var data = body.data || {};
          var totals = data.totals || {};
          var paidEl = document.getElementById('tds-acc-compose-charges-paid');
          var toPayEl = document.getElementById('tds-acc-compose-charges-topay');
          var dueEl = document.getElementById('tds-acc-compose-charges-due');
          if (paidEl) paidEl.textContent = money(totals.paid);
          if (toPayEl) toPayEl.textContent = money(totals.to_pay);
          if (dueEl) dueEl.textContent = money(totals.due);
          var rows = data.rows || [];
          if (chargesBody) {
            chargesBody.innerHTML = rows.length
              ? rows
                  .map(function (r) {
                    return (
                      '<tr><td>' +
                      esc(r.uploader) +
                      '</td><td class="tds-mono">' +
                      money(r.paid) +
                      '</td><td class="tds-mono">' +
                      money(r.to_be) +
                      '</td></tr>'
                    );
                  })
                  .join('')
              : '<tr><td colspan="3">No uploader charges for this point.</td></tr>';
          }
          if (chargesDialog && typeof chargesDialog.showModal === 'function') chargesDialog.showModal();
        })
        .catch(function (err) {
          showError(err.message || 'Failed to load user charges');
        });
    });
  }
  var chargesClose = document.getElementById('tds-acc-compose-charges-close');
  if (chargesClose) {
    chargesClose.addEventListener('click', function () {
      if (chargesDialog && typeof chargesDialog.close === 'function') chargesDialog.close();
    });
  }

  var searchTimer = null;
  if (qEl) {
    qEl.addEventListener('input', function () {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(loadLedger, 250);
    });
  }

  Promise.all([loadBootstrap(), loadContext()])
    .then(function () {
      return loadLedger();
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load compose');
    });
})();
