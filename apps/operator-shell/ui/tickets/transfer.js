(function () {
  var root = document.querySelector('[data-kind="transfer"]');
  if (!root) return;
  var id = root.id;
  var loading = document.getElementById(id + '-loading');
  var empty = document.getElementById(id + '-empty');
  var emptyText = document.getElementById(id + '-empty-text');
  var errorEl = document.getElementById(id + '-error');
  var sheet = document.getElementById(id + '-sheet');
  var actions = document.getElementById(id + '-actions');
  var printBtn = document.getElementById(id + '-print');
  var mode = root.getAttribute('data-mode') || 'from';
  var board = null;

  function esc(value) {
    return String(value == null ? '' : value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function showError(message) {
    if (errorEl) {
      errorEl.hidden = false;
      errorEl.textContent = message || 'Transfer failed';
    }
  }

  function checkedIds() {
    if (!sheet) return [];
    return Array.prototype.map
      .call(sheet.querySelectorAll('[data-booking]:checked'), function (box) {
        return Number(box.getAttribute('data-booking'));
      })
      .filter(function (n) {
        return n > 0;
      });
  }

  function paint() {
    if (loading) loading.hidden = true;
    if (!board || !board.source) {
      if (empty) empty.hidden = false;
      if (emptyText) emptyText.textContent = 'Open transfer from a trip on the manifest.';
      var trips = (board && board.targets) || [];
      if (sheet && trips.length) {
        sheet.hidden = false;
        if (empty) empty.hidden = true;
        sheet.innerHTML =
          '<h2 class="tds-section-title">Trips you can transfer onto</h2><ul>' +
          trips
            .slice(0, 12)
            .map(function (trip) {
              return '<li>' + esc(trip.bus) + ' · ' + esc(trip.route) + '</li>';
            })
            .join('') +
          '</ul>';
      }
      root.setAttribute('data-ready', '1');
      return;
    }
    if (empty) empty.hidden = true;
    if (sheet) sheet.hidden = false;
    var people = board.passengers || [];
    var rows = people
      .map(function (person) {
        return '<tr><td><input type="checkbox" data-booking="' + esc(person.id) + '" aria-label="Select seat ' + esc(person.seat) + '"></td><td>' + esc(person.seat) + '</td><td>' + esc(person.name) + '</td><td>' + esc(person.phone) + '</td><td>' + esc(person.status) + '</td><td class="tds-mono">' + esc(person.fare) + '</td></tr>';
      })
      .join('');
    var options = (board.targets || [])
      .map(function (trip) {
        return '<option value="' + esc(trip.id) + '">' + esc(trip.label || trip.bus + ' · ' + trip.route) + '</option>';
      })
      .join('');
    sheet.innerHTML =
      '<div class="tds-kpi-grid" style="margin-bottom:var(--tds-space-4)">' +
      '<div class="tds-kpi"><span class="tds-kpi-label">From</span><strong class="tds-kpi-value">' + esc(board.source.bus) + '</strong></div>' +
      '<div class="tds-kpi"><span class="tds-kpi-label">Route</span><strong class="tds-kpi-value">' + esc(board.source.route) + '</strong></div>' +
      '<div class="tds-kpi"><span class="tds-kpi-label">Date</span><strong class="tds-kpi-value">' + esc(board.source.trip_date) + '</strong></div>' +
      '<div class="tds-kpi"><span class="tds-kpi-label">Passengers</span><strong class="tds-kpi-value">' + esc(people.length) + '</strong></div>' +
      '</div>' +
      '<div class="tds-field"><label class="tds-label" for="' + id + '-target">Target trip</label><select class="tds-select" id="' + id + '-target"><option value="">Choose a trip</option>' + options + '</select></div>' +
      '<div class="tds-field"><label class="tds-label" for="' + id + '-target-date">Target date</label><input class="tds-input" id="' + id + '-target-date" type="date"></div>' +
      '<div class="tds-table-wrap" style="margin-top:var(--tds-space-4)"><table class="tds-table"><thead><tr><th></th><th>Seat</th><th>Passenger</th><th>Phone</th><th>Status</th><th>Fare</th></tr></thead><tbody>' +
      (rows || '<tr><td colspan="6">No passengers on this trip.</td></tr>') +
      '</tbody></table></div>';
    if (actions) {
      var buttons = '';
      if (mode === 'from' || mode === 'all') {
        buttons += '<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" data-transfer="all">Transfer all</button>';
      }
      if (mode === 'from' || mode === 'multiple' || mode === 'selected') {
        buttons += '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-transfer="' + (mode === 'selected' ? 'selected' : 'multiple') + '">Transfer selected</button>';
      }
      actions.innerHTML = buttons;
      actions.querySelectorAll('[data-transfer]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          submit(btn.getAttribute('data-transfer') || 'multiple');
        });
      });
    }
    root.setAttribute('data-ready', '1');
  }

  function submit(action) {
    if (errorEl) errorEl.hidden = true;
    var target = document.getElementById(id + '-target');
    var targetDate = document.getElementById(id + '-target-date');
    var payload = {
      action: action,
      source_route_day_id: board && board.source ? board.source.id : 0,
      source_trip_date: board && board.source ? board.source.trip_date : '',
      target_route_day_id: target ? Number(target.value) : 0,
      target_trip_date: targetDate ? targetDate.value : '',
      booking_ids: action === 'all' ? [] : checkedIds(),
    };
    fetch('/tickets/api/console/print_transfer.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body.ok) {
            throw new Error((body.error && body.error.message) || 'Transfer failed');
          }
          return body.data || {};
        });
      })
      .then(function (data) {
        if (errorEl) {
          errorEl.className = 'tds-alert tds-alert-success tds-print-chrome';
          errorEl.hidden = false;
          errorEl.textContent = 'Moved ' + (data.moved || 0) + ' passenger' + (data.moved === 1 ? '' : 's') + '.';
        }
      })
      .catch(function (err) {
        if (errorEl) errorEl.className = 'tds-alert tds-alert-danger tds-print-chrome';
        showError(err.message);
      });
  }

  if (printBtn) {
    printBtn.addEventListener('click', function () {
      window.print();
    });
  }

  var params = new URLSearchParams(window.location.search);
  fetch('/tickets/api/console/print_transfer.php?' + params.toString(), { credentials: 'same-origin' })
    .then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body.ok) throw new Error((body.error && body.error.message) || 'Could not load transfer');
        return body.data || {};
      });
    })
    .then(function (data) {
      board = data;
      paint();
    })
    .catch(function (err) {
      if (loading) loading.hidden = true;
      showError(err.message);
    });
})();
