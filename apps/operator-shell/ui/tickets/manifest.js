/**
 * T-P0-04 Manifest — modern console UI (TDS + console API).
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-manifest-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var state = { page: 1, perPage: 25, total: 0, buses: [] };

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
  var busEl = document.getElementById('tds-manifest-bus');
  var fromEl = document.getElementById('tds-manifest-from');
  var toEl = document.getElementById('tds-manifest-to');
  var editDlg = document.getElementById('tds-manifest-edit');
  var editId = document.getElementById('tds-edit-id');
  var editBus = document.getElementById('tds-edit-bus');
  var editDriver = document.getElementById('tds-edit-driver');
  var editConductor = document.getElementById('tds-edit-conductor');

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    if (!okEl) return;
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
    if (msg) setTimeout(function () { okEl.hidden = true; }, 3500);
  }
  function setLoading(on) {
    if (loadingEl) loadingEl.hidden = !on;
    if (wrapEl) wrapEl.hidden = !!on;
    if (pager && on) pager.hidden = true;
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
  }
  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function fetchJson(url, opts) {
    opts = opts || {};
    var headers = Object.assign({ Accept: 'application/json' }, opts.headers || {});
    return fetch(url, Object.assign({ credentials: 'same-origin', headers: headers }, opts)).then(
      function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body || body.ok === false) {
            var message =
              body && body.error && body.error.message ? body.error.message : 'Request failed';
            var err = new Error(message);
            err.status = res.status;
            throw err;
          }
          return body;
        });
      }
    );
  }

  function fillSelect(sel, items, placeholder, valueKey, labelKey) {
    if (!sel) return;
    var keep = sel.value;
    sel.innerHTML = '';
    var opt0 = document.createElement('option');
    opt0.value = '';
    opt0.textContent = placeholder;
    sel.appendChild(opt0);
    (items || []).forEach(function (it) {
      var o = document.createElement('option');
      if (typeof it === 'string') {
        o.value = it;
        o.textContent = it;
      } else {
        o.value = it[valueKey];
        o.textContent = it[labelKey];
      }
      sel.appendChild(o);
    });
    if (keep) sel.value = keep;
  }

  function filterParams() {
    var params = new URLSearchParams();
    params.set('page', String(state.page));
    params.set('per_page', String(state.perPage));
    params.set('sort', 'trip_date');
    params.set('order', 'desc');
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    if (routeEl && routeEl.value) params.set('route_id', routeEl.value);
    if (busEl && busEl.value) params.set('bus', busEl.value);
    if (fromEl && fromEl.value) params.set('trip_date_from', fromEl.value);
    if (toEl && toEl.value) params.set('trip_date_to', toEl.value);
    return params;
  }

  function setKpis(summary) {
    var s = summary || {};
    var map = {
      trips: s.trips,
      buses: s.buses,
      seats: s.seats,
      sales: money(s.sales),
      road: money(s.road),
    };
    Object.keys(map).forEach(function (k) {
      var el = document.querySelector('[data-kpi="' + k + '"]');
      if (el) el.textContent = map[k] == null ? '—' : String(map[k]);
    });
  }

  function actionBtn(label, kind, onClick) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'tds-btn tds-btn-' + kind + ' tds-btn-sm';
    b.textContent = label;
    b.addEventListener('click', onClick);
    return b;
  }
  function actionLink(label, href) {
    var a = document.createElement('a');
    a.className = 'tds-btn tds-btn-secondary tds-btn-sm';
    a.href = href;
    a.textContent = label;
    a.target = '_blank';
    a.rel = 'noopener';
    return a;
  }

  function openEdit(it) {
    if (!editDlg) return;
    editId.value = String(it.id);
    fillSelect(editBus, state.buses, 'Select vehicle');
    editBus.value = it.bus || '';
    if (it.bus && editBus.value !== it.bus) {
      var o = document.createElement('option');
      o.value = it.bus;
      o.textContent = it.bus;
      editBus.appendChild(o);
      editBus.value = it.bus;
    }
    editDriver.value = it.driver || '';
    editConductor.value = it.conductor || '';
    if (typeof editDlg.showModal === 'function') editDlg.showModal();
    else editDlg.setAttribute('open', 'open');
  }

  function renderRows(items) {
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
      tr.innerHTML =
        '<td><strong>' +
        esc(it.trip_date_display) +
        '</strong><div class="tds-muted" style="font-size:var(--tds-fs-xs)">' +
        esc(it.start_time || '') +
        (it.trip_relative ? ' · ' + esc(it.trip_relative) : '') +
        '</div></td>' +
        '<td>' +
        esc(it.bus) +
        '</td>' +
        '<td>' +
        esc(it.route_label) +
        '</td>' +
        '<td>' +
        esc(it.driver || '—') +
        '</td>' +
        '<td>' +
        esc(it.conductor || '—') +
        '</td>' +
        '<td class="tds-mono">' +
        esc(String(it.seats)) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(money(it.amount)) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(money(it.road_collection)) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(money(it.gross)) +
        '</td>' +
        '<td><div class="tds-table-actions"></div></td>';
      tbody.appendChild(tr);
      var actions = tr.querySelector('.tds-table-actions');
      actions.appendChild(actionLink('Book', it.book_url));
      actions.appendChild(actionLink('PDF', it.pdf_url));
      actions.appendChild(actionLink('POS', it.pos_url));
      actions.appendChild(
        actionBtn('Edit', 'primary', function () {
          openEdit(it);
        })
      );
      if (it.can_delete) {
        actions.appendChild(
          actionBtn('Delete', 'secondary', function () {
            deleteManifest(it);
          })
        );
      }
    });
  }

  function deleteManifest(it) {
    if (!it || !it.id) return;
    var label = (it.bus || 'manifest') + ' · ' + (it.trip_date_display || it.trip_date || '');
    if (!window.confirm('Delete ' + label + '? Only empty manifests (zero seats) can be removed.')) {
      return;
    }
    fetchJson(API + '/manifest_delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
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

  function updatePager() {
    if (!pager || !metaEl) return;
    var total = state.total;
    var from = total === 0 ? 0 : (state.page - 1) * state.perPage + 1;
    var to = Math.min(state.page * state.perPage, total);
    metaEl.textContent = total
      ? 'Showing ' + from + '–' + to + ' of ' + total.toLocaleString()
      : 'No results';
    pager.hidden = total === 0;
    var prev = document.getElementById('tds-manifest-prev');
    var next = document.getElementById('tds-manifest-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page * state.perPage >= total;
  }

  function loadList() {
    showError('');
    setLoading(true);
    if (emptyEl) emptyEl.hidden = true;
    return fetchJson(API + '/manifests.php?' + filterParams().toString())
      .then(function (body) {
        state.total = (body.data && body.data.total) || 0;
        if (body.meta && body.meta.page) state.page = body.meta.page;
        setKpis(body.data && body.data.summary);
        renderRows((body.data && body.data.items) || []);
        updatePager();
      })
      .catch(function (err) {
        showError(err.message || 'Could not load manifests');
        setKpis({});
        renderRows([]);
      })
      .finally(function () {
        setLoading(false);
        updatePager();
      });
  }

  function loadFilters() {
    return fetchJson(API + '/manifests_filters.php').then(function (body) {
      var d = body.data || {};
      state.buses = d.buses || [];
      fillSelect(routeEl, d.routes || [], 'All routes', 'id', 'label');
      fillSelect(busEl, state.buses, 'All vehicles');
    });
  }

  function saveEdit() {
    var id = parseInt(editId.value, 10);
    fetchJson(API + '/manifest_update.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({
        id: id,
        bus: editBus.value,
        driver: editDriver.value.trim(),
        conductor: editConductor.value.trim(),
      }),
    })
      .then(function () {
        showOk('Manifest updated');
        if (typeof editDlg.close === 'function') editDlg.close();
        else editDlg.removeAttribute('open');
        return loadList();
      })
      .catch(function (err) {
        showError(err.message || 'Could not update manifest');
      });
  }

  document.getElementById('tds-manifest-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-manifest-reset').addEventListener('click', function () {
    if (qEl) qEl.value = '';
    if (routeEl) routeEl.value = '';
    if (busEl) busEl.value = '';
    if (fromEl) fromEl.value = '';
    if (toEl) toEl.value = '';
    state.page = 1;
    loadList();
  });
  if (qEl) {
    qEl.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        state.page = 1;
        loadList();
      }
    });
  }
  document.getElementById('tds-manifest-prev').addEventListener('click', function () {
    if (state.page > 1) {
      state.page -= 1;
      loadList();
    }
  });
  document.getElementById('tds-manifest-next').addEventListener('click', function () {
    if (state.page * state.perPage < state.total) {
      state.page += 1;
      loadList();
    }
  });
  document.getElementById('tds-manifest-edit-close').addEventListener('click', function () {
    if (typeof editDlg.close === 'function') editDlg.close();
    else editDlg.removeAttribute('open');
  });
  document.getElementById('tds-edit-save').addEventListener('click', saveEdit);

  /* Default: recent + near-term window so first paint is useful */
  try {
    var isoLocal = function (d) {
      var y = d.getFullYear();
      var m = String(d.getMonth() + 1).padStart(2, '0');
      var day = String(d.getDate()).padStart(2, '0');
      return y + '-' + m + '-' + day;
    };
    var today = new Date();
    if (fromEl && !fromEl.value) {
      var start = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);
      fromEl.value = isoLocal(start);
    }
    if (toEl && !toEl.value) {
      var end = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 21);
      toEl.value = isoLocal(end);
    }
  } catch (e) {}

  loadFilters()
    .catch(function () {})
    .then(function () {
      return loadList();
    });
})();
