/**
 * S-04 Payouts — modern settings UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-settings-payouts-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var errEl = document.getElementById('tds-settings-payouts-error');
  var okEl = document.getElementById('tds-settings-payouts-success');
  var form = document.getElementById('tds-settings-payouts-form');
  var saveBtn = document.getElementById('tds-payouts-save');
  var statusEl = document.getElementById('tds-payouts-status');

  function esc(s) {
    var d = document.createElement('div');
    d.textContent = s == null ? '' : String(s);
    return d.innerHTML;
  }

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

  function fetchJson(url, opts) {
    opts = opts || {};
    var attempt = opts._attempt || 0;
    return fetch(url, {
      credentials: 'same-origin',
      headers: Object.assign({ Accept: 'application/json' }, opts.headers || {}),
      method: opts.method || 'GET',
      body: opts.body || undefined,
      redirect: 'manual',
    }).then(function (res) {
      if (res.type === 'opaqueredirect' || res.status === 0 || (res.status >= 300 && res.status < 400)) {
        throw new Error('Session expired — sign in again');
      }
      return res.json().then(function (body) {
        if (res.status === 401 && attempt < 1) {
          return new Promise(function (resolve) {
            setTimeout(resolve, 150);
          }).then(function () {
            return fetchJson(url, Object.assign({}, opts, { _attempt: attempt + 1 }));
          });
        }
        if (!res.ok || !body || body.ok === false) {
          var message =
            body && body.error && body.error.message ? body.error.message : 'Request failed';
          throw new Error(message);
        }
        return body;
      });
    });
  }

  function fmtAmount(n, currency) {
    var num = Number(n) || 0;
    return num.toLocaleString(undefined, { maximumFractionDigits: 0 }) + ' ' + (currency || 'TZS');
  }

  function fill(item, settlements) {
    item = item || {};
    var method = document.getElementById('tds-payouts-method');
    if (method) {
      var methods = item.methods || [
        { code: 'mobile_money', label: 'Mobile money' },
        { code: 'bank', label: 'Bank transfer' },
      ];
      method.innerHTML = methods
        .map(function (m) {
          return (
            '<option value="' +
            esc(m.code) +
            '"' +
            (m.code === item.preferred_method ? ' selected' : '') +
            '>' +
            esc(m.label) +
            '</option>'
          );
        })
        .join('');
    }
    var map = {
      'tds-payouts-momo-network': item.momo_network,
      'tds-payouts-momo-msisdn': item.momo_msisdn,
      'tds-payouts-momo-name': item.momo_account_name,
      'tds-payouts-bank-name': item.bank_name,
      'tds-payouts-account-name': item.account_name,
      'tds-payouts-account-number': item.account_number,
      'tds-payouts-branch': item.bank_branch,
      'tds-payouts-notes': item.notes,
    };
    Object.keys(map).forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.value = map[id] || '';
    });
    if (statusEl) statusEl.textContent = item.verified_label || item.verified_status || '—';

    var tbody = document.getElementById('tds-payouts-settlements-tbody');
    if (!tbody) return;
    settlements = settlements || [];
    if (!settlements.length) {
      tbody.innerHTML = '<tr><td colspan="4" class="tds-muted">No settlements yet.</td></tr>';
      return;
    }
    tbody.innerHTML = settlements
      .map(function (s) {
        return (
          '<tr><td>' +
          esc(s.period_label) +
          '</td><td>' +
          esc(fmtAmount(s.amount_tzs, s.currency)) +
          '</td><td>' +
          esc(s.status) +
          '</td><td>' +
          esc(s.paid_at || '—') +
          '</td></tr>'
        );
      })
      .join('');
  }

  if (form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      showError('');
      showOk('');
      if (saveBtn) saveBtn.disabled = true;
      fetchJson(API + '/payouts_update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          preferred_method: (document.getElementById('tds-payouts-method') || {}).value || '',
          momo_network: (document.getElementById('tds-payouts-momo-network') || {}).value || '',
          momo_msisdn: (document.getElementById('tds-payouts-momo-msisdn') || {}).value || '',
          momo_account_name: (document.getElementById('tds-payouts-momo-name') || {}).value || '',
          bank_name: (document.getElementById('tds-payouts-bank-name') || {}).value || '',
          account_name: (document.getElementById('tds-payouts-account-name') || {}).value || '',
          account_number: (document.getElementById('tds-payouts-account-number') || {}).value || '',
          bank_branch: (document.getElementById('tds-payouts-branch') || {}).value || '',
          notes: (document.getElementById('tds-payouts-notes') || {}).value || '',
        }),
      })
        .then(function (body) {
          fill((body.data && body.data.item) || {}, (body.data && body.data.settlements) || []);
          showOk((body.data && body.data.message) || 'Payout profile saved');
        })
        .catch(function (err) {
          showError(err.message || 'Save failed');
        })
        .finally(function () {
          if (saveBtn) saveBtn.disabled = false;
        });
    });
  }

  fetchJson(API + '/payouts.php')
    .then(function (body) {
      fill((body.data && body.data.item) || {}, (body.data && body.data.settlements) || []);
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load payout profile');
    });
})();
