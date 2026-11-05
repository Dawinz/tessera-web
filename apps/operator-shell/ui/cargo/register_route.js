(function () {
  var root = document.getElementById('tds-cargo-route-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

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
          err.status = res.status;
          throw err;
        }
        return body.data || {};
      });
    });
  }

  var errEl = document.getElementById('tds-cargo-route-err');
  var okEl = document.getElementById('tds-cargo-route-ok');
  var locLoading = document.getElementById('tds-cargo-location-loading');
  var locEmpty = document.getElementById('tds-cargo-location-empty');
  var locWrap = document.getElementById('tds-cargo-location-wrap');
  var locBody = document.getElementById('tds-cargo-locations');
  var routeLoading = document.getElementById('tds-cargo-route-loading');
  var routeEmpty = document.getElementById('tds-cargo-route-empty');
  var routeWrap = document.getElementById('tds-cargo-route-wrap');
  var routeBody = document.getElementById('tds-cargo-routes');
  var form = document.getElementById('tds-cargo-route-form');
  var lockNote = document.getElementById('tds-cargo-route-lock');
  var state = { locations: [], currencies: [], routes: {} };

  function showError(msg) {
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
  }

  function fillSelect(id, items, valueKey, labelKey, placeholder) {
    var select = document.getElementById(id);
    var current = select.value;
    select.innerHTML = '';
    var blank = document.createElement('option');
    blank.value = '';
    blank.textContent = placeholder;
    select.appendChild(blank);
    items.forEach(function (item) {
      var opt = document.createElement('option');
      opt.value = String(item[valueKey] || '');
      opt.textContent = String(item[labelKey] || opt.value);
      select.appendChild(opt);
    });
    if (current) select.value = current;
  }

  function paintChoices() {
    fillSelect('tds-cargo-route-start', state.locations, 'name', 'name', 'Starting point');
    fillSelect('tds-cargo-route-end', state.locations, 'name', 'name', 'Ending point');
    var currencies = state.currencies.map(function (row) {
      return {
        country_stored: row.country_stored,
        label: row.name + ' · ' + (row.country_label || row.country)
      };
    });
    fillSelect('tds-cargo-route-currency', currencies, 'country_stored', 'label', 'Currency');
  }

  function renderLocations() {
    locLoading.hidden = true;
    if (!state.locations.length) {
      locEmpty.hidden = false;
      locWrap.hidden = true;
      locBody.innerHTML = '';
      return;
    }
    locEmpty.hidden = true;
    locWrap.hidden = false;
    locBody.innerHTML = state.locations.map(function (row) {
      return '<tr><td>' + esc(row.name) + '</td><td class="tds-table-actions">' +
        '<button type="button" class="tds-btn tds-btn-danger tds-btn-sm" data-loc="' + esc(row.id) + '">Delete</button></td></tr>';
    }).join('');
  }

  function renderRoutes(items) {
    state.routes = {};
    routeLoading.hidden = true;
    if (!items.length) {
      routeEmpty.hidden = false;
      routeWrap.hidden = true;
      routeBody.innerHTML = '';
      return;
    }
    routeEmpty.hidden = true;
    routeWrap.hidden = false;
    routeBody.innerHTML = items.map(function (row) {
      state.routes[row.id] = row;
      var contacts = [row.contact_1, row.contact_2].filter(Boolean).join(' · ');
      return '<tr><td>' + esc(row.fullname || row.display_name) + '</td><td>' + esc(row.initial) +
        '</td><td>' + esc(row.currency_symbol || row.currency) + '</td><td>' + esc(contacts) +
        '</td><td class="tds-table-actions">' +
        '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-edit="' + esc(row.id) + '">Edit</button> ' +
        '<button type="button" class="tds-btn tds-btn-danger tds-btn-sm" data-del="' + esc(row.id) + '">Delete</button></td></tr>';
    }).join('');
  }

  function setLocked(locked) {
    ['tds-cargo-route-start', 'tds-cargo-route-end', 'tds-cargo-route-initial'].forEach(function (id) {
      document.getElementById(id).disabled = !!locked;
    });
    lockNote.hidden = !locked;
  }

  function hideForm() {
    form.hidden = true;
    form.reset();
    document.getElementById('tds-cargo-route-id').value = '';
    setLocked(false);
  }

  function showForm(item) {
    showError('');
    paintChoices();
    form.hidden = false;
    document.getElementById('tds-cargo-route-id').value = item ? String(item.id) : '';
    document.getElementById('tds-cargo-route-start').value = item ? item.start : '';
    document.getElementById('tds-cargo-route-end').value = item ? item.end : '';
    document.getElementById('tds-cargo-route-currency').value = item ? item.currency : '';
    document.getElementById('tds-cargo-route-initial').value = item ? item.initial : '';
    document.getElementById('tds-cargo-route-display').value = item ? item.display_name : '';
    document.getElementById('tds-cargo-route-c1').value = item ? item.contact_1 : '';
    document.getElementById('tds-cargo-route-c2').value = item ? item.contact_2 : '';
    document.getElementById('tds-cargo-route-a1').value = item ? item.address_1 : '';
    document.getElementById('tds-cargo-route-a2').value = item ? item.address_2 : '';
    document.getElementById('tds-cargo-route-tobe').value = item ? item.to_be_paid_currency : '';
    document.getElementById('tds-cargo-route-t1').value = item ? item.terms_1 : '';
    document.getElementById('tds-cargo-route-t2').value = item ? item.terms_2 : '';
    setLocked(item && item.identity_locked);
    form.scrollIntoView({ block: 'nearest' });
  }

  function loadRoutes() {
    routeLoading.hidden = false;
    return api('/cargo/api/console/routes.php').then(function (data) {
      renderRoutes(data.items || []);
    });
  }

  function loadBoot() {
    locLoading.hidden = false;
    return api('/cargo/api/console/route_bootstrap.php').then(function (data) {
      state.locations = data.locations || [];
      state.currencies = data.currencies || [];
      renderLocations();
      paintChoices();
    });
  }

  document.getElementById('tds-cargo-location-form').addEventListener('submit', function (ev) {
    ev.preventDefault();
    var name = document.getElementById('tds-cargo-location-name').value.trim();
    if (!name) {
      showError('Enter a location name.');
      return;
    }
    showError('');
    api('/cargo/api/console/location_save.php', { method: 'POST', body: { name: name } })
      .then(function () {
        document.getElementById('tds-cargo-location-name').value = '';
        showOk('Location added.');
        return loadBoot();
      })
      .catch(function (err) {
        showError(err.message || 'Could not add the location.');
      });
  });

  locBody.addEventListener('click', function (ev) {
    var btn = ev.target.closest('button');
    if (!btn) return;
    var id = Number(btn.getAttribute('data-loc'));
    if (!id || !window.confirm('Delete this location?')) return;
    btn.disabled = true;
    api('/cargo/api/console/location_delete.php', { method: 'POST', body: { id: id } })
      .then(function () {
        showOk('Location deleted.');
        return loadBoot();
      })
      .catch(function (err) {
        showError(err.message || 'Could not delete the location.');
        btn.disabled = false;
      });
  });

  document.getElementById('tds-cargo-route-add').addEventListener('click', function () {
    showForm(null);
  });
  document.getElementById('tds-cargo-route-cancel').addEventListener('click', hideForm);
  form.addEventListener('submit', function (ev) {
    ev.preventDefault();
    var payload = {
      id: Number(document.getElementById('tds-cargo-route-id').value || 0),
      start: document.getElementById('tds-cargo-route-start').value,
      end: document.getElementById('tds-cargo-route-end').value,
      currency: document.getElementById('tds-cargo-route-currency').value,
      initial: document.getElementById('tds-cargo-route-initial').value.trim(),
      display_name: document.getElementById('tds-cargo-route-display').value.trim(),
      contact_1: document.getElementById('tds-cargo-route-c1').value.trim(),
      contact_2: document.getElementById('tds-cargo-route-c2').value.trim(),
      address_1: document.getElementById('tds-cargo-route-a1').value.trim(),
      address_2: document.getElementById('tds-cargo-route-a2').value.trim(),
      to_be_paid_currency: document.getElementById('tds-cargo-route-tobe').value.trim(),
      terms_1: document.getElementById('tds-cargo-route-t1').value,
      terms_2: document.getElementById('tds-cargo-route-t2').value
    };
    if (!payload.id) delete payload.id;
    var saveBtn = document.getElementById('tds-cargo-route-save');
    saveBtn.disabled = true;
    api('/cargo/api/console/route_save.php', { method: 'POST', body: payload })
      .then(function () {
        showOk(payload.id ? 'Route saved.' : 'Route added.');
        hideForm();
        return loadRoutes();
      })
      .catch(function (err) {
        showError(err.message || 'Could not save the route.');
      })
      .then(function () {
        saveBtn.disabled = false;
      });
  });

  routeBody.addEventListener('click', function (ev) {
    var btn = ev.target.closest('button');
    if (!btn) return;
    var editId = btn.getAttribute('data-edit');
    var delId = btn.getAttribute('data-del');
    if (editId) {
      showForm(state.routes[editId] || null);
      return;
    }
    if (delId) {
      if (!window.confirm('Delete this route?')) return;
      btn.disabled = true;
      api('/cargo/api/console/route_delete.php', { method: 'POST', body: { id: Number(delId) } })
        .then(function () {
          showOk('Route deleted.');
          return loadRoutes();
        })
        .catch(function (err) {
          showError(err.message || 'Could not delete the route.');
          btn.disabled = false;
        });
    }
  });

  loadBoot().then(loadRoutes).catch(function (err) {
    locLoading.hidden = true;
    routeLoading.hidden = true;
    showError(err.message || 'Could not load routes.');
  });
})();
