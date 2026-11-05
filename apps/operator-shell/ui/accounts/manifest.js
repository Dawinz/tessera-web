/**
 * A-P0-01 Accounts Manifest — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-acc-manifest-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/accounts/api/console';
  var state = { page: 1, perPage: 25, total: 0, mode: 'create', vehicles: [], routes: [] };

  var errEl = document.getElementById('tds-acc-manifest-error');
  var okEl = document.getElementById('tds-acc-manifest-success');
  var loadingEl = document.getElementById('tds-acc-manifest-loading');
  var emptyEl = document.getElementById('tds-acc-manifest-empty');
  var wrapEl = document.getElementById('tds-acc-manifest-wrap');
  var tbody = document.getElementById('tds-acc-manifest-tbody');
  var pager = document.getElementById('tds-acc-manifest-pager');
  var metaEl = document.getElementById('tds-acc-manifest-meta');
  var qEl = document.getElementById('tds-acc-manifest-q');
  var routeEl = document.getElementById('tds-acc-manifest-route');
  var vehicleEl = document.getElementById('tds-acc-manifest-vehicle');
  var fromEl = document.getElementById('tds-acc-manifest-from');
  var toEl = document.getElementById('tds-acc-manifest-to');
  var dialog = document.getElementById('tds-acc-manifest-dialog');
  var form = document.getElementById('tds-acc-manifest-form');
  var formRoute = document.getElementById('tds-acc-manifest-form-route');
  var formVehicle = document.getElementById('tds-acc-manifest-form-vehicle');
  var formDate = document.getElementById('tds-acc-manifest-form-date');
  var formDateEdit = document.getElementById('tds-acc-manifest-form-date-edit');
  var formType = document.getElementById('tds-acc-manifest-form-type');
  var formId = document.getElementById('tds-acc-manifest-edit-id');
  var dialogTitle = document.getElementById('tds-acc-manifest-dialog-title');
  var dateWrap = document.getElementById('tds-acc-manifest-date-wrap');
  var dateEditWrap = document.getElementById('tds-acc-manifest-date-edit-wrap');
  var typeWrap = document.getElementById('tds-acc-manifest-type-wrap');

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

  function filterParams() {
    var params = new URLSearchParams();
    params.set('page', String(state.page));
    params.set('per_page', String(state.perPage));
    if (routeEl && routeEl.value) params.set('route', routeEl.value);
    if (vehicleEl && vehicleEl.value) params.set('vehicle', vehicleEl.value);
    if (fromEl && fromEl.value) params.set('date_from', fromEl.value);
    if (toEl && toEl.value) params.set('date_to', toEl.value);
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    return params;
  }

  function fillSelect(el, items, blankLabel, valueKey, labelKey) {
    if (!el) return;
    var cur = el.value;
    el.innerHTML = '';
    var blank = document.createElement('option');
    blank.value = '';
    blank.textContent = blankLabel || 'All';
    el.appendChild(blank);
    (items || []).forEach(function (it) {
      var opt = document.createElement('option');
      if (typeof it === 'string') {
        opt.value = it;
        opt.textContent = it;
      } else {
        opt.value = it[valueKey || 'name'] || '';
        opt.textContent = it[labelKey || 'label'] || it[valueKey || 'name'] || '';
      }
      el.appendChild(opt);
    });
    if (cur) el.value = cur;
  }

  function setSummary(summary) {
    if (!summary) return;
    var countEl = document.getElementById('tds-acc-manifest-count');
    var incomeEl = document.getElementById('tds-acc-manifest-income');
    var expEl = document.getElementById('tds-acc-manifest-expenses');
    var balEl = document.getElementById('tds-acc-manifest-balance');
    if (countEl) countEl.textContent = String(summary.count || 0);
    if (incomeEl) incomeEl.textContent = money(summary.income);
    if (expEl) expEl.textContent = money(summary.expenses);
    if (balEl) balEl.textContent = money(summary.balance);
  }

  function badgeClass(badge) {
    if (badge === 'paid') return 'tds-badge tds-badge-paid';
    if (badge === 'warn') return 'tds-badge tds-badge-warn';
    return 'tds-badge tds-badge-neutral';
  }

  function renderRows(items) {
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
      var actions =
        '<div class="tds-table-actions">' +
        '<a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
        esc(it.compose_url) +
        '">Insert</a>' +
        '<a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
        esc(it.view_url) +
        '" target="_blank" rel="noopener">View</a>';
      if (it.can_edit) {
        actions +=
          '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm tds-acc-manifest-edit">Edit</button>';
      }
      actions += '</div>';

      tr.innerHTML =
        '<td class="tds-mono"><strong>' +
        esc(it.code || '—') +
        '</strong></td>' +
        '<td>' +
        esc(it.route) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(it.vehicle) +
        '</td>' +
        '<td>' +
        esc(it.date_label) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.income) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.expenses) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.balance) +
        '</td>' +
        '<td><span class="' +
        badgeClass(it.status_badge) +
        '">' +
        esc(it.status_label) +
        '</span></td>' +
        '<td>' +
        actions +
        '</td>';
      tbody.appendChild(tr);
      var editBtn = tr.querySelector('.tds-acc-manifest-edit');
      if (editBtn) {
        editBtn.addEventListener('click', function () {
          openEdit(it);
        });
      }
    });
  }

  function renderPager() {
    if (!pager || !metaEl) return;
    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (state.total === 0) {
      pager.hidden = true;
      return;
    }
    pager.hidden = false;
    metaEl.textContent =
      'Page ' + state.page + ' of ' + pages + ' · ' + state.total.toLocaleString() + ' manifests';
    var prev = document.getElementById('tds-acc-manifest-prev');
    var next = document.getElementById('tds-acc-manifest-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page >= pages;
  }

  function loadList() {
    showError('');
    setLoading(true);
    fetchJson(API + '/manifests.php?' + filterParams().toString())
      .then(function (body) {
        var data = body.data || {};
        state.total = Number(data.total || 0);
        if (body.meta && body.meta.page) state.page = Number(body.meta.page);
        setSummary(data.summary);
        renderRows(data.items || []);
        renderPager();
      })
      .catch(function (e) {
        showError(e.message || 'Could not load manifests');
        if (emptyEl) emptyEl.hidden = true;
        if (wrapEl) wrapEl.hidden = true;
      })
      .then(function () {
        setLoading(false);
      });
  }

  function loadTripDates(route) {
    formDate.innerHTML = '<option value="">Choose trip date</option>';
    if (!route) return Promise.resolve();
    return fetchJson(API + '/manifest_trip_dates.php?route=' + encodeURIComponent(route)).then(
      function (body) {
        var dates = (body.data && body.data.dates) || [];
        dates.forEach(function (d) {
          var opt = document.createElement('option');
          opt.value = d.date;
          opt.textContent = d.date_label || d.date;
          formDate.appendChild(opt);
        });
      }
    );
  }

  function openCreate() {
    state.mode = 'create';
    dialogTitle.textContent = 'Add manifest';
    formId.value = '';
    if (dateWrap) dateWrap.hidden = false;
    if (dateEditWrap) dateEditWrap.hidden = true;
    if (typeWrap) typeWrap.hidden = false;
    formRoute.disabled = false;
    if (formRoute.options.length > 1 && !formRoute.value) formRoute.selectedIndex = 1;
    if (formVehicle.options.length > 1 && !formVehicle.value) formVehicle.selectedIndex = 1;
    loadTripDates(formRoute.value).then(function () {
      if (formDate.options.length > 1) formDate.selectedIndex = 1;
    });
    if (dialog.showModal) dialog.showModal();
    else dialog.setAttribute('open', 'open');
  }

  function openEdit(it) {
    state.mode = 'edit';
    dialogTitle.textContent = 'Edit manifest';
    formId.value = String(it.id);
    formRoute.value = it.route;
    formRoute.disabled = true;
    formVehicle.value = it.vehicle;
    if (dateWrap) dateWrap.hidden = true;
    if (dateEditWrap) dateEditWrap.hidden = false;
    if (typeWrap) typeWrap.hidden = true;
    if (formDateEdit) formDateEdit.value = it.date || '';
    if (dialog.showModal) dialog.showModal();
    else dialog.setAttribute('open', 'open');
  }

  function closeDialog() {
    formRoute.disabled = false;
    if (dialog.close) dialog.close();
    else dialog.removeAttribute('open');
  }

  function loadFilters() {
    return fetchJson(API + '/manifests_filters.php').then(function (body) {
      var data = body.data || {};
      state.vehicles = data.vehicles || [];
      state.routes = data.routes || [];
      fillSelect(routeEl, state.routes, 'All routes', 'name', 'label');
      fillSelect(vehicleEl, state.vehicles, 'All vehicles');
      fillSelect(formRoute, state.routes, 'Choose route', 'name', 'label');
      fillSelect(formVehicle, state.vehicles, 'Choose vehicle');
    });
  }

  document.getElementById('tds-acc-manifest-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-acc-manifest-reset').addEventListener('click', function () {
    if (qEl) qEl.value = '';
    if (routeEl) routeEl.value = '';
    if (vehicleEl) vehicleEl.value = '';
    if (fromEl) fromEl.value = '';
    if (toEl) toEl.value = '';
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-acc-manifest-prev').addEventListener('click', function () {
    if (state.page > 1) {
      state.page -= 1;
      loadList();
    }
  });
  document.getElementById('tds-acc-manifest-next').addEventListener('click', function () {
    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (state.page < pages) {
      state.page += 1;
      loadList();
    }
  });
  document.getElementById('tds-acc-manifest-open-create').addEventListener('click', openCreate);
  document.getElementById('tds-acc-manifest-cancel').addEventListener('click', closeDialog);
  formRoute.addEventListener('change', function () {
    if (state.mode === 'create') loadTripDates(formRoute.value);
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    showError('');
    showOk('');
    var payload =
      state.mode === 'edit'
        ? {
            id: Number(formId.value || 0),
            route: formRoute.value,
            vehicle: formVehicle.value,
            date: formDateEdit ? formDateEdit.value : '',
          }
        : {
            route: formRoute.value,
            vehicle: formVehicle.value,
            date: formDate.value,
            vehicle_type: formType ? formType.value : 'Bus',
          };
    var url = API + (state.mode === 'edit' ? '/manifest_update.php' : '/manifest_create.php');
    var saveBtn = document.getElementById('tds-acc-manifest-save');
    saveBtn.disabled = true;
    fetchJson(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(function (body) {
        var item = (body.data && body.data.item) || {};
        closeDialog();
        showOk(
          state.mode === 'edit'
            ? 'Manifest updated.'
            : 'Manifest registered' + (item.code ? ': ' + item.code : '.')
        );
        if (item.vehicle && qEl) qEl.value = item.vehicle;
        if (item.date && fromEl) {
          fromEl.value = item.date;
          toEl.value = item.date;
        }
        state.page = 1;
        loadList();
      })
      .catch(function (err) {
        showError(err.message || 'Save failed');
      })
      .then(function () {
        saveBtn.disabled = false;
      });
  });

  if (qEl) {
    qEl.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        state.page = 1;
        loadList();
      }
    });
  }

  loadFilters()
    .then(loadList)
    .catch(function (e) {
      showError(e.message || 'Could not load filters');
      loadList();
    });
})();
