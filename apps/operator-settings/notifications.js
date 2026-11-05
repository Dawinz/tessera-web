/**
 * S-03 Notifications — modern settings UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-settings-notifications-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var errEl = document.getElementById('tds-settings-notifications-error');
  var okEl = document.getElementById('tds-settings-notifications-success');
  var form = document.getElementById('tds-settings-notifications-form');
  var saveBtn = document.getElementById('tds-notif-save');
  var eventsState = [];

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

  function fillProviders(list, selected) {
    var sel = document.getElementById('tds-notif-sms-provider');
    if (!sel) return;
    sel.innerHTML = (list || [])
      .map(function (p) {
        return (
          '<option value="' +
          esc(p.code) +
          '"' +
          (p.code === selected ? ' selected' : '') +
          '>' +
          esc(p.label) +
          '</option>'
        );
      })
      .join('');
    if (selected && !sel.value) sel.value = selected;
  }

  function renderEvents() {
    var tbody = document.getElementById('tds-notif-events-tbody');
    if (!tbody) return;
    if (!eventsState.length) {
      tbody.innerHTML = '<tr><td colspan="4" class="tds-muted">No events configured.</td></tr>';
      return;
    }
    tbody.innerHTML = eventsState
      .map(function (ev, idx) {
        return (
          '<tr data-idx="' +
          idx +
          '">' +
          '<td><div style="font-weight:600">' +
          esc(ev.label) +
          '</div><code class="tds-muted" style="font-size:0.75rem">' +
          esc(ev.code) +
          '</code></td>' +
          '<td><input type="checkbox" data-ch="sms"' +
          (ev.sms ? ' checked' : '') +
          '></td>' +
          '<td><input type="checkbox" data-ch="whatsapp"' +
          (ev.whatsapp ? ' checked' : '') +
          '></td>' +
          '<td><input type="checkbox" data-ch="push"' +
          (ev.push ? ' checked' : '') +
          '></td>' +
          '</tr>'
        );
      })
      .join('');
  }

  function fill(item) {
    item = item || {};
    var smsEn = document.getElementById('tds-notif-sms-enabled');
    var waEn = document.getElementById('tds-notif-wa-enabled');
    var pushEn = document.getElementById('tds-notif-push-enabled');
    if (smsEn) smsEn.checked = !!item.sms_enabled;
    if (waEn) waEn.checked = !!item.whatsapp_enabled;
    if (pushEn) pushEn.checked = !!item.push_enabled;

    fillProviders(item.sms_providers, item.sms_provider || 'nextsms');
    var sender = document.getElementById('tds-notif-sms-sender');
    var user = document.getElementById('tds-notif-sms-user');
    var smsHint = document.getElementById('tds-notif-sms-key-hint');
    if (sender) sender.value = item.sms_sender_id || '';
    if (user) user.value = item.sms_username || '';
    if (smsHint) {
      smsHint.textContent = item.sms_api_key_last4 ? '(ends …' + item.sms_api_key_last4 + ')' : '';
    }

    var waProv = document.getElementById('tds-notif-wa-provider');
    var waInst = document.getElementById('tds-notif-wa-instance');
    var waHint = document.getElementById('tds-notif-wa-key-hint');
    if (waProv) waProv.value = item.whatsapp_provider || '';
    if (waInst) waInst.value = item.whatsapp_instance || '';
    if (waHint) {
      waHint.textContent = item.whatsapp_api_key_last4 ? '(ends …' + item.whatsapp_api_key_last4 + ')' : '';
    }

    var pushUrl = document.getElementById('tds-notif-push-url');
    if (pushUrl) pushUrl.value = item.push_service_url || '';

    var smsKey = document.getElementById('tds-notif-sms-key');
    var waKey = document.getElementById('tds-notif-wa-key');
    var pushKey = document.getElementById('tds-notif-push-key');
    if (smsKey) smsKey.value = '';
    if (waKey) waKey.value = '';
    if (pushKey) pushKey.value = '';

    eventsState = (item.events || []).map(function (ev) {
      return {
        code: ev.code,
        label: ev.label || ev.code,
        sms: !!ev.sms,
        whatsapp: !!ev.whatsapp,
        push: !!ev.push,
      };
    });
    renderEvents();
  }

  function collectPayload() {
    var tbody = document.getElementById('tds-notif-events-tbody');
    if (tbody) {
      tbody.querySelectorAll('tr[data-idx]').forEach(function (tr) {
        var idx = parseInt(tr.getAttribute('data-idx'), 10);
        if (!eventsState[idx]) return;
        tr.querySelectorAll('input[data-ch]').forEach(function (cb) {
          eventsState[idx][cb.getAttribute('data-ch')] = !!cb.checked;
        });
      });
    }
    var payload = {
      sms_enabled: !!(document.getElementById('tds-notif-sms-enabled') || {}).checked,
      sms_provider: (document.getElementById('tds-notif-sms-provider') || {}).value || '',
      sms_sender_id: (document.getElementById('tds-notif-sms-sender') || {}).value || '',
      sms_username: (document.getElementById('tds-notif-sms-user') || {}).value || '',
      whatsapp_enabled: !!(document.getElementById('tds-notif-wa-enabled') || {}).checked,
      whatsapp_provider: (document.getElementById('tds-notif-wa-provider') || {}).value || '',
      whatsapp_instance: (document.getElementById('tds-notif-wa-instance') || {}).value || '',
      push_enabled: !!(document.getElementById('tds-notif-push-enabled') || {}).checked,
      push_service_url: (document.getElementById('tds-notif-push-url') || {}).value || '',
      events: eventsState.map(function (ev) {
        return {
          code: ev.code,
          sms: !!ev.sms,
          whatsapp: !!ev.whatsapp,
          push: !!ev.push,
        };
      }),
    };
    var smsKey = ((document.getElementById('tds-notif-sms-key') || {}).value || '').trim();
    var waKey = ((document.getElementById('tds-notif-wa-key') || {}).value || '').trim();
    var pushKey = ((document.getElementById('tds-notif-push-key') || {}).value || '').trim();
    if (smsKey) payload.sms_api_key = smsKey;
    if (waKey) payload.whatsapp_api_key = waKey;
    if (pushKey) payload.push_api_key = pushKey;
    return payload;
  }

  if (form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      showError('');
      showOk('');
      if (saveBtn) saveBtn.disabled = true;
      fetchJson(API + '/notifications_update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(collectPayload()),
      })
        .then(function (body) {
          fill((body.data && body.data.item) || {});
          showOk((body.data && body.data.message) || 'Notification settings saved');
        })
        .catch(function (err) {
          showError(err.message || 'Save failed');
        })
        .finally(function () {
          if (saveBtn) saveBtn.disabled = false;
        });
    });
  }

  fetchJson(API + '/notifications.php')
    .then(function (body) {
      fill((body.data && body.data.item) || {});
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load notification settings');
    });
})();
