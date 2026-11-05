/**
 * C-P1-04 Update luggage.
 */
(function () {
  'use strict';
  var root = document.getElementById('tds-update-luggage-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var currentId = Number(root.getAttribute('data-luggage-id') || 0);
  var errEl = document.getElementById('tds-upd-error');
  var okEl = document.getElementById('tds-upd-ok');
  var emptyEl = document.getElementById('tds-upd-empty');
  var form = document.getElementById('tds-upd-form');

  function showError(msg) {
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
  }
  function val(id) { return document.getElementById(id); }
  function fetchJson(url, options) {
    return fetch(url, options || { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body || body.ok === false) throw new Error((body && body.error && body.error.message) || 'Request failed');
        return body;
      });
    });
  }
  function setOptions(sel, values, selected) {
    sel.innerHTML = (values || []).map(function (v) {
      return '<option value="' + String(v).replace(/"/g, '&quot;') + '">' + String(v) + '</option>';
    }).join('');
    if (selected) sel.value = selected;
  }
  function toggleDue() {
    var due = document.getElementById('tds-upd-due-wrap');
    if (due) due.hidden = val('tds-upd-status').value !== 'Due payment';
  }
  function paint(data) {
    var item = data.item;
    if (!item) {
      form.hidden = true;
      emptyEl.hidden = false;
      return;
    }
    emptyEl.hidden = true;
    form.hidden = false;
    currentId = item.id;
    setOptions(val('tds-upd-route'), data.routes || [], item.route);
    val('tds-upd-sender').value = item.sender_name || '';
    val('tds-upd-sphone').value = item.sender_phone || '';
    val('tds-upd-receiver').value = item.receiver_name || '';
    val('tds-upd-rphone').value = item.receiver_phone || '';
    val('tds-upd-status').value = item.status || 'To be Payed';
    val('tds-upd-amount').value = item.amount != null ? item.amount : '';
    val('tds-upd-due').value = item.due || item.paid || '';
    val('tds-upd-qty').value = item.quantity || '1';
    val('tds-upd-wval').value = item.weight_value || '';
    val('tds-upd-wname').value = item.weight_name === 'g' ? 'g' : 'kg';
    val('tds-upd-lugg').value = item.luggage_value || '';
    val('tds-upd-loan').value = item.loan || '';
    val('tds-upd-detail').value = item.detail || '';
    val('tds-upd-status-link').href = item.view_url || '#';
    val('tds-upd-print').href = item.print_url || '#';
    val('tds-upd-save').disabled = !!data.on_manifest;
    if (data.on_manifest) showError('This luggage is on a manifest. Remove it before editing.');
    toggleDue();
  }
  function load(query) {
    showError('');
    showOk('');
    fetchJson('/cargo/api/console/luggage.php?' + query)
      .then(function (body) { paint(body.data || {}); })
      .catch(function (e) { showError(e.message || 'Could not load luggage'); });
  }
  val('tds-upd-status').addEventListener('change', toggleDue);
  document.getElementById('tds-upd-find').addEventListener('click', function () {
    var code = val('tds-upd-code').value.trim();
    if (!code) { showError('Enter a receipt code'); return; }
    load('code=' + encodeURIComponent(code));
  });
  form.addEventListener('submit', function (ev) {
    ev.preventDefault();
    showError('');
    showOk('');
    var save = val('tds-upd-save');
    save.disabled = true;
    fetchJson('/cargo/api/console/luggage_update.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: currentId,
        sender_name: val('tds-upd-sender').value,
        sender_phone: val('tds-upd-sphone').value,
        receiver_name: val('tds-upd-receiver').value,
        receiver_phone: val('tds-upd-rphone').value,
        route: val('tds-upd-route').value,
        status: val('tds-upd-status').value,
        amount: val('tds-upd-amount').value,
        due: val('tds-upd-due').value,
        quantity: val('tds-upd-qty').value,
        weight_value: val('tds-upd-wval').value,
        weight_name: val('tds-upd-wname').value,
        luggage_value: val('tds-upd-lugg').value,
        loan: val('tds-upd-loan').value,
        detail: val('tds-upd-detail').value
      })
    }).then(function (body) {
      showOk('Saved ' + ((body.data && body.data.item && body.data.item.code) || ''));
      if (body.data && body.data.item) paint({ item: body.data.item, routes: [body.data.item.route], on_manifest: false });
    }).catch(function (e) {
      showError(e.message || 'Could not save');
    }).then(function () { save.disabled = false; });
  });

  var params = new URLSearchParams(window.location.search);
  var fromUrl = params.get('u') || params.get('id') || '';
  if (currentId > 0) load('id=' + currentId);
  else if (fromUrl) load('id=' + encodeURIComponent(fromUrl));
  else fetchJson('/cargo/api/console/luggage.php').catch(function () {});
})();
