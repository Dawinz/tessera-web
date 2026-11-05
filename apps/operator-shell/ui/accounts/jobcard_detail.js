(function () {
  var root = document.getElementById('tds-accounts-jobcard-detail-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');
  var host = document.getElementById('tds-accounts-jobcard-detail-root-body') || root;
  var picked = root.getAttribute('data-card-id') || '';

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
          throw new Error((body && body.error && body.error.message) || 'Request failed');
        }
        return body.data || {};
      });
    });
  }

  host.innerHTML =
    '<div class="tds-toolbar">' +
    '<div class="tds-field" style="flex:1 1 18rem"><label class="tds-label" for="tds-accounts-jobcard-detail-card">Job card</label>' +
    '<select class="tds-select" id="tds-accounts-jobcard-detail-card"><option value="">Choose a job card</option></select></div>' +
    '<a class="tds-btn tds-btn-secondary" href="/operator/app.php?module=accounts&page=jobcard.php">All job cards</a></div>' +
    '<div class="tds-alert tds-alert-danger" id="tds-accounts-jobcard-detail-err" hidden role="alert"></div>' +
    '<div class="tds-alert tds-alert-success" id="tds-accounts-jobcard-detail-ok" hidden role="status"></div>' +
    '<div class="tds-card" id="tds-accounts-jobcard-detail-form" hidden style="margin-top:var(--tds-space-4)"><div class="tds-card-body">' +
    '<h2 class="tds-card-title" id="tds-accounts-jobcard-detail-title">Job card</h2>' +
    '<p class="tds-muted" id="tds-accounts-jobcard-detail-meta"></p>' +
    '<form id="tds-accounts-jobcard-detail-form-el"><div class="tds-row" style="align-items:flex-end">' +
    '<div class="tds-field" style="flex:1 1 10rem"><label class="tds-label" for="tds-accounts-jobcard-detail-bus">Vehicle</label><input class="tds-input" id="tds-accounts-jobcard-detail-bus" required></div>' +
    '<div class="tds-field" style="flex:1 1 10rem"><label class="tds-label" for="tds-accounts-jobcard-detail-item">Item</label><input class="tds-input" id="tds-accounts-jobcard-detail-item" required></div>' +
    '<div class="tds-field" style="flex:1 1 8rem"><label class="tds-label" for="tds-accounts-jobcard-detail-qty">Quantity</label><input class="tds-input" id="tds-accounts-jobcard-detail-qty" type="number" min="1" required></div>' +
    '<div class="tds-field" style="flex:1 1 8rem"><label class="tds-label" for="tds-accounts-jobcard-detail-price">Price</label><input class="tds-input" id="tds-accounts-jobcard-detail-price" type="number" min="1" required></div>' +
    '<div class="tds-field" style="flex:1 1 12rem"><label class="tds-label" for="tds-accounts-jobcard-detail-detail">Detail</label><input class="tds-input" id="tds-accounts-jobcard-detail-detail"></div>' +
    '</div><div class="tds-row" style="margin-top:var(--tds-space-3)"><button type="submit" class="tds-btn tds-btn-primary" id="tds-accounts-jobcard-detail-add">Add line</button></div></form></div></div>' +
    '<div class="tds-table-panel" style="margin-top:var(--tds-space-4)"><p class="tds-muted" id="tds-accounts-jobcard-detail-loading">Loading…</p>' +
    '<div class="tds-empty" id="tds-accounts-jobcard-detail-empty" hidden><p class="tds-empty-title">No lines on this card</p><p class="tds-muted">Add the first garage line above.</p></div>' +
    '<div class="table-responsive" id="tds-accounts-jobcard-detail-table" hidden><table class="tds-table"><thead><tr><th>Vehicle</th><th>Item</th><th>Qty</th><th>Price</th><th>Total</th><th>Detail</th><th></th></tr></thead><tbody id="tds-accounts-jobcard-detail-rows"></tbody></table></div></div>';

  var cardEl = document.getElementById('tds-accounts-jobcard-detail-card');
  var errEl = document.getElementById('tds-accounts-jobcard-detail-err');
  var okEl = document.getElementById('tds-accounts-jobcard-detail-ok');
  var formEl = document.getElementById('tds-accounts-jobcard-detail-form');
  var titleEl = document.getElementById('tds-accounts-jobcard-detail-title');
  var metaEl = document.getElementById('tds-accounts-jobcard-detail-meta');
  var loadingEl = document.getElementById('tds-accounts-jobcard-detail-loading');
  var emptyEl = document.getElementById('tds-accounts-jobcard-detail-empty');
  var tableEl = document.getElementById('tds-accounts-jobcard-detail-table');
  var rowsEl = document.getElementById('tds-accounts-jobcard-detail-rows');

  function showError(msg) {
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
  }

  function paint(data) {
    var keep = cardEl.value || picked;
    if (cardEl.options.length < 2) {
      (data.cards || []).forEach(function (card) {
        var opt = document.createElement('option');
        opt.value = String(card.id);
        opt.textContent = card.label;
        cardEl.appendChild(opt);
      });
    }
    if (keep) cardEl.value = String(keep);
    loadingEl.hidden = true;
    var card = data.card;
    if (!card) {
      formEl.hidden = true;
      emptyEl.hidden = true;
      tableEl.hidden = true;
      rowsEl.innerHTML = '';
      return;
    }
    formEl.hidden = false;
    titleEl.textContent = card.date + ' · ' + card.route_name;
    metaEl.textContent = 'Amount ' + card.amount + (card.vehicles ? ' · ' + card.vehicles : '');
    var entries = data.entries || [];
    if (!entries.length) {
      emptyEl.hidden = false;
      tableEl.hidden = true;
      rowsEl.innerHTML = '';
      return;
    }
    emptyEl.hidden = true;
    tableEl.hidden = false;
    rowsEl.innerHTML = entries
      .map(function (row) {
        return (
          '<tr><td>' + esc(row.bus) + '</td><td>' + esc(row.item) + '</td><td>' + esc(row.qty) +
          '</td><td>' + esc(row.price) + '</td><td>' + esc(row.line_total) + '</td><td>' + esc(row.detail) +
          '</td><td><button type="button" class="tds-btn tds-btn-danger tds-btn-sm" data-del="' + esc(row.id) + '">Delete</button></td></tr>'
        );
      })
      .join('');
  }

  function load(id) {
    showError('');
    loadingEl.hidden = false;
    var url = '/accounts/api/console/job_card.php';
    if (id) url += '?id=' + encodeURIComponent(id);
    return api(url)
      .then(paint)
      .catch(function (err) {
        loadingEl.hidden = true;
        showError(err.message || 'Could not load this job card.');
      });
  }

  cardEl.addEventListener('change', function () {
    picked = cardEl.value;
    showOk('');
    load(picked);
  });
  document.getElementById('tds-accounts-jobcard-detail-form-el').addEventListener('submit', function (ev) {
    ev.preventDefault();
    if (!cardEl.value) {
      showError('Choose a job card first.');
      return;
    }
    var btn = document.getElementById('tds-accounts-jobcard-detail-add');
    btn.disabled = true;
    api('/accounts/api/console/job_card_entry_save.php', {
      method: 'POST',
      body: {
        job_card_id: Number(cardEl.value),
        bus: document.getElementById('tds-accounts-jobcard-detail-bus').value.trim(),
        item: document.getElementById('tds-accounts-jobcard-detail-item').value.trim(),
        qty: Number(document.getElementById('tds-accounts-jobcard-detail-qty').value),
        price: Number(document.getElementById('tds-accounts-jobcard-detail-price').value),
        detail: document.getElementById('tds-accounts-jobcard-detail-detail').value.trim(),
      },
    })
      .then(function () {
        showOk('Line added.');
        document.getElementById('tds-accounts-jobcard-detail-form-el').reset();
        return load(cardEl.value);
      })
      .catch(function (err) {
        showError(err.message || 'Could not add the line.');
      })
      .then(function () {
        btn.disabled = false;
      });
  });
  rowsEl.addEventListener('click', function (ev) {
    var btn = ev.target.closest('button');
    if (!btn || !btn.getAttribute('data-del')) return;
    if (!window.confirm('Delete this line?')) return;
    btn.disabled = true;
    api('/accounts/api/console/job_card_entry_delete.php', {
      method: 'POST',
      body: { id: Number(btn.getAttribute('data-del')) },
    })
      .then(function () {
        showOk('Line deleted.');
        return load(cardEl.value);
      })
      .catch(function (err) {
        showError(err.message || 'Could not delete the line.');
        btn.disabled = false;
      });
  });

  load(picked);
})();
