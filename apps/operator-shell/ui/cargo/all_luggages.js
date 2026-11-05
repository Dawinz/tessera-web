/**
 * C-P0-02 All luggages — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-luggages-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/cargo/api/console';
  var state = { page: 1, perPage: 50, total: 0, store: '' };

  var errEl = document.getElementById('tds-luggages-error');
  var okEl = document.getElementById('tds-luggages-success');
  var loadingEl = document.getElementById('tds-luggages-loading');
  var emptyEl = document.getElementById('tds-luggages-empty');
  var wrapEl = document.getElementById('tds-luggages-wrap');
  var tbody = document.getElementById('tds-luggages-tbody');
  var pager = document.getElementById('tds-luggages-pager');
  var metaEl = document.getElementById('tds-luggages-meta');
  var qEl = document.getElementById('tds-luggages-q');
  var routeEl = document.getElementById('tds-luggages-route');
  var fromEl = document.getElementById('tds-luggages-from');
  var toEl = document.getElementById('tds-luggages-to');
  var deleteDlg = document.getElementById('tds-luggages-delete');
  var deleteIdEl = document.getElementById('tds-luggages-delete-id');
  var deleteLabelEl = document.getElementById('tds-luggages-delete-label');
  var deleteReasonEl = document.getElementById('tds-luggages-delete-reason');
  var deletePassEl = document.getElementById('tds-luggages-delete-password');
  var deleteConfirmBtn = document.getElementById('tds-luggages-delete-confirm');

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
  function storeBadge(store) {
    if (store === 'out') return 'tds-badge tds-badge-paid';
    if (store === 'half_out') return 'tds-badge tds-badge-warn';
    return 'tds-badge tds-badge-info';
  }
  function payBadge(badge) {
    if (badge === 'paid') return 'tds-badge tds-badge-paid';
    if (badge === 'due') return 'tds-badge tds-badge-due';
    if (badge === 'warn') return 'tds-badge tds-badge-warn';
    return 'tds-badge tds-badge-neutral';
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
    if (state.store) params.set('store', state.store);
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    if (routeEl && routeEl.value) params.set('route', routeEl.value);
    if (fromEl && fromEl.value) params.set('date_from', fromEl.value);
    if (toEl && toEl.value) params.set('date_to', toEl.value);
    return params;
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
      if (it.payment_status === 'Due payment') tr.classList.add('tds-row-due');
      else if (it.payment_status === 'To be Payed') tr.classList.add('tds-row-open');
      else if (it.payment_status === 'paid') tr.classList.add('tds-row-paid');

      tr.innerHTML =
        '<td class="tds-mono"><strong>' +
        esc(it.code) +
        '</strong></td>' +
        '<td><a class="' +
        storeBadge(it.store) +
        '" href="' +
        esc(it.view_url) +
        '" target="_blank" rel="noopener">' +
        esc(it.store_label) +
        '</a></td>' +
        '<td>' +
        esc(it.sender_name) +
        '</td>' +
        '<td>' +
        esc(it.receiver_name) +
        '</td>' +
        '<td>' +
        esc(it.date_label) +
        '</td>' +
        '<td>' +
        esc(it.weight) +
        '</td>' +
        '<td>' +
        esc(it.detail) +
        '</td>' +
        '<td>' +
        esc(it.quantity) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.to_be) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.paid) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.amount) +
        ' <span class="' +
        payBadge(it.payment_badge) +
        '" style="margin-left:0.35rem">' +
        esc(it.payment_label) +
        '</span></td>' +
        '<td><div class="tds-table-actions"></div></td>';
      tbody.appendChild(tr);
      var actions = tr.querySelector('.tds-table-actions');
      var printA = document.createElement('a');
      printA.className = 'tds-btn tds-btn-ghost tds-btn-sm';
      printA.href = it.print_url;
      printA.target = '_blank';
      printA.rel = 'noopener';
      printA.textContent = 'Print';
      actions.appendChild(printA);
      var editA = document.createElement('a');
      editA.className = 'tds-btn tds-btn-secondary tds-btn-sm';
      editA.href = it.edit_url;
      editA.target = '_blank';
      editA.rel = 'noopener';
      editA.textContent = 'Edit';
      actions.appendChild(editA);
      var delBtn = document.createElement('button');
      delBtn.type = 'button';
      delBtn.className = 'tds-btn tds-btn-secondary tds-btn-sm';
      delBtn.textContent = 'Delete';
      delBtn.addEventListener('click', function () {
        openDelete(it);
      });
      actions.appendChild(delBtn);
    });
  }

  function openDelete(it) {
    if (!deleteDlg || !it) return;
    showError('');
    if (deleteIdEl) deleteIdEl.value = String(it.id);
    if (deleteLabelEl) {
      deleteLabelEl.textContent = 'Delete ' + (it.code || 'parcel') + ' · ' + (it.sender_name || '');
    }
    if (deleteReasonEl) deleteReasonEl.value = '';
    if (deletePassEl) deletePassEl.value = '';
    if (typeof deleteDlg.showModal === 'function') deleteDlg.showModal();
    else deleteDlg.setAttribute('open', 'open');
  }

  function closeDelete() {
    if (!deleteDlg) return;
    if (typeof deleteDlg.close === 'function') deleteDlg.close();
    else deleteDlg.removeAttribute('open');
  }

  function confirmDelete() {
    var id = parseInt((deleteIdEl && deleteIdEl.value) || '0', 10);
    var reason = (deleteReasonEl && deleteReasonEl.value) || '';
    var password = (deletePassEl && deletePassEl.value) || '';
    if (!id) return;
    if (!String(reason).trim()) {
      showError('Enter a delete reason');
      return;
    }
    if (!password) {
      showError('Enter your password');
      return;
    }
    if (deleteConfirmBtn) deleteConfirmBtn.disabled = true;
    fetchJson(API + '/luggage_delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({ id: id, reason: reason, password: password }),
    })
      .then(function () {
        closeDelete();
        showOk('Luggage deleted');
        return loadList();
      })
      .catch(function (e) {
        showError(e.message || 'Could not delete luggage');
      })
      .then(function () {
        if (deleteConfirmBtn) deleteConfirmBtn.disabled = false;
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
      'Page ' + state.page + ' of ' + pages + ' · ' + state.total.toLocaleString() + ' parcels';
    var prev = document.getElementById('tds-luggages-prev');
    var next = document.getElementById('tds-luggages-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page >= pages;
  }

  function loadList() {
    showError('');
    setLoading(true);
    fetchJson(API + '/luggages.php?' + filterParams().toString())
      .then(function (body) {
        var data = body.data || {};
        state.total = Number(data.total || 0);
        if (body.meta && body.meta.page) state.page = Number(body.meta.page);
        renderRows(data.items || []);
        renderPager();
      })
      .catch(function (e) {
        showError(e.message || 'Could not load luggage');
        if (emptyEl) emptyEl.hidden = true;
        if (wrapEl) wrapEl.hidden = true;
      })
      .then(function () {
        setLoading(false);
      });
  }

  function loadFilters() {
    return fetchJson(API + '/luggages_filters.php').then(function (body) {
      var routes = (body.data && body.data.routes) || [];
      if (!routeEl) return;
      var keep = routeEl.value;
      routeEl.innerHTML = '<option value="">All my routes</option>';
      routes.forEach(function (name) {
        var o = document.createElement('option');
        o.value = name;
        o.textContent = name;
        routeEl.appendChild(o);
      });
      if (keep) routeEl.value = keep;
    });
  }

  document.querySelectorAll('#tds-store-chips .tds-chip').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('#tds-store-chips .tds-chip').forEach(function (b) {
        b.classList.remove('is-active');
      });
      btn.classList.add('is-active');
      state.store = btn.getAttribute('data-store') || '';
      state.page = 1;
      loadList();
    });
  });

  document.getElementById('tds-luggages-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-luggages-reset').addEventListener('click', function () {
    if (qEl) qEl.value = '';
    if (routeEl) routeEl.value = '';
    if (fromEl) fromEl.value = '';
    if (toEl) toEl.value = '';
    state.store = '';
    state.page = 1;
    document.querySelectorAll('#tds-store-chips .tds-chip').forEach(function (b) {
      b.classList.toggle('is-active', (b.getAttribute('data-store') || '') === '');
    });
    loadList();
  });
  document.getElementById('tds-luggages-prev').addEventListener('click', function () {
    if (state.page > 1) {
      state.page -= 1;
      loadList();
    }
  });
  document.getElementById('tds-luggages-next').addEventListener('click', function () {
    var pages = Math.max(1, Math.ceil(state.total / state.perPage));
    if (state.page < pages) {
      state.page += 1;
      loadList();
    }
  });
  if (qEl) {
    qEl.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        state.page = 1;
        loadList();
      }
    });
  }

  var deleteCloseBtn = document.getElementById('tds-luggages-delete-close');
  if (deleteCloseBtn) deleteCloseBtn.addEventListener('click', closeDelete);
  if (deleteConfirmBtn) deleteConfirmBtn.addEventListener('click', confirmDelete);

  // Default: show recent month window so first paint is useful on large DBs
  if (fromEl && !fromEl.value) {
    var d = new Date();
    d.setDate(d.getDate() - 30);
    fromEl.value = d.toISOString().slice(0, 10);
  }

  loadFilters()
    .then(loadList)
    .catch(function (e) {
      showError(e.message || 'Could not load filters');
      loadList();
    });
})();
