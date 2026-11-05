/**
 * Accounts reports A-P1-18…25 — one client for the eight TDS screens.
 */
(function () {
  'use strict';

  var root = document.querySelector('[data-report]');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var kind = root.getAttribute('data-report') || '';
  var id = root.id;
  var API = '/accounts/api/console';

  function el(suffix) {
    return document.getElementById(id + suffix);
  }
  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }
  function money(n) {
    var v = Math.round(Number(n) || 0);
    var text = Math.abs(v).toLocaleString();
    return v < 0 ? '(' + text + ')' : text;
  }
  function moneyHtml(n) {
    var v = Math.round(Number(n) || 0);
    var style = v < 0 ? ' style="color:var(--tds-danger)"' : '';
    return '<span class="tds-mono"' + style + '>' + money(v) + '</span>';
  }
  function pct(n) {
    var v = Number(n) || 0;
    return v.toFixed(1) + '%';
  }
  function isoLocal(d) {
    var y = d.getFullYear();
    var m = String(d.getMonth() + 1).padStart(2, '0');
    var day = String(d.getDate()).padStart(2, '0');
    return y + '-' + m + '-' + day;
  }
  function showError(msg) {
    var node = el('-error');
    if (!node) return;
    node.hidden = !msg;
    node.textContent = msg || '';
  }
  function setLoading(on) {
    var loading = el('-loading');
    var wrap = el('-wrap');
    var empty = el('-empty');
    if (loading) loading.hidden = !on;
    if (on) {
      if (wrap) wrap.hidden = true;
      if (empty) empty.hidden = true;
      var extra = el('-extra');
      if (extra) extra.innerHTML = '';
    }
  }
  function fetchJson(url) {
    return fetch(url, {
      credentials: 'same-origin',
      headers: { Accept: 'application/json' },
      redirect: 'manual',
    }).then(function (res) {
      if (res.type === 'opaqueredirect' || res.status === 0 || (res.status >= 300 && res.status < 400)) {
        throw new Error('Session expired — sign in again');
      }
      return res.json().then(function (body) {
        if (!res.ok || !body || body.ok === false) {
          var message = body && body.error && body.error.message ? body.error.message : 'Request failed';
          throw new Error(message);
        }
        return body;
      });
    });
  }
  function fillSelect(node, rows, valueKey, labelKey, allLabel) {
    if (!node) return;
    var current = node.value;
    var html = allLabel ? '<option value="">' + esc(allLabel) + '</option>' : '';
    rows.forEach(function (row) {
      var value = typeof row === 'string' ? row : row[valueKey];
      var label = typeof row === 'string' ? row : row[labelKey];
      html += '<option value="' + esc(value) + '">' + esc(label) + '</option>';
    });
    node.innerHTML = html;
    if (current) node.value = current;
  }
  function selectedIds(node) {
    if (!node) return '';
    var ids = [];
    var options = node.selectedOptions || [];
    for (var i = 0; i < options.length; i++) {
      if (options[i].value) ids.push(options[i].value);
    }
    return ids.join(',');
  }
  function query() {
    var parts = [];
    function add(name, node) {
      if (!node) return;
      parts.push(encodeURIComponent(name) + '=' + encodeURIComponent(node.value || ''));
    }
    add('q', el('-q'));
    add('route', el('-route'));
    add('vehicle', el('-vehicle'));
    add('currency', el('-currency'));
    add('date_from', el('-from'));
    add('date_to', el('-to'));
    add('as_of', el('-asof'));
    add('vehicle_category', el('-vcategory'));
    add('mode', el('-mode'));
    var lines = el('-lines');
    if (lines) parts.push('ids=' + encodeURIComponent(selectedIds(lines)));
    return parts.join('&');
  }
  function defaultDates() {
    var now = new Date();
    var from = el('-from');
    var to = el('-to');
    var asof = el('-asof');
    if (from && !from.value) from.value = isoLocal(new Date(now.getFullYear(), now.getMonth(), 1));
    if (to && !to.value) to.value = isoLocal(now);
    if (asof && !asof.value) asof.value = isoLocal(now);
  }
  function setKpis(pairs) {
    for (var i = 0; i < 4; i++) {
      var label = el('-k' + i + 'l');
      var value = el('-k' + i + 'v');
      var pair = pairs[i] || ['', '—', false];
      if (label) label.textContent = pair[0];
      if (value) {
        value.textContent = pair[2] ? money(pair[1]) : String(pair[1] == null || pair[1] === '' ? '—' : pair[1]);
        value.style.color = pair[2] && Number(pair[1]) < 0 ? 'var(--tds-danger)' : '';
      }
    }
  }
  function table(columns, rows) {
    var head = el('-head');
    var body = el('-body');
    var foot = el('-foot');
    var wrap = el('-wrap');
    var empty = el('-empty');
    if (head) {
      head.innerHTML = '<tr>' + columns.map(function (col) {
        return '<th>' + esc(col.label) + '</th>';
      }).join('') + '</tr>';
    }
    if (body) {
      body.innerHTML = rows.map(function (row) {
        return '<tr>' + columns.map(function (col) {
          var raw = row[col.key];
          if (col.money) return '<td>' + moneyHtml(raw) + '</td>';
          if (col.pct) return '<td class="tds-mono">' + esc(pct(raw)) + '</td>';
          if (col.badge) {
            return '<td><span class="tds-badge tds-badge-' + esc(row.badge || 'neutral') + '">' + esc(raw) + '</span></td>';
          }
          return '<td>' + esc(raw) + '</td>';
        }).join('') + '</tr>';
      }).join('');
    }
    if (foot) foot.innerHTML = '';
    var has = rows.length > 0;
    if (wrap) wrap.hidden = !has;
    if (empty) empty.hidden = has;
  }
  function extraTable(title, columns, rows) {
    if (!rows || !rows.length) return '';
    var head = columns.map(function (col) { return '<th>' + esc(col.label) + '</th>'; }).join('');
    var body = rows.map(function (row) {
      return '<tr>' + columns.map(function (col) {
        var raw = row[col.key];
        if (col.money) return '<td>' + moneyHtml(raw) + '</td>';
        if (col.pct) return '<td class="tds-mono">' + esc(pct(raw)) + '</td>';
        return '<td>' + esc(raw) + '</td>';
      }).join('') + '</tr>';
    }).join('');
    return '<div class="tds-table-panel" style="margin-top:var(--tds-space-4)"><p class="tds-label">' + esc(title) + '</p><div class="tds-table-wrap"><table class="tds-table"><thead><tr>' + head + '</tr></thead><tbody>' + body + '</tbody></table></div></div>';
  }
  function statementBlock(rows) {
    var body = (rows || []).map(function (row) {
      if (row.type === 'heading') {
        return '<tr><td colspan="2"><strong>' + esc(row.label) + '</strong></td></tr>';
      }
      return '<tr><td>' + esc(row.label) + '</td><td>' + moneyHtml(row.amount) + '</td></tr>';
    }).join('');
    return '<div class="tds-table-panel" style="margin-top:var(--tds-space-4)"><p class="tds-label">Summary</p><div class="tds-table-wrap"><table class="tds-table"><thead><tr><th>Line</th><th>Amount</th></tr></thead><tbody>' + body + '</tbody></table></div></div>';
  }
  function fillLines(catalog) {
    var mode = el('-mode');
    var lines = el('-lines');
    if (!lines || !catalog) return;
    var selected = {};
    selectedIds(lines).split(',').forEach(function (idValue) {
      if (idValue) selected[idValue] = true;
    });
    var useParticular = mode && mode.value === 'particular';
    var rows = useParticular ? catalog.particulars || [] : catalog.categories || [];
    lines.innerHTML = rows.map(function (row) {
      var label = useParticular ? (row.category ? row.category + ' — ' + row.name : row.name) : row.name;
      var mark = selected[String(row.id)] ? ' selected' : '';
      return '<option value="' + esc(row.id) + '"' + mark + '>' + esc(label) + '</option>';
    }).join('');
  }

  var catalog = null;

  function paint(data) {
    var applied = data.applied || {};
    var period = el('-period');
    if (period) {
      var bits = [];
      if (applied.period_label) bits.push(applied.period_label);
      if (applied.currency) bits.push(applied.currency);
      if (data.previous && data.previous.label) bits.push('compared with ' + data.previous.label);
      if (data.base) bits.push('converted to ' + data.base);
      period.textContent = bits.join(' · ');
    }
    var summary = data.summary || {};
    var extra = el('-extra');
    if (kind === 'vehicle_statement') {
      setKpis([
        ['Vehicles', summary.vehicles, false],
        ['Income', summary.income, true],
        ['Expenses', summary.expenses, true],
        ['Balance', summary.balance, true],
      ]);
      table([
        { key: 'bus', label: 'Vehicle' },
        { key: 'routes', label: 'Routes' },
        { key: 'ticket', label: 'Tickets', money: true },
        { key: 'road', label: 'Road collection', money: true },
        { key: 'cargo_paid', label: 'Cargo paid', money: true },
        { key: 'cargo_to_pay', label: 'Cargo to be paid', money: true },
        { key: 'income', label: 'Income', money: true },
        { key: 'expenses', label: 'Expenses', money: true },
        { key: 'balance', label: 'Balance', money: true },
      ], data.items || []);
    } else if (kind === 'vehicle_grouped') {
      setKpis([
        ['Vehicles', summary.vehicles, false],
        ['Income', summary.income, true],
        ['Expenses', summary.expenses, true],
        ['Trips', summary.trips, false],
      ]);
      if (extra) extra.innerHTML = extraTable('Groups', [
        { key: 'category', label: 'Group' },
        { key: 'vehicles', label: 'Vehicles' },
        { key: 'income', label: 'Income', money: true },
        { key: 'expenses', label: 'Expenses', money: true },
        { key: 'balance', label: 'Balance', money: true },
        { key: 'trips', label: 'Trips' },
      ], data.groups || []);
      table([
        { key: 'category', label: 'Group' },
        { key: 'vehicle', label: 'Vehicle' },
        { key: 'income', label: 'Income', money: true },
        { key: 'expenses', label: 'Expenses', money: true },
        { key: 'balance', label: 'Balance', money: true },
        { key: 'trips', label: 'Trips' },
      ], data.items || []);
    } else if (kind === 'category_filter') {
      setKpis([
        ['Lines', summary.lines, false],
        ['Total', summary.total, true],
        ['Showing', data.mode === 'particular' ? 'Particulars' : 'Categories', false],
        ['Currency', applied.currency || '', false],
      ]);
      var cols = [
        { key: 'name', label: data.mode === 'particular' ? 'Particular' : 'Category' },
      ];
      if (data.mode === 'particular') cols.push({ key: 'category', label: 'Category' });
      cols.push({ key: 'route', label: 'Route' }, { key: 'amount', label: 'Amount', money: true });
      table(cols, data.rows || []);
    } else if (kind === 'cash_flow') {
      setKpis([
        ['Opening', summary.opening, true],
        ['Cash in', summary.cash_in, true],
        ['Cash out', summary.cash_out, true],
        ['Closing', summary.closing, true],
      ]);
      if (extra) {
        extra.innerHTML = statementBlock(data.statement || []) + extraTable('Cash book deposits', [
          { key: 'date_label', label: 'Date' },
          { key: 'route', label: 'Route' },
          { key: 'authorizer', label: 'Authorizer' },
          { key: 'amount', label: 'Amount', money: true },
        ], data.deposits || []);
      }
      table([
        { key: 'label', label: 'Date' },
        { key: 'cash_in', label: 'Cash in', money: true },
        { key: 'trip_costs', label: 'Trip costs', money: true },
        { key: 'office', label: 'Office', money: true },
        { key: 'deposited', label: 'Deposits', money: true },
        { key: 'net', label: 'Net', money: true },
        { key: 'balance', label: 'Balance', money: true },
      ], data.days || []);
    } else if (kind === 'revenue') {
      setKpis([
        ['Revenue', summary.total, true],
        ['Tickets', summary.ticket, true],
        ['Cargo', summary.cargo, true],
        ['Per trip', summary.per_trip, true],
      ]);
      if (extra) extra.innerHTML = extraTable('Revenue mix', [
        { key: 'source', label: 'Source' },
        { key: 'amount', label: 'Amount', money: true },
        { key: 'share', label: 'Share', pct: true },
      ], data.mix || []);
      table([
        { key: 'route', label: 'Route' },
        { key: 'trips', label: 'Trips' },
        { key: 'ticket', label: 'Tickets', money: true },
        { key: 'road', label: 'Road collection', money: true },
        { key: 'cargo_paid', label: 'Cargo paid', money: true },
        { key: 'cargo_to_pay', label: 'Cargo to be paid', money: true },
        { key: 'total', label: 'Total', money: true },
        { key: 'share', label: 'Share', pct: true },
        { key: 'per_trip', label: 'Per trip', money: true },
      ], data.routes || []);
    } else if (kind === 'expense') {
      setKpis([
        ['Trip costs', summary.trip_costs, true],
        ['Office', summary.office, true],
        ['Per trip', summary.per_trip, true],
        ['Largest', summary.largest ? summary.largest + ' (' + pct(summary.largest_share) + ')' : '—', false],
      ]);
      if (extra) extra.innerHTML = extraTable('Office expenses', [
        { key: 'name', label: 'Name' },
        { key: 'now', label: 'This period', money: true },
        { key: 'before', label: 'Previous', money: true },
        { key: 'change', label: 'Change', money: true },
      ], data.office || []);
      table([
        { key: 'category', label: 'Category' },
        { key: 'now', label: 'This period', money: true },
        { key: 'before', label: 'Previous', money: true },
        { key: 'change', label: 'Change', money: true },
        { key: 'change_pct', label: 'Change %', pct: true },
        { key: 'per_trip', label: 'Per trip', money: true },
        { key: 'share', label: 'Share', pct: true },
      ], data.rows || []);
    } else if (kind === 'payables') {
      setKpis([
        ['Outstanding', summary.total, true],
        ['Items', summary.items, false],
        ['Over 30 days', summary.overdue, true],
        ['Oldest (days)', summary.oldest_days, false],
      ]);
      if (extra) extra.innerHTML = extraTable('Aging', [
        { key: 'bucket', label: 'Outstanding for' },
        { key: 'items', label: 'Items' },
        { key: 'amount', label: 'Amount', money: true },
        { key: 'share', label: 'Share', pct: true },
      ], data.aging || []);
      table([
        { key: 'date_label', label: 'Trip date' },
        { key: 'bus', label: 'Vehicle' },
        { key: 'route', label: 'Route' },
        { key: 'category', label: 'Category' },
        { key: 'name', label: 'Particular' },
        { key: 'amount', label: 'Amount', money: true },
        { key: 'days', label: 'Days' },
        { key: 'bucket', label: 'Bucket', badge: true },
      ], data.items || []);
    } else if (kind === 'currency_position') {
      setKpis([
        ['Net in ' + (data.base || 'base'), summary.net_base, true],
        ['Currencies', summary.currencies, false],
        ['Primary', summary.primary || data.base || '', false],
        ['Period', applied.period_label || '', false],
      ]);
      table([
        { key: 'code', label: 'Currency' },
        { key: 'entries', label: 'Entries' },
        { key: 'income', label: 'Income', money: true },
        { key: 'expenses', label: 'Expenses', money: true },
        { key: 'net', label: 'Net', money: true },
        { key: 'rate_text', label: 'Exchange rate' },
        { key: 'net_base', label: 'Net in ' + (data.base || 'base'), money: true },
      ], data.rows || []);
    }
    root.setAttribute('data-ready', '1');
  }

  function loadReport() {
    var endpoint = {
      vehicle_statement: 'report_vehicle_statement.php',
      vehicle_grouped: 'report_vehicle_grouped.php',
      category_filter: 'report_category_filter.php',
      cash_flow: 'report_cash_flow.php',
      revenue: 'report_revenue.php',
      expense: 'report_expense.php',
      payables: 'report_payables.php',
      currency_position: 'report_currency_position.php',
    }[kind];
    if (!endpoint) return;
    showError('');
    setLoading(true);
    fetchJson(API + '/' + endpoint + '?' + query())
      .then(function (body) {
        setLoading(false);
        paint(body.data || {});
      })
      .catch(function (err) {
        setLoading(false);
        showError(err && err.message ? err.message : 'Request failed');
        root.setAttribute('data-ready', '1');
      });
  }

  function bootFilters() {
    return fetchJson(API + '/reports_filters.php').then(function (body) {
      catalog = body.data || {};
      fillSelect(el('-route'), catalog.routes || [], 'name', 'label', 'All routes');
      fillSelect(el('-vehicle'), catalog.vehicles || [], null, null, 'All vehicles');
      fillSelect(el('-vcategory'), catalog.vehicle_categories || [], null, null, 'All groups');
      var currency = el('-currency');
      if (currency) {
        currency.innerHTML = (catalog.currencies || []).map(function (row) {
          var mark = row.primary ? ' selected' : '';
          return '<option value="' + esc(row.name) + '"' + mark + '>' + esc(row.name) + '</option>';
        }).join('');
        if (!currency.value && catalog.primary_currency) currency.value = catalog.primary_currency;
      }
      fillLines(catalog);
    });
  }

  defaultDates();
  var apply = el('-apply');
  var reset = el('-reset');
  if (apply) apply.addEventListener('click', loadReport);
  if (reset) {
    reset.addEventListener('click', function () {
      ['-q', '-route', '-vehicle', '-vcategory'].forEach(function (suffix) {
        var node = el(suffix);
        if (node) node.value = '';
      });
      var lines = el('-lines');
      if (lines) {
        for (var i = 0; i < lines.options.length; i++) lines.options[i].selected = false;
      }
      var mode = el('-mode');
      if (mode) mode.value = 'category';
      var from = el('-from');
      var to = el('-to');
      var asof = el('-asof');
      if (from) from.value = '';
      if (to) to.value = '';
      if (asof) asof.value = '';
      defaultDates();
      if (catalog) fillLines(catalog);
      var currency = el('-currency');
      if (currency && catalog && catalog.primary_currency) currency.value = catalog.primary_currency;
      loadReport();
    });
  }
  var mode = el('-mode');
  if (mode) {
    mode.addEventListener('change', function () {
      fillLines(catalog);
      loadReport();
    });
  }
  bootFilters().then(loadReport).catch(function (err) {
    showError(err && err.message ? err.message : 'Request failed');
    root.setAttribute('data-ready', '1');
  });
})();
