(function () {

    var ROUTES_VIEW = 'modules/read/views/route_manager/routes.php';
    var PANEL_SELECTOR = '#route_network_panel';
    var skeletonHtml = '';

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
        $current.find('.route-explorer').html(
            '<div class="route-explorer-error text-center py-5 px-3">'
            + '<i class="uil uil-exclamation-triangle d-block mb-2 fs-1 text-danger"></i>'
            + '<h6 class="mb-1">Unable to load routes</h6>'
            + '<p class="text-muted mb-3">' + escapeHtml(message) + '</p>'
            + '<button type="button" class="btn btn-soft-primary btn-sm route-explorer-retry">'
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
            return;
        }

        $panel().html(html).attr('aria-busy', 'false');
    }

    function loadRoutes(restoreSkeleton) {
        if (!$panel().length) {
            return;
        }

        if (restoreSkeleton) {
            showSkeleton();
        }

        jQuery.ajax({
            url: ROUTES_VIEW,
            type: 'GET',
            dataType: 'html',
            cache: false,
            success: function (html) {
                if (!html || !String(html).trim()) {
                    showError('The route network came back empty. Please try again.');
                    return;
                }
                applyMarkup(html);
            },
            error: function () {
                showError('The route network could not be loaded. Please try again.');
            }
        });
    }

    if (!window.jQuery) {
        return;
    }

    jQuery(function () {
        captureSkeleton();
        jQuery(document).on('click', PANEL_SELECTOR + ' .route-explorer-retry', function () {
            loadRoutes(true);
        });
        loadRoutes(false);
    });

    window.reloadRouteNetwork = function () {
        loadRoutes(true);
    };

})();
