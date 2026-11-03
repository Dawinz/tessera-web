/**
 * Operator Settings - Staff & menu permissions.
 */
(function () {
  'use strict';

  var cfg = window.TESSERA_STAFF_CFG || {};
  var apiBase = cfg.apiBase || '/tickets/';
  var activeUser = cfg.initialUser || '';

  function esc(s) {
    var d = document.createElement('div');
    d.textContent = s || '';
    return d.innerHTML;
  }

  function toast(msg, ok) {
    var wrap = document.getElementById('staff-toast-wrap');
    if (!wrap) return;
    var el = document.createElement('div');
    el.className = 'alert alert-' + (ok ? 'success' : 'danger') + ' shadow mb-2 py-2 px-3';
    el.textContent = msg;
    wrap.appendChild(el);
    setTimeout(function () { el.remove(); }, 2600);
  }

  function roleBadge(code, labels) {
    var label = labels[code] || code;
    var cls = {
      owner: 'bg-soft-danger text-danger',
      manager: 'bg-soft-primary text-primary',
      tickets_agent: 'bg-soft-success text-success',
      cargo_agent: 'bg-soft-warning text-warning',
      accountant: 'bg-soft-info text-info'
    }[code] || 'bg-soft-secondary text-secondary';
    return '<span class="badge role-badge ' + cls + '">' + esc(label) + '</span>';
  }

  function setMenuLoading(on) {
    var panel = document.getElementById('menu-access-panel');
    if (panel) panel.classList.toggle('opacity-50', on);
  }

  function renderMenuAccess(data) {
    var panel = document.getElementById('menu-access-panel');
    var summary = document.getElementById('menu-access-summary');
    if (!panel) return;

    if (!data || !data.ok) {
      panel.innerHTML = '<div class="menu-access-empty"><i class="uil uil-exclamation-triangle d-block fs-2 mb-2"></i>Could not load menu permissions.</div>';
      return;
    }

    var u = data.user;
    var counts = data.counts || {};
    if (summary) {
      summary.innerHTML =
        '<strong>' + esc(u.username) + '</strong> - ' +
        esc((u.firstname + ' ' + u.surname).trim()) + ' - ' +
        esc(u.role) + ' - ' +
        (counts.assigned || 0) + ' of ' + (counts.total || 0) + ' screens enabled';
    }

    if (!data.groups || !data.groups.length) {
      panel.innerHTML = '<div class="menu-access-empty"><i class="uil uil-apps d-block fs-2 mb-2"></i>No menu templates for this role level.</div>';
      return;
    }

    var html = '';
    data.groups.forEach(function (group) {
      html += '<div class="menu-group-card" data-category="' + esc(group.category) + '">';
      html += '<div class="menu-group-card__head">';
      html += '<span class="fw-semibold">' + esc(group.category) + '</span>';
      html += '<span class="small text-muted">' + group.assigned + '/' + group.total + '</span>';
      html += '</div>';
      group.items.forEach(function (item) {
        var id = 'menu-' + item.file.replace(/\W/g, '-');
        html += '<div class="menu-perm-row" data-file="' + esc(item.file) + '">';
        html += '<div class="menu-perm-row__meta">';
        html += '<div class="menu-perm-row__title">' + esc(item.name) + '</div>';
        html += '<div class="menu-perm-row__sub">' + esc(item.page_name) + ' - <code class="text-muted">' + esc(item.file) + '</code></div>';
        html += '</div>';
        html += '<div class="form-check form-switch mb-0">';
        html += '<input class="form-check-input menu-perm-toggle" type="checkbox" id="' + id + '" data-file="' + esc(item.file) + '"' + (item.assigned ? ' checked' : '') + '>';
        html += '</div></div>';
      });
      html += '</div>';
    });
    panel.innerHTML = html;
  }

  function loadMenuAccess(username) {
    if (!username) return;
    activeUser = username;
    setMenuLoading(true);
    fetch(apiBase + 'fetch_menu_access.php?username=' + encodeURIComponent(username), {
      credentials: 'same-origin',
      headers: { Accept: 'application/json' }
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        renderMenuAccess(data);
        document.querySelectorAll('.staff-user-item').forEach(function (btn) {
          btn.classList.toggle('is-active', btn.getAttribute('data-username') === username);
        });
      })
      .catch(function () {
        renderMenuAccess(null);
      })
      .finally(function () {
        setMenuLoading(false);
      });
  }

  function toggleMenu(file, enabled) {
    if (!activeUser || !file) return;
    var body = new URLSearchParams();
    body.set('change', enabled ? 'on' : '');
    body.set('file', file);
    body.set('username_names', activeUser);
    body.set('format', 'json');

    fetch(apiBase + 'change.php', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: body.toString()
    })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res && res.success) {
          toast('Menu updated', true);
          loadMenuAccess(activeUser);
        } else {
          toast((res && res.message) || 'Update failed', false);
        }
      })
      .catch(function () {
        toast('Could not reach server', false);
      });
  }

  function bindMenuTab() {
    document.querySelectorAll('.staff-user-item').forEach(function (btn) {
      btn.addEventListener('click', function () {
        loadMenuAccess(btn.getAttribute('data-username'));
      });
    });

    var search = document.getElementById('staff-user-search');
    if (search) {
      search.addEventListener('input', function () {
        var q = search.value.toLowerCase();
        document.querySelectorAll('.staff-user-item').forEach(function (btn) {
          var text = (btn.getAttribute('data-search') || '').toLowerCase();
          btn.closest('.staff-user-list-item').style.display = text.indexOf(q) >= 0 ? '' : 'none';
        });
      });
    }

    document.getElementById('menu-access-panel').addEventListener('change', function (e) {
      var t = e.target;
      if (!t.classList.contains('menu-perm-toggle')) return;
      toggleMenu(t.getAttribute('data-file'), t.checked);
    });

    if (activeUser) {
      loadMenuAccess(activeUser);
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    if (document.body.classList.contains('tessera-staff-page')) {
      bindMenuTab();
    }
  });
})();
