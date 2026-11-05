/**
 * S-06 Webhooks — modern settings UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-settings-webhooks-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var errEl = document.getElementById('tds-settings-webhooks-error');
  var okEl = document.getElementById('tds-settings-webhooks-success');
  var secretEl = document.getElementById('tds-settings-webhooks-secret');
  var secretVal = document.getElementById('tds-settings-webhooks-secret-value');
  var form = document.getElementById('tds-settings-webhooks-create');
  var createBtn = document.getElementById('tds-webhooks-create-btn');
  var eventCatalog = [];
  var items = [];

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
  function showSecret(secret) {
    if (!secretEl || !secretVal) return;
    if (!secret) {
      secretEl.hidden = true;
      secretVal.textContent = '';
      return;
    }
    secretVal.textContent = secret;
    secretEl.hidden = false;
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

  function applyMeta(meta) {
    meta = meta || {};
    var hint = document.getElementById('tds-webhooks-sig-hint');
    if (hint && meta.signature_header) hint.textContent = meta.signature_header;
    if (Array.isArray(meta.events) && meta.events.length) {
      eventCatalog = meta.events;
      renderEventChecks();
    }
  }

  function renderEventChecks() {
    var wrap = document.getElementById('tds-webhooks-events');
    if (!wrap) return;
    if (!eventCatalog.length) {
      wrap.innerHTML = '<span class="tds-muted">No events</span>';
      return;
    }
    wrap.innerHTML = eventCatalog
      .map(function (ev) {
        var id = 'tds-webhooks-ev-' + String(ev.code).replace(/\W/g, '-');
        return (
          '<label class="tds-row" style="gap:var(--tds-space-2);align-items:center">' +
          '<input type="checkbox" class="tds-webhooks-event" id="' +
          id +
          '" value="' +
          esc(ev.code) +
          '" checked>' +
          '<span>' +
          esc(ev.label || ev.code) +
          '</span></label>'
        );
      })
      .join('');
  }

  function renderTable() {
    var tbody = document.getElementById('tds-webhooks-tbody');
    if (!tbody) return;
    if (!items.length) {
      tbody.innerHTML = '<tr><td colspan="5" class="tds-muted">None yet.</td></tr>';
      return;
    }
    tbody.innerHTML = items
      .map(function (h) {
        var events = (h.events || []).join(', ');
        var secret = h.secret_last4 ? '…' + h.secret_last4 : '—';
        var disable =
          h.status === 'active'
            ? '<button type="button" class="tds-btn tds-btn-sm tds-webhooks-disable" data-id="' +
              esc(String(h.id)) +
              '">Disable</button>'
            : '';
        return (
          '<tr>' +
          '<td style="font-size:0.85rem;word-break:break-all">' +
          esc(h.url) +
          '</td>' +
          '<td class="tds-muted" style="font-size:0.85rem">' +
          esc(events) +
          '</td>' +
          '<td><code>' +
          esc(secret) +
          '</code></td>' +
          '<td>' +
          esc(h.status) +
          '</td>' +
          '<td class="text-end">' +
          disable +
          '</td></tr>'
        );
      })
      .join('');

    tbody.querySelectorAll('.tds-webhooks-disable').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = parseInt(btn.getAttribute('data-id') || '0', 10);
        if (!id) return;
        showError('');
        showOk('');
        showSecret('');
        btn.disabled = true;
        fetchJson(API + '/webhooks_disable.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id }),
        })
          .then(function (body) {
            items = (body.data && body.data.items) || [];
            applyMeta((body.data && body.data.meta) || {});
            renderTable();
            showOk((body.data && body.data.message) || 'Webhook disabled');
          })
          .catch(function (err) {
            showError(err.message || 'Disable failed');
            btn.disabled = false;
          });
      });
    });
  }

  if (form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      showError('');
      showOk('');
      showSecret('');
      if (createBtn) createBtn.disabled = true;
      var events = [];
      document.querySelectorAll('.tds-webhooks-event:checked').forEach(function (cb) {
        events.push(cb.value);
      });
      fetchJson(API + '/webhooks_create.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          url: (document.getElementById('tds-webhooks-url') || {}).value || '',
          events: events,
        }),
      })
        .then(function (body) {
          items = (body.data && body.data.items) || [];
          applyMeta((body.data && body.data.meta) || {});
          renderTable();
          showOk((body.data && body.data.message) || 'Webhook registered');
          showSecret((body.data && body.data.plaintext_secret) || '');
          var urlEl = document.getElementById('tds-webhooks-url');
          if (urlEl) urlEl.value = '';
        })
        .catch(function (err) {
          showError(err.message || 'Create failed');
        })
        .finally(function () {
          if (createBtn) createBtn.disabled = false;
        });
    });
  }

  fetchJson(API + '/webhooks.php')
    .then(function (body) {
      items = (body.data && body.data.items) || [];
      applyMeta((body.data && body.data.meta) || {});
      renderTable();
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load webhooks');
    });
})();
