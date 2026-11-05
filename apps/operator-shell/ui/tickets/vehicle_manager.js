/**
 * T-P0-05 Vehicle manager — modern console UI (TDS + console API).
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-vehicle-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var SEAT_TEMPLATES = {
    37: ['i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'iiii'],
    41: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    45: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    49: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    57: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    59: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
  };

  var state = {
    vehicles: [],
    selectedId: null,
    vehicle: null,
    layout: { grid: [], extras: [], labels: [] },
    tab: 'overview',
  };

  var errEl = document.getElementById('tds-vm-error');
  var okEl = document.getElementById('tds-vm-success');
  var fleetEl = document.getElementById('tds-vm-fleet');
  var emptyEl = document.getElementById('tds-vm-empty');
  var workspaceEl = document.getElementById('tds-vm-workspace');
  var createDlg = document.getElementById('tds-vm-create');

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    if (!okEl) return;
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
    if (msg) setTimeout(function () { okEl.hidden = true; }, 3200);
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
            err.body = body;
            throw err;
          }
          return body;
        });
      }
    );
  }

  function patternsFromGrid(grid) {
    return (grid || [])
      .map(function (row) {
        return row
          .map(function (cell) {
            if (cell === 'aisle') return '-';
            if (cell === 'seat') return 'i';
            return '';
          })
          .join('');
      })
      .filter(function (p) {
        return p !== '';
      });
  }

  function gridFromPatterns(patterns) {
    return (patterns || []).map(function (pattern) {
      var row = [];
      String(pattern)
        .split('')
        .forEach(function (ch) {
          if (ch === 'i') row.push('seat');
          else if (ch === '-') row.push('aisle');
        });
      return row;
    });
  }

  function countSeats(grid, extras) {
    var n = (extras || []).length;
    (grid || []).forEach(function (row) {
      row.forEach(function (cell) {
        if (cell === 'seat') n++;
      });
    });
    return n;
  }

  function labelsForLayout(grid, extras) {
    var labels = (extras || []).slice();
    var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
    (grid || []).forEach(function (row, rowIndex) {
      var letter = alphabet[rowIndex] || 'R' + (rowIndex + 1);
      var seatNo = 0;
      row.forEach(function (cell) {
        if (cell === 'seat') {
          seatNo++;
          labels.push(letter + seatNo);
        }
      });
    });
    return labels;
  }

  function loadFleet() {
    var q = document.getElementById('tds-vm-search');
    var params = new URLSearchParams();
    if (q && q.value.trim()) params.set('q', q.value.trim());
    return fetchJson(API + '/vehicles.php?' + params.toString())
      .then(function (body) {
        state.vehicles = (body.data && body.data.items) || [];
        renderFleet();
      })
      .catch(function (err) {
        showError(err.message || 'Could not load fleet');
        fleetEl.innerHTML = '<div class="tds-empty"><p class="tds-muted">Could not load fleet</p></div>';
      });
  }

  function renderFleet() {
    if (!fleetEl) return;
    if (!state.vehicles.length) {
      fleetEl.innerHTML = '<div class="tds-empty"><p class="tds-muted">No vehicles found</p></div>';
      return;
    }
    fleetEl.innerHTML = state.vehicles
      .map(function (v) {
        var active = v.id === state.selectedId ? ' is-active' : '';
        var layout = v.layout_ok
          ? '<span class="tds-badge tds-badge-paid">Layout OK</span>'
          : '<span class="tds-badge tds-badge-warn">Layout mismatch</span>';
        return (
          '<button type="button" class="tds-fleet-item' +
          active +
          '" data-id="' +
          v.id +
          '">' +
          '<span class="tds-fleet-item__name">' +
          esc(v.name) +
          '</span>' +
          '<span class="tds-fleet-item__meta">' +
          esc(v.driver || '—') +
          ' · ' +
          v.seats +
          ' seats · ' +
          esc(v.status) +
          '</span>' +
          '<span style="display:block;margin-top:0.35rem">' +
          layout +
          '</span></button>'
        );
      })
      .join('');
  }

  function selectVehicle(id) {
    state.selectedId = id;
    renderFleet();
    showError('');
    return fetchJson(API + '/vehicle.php?id=' + id)
      .then(function (body) {
        state.vehicle = body.data.vehicle;
        state.layout.grid = (body.data.layout && body.data.layout.grid) || [];
        state.layout.extras = (body.data.layout && body.data.layout.extras) || [];
        state.layout.labels = (body.data.layout && body.data.layout.labels) || [];
        populateOverview(body.data);
        renderLayout();
        updateLayoutSummary();
        emptyEl.hidden = true;
        workspaceEl.hidden = false;
        setTab(state.tab);
        return loadAssignments(id);
      })
      .catch(function (err) {
        showError(err.message || 'Could not load vehicle');
      });
  }

  function populateOverview(data) {
    var v = data.vehicle || {};
    var a = data.assignments || {};
    document.getElementById('tds-vm-hero-name').textContent = v.name || '—';
    var status = document.getElementById('tds-vm-hero-status');
    status.textContent = v.status === 'active' ? 'Active' : 'Off fleet';
    status.className = 'tds-badge ' + (v.status === 'active' ? 'tds-badge-paid' : 'tds-badge-warn');
    document.getElementById('tds-vm-hero-type').textContent = v.state === 'vip' ? 'VIP' : 'Standard';
    document.getElementById('tds-vm-hero-meta').textContent =
      (v.driver || '—') + ' · ' + (v.conductor || '—') + ' · ' + (v.seats || 0) + ' seats';
    document.getElementById('tds-vm-name').value = v.name || '';
    document.getElementById('tds-vm-seats').value = v.seats || '';
    document.getElementById('tds-vm-state').value = v.state || 'normal';
    document.getElementById('tds-vm-driver').value = v.driver || '';
    document.getElementById('tds-vm-conductor').value = v.conductor || '';
    document.getElementById('tds-vm-detail').value = v.detail || '';
    document.getElementById('tds-vm-dedicate').value = v.dedicate_seat_price || '';
    document.getElementById('tds-vm-active').checked = v.status === 'active';
    document.getElementById('tds-vm-stat-templates').textContent = String(a.templates || 0);
    document.getElementById('tds-vm-stat-manifests').textContent = String(a.manifests || 0);
    document.getElementById('tds-vm-stat-bookings').textContent = String(a.bookings || 0);
    var delBtn = document.getElementById('tds-vm-delete');
    if (delBtn) {
      var canDelete = data.can_delete !== false && !(a.bookings > 0);
      delBtn.hidden = !canDelete;
      delBtn.disabled = !canDelete;
    }
  }

  function setTab(tab) {
    state.tab = tab;
    document.querySelectorAll('.tds-tab').forEach(function (btn) {
      btn.classList.toggle('is-active', btn.getAttribute('data-tab') === tab);
    });
    document.getElementById('tds-vm-panel-overview').hidden = tab !== 'overview';
    document.getElementById('tds-vm-panel-layout').hidden = tab !== 'layout';
    document.getElementById('tds-vm-panel-assignments').hidden = tab !== 'assignments';
  }

  function renderLayout() {
    var canvas = document.getElementById('tds-layout-canvas');
    var extrasEl = document.getElementById('tds-layout-extras');
    canvas.innerHTML = '';
    state.layout.grid.forEach(function (row, rowIndex) {
      var rowEl = document.createElement('div');
      rowEl.className = 'tds-layout-row';
      var cells = document.createElement('div');
      cells.className = 'tds-layout-cells';
      row.forEach(function (cell, colIndex) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'tds-layout-cell';
        if (cell === 'seat') {
          btn.className += ' tds-layout-cell--seat';
          btn.textContent = 'S';
        } else if (cell === 'aisle') {
          btn.className += ' tds-layout-cell--aisle';
          btn.textContent = '||';
        } else {
          btn.textContent = '·';
        }
        btn.addEventListener('click', function () {
          cycleCell(rowIndex, colIndex);
        });
        cells.appendChild(btn);
      });
      var tools = document.createElement('div');
      tools.className = 'tds-row';
      var addSeat = document.createElement('button');
      addSeat.type = 'button';
      addSeat.className = 'tds-btn tds-btn-ghost tds-btn-sm';
      addSeat.textContent = '+ seat';
      addSeat.addEventListener('click', function () {
        state.layout.grid[rowIndex].push('seat');
        renderLayout();
        updateLayoutSummary();
      });
      var rm = document.createElement('button');
      rm.type = 'button';
      rm.className = 'tds-btn tds-btn-ghost tds-btn-sm';
      rm.textContent = 'Remove';
      rm.addEventListener('click', function () {
        state.layout.grid.splice(rowIndex, 1);
        renderLayout();
        updateLayoutSummary();
      });
      tools.appendChild(addSeat);
      tools.appendChild(rm);
      rowEl.appendChild(cells);
      rowEl.appendChild(tools);
      canvas.appendChild(rowEl);
    });

    extrasEl.innerHTML = '';
    if (state.layout.extras.length) {
      var title = document.createElement('p');
      title.className = 'tds-muted';
      title.style.fontSize = 'var(--tds-fs-xs)';
      title.textContent = 'Extra seats';
      extrasEl.appendChild(title);
      var wrap = document.createElement('div');
      wrap.className = 'tds-row';
      state.layout.extras.forEach(function (label, idx) {
        var chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'tds-badge tds-badge-info';
        chip.textContent = label + ' ×';
        chip.addEventListener('click', function () {
          state.layout.extras.splice(idx, 1);
          renderLayout();
          updateLayoutSummary();
        });
        wrap.appendChild(chip);
      });
      extrasEl.appendChild(wrap);
    }
  }

  function cycleCell(rowIndex, colIndex) {
    var cur = state.layout.grid[rowIndex][colIndex];
    var next = cur === 'seat' ? 'aisle' : cur === 'aisle' ? 'empty' : 'seat';
    if (next === 'empty') {
      state.layout.grid[rowIndex].splice(colIndex, 1);
    } else {
      state.layout.grid[rowIndex][colIndex] = next;
    }
    renderLayout();
    updateLayoutSummary();
  }

  function updateLayoutSummary() {
    var capacity = state.vehicle ? parseInt(state.vehicle.seats, 10) : 0;
    var count = countSeats(state.layout.grid, state.layout.extras);
    var ok = count === capacity;
    state.layout.labels = labelsForLayout(state.layout.grid, state.layout.extras);
    document.getElementById('tds-layout-capacity').textContent = String(capacity);
    document.getElementById('tds-layout-count').textContent = String(count);
    var match = document.getElementById('tds-layout-match');
    match.textContent = ok ? 'OK' : 'Off by ' + Math.abs(capacity - count);
    match.style.color = ok ? 'var(--tds-success)' : 'var(--tds-warning)';
    renderPreview();
  }

  function renderPreview() {
    var preview = document.getElementById('tds-layout-preview');
    preview.innerHTML = '';
    var labels = state.layout.labels || [];
    var li = 0;
    if (state.layout.extras.length) {
      var er = document.createElement('div');
      er.className = 'tds-layout-preview-row';
      state.layout.extras.forEach(function () {
        var s = document.createElement('span');
        s.className = 'tds-layout-preview-seat';
        s.textContent = labels[li++] || '?';
        er.appendChild(s);
      });
      preview.appendChild(er);
    }
    state.layout.grid.forEach(function (row) {
      var r = document.createElement('div');
      r.className = 'tds-layout-preview-row';
      row.forEach(function (cell) {
        if (cell === 'aisle') {
          var a = document.createElement('span');
          a.className = 'tds-layout-preview-aisle';
          r.appendChild(a);
        } else if (cell === 'seat') {
          var s = document.createElement('span');
          s.className = 'tds-layout-preview-seat';
          s.textContent = labels[li++] || '?';
          r.appendChild(s);
        }
      });
      preview.appendChild(r);
    });
  }

  function loadAssignments(id) {
    return fetchJson(API + '/vehicle_assignments.php?id=' + id)
      .then(function (body) {
        var tEl = document.getElementById('tds-vm-templates');
        var mEl = document.getElementById('tds-vm-manifests');
        var templates = (body.data && body.data.templates) || [];
        var manifests = (body.data && body.data.manifests) || [];
        tEl.innerHTML = templates.length
          ? templates
              .map(function (row) {
                return (
                  '<a class="tds-fleet-item" href="' +
                  esc(row.url) +
                  '"><span class="tds-fleet-item__name">' +
                  esc(row.route_name || 'Route') +
                  '</span><span class="tds-fleet-item__meta">' +
                  esc(row.day) +
                  ' · ' +
                  esc(row.departure) +
                  '–' +
                  esc(row.arrival) +
                  '</span></a>'
                );
              })
              .join('')
          : '<p class="tds-muted">Not assigned to any weekday templates.</p>';
        mEl.innerHTML = manifests.length
          ? manifests
              .map(function (row) {
                return (
                  '<a class="tds-fleet-item" href="' +
                  esc(row.url) +
                  '"><span class="tds-fleet-item__name">' +
                  esc(row.trip_date) +
                  '</span><span class="tds-fleet-item__meta">' +
                  esc(row.route_name) +
                  '</span></a>'
                );
              })
              .join('')
          : '<p class="tds-muted">No recent manifest rows.</p>';
      })
      .catch(function () {
        /* non-blocking */
      });
  }

  function deleteVehicle() {
    if (!state.selectedId || !state.vehicle) return;
    var name = state.vehicle.name || 'this vehicle';
    if (
      !window.confirm(
        'Delete ' + name + '? Vehicles with ticket bookings cannot be removed.'
      )
    ) {
      return;
    }
    fetchJson(API + '/vehicle_delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({ id: state.selectedId }),
    })
      .then(function () {
        showOk('Vehicle deleted');
        state.selectedId = null;
        state.vehicle = null;
        if (workspaceEl) workspaceEl.hidden = true;
        return loadFleet().then(function () {
          if (state.vehicles.length) selectVehicle(state.vehicles[0].id);
        });
      })
      .catch(function (err) {
        showError(err.message || 'Could not delete vehicle');
      });
  }

  function saveDetails() {
    if (!state.selectedId) return;
    fetchJson(API + '/vehicle_save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({
        id: state.selectedId,
        name: document.getElementById('tds-vm-name').value.trim(),
        driver: document.getElementById('tds-vm-driver').value.trim(),
        conductor: document.getElementById('tds-vm-conductor').value.trim(),
        seats: parseInt(document.getElementById('tds-vm-seats').value, 10) || 0,
        detail: document.getElementById('tds-vm-detail').value.trim(),
        dedicate_seat_price: document.getElementById('tds-vm-dedicate').value.trim(),
        state: document.getElementById('tds-vm-state').value,
        active: document.getElementById('tds-vm-active').checked,
      }),
    })
      .then(function () {
        showOk('Vehicle saved');
        return loadFleet().then(function () {
          return selectVehicle(state.selectedId);
        });
      })
      .catch(function (err) {
        showError(err.message || 'Could not save vehicle');
      });
  }

  function saveLayout() {
    if (!state.selectedId) return;
    var capacity = state.vehicle ? parseInt(state.vehicle.seats, 10) : 0;
    var count = countSeats(state.layout.grid, state.layout.extras);
    if (count !== capacity) {
      showError('Layout has ' + count + ' seats but capacity is ' + capacity);
      return;
    }
    fetchJson(API + '/vehicle_layout.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({
        id: state.selectedId,
        patterns: patternsFromGrid(state.layout.grid),
        extras: state.layout.extras.slice(),
        grid: state.layout.grid,
      }),
    })
      .then(function () {
        showOk('Layout saved');
        return loadFleet().then(function () {
          return selectVehicle(state.selectedId);
        });
      })
      .catch(function (err) {
        showError(err.message || 'Could not save layout');
      });
  }

  function openCreate() {
    if (!createDlg) return;
    document.getElementById('tds-create-name').value = '';
    document.getElementById('tds-create-seats').value = '49';
    document.getElementById('tds-create-state').value = 'normal';
    document.getElementById('tds-create-driver').value = '';
    document.getElementById('tds-create-conductor').value = '';
    document.getElementById('tds-create-detail').value = '';
    if (typeof createDlg.showModal === 'function') createDlg.showModal();
    else createDlg.setAttribute('open', 'open');
  }

  function createVehicle() {
    fetchJson(API + '/vehicle_save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({
        name: document.getElementById('tds-create-name').value.trim(),
        seats: parseInt(document.getElementById('tds-create-seats').value, 10) || 0,
        state: document.getElementById('tds-create-state').value,
        driver: document.getElementById('tds-create-driver').value.trim(),
        conductor: document.getElementById('tds-create-conductor').value.trim(),
        detail: document.getElementById('tds-create-detail').value.trim(),
        active: true,
      }),
    })
      .then(function (body) {
        showOk('Vehicle created');
        if (typeof createDlg.close === 'function') createDlg.close();
        else createDlg.removeAttribute('open');
        var newId = body.data && body.data.id;
        return loadFleet().then(function () {
          if (newId) selectVehicle(newId);
        });
      })
      .catch(function (err) {
        showError(err.message || 'Could not create vehicle');
      });
  }

  fleetEl.addEventListener('click', function (e) {
    var btn = e.target.closest('.tds-fleet-item');
    if (!btn) return;
    selectVehicle(parseInt(btn.getAttribute('data-id'), 10));
  });
  document.getElementById('tds-vm-search').addEventListener('input', function () {
    clearTimeout(state._searchT);
    state._searchT = setTimeout(loadFleet, 250);
  });
  document.querySelectorAll('.tds-tab').forEach(function (btn) {
    btn.addEventListener('click', function () {
      setTab(btn.getAttribute('data-tab'));
    });
  });
  document.getElementById('tds-vm-save').addEventListener('click', saveDetails);
  var deleteBtn = document.getElementById('tds-vm-delete');
  if (deleteBtn) deleteBtn.addEventListener('click', deleteVehicle);
  document.getElementById('tds-layout-save').addEventListener('click', saveLayout);
  document.getElementById('tds-layout-add-row').addEventListener('click', function () {
    state.layout.grid.push(['seat', 'aisle', 'seat', 'seat']);
    renderLayout();
    updateLayoutSummary();
  });
  document.getElementById('tds-layout-add-extra').addEventListener('click', function () {
    state.layout.extras.push('xt' + (state.layout.extras.length + 1));
    renderLayout();
    updateLayoutSummary();
  });
  document.querySelectorAll('.tds-layout-tpl').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var seats = parseInt(btn.getAttribute('data-seats'), 10);
      var patterns = SEAT_TEMPLATES[seats];
      if (!patterns) return;
      state.layout.grid = gridFromPatterns(patterns);
      state.layout.extras = seats === 45 || seats === 59 ? ['xt1', 'xt2'] : [];
      if (state.vehicle) {
        document.getElementById('tds-vm-seats').value = String(seats);
        state.vehicle.seats = seats;
      }
      renderLayout();
      updateLayoutSummary();
    });
  });
  document.getElementById('tds-vm-add').addEventListener('click', openCreate);
  document.getElementById('tds-vm-add-empty').addEventListener('click', openCreate);
  document.getElementById('tds-vm-create-close').addEventListener('click', function () {
    if (typeof createDlg.close === 'function') createDlg.close();
    else createDlg.removeAttribute('open');
  });
  document.getElementById('tds-vm-create-save').addEventListener('click', createVehicle);

  var pre = parseInt(root.getAttribute('data-preselect') || '0', 10);
  loadFleet().then(function () {
    if (pre > 0) selectVehicle(pre);
    else if (state.vehicles.length) selectVehicle(state.vehicles[0].id);
  });
})();
