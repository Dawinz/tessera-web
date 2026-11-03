(function () {

    var DETAILS_ENDPOINT = 'modules/read/records/route_manager/route_details.php';

    // Values come straight out of the database and are dropped into template
    // strings, so everything has to be escaped on the way in.
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

    function placeholder() {
        return '<span class="text-muted">&mdash;</span>';
    }

    // Trims the trailing zeros a MySQL double/float brings along and groups
    // thousands, so 1500 shows as "1,500" and 3.40 as "3.4".
    function formatNumber(value) {
        var number = parseFloat(value);
        if (isNaN(number)) {
            return '';
        }
        var rounded = Math.round(number * 100) / 100;
        var parts = String(rounded).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return parts.join('.');
    }

    function formatDuration(value, unit) {
        var amount = parseFloat(value);
        if (isNaN(amount) || amount <= 0) {
            return '';
        }
        var label = String(unit || '').trim();
        if (amount === 1) {
            label = label.replace(/s$/i, '');
        }
        return formatNumber(amount) + (label === '' ? '' : ' ' + label);
    }

    function statusBadge(state) {
        return Number(state) === 1
            ? '<span class="badge bg-soft-success text-success">Active</span>'
            : '<span class="badge bg-soft-danger text-danger">Inactive</span>';
    }

    function sectionTitle(title, count) {
        var badge = count === undefined
            ? ''
            : ' <span class="badge bg-soft-primary text-primary">' + count + '</span>';
        return '<h6 class="route-details-section-title">' + escapeHtml(title) + badge + '</h6>';
    }

    // One label/value pair of the summary grid.
    function field(label, value) {
        return '<div class="route-details-field">'
            + '<span class="route-details-label">' + escapeHtml(label) + '</span>'
            + '<span class="route-details-value">' + (value === '' ? placeholder() : value) + '</span>'
            + '</div>';
    }

    function emptySection(message) {
        return '<p class="text-muted mb-0">' + escapeHtml(message) + '</p>';
    }

    function renderSummary(route) {
        return '<div class="route-details-head">'
            + '<span class="badge bg-soft-primary text-primary route-details-route-id">'
            + escapeHtml(route.route_id) + '</span>'
            + statusBadge(route.state)
            + '<h5 class="mt-2 mb-0">' + escapeHtml(route.route_name) + '</h5>'
            + (route.route_acronym_name
                ? '<small class="text-muted">' + escapeHtml(route.route_acronym_name) + '</small>'
                : '')
            + '</div>'
            + '<div class="route-details-grid">'
            + field('Starting Point', escapeHtml(route.start_route))
            + field('Ending Point', escapeHtml(route.end_route))
            + field('Duration', escapeHtml(formatDuration(route.journey_duration_value, route.journey_duration_unit)))
            + field('Distance', route.distance > 0 ? escapeHtml(formatNumber(route.distance) + ' km') : '')
            + field('Route Identifier', escapeHtml(route.route_initial_letters))
            + field('Map Coordinates', escapeHtml(route.map_coordinates))
            + field('Registered By', escapeHtml(route.uploader))
            + '</div>';
    }

    function renderDescription(description) {
        var text = String(description || '').trim();
        if (text === '') {
            return '';
        }
        return '<div class="route-details-section">'
            + sectionTitle('Description')
            + '<p class="mb-0">' + escapeHtml(text) + '</p>'
            + '</div>';
    }

    function renderNameList(title, names, emptyMessage) {
        var body = names.length === 0
            ? emptySection(emptyMessage)
            : '<ul class="route-details-list">'
                + names.map(function (name) {
                    return '<li>' + escapeHtml(name) + '</li>';
                }).join('')
                + '</ul>';

        return '<div class="route-details-section">'
            + sectionTitle(title, names.length)
            + body
            + '</div>';
    }

    function renderTicketPrices(prices) {
        var body = prices.length === 0
            ? emptySection('No ticket prices were set for this route.')
            : '<table class="table table-sm align-middle mb-0 route-details-table">'
                + '<thead><tr><th>Vehicle Class</th><th class="text-end">Fare</th></tr></thead>'
                + '<tbody>'
                + prices.map(function (price) {
                    return '<tr><td>' + escapeHtml(price.class) + '</td>'
                        + '<td class="text-end">'
                        + escapeHtml(formatNumber(price.amount) + ' ' + (price.currency || '')).trim()
                        + '</td></tr>';
                }).join('')
                + '</tbody></table>';

        return '<div class="route-details-section">'
            + sectionTitle('Ticket Prices', prices.length)
            + body
            + '</div>';
    }

    function renderStops(stops) {
        var body = stops.length === 0
            ? emptySection('No stops were added along this route.')
            : '<table class="table table-sm align-middle mb-0 route-details-table">'
                + '<thead><tr><th>Stop</th><th>Category</th><th>Duration</th></tr></thead>'
                + '<tbody>'
                + stops.map(function (stop) {
                    return '<tr><td>' + escapeHtml(stop.name) + '</td>'
                        + '<td>' + (stop.category ? escapeHtml(stop.category) : placeholder()) + '</td>'
                        + '<td>' + (stop.duration ? escapeHtml(stop.duration) : placeholder()) + '</td>'
                        + '</tr>';
                }).join('')
                + '</tbody></table>';

        return '<div class="route-details-section">'
            + sectionTitle('Stops', stops.length)
            + body
            + '</div>';
    }

    function renderRequirements(requirements) {
        var body = requirements.length === 0
            ? emptySection('No documents are required for this route.')
            : '<div class="d-flex flex-wrap gap-2 route-details-pills">'
                + requirements.map(function (name) {
                    return '<span class="badge bg-soft-info text-info">' + escapeHtml(name) + '</span>';
                }).join('')
                + '</div>';

        return '<div class="route-details-section">'
            + sectionTitle('Required Documents', requirements.length)
            + body
            + '</div>';
    }

    function renderDetails(payload) {
        var route = payload.route;
        return renderSummary(route)
            + renderDescription(route.description)
            + renderNameList('Departure Points', payload.departure_points || [],
                'No departure points were set for this route.')
            + renderNameList('Arrival Points', payload.arrival_points || [],
                'No arrival points were set for this route.')
            + renderTicketPrices(payload.ticket_prices || [])
            + renderStops(payload.stops || [])
            + renderRequirements(payload.requirements || []);
    }

    function skeletonBar(width, extraClass) {
        return '<div class="route-details-skeleton-bar' + (extraClass ? ' ' + extraClass : '')
            + '" style="width:' + width + '"></div>';
    }

    function skeletonField() {
        return '<div class="route-details-field">'
            + skeletonBar('55%')
            + skeletonBar('85%')
            + '</div>';
    }

    function skeletonSection(lineCount) {
        var lines = '';
        for (var i = 0; i < lineCount; i++) {
            lines += skeletonBar(i % 2 === 0 ? '92%' : '64%');
        }
        return '<div class="route-details-section">'
            + skeletonBar('35%', 'route-details-skeleton-title')
            + lines
            + '</div>';
    }

    // Mirrors the shape of the real content (header, summary grid, sections) so
    // the modal keeps its height when the response lands.
    function showSkeleton($body) {
        var fields = '';
        for (var i = 0; i < 6; i++) {
            fields += skeletonField();
        }

        $body.html('<div class="route-details-skeleton" aria-hidden="true">'
            + '<div class="route-details-head">'
            + '<div class="route-details-skeleton-badges">'
            + skeletonBar('90px', 'route-details-skeleton-badge')
            + skeletonBar('70px', 'route-details-skeleton-badge')
            + '</div>'
            + skeletonBar('60%', 'route-details-skeleton-title')
            + skeletonBar('35%')
            + '</div>'
            + '<div class="route-details-grid">' + fields + '</div>'
            + skeletonSection(2)
            + skeletonSection(3)
            + '</div>'
            + '<span class="visually-hidden">Loading route details...</span>');
    }

    function showError($body, message) {
        $body.html('<div class="text-center py-5">'
            + '<i class="uil uil-exclamation-triangle d-block mb-2 fs-1 text-danger"></i>'
            + '<h6 class="mb-1">Unable to load route details</h6>'
            + '<p class="text-muted mb-0">' + escapeHtml(message) + '</p>'
            + '</div>');
    }

    function handleLogout(message) {
        if (window.Swal) {
            Swal.fire({
                title: 'Session expired',
                text: message || 'Your session has expired. Please log in again.',
                icon: 'error',
                confirmButtonText: 'Log in'
            }).then(function () {
                window.location.href = 'log_out.php';
            });
            return;
        }
        window.location.href = 'log_out.php';
    }

    function openRouteDetails(routeId) {
        var $modal = jQuery('#route_details_modal');
        var $body = jQuery('#route_details_body');
        if (!$modal.length) {
            return;
        }

        jQuery('#route_details_subtitle').text(routeId);
        // Shown before the request goes out so the skeleton is what opens.
        showSkeleton($body);

        if (window.bootstrap && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance($modal[0]).show();
        }

        jQuery.ajax({
            url: DETAILS_ENDPOINT,
            type: 'POST',
            dataType: 'json',
            data: { route_id: routeId },
            success: function (response) {
                if (!response || !response.success) {
                    showError($body, (response && response.message) || 'Please try again.');
                    return;
                }
                jQuery('#route_details_subtitle').text(response.route.route_id);
                $body.html(renderDetails(response));
            },
            error: function (xhr) {
                var response = null;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (err) {}
                if (response && response.logout) {
                    handleLogout(response.message);
                    return;
                }
                showError($body, (response && response.message)
                    || 'The route details could not be loaded. Please try again.');
            }
        });
    }

    // Delegated because the rows are drawn (and redrawn) by DataTables.
    if (window.jQuery) {
        jQuery(function () {
            jQuery(document).on('click', '#routes_table .view_route', function () {
                openRouteDetails(jQuery(this).data('route-id'));
            });
        });
    }

})();
