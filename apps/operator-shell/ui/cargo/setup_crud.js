/**
 * Shared TDS list + add/edit/delete for Cargo setup screens.
 */
(function (global) {
  'use strict';

  function esc(s) {
    return String(s == null ? '' : s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function api(url, options) {
    var opts = options || {};
    var headers = { Accept: 'application/json' };
    if (opts.body) headers['Content-Type'] = 'application/json';
    return fetch(url, {
      method: opts.method || 'GET',
      credentials: 'same-origin',
      headers: headers,
      body: opts.body ? JSON.stringify(opts.body) : undefined,
    }).then(function (res) {
      return res.json().then(function (body) {
        if (!res.ok || !body || body.ok === false) {
          var err = new Error((body && body.error && body.error.message) || 'Request failed');
          err.status = res.status;
          throw err;
        }
        return body.data || {};
      });
    });
  }

  function optionList(boot, field) {
    if (Array.isArray(field.options)) return field.options;
    if (typeof field.options === 'string' && boot) return boot[field.options] || [];
    return [];
  }

  function fillSelect(select, field, boot, placeholder) {
    var items = optionList(boot, field);
    var valueKey = field.optionValue || 'id';
    var labelKey = field.optionLabel || 'label';
    var current = select.value;
    select.innerHTML = '';
    var blank = document.createElement('option');
    blank.value = '';
    blank.textContent = placeholder || field.placeholder || 'Choose';
    select.appendChild(blank);
    items.forEach(function (item) {
      var opt = document.createElement('option');
      if (item != null && typeof item === 'object') {
        opt.value = item[valueKey] == null ? '' : String(item[valueKey]);
        opt.textContent = item[labelKey] == null ? opt.value : String(item[labelKey]);
      } else {
        opt.value = String(item);
        opt.textContent = String(item);
      }
      select.appendChild(opt);
    });
    if (current) select.value = current;
  }

  function cell(col, item) {
    if (col.format === 'badge') {
      var kind = String(item[col.key] || '');
      var cls = /main/i.test(kind) ? 'tds-badge tds-badge-info' : 'tds-badge tds-badge-warn';
      return '<span class="' + cls + '">' + esc(kind) + '</span>';
    }
    if (col.format === 'money') {
      return esc(item.fare_price) + (item.currency_symbol ? ' ' + esc(item.currency_symbol) : '');
    }
    return esc(item[col.key]);
  }

  function mount(cfg) {
    var root = document.getElementById(cfg.root);
    if (!root || root.getAttribute('data-booted') === '1') return;
    root.setAttribute('data-booted', '1');
    var host = document.getElementById(cfg.root + '-body') || root;
    var state = { boot: null, items: {}, editing: 0 };

    var filterHtml = (cfg.filters || [])
      .map(function (f) {
        return (
          '<div class="tds-field"><label class="tds-label" for="' +
          cfg.root +
          '-f-' +
          esc(f.name) +
          '">' +
          esc(f.label) +
          '</label><select class="tds-select" id="' +
          cfg.root +
          '-f-' +
          esc(f.name) +
          '" data-filter="' +
          esc(f.name) +
          '"></select></div>'
        );
      })
      .join('');

    var fieldHtml = (cfg.fields || [])
      .map(function (f) {
        var control;
        if (f.type === 'select') {
          control =
            '<select class="tds-select" id="' +
            cfg.root +
            '-in-' +
            esc(f.name) +
            '" data-field="' +
            esc(f.name) +
            '"></select>';
        } else if (f.type === 'textarea') {
          control =
            '<textarea class="tds-input" id="' +
            cfg.root +
            '-in-' +
            esc(f.name) +
            '" data-field="' +
            esc(f.name) +
            '" rows="2"></textarea>';
        } else {
          control =
            '<input class="tds-input" id="' +
            cfg.root +
            '-in-' +
            esc(f.name) +
            '" data-field="' +
            esc(f.name) +
            '" type="' +
            esc(f.type || 'text') +
            '">';
        }
        return (
          '<div class="tds-field" style="flex:1 1 14rem"><label class="tds-label" for="' +
          cfg.root +
          '-in-' +
          esc(f.name) +
          '">' +
          esc(f.label) +
          '</label>' +
          control +
          '</div>'
        );
      })
      .join('');

    var heads = (cfg.columns || [])
      .map(function (c) {
        return '<th>' + esc(c.label) + '</th>';
      })
      .join('');

    host.innerHTML =
      '<div class="tds-toolbar">' +
      '<div class="tds-field" style="flex:1 1 16rem"><label class="tds-label" for="' +
      cfg.root +
      '-q">Search</label><input class="tds-input" id="' +
      cfg.root +
      '-q" type="search" placeholder="Search"></div>' +
      '<button type="button" class="tds-btn tds-btn-secondary" id="' +
      cfg.root +
      '-go">Search</button>' +
      filterHtml +
      '<button type="button" class="tds-btn tds-btn-primary" id="' +
      cfg.root +
      '-add">' +
      esc(cfg.addLabel || 'Add') +
      '</button></div>' +
      '<div class="tds-alert tds-alert-danger" id="' +
      cfg.root +
      '-err" hidden role="alert"></div>' +
      '<div class="tds-alert tds-alert-success" id="' +
      cfg.root +
      '-ok" hidden role="status"></div>' +
      '<div class="tds-card" id="' +
      cfg.root +
      '-form" hidden style="margin-top:var(--tds-space-4)"><div class="tds-card-body"><h2 class="tds-card-title" id="' +
      cfg.root +
      '-form-title">' +
      esc(cfg.addLabel || 'Add') +
      '</h2><form id="' +
      cfg.root +
      '-form-el"><div class="tds-row" style="align-items:flex-end">' +
      fieldHtml +
      '</div><div class="tds-row" style="margin-top:var(--tds-space-3)"><button type="submit" class="tds-btn tds-btn-primary" id="' +
      cfg.root +
      '-save">Save</button><button type="button" class="tds-btn tds-btn-ghost" id="' +
      cfg.root +
      '-cancel">Cancel</button></div></form></div></div>' +
      '<div class="tds-table-panel" style="margin-top:var(--tds-space-4)"><p class="tds-muted" id="' +
      cfg.root +
      '-loading">Loading…</p><div class="tds-empty" id="' +
      cfg.root +
      '-empty" hidden><p class="tds-empty-title">' +
      esc(cfg.emptyTitle || 'Nothing here yet') +
      '</p><p class="tds-muted">' +
      esc(cfg.emptyLead || 'Add the first record to get started.') +
      '</p></div><div class="table-responsive" id="' +
      cfg.root +
      '-table-wrap" hidden><table class="tds-table"><thead><tr>' +
      heads +
      '<th></th></tr></thead><tbody id="' +
      cfg.root +
      '-body-rows"></tbody></table></div></div>';

    var errEl = document.getElementById(cfg.root + '-err');
    var okEl = document.getElementById(cfg.root + '-ok');
    var formCard = document.getElementById(cfg.root + '-form');
    var formTitle = document.getElementById(cfg.root + '-form-title');
    var loadingEl = document.getElementById(cfg.root + '-loading');
    var emptyEl = document.getElementById(cfg.root + '-empty');
    var tableWrap = document.getElementById(cfg.root + '-table-wrap');
    var tbody = document.getElementById(cfg.root + '-body-rows');

    function showError(msg) {
      errEl.hidden = !msg;
      errEl.textContent = msg || '';
    }
    function showOk(msg) {
      okEl.hidden = !msg;
      okEl.textContent = msg || '';
    }

    function paintSelects() {
      host.querySelectorAll('[data-field]').forEach(function (el) {
        if (el.tagName !== 'SELECT') return;
        var name = el.getAttribute('data-field');
        var field = (cfg.fields || []).filter(function (f) {
          return f.name === name;
        })[0];
        if (field) fillSelect(el, field, state.boot, field.placeholder || 'Choose');
      });
      host.querySelectorAll('[data-filter]').forEach(function (el) {
        var name = el.getAttribute('data-filter');
        var field = (cfg.filters || []).filter(function (f) {
          return f.name === name;
        })[0];
        if (field) fillSelect(el, field, state.boot, 'All');
      });
    }

    function queryString() {
      var params = new URLSearchParams();
      var q = document.getElementById(cfg.root + '-q');
      if (q && q.value.trim()) params.set('q', q.value.trim());
      host.querySelectorAll('[data-filter]').forEach(function (el) {
        if (el.value) params.set(el.getAttribute('data-filter'), el.value);
      });
      var s = params.toString();
      return s ? '?' + s : '';
    }

    function hideForm() {
      state.editing = 0;
      formCard.hidden = true;
      var form = document.getElementById(cfg.root + '-form-el');
      if (form) form.reset();
    }

    function showForm(item) {
      showError('');
      paintSelects();
      state.editing = item && item.id ? Number(item.id) : 0;
      formTitle.textContent = state.editing ? cfg.editLabel || 'Edit' : cfg.addLabel || 'Add';
      (cfg.fields || []).forEach(function (f) {
        var el = document.getElementById(cfg.root + '-in-' + f.name);
        if (!el) return;
        var value = item ? item[f.name] : '';
        if (f.type === 'time' && value) {
          var match = String(value).match(/(\d{1,2}):(\d{2})\s*([AP]M)/i);
          if (match) {
            var hh = parseInt(match[1], 10) % 12;
            if (/pm/i.test(match[3])) hh += 12;
            value = String(hh).padStart(2, '0') + ':' + match[2];
          }
        }
        el.value = value == null ? '' : String(value);
      });
      formCard.hidden = false;
      formCard.scrollIntoView({ block: 'nearest' });
    }

    function renderRows(items) {
      state.items = {};
      loadingEl.hidden = true;
      if (!items.length) {
        var title = emptyEl.querySelector('.tds-empty-title');
        if (title) {
          title.textContent = queryString() ? 'No matching records' : cfg.emptyTitle || 'Nothing here yet';
        }
        emptyEl.hidden = false;
        tableWrap.hidden = true;
        tbody.innerHTML = '';
        return;
      }
      emptyEl.hidden = true;
      tableWrap.hidden = false;
      tbody.innerHTML = items
        .map(function (item) {
          state.items[item.id] = item;
          var actions = '';
          if (cfg.statusUrl) {
            actions +=
              '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-status="' +
              esc(item.id) +
              '">' +
              esc(item.status === 'active' ? 'Deactivate' : 'Activate') +
              '</button> ';
          }
          if (cfg.edit) {
            actions +=
              '<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-edit="' +
              esc(item.id) +
              '">Edit</button> ';
          }
          if (cfg.deleteUrl) {
            actions +=
              '<button type="button" class="tds-btn tds-btn-danger tds-btn-sm" data-del="' +
              esc(item.id) +
              '">Delete</button>';
          }
          var cols = (cfg.columns || [])
            .map(function (c) {
              return '<td>' + cell(c, item) + '</td>';
            })
            .join('');
          return '<tr>' + cols + '<td class="tds-table-actions">' + actions + '</td></tr>';
        })
        .join('');
    }

    function load() {
      showError('');
      loadingEl.hidden = false;
      emptyEl.hidden = true;
      return api(cfg.listUrl + queryString())
        .then(function (data) {
          renderRows(data.items || []);
        })
        .catch(function (err) {
          loadingEl.hidden = true;
          showError(err.message || 'Could not load this list.');
        });
    }

    function loadBoot() {
      if (!cfg.bootstrapUrl) {
        paintSelects();
        return Promise.resolve();
      }
      return api(cfg.bootstrapUrl)
        .then(function (data) {
          state.boot = data;
          paintSelects();
        })
        .catch(function (err) {
          showError(err.message || 'Could not load form choices.');
        });
    }

    document.getElementById(cfg.root + '-add').addEventListener('click', function () {
      showForm(null);
    });
    document.getElementById(cfg.root + '-cancel').addEventListener('click', hideForm);
    document.getElementById(cfg.root + '-go').addEventListener('click', load);
    document.getElementById(cfg.root + '-q').addEventListener('keydown', function (ev) {
      if (ev.key === 'Enter') {
        ev.preventDefault();
        load();
      }
    });
    host.querySelectorAll('[data-filter]').forEach(function (el) {
      el.addEventListener('change', load);
    });
    document.getElementById(cfg.root + '-form-el').addEventListener('submit', function (ev) {
      ev.preventDefault();
      var payload = {};
      var missing = '';
      (cfg.fields || []).forEach(function (f) {
        var el = document.getElementById(cfg.root + '-in-' + f.name);
        var value = el ? String(el.value || '').trim() : '';
        if (f.required && value === '') missing = f.label;
        payload[f.name] = f.type === 'number' && value !== '' ? Number(value) : value;
      });
      if (missing) {
        showError(missing + ' is required.');
        return;
      }
      if (state.editing) payload.id = state.editing;
      var saveBtn = document.getElementById(cfg.root + '-save');
      saveBtn.disabled = true;
      api(cfg.saveUrl, { method: 'POST', body: payload })
        .then(function () {
          showOk(state.editing ? 'Saved.' : 'Added.');
          hideForm();
          return loadBoot().then(load);
        })
        .catch(function (err) {
          showError(err.message || 'Could not save.');
        })
        .then(function () {
          saveBtn.disabled = false;
        });
    });
    tbody.addEventListener('click', function (ev) {
      var btn = ev.target.closest('button');
      if (!btn) return;
      var editId = btn.getAttribute('data-edit');
      var delId = btn.getAttribute('data-del');
      var statusId = btn.getAttribute('data-status');
      if (editId) {
        showForm(state.items[editId] || null);
        return;
      }
      if (statusId && cfg.statusUrl) {
        btn.disabled = true;
        api(cfg.statusUrl, { method: 'POST', body: { id: Number(statusId) } })
          .then(function () {
            showOk('Status updated.');
            return load();
          })
          .catch(function (err) {
            showError(err.message || 'Could not change status.');
            btn.disabled = false;
          });
        return;
      }
      if (delId && cfg.deleteUrl) {
        if (!global.confirm(cfg.deleteConfirm || 'Delete this record?')) return;
        btn.disabled = true;
        api(cfg.deleteUrl, { method: 'POST', body: { id: Number(delId) } })
          .then(function () {
            showOk('Deleted.');
            return load();
          })
          .catch(function (err) {
            showError(err.message || 'Could not delete.');
            btn.disabled = false;
          });
      }
    });

    loadBoot().then(load);
  }

  global.TesseraCargoSetup = mount;
})(window);
