(function () {

    var SCHEDULES_VIEW = 'modules/read/views/route_manager/schedules.php';
    var PANEL_SELECTOR = '#route_schedule_panel';
    var skeletonHtml = '';
    var lastRouteId = '';
    var lastFilters = {
        bus: '',
        date: '',
        trip_date: ''
    };
    var applyingFilters = false;

    function emptyFilters() {
        return { bus: '', date: '', trip_date: '' };
    }

    function readFilters() {
        return {
            bus: jQuery('#schedule_filter_bus').val() || '',
            date: jQuery('#schedule_filter_date').val() || '',
            trip_date: jQuery('#schedule_filter_trip_date').val() || ''
        };
    }

    function $panel() {
        return jQuery(PANEL_SELECTOR);
    }

    function syncFiltersFromPanel() {
        var $current = $panel();
        lastFilters = {
            bus: $current.attr('data-filter-bus') || '',
            date: $current.attr('data-filter-date') || '',
            trip_date: $current.attr('data-filter-trip-date') || ''
        };
    }

    function lockDatePickerInput(el) {
        if (!el) {
            return;
        }
        el.setAttribute('readonly', 'readonly');
        el.setAttribute('autocomplete', 'off');
        el.addEventListener('paste', function (e) {
            e.preventDefault();
        });
        el.addEventListener('drop', function (e) {
            e.preventDefault();
        });
        el.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && String(e.key).toLowerCase() === 'v') {
                e.preventDefault();
            }
        });
    }

    function initFilterDatePicker(input) {
        if (!input || typeof window.flatpickr !== 'function' || input._flatpickr) {
            return;
        }
        window.flatpickr(input, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'D, j M Y',
            allowInput: false,
            disableMobile: true,
            animate: true,
            monthSelectorType: 'static',
            onReady: function (selectedDates, dateStr, instance) {
                instance.calendarContainer.classList.add('schedule-flatpickr');
                lockDatePickerInput(instance.input);
                if (instance.altInput) {
                    instance.altInput.classList.add('form-control', 'form-control-sm');
                    instance.altInput.setAttribute('placeholder', input.getAttribute('placeholder') || 'Date');
                    lockDatePickerInput(instance.altInput);
                }
            },
            onChange: function () {
                if (applyingFilters) {
                    return;
                }
                loadSchedules(false, lastRouteId, readFilters());
            }
        });
    }

    function initScheduleFilters() {
        applyingFilters = true;
        var $bus = jQuery('#schedule_filter_bus');
        if ($bus.length && jQuery.fn.select2) {
            if ($bus.hasClass('select2-hidden-accessible')) {
                $bus.select2('destroy');
            }
            $bus.next('.select2-container').remove();
            $bus.select2({
                dropdownParent: $panel(),
                dropdownCssClass: 'schedule-filter-select2',
                width: '100%',
                placeholder: 'All buses',
                allowClear: true,
                minimumResultsForSearch: 0
            });
        }
        jQuery('#schedule_filter_date, #schedule_filter_trip_date').each(function () {
            initFilterDatePicker(this);
        });
        applyingFilters = false;
    }

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
        $current.find('.route-schedule').html(
            '<div class="route-explorer-error text-center py-5 px-3">'
            + '<i class="uil uil-exclamation-triangle d-block mb-2 fs-1 text-danger"></i>'
            + '<h6 class="mb-1">Unable to load schedules</h6>'
            + '<p class="text-muted mb-3">' + escapeHtml(message) + '</p>'
            + '<button type="button" class="btn btn-soft-primary btn-sm route-schedule-retry">'
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
            syncFiltersFromPanel();
            initScheduleFilters();
            return;
        }

        $panel().html(html).attr('aria-busy', 'false');
        syncFiltersFromPanel();
        initScheduleFilters();
    }

    function loadSchedules(restoreSkeleton, routeId, filters) {
        if (!$panel().length) {
            return;
        }

        if (restoreSkeleton) {
            showSkeleton();
        }

        if (filters) {
            lastFilters = {
                bus: filters.bus || '',
                date: filters.date || '',
                trip_date: filters.trip_date || ''
            };
        }

        var data = {
            bus: lastFilters.bus,
            date: lastFilters.date,
            trip_date: lastFilters.trip_date
        };
        if (routeId) {
            lastRouteId = routeId;
            data.route_id = routeId;
        } else if (lastRouteId) {
            data.route_id = lastRouteId;
        }

        jQuery.ajax({
            url: SCHEDULES_VIEW,
            type: 'GET',
            data: data,
            dataType: 'html',
            cache: false,
            success: function (html) {
                if (!html || !String(html).trim()) {
                    showError('The weekly schedule came back empty. Please try again.');
                    return;
                }
                applyMarkup(html);
            },
            error: function () {
                showError('The weekly schedule could not be loaded. Please try again.');
            }
        });
    }

    if (!window.jQuery) {
        return;
    }

    jQuery(function () {
        captureSkeleton();
        jQuery(document).on('click', PANEL_SELECTOR + ' .route-schedule-retry', function () {
            loadSchedules(true, lastRouteId, lastFilters);
        });
        jQuery(document).on('change', '#schedule_filter_bus', function () {
            if (applyingFilters) {
                return;
            }
            loadSchedules(false, lastRouteId, readFilters());
        });
        jQuery(document).on('click', '.route-node-head', function () {
            var routeId = jQuery(this).closest('.route-node').attr('data-route-id');
            if (routeId) {
                lastFilters = emptyFilters();
                loadSchedules(true, routeId, lastFilters);
            }
        });
        jQuery(document).on('click', '.route-leg', function () {
            var routeId = jQuery(this).attr('data-route-id');
            if (routeId) {
                lastFilters = emptyFilters();
                loadSchedules(true, routeId, lastFilters);
            }
        });
        loadSchedules(false);
    });

    window.reloadRouteSchedules = function (routeId) {
        loadSchedules(true, routeId, lastFilters);
    };

})();
