/**
 * T-P0-01 Schedule — modern console UI (TDS + FullCalendar + console API).
 */
(function () {
  'use strict';

  var root = document.getElementById('tds-schedule-root');
  if (!root || root.getAttribute('data-booted') === '1') return;
  root.setAttribute('data-booted', '1');

  var API = '/tickets/api/console';
  var startSel = document.getElementById('tds-start-loc');
  var endSel = document.getElementById('tds-end-loc');
  var errEl = document.getElementById('tds-schedule-error');
  var emptyEl = document.getElementById('tds-schedule-empty');
  var loadingEl = document.getElementById('tds-schedule-loading');
  var calEl = document.getElementById('tds-schedule-calendar');
  var addBtn = document.getElementById('tds-schedule-add');
  var applyBtn = document.getElementById('tds-schedule-apply');
  var calendar = null;

  function showError(msg) {
    if (!errEl) return;
    errEl.hidden = !msg;
    errEl.textContent = msg || '';
  }

  function setLoading(on) {
    if (loadingEl) loadingEl.hidden = !on;
  }

  function fetchJson(url) {
    return fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } }).then(
      function (res) {
        return res.json().then(function (body) {
          if (!res.ok || !body || body.ok === false) {
            var code = body && body.error && body.error.code ? body.error.code : 'error';
            var message =
              body && body.error && body.error.message ? body.error.message : 'Request failed';
            var err = new Error(message);
            err.code = code;
            err.status = res.status;
            throw err;
          }
          return body;
        });
      }
    );
  }

  function fillSelect(sel, items, placeholder) {
    if (!sel) return;
    var keep = sel.value;
    sel.innerHTML = '';
    var opt0 = document.createElement('option');
    opt0.value = '';
    opt0.textContent = placeholder;
    sel.appendChild(opt0);
    (items || []).forEach(function (v) {
      var o = document.createElement('option');
      o.value = v;
      o.textContent = v;
      sel.appendChild(o);
    });
    if (keep) sel.value = keep;
  }

  function bookUrl(props) {
    var params = new URLSearchParams();
    params.set('route_day_id', String(props.route_day_id || ''));
    params.set('trip_date', String(props.trip_date || ''));
    if (props.route_id) params.set('route_id', String(props.route_id));
    return '/operator/app.php?module=tickets&page=schedule_book.php&' + params.toString();
  }

  function filterParams() {
    return {
      starting_location: startSel ? startSel.value : '',
      ending_location: endSel ? endSel.value : '',
    };
  }

  function toFcEvents(items) {
    return (items || []).map(function (it) {
      return {
        id: it.id,
        title: it.title,
        start: it.start,
        allDay: !!it.all_day,
        classNames: it.full ? ['tds-cal-event--full'] : ['tds-cal-event--open'],
        extendedProps: it,
      };
    });
  }

  function monthRange() {
    var now = new Date();
    var start = new Date(now.getFullYear(), now.getMonth(), 1);
    var end = new Date(now.getFullYear(), now.getMonth() + 1, 1);
    function iso(d) {
      return d.toISOString().slice(0, 10);
    }
    return { start: iso(start), end: iso(end) };
  }

  function applyBootstrap(data) {
    fillSelect(startSel, data.starts || [], 'All starts');
    fillSelect(endSel, data.ends || [], 'All ends');
    if (addBtn) {
      addBtn.hidden = !data.can_add_trip_template;
      addBtn.onclick = function () {
        var a = document.createElement('a');
        a.href = '/operator/app.php?module=tickets&page=register_trip.php';
        a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        a.remove();
      };
    }
    return data.items || [];
  }

  function loadBootstrap(startStr, endStr) {
    var f = filterParams();
    var q = new URLSearchParams({
      start: startStr,
      end: endStr,
      starting_location: f.starting_location,
      ending_location: f.ending_location,
    });
    return fetchJson(API + '/schedule_bootstrap.php?' + q.toString()).then(function (body) {
      return applyBootstrap(body.data || {});
    });
  }

  function loadEvents(startStr, endStr) {
    var f = filterParams();
    var q = new URLSearchParams({
      start: startStr,
      end: endStr,
      starting_location: f.starting_location,
      ending_location: f.ending_location,
    });
    return fetchJson(API + '/schedule_events.php?' + q.toString())
      .then(function (body) {
        return (body.data && body.data.items) || [];
      })
      .catch(function (err) {
        return fetch('/tickets/fetch_schedule_events.php?' + q.toString(), {
          credentials: 'same-origin',
          headers: { Accept: 'application/json' },
        }).then(function (res) {
          return res.json().then(function (rows) {
            if (!res.ok || !Array.isArray(rows)) throw err;
            return rows.map(function (ev) {
              var p = ev.extendedProps || {};
              return Object.assign(
                {
                  id: ev.id,
                  title: ev.title,
                  start: ev.start,
                  all_day: !!ev.allDay,
                },
                p
              );
            });
          });
        });
      });
  }

  function bootCalendar(initialItems) {
    if (!calEl || typeof FullCalendar === 'undefined') {
      showError('Calendar library failed to load.');
      return;
    }
    if (calendar) {
      calendar.removeAllEvents();
      calendar.addEventSource(toFcEvents(initialItems || []));
      return;
    }
    calendar = new FullCalendar.Calendar(calEl, {
      initialView: 'dayGridMonth',
      height: 'auto',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,listWeek',
      },
      navLinks: true,
      editable: false,
      dayMaxEvents: 4,
      moreLinkClick: 'day',
      eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
      events: toFcEvents(initialItems || []),
      eventContent: function (arg) {
        var p = arg.event.extendedProps || {};
        var route = p.route_name || arg.event.title || 'Trip';
        if (route.length > 34) route = route.slice(0, 32) + '…';
        var time = arg.timeText
          ? '<span class="tds-cal-event__time">' + arg.timeText + '</span>'
          : '';
        var bus = p.bus ? '<span class="tds-cal-event__bus">' + p.bus + '</span>' : '';
        var seats =
          p.seats_booked != null && p.seats_total != null
            ? '<span class="tds-cal-event__seats">' + p.seats_booked + '/' + p.seats_total + '</span>'
            : '';
        return {
          html:
            '<div class="tds-cal-event">' +
            time +
            '<span class="tds-cal-event__route">' +
            route +
            '</span>' +
            '<span class="tds-cal-event__meta">' +
            bus +
            seats +
            '</span></div>',
        };
      },
      eventClick: function (info) {
        info.jsEvent.preventDefault();
        var a = document.createElement('a');
        a.href = bookUrl(info.event.extendedProps || {});
        a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        a.remove();
      },
      datesSet: function (info) {
        if (!calendar.__tdsReady) return;
        setLoading(true);
        showError('');
        loadEvents(info.startStr, info.endStr)
          .then(function (items) {
            if (emptyEl) emptyEl.hidden = items.length > 0;
            calendar.removeAllEvents();
            toFcEvents(items).forEach(function (ev) {
              calendar.addEvent(ev);
            });
          })
          .catch(function (err) {
            showError(err.message || 'Could not load trips.');
          })
          .finally(function () {
            setLoading(false);
          });
      },
    });
    calendar.render();
    if (emptyEl) emptyEl.hidden = (initialItems || []).length > 0;
    calendar.__tdsReady = true;
  }

  (function ensureFcCss() {
    var found = Array.prototype.some.call(document.querySelectorAll('link[rel="stylesheet"]'), function (l) {
      return (l.getAttribute('href') || '').indexOf('fullcalendar') !== -1;
    });
    if (!found) {
      var link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = '/tickets/assets/libs/fullcalendar/main.min.css';
      link.setAttribute('data-sr-spa-style', '1');
      document.head.appendChild(link);
    }
  })();

  if (applyBtn) {
    applyBtn.addEventListener('click', function () {
      if (!calendar) return;
      var info = {
        startStr: calendar.view.activeStart.toISOString(),
        endStr: calendar.view.activeEnd.toISOString(),
      };
      setLoading(true);
      loadEvents(info.startStr, info.endStr)
        .then(function (items) {
          if (emptyEl) emptyEl.hidden = items.length > 0;
          calendar.removeAllEvents();
          toFcEvents(items).forEach(function (ev) {
            calendar.addEvent(ev);
          });
          showError('');
        })
        .catch(function (err) {
          showError(err.message || 'Could not load trips.');
        })
        .finally(function () {
          setLoading(false);
        });
    });
  }

  setLoading(true);
  var range = monthRange();
  loadBootstrap(range.start, range.end)
    .then(function (items) {
      showError('');
      bootCalendar(items);
    })
    .catch(function (err) {
      showError(err.message || 'Could not load schedule.');
      if (err.code === 'unauthorized') {
        window.location.href =
          '/login/?next=' + encodeURIComponent('/operator/app.php?module=tickets&page=schedule.php');
      }
    })
    .finally(function () {
      setLoading(false);
    });
})();
