/**
 * C-P0-01 Register luggage — modern console UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-reg-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/cargo/api/console';
  var routes = [];
  var codes = [];
  var recvSeq = 0;

  var errEl = document.getElementById('tds-reg-error');
  var okEl = document.getElementById('tds-reg-success');
  var listEl = document.getElementById('tds-reg-receivers');
  var scodeEl = document.getElementById('tds-reg-scode');
  var submitBtn = document.getElementById('tds-reg-submit');
  var phoneEl = document.getElementById('tds-reg-sphone');
  var suggestEl = document.getElementById('tds-reg-phone-suggest');
  var suggestTimer = null;
  var suggestReq = 0;

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }
  function showOk(msg) {
    if (!okEl) return;
    okEl.hidden = !msg;
    okEl.textContent = msg || '';
  }
  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }
  function money(n) {
    return Number(n || 0).toLocaleString(undefined, { maximumFractionDigits: 2 });
  }

  function fetchJson(url, opts) {
    opts = opts || {};
    return fetch(url, {
      credentials: 'same-origin',
      headers: Object.assign({ Accept: 'application/json' }, opts.headers || {}),
      method: opts.method || 'GET',
      body: opts.body || undefined,
    }).then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body || body.ok === false) {
          var message =
            body && body.error && body.error.message ? body.error.message : 'Request failed';
          throw new Error(message);
        }
        return body;
      });
    });
  }

  function setSummary(summary) {
    if (!summary) return;
    var paid = document.getElementById('tds-reg-paid');
    var tobe = document.getElementById('tds-reg-tobe');
    var total = document.getElementById('tds-reg-total');
    if (paid) paid.textContent = money(summary.paid);
    if (tobe) tobe.textContent = money(summary.to_be);
    if (total) total.textContent = money(summary.total);
  }

  function routeOptionsHtml(selected) {
    var html = '<option value="">Choose route</option>';
    routes.forEach(function (r) {
      var name = r.name || r;
      html +=
        '<option value="' +
        esc(name) +
        '"' +
        (selected === name ? ' selected' : '') +
        '>' +
        esc(name) +
        '</option>';
    });
    return html;
  }

  function codeOptionsHtml(selected) {
    var html = '';
    codes.forEach(function (c) {
      html +=
        '<option value="' +
        esc(c) +
        '"' +
        (selected === c ? ' selected' : '') +
        '>' +
        esc(c) +
        '</option>';
    });
    return html;
  }

  function addReceiver(prefill) {
    prefill = prefill || {};
    recvSeq += 1;
    var id = recvSeq;
    var card = document.createElement('div');
    card.className = 'tds-card';
    card.style.marginBottom = 'var(--tds-space-3)';
    card.setAttribute('data-recv', String(id));
    card.innerHTML =
      '<div class="tds-card-body">' +
      '<div class="tds-row" style="justify-content:space-between;margin-bottom:var(--tds-space-3)">' +
      '<strong>Receiver ' +
      id +
      '</strong>' +
      '<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm tds-reg-remove">Remove</button>' +
      '</div>' +
      '<div class="tds-toolbar" style="align-items:flex-end;flex-wrap:wrap">' +
      '<div class="tds-field" style="min-width:12rem;flex:1"><label class="tds-label">Full name</label>' +
      '<input class="tds-input tds-recv-name" type="text" style="text-transform:uppercase" value="' +
      esc(prefill.name || '') +
      '"></div>' +
      '<div class="tds-field"><label class="tds-label">Code</label>' +
      '<select class="tds-select tds-recv-code">' +
      codeOptionsHtml(prefill.phone_code || (codes[0] || '+255')) +
      '</select></div>' +
      '<div class="tds-field" style="min-width:9rem"><label class="tds-label">Phone</label>' +
      '<input class="tds-input tds-recv-phone" type="tel" inputmode="numeric" maxlength="11" value="' +
      esc(prefill.phone || '') +
      '"></div>' +
      '<div class="tds-field" style="min-width:14rem;flex:1"><label class="tds-label">Route</label>' +
      '<select class="tds-select tds-recv-route">' +
      routeOptionsHtml(prefill.route || '') +
      '</select></div>' +
      '<div class="tds-field"><label class="tds-label">Payment</label>' +
      '<select class="tds-select tds-recv-pay">' +
      '<option value="paid">Paid</option>' +
      '<option value="To be Payed">To be paid</option>' +
      '<option value="Due payment">Due payment</option>' +
      '</select></div>' +
      '<div class="tds-field" style="min-width:8rem"><label class="tds-label">Fee</label>' +
      '<input class="tds-input tds-recv-amount" type="number" min="0" step="1" value="' +
      esc(prefill.amount || '') +
      '"></div>' +
      '<div class="tds-field tds-recv-due-wrap" hidden style="min-width:8rem"><label class="tds-label">Paid now</label>' +
      '<input class="tds-input tds-recv-paid" type="number" min="0" step="1" value="' +
      esc(prefill.amount_paid || '') +
      '"></div>' +
      '<div class="tds-field" style="min-width:5rem"><label class="tds-label">Qty</label>' +
      '<input class="tds-input tds-recv-qty" type="number" min="1" step="1" value="' +
      esc(prefill.quantity || '1') +
      '"></div>' +
      '<div class="tds-field"><label class="tds-label">Weight unit</label>' +
      '<select class="tds-select tds-recv-wunit"><option value="kg">kg</option><option value="g">g</option></select></div>' +
      '<div class="tds-field" style="min-width:6rem"><label class="tds-label">Weight</label>' +
      '<input class="tds-input tds-recv-weight" type="number" min="0" step="0.1" value="' +
      esc(prefill.weight || '') +
      '"></div>' +
      '<div class="tds-field" style="min-width:8rem"><label class="tds-label">Declared value</label>' +
      '<input class="tds-input tds-recv-value" type="number" min="0" step="1" value="' +
      esc(prefill.declared_value || '') +
      '"></div>' +
      '<div class="tds-field" style="min-width:14rem;flex:1"><label class="tds-label">Detail</label>' +
      '<input class="tds-input tds-recv-detail" type="text" value="' +
      esc(prefill.detail || 'NGUO') +
      '"></div>' +
      '</div></div>';

    listEl.appendChild(card);

    var payEl = card.querySelector('.tds-recv-pay');
    var dueWrap = card.querySelector('.tds-recv-due-wrap');
    function syncDue() {
      if (dueWrap) dueWrap.hidden = payEl.value !== 'Due payment';
    }
    payEl.addEventListener('change', syncDue);
    if (prefill.payment_status) {
      payEl.value = prefill.payment_status;
      syncDue();
    }
    card.querySelector('.tds-reg-remove').addEventListener('click', function () {
      if (listEl.querySelectorAll('[data-recv]').length <= 1) {
        showError('Keep at least one receiver');
        return;
      }
      card.remove();
    });
  }

  function collectReceivers() {
    var rows = [];
    listEl.querySelectorAll('[data-recv]').forEach(function (card) {
      rows.push({
        name: (card.querySelector('.tds-recv-name') || {}).value || '',
        phone_code: (card.querySelector('.tds-recv-code') || {}).value || '',
        phone: (card.querySelector('.tds-recv-phone') || {}).value || '',
        route: (card.querySelector('.tds-recv-route') || {}).value || '',
        payment_status: (card.querySelector('.tds-recv-pay') || {}).value || '',
        amount: (card.querySelector('.tds-recv-amount') || {}).value || '',
        amount_paid: (card.querySelector('.tds-recv-paid') || {}).value || '',
        quantity: (card.querySelector('.tds-recv-qty') || {}).value || '1',
        weight_unit: (card.querySelector('.tds-recv-wunit') || {}).value || 'kg',
        weight: (card.querySelector('.tds-recv-weight') || {}).value || '0',
        declared_value: (card.querySelector('.tds-recv-value') || {}).value || '0',
        detail: (card.querySelector('.tds-recv-detail') || {}).value || 'NGUO',
      });
    });
    return rows;
  }

  function renderReceipts(items) {
    var panel = document.getElementById('tds-reg-receipts');
    var body = document.getElementById('tds-reg-receipt-body');
    if (!panel || !body) return;
    body.innerHTML = '';
    if (!items || !items.length) {
      panel.hidden = true;
      return;
    }
    panel.hidden = false;
    items.forEach(function (r) {
      var tr = document.createElement('tr');
      tr.innerHTML =
        '<td><strong>' +
        esc(r.code) +
        '</strong></td>' +
        '<td>' +
        esc(r.receiver_name) +
        '</td>' +
        '<td>' +
        esc(r.route) +
        '</td>' +
        '<td>' +
        esc(r.payment_status) +
        '</td>' +
        '<td>' +
        money(r.amount) +
        '</td>' +
        '<td><a class="tds-btn tds-btn-secondary tds-btn-sm" target="_blank" rel="noopener" href="' +
        esc(r.print_url) +
        '">Print</a></td>';
      body.appendChild(tr);
    });
  }

  function boot() {
    showError('');
    showOk('');
    fetchJson(API + '/register_luggage_bootstrap.php')
      .then(function (body) {
        var data = body.data || {};
        routes = data.routes || [];
        codes = data.country_codes || ['+255'];
        setSummary(data.summary);
        if (scodeEl) {
          scodeEl.innerHTML = codeOptionsHtml(codes[0]);
        }
        var agent = document.getElementById('tds-reg-agent');
        if (agent && data.agent) {
          agent.textContent = 'Signed in as ' + (data.agent.display_name || data.agent.username);
        }
        listEl.innerHTML = '';
        recvSeq = 0;
        var defaultRoute = routes.length === 1 ? routes[0].name : '';
        addReceiver({ route: defaultRoute });
      })
      .catch(function (e) {
        showError(e.message || 'Could not load register form');
      });
  }

  function hideSuggest() {
    if (!suggestEl) return;
    suggestEl.hidden = true;
    suggestEl.innerHTML = '';
  }

  function applyContact(item) {
    var nameEl = document.getElementById('tds-reg-sname');
    if (nameEl) nameEl.value = item.sender_name || '';
    if (phoneEl) phoneEl.value = item.sender_phone || '';
    var first = listEl ? listEl.querySelector('[data-recv]') : null;
    if (first) {
      var rn = first.querySelector('.tds-recv-name');
      var rp = first.querySelector('.tds-recv-phone');
      if (rn) rn.value = item.receiver_name || '';
      if (rp) rp.value = item.receiver_phone || '';
    } else {
      var defaultRoute = routes.length === 1 ? routes[0].name : '';
      addReceiver({
        route: defaultRoute,
        name: item.receiver_name || '',
        phone: item.receiver_phone || '',
      });
    }
    hideSuggest();
  }

  function renderSuggest(items) {
    if (!suggestEl) return;
    suggestEl.innerHTML = '';
    if (!items || !items.length) {
      hideSuggest();
      return;
    }
    items.forEach(function (it) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'tds-btn tds-btn-ghost';
      btn.setAttribute('role', 'option');
      btn.style.cssText =
        'display:block;width:100%;text-align:left;border-radius:0;padding:0.65rem 0.85rem;white-space:normal';
      btn.innerHTML =
        '<strong>' +
        esc(it.sender_name || '—') +
        '</strong>' +
        '<div class="tds-muted" style="font-size:var(--tds-fs-xs)">' +
        'Sender ' +
        esc(it.sender_phone || '—') +
        ' · Receiver ' +
        esc(it.receiver_name || '—') +
        ' ' +
        esc(it.receiver_phone || '') +
        '</div>';
      btn.addEventListener('mousedown', function (e) {
        e.preventDefault();
        applyContact(it);
      });
      suggestEl.appendChild(btn);
    });
    suggestEl.hidden = false;
  }

  function lookupContacts(q) {
    var digits = String(q || '').replace(/\D+/g, '');
    if (digits.length < 2) {
      hideSuggest();
      return;
    }
    var reqId = ++suggestReq;
    fetchJson(API + '/contact_lookup.php?q=' + encodeURIComponent(digits) + '&limit=8')
      .then(function (body) {
        if (reqId !== suggestReq) return;
        renderSuggest((body.data && body.data.items) || []);
      })
      .catch(function () {
        if (reqId !== suggestReq) return;
        hideSuggest();
      });
  }

  if (phoneEl) {
    phoneEl.addEventListener('input', function () {
      clearTimeout(suggestTimer);
      suggestTimer = setTimeout(function () {
        lookupContacts(phoneEl.value);
      }, 180);
    });
    phoneEl.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') hideSuggest();
    });
    phoneEl.addEventListener('blur', function () {
      setTimeout(hideSuggest, 150);
    });
  }
  document.addEventListener('click', function (e) {
    if (!suggestEl || suggestEl.hidden) return;
    if (e.target === phoneEl || suggestEl.contains(e.target)) return;
    hideSuggest();
  });

  document.getElementById('tds-reg-add').addEventListener('click', function () {
    var defaultRoute = routes.length === 1 ? routes[0].name : '';
    addReceiver({ route: defaultRoute });
  });

  submitBtn.addEventListener('click', function () {
    showError('');
    showOk('');
    var payload = {
      sender_name: (document.getElementById('tds-reg-sname') || {}).value || '',
      sender_code: (scodeEl || {}).value || '',
      sender_phone: (document.getElementById('tds-reg-sphone') || {}).value || '',
      receivers: collectReceivers(),
    };
    submitBtn.disabled = true;
    fetchJson(API + '/register_luggage.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(function (body) {
        var data = body.data || {};
        setSummary(data.summary);
        renderReceipts(data.receipts || []);
        var n = (data.receipts || []).length;
        showOk(n === 1 ? 'Parcel registered.' : n + ' parcels registered.');
        document.getElementById('tds-reg-sname').value = '';
        document.getElementById('tds-reg-sphone').value = '';
        listEl.innerHTML = '';
        recvSeq = 0;
        var defaultRoute = routes.length === 1 ? routes[0].name : '';
        addReceiver({ route: defaultRoute });
      })
      .catch(function (e) {
        showError(e.message || 'Register failed');
      })
      .then(function () {
        submitBtn.disabled = false;
      });
  });

  boot();
})();
