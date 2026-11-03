(function () {

    function syncTimeHidden($wrap) {
        var hour = $wrap.find('.schedule-time-hour').val();
        var minute = $wrap.find('.schedule-time-minute').val();
        $wrap.find('input[type="hidden"]').val((hour && minute) ? (hour + ':' + minute) : '');
    }

    function initBusSelect() {
        var $select = jQuery('#schedule_bus');
        if (!$select.length || !jQuery.fn.select2) {
            return;
        }
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        $select.next('.select2-container').remove();
        $select.select2({
            dropdownParent: jQuery('#schedule_modal'),
            width: '100%',
            placeholder: 'Search bus',
            allowClear: false,
            minimumResultsForSearch: 0
        });
    }

    function routeAttr($el, name) {
        return ($el && $el.length) ? (jQuery.trim($el.attr(name) || '')) : '';
    }

    function findRouteSource(routeId) {
        var $panel = jQuery('#route_points_panel');
        var $schedule = jQuery('#route_schedule_panel');
        if (routeAttr($panel, 'data-route-name') || routeAttr($panel, 'data-start-route')) {
            return $panel;
        }
        if (routeId) {
            var $match = jQuery('.route-node, .route-leg').filter(function () {
                return jQuery(this).attr('data-route-id') === routeId;
            }).first();
            if ($match.length) {
                return $match;
            }
        }
        if (routeAttr($schedule, 'data-route-name') || routeAttr($schedule, 'data-start-route')) {
            return $schedule;
        }
        var $open = jQuery('.route-node[open]').first();
        return $open.length ? $open : jQuery('.route-node').first();
    }

    function setScheduleRouteId() {
        var $panel = jQuery('#route_points_panel');
        var routeId = routeAttr($panel, 'data-route-id')
            || routeAttr(jQuery('#route_schedule_panel'), 'data-route-id')
            || routeAttr(jQuery('.route-node[open]').first(), 'data-route-id')
            || routeAttr(jQuery('.route-node').first(), 'data-route-id');
        jQuery('#schedule_route_id').val(routeId);

        var $source = findRouteSource(routeId);
        var name = routeAttr($source, 'data-route-name');
        var start = routeAttr($source, 'data-start-route');
        var end = routeAttr($source, 'data-end-route');
        var detail = (start && end) ? (start + ' - ' + end) : (start || end);

        jQuery('#schedule_modal_route_name').text(name || 'Select a route');
        jQuery('#schedule_modal_route_detail').text(detail);
    }

    if (!window.jQuery) {
        return;
    }

    jQuery(function () {
        jQuery(document).on('change', '#add_schedule_form .schedule-time-hour, #add_schedule_form .schedule-time-minute', function () {
            syncTimeHidden(jQuery(this).closest('.schedule-time-24'));
        });

        jQuery('#schedule_modal').on('shown.bs.modal', function () {
            setScheduleRouteId();
            initBusSelect();
        });
    });

})();
