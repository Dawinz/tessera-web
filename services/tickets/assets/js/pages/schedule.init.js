/**
 * FullCalendar - Schedule calendar hub (schedule.php).
 * Event click opens schedule_book.php in a dedicated booking subpage.
 * Boots on DOMContentLoaded, or immediately when injected into the operator SPA
 * after the document is already ready.
 */
function srBootScheduleCalendar() {
  var el = document.getElementById('schedule-calendar');
  if (!el || typeof FullCalendar === 'undefined') {
    return;
  }
  if (el.getAttribute('data-sr-fc-booted') === '1') {
    return;
  }
  el.setAttribute('data-sr-fc-booted', '1');

  function filterParams() {
    var startLoc = $('#starting_location option:selected').text();
    var endLoc = $('#ending_location option:selected').text();
    return {
      starting_location: startLoc === 'Choose Ending Point' ? '' : startLoc,
      ending_location: endLoc === 'Choose Ending Point' ? '' : endLoc
    };
  }

  function bookUrl(props) {
    var params = new URLSearchParams();
    params.set('route_day_id', String(props.route_day_id || ''));
    params.set('trip_date', String(props.trip_date || ''));
    if (props.route_id) {
      params.set('route_id', String(props.route_id));
    }
    return 'schedule_book.php?' + params.toString();
  }

  var calendar = new FullCalendar.Calendar(el, {
    initialView: 'dayGridMonth',
    height: 'auto',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,listWeek'
    },
    navLinks: true,
    editable: false,
    dayMaxEvents: 4,
    moreLinkClick: 'day',
    eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
    events: function (info, successCallback, failureCallback) {
      var params = filterParams();
      $.ajax({
        url: 'fetch_schedule_events.php',
        data: {
          start: info.startStr,
          end: info.endStr,
          starting_location: params.starting_location,
          ending_location: params.ending_location
        },
        dataType: 'json',
        success: function (data) {
          successCallback(data || []);
        },
        error: function () {
          failureCallback();
        }
      });
    },
    eventContent: function (arg) {
      var p = arg.event.extendedProps || {};
      var route = p.route_name || arg.event.title || 'Trip';
      if (route.length > 34) {
        route = route.slice(0, 32) + '...';
      }
      var time = arg.timeText ? '<span class="sr-cal-event__time">' + arg.timeText + '</span>' : '';
      var bus = p.bus ? '<span class="sr-cal-event__bus">' + p.bus + '</span>' : '';
      var seats = (p.seats_booked != null && p.seats_total != null)
        ? '<span class="sr-cal-event__seats">' + p.seats_booked + '/' + p.seats_total + '</span>'
        : '';
      return {
        html: '<div class="sr-cal-event">' + time
          + '<span class="sr-cal-event__route">' + route + '</span>'
          + bus + seats + '</div>'
      };
    },
    eventClick: function (arg) {
      arg.jsEvent.preventDefault();
      var props = arg.event.extendedProps || {};
      if (!props.route_day_id || !props.trip_date) {
        return;
      }
      window.location.href = bookUrl(props);
    },
    dateClick: function (info) {
      if (window.SR_SCHEDULE && window.SR_SCHEDULE.isAdmin) {
        var weekday = info.date.toLocaleDateString('en-US', { weekday: 'long' });
        $('#schedule-add-trip-btn, #schedule-add-trip-top').data('prefill-day', weekday);
      }
    }
  });

  calendar.render();
  window.SR_scheduleCalendar = calendar;

  $('#starting_location, #ending_location').on('change', function () {
    calendar.refetchEvents();
  });

  document.addEventListener('sr-schedule-refetch', function () {
    calendar.refetchEvents();
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', srBootScheduleCalendar);
} else {
  srBootScheduleCalendar();
}
