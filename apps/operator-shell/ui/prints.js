(function () {
  var root = document.querySelector('[data-print-root]');
  if (!root || root.getAttribute('data-kind') === 'physical' || root.getAttribute('data-module') === 'transfer') {
    return;
  }
  if (root.getAttribute('data-kind') === 'transfer') {
    return;
  }

  var id = root.id;
  var loading = document.getElementById(id + '-loading');
  var empty = document.getElementById(id + '-empty');
  var emptyText = document.getElementById(id + '-empty-text');
  var errorEl = document.getElementById(id + '-error');
  var sheet = document.getElementById(id + '-sheet');
  var actions = document.getElementById(id + '-actions');
  var printBtn = document.getElementById(id + '-print');

  function showError(message) {
    if (loading) loading.hidden = true;
    if (errorEl) {
      errorEl.hidden = false;
      errorEl.textContent = message || 'Could not load this document';
    }
  }

  function esc(value) {
    return String(value == null ? '' : value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function render(data) {
    data = data || {};
    if (loading) loading.hidden = true;
    var rows = data.rows || [];
    var meta = data.meta || [];
    var hasBody = rows.length > 0 || meta.length > 0;
    if (!hasBody) {
      if (empty) empty.hidden = false;
      if (emptyText && data.notes) emptyText.textContent = data.notes;
      if (sheet) sheet.hidden = true;
      root.setAttribute('data-ready', '1');
      return;
    }
    if (empty) empty.hidden = true;
    if (sheet) sheet.hidden = false;
    var company = data.company || {};
    var metaHtml = meta
      .map(function (item) {
        return '<div class="tds-kpi"><span class="tds-kpi-label">' + esc(item.label) + '</span><strong class="tds-kpi-value">' + esc(item.value) + '</strong></div>';
      })
      .join('');
    var head = (data.columns || [])
      .map(function (col, index) {
        if (index === 0 && data.selectable) {
          return '<th></th><th>' + esc(col) + '</th>';
        }
        return '<th>' + esc(col) + '</th>';
      })
      .join('');
    if (data.selectable && !(data.columns || []).length) {
      head = '<th></th>';
    }
    var body = rows
      .map(function (row, index) {
        var cells = (row || [])
          .map(function (cell) {
            return '<td>' + esc(cell) + '</td>';
          })
          .join('');
        var check = '';
        if (data.selectable) {
          var rowId = (data.row_ids || [])[index] || '';
          check = '<td><input type="checkbox" data-print-id="' + esc(rowId) + '" aria-label="Select row"></td>';
        }
        return '<tr>' + check + cells + '</tr>';
      })
      .join('');
    var notes = data.notes ? '<p class="tds-muted" style="margin-top:var(--tds-space-3)">' + esc(data.notes) + '</p>' : '';
    sheet.innerHTML =
      '<p class="tds-page-title" style="margin-bottom:var(--tds-space-2)">' + esc(company.name || '') + '</p>' +
      '<h2 class="tds-section-title">' + esc(data.title || '') + '</h2>' +
      (metaHtml ? '<div class="tds-kpi-grid" style="margin:var(--tds-space-4) 0">' + metaHtml + '</div>' : '') +
      '<div class="tds-table-wrap"><table class="tds-table"><thead><tr>' + head + '</tr></thead><tbody>' + body + '</tbody></table></div>' +
      notes;
    if (actions && data.selectable) {
      var params = new URLSearchParams(window.location.search);
      actions.innerHTML =
        '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="' + id + '-transfer">Transfer selected</button>' +
        '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="' + id + '-multi">Print selected tickets</button>';
      function selectedIds() {
        return Array.prototype.map
          .call(sheet.querySelectorAll('[data-print-id]:checked'), function (box) {
            return box.getAttribute('data-print-id');
          })
          .filter(Boolean)
          .join(',');
      }
      var transfer = document.getElementById(id + '-transfer');
      var multi = document.getElementById(id + '-multi');
      if (transfer) {
        transfer.addEventListener('click', function () {
          var ids = selectedIds();
          if (!ids) {
            showError('Select at least one passenger');
            return;
          }
          var q = new URLSearchParams();
          q.set('route_day_id', data.route_day_id || params.get('id') || '');
          q.set('trip_date', data.trip_date || params.get('trip_date') || '');
          q.set('ids', ids);
          window.location.href = '/operator/app.php?module=tickets&page=transfer_multiple.php&' + q.toString();
        });
      }
      if (multi) {
        multi.addEventListener('click', function () {
          var ids = selectedIds();
          if (!ids) {
            showError('Select at least one passenger');
            return;
          }
          window.location.href = '/operator/app.php?module=tickets&page=print_multiple_ticket.php&ids=' + encodeURIComponent(ids);
        });
      }
    }
    root.setAttribute('data-ready', '1');
  }

  if (printBtn) {
    printBtn.addEventListener('click', function () {
      window.print();
    });
  }

  var api = root.getAttribute('data-api') || '';
  var kind = root.getAttribute('data-kind') || '';
  var params = new URLSearchParams(window.location.search);
  params.set('kind', kind);
  fetch(api + (api.indexOf('?') >= 0 ? '&' : '?') + params.toString(), { credentials: 'same-origin' })
    .then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body.ok) {
          var message = body && body.error && body.error.message ? body.error.message : 'Could not load this document';
          throw new Error(message);
        }
        return body.data || {};
      });
    })
    .then(render)
    .catch(function (err) {
      showError(err.message);
    });
})();
