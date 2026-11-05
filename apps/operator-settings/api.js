/**
 * S-05 API keys — modern settings UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-settings-api-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var errEl = document.getElementById('tds-settings-api-error');
  var okEl = document.getElementById('tds-settings-api-success');
  var newKeyEl = document.getElementById('tds-settings-api-newkey');
  var newKeyVal = document.getElementById('tds-settings-api-newkey-value');
  var form = document.getElementById('tds-settings-api-create');
  var createBtn = document.getElementById('tds-api-create-btn');
  var scopeCatalog = [];
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
  function showNewKey(key) {
    if (!newKeyEl || !newKeyVal) return;
    if (!key) {
      newKeyEl.hidden = true;
      newKeyVal.textContent = '';
      return;
    }
    newKeyVal.textContent = key;
    newKeyEl.hidden = false;
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
    var baseEl = document.getElementById('tds-api-base');
    var authEl = document.getElementById('tds-api-auth-hint');
    if (baseEl && meta.api_base) baseEl.textContent = meta.api_base;
    if (authEl && meta.auth_hint) authEl.textContent = meta.auth_hint;
    if (Array.isArray(meta.scopes) && meta.scopes.length) {
      scopeCatalog = meta.scopes;
      renderScopeChecks();
    }
  }

  function renderScopeChecks() {
    var wrap = document.getElementById('tds-api-scopes');
    if (!wrap) return;
    if (!scopeCatalog.length) {
      wrap.innerHTML = '<span class="tds-muted">No scopes</span>';
      return;
    }
    wrap.innerHTML = scopeCatalog
      .map(function (sc) {
        var id = 'tds-api-scope-' + String(sc.code).replace(/\W/g, '-');
        return (
          '<label class="tds-row" style="gap:var(--tds-space-2);align-items:center">' +
          '<input type="checkbox" class="tds-api-scope" id="' +
          id +
          '" value="' +
          esc(sc.code) +
          '" checked>' +
          '<span>' +
          esc(sc.label || sc.code) +
          '</span></label>'
        );
      })
      .join('');
  }

  function renderTable() {
    var tbody = document.getElementById('tds-api-tbody');
    if (!tbody) return;
    if (!items.length) {
      tbody.innerHTML = '<tr><td colspan="6" class="tds-muted">No keys yet.</td></tr>';
      return;
    }
    tbody.innerHTML = items
      .map(function (k) {
        var scopes = (k.scopes || []).join(', ');
        var revoke =
          k.status === 'active'
            ? '<button type="button" class="tds-btn tds-btn-sm tds-api-revoke" data-id="' +
              esc(String(k.id)) +
              '">Revoke</button>'
            : '';
        return (
          '<tr>' +
          '<td style="font-weight:600">' +
          esc(k.name) +
          '</td>' +
          '<td><code>' +
          esc(k.key_prefix) +
          '…</code></td>' +
          '<td class="tds-muted" style="font-size:0.85rem">' +
          esc(scopes) +
          '</td>' +
          '<td>' +
          esc(k.status) +
          '</td>' +
          '<td class="tds-muted">' +
          esc(k.last_used_at || '—') +
          '</td>' +
          '<td class="text-end">' +
          revoke +
          '</td></tr>'
        );
      })
      .join('');

    tbody.querySelectorAll('.tds-api-revoke').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var id = parseInt(btn.getAttribute('data-id') || '0', 10);
        if (!id) return;
        if (!window.confirm('Revoke this key?')) return;
        showError('');
        showOk('');
        showNewKey('');
        btn.disabled = true;
        fetchJson(API + '/api_keys_revoke.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id }),
        })
          .then(function (body) {
            items = (body.data && body.data.items) || [];
            applyMeta((body.data && body.data.meta) || {});
            renderTable();
            showOk((body.data && body.data.message) || 'Key revoked');
          })
          .catch(function (err) {
            showError(err.message || 'Revoke failed');
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
      showNewKey('');
      if (createBtn) createBtn.disabled = true;
      var scopes = [];
      document.querySelectorAll('.tds-api-scope:checked').forEach(function (cb) {
        scopes.push(cb.value);
      });
      fetchJson(API + '/api_keys_create.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: (document.getElementById('tds-api-name') || {}).value || 'Website',
          scopes: scopes,
        }),
      })
        .then(function (body) {
          items = (body.data && body.data.items) || [];
          applyMeta((body.data && body.data.meta) || {});
          renderTable();
          showOk((body.data && body.data.message) || 'API key created');
          showNewKey((body.data && body.data.plaintext_key) || '');
        })
        .catch(function (err) {
          showError(err.message || 'Create failed');
        })
        .finally(function () {
          if (createBtn) createBtn.disabled = false;
        });
    });
  }

  fetchJson(API + '/api_keys.php')
    .then(function (body) {
      items = (body.data && body.data.items) || [];
      applyMeta((body.data && body.data.meta) || {});
      renderTable();
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load API keys');
    });
})();
