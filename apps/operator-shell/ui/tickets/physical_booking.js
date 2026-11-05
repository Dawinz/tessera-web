(function () {
  var root = document.getElementById('tds-tk-physical');
  if (!root) return;
  var loading = document.getElementById('tds-tk-physical-loading');
  var empty = document.getElementById('tds-tk-physical-empty');
  var errorEl = document.getElementById('tds-tk-physical-error');
  var sheet = document.getElementById('tds-tk-physical-sheet');
  var printBtn = document.getElementById('tds-tk-physical-print');
  var state = { trip: null, seats: [], picked: {}, amount: '', paid: '' };

  function esc(value) {
    return String(value == null ? '' : value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function showError(message) {
    if (loading) loading.hidden = true;
    if (errorEl) {
      errorEl.hidden = false;
      errorEl.textContent = message;
    }
  }

  function selectedSeats() {
    return Object.keys(state.picked).filter(function (key) {
      return state.picked[key];
    });
  }

  function paint() {
    if (!sheet || !state.trip) return;
    var seats = selectedSeats();
    var amount = Number(state.amount || 0);
    var paid = Number(state.paid || 0);
    var balance = Math.max(0, amount - paid);
    var seatButtons = (state.seats || [])
      .map(function (seat) {
        var on = !!state.picked[seat.no];
        var disabled = seat.taken ? ' disabled' : '';
        var label = seat.taken ? 'Taken' : on ? 'Selected' : 'Free';
        return '<button type="button" class="tds-btn tds-btn-sm ' + (on ? 'tds-btn-primary' : 'tds-btn-secondary') + '" data-seat="' + esc(seat.no) + '"' + disabled + '>' + esc(seat.no) + ' ' + label + '</button>';
      })
      .join('');
    sheet.hidden = false;
    if (empty) empty.hidden = true;
    sheet.innerHTML =
      '<h2 class="tds-section-title">Hire sheet</h2>' +
      '<div class="tds-kpi-grid" style="margin:var(--tds-space-3) 0">' +
      '<div class="tds-kpi"><span class="tds-kpi-label">Vehicle</span><strong class="tds-kpi-value">' + esc(state.trip.bus) + '</strong></div>' +
      '<div class="tds-kpi"><span class="tds-kpi-label">Route</span><strong class="tds-kpi-value">' + esc(state.trip.route) + '</strong></div>' +
      '<div class="tds-kpi"><span class="tds-kpi-label">Date</span><strong class="tds-kpi-value">' + esc(state.trip.date || 'Set on print') + '</strong></div>' +
      '<div class="tds-kpi"><span class="tds-kpi-label">Seats</span><strong class="tds-kpi-value">' + esc(seats.join(', ') || '—') + '</strong></div>' +
      '<div class="tds-kpi"><span class="tds-kpi-label">Balance</span><strong class="tds-kpi-value">' + esc(String(balance)) + '</strong></div>' +
      '</div>' +
      '<div class="tds-row" id="tds-tk-physical-seats" style="flex-wrap:wrap;gap:var(--tds-space-2);margin-bottom:var(--tds-space-4)">' + seatButtons + '</div>' +
      '<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap">' +
      '<div class="tds-field"><label class="tds-label" for="tds-tk-physical-amount">Hire amount</label><input class="tds-input" id="tds-tk-physical-amount" type="number" min="0" value="' + esc(state.amount) + '"></div>' +
      '<div class="tds-field"><label class="tds-label" for="tds-tk-physical-paid">Paid</label><input class="tds-input" id="tds-tk-physical-paid" type="number" min="0" value="' + esc(state.paid) + '"></div>' +
      '<div class="tds-field"><label class="tds-label" for="tds-tk-physical-name">Passenger</label><input class="tds-input" id="tds-tk-physical-name" type="text"></div>' +
      '</div>';
    sheet.querySelectorAll('[data-seat]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var no = btn.getAttribute('data-seat');
        state.picked[no] = !state.picked[no];
        var amountInput = document.getElementById('tds-tk-physical-amount');
        var paidInput = document.getElementById('tds-tk-physical-paid');
        var nameInput = document.getElementById('tds-tk-physical-name');
        if (amountInput) state.amount = amountInput.value;
        if (paidInput) state.paid = paidInput.value;
        paint();
        if (nameInput) {
          var again = document.getElementById('tds-tk-physical-name');
          if (again) again.value = nameInput.value;
        }
      });
    });
    var amountInput = document.getElementById('tds-tk-physical-amount');
    var paidInput = document.getElementById('tds-tk-physical-paid');
    if (amountInput) {
      amountInput.addEventListener('input', function () {
        state.amount = amountInput.value;
      });
    }
    if (paidInput) {
      paidInput.addEventListener('input', function () {
        state.paid = paidInput.value;
      });
    }
    root.setAttribute('data-ready', '1');
  }

  function renderPicker(trips) {
    if (loading) loading.hidden = true;
    if (!sheet) return;
    sheet.hidden = false;
    if (empty) empty.hidden = true;
    var links = (trips || [])
      .map(function (trip) {
        var href = '/operator/app.php?module=tickets&page=physical_booking.php&id=' + encodeURIComponent(trip.id) + '&bus=' + encodeURIComponent(trip.bus) + '&route=' + encodeURIComponent(trip.route);
        return '<li><a href="' + href + '">' + esc(trip.bus) + ' · ' + esc(trip.route) + '</a></li>';
      })
      .join('');
    sheet.innerHTML = '<h2 class="tds-section-title">Choose a trip</h2><ul>' + (links || '<li>No active trips.</li>') + '</ul>';
    root.setAttribute('data-ready', '1');
  }

  if (printBtn) {
    printBtn.addEventListener('click', function () {
      window.print();
    });
  }

  var params = new URLSearchParams(window.location.search);
  params.set('kind', 'physical');
  fetch('/tickets/api/console/print_document.php?' + params.toString(), { credentials: 'same-origin' })
    .then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body.ok) throw new Error((body.error && body.error.message) || 'Could not load trips');
        return body.data || {};
      });
    })
    .then(function (data) {
      if (loading) loading.hidden = true;
      if (data.trip) {
        state.trip = data.trip;
        state.seats = data.seats || [];
        state.amount = data.trip.fare || '';
        paint();
        return;
      }
      renderPicker(data.trips || []);
    })
    .catch(function (err) {
      showError(err.message);
    });
})();
