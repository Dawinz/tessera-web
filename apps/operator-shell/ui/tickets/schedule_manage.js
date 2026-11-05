/**
 * T-P1-11 Manage trip template.
 * Read path: /tickets/api/console/trip.php (schedule_trip_from_request).
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-manage-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var host = document.getElementById('tds-manage-root-body') || root;

  var routeDayId = root.getAttribute('data-route-day-id') || '';
  var tripDate = root.getAttribute('data-trip-date') || '';
  var routeId = root.getAttribute('data-route-id') || '';
  var params = new URLSearchParams(window.location.search);
  if (!routeDayId) routeDayId = params.get('route_day_id') || '';
  if (!tripDate) tripDate = params.get('trip_date') || '';
  if (!routeId) routeId = params.get('route_id') || '';

  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function api(url, options) {
    var opts = options || {};
    var headers = { Accept: 'application/json' };
    if (opts.body) headers['Content-Type'] = 'application/json';
    return fetch(url, {
      method: opts.method || 'GET',
      credentials: 'same-origin',
      headers: headers,
      body: opts.body ? JSON.stringify(opts.body) : undefined,
    }).then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body || body.ok === false) {
          var err = new Error((body && body.error && body.error.message) || 'Request failed');
          throw err;
        }
        return body.data || {};
      });
    });
  }

  function shell(inner) {
    host.innerHTML =
      '<div class="tds-alert tds-alert-danger" id="tds-manage-err" hidden role="alert"></div>' +
      '<div class="tds-alert tds-alert-success" id="tds-manage-ok" hidden role="status"></div>' +
      inner;
  }

  function showError(msg) {
    var el = document.getElementById('tds-manage-err');
    if (!el) return;
    el.hidden = !msg;
    el.textContent = msg || '';
  }

  if (!routeDayId || !tripDate) {
    shell(
      '<div class="tds-empty"><p class="tds-empty-title">No departure selected</p>' +
        '<p class="tds-muted">Open a trip from the calendar to manage its weekday template.</p>' +
        '<div class="tds-row" style="margin-top:var(--tds-space-3)">' +
        '<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=schedule.php">Back to calendar</a>' +
        '<a class="tds-btn tds-btn-primary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=register_trip.php">Register trip</a>' +
        '</div></div>'
    );
    return;
  }

  shell('<p class="tds-muted" id="tds-manage-loading">Loading trip template…</p><div id="tds-manage-detail"></div>');

  var q = new URLSearchParams({
    route_day_id: routeDayId,
    trip_date: tripDate,
    route_id: routeId,
  });

  api('/tickets/api/console/trip.php?' + q.toString())
    .then(function (data) {
      var trip = data.trip || {};
      var can = !!data.can_manage_template;
      var book =
        '/operator/app.php?module=tickets&page=schedule_book.php&route_day_id=' +
        encodeURIComponent(routeDayId) +
        '&trip_date=' +
        encodeURIComponent(tripDate) +
        '&route_id=' +
        encodeURIComponent(routeId || trip.route_id || '');
      var actions = can
        ? '<button type="button" class="tds-btn tds-btn-secondary" id="tds-manage-toggle">Toggle active status</button>' +
          '<button type="button" class="tds-btn tds-btn-danger" id="tds-manage-delete">Delete trip template</button>'
        : '<p class="tds-muted">Only administrators can change this template.</p>';
      document.getElementById('tds-manage-detail').innerHTML =
        '<div class="tds-row" style="align-items:stretch">' +
        '<div class="tds-card" style="flex:1 1 22rem"><div class="tds-card-header"><h2 class="tds-card-title">' +
        esc(trip.route_name || 'Trip template') +
        '</h2></div><div class="tds-card-body">' +
        row('Trip date', (trip.trip_date_display || trip.trip_date || '') + ' · ' + (trip.weekday || '')) +
        row('Bus', trip.bus) +
        row('Weekday template', trip.weekday) +
        row('Departure', trip.departure || '—') +
        row('Arrival', trip.arrival || '—') +
        row('Status', trip.status || '—') +
        row('Seats booked', (trip.seats_booked || 0) + ' / ' + (trip.seats_total || 0)) +
        row('Route', trip.route_name) +
        '</div></div>' +
        '<div class="tds-card" style="flex:0 1 18rem"><div class="tds-card-header"><h2 class="tds-card-title">Actions</h2></div><div class="tds-card-body"><div class="tds-stack">' +
        actions +
        '<a class="tds-btn tds-btn-secondary" href="/operator/app.php?module=tickets&amp;page=register_trip.php">Open trip registration</a>' +
        '<a class="tds-btn tds-btn-primary" href="' +
        esc(book) +
        '">Book seats for this date</a>' +
        '<a class="tds-btn tds-btn-ghost" href="/operator/app.php?module=tickets&amp;page=schedule.php">Back to calendar</a>' +
        '</div></div></div></div>';

      var toggle = document.getElementById('tds-manage-toggle');
      var del = document.getElementById('tds-manage-delete');
      if (toggle) {
        toggle.addEventListener('click', function () {
          toggle.disabled = true;
          api('/tickets/api/console/trip_template_status.php', {
            method: 'POST',
            body: { id: Number(trip.route_day_id || routeDayId) },
          })
            .then(function () {
              window.location.href =
                '/operator/app.php?module=tickets&page=schedule_manage.php&route_day_id=' +
                encodeURIComponent(routeDayId) +
                '&trip_date=' +
                encodeURIComponent(tripDate) +
                '&route_id=' +
                encodeURIComponent(routeId || '');
            })
            .catch(function (err) {
              showError(err.message || 'Could not change status.');
              toggle.disabled = false;
            });
        });
      }
      if (del) {
        del.addEventListener('click', function () {
          if (!window.confirm('Delete this trip template?')) return;
          del.disabled = true;
          api('/tickets/api/console/trip_template_delete.php', {
            method: 'POST',
            body: { id: Number(trip.route_day_id || routeDayId) },
          })
            .then(function () {
              window.location.href = '/operator/app.php?module=tickets&page=schedule.php';
            })
            .catch(function (err) {
              showError(err.message || 'Could not delete the template.');
              del.disabled = false;
            });
        });
      }
    })
    .catch(function (err) {
      var loading = document.getElementById('tds-manage-loading');
      if (loading) loading.hidden = true;
      showError(err.message || 'This departure could not be found.');
    });

  function row(label, value) {
    return (
      '<p style="margin:0 0 0.45rem"><span class="tds-muted">' +
      esc(label) +
      '</span><br><strong>' +
      esc(value || '—') +
      '</strong></p>'
    );
  }
})();
