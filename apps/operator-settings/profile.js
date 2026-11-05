/**
 * S-07 Profile — modern settings UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-settings-profile-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var errEl = document.getElementById('tds-settings-profile-error');
  var okEl = document.getElementById('tds-settings-profile-success');
  var form = document.getElementById('tds-settings-profile-form');
  var pwForm = document.getElementById('tds-settings-profile-password');
  var avatarForm = document.getElementById('tds-settings-profile-avatar');
  var saveBtn = document.getElementById('tds-profile-save');
  var pwBtn = document.getElementById('tds-profile-password-btn');
  var avatarBtn = document.getElementById('tds-profile-avatar-btn');

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
    var headers = Object.assign({ Accept: 'application/json' }, opts.headers || {});
    return fetch(url, {
      credentials: 'same-origin',
      headers: headers,
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
    var avatar = document.getElementById('tds-profile-avatar');
    var nameLabel = document.getElementById('tds-profile-fullname-label');
    var roleLabel = document.getElementById('tds-profile-role-label');
    if (avatar) avatar.src = item.avatar_url || '/tickets/assets/images/users/user.png';
    if (nameLabel) nameLabel.textContent = item.fullname || item.username || '';
    if (roleLabel) roleLabel.textContent = item.role_label || item.role || '';

    var map = {
      'tds-profile-username': item.username,
      'tds-profile-role': item.role_label || item.role,
      'tds-profile-firstname': item.firstname,
      'tds-profile-surname': item.surname,
      'tds-profile-email': item.email,
      'tds-profile-number': item.number,
    };
    Object.keys(map).forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.value = map[id] || '';
    });
  }

  if (form) {
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      showError('');
      showOk('');
      if (saveBtn) saveBtn.disabled = true;
      fetchJson(API + '/profile_update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          firstname: (document.getElementById('tds-profile-firstname') || {}).value || '',
          surname: (document.getElementById('tds-profile-surname') || {}).value || '',
          email: (document.getElementById('tds-profile-email') || {}).value || '',
          number: (document.getElementById('tds-profile-number') || {}).value || '',
        }),
      })
        .then(function (body) {
          fill((body.data && body.data.item) || {});
          showOk((body.data && body.data.message) || 'Profile saved');
        })
        .catch(function (err) {
          showError(err.message || 'Save failed');
        })
        .finally(function () {
          if (saveBtn) saveBtn.disabled = false;
        });
    });
  }

  if (pwForm) {
    pwForm.addEventListener('submit', function (ev) {
      ev.preventDefault();
      showError('');
      showOk('');
      var next = (document.getElementById('tds-profile-new') || {}).value || '';
      var confirm = (document.getElementById('tds-profile-confirm') || {}).value || '';
      if (next !== confirm) {
        showError('New password and confirmation do not match');
        return;
      }
      if (pwBtn) pwBtn.disabled = true;
      fetchJson(API + '/profile_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          current_password: (document.getElementById('tds-profile-current') || {}).value || '',
          new_password: next,
        }),
      })
        .then(function (body) {
          pwForm.reset();
          showOk((body.data && body.data.message) || 'Password updated');
        })
        .catch(function (err) {
          showError(err.message || 'Password update failed');
        })
        .finally(function () {
          if (pwBtn) pwBtn.disabled = false;
        });
    });
  }

  if (avatarForm) {
    avatarForm.addEventListener('submit', function (ev) {
      ev.preventDefault();
      showError('');
      showOk('');
      var fileEl = document.getElementById('tds-profile-avatar-file');
      if (!fileEl || !fileEl.files || !fileEl.files[0]) {
        showError('Choose a photo first');
        return;
      }
      var fd = new FormData();
      fd.append('avatar', fileEl.files[0]);
      if (avatarBtn) avatarBtn.disabled = true;
      fetchJson(API + '/profile_avatar.php', {
        method: 'POST',
        body: fd,
      })
        .then(function (body) {
          fill((body.data && body.data.item) || {});
          fileEl.value = '';
          showOk((body.data && body.data.message) || 'Photo updated');
        })
        .catch(function (err) {
          showError(err.message || 'Upload failed');
        })
        .finally(function () {
          if (avatarBtn) avatarBtn.disabled = false;
        });
    });
  }

  fetchJson(API + '/profile.php')
    .then(function (body) {
      fill((body.data && body.data.item) || {});
    })
    .catch(function (err) {
      showError(err.message || 'Failed to load profile');
    });
})();
