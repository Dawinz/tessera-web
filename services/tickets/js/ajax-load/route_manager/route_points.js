(function () {

    var POINTS_VIEW = 'modules/read/views/route_manager/route_points.php';
    var PANEL_SELECTOR = '#route_points_panel';
    var skeletonHtml = '';
    var lastRouteId = '';

    function escapeHtml(value) {
        if (value === null || value === undefined) {
            return '';
        }
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function $panel() {
        return jQuery(PANEL_SELECTOR);
    }

    // The first paint lives in route_manager.php. Keep a copy so a retry can
    // put the same skeleton back before the next request goes out.
    function captureSkeleton() {
        var $current = $panel();
        if ($current.length) {
            skeletonHtml = $current.prop('outerHTML');
        }
    }

    function showSkeleton() {
        if (!skeletonHtml) {
            return;
        }
        var $current = $panel();
        if (!$current.length) {
            return;
        }
        $current.replaceWith(skeletonHtml);
        $panel().attr('aria-busy', 'true');
    }

    function showError(message) {
        var $current = $panel();
        if (!$current.length) {
            return;
        }

        $current.attr('aria-busy', 'false');
        $current.find('.route-points').html(
            '<div class="route-explorer-error text-center py-5 px-3">'
            + '<i class="uil uil-exclamation-triangle d-block mb-2 fs-1 text-danger"></i>'
            + '<h6 class="mb-1">Unable to load departure points</h6>'
            + '<p class="text-muted mb-3">' + escapeHtml(message) + '</p>'
            + '<button type="button" class="btn btn-soft-primary btn-sm route-points-retry">'
            + '<i class="uil uil-refresh"></i> Try again</button>'
            + '</div>'
        );
    }

    function applyMarkup(html) {
        var nodes = jQuery.parseHTML(html, document, false);
        var $incoming = jQuery(nodes);
        var $replacement = $incoming.filter(PANEL_SELECTOR);
        if (!$replacement.length) {
            $replacement = $incoming.find(PANEL_SELECTOR);
        }

        if ($replacement.length) {
            $panel().replaceWith($replacement.first().attr('aria-busy', 'false'));
            lastRouteId = $panel().attr('data-route-id') || lastRouteId;
            return;
        }

        $panel().html(html).attr('aria-busy', 'false');
    }

    function loadRoutePoints(restoreSkeleton, routeId) {
        if (!$panel().length) {
            return;
        }

        if (restoreSkeleton) {
            showSkeleton();
        }

        var data = {};
        if (routeId) {
            lastRouteId = routeId;
            data.route_id = routeId;
        }

        jQuery.ajax({
            url: POINTS_VIEW,
            type: 'GET',
            data: data,
            dataType: 'html',
            cache: false,
            success: function (html) {
                if (!html || !String(html).trim()) {
                    showError('The departure points came back empty. Please try again.');
                    return;
                }
                applyMarkup(html);
            },
            error: function () {
                showError('The departure points could not be loaded. Please try again.');
            }
        });
    }

    if (!window.jQuery) {
        return;
    }

    jQuery(function () {
        captureSkeleton();
        jQuery(document).on('click', PANEL_SELECTOR + ' .route-points-retry', function () {
            loadRoutePoints(true);
        });
        jQuery(document).on('click', '.route-node-head', function () {
            var routeId = jQuery(this).closest('.route-node').attr('data-route-id');
            if (routeId) {
                loadRoutePoints(true, routeId);
            }
        });
        jQuery(document).on('click', '.route-leg', function () {
            var routeId = jQuery(this).attr('data-route-id');
            if (routeId) {
                loadRoutePoints(true, routeId);
            }
        });
        loadRoutePoints(false);
    });

    window.reloadRoutePoints = function (routeId) {
        loadRoutePoints(true, routeId);
    };

})();
