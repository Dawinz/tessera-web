/**
 * Route manager - routes network + weekly schedule board (import model).
 */
(function ($) {
  'use strict';

  var state = {
    routes: [],
    selectedKey: '',
    weekStart: null,
    vehicles: [],
    stopCatalog: [],
    metaStops: [],
    metaFares: [],
    ready: true
  };

  function escHtml(s) {
    return String(s || '').replace(/[&<>"']/g, function (c) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c];
    });
  }

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

  function formatMoney(amount, symbol) {
    var n = Number(amount || 0);
    if (!n) return ' - ';
    return symbol + ' ' + n.toLocaleString();
  }

  function parseWeekStart(val) {
    if (!val) return new Date();
    var d = new Date(val + 'T12:00:00');
    return isNaN(d.getTime()) ? new Date() : d;
  }

  function mondayOf(date) {
    var d = new Date(date);
    var day = d.getDay();
    var diff = day === 0 ? -6 : 1 - day;
    d.setDate(d.getDate() + diff);
    d.setHours(12, 0, 0, 0);
    return d;
  }

  function isoDate(d) {
    return d.toISOString().slice(0, 10);
  }

  function shiftWeek(delta) {
    var d = state.weekStart ? new Date(state.weekStart) : mondayOf(new Date());
    d.setDate(d.getDate() + (delta * 7));
    state.weekStart = d;
    loadSchedules();
  }

  function filteredRoutes() {
    var q = ($('#rm-route-search').val() || '').toLowerCase().trim();
    if (!q) return state.routes;
    return state.routes.filter(function (r) {
      return (r.label || '').toLowerCase().indexOf(q) >= 0
        || (r.path || '').toLowerCase().indexOf(q) >= 0
        || (r.route_key || '').toLowerCase().indexOf(q) >= 0;
    });
  }

  function renderRoutes() {
    var list = filteredRoutes();
    $('#rm-route-count').text(list.length);
    if (!list.length) {
      $('#rm-route-list').html('<div class="text-muted small p-3">No routes found.</div>');
      return;
    }
    var html = list.map(function (r) {
      var active = r.route_key === state.selectedKey ? ' is-active' : '';
      var typeBadge = r.is_main
        ? '<span class="badge bg-soft-primary text-primary rm-route-item__badge">Main</span>'
        : '<span class="badge bg-soft-warning text-warning rm-route-item__badge">Sub</span>';
      return '<button type="button" class="rm-route-item' + active + '" data-route-key="' + escHtml(r.route_key) + '">' +
        '<div class="rm-route-item__head">' +
          '<div><div class="rm-route-item__title">' + escHtml(r.label) + '</div>' +
          (r.path ? '<div class="rm-route-item__path">' + escHtml(r.path) + '</div>' : '') +
          '</div>' + typeBadge +
        '</div>' +
        '<div class="rm-route-item__meta">' +
          '<span><i class="uil uil-schedule me-1"></i>' + (r.schedule_count || 0) + ' templates</span>' +
          '<span><i class="uil uil-shield me-1"></i>' + (r.sub_route_count || 0) + ' sub-routes</span>' +
          '<span><i class="uil uil-money-bill me-1"></i>' + escHtml(formatMoney(r.fare_min, r.currency_symbol)) + '</span>' +
        '</div>' +
      '</button>';
    }).join('');
    $('#rm-route-list').html(html);
  }

  function renderScheduleBoard(data) {
    $('#rm-week-label').text(data.week_label || ' - ');
    if (!state.selectedKey) {
      $('#rm-schedule-board').html('<div class="text-muted small p-4 text-center">Select a route to view departure templates.</div>');
      return;
    }
    var days = data.days || [];
    if (!days.length) {
      $('#rm-schedule-board').html('<div class="text-muted small p-4 text-center">No schedule templates for this week.</div>');
      return;
    }
    var html = days.map(function (day) {
      var trips = day.trips || [];
      var tripHtml = trips.length ? trips.map(function (t) {
        return '<li class="rm-trip" data-schedule-id="' + t.id + '">' +
          '<div class="rm-trip__time">' + escHtml(t.departure_label || t.departure_time) + '</div>' +
          '<div><div class="rm-trip__bus">' + escHtml(t.vehicle_name) + '</div>' +
          '<div class="rm-trip__meta">' + escHtml(t.arrival_label || '') +
          (t.notes ? ' - ' + escHtml(t.notes) : '') + '</div></div>' +
          '<div class="rm-trip__actions">' +
            '<button type="button" class="btn btn-soft-danger btn-sm rm-trip-delete" data-id="' + t.id + '" title="Remove"><i class="uil uil-trash-alt"></i></button>' +
          '</div></li>';
      }).join('') : '<div class="rm-empty-day">No departures</div>';
      return '<section class="rm-schedule-day' + (day.is_today ? ' is-today' : '') + '">' +
        '<div class="rm-schedule-day__rail">' +
          '<span class="rm-schedule-day__name">' + escHtml(day.code) + '</span>' +
          '<span class="rm-schedule-day__date">' + escHtml(day.date_label) + '</span>' +
          '<span class="rm-schedule-day__count">' + trips.length + ' trip' + (trips.length === 1 ? '' : 's') + '</span>' +
        '</div>' +
        '<div class="rm-schedule-day__body"><ul class="rm-trip-list">' + tripHtml + '</ul></div>' +
      '</section>';
    }).join('');
    $('#rm-schedule-board').html(html);
  }

  function renderMeta(data) {
    var route = data.route;
    if (!route) {
      $('#rm-detail-card').addClass('d-none');
      return;
    }
    $('#rm-detail-card').removeClass('d-none');
    $('#rm-detail-title').text(route.label || ' - ');
    $('#rm-detail-path').text(route.path || '');
    $('#rm-detail-key').text(route.route_key || ' - ');
    var selected = state.routes.find(function (r) { return r.route_key === state.selectedKey; });
    $('#rm-detail-schedules').text(selected ? selected.schedule_count : '0');
    $('#rm-detail-fare').text(formatMoney(route.fair_price, route.currency_symbol));

    state.metaStops = data.stops || [];
    state.metaFares = data.fares || [];
    $('#rm-add-stop, #rm-add-fare').prop('disabled', !state.selectedKey);

    $('#rm-stops-list').html(state.metaStops.length ? state.metaStops.map(function (s) {
      return '<li class="list-group-item rm-side-row">' +
        '<div class="rm-side-row__main"><div class="fw-semibold">' + escHtml(s.name) + '</div>' +
        '<div class="small text-muted">' + escHtml(s.category) + (s.location ? ' - ' + escHtml(s.location) : '') +
        (s.duration ? ' - ' + escHtml(s.duration) : '') + '</div></div>' +
        '<button type="button" class="btn btn-soft-danger btn-sm rm-stop-delete" data-id="' + s.id + '" title="Remove"><i class="uil uil-trash-alt"></i></button></li>';
    }).join('') : '<li class="list-group-item text-muted small">No stops linked yet.</li>');

    $('#rm-fares-list').html(state.metaFares.length ? state.metaFares.map(function (f) {
      return '<li class="list-group-item rm-side-row">' +
        '<div class="rm-side-row__main"><span class="fw-semibold">' + escHtml(f.class) + '</span><br>' +
        '<span class="text-muted small">' + escHtml(formatMoney(f.amount, '')) + ' ' + escHtml(f.currency) + '</span></div>' +
        '<div class="d-flex gap-1">' +
        '<button type="button" class="btn btn-soft-secondary btn-sm rm-fare-edit" data-id="' + f.id + '" title="Edit"><i class="uil uil-edit"></i></button>' +
        '<button type="button" class="btn btn-soft-danger btn-sm rm-fare-delete" data-id="' + f.id + '" title="Remove"><i class="uil uil-trash-alt"></i></button></div></li>';
    }).join('') : '<li class="list-group-item text-muted small">No class fares yet - showing legacy route price above.</li>');

    state.vehicles = data.vehicles || [];
    var opts = state.vehicles.map(function (v) {
      return '<option value="' + escHtml(v) + '">' + escHtml(v) + '</option>';
    }).join('');
    $('#rm-schedule-vehicle').html(opts || '<option value="">No vehicles</option>');
  }

  function loadRoutes() {
    return $.getJSON('fetch_route_manager_list.php').then(function (data) {
      state.ready = !!data.ready;
      state.routes = data.routes || [];
      if (!state.ready) {
        $('#rm-schema-banner').removeClass('d-none');
      }
      if (data.error) {
        toastErr('Could not load routes', data.detail || data.error);
      }
      renderRoutes();
      if (!state.selectedKey && state.routes.length) {
        selectRoute(state.routes[0].route_key);
      }
    }).fail(function () {
      $('#rm-route-list').html('<div class="text-danger small p-3">Could not load routes. Refresh the page.</div>');
    });
  }

  function loadSchedules() {
    if (!state.selectedKey) return $.when();
    var week = state.weekStart ? isoDate(state.weekStart) : isoDate(mondayOf(new Date()));
    return $.getJSON('fetch_route_schedules.php', { route_key: state.selectedKey, week: week }).then(function (data) {
      renderScheduleBoard(data);
      if (data.week_start) {
        state.weekStart = parseWeekStart(data.week_start);
      }
    });
  }

  function loadMeta() {
    if (!state.selectedKey) return $.when();
    return $.getJSON('fetch_route_manager_meta.php', { route_key: state.selectedKey }).then(renderMeta);
  }

  function selectRoute(key) {
    state.selectedKey = key;
    var route = state.routes.find(function (r) { return r.route_key === key; });
    $('#rm-schedule-route-label').text(route ? route.label : 'Select a route');
    $('#rm-add-schedule').prop('disabled', !key);
    renderRoutes();
    return $.when(loadSchedules(), loadMeta());
  }

  function openScheduleModal(editTrip) {
    if (!state.selectedKey) return;
    $('#rm-schedule-id').val(editTrip ? editTrip.id : '');
    $('#rm-schedule-modal-title').text(editTrip ? 'Edit departure' : 'Add departure');
    $('#rm-schedule-type').val(editTrip ? editTrip.schedule_type : 'weekly');
    $('#rm-schedule-day').val(editTrip ? editTrip.day_of_week : 'Mon');
    $('#rm-schedule-date').val(editTrip ? editTrip.trip_date : '');
    $('#rm-schedule-vehicle').val(editTrip ? editTrip.vehicle_name : (state.vehicles[0] || ''));
    $('#rm-schedule-reporting').val((editTrip && editTrip.reporting_time) ? editTrip.reporting_time.slice(0, 5) : '');
    $('#rm-schedule-departure').val((editTrip && editTrip.departure_time) ? editTrip.departure_time.slice(0, 5) : '');
    $('#rm-schedule-arrival').val((editTrip && editTrip.arrival_time) ? editTrip.arrival_time.slice(0, 5) : '');
    $('#rm-schedule-notes').val(editTrip ? editTrip.notes : '');
    toggleScheduleTypeFields();
    new bootstrap.Modal(document.getElementById('rm-schedule-modal')).show();
  }

  function toggleScheduleTypeFields() {
    var type = $('#rm-schedule-type').val();
    $('#rm-schedule-day-wrap').toggleClass('d-none', type !== 'weekly');
    $('#rm-schedule-date-wrap').toggleClass('d-none', type !== 'once');
  }

  function saveSchedule() {
    var payload = {
      id: parseInt($('#rm-schedule-id').val(), 10) || 0,
      route_key: state.selectedKey,
      schedule_type: $('#rm-schedule-type').val(),
      day_of_week: $('#rm-schedule-day').val(),
      trip_date: $('#rm-schedule-date').val(),
      vehicle_name: $('#rm-schedule-vehicle').val(),
      reporting_time: $('#rm-schedule-reporting').val(),
      departure_time: $('#rm-schedule-departure').val(),
      arrival_time: $('#rm-schedule-arrival').val(),
      notes: $('#rm-schedule-notes').val()
    };
    $.ajax({
      url: 'save_route_schedule.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(payload)
    }).done(function () {
      bootstrap.Modal.getInstance(document.getElementById('rm-schedule-modal')).hide();
      toastOk('Departure saved');
      loadRoutes().then(function () {
        return selectRoute(state.selectedKey);
      });
    }).fail(function (xhr) {
      var msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Could not save departure';
      toastErr('Save failed', msg);
    });
  }

  function loadStopCatalog() {
    return $.getJSON('fetch_route_manager_stops_catalog.php').then(function (data) {
      state.stopCatalog = data.stops || [];
      var opts = '<option value=""> - Create new below - </option>' + state.stopCatalog.map(function (s) {
        return '<option value="' + s.id + '">' + escHtml(s.name) + ' (' + escHtml(s.category) + ')</option>';
      }).join('');
      $('#rm-stop-catalog').html(opts);
    });
  }

  function openStopModal() {
    if (!state.selectedKey) return;
    $('#rm-stop-link-id').val('');
    $('#rm-stop-name, #rm-stop-category, #rm-stop-location, #rm-stop-duration').val('');
    $('#rm-stop-catalog').val('');
    loadStopCatalog().then(function () {
      new bootstrap.Modal(document.getElementById('rm-stop-modal')).show();
    });
  }

  function saveStop() {
    var catalogId = parseInt($('#rm-stop-catalog').val(), 10) || 0;
    var payload = {
      route_key: state.selectedKey,
      stop_id: catalogId,
      stop_name: $('#rm-stop-name').val(),
      category: $('#rm-stop-category').val(),
      location: $('#rm-stop-location').val(),
      duration: $('#rm-stop-duration').val()
    };
    $.ajax({
      url: 'save_route_stop.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(payload)
    }).done(function () {
      bootstrap.Modal.getInstance(document.getElementById('rm-stop-modal')).hide();
      toastOk('Stop linked');
      selectRoute(state.selectedKey);
    }).fail(function () { toastErr('Could not save stop'); });
  }

  function deleteStop(id) {
    $.ajax({
      url: 'delete_route_stop.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ id: id })
    }).done(function () {
      toastOk('Stop removed');
      selectRoute(state.selectedKey);
    }).fail(function () { toastErr('Delete failed'); });
  }

  function openFareModal(fare) {
    if (!state.selectedKey) return;
    $('#rm-fare-id').val(fare ? fare.id : '');
    $('#rm-fare-class').val(fare ? fare.class : '');
    $('#rm-fare-amount').val(fare ? fare.amount : '');
    $('#rm-fare-currency').val(fare ? fare.currency : '/=');
    new bootstrap.Modal(document.getElementById('rm-fare-modal')).show();
  }

  function saveFare() {
    var payload = {
      id: parseInt($('#rm-fare-id').val(), 10) || 0,
      route_key: state.selectedKey,
      class: $('#rm-fare-class').val(),
      amount: parseFloat($('#rm-fare-amount').val()) || 0,
      currency: $('#rm-fare-currency').val()
    };
    $.ajax({
      url: 'save_route_fare.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify(payload)
    }).done(function () {
      bootstrap.Modal.getInstance(document.getElementById('rm-fare-modal')).hide();
      toastOk('Fare saved');
      loadRoutes().then(function () { return selectRoute(state.selectedKey); });
    }).fail(function () { toastErr('Could not save fare'); });
  }

  function deleteFare(id) {
    $.ajax({
      url: 'delete_route_fare.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({ id: id })
    }).done(function () {
      toastOk('Fare removed');
      loadRoutes().then(function () { return selectRoute(state.selectedKey); });
    }).fail(function () { toastErr('Delete failed'); });
  }

  function deleteSchedule(id) {
    Swal.fire({
      icon: 'warning',
      title: 'Remove departure?',
      text: 'This removes the template from the route manager board.',
      showCancelButton: true,
      confirmButtonText: 'Remove'
    }).then(function (result) {
      if (!result.isConfirmed) return;
      $.ajax({
        url: 'delete_route_schedule.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id: id })
      }).done(function () {
        toastOk('Departure removed');
        loadRoutes().then(function () {
          return selectRoute(state.selectedKey);
        });
      }).fail(function () {
        toastErr('Delete failed');
      });
    });
  }

  $(function () {
    state.weekStart = mondayOf(new Date());

    $('#rm-route-search').on('input', renderRoutes);

    $('#rm-route-list').on('click', '.rm-route-item', function () {
      selectRoute($(this).data('route-key'));
    });

    $('#rm-week-prev').on('click', function () { shiftWeek(-1); });
    $('#rm-week-next').on('click', function () { shiftWeek(1); });
    $('#rm-add-schedule').on('click', function () { openScheduleModal(null); });
    $('#rm-schedule-type').on('change', toggleScheduleTypeFields);
    $('#rm-schedule-save').on('click', saveSchedule);

    $('#rm-schedule-board').on('click', '.rm-trip-delete', function () {
      deleteSchedule(parseInt($(this).data('id'), 10));
    });

    $('#rm-add-stop').on('click', openStopModal);
    $('#rm-stop-save').on('click', saveStop);
    $('#rm-stops-list').on('click', '.rm-stop-delete', function () {
      deleteStop(parseInt($(this).data('id'), 10));
    });

    $('#rm-add-fare').on('click', function () { openFareModal(null); });
    $('#rm-fare-save').on('click', saveFare);
    $('#rm-fares-list').on('click', '.rm-fare-delete', function () {
      deleteFare(parseInt($(this).data('id'), 10));
    });
    $('#rm-fares-list').on('click', '.rm-fare-edit', function () {
      var id = parseInt($(this).data('id'), 10);
      var fare = state.metaFares.find(function (f) { return f.id === id; });
      openFareModal(fare || null);
    });

    loadStopCatalog();
    loadRoutes();
  });
}(jQuery));
