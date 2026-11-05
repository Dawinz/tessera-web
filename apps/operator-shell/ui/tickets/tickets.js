/**
 * T-P0-03 Book list — modern console UI (TDS + console API).
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-bookings-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var state = {
    page: 1,
    perPage: 50,
    total: 0,
    preset: 'all',
    items: [],
  };

  var errEl = document.getElementById('tds-bookings-error');
  var okEl = document.getElementById('tds-bookings-success');
  var loadingEl = document.getElementById('tds-bookings-loading');
  var emptyEl = document.getElementById('tds-bookings-empty');
  var wrapEl = document.getElementById('tds-bookings-wrap');
  var tbody = document.getElementById('tds-bookings-tbody');
  var pager = document.getElementById('tds-bookings-pager');
  var metaEl = document.getElementById('tds-bookings-meta');
  var qEl = document.getElementById('tds-bookings-q');
  var startEl = document.getElementById('tds-bookings-start');
  var endEl = document.getElementById('tds-bookings-end');
  var busEl = document.getElementById('tds-bookings-bus');
  var issuerEl = document.getElementById('tds-bookings-issuer');
  var fromEl = document.getElementById('tds-bookings-from');
  var toEl = document.getElementById('tds-bookings-to');
  var focEl = document.getElementById('tds-bookings-foc');
  var transitEl = document.getElementById('tds-bookings-transit');
  var detailDlg = document.getElementById('tds-booking-detail');
  var detailTitle = document.getElementById('tds-detail-title');
  var detailBody = document.getElementById('tds-detail-body');

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }

  function showOk(msg) {
    if (!okEl) return;
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
    if (msg) {
      setTimeout(function () {
        okEl.hidden = true;
      }, 3500);
    }
  }

  function setLoading(on) {
    if (loadingEl) loadingEl.hidden = !on;
    if (wrapEl) wrapEl.hidden = !!on;
    if (pager && on) pager.hidden = true;
  }

  function fetchJson(url, opts) {
    opts = opts || {};
    var headers = Object.assign({ Accept: 'application/json' }, opts.headers || {});
    return fetch(url, Object.assign({ credentials: 'same-origin', headers: headers }, opts)).then(
      function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body || body.ok === false) {
            var code = body && body.error && body.error.code ? body.error.code : 'error';
            var message =
              body && body.error && body.error.message ? body.error.message : 'Request failed';
            var err = new Error(message);
            err.code = code;
            err.status = res.status;
            throw err;
          }
          return body;
        });
      }
    );
  }

  function fillSelect(sel, items, placeholder) {
    if (!sel) return;
    var keep = sel.value;
    sel.innerHTML = '';
    var opt0 = document.createElement('option');
    opt0.value = '';
    opt0.textContent = placeholder;
    sel.appendChild(opt0);
    (items || []).forEach(function (v) {
      var o = document.createElement('option');
      o.value = v;
      o.textContent = v;
      sel.appendChild(o);
    });
    if (keep) sel.value = keep;
  }

  function money(n, currency) {
    var num = Number(n || 0);
    var formatted = num.toLocaleString(undefined, { maximumFractionDigits: 0 });
    return currency ? currency + ' ' + formatted : formatted;
  }

  function badgeClass(badge) {
    if (badge === 'paid') return 'tds-badge tds-badge-paid';
    if (badge === 'due') return 'tds-badge tds-badge-due';
    if (badge === 'warn') return 'tds-badge tds-badge-warn';
    if (badge === 'info') return 'tds-badge tds-badge-info';
    return 'tds-badge tds-badge-neutral';
  }

  function filterParams() {
    var params = new URLSearchParams();
    params.set('page', String(state.page));
    params.set('per_page', String(state.perPage));
    params.set('sort', 'id');
    params.set('order', 'desc');
    if (state.preset === 'due') {
      params.set('due', '1');
    } else if (state.preset && state.preset !== 'all') {
      params.set('status', state.preset);
    }
    var q = qEl ? qEl.value.trim() : '';
    if (q) params.set('q', q);
    if (startEl && startEl.value) params.set('start_route', startEl.value);
    if (endEl && endEl.value) params.set('end_route', endEl.value);
    if (busEl && busEl.value) params.set('bus', busEl.value);
    if (issuerEl && issuerEl.value) params.set('issuer', issuerEl.value);
    if (fromEl && fromEl.value) params.set('trip_date_from', fromEl.value);
    if (toEl && toEl.value) params.set('trip_date_to', toEl.value);
    if (focEl && focEl.checked) params.set('foc', '1');
    if (transitEl && transitEl.checked) params.set('transit', '1');
    return params;
  }

  function renderRows(items) {
    if (!tbody) return;
    tbody.innerHTML = '';
    state.items = items || [];
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
        '<td>' +
        esc(it.seat_no) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(String(it.id)) +
        '</td>' +
        '<td>' +
        esc(it.fullname) +
        '</td>' +
        '<td>' +
        esc(it.trip_date) +
        '</td>' +
        '<td>' +
        esc(it.bus) +
        '</td>' +
        '<td>' +
        esc(it.issuer) +
        '</td>' +
        '<td class="tds-mono">' +
        esc(money(it.cost, it.currency)) +
        '</td>' +
        '<td><span class="' +
        badgeClass(it.badge) +
        '">' +
        esc(it.status_label) +
        '</span></td>' +
        '<td><div class="tds-table-actions" data-id="' +
        it.id +
        '"></div></td>';
      tbody.appendChild(tr);
      var actions = tr.querySelector('.tds-table-actions');
      if (!actions) return;

      actions.appendChild(
        actionBtn('View', 'ghost', function () {
          openDetail(it);
        })
      );
      actions.appendChild(
        actionLink('Print', it.print_url, true)
      );
      if (it.can_complete_reserve) {
        actions.appendChild(
          actionBtn('Complete', 'primary', function () {
            completeReserve(it.id);
          })
        );
      }
      if (it.can_clear_due) {
        actions.appendChild(
          actionBtn('Clear due', 'secondary', function () {
            clearDue(it.id);
          })
        );
      }
      if (it.transfer_url) {
        actions.appendChild(actionLink('Transfer', it.transfer_url, false));
      }
      if (it.can_delete) {
        actions.appendChild(
          actionBtn('Delete', 'secondary', function () {
            deleteBooking(it);
          })
        );
      }
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

  function actionLink(label, href, blank) {
    var a = document.createElement('a');
    a.className = 'tds-btn tds-btn-secondary tds-btn-sm';
    a.href = href;
    a.textContent = label;
    if (blank) {
      a.target = '_blank';
      a.rel = 'noopener';
    }
    return a;
  }

  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function openDetail(it) {
    if (!detailDlg || !detailBody || !detailTitle) return;
    detailTitle.textContent = 'Ticket #' + it.id;
    detailBody.innerHTML =
      '<dl class="tds-stack" style="gap:var(--tds-space-2);margin:0">' +
      row('Passenger', it.fullname) +
      row('Phone', it.phone || '—') +
      row('Seat', it.seat_no) +
      row('Trip', it.trip_date) +
      row('Route', (it.start_route || '—') + ' → ' + (it.end_route || '—')) +
      row('Vehicle', it.bus || '—') +
      row('Seller', it.issuer || '—') +
      row('Cost', money(it.cost, it.currency)) +
      row('Status', it.status_label) +
      '</dl>' +
      '<div class="tds-row" style="margin-top:var(--tds-space-4)">' +
      '<a class="tds-btn tds-btn-primary tds-btn-sm" href="' +
      esc(it.print_url) +
      '" target="_blank" rel="noopener">Print ticket</a>' +
      '<a class="tds-btn tds-btn-secondary tds-btn-sm" href="' +
      esc(it.manifest_url) +
      '" target="_blank" rel="noopener">Manifest</a>' +
      '</div>';
    if (typeof detailDlg.showModal === 'function') detailDlg.showModal();
    else detailDlg.setAttribute('open', 'open');
  }

  function row(k, v) {
    return (
      '<div><dt class="tds-muted" style="font-size:var(--tds-fs-xs);margin:0">' +
      esc(k) +
      '</dt><dd style="margin:0.15rem 0 0;font-weight:600">' +
      esc(v) +
      '</dd></div>'
    );
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
    var prev = document.getElementById('tds-bookings-prev');
    var next = document.getElementById('tds-bookings-next');
    if (prev) prev.disabled = state.page <= 1;
    if (next) next.disabled = state.page * state.perPage >= total;
  }

  function loadList() {
    showError('');
    setLoading(true);
    if (emptyEl) emptyEl.hidden = true;
    return fetchJson(API + '/bookings.php?' + filterParams().toString())
      .then(function (body) {
        state.total = (body.data && body.data.total) || 0;
        if (body.meta && body.meta.page) state.page = body.meta.page;
        renderRows((body.data && body.data.items) || []);
        updatePager();
      })
      .catch(function (err) {
        showError(err.message || 'Could not load bookings');
        renderRows([]);
      })
      .finally(function () {
        setLoading(false);
        updatePager();
      });
  }

  function loadFilters() {
    return fetchJson(API + '/bookings_filters.php').then(function (body) {
      var d = body.data || {};
      fillSelect(startEl, d.starts, 'All starts');
      fillSelect(endEl, d.ends, 'All ends');
      fillSelect(busEl, d.buses, 'All vehicles');
      fillSelect(issuerEl, d.issuers, 'All sellers');
    });
  }

  function completeReserve(id) {
    if (!window.confirm('Complete this reserved booking?')) return;
    fetchJson(API + '/booking_complete_reserve.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({ id: id }),
    })
      .then(function () {
        showOk('Reservation completed');
        return loadList();
      })
      .catch(function (err) {
        showError(err.message || 'Could not complete reservation');
      });
  }

  function clearDue(id) {
    if (!window.confirm('Clear the due payment on this booking?')) return;
    fetchJson(API + '/booking_clear_due.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({ id: id }),
    })
      .then(function () {
        showOk('Due payment cleared');
        return loadList();
      })
      .catch(function (err) {
        showError(err.message || 'Could not clear due');
      });
  }

  function deleteBooking(it) {
    var reason = window.prompt(
      'Delete ticket #' + it.id + ' (seat ' + it.seat_no + ')? Enter a short reason:',
      ''
    );
    if (reason === null) return;
    reason = String(reason).trim();
    if (!reason) {
      showError('Add a short delete reason');
      return;
    }
    if (!window.confirm('Permanently remove this booking from the active list?')) return;
    fetchJson(API + '/booking_delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({ id: it.id, reason: reason }),
    })
      .then(function () {
        showOk('Booking #' + it.id + ' deleted');
        return loadList();
      })
      .catch(function (err) {
        showError(err.message || 'Could not delete booking');
      });
  }

  function setPreset(preset) {
    state.preset = preset;
    state.page = 1;
    document.querySelectorAll('#tds-status-chips .tds-chip').forEach(function (chip) {
      chip.classList.toggle('is-active', chip.getAttribute('data-preset') === preset);
    });
    loadList();
  }

  document.querySelectorAll('#tds-status-chips .tds-chip').forEach(function (chip) {
    chip.addEventListener('click', function () {
      setPreset(chip.getAttribute('data-preset') || 'all');
    });
  });

  var applyBtn = document.getElementById('tds-bookings-apply');
  if (applyBtn) {
    applyBtn.addEventListener('click', function () {
      state.page = 1;
      loadList();
    });
  }
  var resetBtn = document.getElementById('tds-bookings-reset');
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      if (qEl) qEl.value = '';
      if (startEl) startEl.value = '';
      if (endEl) endEl.value = '';
      if (busEl) busEl.value = '';
      if (issuerEl) issuerEl.value = '';
      if (fromEl) fromEl.value = '';
      if (toEl) toEl.value = '';
      if (focEl) focEl.checked = false;
      if (transitEl) transitEl.checked = false;
      setPreset('all');
    });
  }
  if (qEl) {
    qEl.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        state.page = 1;
        loadList();
      }
    });
  }
  var prevBtn = document.getElementById('tds-bookings-prev');
  var nextBtn = document.getElementById('tds-bookings-next');
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
      if (state.page * state.perPage < state.total) {
        state.page += 1;
        loadList();
      }
    });
  }
  var closeBtn = document.getElementById('tds-detail-close');
  if (closeBtn && detailDlg) {
    closeBtn.addEventListener('click', function () {
      if (typeof detailDlg.close === 'function') detailDlg.close();
      else detailDlg.removeAttribute('open');
    });
  }

  /* Deep-link: ?q=99342 or highlight after booking */
  try {
    var u = new URL(window.location.href);
    var qParam = u.searchParams.get('q');
    if (qParam && qEl) qEl.value = qParam;
  } catch (e) {}

  loadFilters()
    .catch(function () {
      /* filters optional */
    })
    .then(function () {
      return loadList();
    });
})();
