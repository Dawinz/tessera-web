/**
 * Trip-scoped bookings table + seat legend on schedule_book.php
 */
(function ($) {
  'use strict';

  function tripPayload() {
    var trip = window.SR_SCHEDULE_TRIP;
    if (!trip) {
      return null;
    }
    return {
      route_day_id: trip.route_day_id,
      trip_date: trip.trip_date,
      bus: trip.bus
    };
  }

  window.SR_reloadTripBookingStatus = function () {
    var trip = window.SR_SCHEDULE_TRIP;
    var $legend = $('#load_status');
    if (!trip || !$legend.length) {
      return;
    }
    $legend.load(
      'load_status.php?id=' + encodeURIComponent(trip.route_day_id) +
      '&trip_date=' + encodeURIComponent(trip.trip_date) +
      '&bus=' + encodeURIComponent(trip.bus)
    );
  };

  window.SR_reloadPassengerInfoTable = function () {
    var $table = $('#passenger_info');
    if ($table.length && $.fn.DataTable && $.fn.DataTable.isDataTable($table)) {
      $table.DataTable().ajax.reload(null, false);
    }
    window.SR_reloadTripBookingStatus();
  };

  function updateBookingsTotal($table) {
    var $stat = $('#schedule-trip-bookings-total');
    if (!$stat.length || !$table.length || !$.fn.DataTable.isDataTable($table)) {
      return;
    }
    var info = $table.DataTable().page.info();
    if (info && typeof info.recordsTotal !== 'undefined') {
      $stat.text(info.recordsTotal);
    }
  }

  function initTripBookingsTable() {
    var $table = $('#passenger_info');
    if (!$table.length || !$.fn.DataTable || $.fn.DataTable.isDataTable($table)) {
      return;
    }

    var payload = tripPayload();
    if (!payload) {
      return;
    }

    $table.DataTable({
      processing: true,
      serverSide: true,
      serverMethod: 'post',
      order: [[1, 'desc']],
      pageLength: 10,
      lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
      dom: 'rtip',
      language: (window.TesseraStates && TesseraStates.listLanguage)
        ? TesseraStates.listLanguage('No bookings on this trip yet', 'No bookings match your search')
        : {
          emptyTable: 'No bookings on this trip yet',
          zeroRecords: 'No bookings match your search'
        },
      ajax: {
        url: 'fetch_user_booking_info.php',
        type: 'POST',
        data: payload,
        error: (window.TesseraStates && TesseraStates.loadError)
          ? TesseraStates.loadError('Could not load trip bookings')
          : undefined
      },
      columns: [
        { data: 'seat_no' },
        { data: 'id' },
        { data: 'fullname' },
        { data: 'cost' },
        { data: 'view' },
        { data: 'edit' },
        { data: 'reserve' },
        { data: 'clear' },
        { data: 'print' },
        { data: 'lock' },
        { data: 'transfer' },
        { data: 'delete' }
      ],
      columnDefs: [
        { targets: [4, 5, 6, 7, 8, 9, 10, 11], orderable: false, searchable: false }
      ],
      rowCallback: function (row, data) {
        $(row).css('background-color', '');
        if (data.status === 'Due payment') {
          $(row).css('background-color', '#ffe8f0');
        } else if (data.status === 'paid') {
          $(row).css('background-color', '#99ff9c');
        }
      },
      drawCallback: function () {
        updateBookingsTotal($table);
      }
    });

    $table.on('xhr.dt', function (_e, _settings, json) {
      var total = json && typeof json.recordsTotal !== 'undefined' ? json.recordsTotal : null;
      if (total !== null) {
        $('#schedule-trip-bookings-total').text(total);
      }
    });

    $('#schedule_trip_bookings_search').on('keyup', function () {
      $table.DataTable().search($(this).val()).draw();
    });
  }

  $(function () {
    if (!$('#passenger_info').length || !window.SR_SCHEDULE_TRIP) {
      return;
    }
    initTripBookingsTable();
    window.SR_reloadTripBookingStatus();

    $(document).ajaxSuccess(function (_event, _xhr, settings) {
      var url = (settings && settings.url) ? String(settings.url) : '';
      if (!url) {
        return;
      }
      if (/unlock_seat2|update_resserved|update_clear|delete_seat\.php/i.test(url)) {
        if (window.SR_reloadTripBookingStatus) {
          window.SR_reloadTripBookingStatus();
        }
        if (window.SR_reloadSeatChartForTrip) {
          window.SR_reloadSeatChartForTrip();
        }
      }
    });
  });
})(window.jQuery);
