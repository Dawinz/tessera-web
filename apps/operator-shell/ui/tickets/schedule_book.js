/**
 * T-P0-02 Book trip — TDS seat chart + passenger form + console book API.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-book-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var routeDayId = root.getAttribute('data-route-day-id') || '';
  var tripDate = root.getAttribute('data-trip-date') || '';
  var routeId = root.getAttribute('data-route-id') || '';
  var params = new URLSearchParams(window.location.search);
  if (!routeDayId) routeDayId = params.get('route_day_id') || '';
  if (!tripDate) tripDate = params.get('trip_date') || '';
  if (!routeId) routeId = params.get('route_id') || '';

  var errEl = document.getElementById('tds-book-error');
  var okEl = document.getElementById('tds-book-success');
  var heroEl = document.getElementById('tds-book-hero');
  var chartEl = document.getElementById('tds-seat-chart');
  var summaryEl = document.getElementById('tds-seat-summary');
  var listEl = document.getElementById('tds-passenger-list');
  var emptyEl = document.getElementById('tds-passenger-empty');
  var totalEl = document.getElementById('tds-book-total');
  var submitBtn = document.getElementById('tds-book-submit');
  var classicLink = document.getElementById('tds-book-classic');

  var trip = null;
  var canManage = false;
  var baseFare = 0;
  var fare = 0;
  var currency = '';
  var subRoutes = [];
  var selectedSubRouteId = '';
  var selected = {}; // seat -> {firstname, lastname, phone, gender, foc, foc_reason, transit, transit_reason}
  var segmentEl = document.getElementById('tds-book-segment');
  var subRouteSelect = document.getElementById('tds-book-sub-route');

  if (classicLink) {
    var q = new URLSearchParams({
      route_day_id: routeDayId,
      trip_date: tripDate,
      route_id: routeId,
      ui_tickets_schedule_book: 'legacy',
    });
    classicLink.href = '/operator/app.php?module=tickets&page=schedule_book.php&' + q.toString();
  }

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

  function fetchJson(url, opts) {
    opts = opts || {};
    return fetch(url, Object.assign({ credentials: 'same-origin', headers: { Accept: 'application/json' } }, opts)).then(
      function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body || body.ok === false) {
            var message =
              (body && body.error && body.error.message) || 'Request failed';
            var err = new Error(message);
            err.code = body && body.error && body.error.code;
            err.status = res.status;
            err.body = body;
            throw err;
          }
          return body;
        });
      }
    );
  }

  function money(n) {
    try {
      return Number(n || 0).toLocaleString() + (currency ? ' ' + currency : '');
    } catch (e) {
      return String(n) + (currency ? ' ' + currency : '');
    }
  }

  function renderHero() {
    if (!heroEl || !trip) return;
    var manage = '';
    if (canManage) {
      var manageHref =
        '/operator/app.php?module=tickets&page=schedule_manage.php&route_day_id=' +
        encodeURIComponent(routeDayId) +
        '&trip_date=' +
        encodeURIComponent(tripDate) +
        '&route_id=' +
        encodeURIComponent(routeId || trip.route_id || '');
      manage =
        '<a class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-book-manage" href="' +
        manageHref +
        '">Manage trip template</a>';
    }
    heroEl.innerHTML =
      '<div class="tds-card-body">' +
      '<div class="tds-row" style="justify-content:space-between;align-items:flex-start">' +
      '<h2 class="tds-card-title" style="margin-bottom:0.5rem">' +
      escapeHtml(trip.route_name || 'Trip') +
      '</h2>' +
      manage +
      '</div>' +
      '<div class="tds-row tds-muted" style="font-size:var(--tds-fs-sm)">' +
      '<span>' +
      escapeHtml(trip.trip_date_display || trip.trip_date || '') +
      ' · ' +
      escapeHtml(trip.weekday || '') +
      '</span>' +
      '<span>' +
      escapeHtml(trip.bus || '') +
      '</span>' +
      '<span>' +
      escapeHtml(trip.departure || '—') +
      ' → ' +
      escapeHtml(trip.arrival || '—') +
      '</span>' +
      '<span>' +
      (trip.seats_booked || 0) +
      ' / ' +
      (trip.seats_total || 0) +
      ' seats</span>' +
      '<span>' +
      money(fare) +
      ' / seat</span>' +
      '</div></div>';
  }

  function escapeHtml(s) {
    return String(s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function seatClass(state, isSelected) {
    if (isSelected) return 'tds-seat tds-seat-selected';
    if (state === 'available') return 'tds-seat tds-seat-available';
    if (state === 'locked') return 'tds-seat tds-seat-blocked';
    return 'tds-seat tds-seat-taken';
  }

  function renderChart(seats) {
    if (!chartEl) return;
    var html = '';
    html +=
      '<div class="tds-seat-chart-legend">' +
      '<span class="tds-seat-chart-legend-item"><button type="button" class="tds-seat tds-seat-available" tabindex="-1">A1</button> Available</span>' +
      '<span class="tds-seat-chart-legend-item"><button type="button" class="tds-seat tds-seat-selected" tabindex="-1">A2</button> Selected</span>' +
      '<span class="tds-seat-chart-legend-item"><button type="button" class="tds-seat tds-seat-taken" tabindex="-1" disabled>A3</button> Taken</span>' +
      '<span class="tds-seat-chart-legend-item"><button type="button" class="tds-seat tds-seat-blocked" tabindex="-1" disabled>—</button> Locked</span>' +
      '</div>';

    if (seats.extras && seats.extras.length) {
      html += '<div class="tds-seat-grid" style="grid-template-columns:repeat(auto-fill,minmax(2.25rem,2.25rem));margin-bottom:0.75rem">';
      seats.extras.forEach(function (cell) {
        html += seatButton(cell);
      });
      html += '</div>';
    }

    (seats.rows || []).forEach(function (row) {
      html += '<div class="tds-row" style="margin-bottom:0.35rem;gap:0.35rem">';
      (row.cells || []).forEach(function (cell) {
        if (cell.type === 'aisle') {
          html += '<span style="width:1rem;display:inline-block" aria-hidden="true"></span>';
          return;
        }
        html += seatButton(cell);
      });
      html += '</div>';
    });

    chartEl.innerHTML = html;
    if (summaryEl && seats.summary) {
      summaryEl.textContent =
        seats.summary.available +
        ' free · ' +
        seats.summary.taken +
        ' taken · ' +
        seats.summary.locked +
        ' locked';
    }

    chartEl.querySelectorAll('[data-seat]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var seat = btn.getAttribute('data-seat');
        var state = btn.getAttribute('data-state');
        if (state !== 'available' && !selected[seat]) return;
        toggleSeat(seat);
      });
    });
  }

  function seatButton(cell) {
    var seat = cell.seat || '';
    var state = cell.state || 'taken';
    var isSelected = !!selected[seat];
    var disabled = state !== 'available' && !isSelected ? ' disabled' : '';
    return (
      '<button type="button" class="' +
      seatClass(state, isSelected) +
      '" data-seat="' +
      escapeHtml(seat) +
      '" data-state="' +
      escapeHtml(state) +
      '"' +
      disabled +
      '>' +
      escapeHtml(seat) +
      '</button>'
    );
  }

  function toggleSeat(seat) {
    if (selected[seat]) {
      delete selected[seat];
    } else {
      selected[seat] = {
        firstname: '',
        lastname: '',
        phone: '',
        gender: '',
        foc: false,
        foc_reason: '',
        transit: false,
        transit_reason: '',
      };
    }
    renderPassengers();
    // re-render chart selection state without full reload
    chartEl.querySelectorAll('[data-seat]').forEach(function (btn) {
      var s = btn.getAttribute('data-seat');
      var state = btn.getAttribute('data-state');
      btn.className = seatClass(state, !!selected[s]);
      if (state === 'available' || selected[s]) btn.disabled = false;
    });
  }

  function renderPassengers() {
    var seats = Object.keys(selected).sort();
    if (!seats.length) {
      if (emptyEl) emptyEl.hidden = false;
      if (listEl) {
        listEl.hidden = true;
        listEl.innerHTML = '';
      }
      if (submitBtn) submitBtn.disabled = true;
      if (totalEl) totalEl.textContent = 'Total: —';
      return;
    }
    if (emptyEl) emptyEl.hidden = true;
    if (listEl) listEl.hidden = false;
    var html = '';
    seats.forEach(function (seat) {
      var p = selected[seat];
      html +=
        '<div class="tds-card" style="box-shadow:none;border-color:var(--tds-line)" data-pax-seat="' +
        escapeHtml(seat) +
        '">' +
        '<div class="tds-card-body" style="padding:var(--tds-space-3)">' +
        '<div class="tds-row" style="justify-content:space-between;margin-bottom:0.5rem">' +
        '<strong>Seat ' +
        escapeHtml(seat) +
        '</strong>' +
        '<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" data-remove="' +
        escapeHtml(seat) +
        '">Remove</button></div>' +
        '<div class="tds-row">' +
        '<div class="tds-field" style="flex:1"><label class="tds-label">First name</label>' +
        '<input class="tds-input" data-field="firstname" data-seat="' +
        escapeHtml(seat) +
        '" value="' +
        escapeHtml(p.firstname) +
        '"></div>' +
        '<div class="tds-field" style="flex:1"><label class="tds-label">Last name</label>' +
        '<input class="tds-input" data-field="lastname" data-seat="' +
        escapeHtml(seat) +
        '" value="' +
        escapeHtml(p.lastname) +
        '"></div></div>' +
        '<div class="tds-row" style="margin-top:0.5rem">' +
        '<div class="tds-field" style="flex:1"><label class="tds-label">Phone</label>' +
        '<input class="tds-input" data-field="phone" data-seat="' +
        escapeHtml(seat) +
        '" value="' +
        escapeHtml(p.phone) +
        '" inputmode="tel"></div>' +
        '<div class="tds-field" style="flex:1"><label class="tds-label">Gender</label>' +
        '<select class="tds-select" data-field="gender" data-seat="' +
        escapeHtml(seat) +
        '">' +
        '<option value="">—</option>' +
        '<option value="male"' +
        (p.gender === 'male' ? ' selected' : '') +
        '>Male</option>' +
        '<option value="female"' +
        (p.gender === 'female' ? ' selected' : '') +
        '>Female</option>' +
        '</select></div></div>' +
        '<div class="tds-row" style="margin-top:0.75rem;gap:var(--tds-space-4);flex-wrap:wrap">' +
        '<label class="tds-muted" style="display:flex;align-items:center;gap:0.4rem;font-size:var(--tds-fs-sm)">' +
        '<input type="checkbox" data-flag="foc" data-seat="' +
        escapeHtml(seat) +
        '"' +
        (p.foc ? ' checked' : '') +
        '> Zero-fare (FOC)</label>' +
        '<label class="tds-muted" style="display:flex;align-items:center;gap:0.4rem;font-size:var(--tds-fs-sm)">' +
        '<input type="checkbox" data-flag="transit" data-seat="' +
        escapeHtml(seat) +
        '"' +
        (p.transit ? ' checked' : '') +
        '> Transit</label></div>' +
        '<div class="tds-field" data-reason-wrap="foc" data-seat="' +
        escapeHtml(seat) +
        '" style="margin-top:0.5rem"' +
        (p.foc ? '' : ' hidden') +
        '>' +
        '<label class="tds-label">FOC note</label>' +
        '<input class="tds-input" data-field="foc_reason" data-seat="' +
        escapeHtml(seat) +
        '" value="' +
        escapeHtml(p.foc_reason || '') +
        '" placeholder="Why is this seat free?"></div>' +
        '<div class="tds-field" data-reason-wrap="transit" data-seat="' +
        escapeHtml(seat) +
        '" style="margin-top:0.5rem"' +
        (p.transit ? '' : ' hidden') +
        '>' +
        '<label class="tds-label">Transit note</label>' +
        '<input class="tds-input" data-field="transit_reason" data-seat="' +
        escapeHtml(seat) +
        '" value="' +
        escapeHtml(p.transit_reason || '') +
        '" placeholder="Transit details"></div>' +
        '</div></div>';
    });
    listEl.innerHTML = html;
    listEl.querySelectorAll('[data-field]').forEach(function (el) {
      el.addEventListener('input', function () {
        var seat = el.getAttribute('data-seat');
        var field = el.getAttribute('data-field');
        if (selected[seat]) selected[seat][field] = el.value;
        updateTotal();
      });
      el.addEventListener('change', function () {
        var seat = el.getAttribute('data-seat');
        var field = el.getAttribute('data-field');
        if (selected[seat]) selected[seat][field] = el.value;
        updateTotal();
      });
    });
    listEl.querySelectorAll('[data-flag]').forEach(function (el) {
      el.addEventListener('change', function () {
        var seat = el.getAttribute('data-seat');
        var flag = el.getAttribute('data-flag');
        if (!selected[seat]) return;
        selected[seat][flag] = !!el.checked;
        var wrap = listEl.querySelector(
          '[data-reason-wrap="' + flag + '"][data-seat="' + seat + '"]'
        );
        if (wrap) wrap.hidden = !el.checked;
        updateTotal();
      });
    });
    listEl.querySelectorAll('[data-remove]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        toggleSeat(btn.getAttribute('data-remove'));
      });
    });
    updateTotal();
  }

  function seatFare(p) {
    if (p && (p.foc || p.transit)) return 0;
    return fare;
  }

  function updateTotal() {
    var seats = Object.keys(selected);
    var n = seats.length;
    var total = 0;
    seats.forEach(function (seat) {
      total += seatFare(selected[seat]);
    });
    if (totalEl) {
      totalEl.textContent = n ? 'Total: ' + money(total) : 'Total: —';
    }
    var ready =
      n > 0 &&
      seats.every(function (seat) {
        var p = selected[seat];
        if (!p.firstname || !p.phone) return false;
        if (p.foc && !String(p.foc_reason || '').trim()) return false;
        if (p.transit && !String(p.transit_reason || '').trim()) return false;
        return true;
      });
    if (submitBtn) submitBtn.disabled = !ready;
  }

  function applySegmentFare() {
    selectedSubRouteId = (subRouteSelect && subRouteSelect.value) || '';
    if (!selectedSubRouteId) {
      fare = baseFare;
    } else {
      var match = null;
      for (var i = 0; i < subRoutes.length; i++) {
        if (String(subRoutes[i].id) === String(selectedSubRouteId)) {
          match = subRoutes[i];
          break;
        }
      }
      fare = match ? Number(match.fare_price || 0) : baseFare;
      if (match && match.currency_symbol) currency = match.currency_symbol;
    }
    renderHero();
    updateTotal();
  }

  function renderSubRoutes() {
    if (!segmentEl || !subRouteSelect) return;
    if (!subRoutes.length) {
      segmentEl.hidden = true;
      selectedSubRouteId = '';
      return;
    }
    segmentEl.hidden = false;
    var html = '<option value="">Full route (main fare) — ' + money(baseFare) + '</option>';
    subRoutes.forEach(function (seg) {
      html +=
        '<option value="' +
        escapeHtml(seg.id) +
        '"' +
        (String(selectedSubRouteId) === String(seg.id) ? ' selected' : '') +
        '>' +
        escapeHtml(seg.label || seg.start_route + ' → ' + seg.end_route) +
        ' — ' +
        money(seg.fare_price) +
        '</option>';
    });
    subRouteSelect.innerHTML = html;
  }

  function boot() {
    if (!routeDayId || !tripDate) {
      showError('Missing trip. Open a departure from Schedule.');
      return;
    }
    var q = new URLSearchParams({
      route_day_id: routeDayId,
      trip_date: tripDate,
      route_id: routeId,
    });
    fetchJson(API + '/seats.php?' + q.toString())
      .then(function (body) {
        trip = (body.data && body.data.trip) || null;
        canManage = !!(body.data && body.data.can_manage_template);
        var seats = (body.data && body.data.seats) || {};
        subRoutes = (body.data && body.data.sub_routes) || [];
        baseFare =
          seats.fare_price != null ? seats.fare_price : (trip && trip.fare_price) || 0;
        fare = baseFare;
        currency = seats.currency_symbol || (trip && trip.currency_symbol) || '';
        renderSubRoutes();
        applySegmentFare();
        renderHero();
        renderChart(seats);
        renderPassengers();
        showError('');
      })
      .catch(function (err) {
        showError(err.message || 'Could not load seats.');
        if (err.code === 'unauthorized') {
          window.location.href =
            '/login/?next=' +
            encodeURIComponent(window.location.pathname + window.location.search);
        }
      });
  }

  if (submitBtn) {
    submitBtn.addEventListener('click', function () {
      var passengers = Object.keys(selected).map(function (seat) {
        var p = selected[seat];
        return {
          seat: seat,
          firstname: p.firstname,
          lastname: p.lastname,
          phone: p.phone,
          gender: p.gender,
          foc: !!p.foc,
          foc_reason: p.foc_reason || '',
          transit: !!p.transit,
          transit_reason: p.transit_reason || '',
        };
      });
      submitBtn.disabled = true;
      showOk('');
      showError('');
      fetchJson(API + '/book.php', {
        method: 'POST',
        headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({
          route_day_id: routeDayId,
          trip_date: tripDate,
          route_id: routeId,
          sub_route_id: selectedSubRouteId || '',
          passengers: passengers,
        }),
      })
        .then(function (body) {
          var n = (body.data && body.data.bookings && body.data.bookings.length) || 0;
          showOk('Booked ' + n + ' seat' + (n === 1 ? '' : 's') + '. Print id: ' + ((body.data && body.data.print_id) || '—'));
          selected = {};
          boot();
        })
        .catch(function (err) {
          showError(err.message || 'Booking failed.');
          submitBtn.disabled = false;
        });
    });
  }

  if (subRouteSelect) {
    subRouteSelect.addEventListener('change', applySegmentFare);
  }

  boot();
})();
