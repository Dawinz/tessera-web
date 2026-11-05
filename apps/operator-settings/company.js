/**
 * S-01 Company profile — modern settings UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-settings-company-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var errEl = document.getElementById('tds-settings-company-error');
  var okEl = document.getElementById('tds-settings-company-success');
  var form = document.getElementById('tds-settings-company-form');
  var displayEl = document.getElementById('tds-settings-company-display');
  var legalEl = document.getElementById('tds-settings-company-legal');
  var slugEl = document.getElementById('tds-settings-company-slug');
  var statusEl = document.getElementById('tds-settings-company-status');
  var planEl = document.getElementById('tds-settings-company-plan');
  var colorEl = document.getElementById('tds-settings-company-color');
  var logoEl = document.getElementById('tds-settings-company-logo');
  var domainEl = document.getElementById('tds-settings-company-domain');
  var saveBtn = document.getElementById('tds-settings-company-save');

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

  function fill(item) {
    item = item || {};
    if (displayEl) displayEl.value = item.display_name || '';
    if (legalEl) legalEl.value = item.legal_name || '';
    if (slugEl) slugEl.value = item.slug || '';
    if (statusEl) statusEl.value = item.status || '';
    if (planEl) planEl.value = item.plan || '';
    if (colorEl) colorEl.value = item.primary_color || '';
    if (logoEl) logoEl.value = item.logo_url || '';
    if (domainEl) domainEl.value = item.primary_domain || '';
  }

  if (form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      showError('');
      showOk('');
      if (saveBtn) saveBtn.disabled = true;
      fetchJson(API + '/company_update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          display_name: (displayEl && displayEl.value) || '',
          legal_name: (legalEl && legalEl.value) || '',
          primary_color: (colorEl && colorEl.value) || '',
          logo_url: (logoEl && logoEl.value) || '',
        }),
      })
        .then(function (body) {
          fill((body.data && body.data.item) || {});
          showOk('Company profile saved');
        })
        .catch(function (err) {
          showError(err.message || 'Save failed');
        })
        .finally(function () {
          if (saveBtn) saveBtn.disabled = false;
        });
    });
  }

  fetchJson(API + '/company.php')
    .then(function (body) {
      fill((body.data && body.data.item) || {});
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load company profile');
    });
})();
