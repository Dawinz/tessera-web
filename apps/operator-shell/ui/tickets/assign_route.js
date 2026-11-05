(function () {
  var root = document.getElementById('tds-assign-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var host = document.getElementById('tds-assign-root-body') || root;

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
          throw new Error((body && body.error && body.error.message) || 'Request failed');
        }
        return body.data || {};
      });
    });
  }

  host.innerHTML =
    '<div class="tds-toolbar">' +
    '<div class="tds-field" style="flex:1 1 16rem"><label class="tds-label" for="tds-assign-user">Staff member</label>' +
    '<select class="tds-select" id="tds-assign-user"><option value="">Choose a person</option></select></div>' +
    '<div class="tds-field" style="flex:1 1 16rem"><label class="tds-label" for="tds-assign-q">Search routes</label>' +
    '<input class="tds-input" id="tds-assign-q" type="search" placeholder="Search routes"></div></div>' +
    '<div class="tds-alert tds-alert-danger" id="tds-assign-err" hidden role="alert"></div>' +
    '<div class="tds-alert tds-alert-success" id="tds-assign-ok" hidden role="status"></div>' +
    '<div class="tds-card" style="margin-top:var(--tds-space-4)"><div class="tds-card-body">' +
    '<p class="tds-muted" id="tds-assign-loading">Loading…</p>' +
    '<div class="tds-empty" id="tds-assign-empty" hidden><p class="tds-empty-title">No routes yet</p></div>' +
    '<div id="tds-assign-list"></div></div></div>';

  var userEl = document.getElementById('tds-assign-user');
  var qEl = document.getElementById('tds-assign-q');
  var errEl = document.getElementById('tds-assign-err');
  var okEl = document.getElementById('tds-assign-ok');
  var loadingEl = document.getElementById('tds-assign-loading');
  var emptyEl = document.getElementById('tds-assign-empty');
  var listEl = document.getElementById('tds-assign-list');
  var routes = [];

  function showError(msg) {
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
  }

  function render() {
    var q = qEl.value.trim().toLowerCase();
    var shown = routes.filter(function (route) {
      if (!q) return true;
      return (route.fullname + ' ' + route.route_name).toLowerCase().indexOf(q) !== -1;
    });
    loadingEl.hidden = true;
    if (!shown.length) {
      emptyEl.hidden = false;
      listEl.innerHTML = '';
      return;
    }
    emptyEl.hidden = true;
    var disabled = userEl.value ? '' : ' disabled';
    listEl.innerHTML = shown.map(function (route) {
      var label = route.fullname || route.route_name || ('Route ' + route.id);
      if (route.route_name && route.fullname) label += ' · ' + route.route_name;
      return '<label class="tds-row" style="gap:var(--tds-space-3);padding:var(--tds-space-2) 0">' +
        '<input type="checkbox" data-route="' + esc(route.id) + '"' + (route.assigned ? ' checked' : '') + disabled + '>' +
        '<span>' + esc(label) + '</span></label>';
    }).join('');
  }

  function load() {
    showError('');
    loadingEl.hidden = false;
    var url = '/tickets/api/console/assign_routes.php';
    if (userEl.value) url += '?username=' + encodeURIComponent(userEl.value);
    var keepUser = userEl.value;
    return api(url).then(function (data) {
      if (!userEl.options || userEl.options.length < 2) {
        (data.users || []).forEach(function (user) {
          var opt = document.createElement('option');
          opt.value = user.username;
          opt.textContent = user.username;
          userEl.appendChild(opt);
        });
      }
      if (keepUser) userEl.value = keepUser;
      routes = data.routes || [];
      render();
    }).catch(function (err) {
      loadingEl.hidden = true;
      showError(err.message || 'Could not load route assignments.');
    });
  }

  userEl.addEventListener('change', function () {
    showOk('');
    load();
  });
  qEl.addEventListener('input', render);
  listEl.addEventListener('change', function (ev) {
    var box = ev.target;
    if (!box || box.type !== 'checkbox') return;
    if (!userEl.value) {
      box.checked = false;
      showError('Choose a staff member first.');
      return;
    }
    var routePk = Number(box.getAttribute('data-route'));
    box.disabled = true;
    api('/tickets/api/console/assign_route_save.php', {
      method: 'POST',
      body: { username: userEl.value, route_pk: routePk, assigned: box.checked }
    }).then(function () {
      routes.forEach(function (route) {
        if (Number(route.id) === routePk) route.assigned = box.checked;
      });
      showOk(box.checked ? 'Route assigned.' : 'Route removed.');
    }).catch(function (err) {
      box.checked = !box.checked;
      showError(err.message || 'Could not update the assignment.');
    }).then(function () {
      box.disabled = false;
    });
  });

  load();
})();
