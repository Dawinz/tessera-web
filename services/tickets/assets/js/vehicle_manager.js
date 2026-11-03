/**
 * Vehicle manager - fleet list, overview, drag-drop seat layout, assignments.
 */
(function ($) {
  'use strict';

  var state = {
    vehicles: [],
    selectedId: null,
    vehicle: null,
    layout: { grid: [], extras: [], patterns: [], labels: [] },
    drag: { type: null, fromRow: null, fromCol: null, extraIndex: null }
  };

  function toastOk(title, body) {
    if (window.TesseraStates && TesseraStates.toastOk) {
      TesseraStates.toastOk(title, body || '');
      return;
    }
    Swal.fire({ icon: 'success', title: title, text: body || '', timer: 1800, showConfirmButton: false });
  }

  function toastErr(title, body) {
    if (window.TesseraStates && TesseraStates.toastErr) {
      TesseraStates.toastErr(title, body || '');
      return;
    }
    Swal.fire({ icon: 'error', title: title, text: body || '' });
  }

  function escHtml(s) {
    return String(s || '').replace(/[&<>"']/g, function (c) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c];
    });
  }

  function patternsFromGrid(grid) {
    return grid.map(function (row) {
      return row.map(function (cell) {
        if (cell === 'aisle') return '-';
        if (cell === 'seat') return 'i';
        return '';
      }).join('');
    }).filter(function (p) { return p !== ''; });
  }

  function countLayoutSeats(grid, extras) {
    var n = (extras || []).length;
    grid.forEach(function (row) {
      row.forEach(function (cell) {
        if (cell === 'seat') n++;
      });
    });
    return n;
  }

  function labelsForLayout(grid, extras) {
    var labels = (extras || []).slice();
    var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
    grid.forEach(function (row, rowIndex) {
      var letter = alphabet[rowIndex] || ('R' + (rowIndex + 1));
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

  var SEAT_TEMPLATES = {
    37: ['i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'i-ii', 'iiii'],
    41: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    42: ['i--ii', 'i--ii', 'i--ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    45: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    47: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    49: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    53: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    57: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii'],
    59: ['ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'ii-ii', 'iiiii']
  };

  function fetchFleet() {
    var q = $('#vm-fleet-search').val() || '';
    var $list = $('#vm-fleet-list');
    $list.html('<div class="vm-fleet-empty text-muted small p-3">Loading vehicles...</div>');
    return $.getJSON('fetch_vehicle_manager_list.php', { q: q })
      .done(function (data) {
        state.vehicles = data.vehicles || [];
        renderFleetList();
      })
      .fail(function () {
        $list.html(
          '<div class="vm-fleet-empty text-muted small p-3">' +
          'Could not load fleet. <button type="button" class="btn btn-sm btn-soft-primary ms-1 vm-fleet-retry">Retry</button>' +
          '</div>'
        );
      });
  }

  function renderFleetList() {
    var $list = $('#vm-fleet-list');
    if (!state.vehicles.length) {
      $list.html('<div class="vm-fleet-empty text-muted small p-3">No vehicles found.</div>');
      return;
    }
    var html = '';
    state.vehicles.forEach(function (v) {
      var active = v.id === state.selectedId ? ' is-active' : '';
      var statusClass = v.status === 'active' ? 'text-success' : 'text-warning';
      var layoutBadge = v.layout_ok
        ? '<span class="badge bg-soft-success text-success">Layout OK</span>'
        : '<span class="badge bg-soft-warning text-warning">Layout mismatch</span>';
      html += '<button type="button" class="vm-fleet-item' + active + '" data-id="' + v.id + '">' +
        '<span class="vm-fleet-item__name">' + escHtml(v.name) + '</span>' +
        '<span class="vm-fleet-item__meta">' + escHtml(v.driver) + ' - ' + v.seats + ' seats</span>' +
        '<span class="vm-fleet-item__badges">' +
          '<span class="small ' + statusClass + '">' + escHtml(v.status) + '</span> ' + layoutBadge +
        '</span></button>';
    });
    $list.html(html);
  }

  function showWorkspace(show) {
    $('#vm-workspace-empty').toggleClass('d-none', show);
    $('#vm-workspace').toggleClass('d-none', !show);
  }

  function loadVehicle(id) {
    state.selectedId = id;
    renderFleetList();
    return $.getJSON('get_vehicle_detail.php', { id: id }).then(function (data) {
      state.vehicle = data.vehicle;
      state.layout.grid = data.layout.grid || [];
      state.layout.extras = data.layout.extras || [];
      state.layout.patterns = data.layout.patterns || [];
      state.layout.labels = data.layout.labels || [];
      populateOverview(data);
      renderLayoutCanvas();
      updateLayoutSummary();
      loadAssignments(id);
      showWorkspace(true);
      maybeOpenLayoutTab();
    }).fail(function () {
      toastErr('Could not load vehicle', 'Try again or pick another bus.');
    });
  }

  function populateOverview(data) {
    var v = data.vehicle;
    var a = data.assignments || {};
    $('#vm-hero-name').text(v.name);
    $('#vm-hero-status').text(v.status === 'active' ? 'Active' : 'Off fleet').toggleClass('bg-soft-success text-success', v.status === 'active').toggleClass('bg-soft-warning text-warning', v.status !== 'active');
    $('#vm-hero-type').text(v.state === 'vip' ? 'VIP' : 'Standard');
    $('#vm-hero-meta').text(v.driver + ' - ' + v.conductor + ' - ' + v.seats + ' seats');
    $('#vm-field-name').val(v.name);
    $('#vm-field-seats').val(v.seats);
    $('#vm-field-state').val(v.state || 'normal');
    $('#vm-field-driver').val(v.driver);
    $('#vm-field-conductor').val(v.conductor);
    $('#vm-field-detail').val(v.detail || '');
    $('#vm-field-dedicate').val(v.dedicate_seat_price || '');
    $('#vm-field-active').prop('checked', v.status === 'active');
    $('#vm-stat-templates').text(a.templates || 0);
    $('#vm-stat-manifests').text(a.manifests || 0);
    $('#vm-stat-bookings').text(a.bookings || 0);
  }

  function renderLayoutCanvas() {
    var $canvas = $('#vm-layout-canvas');
    $canvas.empty();
    state.layout.grid.forEach(function (row, rowIndex) {
      var $row = $('<div class="vm-layout-row" data-row="' + rowIndex + '"></div>');
      var $cells = $('<div class="vm-layout-row__cells"></div>');
      row.forEach(function (cell, colIndex) {
        $cells.append(buildCell(cell, rowIndex, colIndex));
      });
      var $tools = $('<div class="vm-layout-row__tools"></div>');
      $tools.append('<button type="button" class="btn btn-link btn-sm text-muted vm-row-add-seat" title="Add seat"><i class="uil uil-plus"></i></button>');
      $tools.append('<button type="button" class="btn btn-link btn-sm text-muted vm-row-add-aisle" title="Add aisle"><i class="uil uil-arrows-resize"></i></button>');
      $tools.append('<button type="button" class="btn btn-link btn-sm text-danger vm-row-remove" title="Remove row"><i class="uil uil-trash-alt"></i></button>');
      $row.append($('<div class="vm-layout-row__handle" draggable="true" title="Drag to reorder row"><i class="uil uil-draggabledots"></i></div>'));
      $row.append($cells);
      $row.append($tools);
      $canvas.append($row);
    });

    var $extras = $('#vm-layout-extras');
    $extras.empty();
    if (state.layout.extras.length) {
      $extras.append('<div class="vm-layout-extras__label small text-muted mb-2">Extra seats (front / special)</div>');
      var $wrap = $('<div class="vm-layout-extras__list"></div>');
      state.layout.extras.forEach(function (label, idx) {
        $wrap.append(
          '<div class="vm-layout-extra" draggable="true" data-extra="' + idx + '">' +
            '<span class="vm-layout-extra__label">' + escHtml(label) + '</span>' +
            '<button type="button" class="btn btn-link btn-sm text-danger vm-extra-remove" data-extra="' + idx + '"><i class="uil uil-times"></i></button>' +
          '</div>'
        );
      });
      $extras.append($wrap);
    }
  }

  function buildCell(cell, rowIndex, colIndex) {
    var cls = 'vm-layout-cell';
    var label = '';
    if (cell === 'seat') {
      cls += ' vm-layout-cell--seat';
      label = '<span class="vm-layout-cell__icon"><i class="uil uil-chair"></i></span>';
    } else if (cell === 'aisle') {
      cls += ' vm-layout-cell--aisle';
      label = '<span class="vm-layout-cell__icon">||</span>';
    } else {
      cls += ' vm-layout-cell--empty';
    }
    return $('<div class="' + cls + '" draggable="' + (cell === 'seat' ? 'true' : 'false') + '" data-row="' + rowIndex + '" data-col="' + colIndex + '" data-type="' + cell + '">' + label + '</div>');
  }

  function updateLayoutSummary() {
    var capacity = state.vehicle ? parseInt(state.vehicle.seats, 10) : 0;
    var count = countLayoutSeats(state.layout.grid, state.layout.extras);
    var ok = count === capacity;
    state.layout.labels = labelsForLayout(state.layout.grid, state.layout.extras);
    $('#vm-layout-capacity').text(capacity);
    $('#vm-layout-count').text(count);
    $('#vm-layout-match').text(ok ? 'OK' : ('Off by ' + Math.abs(capacity - count))).toggleClass('text-success', ok).toggleClass('text-warning', !ok);
    $('#vm-layout-labels').text(state.layout.labels.join(', ') || '-');
    renderLayoutPreview();
  }

  function renderLayoutPreview() {
    var $chart = $('#vm-layout-preview-chart');
    $chart.empty();
    if (!state.layout.grid.length && !state.layout.extras.length) {
      $chart.html('<span class="text-muted small">No layout yet.</span>');
      return;
    }
    var labels = state.layout.labels || [];
    var labelIndex = 0;
    if (state.layout.extras.length) {
      var $extraRow = $('<div class="vm-preview-row vm-preview-row--extras"></div>');
      state.layout.extras.forEach(function () {
        $extraRow.append('<span class="vm-preview-seat vm-preview-seat--extra">' + escHtml(labels[labelIndex++] || '?') + '</span>');
      });
      $chart.append($extraRow);
    }
    state.layout.grid.forEach(function (row) {
      var $row = $('<div class="vm-preview-row"></div>');
      row.forEach(function (cell) {
        if (cell === 'aisle') {
          $row.append('<span class="vm-preview-aisle" aria-hidden="true"></span>');
        } else if (cell === 'seat') {
          $row.append('<span class="vm-preview-seat">' + escHtml(labels[labelIndex++] || '?') + '</span>');
        }
      });
      $chart.append($row);
    });
  }

  function maybeOpenLayoutTab() {
    if (window.location.hash !== '#layout') return;
    var tabBtn = document.querySelector('[data-bs-target="#vm-tab-layout"]');
    if (tabBtn && window.bootstrap && bootstrap.Tab) {
      bootstrap.Tab.getOrCreateInstance(tabBtn).show();
    }
  }

  function loadAssignments(id) {
    $.getJSON('fetch_vehicle_assignments.php', { vehicle_id: id }).then(function (data) {
      var $t = $('#vm-assign-templates');
      var $m = $('#vm-assign-manifests');
      if (!data.templates || !data.templates.length) {
        $t.html('<p class="text-muted small">Not assigned to any weekday templates.</p>');
      } else {
        $t.html(data.templates.map(function (row) {
          return '<a href="' + escHtml(row.manage_url || 'register_trip.php') + '" class="vm-assign-item vm-assign-item--link">' +
            '<strong>' + escHtml(row.route_name || 'Route') + '</strong><span class="small text-muted">' +
            escHtml(row.day) + ' - ' + escHtml(row.departure) + '-' + escHtml(row.arrival) + '</span></a>';
        }).join(''));
      }
      if (!data.manifests || !data.manifests.length) {
        $m.html('<p class="text-muted small">No recent manifest rows.</p>');
      } else {
        $m.html(data.manifests.map(function (row) {
          return '<a href="' + escHtml(row.url || 'manifest.php') + '" class="vm-assign-item vm-assign-item--link">' +
            '<strong>' + escHtml(row.trip_date) + '</strong><span class="small text-muted">' +
            escHtml(row.route_name) + '</span></a>';
        }).join(''));
      }
    });
  }

  function saveVehicleDetails() {
    if (!state.selectedId) return;
    $.post('update_vehicle.php', {
      id_no: state.selectedId,
      vehicle_name: $('#vm-field-name').val(),
      driver_name: $('#vm-field-driver').val(),
      conductor_name: $('#vm-field-conductor').val(),
      seat_no: $('#vm-field-seats').val(),
      detail: $('#vm-field-detail').val(),
      dedicate_seat_price: $('#vm-field-dedicate').val(),
      check: $('#vm-field-active').is(':checked') ? 'on' : '',
      state: $('#vm-field-state').val()
    }).done(function (resp) {
      if ($.trim(resp) === 'succesfull') {
        toastOk('Vehicle saved');
        loadVehicle(state.selectedId);
        fetchFleet();
      } else {
        toastErr('Save failed', String(resp));
      }
    }).fail(function () {
      toastErr('Save failed', 'Could not reach the server.');
    });
  }

  function saveLayout() {
    if (!state.selectedId) return;
    var capacity = state.vehicle ? parseInt(state.vehicle.seats, 10) : 0;
    var count = countLayoutSeats(state.layout.grid, state.layout.extras);
    if (count !== capacity) {
      toastErr('Seat count mismatch', 'Layout has ' + count + ' seats but capacity is ' + capacity + '. Adjust rows or total seats first.');
      return;
    }
    var payload = {
      patterns: patternsFromGrid(state.layout.grid),
      extras: state.layout.extras.slice(),
      grid: state.layout.grid
    };
    $.post('save_vehicle_layout.php', {
      vehicle_id: state.selectedId,
      layout: JSON.stringify(payload)
    }).done(function (data) {
      if (data && data.ok) {
        toastOk('Layout saved');
        if (!data.layout_ok) {
          toastErr('Seat count mismatch', 'Layout has ' + data.layout_seats + ' seats but capacity is ' + state.vehicle.seats + '.');
        }
        loadVehicle(state.selectedId);
        fetchFleet();
      } else {
        toastErr('Layout not saved', (data && data.message) || (data && data.error) || 'Unknown error');
      }
    }).fail(function (xhr) {
      var msg = 'Could not save layout.';
      try {
        var j = JSON.parse(xhr.responseText);
        if (j.message) msg = j.message;
      } catch (e) { /* ignore */ }
      toastErr('Layout not saved', msg);
    });
  }

  function createVehicle() {
    $.post('save_vehicle.php', {
      vehicle_name: $('#vm-create-name').val(),
      driver_name: $('#vm-create-driver').val(),
      conductor_name: $('#vm-create-conductor').val(),
      seat_no: $('#vm-create-seats').val(),
      detail: $('#vm-create-detail').val(),
      state: $('#vm-create-state').val(),
      check: 'on'
    }).done(function (resp) {
      var r = $.trim(resp);
      if (r === 'succesfull') {
        $('#vm-create-modal').modal('hide');
        toastOk('Vehicle created');
        fetchFleet().then(function () {
          var match = state.vehicles.find(function (v) {
            return v.name === $('#vm-create-name').val();
          });
          if (match) loadVehicle(match.id);
        });
      } else if (r === 'found') {
        toastErr('Already exists', 'That vehicle number is already registered.');
      } else {
        toastErr('Create failed', r);
      }
    });
  }

  function deleteVehicle() {
    if (!state.selectedId || !state.vehicle) return;
    Swal.fire({
      title: 'Delete ' + state.vehicle.name + '?',
      text: 'This cannot be undone if the vehicle has no bookings.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Delete'
    }).then(function (result) {
      if (!result.isConfirmed) return;
      $.post('delete_vehilce.php', { id: state.selectedId, name: state.vehicle.name }).done(function (resp) {
        if ($.trim(resp) === 'cant') {
          toastErr('Cannot delete', 'This vehicle has ticket bookings.');
        } else {
          toastOk('Vehicle deleted');
          state.selectedId = null;
          showWorkspace(false);
          fetchFleet();
        }
      });
    });
  }

  function applyTemplateSeats(seats) {
    var patterns = SEAT_TEMPLATES[seats];
    if (!patterns) return;
    state.layout.grid = patterns.map(function (p) {
      return p.split('').map(function (ch) { return ch === '-' ? 'aisle' : 'seat'; });
    });
    state.layout.extras = (seats === 45 || seats === 59) ? ['xt1', 'xt2'] : [];
    if (state.vehicle) state.vehicle.seats = seats;
    $('#vm-field-seats').val(seats);
    renderLayoutCanvas();
    updateLayoutSummary();
  }

  function bindEvents() {
    $('#vm-fleet-search').on('input', function () {
      clearTimeout(bindEvents._searchTimer);
      bindEvents._searchTimer = setTimeout(fetchFleet, 250);
    });

    $('#vm-fleet-list').on('click', '.vm-fleet-item', function () {
      loadVehicle(parseInt($(this).data('id'), 10));
    });

    $('#vm-add-vehicle, #vm-add-vehicle-empty').on('click', function () {
      $('#vm-create-modal').modal('show');
    });
    $('#vm-create-submit').on('click', createVehicle);
    $('#vm-save-vehicle').on('click', saveVehicleDetails);
    $('#vm-delete-vehicle').on('click', deleteVehicle);
    $('#vm-layout-save').on('click', saveLayout);

    $('#vm-layout-add-row').on('click', function () {
      state.layout.grid.push(['seat', 'seat', 'aisle', 'seat', 'seat']);
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    $('#vm-layout-add-extra').on('click', function () {
      var n = state.layout.extras.length + 1;
      state.layout.extras.push('xt' + n);
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    $('.vm-layout-template').on('click', function () {
      applyTemplateSeats(parseInt($(this).data('seats'), 10));
    });

    $('#vm-layout-reset').on('click', function () {
      if (!state.selectedId) return;
      loadVehicle(state.selectedId);
    });

    $('#vm-layout-canvas').on('click', '.vm-layout-cell', function () {
      var $c = $(this);
      var r = parseInt($c.data('row'), 10);
      var c = parseInt($c.data('col'), 10);
      var type = $c.data('type');
      if (type === 'seat') {
        state.layout.grid[r][c] = 'aisle';
      } else if (type === 'aisle') {
        state.layout.grid[r].splice(c, 1);
        if (!state.layout.grid[r].length) state.layout.grid.splice(r, 1);
      } else {
        state.layout.grid[r][c] = 'seat';
      }
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    $('#vm-layout-canvas').on('click', '.vm-row-add-seat', function (e) {
      e.stopPropagation();
      var r = parseInt($(this).closest('.vm-layout-row').data('row'), 10);
      state.layout.grid[r].push('seat');
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    $('#vm-layout-canvas').on('click', '.vm-row-add-aisle', function (e) {
      e.stopPropagation();
      var r = parseInt($(this).closest('.vm-layout-row').data('row'), 10);
      state.layout.grid[r].push('aisle');
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    $('#vm-layout-canvas').on('click', '.vm-row-remove', function (e) {
      e.stopPropagation();
      var r = parseInt($(this).closest('.vm-layout-row').data('row'), 10);
      state.layout.grid.splice(r, 1);
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    $('#vm-layout-extras').on('click', '.vm-extra-remove', function (e) {
      e.stopPropagation();
      var idx = parseInt($(this).data('extra'), 10);
      state.layout.extras.splice(idx, 1);
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    var dragExtraIndex = null;
    $('#vm-layout-extras').on('dragstart', '.vm-layout-extra', function (e) {
      dragExtraIndex = parseInt($(this).data('extra'), 10);
      e.originalEvent.dataTransfer.effectAllowed = 'move';
    });
    $('#vm-layout-extras').on('dragover', '.vm-layout-extra', function (e) {
      e.preventDefault();
      $(this).addClass('vm-layout-extra--drop-target');
    });
    $('#vm-layout-extras').on('dragleave', '.vm-layout-extra', function () {
      $(this).removeClass('vm-layout-extra--drop-target');
    });
    $('#vm-layout-extras').on('drop', '.vm-layout-extra', function (e) {
      e.preventDefault();
      $('.vm-layout-extra--drop-target').removeClass('vm-layout-extra--drop-target');
      if (dragExtraIndex === null) return;
      var toIndex = parseInt($(this).data('extra'), 10);
      if (dragExtraIndex === toIndex) return;
      var item = state.layout.extras.splice(dragExtraIndex, 1)[0];
      state.layout.extras.splice(toIndex, 0, item);
      dragExtraIndex = null;
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    // Drag seat cells
    $('#vm-layout-canvas').on('dragstart', '.vm-layout-cell--seat', function (e) {
      state.drag = { type: 'seat', fromRow: parseInt($(this).data('row'), 10), fromCol: parseInt($(this).data('col'), 10) };
      e.originalEvent.dataTransfer.effectAllowed = 'move';
    });

    $('#vm-layout-canvas').on('dragover', '.vm-layout-cell', function (e) {
      e.preventDefault();
      $(this).addClass('vm-layout-cell--drop-target');
    });

    $('#vm-layout-canvas').on('dragleave', '.vm-layout-cell', function () {
      $(this).removeClass('vm-layout-cell--drop-target');
    });

    $('#vm-layout-canvas').on('drop', '.vm-layout-cell', function (e) {
      e.preventDefault();
      $('.vm-layout-cell--drop-target').removeClass('vm-layout-cell--drop-target');
      if (state.drag.type !== 'seat') return;
      var toRow = parseInt($(this).data('row'), 10);
      var toCol = parseInt($(this).data('col'), 10);
      var fromRow = state.drag.fromRow;
      var fromCol = state.drag.fromCol;
      if (fromRow === toRow && fromCol === toCol) return;
      var moving = state.layout.grid[fromRow][fromCol];
      state.layout.grid[fromRow].splice(fromCol, 1);
      if (state.layout.grid[fromRow].length === 0) state.layout.grid.splice(fromRow, 1);
      if (toRow >= state.layout.grid.length) state.layout.grid.push([]);
      state.layout.grid[toRow].splice(toCol, 0, moving);
      state.drag = { type: null };
      renderLayoutCanvas();
      updateLayoutSummary();
    });

    // Drag rows
    var dragRowIndex = null;
    $('#vm-layout-canvas').on('dragstart', '.vm-layout-row__handle', function (e) {
      dragRowIndex = parseInt($(this).closest('.vm-layout-row').data('row'), 10);
      e.originalEvent.dataTransfer.effectAllowed = 'move';
    });
    $('#vm-layout-canvas').on('dragover', '.vm-layout-row', function (e) {
      e.preventDefault();
      $(this).addClass('vm-layout-row--drop-target');
    });
    $('#vm-layout-canvas').on('dragleave', '.vm-layout-row', function () {
      $(this).removeClass('vm-layout-row--drop-target');
    });
    $('#vm-layout-canvas').on('drop', '.vm-layout-row', function (e) {
      e.preventDefault();
      $('.vm-layout-row--drop-target').removeClass('vm-layout-row--drop-target');
      if (dragRowIndex === null) return;
      var toRow = parseInt($(this).data('row'), 10);
      if (dragRowIndex === toRow) return;
      var row = state.layout.grid.splice(dragRowIndex, 1)[0];
      state.layout.grid.splice(toRow, 0, row);
      dragRowIndex = null;
      renderLayoutCanvas();
      updateLayoutSummary();
    });
  }

  $(function () {
    bindEvents();
    $(document).on('click', '.vm-fleet-retry', function (e) {
      e.preventDefault();
      fetchFleet();
    });
    fetchFleet().then(function () {
      if (window.VM_PRESELECT_ID > 0) {
        loadVehicle(window.VM_PRESELECT_ID);
      }
    });
  });
})(jQuery);
