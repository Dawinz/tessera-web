/**
 * C-P0-04 Vehicle manifest — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-manifest-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/cargo/api/console';
  var state = { page: 1, perPage: 25, total: 0, mode: 'create', vehicles: [] };

  var errEl = document.getElementById('tds-manifest-error');
  var okEl = document.getElementById('tds-manifest-success');
  var loadingEl = document.getElementById('tds-manifest-loading');
  var emptyEl = document.getElementById('tds-manifest-empty');
  var wrapEl = document.getElementById('tds-manifest-wrap');
  var tbody = document.getElementById('tds-manifest-tbody');
  var pager = document.getElementById('tds-manifest-pager');
  var metaEl = document.getElementById('tds-manifest-meta');
  var qEl = document.getElementById('tds-manifest-q');
  var routeEl = document.getElementById('tds-manifest-route');
  var vehicleEl = document.getElementById('tds-manifest-vehicle');
  var fromEl = document.getElementById('tds-manifest-from');
  var toEl = document.getElementById('tds-manifest-to');
  var dialog = document.getElementById('tds-manifest-dialog');
  var form = document.getElementById('tds-manifest-form');
  var formRoute = document.getElementById('tds-manifest-form-route');
  var formVehicle = document.getElementById('tds-manifest-form-vehicle');
  var formDriver = document.getElementById('tds-manifest-form-driver');
  var formConductor = document.getElementById('tds-manifest-form-conductor');
  var formDate = document.getElementById('tds-manifest-form-date');
  var formId = document.getElementById('tds-manifest-edit-id');
  var dialogTitle = document.getElementById('tds-manifest-dialog-title');

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
    return fetch(url, {
      credentials: 'same-origin',
      headers: Object.assign({ Accept: 'application/json' }, opts.headers || {}),
      method: opts.method || 'GET',
      body: opts.body || undefined,
    }).then(function (res) {
      return res.json().then(function (body) {
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
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    if (routeEl && routeEl.value) params.set('route', routeEl.value);
    if (vehicleEl && vehicleEl.value) params.set('vehicle', vehicleEl.value);
    if (fromEl && fromEl.value) params.set('date_from', fromEl.value);
    if (toEl && toEl.value) params.set('date_to', toEl.value);
    return params;
  }

  function fillSelect(sel, items, placeholder, valueKey) {
    if (!sel) return;
    var keep = sel.value;
    sel.innerHTML = '';
    var opt0 = document.createElement('option');
    opt0.value = '';
    opt0.textContent = placeholder;
    sel.appendChild(opt0);
    (items || []).forEach(function (it) {
      var o = document.createElement('option');
      var val = typeof it === 'string' ? it : it[valueKey || 'name'];
      o.value = val;
      o.textContent = val;
      sel.appendChild(o);
    });
    if (keep) sel.value = keep;
  }

  function syncCrewFromVehicle() {
    var name = formVehicle.value;
    var found = (state.vehicles || []).find(function (v) {
      return v.name === name;
    });
    if (found) {
      if (formDriver && !formDriver.value) formDriver.value = found.driver || '';
      if (formConductor && !formConductor.value) formConductor.value = found.conductor || '';
    }
  }

  function actionBtn(label, kind, onClick) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'tds-btn tds-btn-' + kind + ' tds-btn-sm';
    b.textContent = label;
    b.addEventListener('click', onClick);
    return b;
  }
  function actionLink(label, href, ghost) {
    var a = document.createElement('a');
    a.className = 'tds-btn ' + (ghost ? 'tds-btn-ghost' : 'tds-btn-secondary') + ' tds-btn-sm';
    a.href = href;
    a.textContent = label;
    if (ghost) {
      a.target = '_blank';
      a.rel = 'noopener';
    }
    return a;
  }

  function sendSms(it) {
    if (!it || !it.id) return;
    if (!window.confirm('Send packed SMS to senders on ' + (it.vehicle || 'this') + ' · ' + (it.date_label || '') + '?')) {
      return;
    }
    fetchJson(API + '/manifest_sms.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: it.id }),
    })
      .then(function (body) {
        var n = (body.data && body.data.notified) || 0;
        showOk(n ? 'SMS queued for ' + n + ' sender(s).' : 'Manifest marked as notified.');
        return loadList();
      })
      .catch(function (err) {
        showError(err.message || 'Could not send SMS');
      });
  }

  function deleteManifest(it) {
    if (!it || !it.id) return;
    if (!window.confirm('Delete manifest ' + (it.code || it.vehicle || '') + '? Only empty manifests can be removed.')) {
      return;
    }
    fetchJson(API + '/manifest_delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: it.id }),
    })
      .then(function () {
        showOk('Manifest deleted');
        return loadList();
      })
      .catch(function (err) {
        showError(err.message || 'Could not delete manifest');
      });
  }

  function openRemark(it) {
    var dlg = document.getElementById('tds-manifest-remark');
    var idEl = document.getElementById('tds-manifest-remark-id');
    var labelEl = document.getElementById('tds-manifest-remark-label');
    var textEl = document.getElementById('tds-manifest-remark-text');
    if (!dlg || !it) return;
    if (idEl) idEl.value = String(it.id);
    if (labelEl) labelEl.textContent = (it.vehicle || 'Manifest') + ' · ' + (it.date_label || '');
    if (textEl) textEl.value = it.remark || '';
    try {
      if (typeof dlg.showModal === 'function') dlg.showModal();
      else dlg.setAttribute('open', 'open');
    } catch (err) {
      dlg.setAttribute('open', 'open');
    }
  }

  function closeRemark() {
    var dlg = document.getElementById('tds-manifest-remark');
    if (!dlg) return;
    if (typeof dlg.close === 'function') dlg.close();
    else dlg.removeAttribute('open');
  }

  function saveRemark() {
    var idEl = document.getElementById('tds-manifest-remark-id');
    var textEl = document.getElementById('tds-manifest-remark-text');
    var id = parseInt((idEl && idEl.value) || '0', 10);
    var text = (textEl && textEl.value) || '';
    if (!id) return;
    fetchJson(API + '/manifest_remark.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id, remark_text: text }),
    })
      .then(function () {
        closeRemark();
        showOk('Remark saved');
        return loadList();
      })
      .catch(function (err) {
        showError(err.message || 'Could not save remark');
      });
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
      tr.innerHTML =
        '<td class="tds-mono"><strong>' +
        esc(it.code) +
        '</strong>' +
        (it.locked ? ' <span class="tds-badge tds-badge-neutral">Locked</span>' : '') +
        '</td>' +
        '<td>' +
        esc(it.route) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(it.vehicle) +
        '</td>' +
        '<td>' +
        esc(it.date_label) +
        '</td>' +
        '<td>' +
        esc(it.driver) +
        '</td>' +
        '<td>' +
        esc(it.conductor) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.to_pay) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.paid) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.total) +
        (it.currency
          ? ' <span class="tds-badge tds-badge-paid">' + esc(it.currency) + '</span>'
          : '') +
        '</td>' +
        '<td><div class="tds-table-actions"></div></td>';
      tbody.appendChild(tr);
      var actions = tr.querySelector('.tds-table-actions');
      actions.appendChild(actionLink('View', it.view_url, true));
      actions.appendChild(actionLink('Load', it.load_url, false));
      actions.appendChild(actionLink('Checkout', it.checkout_url, true));
      actions.appendChild(actionLink('Charges', it.charges_url, true));
      if (it.can_edit) {
        actions.appendChild(
          actionBtn('Edit', 'secondary', function () {
            openEdit(it);
          })
        );
      }
      actions.appendChild(
        actionBtn('Remark', 'secondary', function () {
          openRemark(it);
        })
      );
      if (it.can_sms) {
        actions.appendChild(
          actionBtn('SMS', 'secondary', function () {
            sendSms(it);
          })
        );
      }
      if (it.can_delete) {
        actions.appendChild(
          actionBtn('Delete', 'secondary', function () {
            deleteManifest(it);
          })
        );
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
    var prev = document.getElementById('tds-manifest-prev');
    var next = document.getElementById('tds-manifest-next');
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

  function openCreate() {
    state.mode = 'create';
    dialogTitle.textContent = 'Register manifest';
    formId.value = '';
    formDriver.value = '';
    formConductor.value = '';
    formDate.value = new Date().toISOString().slice(0, 10);
    if (formRoute.options.length > 1 && !formRoute.value) formRoute.selectedIndex = 1;
    if (formVehicle.options.length > 1 && !formVehicle.value) formVehicle.selectedIndex = 1;
    syncCrewFromVehicle();
    if (dialog.showModal) dialog.showModal();
    else dialog.setAttribute('open', 'open');
  }

  function openEdit(it) {
    state.mode = 'edit';
    dialogTitle.textContent = 'Edit manifest';
    formId.value = String(it.id);
    formRoute.value = it.route;
    formVehicle.value = it.vehicle;
    formDriver.value = it.driver || '';
    formConductor.value = it.conductor || '';
    formDate.value = it.date || '';
    if (dialog.showModal) dialog.showModal();
    else dialog.setAttribute('open', 'open');
  }

  function closeDialog() {
    if (dialog.close) dialog.close();
    else dialog.removeAttribute('open');
  }

  function loadFilters() {
    return fetchJson(API + '/manifests_filters.php').then(function (body) {
      var data = body.data || {};
      state.vehicles = data.vehicles || [];
      fillSelect(routeEl, data.routes || [], 'All routes');
      fillSelect(vehicleEl, state.vehicles, 'All vehicles', 'name');
      fillSelect(formRoute, data.routes || [], 'Choose route');
      fillSelect(formVehicle, state.vehicles, 'Choose vehicle', 'name');
    });
  }

  document.getElementById('tds-manifest-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-manifest-reset').addEventListener('click', function () {
    if (qEl) qEl.value = '';
    if (routeEl) routeEl.value = '';
    if (vehicleEl) vehicleEl.value = '';
    if (fromEl) fromEl.value = '';
    if (toEl) toEl.value = '';
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-manifest-prev').addEventListener('click', function () {
    if (state.page > 1) {
      state.page -= 1;
      loadList();
    }
  });
  document.getElementById('tds-manifest-next').addEventListener('click', function () {
    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (state.page < pages) {
      state.page += 1;
      loadList();
    }
  });
  document.getElementById('tds-manifest-open-create').addEventListener('click', openCreate);
  document.getElementById('tds-manifest-cancel').addEventListener('click', closeDialog);
  formVehicle.addEventListener('change', syncCrewFromVehicle);
  var remarkClose = document.getElementById('tds-manifest-remark-close');
  var remarkSave = document.getElementById('tds-manifest-remark-save');
  if (remarkClose) remarkClose.addEventListener('click', closeRemark);
  if (remarkSave) remarkSave.addEventListener('click', saveRemark);

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    showError('');
    showOk('');
    var payload = {
      vehicle: formVehicle.value,
      driver: formDriver.value,
      conductor: formConductor.value,
      route: formRoute.value,
      date: formDate.value,
    };
    var url = API + '/manifest_create.php';
    if (state.mode === 'edit') {
      url = API + '/manifest_update.php';
      payload.id = Number(formId.value || 0);
    }
    var saveBtn = document.getElementById('tds-manifest-save');
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

  // Default: last year window so older manifests still appear on quiet tenants
  if (fromEl && !fromEl.value) {
    var d = new Date();
    d.setFullYear(d.getFullYear() - 1);
    fromEl.value = d.toISOString().slice(0, 10);
  }

  loadFilters()
    .then(loadList)
    .catch(function (e) {
      showError(e.message || 'Could not load filters');
      loadList();
    });
})();
