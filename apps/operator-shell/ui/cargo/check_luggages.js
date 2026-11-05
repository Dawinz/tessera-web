/**
 * C-P0-07 Check / load luggages — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-load-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/cargo/api/console';
  var state = {
    page: 1,
    perPage: 50,
    total: 0,
    bus: root.getAttribute('data-bus') || '',
    date: root.getAttribute('data-date') || '',
    parcel: null,
    locked: false,
    manifestId: 0,
    remark: '',
  };

  var errEl = document.getElementById('tds-load-error');
  var okEl = document.getElementById('tds-load-success');
  var warnEl = document.getElementById('tds-load-warn');
  var searchPanel = document.getElementById('tds-load-search-panel');
  var confirmPanel = document.getElementById('tds-load-confirm-panel');
  var codeInput = document.getElementById('tds-load-code-input');
  var searchBtn = document.getElementById('tds-load-search');
  var backBtn = document.getElementById('tds-load-back');
  var commitBtn = document.getElementById('tds-load-commit');
  var qtyEl = document.getElementById('tds-load-qty');
  var tobeEl = document.getElementById('tds-load-tobe');
  var paidEl = document.getElementById('tds-load-paid');
  var tobeWrap = document.getElementById('tds-load-tobe-wrap');
  var paidWrap = document.getElementById('tds-load-paid-wrap');
  var qEl = document.getElementById('tds-load-q');
  var loadingEl = document.getElementById('tds-load-loading');
  var emptyEl = document.getElementById('tds-load-empty');
  var wrapEl = document.getElementById('tds-load-wrap');
  var tbody = document.getElementById('tds-load-tbody');
  var pager = document.getElementById('tds-load-pager');
  var metaEl = document.getElementById('tds-load-meta');

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
  function parseNum(v) {
    var s = String(v == null ? '' : v).replace(/[^0-9.\-]/g, '');
    if (!s) return 0;
    var n = Number(s);
    return isFinite(n) ? n : 0;
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

  function setSummary(summary) {
    var paid = document.getElementById('tds-load-sum-paid');
    var tobe = document.getElementById('tds-load-sum-tobe');
    var amount = document.getElementById('tds-load-sum-amount');
    if (!summary) return;
    if (paid) paid.textContent = money(summary.paid);
    if (tobe) tobe.textContent = money(summary.to_be);
    if (amount) amount.textContent = money(summary.amount);
  }

  function setContext(ctx) {
    if (!ctx) return;
    state.bus = ctx.vehicle || state.bus;
    state.date = ctx.date || state.date;
    state.locked = !!ctx.locked;
    state.manifestId = Number(ctx.id || 0);
    state.remark = ctx.remark || '';
    var map = {
      'tds-load-code': ctx.code,
      'tds-load-vehicle': ctx.vehicle,
      'tds-load-date': ctx.date_label || ctx.date,
      'tds-load-driver': ctx.driver,
      'tds-load-conductor': ctx.conductor,
      'tds-load-route': ctx.route,
    };
    Object.keys(map).forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.textContent = map[id] || '—';
    });
    var lockedEl = document.getElementById('tds-load-locked');
    if (lockedEl) lockedEl.hidden = !state.locked;
    var viewMore = document.getElementById('tds-load-view-more');
    if (viewMore && ctx.view_url) viewMore.href = ctx.view_url;
    var classic = document.getElementById('tds-load-classic');
    if (classic && ctx.load_path) {
      var qs = ctx.load_path.split('?')[1] || '';
      classic.href =
        '/operator/app.php?module=cargo&page=check_luggages.php&ui_cargo_check_luggages=legacy' +
        (qs ? '&' + qs : '');
    }
    if (searchBtn) searchBtn.disabled = state.locked;
    if (commitBtn) commitBtn.disabled = state.locked;
  }

  function removeLine(it) {
    if (!it || !it.id) return;
    if (state.locked) {
      showError('Manifest is locked');
      return;
    }
    if (!window.confirm('Remove ' + (it.code || 'this line') + ' from the load?')) return;
    fetchJson(API + '/load_remove.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: it.id }),
    })
      .then(function () {
        showOk('Removed from load');
        return loadContext();
      })
      .catch(function (err) {
        showError(err.message || 'Could not remove line');
      });
  }

  function openRemark() {
    var dlg = document.getElementById('tds-load-remark');
    var textEl = document.getElementById('tds-load-remark-text');
    var labelEl = document.getElementById('tds-load-remark-label');
    if (!dlg) return;
    if (!state.manifestId) {
      showError('No manifest loaded');
      return;
    }
    if (labelEl) {
      labelEl.textContent = (state.bus || 'Manifest') + (state.date ? ' · ' + state.date : '');
    }
    if (textEl) textEl.value = state.remark || '';
    try {
      if (typeof dlg.showModal === 'function') dlg.showModal();
      else dlg.setAttribute('open', 'open');
    } catch (err) {
      dlg.setAttribute('open', 'open');
    }
  }

  function closeRemark() {
    var dlg = document.getElementById('tds-load-remark');
    if (!dlg) return;
    if (typeof dlg.close === 'function') dlg.close();
    else dlg.removeAttribute('open');
  }

  function saveRemark() {
    var textEl = document.getElementById('tds-load-remark-text');
    var text = (textEl && textEl.value) || '';
    if (!state.manifestId) return;
    fetchJson(API + '/manifest_remark.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: state.manifestId, remark_text: text }),
    })
      .then(function () {
        state.remark = text;
        closeRemark();
        showOk('Remark saved');
      })
      .catch(function (err) {
        showError(err.message || 'Could not save remark');
      });
  }

  function showSearch() {
    state.parcel = null;
    if (searchPanel) searchPanel.hidden = false;
    if (confirmPanel) confirmPanel.hidden = true;
    if (codeInput) {
      codeInput.focus();
      codeInput.select();
    }
  }

  function badgeClass(badge) {
    if (badge === 'paid') return 'tds-badge tds-badge-paid';
    if (badge === 'warn') return 'tds-badge tds-badge-warn';
    if (badge === 'due') return 'tds-badge tds-badge-due';
    return 'tds-badge tds-badge-neutral';
  }

  function showConfirm(parcel) {
    state.parcel = parcel;
    if (searchPanel) searchPanel.hidden = true;
    if (confirmPanel) confirmPanel.hidden = false;
    var codeEl = document.getElementById('tds-load-parcel-code');
    var statusEl = document.getElementById('tds-load-parcel-status');
    var routeEl = document.getElementById('tds-load-parcel-route');
    var qtyLabel = document.getElementById('tds-load-parcel-qty');
    var tobeLabel = document.getElementById('tds-load-parcel-tobe');
    var paidLabel = document.getElementById('tds-load-parcel-paid');
    var amountLabel = document.getElementById('tds-load-parcel-amount');
    var storeEl = document.getElementById('tds-load-qty-store');
    if (codeEl) codeEl.textContent = parcel.code || '—';
    if (statusEl) {
      statusEl.className = badgeClass(parcel.status_badge);
      statusEl.textContent = parcel.status_label || parcel.status || '—';
    }
    if (routeEl) {
      routeEl.textContent =
        (parcel.receiver ? parcel.receiver + ' · ' : '') + (parcel.route || '');
    }
    if (qtyLabel) qtyLabel.textContent = String(parcel.qty || 0);
    if (tobeLabel) tobeLabel.textContent = money(parcel.to_be);
    if (paidLabel) paidLabel.textContent = money(parcel.paid);
    if (amountLabel) amountLabel.textContent = money(parcel.amount);
    if (storeEl) storeEl.textContent = String(parcel.qty_in_store || 0);
    if (qtyEl) qtyEl.value = String(parcel.qty_in_store || 0);
    if (tobeEl) tobeEl.value = money(parcel.to_be_remain);
    if (paidEl) paidEl.value = money(parcel.paid_remain);

    if (tobeWrap) tobeWrap.hidden = parcel.status === 'paid';
    if (paidWrap) paidWrap.hidden = parcel.status === 'To be Payed';

    showWarn(parcel.partial ? 'Partially loaded — some pieces are still in store.' : '');
    if (qtyEl) qtyEl.focus();
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
        '</strong></td>' +
        '<td class="tds-mono">' +
        esc(it.qty) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.to_be) +
        '</td>' +
        '<td class="tds-mono">' +
        money(it.paid) +
        '</td>' +
        '<td><span class="' +
        badgeClass(it.status_badge) +
        '">' +
        esc(it.status_label || it.status) +
        '</span></td>' +
        '<td><div class="tds-table-actions"></div></td>';
      tbody.appendChild(tr);
      var actions = tr.querySelector('.tds-table-actions');
      if (it.can_remove) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'tds-btn tds-btn-secondary tds-btn-sm';
        btn.textContent = 'Remove';
        btn.addEventListener('click', function () {
          removeLine(it);
        });
        actions.appendChild(btn);
      }
    });
  }

  function loadItems() {
    if (!state.bus || !state.date) return Promise.resolve();
    if (loadingEl) loadingEl.hidden = false;
    if (wrapEl) wrapEl.hidden = true;
    if (emptyEl) emptyEl.hidden = true;
    if (pager) pager.hidden = true;
    var params = new URLSearchParams();
    params.set('bus', state.bus);
    params.set('date', state.date);
    params.set('page', String(state.page));
    params.set('per_page', String(state.perPage));
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    return fetchJson(API + '/load_items.php?' + params.toString())
      .then(function (body) {
        if (loadingEl) loadingEl.hidden = true;
        applyItemsPayload(body.data || {});
      })
      .catch(function (err) {
        if (loadingEl) loadingEl.hidden = true;
        showError(err.message || 'Failed to load lines');
      });
  }

  function applyItemsPayload(data) {
    data = data || {};
    state.total = data.total || 0;
    setSummary(data.summary);
    renderRows(data.items || []);
    if (metaEl) {
      var from = state.total === 0 ? 0 : (state.page - 1) * state.perPage + 1;
      var to = Math.min(state.page * state.perPage, state.total);
      metaEl.textContent = from + '–' + to + ' of ' + state.total;
    }
    if (pager) {
      pager.hidden = state.total <= state.perPage;
      var prevBtn = document.getElementById('tds-load-prev');
      var nextBtn = document.getElementById('tds-load-next');
      if (prevBtn) prevBtn.disabled = state.page <= 1;
      if (nextBtn) nextBtn.disabled = state.page * state.perPage >= state.total;
    }
  }

  function loadContext() {
    var params = new URLSearchParams();
    if (state.bus) params.set('bus', state.bus);
    if (state.date) params.set('date', state.date);
    params.set('page', String(state.page));
    params.set('per_page', String(state.perPage));
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    if (loadingEl) loadingEl.hidden = false;
    return fetchJson(API + '/load_context.php?' + params.toString()).then(function (body) {
      if (loadingEl) loadingEl.hidden = true;
      var data = body.data || {};
      setContext(data.context || null);
      applyItemsPayload(data);
      showError('');
    });
  }

  function doSearch() {
    showError('');
    showOk('');
    showWarn('');
    var code = codeInput ? codeInput.value.trim() : '';
    if (!code) {
      showError('Enter a luggage code');
      if (codeInput) codeInput.focus();
      return;
    }
    if (state.locked) {
      showError('This manifest is locked');
      return;
    }
    if (searchBtn) searchBtn.disabled = true;
    var params = new URLSearchParams({
      code: code,
      bus: state.bus,
      date: state.date,
    });
    fetchJson(API + '/load_lookup.php?' + params.toString())
      .then(function (body) {
        if (searchBtn) searchBtn.disabled = state.locked;
        showConfirm((body.data && body.data.parcel) || null);
      })
      .catch(function (err) {
        if (searchBtn) searchBtn.disabled = state.locked;
        showError(err.message || 'Search failed');
      });
  }

  function doCommit() {
    showError('');
    showOk('');
    if (!state.parcel) return;
    if (state.locked) {
      showError('This manifest is locked');
      return;
    }
    if (commitBtn) commitBtn.disabled = true;
    fetchJson(API + '/load_item.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({
        code: state.parcel.code,
        vehicle: state.bus,
        date: state.date,
        qty: parseNum(qtyEl && qtyEl.value),
        to_be: parseNum(tobeEl && tobeEl.value),
        paid: parseNum(paidEl && paidEl.value),
      }),
    })
      .then(function () {
        if (commitBtn) commitBtn.disabled = state.locked;
        showOk((state.parcel.code || '') + ' added to manifest');
        if (codeInput) codeInput.value = '';
        showSearch();
        return loadItems();
      })
      .catch(function (err) {
        if (commitBtn) commitBtn.disabled = state.locked;
        showError(err.message || 'Load failed');
      });
  }

  if (searchBtn) searchBtn.addEventListener('click', doSearch);
  if (backBtn)
    backBtn.addEventListener('click', function () {
      showWarn('');
      showSearch();
    });
  if (commitBtn) commitBtn.addEventListener('click', doCommit);
  if (codeInput) {
    codeInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        doSearch();
      }
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Enter') return;
    if (confirmPanel && !confirmPanel.hidden) {
      e.preventDefault();
      doCommit();
    }
  });
  if (qEl) {
    var t = null;
    qEl.addEventListener('input', function () {
      clearTimeout(t);
      t = setTimeout(function () {
        state.page = 1;
        loadItems();
      }, 250);
    });
  }
  var prev = document.getElementById('tds-load-prev');
  var next = document.getElementById('tds-load-next');
  if (prev)
    prev.addEventListener('click', function () {
      if (state.page > 1) {
        state.page -= 1;
        loadItems();
      }
    });
  if (next)
    next.addEventListener('click', function () {
      if (state.page * state.perPage < state.total) {
        state.page += 1;
        loadItems();
      }
    });

  var remarkOpen = document.getElementById('tds-load-remark-open');
  var remarkCloseBtn = document.getElementById('tds-load-remark-close');
  var remarkSaveBtn = document.getElementById('tds-load-remark-save');
  if (remarkOpen) remarkOpen.addEventListener('click', openRemark);
  if (remarkCloseBtn) remarkCloseBtn.addEventListener('click', closeRemark);
  if (remarkSaveBtn) remarkSaveBtn.addEventListener('click', saveRemark);

  showError('');
  loadContext().catch(function (err) {
    if (loadingEl) loadingEl.hidden = true;
    showError(err.message || 'Failed to load manifest context');
  });
})();
