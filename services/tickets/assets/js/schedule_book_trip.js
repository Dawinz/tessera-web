/**
 * Load seat chart + booking form on schedule_book.php (and legacy hooks).
 */
(function ($) {
  'use strict';

  window.SR_scheduleSelectTrip = function (props, title) {
    if (!props || !props.route_day_id) {
      return;
    }

    var tripDate = props.trip_date;
    var routeId = props.route_id;
    var routeDayId = props.route_day_id;
    var bus = props.bus;
    var farePrice = props.fare_price;
    var routeName = props.route_name || title || '';

    $('#starting_date').val(props.trip_date_display || props.trip_date);
    $('#route_id_selected').val(routeId);

    if ($('#admin-route-name').length) {
      $('#admin-route-name').text(props.route_name || title || ' - ');
      $('#admin-bus').text(props.bus || ' - ');
      $('#admin-weekday').text(props.weekday || ' - ');
      $('#admin-departure').text(props.departure || ' - ');
      $('#admin-arrival').text(props.arrival || ' - ');
      $('#admin-status').text(props.status || ' - ');
      $('#admin-seats').text((props.seats_booked || 0) + ' / ' + (props.seats_total || 0));
      $('#schedule-toggle-status').data('id', routeDayId);
      $('#schedule-delete-trip').data('id', routeDayId);
    }

    $('.route_selection_spiner').show();

    $.ajax({
      url: 'load_rout_starting_p.php',
      method: 'POST',
      data: { route_id: routeId },
      success: function (response) {
        $('#trip_start').html(response);
        if ($.fn.select2) {
          $('#trip_start').trigger('change.select2');
        }
      }
    });

    $.ajax({
      url: 'load_rout_ending_p.php',
      method: 'POST',
      data: { route_id: routeId },
      success: function (response) {
        $('#trip_end').html(response);
        if ($.fn.select2) {
          $('#trip_end').trigger('change.select2');
        }
      }
    });

    $.ajax({
      url: 'dependence/route_contacts.php',
      method: 'POST',
      data: { route_id: routeId },
      success: function (response) {
        $('#contact_holder').html(response);
      }
    });

    $('#load_seat_chart').html('<div class="placeholder-glow py-5"><span class="placeholder col-10 mx-auto" style="height:320px"></span></div>');
    $('#load_seat_chart').load(
      'load_seat_chart.php?id=' + routeDayId +
      '&trip_date=' + encodeURIComponent(tripDate) +
      '&route_id=' + encodeURIComponent(routeId) +
      '&route_name=' + encodeURIComponent(routeName) +
      '&bus=' + encodeURIComponent(bus) +
      '&fare_price=' + encodeURIComponent(farePrice),
      function () {
        $('.route_selection_spiner').hide();
        if (window.SR_reloadTripBookingStatus) {
          window.SR_reloadTripBookingStatus();
        }
      }
    );

    if (window.SR_reloadPassengerInfoTable) {
      window.SR_reloadPassengerInfoTable();
    }
  };

  window.SR_reloadSeatChartForTrip = function () {
    if (window.SR_SCHEDULE_TRIP && window.SR_scheduleSelectTrip) {
      window.SR_scheduleSelectTrip(
        window.SR_SCHEDULE_TRIP,
        window.SR_SCHEDULE_TRIP.route_name || ''
      );
    }
  };

  function srBootScheduleTripSeatChart() {
    if (!window.SR_SCHEDULE_TRIP || typeof window.SR_scheduleSelectTrip !== 'function') {
      return;
    }
    if (!$('#load_seat_chart').length) {
      return;
    }
    if ($('#load_seat_chart .seat_name').length) {
      return;
    }
    window.SR_scheduleSelectTrip(
      window.SR_SCHEDULE_TRIP,
      window.SR_SCHEDULE_TRIP.route_name || ''
    );
  }

  $(document).ready(function () {
    // Operator SPA may assign SR_SCHEDULE_TRIP via a later inline script.
    srBootScheduleTripSeatChart();
    setTimeout(srBootScheduleTripSeatChart, 0);

    $('#schedule-toggle-status').on('click', function () {
      var id = $(this).data('id');
      if (!id) return;
      $.post('change_trip_status.php', { id: id }, function () {
        document.dispatchEvent(new Event('sr-schedule-refetch'));
        Swal.fire({ icon: 'success', title: 'Trip status updated', timer: 1400, showConfirmButton: false });
      });
    });

    $('#schedule-delete-trip').on('click', function () {
      var id = $(this).data('id');
      if (!id) return;
      Swal.fire({
        title: 'Delete trip template?',
        text: 'This removes the weekday template and its times.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete'
      }).then(function (result) {
        if (!result.isConfirmed) return;
        $.post('delete_trip.php', { id: id }, function () {
          document.dispatchEvent(new Event('sr-schedule-refetch'));
          Swal.fire({ icon: 'success', title: 'Deleted', timer: 1400, showConfirmButton: false })
            .then(function () {
              window.location.href = 'schedule.php';
            });
        });
      });
    });

    $('#schedule-add-trip-btn, #schedule-add-trip-top').on('click', function () {
      var day = $(this).data('prefill-day');
      if (day && $('#register_table .day_of_week').length) {
        $('#register_table .day_of_week').first().val(day);
      }
      $('#myModal').modal('show');
    });
  });
})(jQuery);
