(function () {
  var root = document.getElementById('tds-accounts-particular-single-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var host = document.getElementById('tds-accounts-particular-single-root-body') || root;
  var initial = root.getAttribute('data-particular') || '';

  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function api(url) {
    return fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body || body.ok === false) {
          throw new Error((body && body.error && body.error.message) || 'Request failed');
        }
        return body.data || {};
      });
    });
  }

  host.innerHTML =
    '<form class="tds-toolbar" id="tds-accounts-particular-single-form">' +
    '<div class="tds-field" style="flex:1 1 16rem"><label class="tds-label" for="tds-accounts-particular-single-name">Particular</label>' +
    '<select class="tds-select" id="tds-accounts-particular-single-name"><option value="">Choose a particular</option></select></div>' +
    '<div class="tds-field"><label class="tds-label" for="tds-accounts-particular-single-start">From</label><input class="tds-input" id="tds-accounts-particular-single-start" type="date"></div>' +
    '<div class="tds-field"><label class="tds-label" for="tds-accounts-particular-single-end">To</label><input class="tds-input" id="tds-accounts-particular-single-end" type="date"></div>' +
    '<div class="tds-field"><label class="tds-label" for="tds-accounts-particular-single-currency">Currency</label>' +
    '<select class="tds-select" id="tds-accounts-particular-single-currency"><option value="">Any currency</option></select></div>' +
    '<button type="submit" class="tds-btn tds-btn-primary" id="tds-accounts-particular-single-go">Show lines</button></form>' +
    '<div class="tds-alert tds-alert-danger" id="tds-accounts-particular-single-err" hidden role="alert"></div>' +
    '<div class="tds-table-panel" style="margin-top:var(--tds-space-4)"><p class="tds-muted" id="tds-accounts-particular-single-loading" hidden>Loading…</p>' +
    '<div class="tds-empty" id="tds-accounts-particular-single-empty" hidden><p class="tds-empty-title">No ledger lines</p><p class="tds-muted">Try another particular or a wider date range.</p></div>' +
    '<div class="table-responsive" id="tds-accounts-particular-single-table" hidden><table class="tds-table"><thead><tr><th>Date</th><th>Vehicle</th><th>Route</th><th>Category</th><th>Amount</th><th>Currency</th><th>Detail</th></tr></thead><tbody id="tds-accounts-particular-single-rows"></tbody></table></div></div>';

  var nameEl = document.getElementById('tds-accounts-particular-single-name');
  var currencyEl = document.getElementById('tds-accounts-particular-single-currency');
  var errEl = document.getElementById('tds-accounts-particular-single-err');
  var loadingEl = document.getElementById('tds-accounts-particular-single-loading');
  var emptyEl = document.getElementById('tds-accounts-particular-single-empty');
  var tableEl = document.getElementById('tds-accounts-particular-single-table');
  var rowsEl = document.getElementById('tds-accounts-particular-single-rows');

  function showError(msg) {
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }

  function loadLines() {
    if (!nameEl.value) {
      emptyEl.hidden = false;
      tableEl.hidden = true;
      rowsEl.innerHTML = '';
      return Promise.resolve();
    }
    loadingEl.hidden = false;
    emptyEl.hidden = true;
    var params = new URLSearchParams();
    params.set('particular', nameEl.value);
    var start = document.getElementById('tds-accounts-particular-single-start').value;
    var end = document.getElementById('tds-accounts-particular-single-end').value;
    if (start) params.set('start', start);
    if (end) params.set('end', end);
    if (currencyEl.value) params.set('currency', currencyEl.value);
    return api('/accounts/api/console/particular_lines.php?' + params.toString())
      .then(function (data) {
        loadingEl.hidden = true;
        var items = data.items || [];
        if (!items.length) {
          emptyEl.hidden = false;
          tableEl.hidden = true;
          rowsEl.innerHTML = '';
          return;
        }
        emptyEl.hidden = true;
        tableEl.hidden = false;
        rowsEl.innerHTML = items
          .map(function (row) {
            return (
              '<tr><td>' + esc(row.trip_date) + '</td><td>' + esc(row.bus) + '</td><td>' + esc(row.route_name) +
              '</td><td>' + esc(row.category) + '</td><td>' + esc(row.price) + '</td><td>' + esc(row.currency) +
              '</td><td>' + esc(row.detail) + '</td></tr>'
            );
          })
          .join('');
      })
      .catch(function (err) {
        loadingEl.hidden = true;
        showError(err.message || 'Could not load ledger lines.');
      });
  }

  api('/accounts/api/console/setup_bootstrap.php')
    .then(function (data) {
      (data.particulars || []).forEach(function (row) {
        var opt = document.createElement('option');
        opt.value = row.name;
        opt.textContent = row.label || row.name;
        nameEl.appendChild(opt);
      });
      (data.currencies || []).forEach(function (row) {
        var opt = document.createElement('option');
        opt.value = row.name;
        opt.textContent = row.name;
        currencyEl.appendChild(opt);
      });
      if (initial) {
        nameEl.value = initial;
        return loadLines();
      }
    })
    .catch(function (err) {
      showError(err.message || 'Could not load particulars.');
    });

  document.getElementById('tds-accounts-particular-single-form').addEventListener('submit', function (ev) {
    ev.preventDefault();
    showError('');
    loadLines();
  });
})();
