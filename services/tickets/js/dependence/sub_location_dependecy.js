(function () {
  'use strict';

  var ROUTE_INITIAL_ENDPOINT = 'modules/read/search/dependency.php/route_inital.php';

  function refreshRouteInitial() {
    if (!window.jQuery) {
      return;
    }
    var $start = jQuery('#route_starting_point');
    var $end = jQuery('#route_ending_point');
    if (!$start.length || !$end.length) {
      return;
    }
    var startVal = $start.val();
    var endVal = $end.val();
    if (!startVal || !endVal || String(startVal).indexOf('Choose') === 0 || String(endVal).indexOf('Choose') === 0) {
      return;
    }

    jQuery.ajax({
      type: 'POST',
      url: ROUTE_INITIAL_ENDPOINT,
      data: {
        route_starting_point: startVal,
        route_ending_point: endVal,
      },
      dataType: 'json',
      success: function (data) {
        if (!data || !data.route_initial) {
          return;
        }
        jQuery('#identifer').val(data.route_initial);
      },
    });
  }

  function init() {
    if (!window.jQuery) {
      return;
    }
    jQuery(document).on('change', '#route_starting_point, #route_ending_point', refreshRouteInitial);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
