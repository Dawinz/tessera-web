/**
 * C-P1-05 Luggage status.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-luggage-status-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
  function money(n) { return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 0 }); }
  function showError(msg) {
    var el = document.getElementById('tds-view-error');
    el.hidden = !msg;
    el.textContent = msg || '';
  }
  function row(label, value) {
    return '<tr><th style="width:12rem">' + esc(label) + '</th><td>' + esc(value) + '</td></tr>';
  }
  function paint(data) {
    var item = data.item;
    var empty = document.getElementById('tds-view-empty');
    var card = document.getElementById('tds-view-card');
    if (!item) {
      empty.hidden = false;
      card.hidden = true;
      return;
    }
    empty.hidden = true;
    card.hidden = false;
    document.getElementById('tds-view-fields').innerHTML = [
      row('Code', item.code),
      row('Sender', (item.sender_name || '') + (item.sender_phone ? ' · ' + item.sender_phone : '')),
      row('Receiver', (item.receiver_name || '') + (item.receiver_phone ? ' · ' + item.receiver_phone : '')),
      row('Route', item.route),
      row('Payment', item.status),
      row('Store', item.store),
      row('Date', (item.date_label || '') + (item.time ? ' at ' + item.time : '')),
      row('Charged by', item.uploader),
      row('Amount', money(item.amount)),
      row('Paid', money(item.paid)),
      row('To be paid', money(item.to_be)),
      row('Quantity', item.quantity),
      row('Weight', (item.weight_value || '') + (item.weight_name || '')),
      row('Detail', item.detail)
    ].join('');
    document.getElementById('tds-view-actions').innerHTML =
      '<a class="tds-btn tds-btn-secondary tds-btn-sm" href="' + esc(item.edit_url) + '">Update</a>' +
      '<a class="tds-btn tds-btn-ghost tds-btn-sm" href="' + esc(item.print_url) + '" target="_blank" rel="noopener">Print</a>';
    var legs = data.manifest || [];
    document.getElementById('tds-view-man').innerHTML = legs.map(function (m) {
      var taken = m.taken_by ? (m.taken_by + (m.taken_at ? ' at ' + m.taken_at : '')) : '—';
      return '<tr><td>' + esc(m.bus) + '</td><td>' + esc(m.date_label) + '</td><td>' + esc(m.route) + '</td><td>' +
        esc(m.status) + '</td><td>' + esc(m.checked || 'pending') + '</td><td class="tds-mono">' + money(m.paid) +
        '</td><td class="tds-mono">' + money(m.to_be) + '</td><td>' + esc(m.quantity) + '</td><td>' + esc(taken) + '</td></tr>';
    }).join('');
    document.getElementById('tds-view-man-wrap').hidden = legs.length === 0;
    document.getElementById('tds-view-man-empty').hidden = legs.length !== 0;
  }
  function load(query) {
    showError('');
    fetch('/cargo/api/console/luggage_status.php?' + query, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (res) { return res.json().then(function (body) { if (!res.ok || !body.ok) throw new Error((body.error && body.error.message) || 'Request failed'); return body; }); })
      .then(function (body) { paint(body.data || {}); })
      .catch(function (e) { showError(e.message || 'Could not load status'); });
  }
  document.getElementById('tds-view-find').addEventListener('click', function () {
    var code = document.getElementById('tds-view-code').value.trim();
    if (!code) { showError('Enter a receipt code'); return; }
    load('code=' + encodeURIComponent(code));
  });
  var id = Number(root.getAttribute('data-luggage-id') || 0);
  var params = new URLSearchParams(window.location.search);
  var fromUrl = params.get('u') || params.get('id') || '';
  if (id > 0) load('id=' + id);
  else if (fromUrl) load('id=' + encodeURIComponent(fromUrl));
})();
