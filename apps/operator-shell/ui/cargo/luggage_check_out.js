/**
 * C-P0-05 Luggage check-out — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-checkout-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/cargo/api/console';
  var state = { page: 1, perPage: 50, total: 0, checked: '' };

  var errEl = document.getElementById('tds-checkout-error');
  var okEl = document.getElementById('tds-checkout-success');
  var loadingEl = document.getElementById('tds-checkout-loading');
  var emptyEl = document.getElementById('tds-checkout-empty');
  var wrapEl = document.getElementById('tds-checkout-wrap');
  var tbody = document.getElementById('tds-checkout-tbody');
  var pager = document.getElementById('tds-checkout-pager');
  var metaEl = document.getElementById('tds-checkout-meta');
  var qEl = document.getElementById('tds-checkout-q');
  var routeEl = document.getElementById('tds-checkout-route');

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
    if (state.checked) params.set('checked', state.checked);
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    if (routeEl && routeEl.value) params.set('route', routeEl.value);
    return params;
  }

  function toggleCheckout(id, currentlyOut) {
    showError('');
    showOk('');
    if (currentlyOut && !window.confirm('Uncheck this luggage?')) return;
    fetchJson(API + '/checkout_toggle.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id }),
    })
      .then(function (body) {
        var stateOut = body.data && body.data.state;
        showOk(stateOut === 'out' ? 'Parcel checked out.' : 'Checkout cleared.');
        loadList();
      })
      .catch(function (e) {
        showError(e.message || 'Checkout failed');
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
      var btnLabel = it.checked ? 'Uncheck' : 'Check out';
      var btnClass = it.checked ? 'tds-btn tds-btn-secondary tds-btn-sm' : 'tds-btn tds-btn-primary tds-btn-sm';
      tr.innerHTML =
        '<td class="tds-mono"><strong>' +
        esc(it.code) +
        '</strong></td>' +
        '<td>' +
        esc(it.owner) +
        '</td>' +
        '<td>' +
        esc(it.weight) +
        '</td>' +
        '<td>' +
        esc(it.detail) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.to_be) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.paid) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(it.vehicle) +
        '</td>' +
        '<td>' +
        esc(it.date_label) +
        '</td>' +
        '<td>' +
        esc(it.quantity_left) +
        '</td>' +
        '<td><span class="' +
        (it.checked ? 'tds-badge tds-badge-paid' : payBadge(it.payment_badge)) +
        '">' +
        esc(it.checked ? 'Out' : it.payment_label) +
        '</span></td>' +
        '<td><div class="tds-table-actions">' +
        '<button type="button" class="' +
        btnClass +
        ' tds-checkout-btn">' +
        btnLabel +
        '</button>' +
        '<a class="tds-btn tds-btn-ghost tds-btn-sm" href="' +
        esc(it.print_url) +
        '" target="_blank" rel="noopener">Print</a>' +
        '</div></td>';
      tbody.appendChild(tr);
      var btn = tr.querySelector('.tds-checkout-btn');
      if (btn) {
        btn.addEventListener('click', function () {
          toggleCheckout(it.id, !!it.checked);
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
      'Page ' + state.page + ' of ' + pages + ' · ' + state.total.toLocaleString() + ' parcels';
    var prev = document.getElementById('tds-checkout-prev');
    var next = document.getElementById('tds-checkout-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page >= pages;
  }

  function loadList() {
    showError('');
    setLoading(true);
    fetchJson(API + '/checkout.php?' + filterParams().toString())
      .then(function (body) {
        var data = body.data || {};
        state.total = Number(data.total || 0);
        if (body.meta && body.meta.page) state.page = Number(body.meta.page);
        renderRows(data.items || []);
        renderPager();
      })
      .catch(function (e) {
        showError(e.message || 'Could not load checkout list');
        if (emptyEl) emptyEl.hidden = true;
        if (wrapEl) wrapEl.hidden = true;
      })
      .then(function () {
        setLoading(false);
      });
  }

  function loadFilters() {
    return fetchJson(API + '/checkout_filters.php').then(function (body) {
      var routes = (body.data && body.data.routes) || [];
      if (!routeEl) return;
      var keep = routeEl.value;
      routeEl.innerHTML = '<option value="">All routes</option>';
      routes.forEach(function (name) {
        var o = document.createElement('option');
        o.value = name;
        o.textContent = name;
        routeEl.appendChild(o);
      });
      if (keep) routeEl.value = keep;
      else if (routes.length === 1) routeEl.value = routes[0];
    });
  }

  document.querySelectorAll('#tds-checkout-chips .tds-chip').forEach(function (btn) {
    btn.addEventListener('click', function () {
      document.querySelectorAll('#tds-checkout-chips .tds-chip').forEach(function (b) {
        b.classList.remove('is-active');
      });
      btn.classList.add('is-active');
      state.checked = btn.getAttribute('data-checked') || '';
      state.page = 1;
      loadList();
    });
  });

  document.getElementById('tds-checkout-apply').addEventListener('click', function () {
    state.page = 1;
    loadList();
  });
  document.getElementById('tds-checkout-reset').addEventListener('click', function () {
    if (qEl) qEl.value = '';
    if (routeEl) routeEl.value = '';
    state.checked = '';
    state.page = 1;
    document.querySelectorAll('#tds-checkout-chips .tds-chip').forEach(function (b) {
      b.classList.toggle('is-active', (b.getAttribute('data-checked') || '') === '');
    });
    loadList();
  });
  document.getElementById('tds-checkout-prev').addEventListener('click', function () {
    if (state.page > 1) {
      state.page -= 1;
      loadList();
    }
  });
  document.getElementById('tds-checkout-next').addEventListener('click', function () {
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

  loadFilters()
    .then(loadList)
    .catch(function (e) {
      showError(e.message || 'Could not load filters');
      loadList();
    });
})();
