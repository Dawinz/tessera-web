(function () {
  'use strict';

  var LOCATION_POINTS_ENDPOINT = 'modules/read/search/dependency.php/route_location.php';

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function updateSelectOptions(selectors, points) {
    var optionsHtml = points
      .map(function (point) {
        return (
          '<option value="' +
          escapeHtml(point.id) +
          '">' +
          escapeHtml(point.name) +
          '</option>'
        );
      })
      .join('');

    document.querySelectorAll(selectors).forEach(function (select) {
      var placeholder = select.options.length ? select.options[0].outerHTML : '';
      var currentValue = select.value;
      select.innerHTML = placeholder + optionsHtml;
      var stillExists = Array.prototype.some.call(select.options, function (option) {
        return option.value === currentValue;
      });
      select.value = stillExists ? currentValue : select.options[0].value;
      if (window.jQuery && jQuery.fn.select2 && jQuery(select).hasClass('select2-hidden-accessible')) {
        jQuery(select).trigger('change');
      }
    });
  }

  function loadLocationPoints(pointId, targetSelectors) {
    if (!pointId || !window.jQuery) {
      return;
    }
    jQuery.ajax({
      type: 'POST',
      url: LOCATION_POINTS_ENDPOINT,
      data: { point_id: pointId },
      dataType: 'json',
      success: function (points) {
        if (Array.isArray(points) && points.length > 0) {
          updateSelectOptions(targetSelectors, points);
        }
      },
    });
  }

  function init() {
    if (!window.jQuery) {
      return;
    }
    jQuery(document).on('change', '#route_starting_point', function () {
      loadLocationPoints(jQuery(this).val(), '#pick_up_points select[name="starting_point[]"]');
    });
    jQuery(document).on('change', '#route_ending_point', function () {
      loadLocationPoints(jQuery(this).val(), '#arrival_point select[name="ending_point[]"]');
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
