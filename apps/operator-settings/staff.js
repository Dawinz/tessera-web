/**
 * S-02 Staff & roles — modern settings UI.
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-settings-staff-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var TICKETS = '/tickets/';
  var tab = root.getAttribute('data-tab') || 'team';
  var initialUser = root.getAttribute('data-initial-user') || '';
  var errEl = document.getElementById('tds-settings-staff-error');
  var okEl = document.getElementById('tds-settings-staff-success');
  var roles = [];
  var items = [];
  var activeMenuUser = initialUser;

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

  function toast(msg, ok) {
    var wrap = document.getElementById('tds-staff-toast-wrap');
    if (!wrap) return;
    var el = document.createElement('div');
    el.className = 'tds-alert ' + (ok ? 'tds-alert-success' : 'tds-alert-danger');
    el.style.marginBottom = '0.5rem';
    el.textContent = msg;
    wrap.appendChild(el);
    setTimeout(function () {
      el.remove();
    }, 2600);
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

  function fillKpis(summary) {
    summary = summary || {};
    root.querySelectorAll('[data-kpi]').forEach(function (el) {
      var key = el.getAttribute('data-kpi');
      el.textContent = summary[key] != null ? String(summary[key]) : '0';
    });
  }

  function fillRoleSelects() {
    var createSel = document.getElementById('tds-staff-create-role');
    var assignSel = document.getElementById('tds-staff-assign-role');
    var hint = document.getElementById('tds-staff-create-role-hint');
    var opts = roles
      .map(function (r) {
        return '<option value="' + esc(r.code) + '">' + esc(r.label) + '</option>';
      })
      .join('');
    if (createSel) {
      createSel.innerHTML = opts;
      var sync = function () {
        var found = roles.find(function (r) {
          return r.code === createSel.value;
        });
        if (hint) hint.textContent = (found && found.description) || '';
      };
      createSel.addEventListener('change', sync);
      sync();
    }
    if (assignSel) assignSel.innerHTML = opts;
  }

  function fillAssignUsers() {
    var sel = document.getElementById('tds-staff-assign-user');
    if (!sel) return;
    var cur = sel.value;
    sel.innerHTML =
      '<option value="">Select staff…</option>' +
      items
        .map(function (u) {
          var label = u.username + (u.fullname ? ' — ' + u.fullname : '');
          return '<option value="' + esc(u.username) + '">' + esc(label) + '</option>';
        })
        .join('');
    if (cur) sel.value = cur;
  }

  function renderTable(filter) {
    var tbody = document.getElementById('tds-staff-tbody');
    if (!tbody) return;
    var q = (filter || '').toLowerCase().trim();
    var rows = items.filter(function (u) {
      if (!q) return true;
      var hay = (u.username + ' ' + u.fullname + ' ' + u.role_label).toLowerCase();
      return hay.indexOf(q) >= 0;
    });
    if (!rows.length) {
      tbody.innerHTML = '<tr><td colspan="5" class="tds-muted">No staff match.</td></tr>';
      return;
    }
    tbody.innerHTML = rows
      .map(function (u) {
        var initials = ((u.firstname || u.username || '?').charAt(0) + (u.surname || '').charAt(0)).toUpperCase();
        var status =
          u.status === 'active'
            ? '<span class="tds-badge tds-badge-success">Active</span>'
            : '<span class="tds-badge">' + esc(u.status || 'off') + '</span>';
        return (
          '<tr>' +
          '<td><div class="tds-row" style="gap:var(--tds-space-2);align-items:center">' +
          '<span class="tds-staff-avatar">' +
          esc(initials || '?') +
          '</span><div><div style="font-weight:600">' +
          esc(u.username) +
          '</div><div class="tds-muted" style="font-size:0.85rem">' +
          esc(u.fullname) +
          '</div></div></div></td>' +
          '<td><span class="tds-badge">' +
          esc(u.role_label || u.role) +
          '</span></td>' +
          '<td class="tds-muted">' +
          esc(u.legacy_role) +
          '</td>' +
          '<td>' +
          status +
          '</td>' +
          '<td class="text-end"><a class="tds-btn tds-btn-sm" href="' +
          esc(u.menus_url) +
          '">Edit menus</a></td>' +
          '</tr>'
        );
      })
      .join('');
  }

  function applyList(data) {
    items = (data && data.items) || [];
    fillKpis((data && data.summary) || {});
    fillAssignUsers();
    renderTable((document.getElementById('tds-staff-search') || {}).value || '');
  }

  function loadList(q) {
    var url = API + '/staff_list.php' + (q ? '?q=' + encodeURIComponent(q) : '');
    return fetchJson(url).then(function (body) {
      applyList(body.data || {});
    });
  }

  function bootTeam() {
    var search = document.getElementById('tds-staff-search');
    if (search) {
      search.addEventListener('input', function () {
        renderTable(search.value);
      });
    }

    var createForm = document.getElementById('tds-settings-staff-create');
    if (createForm) {
      createForm.addEventListener('submit', function (ev) {
        ev.preventDefault();
        showError('');
        showOk('');
        var btn = document.getElementById('tds-staff-create-btn');
        if (btn) btn.disabled = true;
        fetchJson(API + '/staff_create.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            firstname: (document.getElementById('tds-staff-firstname') || {}).value || '',
            surname: (document.getElementById('tds-staff-surname') || {}).value || '',
            username: (document.getElementById('tds-staff-username') || {}).value || '',
            password: (document.getElementById('tds-staff-password') || {}).value || '',
            number: (document.getElementById('tds-staff-number') || {}).value || '',
            email: (document.getElementById('tds-staff-email') || {}).value || '',
            role: (document.getElementById('tds-staff-create-role') || {}).value || '',
          }),
        })
          .then(function (body) {
            createForm.reset();
            fillRoleSelects();
            if (body.data && body.data.summary) fillKpis(body.data.summary);
            showOk((body.data && body.data.message) || 'Staff created');
            return loadList();
          })
          .catch(function (err) {
            showError(err.message || 'Create failed');
          })
          .finally(function () {
            if (btn) btn.disabled = false;
          });
      });
    }

    var assignForm = document.getElementById('tds-settings-staff-assign');
    if (assignForm) {
      assignForm.addEventListener('submit', function (ev) {
        ev.preventDefault();
        showError('');
        showOk('');
        var btn = document.getElementById('tds-staff-assign-btn');
        if (btn) btn.disabled = true;
        fetchJson(API + '/staff_role.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            username: (document.getElementById('tds-staff-assign-user') || {}).value || '',
            role: (document.getElementById('tds-staff-assign-role') || {}).value || '',
          }),
        })
          .then(function (body) {
            showOk((body.data && body.data.message) || 'Role updated');
            return loadList();
          })
          .catch(function (err) {
            showError(err.message || 'Role update failed');
          })
          .finally(function () {
            if (btn) btn.disabled = false;
          });
      });
    }

    Promise.all([fetchJson(API + '/staff_bootstrap.php'), loadList()])
      .then(function (pair) {
        roles = (pair[0].data && pair[0].data.roles) || [];
        fillRoleSelects();
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load staff');
      });
  }

  function renderMenuAccess(data) {
    var panel = document.getElementById('tds-menu-access-panel');
    var summary = document.getElementById('tds-menu-access-summary');
    if (!panel) return;

    if (!data || !data.ok) {
      panel.innerHTML = '<div class="tds-menu-empty">Could not load menu permissions.</div>';
      return;
    }

    var u = data.user || {};
    var counts = data.counts || {};
    if (summary) {
      summary.innerHTML =
        '<strong>' +
        esc(u.username) +
        '</strong> — ' +
        esc(((u.firstname || '') + ' ' + (u.surname || '')).trim()) +
        ' — ' +
        esc(u.role) +
        ' — ' +
        (counts.assigned || 0) +
        ' of ' +
        (counts.total || 0) +
        ' screens enabled';
    }

    if (!data.groups || !data.groups.length) {
      panel.innerHTML = '<div class="tds-menu-empty">No menu templates for this role level.</div>';
      return;
    }

    var html = '';
    data.groups.forEach(function (group) {
      html += '<div class="tds-menu-group">';
      html += '<div class="tds-menu-group__head"><span>' + esc(group.category) + '</span>';
      html += '<span class="tds-muted">' + group.assigned + '/' + group.total + '</span></div>';
      group.items.forEach(function (item) {
        var id = 'menu-' + String(item.file).replace(/\W/g, '-');
        html += '<div class="tds-menu-row">';
        html +=
          '<div><div style="font-weight:600">' +
          esc(item.name) +
          '</div><div class="tds-muted" style="font-size:0.8rem">' +
          esc(item.page_name) +
          ' · <code>' +
          esc(item.file) +
          '</code></div></div>';
        html +=
          '<label class="tds-switch"><input type="checkbox" class="tds-menu-perm-toggle" id="' +
          id +
          '" data-file="' +
          esc(item.file) +
          '"' +
          (item.assigned ? ' checked' : '') +
          '></label>';
        html += '</div>';
      });
      html += '</div>';
    });
    panel.innerHTML = html;
  }

  function loadMenuAccess(username) {
    if (!username) return;
    activeMenuUser = username;
    var panel = document.getElementById('tds-menu-access-panel');
    if (panel) panel.style.opacity = '0.55';
    fetch(TICKETS + 'fetch_menu_access.php?username=' + encodeURIComponent(username), {
      credentials: 'same-origin',
      headers: { Accept: 'application/json' },
      redirect: 'manual',
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        renderMenuAccess(data);
        document.querySelectorAll('.tds-staff-user-item').forEach(function (btn) {
          btn.classList.toggle('is-active', btn.getAttribute('data-username') === username);
        });
      })
      .catch(function () {
        renderMenuAccess(null);
      })
      .finally(function () {
        if (panel) panel.style.opacity = '';
      });
  }

  function toggleMenu(file, enabled) {
    if (!activeMenuUser || !file) return;
    var body = new URLSearchParams();
    body.set('change', enabled ? 'on' : '');
    body.set('file', file);
    body.set('username_names', activeMenuUser);
    body.set('format', 'json');

    fetch(TICKETS + 'change.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: body.toString(),
      redirect: 'manual',
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (res) {
        if (res && res.success) {
          toast('Menu updated', true);
          loadMenuAccess(activeMenuUser);
        } else {
          toast((res && res.message) || 'Update failed', false);
        }
      })
      .catch(function () {
        toast('Could not reach server', false);
      });
  }

  function renderUserList() {
    var list = document.getElementById('tds-staff-user-list');
    if (!list) return;
    if (!items.length) {
      list.innerHTML = '<div class="tds-menu-empty">No staff found.</div>';
      return;
    }
    list.innerHTML = items
      .map(function (u) {
        var initials = ((u.firstname || u.username || '?').charAt(0) + (u.surname || '').charAt(0)).toUpperCase();
        var search = (u.username + ' ' + u.fullname + ' ' + u.legacy_role).toLowerCase();
        return (
          '<button type="button" class="tds-staff-user-item' +
          (activeMenuUser === u.username ? ' is-active' : '') +
          '" data-username="' +
          esc(u.username) +
          '" data-search="' +
          esc(search) +
          '">' +
          '<span class="tds-staff-avatar">' +
          esc(initials || '?') +
          '</span>' +
          '<span style="min-width:0;flex:1">' +
          '<span style="display:block;font-weight:600;overflow:hidden;text-overflow:ellipsis">' +
          esc(u.username) +
          '</span>' +
          '<span class="tds-muted" style="display:block;font-size:0.8rem;overflow:hidden;text-overflow:ellipsis">' +
          esc(u.fullname) +
          ' · ' +
          esc(u.role_label || u.legacy_role) +
          '</span></span></button>'
        );
      })
      .join('');

    list.querySelectorAll('.tds-staff-user-item').forEach(function (btn) {
      btn.addEventListener('click', function () {
        loadMenuAccess(btn.getAttribute('data-username'));
      });
    });
  }

  function bootMenus() {
    var search = document.getElementById('tds-staff-user-search');
    if (search) {
      search.addEventListener('input', function () {
        var q = search.value.toLowerCase();
        document.querySelectorAll('.tds-staff-user-item').forEach(function (btn) {
          var text = (btn.getAttribute('data-search') || '').toLowerCase();
          btn.style.display = text.indexOf(q) >= 0 ? '' : 'none';
        });
      });
    }

    var panel = document.getElementById('tds-menu-access-panel');
    if (panel) {
      panel.addEventListener('change', function (e) {
        var t = e.target;
        if (!t.classList.contains('tds-menu-perm-toggle')) return;
        toggleMenu(t.getAttribute('data-file'), t.checked);
      });
    }

    loadList()
      .then(function () {
        renderUserList();
        if (!activeMenuUser && items[0]) activeMenuUser = items[0].username;
        if (activeMenuUser) loadMenuAccess(activeMenuUser);
      })
      .catch(function (err) {
        showError(err.message || 'Failed to load staff');
      });
  }

  if (tab === 'menus') bootMenus();
  else bootTeam();
})();
