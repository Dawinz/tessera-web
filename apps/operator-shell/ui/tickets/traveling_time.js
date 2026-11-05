(function () {
  var root = document.getElementById('tds-time-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var host = document.getElementById('tds-time-root-body') || root;
  var preferred = root.getAttribute('data-route-day-id') || '';
  if (preferred === '0') preferred = '';

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
      body: opts.body ? JSON.stringify(opts.body) : undefined
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

  host.innerHTML =
    '<div class="tds-toolbar">' +
    '<div class="tds-field" style="flex:1 1 18rem"><label class="tds-label" for="tds-time-day">Trip template</label>' +
    '<select class="tds-select" id="tds-time-day"><option value="">Choose a trip</option></select></div>' +
    '<button type="button" class="tds-btn tds-btn-primary" id="tds-time-save">Save times</button></div>' +
    '<div class="tds-alert tds-alert-danger" id="tds-time-err" hidden role="alert"></div>' +
    '<div class="tds-alert tds-alert-success" id="tds-time-ok" hidden role="status"></div>' +
    '<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">' +
    '<p class="tds-muted" id="tds-time-loading">Loading…</p>' +
    '<div class="tds-empty" id="tds-time-empty" hidden><p class="tds-empty-title">No stops on this trip</p>' +
    '<p class="tds-muted">Add stops under Register route points, then open this trip again.</p></div>' +
    '<div class="table-responsive" id="tds-time-table-wrap" hidden><table class="tds-table"><thead><tr>' +
    '<th>Stop</th><th>Kind</th><th>Departure</th><th>Reporting</th><th>Arrival</th>' +
    '</tr></thead><tbody id="tds-time-rows"></tbody></table></div></div>';

  var dayEl = document.getElementById('tds-time-day');
  var errEl = document.getElementById('tds-time-err');
  var okEl = document.getElementById('tds-time-ok');
  var loadingEl = document.getElementById('tds-time-loading');
  var emptyEl = document.getElementById('tds-time-empty');
  var wrapEl = document.getElementById('tds-time-table-wrap');
  var rowsEl = document.getElementById('tds-time-rows');

  function showError(msg) {
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
  }

  function fillDays(templates, selected) {
    var current = selected || dayEl.value;
    dayEl.innerHTML = '<option value="">Choose a trip</option>';
    (templates || []).forEach(function (item) {
      var opt = document.createElement('option');
      opt.value = String(item.id);
      opt.textContent = item.label || item.day || String(item.id);
      dayEl.appendChild(opt);
    });
    if (current) dayEl.value = String(current);
  }

  function render(items) {
    loadingEl.hidden = true;
    if (!dayEl.value) {
      emptyEl.hidden = true;
      wrapEl.hidden = true;
      rowsEl.innerHTML = '';
      return;
    }
    if (!items.length) {
      emptyEl.hidden = false;
      wrapEl.hidden = true;
      rowsEl.innerHTML = '';
      return;
    }
    emptyEl.hidden = true;
    wrapEl.hidden = false;
    rowsEl.innerHTML = items.map(function (item) {
      return '<tr data-id="' + esc(item.id) + '">' +
        '<td>' + esc(item.name) + '</td>' +
        '<td>' + esc(item.status) + '</td>' +
        '<td><input class="tds-input" type="time" data-k="departure" value="' + esc(item.departure || '') + '"></td>' +
        '<td><input class="tds-input" type="time" data-k="reporting_time" value="' + esc(item.reporting_time || '') + '"></td>' +
        '<td><input class="tds-input" type="time" data-k="arrival" value="' + esc(item.arrival || '') + '"></td>' +
        '</tr>';
    }).join('');
  }

  function load(dayId) {
    showError('');
    loadingEl.hidden = false;
    var url = '/tickets/api/console/traveling_times.php';
    if (dayId) url += '?route_day_id=' + encodeURIComponent(dayId);
    return api(url).then(function (data) {
      fillDays(data.templates || [], dayId || data.route_day_id);
      render(data.items || []);
      return data;
    }).catch(function (err) {
      loadingEl.hidden = true;
      showError(err.message || 'Could not load traveling times.');
    });
  }

  dayEl.addEventListener('change', function () {
    showOk('');
    load(dayEl.value);
  });
  document.getElementById('tds-time-save').addEventListener('click', function () {
    if (!dayEl.value) {
      showError('Choose a trip template.');
      return;
    }
    var points = [];
    rowsEl.querySelectorAll('tr').forEach(function (tr) {
      var point = { id: Number(tr.getAttribute('data-id')) };
      tr.querySelectorAll('input').forEach(function (input) {
        point[input.getAttribute('data-k')] = input.value;
      });
      points.push(point);
    });
    if (!points.length) {
      showError('This trip has no stops to save.');
      return;
    }
    var btn = document.getElementById('tds-time-save');
    btn.disabled = true;
    api('/tickets/api/console/traveling_time_save.php', {
      method: 'POST',
      body: { route_day_id: Number(dayEl.value), points: points }
    }).then(function () {
      showOk('Times saved.');
      return load(dayEl.value);
    }).catch(function (err) {
      showError(err.message || 'Could not save times.');
    }).then(function () {
      btn.disabled = false;
    });
  });

  load(preferred).then(function (data) {
    if (!preferred && data && data.templates && data.templates[0] && !dayEl.value) {
      dayEl.value = String(data.templates[0].id);
      return load(dayEl.value);
    }
  });
})();
