(function () {

    var MODAL_ID = 'sub_route_modal';
    var ADD_SUB_ROUTE_ENDPOINT = 'modules/create/records/route_manager/sub_route.php';
    var LOCATION_POINTS_ENDPOINT = 'modules/read/search/dependency.php/route_location.php';

    function injectShakeStyles() {
        if (document.getElementById('register-route-shake-styles')) {
            return;
        }
        var style = document.createElement('style');
        style.id = 'register-route-shake-styles';
        style.textContent =
            '@keyframes registerRouteShake {' +
            '0%, 100% { transform: translateX(0); }' +
            '20% { transform: translateX(-6px); }' +
            '40% { transform: translateX(6px); }' +
            '60% { transform: translateX(-4px); }' +
            '80% { transform: translateX(4px); }' +
            '}' +
            '.register-route-shake { animation: registerRouteShake 0.4s ease-in-out; }' +
            '.select2-container .select2-selection.is-invalid { border-color: #dc3545 !important; }' +
            '.default-fare-notice.swal2-popup .swal2-title,' +
            '.default-fare-notice.swal2-popup .swal2-html-container,' +
            '.default-fare-notice.swal2-popup .swal2-content { color: #fff !important; }';
        document.head.appendChild(style);
    }

    // select2 hides the real <select> and renders its own widget next to it,
    // so error styling/shaking has to target that widget instead of the select.
    function getVisualTarget(el) {
        if (el.tagName === 'SELECT' && el.classList.contains('select2-hidden-accessible')) {
            var container = el.nextElementSibling;
            if (container && container.classList.contains('select2-container')) {
                return container.querySelector('.select2-selection') || container;
            }
        }
        return el;
    }

    function shakeField(field) {
        var target = field.el.type === 'checkbox' ? (field.el.closest('.form-check') || field.el) : getVisualTarget(field.el);
        target.classList.remove('register-route-shake');
        // force reflow so the animation restarts if triggered again
        void target.offsetWidth;
        target.classList.add('register-route-shake');
        target.addEventListener('animationend', function handler() {
            target.classList.remove('register-route-shake');
            target.removeEventListener('animationend', handler);
        });
    }

    function toArray(nodeList) {
        return Array.prototype.slice.call(nodeList);
    }

    var STAR_EMPTY_SRC = 'assets/libs/rater-js/lib/star_0.svg';
    var STAR_FILLED_SRC = 'assets/libs/rater-js/lib/star_1.svg';
    var STAR_COUNT = 5;
    // Matches .vehicle-class-star in route_manager.css; also set as an
    // attribute on each <img> so the SVGs can never paint at their own
    // 109x104 size before the stylesheet applies.
    var STAR_SIZE = 12;

    function buildStarImage(src) {
        return jQuery('<img alt="">')
            .attr({ src: src, width: STAR_SIZE, height: STAR_SIZE });
    }

    // Draws a 0-5 rating as five star images; a fractional rating clips the
    // gold star over the grey one so e.g. 4.3 shows 30% of the fifth star.
    function buildRatingStars(rating) {
        var $stars = jQuery('<span class="vehicle-class-rating"></span>');
        for (var i = 0; i < STAR_COUNT; i++) {
            var fill = Math.min(Math.max(rating - i, 0), 1);
            var $star = jQuery('<span class="vehicle-class-star"></span>')
                .append(buildStarImage(STAR_EMPTY_SRC));
            if (fill > 0) {
                $star.append(
                    jQuery('<span class="vehicle-class-star-fill"></span>')
                        .css('width', (fill * 100) + '%')
                        .append(buildStarImage(STAR_FILLED_SRC))
                );
            }
            $stars.append($star);
        }
        return $stars;
    }

    function formatVehicleClass(state) {
        if (!state.id || !state.element) {
            return state.text;
        }
        var rating = parseFloat(state.element.getAttribute('data-rating'));
        if (isNaN(rating)) {
            return state.text;
        }
        return jQuery('<span class="vehicle-class-option"></span>')
            .append(jQuery('<span class="vehicle-class-name"></span>').text(state.text))
            .append(buildRatingStars(rating))
            .append(jQuery('<span class="vehicle-class-rating-value"></span>').text(rating.toFixed(1)));
    }

    // Makes a <select>'s options searchable via select2 (already loaded on this page).
    function initSelect2(select) {
        if (!window.jQuery || !jQuery.fn.select2) {
            return;
        }
        var $select = jQuery(select);
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        // strip any select2 markup that may have been copied over by clone()
        $select.next('.select2-container').remove();
        var settings = {
            dropdownParent: jQuery('#' + MODAL_ID),
            width: '100%'
        };
        if (select.name === 'vehicle_type[]') {
            settings.templateResult = formatVehicleClass;
            settings.templateSelection = formatVehicleClass;
        }
        $select.select2(settings);
    }

    function initAllSelect2() {
        toArray(document.querySelectorAll('#add_sub_route_form select.selectpicker')).forEach(initSelect2);
    }

    // Required fields on the "Basic Info" step (marked with * in the form)
    function getBasicInfoFields() {
        return [
            {
                el: document.getElementById('sub_route_starting_point'),
                message: 'Please choose the sub route starting point.',
                isEmpty: function (el) {
                    var v = el.value;
                    return !v || v === 'Choose Starting Point';
                }
            },
            {
                el: document.getElementById('sub_route_ending_point'),
                message: 'Please choose the sub route ending point.',
                isEmpty: function (el) {
                    var v = el.value;
                    return !v || v === 'Choose Ending Point';
                }
            },
            {
                el: document.getElementById('sub_journey_duration_value'),
                message: 'Please enter the journey duration in hours, e.g. 3.',
                isEmpty: function (el) {
                    var v = el.value.trim();
                    return v === '' || !/^\d+$/.test(v) || parseInt(v, 10) < 1;
                }
            },
            {
                el: document.getElementById('sub_identifer'),
                message: 'Please enter a route identifier (up to 3 letters), e.g. DAR.',
                isEmpty: function (el) {
                    return !/^[A-Za-z]{1,3}$/.test(el.value.trim());
                }
            }
        ].filter(function (field) {
            return !!field.el;
        });
    }

    function isStartingPointEmpty(el) {
        var v = el.value;
        return !v || v === 'Choose Starting Point';
    }

    function isEndingPointEmpty(el) {
        var v = el.value;
        return !v || v === 'Choose Ending Point';
    }

    // Required fields on the "Departure Points" step (at least one pick up point)
    function getPickUpPointFields() {
        return toArray(document.querySelectorAll('#sub_pick_up_points select[name="starting_point[]"]')).map(function (el) {
            return {
                el: el,
                message: 'Please choose a departure point.',
                isEmpty: isStartingPointEmpty
            };
        });
    }

    // Required fields on the "Arrival Point" step (at least one arrival point)
    function getArrivalPointFields() {
        return toArray(document.querySelectorAll('#sub_arrival_point select[name="ending_point[]"]')).map(function (el) {
            return {
                el: el,
                message: 'Please choose an arrival point.',
                isEmpty: isEndingPointEmpty
            };
        });
    }

    function isVehicleTypeEmpty(el) {
        var v = el.value;
        return !v || v === 'Choose Vehicle Type';
    }

    function isFareAmountEmpty(el) {
        var v = el.value.trim().replace(/,/g, '');
        return v === '' || isNaN(Number(v));
    }

    function isFareCurrencyEmpty(el) {
        var v = el.value;
        return !v || v === 'Choose Currency';
    }

    // Required fields on the "Ticket Price" step (vehicle type, fare amount, currency per row)
    function getTicketPriceFields() {
        var vehicleFields = toArray(document.querySelectorAll('#sub_ticket_price select[name="vehicle_type[]"]')).map(function (el) {
            return {
                el: el,
                message: 'Please choose a vehicle type.',
                isEmpty: isVehicleTypeEmpty
            };
        });
        var fareFields = toArray(document.querySelectorAll('#sub_ticket_price input[name="fare_amount[]"]')).map(function (el) {
            return {
                el: el,
                message: 'Please enter a valid fare amount.',
                isEmpty: isFareAmountEmpty
            };
        });
        var currencyFields = toArray(document.querySelectorAll('#sub_ticket_price select[name="fare_currency[]"]')).map(function (el) {
            return {
                el: el,
                message: 'Please choose a currency.',
                isEmpty: isFareCurrencyEmpty
            };
        });
        return vehicleFields.concat(fareFields, currencyFields);
    }

    function isStopEmpty(el) {
        var v = el.value;
        return !v || v === 'Choose Stop';
    }

    var STOP_ROW_MESSAGE = 'Please fill in both stop and duration, or leave both empty.';

    // A stop row is optional as a whole, but if either the stop or its
    // duration is filled in, the other one becomes required too.
    function revalidateStopRow(row) {
        var stopEl = row.querySelector('select[name="stops[]"]');
        var durationEl = row.querySelector('input[name="stop_duration[]"]');
        if (!stopEl || !durationEl) {
            return true;
        }

        var stopEmpty = isStopEmpty(stopEl);
        var durationEmpty = durationEl.value.trim() === '';

        if (stopEmpty && durationEmpty) {
            clearFieldError({ el: stopEl });
            clearFieldError({ el: durationEl });
            return true;
        }
        if (stopEmpty) {
            showFieldError({ el: stopEl, message: STOP_ROW_MESSAGE });
            clearFieldError({ el: durationEl });
            return false;
        }
        if (durationEmpty) {
            showFieldError({ el: durationEl, message: STOP_ROW_MESSAGE });
            clearFieldError({ el: stopEl });
            return false;
        }
        clearFieldError({ el: stopEl });
        clearFieldError({ el: durationEl });
        return true;
    }

    function getFieldContainer(field) {
        var isCheckbox = field.el.type === 'checkbox';
        var container = isCheckbox ? field.el.closest('.form-check') : field.el.closest('.mb-3');
        if (!container) {
            container = field.el.closest('td') || field.el.parentElement;
        }
        return container;
    }

    function getFeedbackEl(field) {
        var container = getFieldContainer(field);
        var feedback = container.querySelector('.js-validation-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback d-block js-validation-feedback';
            container.appendChild(feedback);
        }
        return feedback;
    }

    function showFieldError(field) {
        field.el.classList.add('is-invalid');
        getVisualTarget(field.el).classList.add('is-invalid');
        var feedback = getFeedbackEl(field);
        feedback.textContent = field.message;
        feedback.style.display = 'block';
        shakeField(field);
    }

    function clearFieldError(field) {
        field.el.classList.remove('is-invalid');
        getVisualTarget(field.el).classList.remove('is-invalid');
        var container = getFieldContainer(field);
        var feedback = container.querySelector('.js-validation-feedback');
        if (feedback) {
            feedback.style.display = 'none';
        }
    }

    function validateFields(fields) {
        var isValid = true;
        var firstInvalidEl = null;

        fields.forEach(function (field) {
            if (field.isEmpty(field.el)) {
                isValid = false;
                showFieldError(field);
                if (!firstInvalidEl) {
                    firstInvalidEl = field.el;
                }
            } else {
                clearFieldError(field);
            }
        });

        if (firstInvalidEl) {
            firstInvalidEl.focus();
        }

        return isValid;
    }

    function validateBasicInfo() {
        return validateFields(getBasicInfoFields());
    }

    function validatePickUpPoints() {
        return validateFields(getPickUpPointFields());
    }

    function validateArrivalPoints() {
        return validateFields(getArrivalPointFields());
    }

    function validateTicketPrice() {
        return validateFields(getTicketPriceFields());
    }

    function validateStops() {
        var rows = toArray(document.querySelectorAll('#sub_stops .sub-route-stop-row'));
        var isValid = true;
        var firstInvalidRow = null;

        rows.forEach(function (row) {
            if (!revalidateStopRow(row)) {
                isValid = false;
                if (!firstInvalidRow) {
                    firstInvalidRow = row;
                }
            }
        });

        if (firstInvalidRow) {
            var focusEl = firstInvalidRow.querySelector('.is-invalid');
            if (focusEl) {
                focusEl.focus();
            }
        }

        return isValid;
    }

    function attachLiveClearing() {
        getBasicInfoFields().forEach(function (field) {
            var eventName = (field.el.tagName === 'SELECT' || field.el.type === 'checkbox') ? 'change' : 'input';
            field.el.addEventListener(eventName, function () {
                if (!field.isEmpty(field.el)) {
                    clearFieldError(field);
                }
            });
        });

        // Rows on the dynamic-row steps are added and removed at runtime,
        // so their live-clearing is delegated instead of bound per-element.
        jQuery('#add_sub_route_form').on('change', 'select', function () {
            var target = this;
            if (target.name === 'starting_point[]' && !isStartingPointEmpty(target)) {
                clearFieldError({ el: target });
            } else if (target.name === 'ending_point[]' && !isEndingPointEmpty(target)) {
                clearFieldError({ el: target });
            } else if (target.name === 'vehicle_type[]' && !isVehicleTypeEmpty(target)) {
                clearFieldError({ el: target });
            } else if (target.name === 'fare_currency[]' && !isFareCurrencyEmpty(target)) {
                clearFieldError({ el: target });
            } else if (target.name === 'stops[]') {
                var stopRow = target.closest('.sub-route-stop-row');
                if (stopRow) {
                    revalidateStopRow(stopRow);
                }
            }
        });

        jQuery('#add_sub_route_form').on('input', 'input, textarea', function () {
            var target = this;
            if (target.id === 'sub_distance_in_km') {
                restrictToFloatInput(target);
            } else if (target.id === 'sub_identifer') {
                restrictToLettersInput(target, 3);
            } else if (target.name === 'fare_amount[]') {
                restrictToCurrencyInput(target);
                if (!isFareAmountEmpty(target)) {
                    clearFieldError({ el: target });
                }
                if (isFirstFareAmountInput(target)) {
                    showDefaultFareNotice();
                }
            } else if (target.name === 'stop_duration[]') {
                var stopRow = target.closest('.sub-route-stop-row');
                if (stopRow) {
                    revalidateStopRow(stopRow);
                }
            }
        });
    }

    // Keeps the distance input to digits with at most one decimal point,
    // e.g. "3.4" - strips anything else as the user types.
    function restrictToFloatInput(input) {
        var value = input.value.replace(/[^0-9.]/g, '');
        var firstDot = value.indexOf('.');
        if (firstDot !== -1) {
            value = value.slice(0, firstDot + 1) + value.slice(firstDot + 1).replace(/\./g, '');
        }
        if (value !== input.value) {
            input.value = value;
        }
    }

    // Keeps an input to letters only, up to maxLength characters,
    // e.g. the route identifier ("DAR").
    function restrictToLettersInput(input, maxLength) {
        var value = input.value.replace(/[^A-Za-z]/g, '').slice(0, maxLength);
        if (value !== input.value) {
            input.value = value;
        }
    }

    // Formats a fare amount input as the user types: digits with at most one
    // decimal point (up to 2 decimal places), and a thousands-separator comma
    // in the integer part, e.g. "100000" -> "100,000", "1234.5" -> "1,234.5".
    function restrictToCurrencyInput(input) {
        var raw = input.value;
        var cursorPos = input.selectionStart === null ? raw.length : input.selectionStart;
        var digitsBeforeCursor = raw.slice(0, cursorPos).replace(/[^0-9]/g, '').length;

        var cleaned = raw.replace(/[^0-9.]/g, '');
        var firstDot = cleaned.indexOf('.');
        var integerPart = firstDot === -1 ? cleaned : cleaned.slice(0, firstDot);
        var decimalPart = firstDot === -1 ? null : cleaned.slice(firstDot + 1).replace(/\./g, '').slice(0, 2);

        integerPart = integerPart.replace(/^0+(?=\d)/, '');
        var formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        var formatted = formattedInteger + (decimalPart !== null ? '.' + decimalPart : '');

        if (formatted === input.value) {
            return;
        }
        input.value = formatted;

        if (digitsBeforeCursor === 0) {
            input.setSelectionRange(0, 0);
            return;
        }
        var digitsSeen = 0;
        var newPos = formatted.length;
        for (var i = 0; i < formatted.length; i++) {
            if (/[0-9]/.test(formatted[i])) {
                digitsSeen++;
            }
            if (digitsSeen === digitsBeforeCursor) {
                newPos = i + 1;
                break;
            }
        }
        input.setSelectionRange(newPos, newPos);
    }

    var defaultFareNoticeShown = false;

    function isFirstFareAmountInput(input) {
        var firstRow = document.querySelector('#sub_ticket_price_rows .sub-ticket-price-row');
        return !!(firstRow && firstRow.contains(input));
    }

    function showDefaultFareNotice() {
        if (defaultFareNoticeShown) {
            return;
        }
        defaultFareNoticeShown = true;

        if (!window.Swal) {
            return;
        }

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Default sub route fare',
            text: 'The first fare you enter will be used as the default fare for this sub route. Additional fares will be saved for their vehicle classes only.',
            showConfirmButton: false,
            timer: 5500,
            timerProgressBar: true,
            customClass: {
                popup: 'default-fare-notice'
            }
        });
    }

    // A row is cloned (including any error state) when "Add Pick Up Point" /
    // "Add Arrival Point" is clicked, so strip any inherited error state
    // from the freshly added row.
    function cleanClonedRow(row) {
        if (!row) {
            return;
        }
        toArray(row.querySelectorAll('.is-invalid')).forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        toArray(row.querySelectorAll('.js-validation-feedback')).forEach(function (el) {
            el.remove();
        });
        toArray(row.querySelectorAll('select.selectpicker')).forEach(initSelect2);
    }

    // Clones the first row of a step's table, blanks it out and appends it.
    function addRow(containerId, rowClass) {
        var $container = jQuery('#' + containerId);
        var $newRow = $container.find('.' + rowClass).first().clone();
        // select2 copies its own widget into the clone; drop it so the fresh
        // <select> can be re-initialised from scratch.
        $newRow.find('.select2-container').remove();
        $newRow.find('select')
            .removeClass('select2-hidden-accessible')
            .removeAttr('data-select2-id tabindex aria-hidden')
            .each(function () {
                this.value = this.options.length ? this.options[0].value : '';
            });
        $newRow.find('input').val('');
        $container.append($newRow);
        cleanClonedRow($newRow[0]);
    }

    function removeRow(button, containerId, rowClass) {
        if (jQuery('#' + containerId + ' .' + rowClass).length > 1) {
            jQuery(button).closest('.' + rowClass).remove();
        }
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // Swaps every matching select's options (keeping its own placeholder) for
    // the points that belong to the chosen location, the way
    // js/dependence/location_dependecy.js does it for the add route modal.
    function updateSelectOptions(selectors, points) {
        var optionsHtml = points.map(function (point) {
            return '<option value="' + escapeHtml(point.id) + '">' + escapeHtml(point.name) + '</option>';
        }).join('');

        toArray(document.querySelectorAll(selectors)).forEach(function (select) {
            var placeholder = select.options.length ? select.options[0].outerHTML : '';
            var currentValue = select.value;

            select.innerHTML = placeholder + optionsHtml;

            var stillExists = toArray(select.options).some(function (option) {
                return option.value === currentValue;
            });
            select.value = stillExists ? currentValue : select.options[0].value;

            if (jQuery.fn.select2 && jQuery(select).hasClass('select2-hidden-accessible')) {
                jQuery(select).trigger('change');
            }
        });
    }

    function loadLocationPoints(pointId, targetSelectors) {
        jQuery.ajax({
            type: 'POST',
            url: LOCATION_POINTS_ENDPOINT,
            data: { point_id: pointId },
            dataType: 'json',
            success: function (points) {
                if (Array.isArray(points) && points.length > 0) {
                    updateSelectOptions(targetSelectors, points);
                }
            }
        });
    }

    var $tabs = null;
    var $navItems = null;
    var currentStep = 0;

    function showStep(index) {
        $tabs.removeClass('active-step').eq(index).addClass('active-step');
        $navItems.removeClass('active done');
        $navItems.each(function (i) {
            if (i < index) {
                jQuery(this).addClass('done');
            } else if (i === index) {
                jQuery(this).addClass('active');
            }
        });
        jQuery('#add_sub_route_prev').toggleClass('d-none', index === 0);
        jQuery('#add_sub_route_next').toggleClass('d-none', index === $tabs.length - 1);
        jQuery('#add_sub_route_submit').toggleClass('d-none', index !== $tabs.length - 1);
        currentStep = index;
    }

    function validateCurrentStep() {
        var activeTab = document.querySelector('.add-sub-route-wizard-tab.active-step');
        if (!activeTab) {
            return true;
        }
        if (activeTab.id === 'sub_basic_info') {
            return validateBasicInfo();
        }
        if (activeTab.id === 'sub_pick_up_points') {
            return validatePickUpPoints();
        }
        if (activeTab.id === 'sub_arrival_point') {
            return validateArrivalPoints();
        }
        if (activeTab.id === 'sub_ticket_price') {
            return validateTicketPrice();
        }
        if (activeTab.id === 'sub_stops') {
            return validateStops();
        }
        return true;
    }

    // Drops the error marks and the extra rows left behind by the previous sub
    // route, so the modal always opens the way it was rendered.
    function resetForm() {
        var form = document.getElementById('add_sub_route_form');
        if (!form) {
            return;
        }
        form.reset();
        toArray(form.querySelectorAll('.is-invalid')).forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        toArray(form.querySelectorAll('.js-validation-feedback')).forEach(function (el) {
            el.remove();
        });
        [
            ['sub_pick_up_points_rows', 'sub-pick-up-point-row'],
            ['sub_arrival_point_rows', 'sub-arrival-point-row'],
            ['sub_ticket_price_rows', 'sub-ticket-price-row'],
            ['sub_stops_rows', 'sub-route-stop-row']
        ].forEach(function (step) {
            jQuery('#' + step[0] + ' .' + step[1]).not(':first').remove();
        });
        // form.reset() puts the native <select> back on its first option
        // without select2 noticing, so the widget has to be told to redraw.
        jQuery(form).find('select.select2-hidden-accessible').trigger('change.select2');
        defaultFareNoticeShown = false;
    }

    function setMainRouteId(button) {
        if (!button || !button.getAttribute) {
            return;
        }

        var routeId = button.getAttribute('data-route-id') || '';
        var routeName = button.getAttribute('data-route-name') || '';
        var field = document.getElementById('sub_route_main_route_id');

        if (field) {
            field.value = routeId;
            field.setAttribute('value', routeId);
        }

        jQuery('#sub_route_parent_name').text(routeName === '' ? '' : 'Sub route of ' + routeName);
    }

    // Prepares the modal for the route whose "Sub route" button was clicked.
    // Bootstrap opens the modal via data-bs-toggle on the button.
    function openSubRouteModal(button) {
        resetForm();
        setMainRouteId(button);
        showStep(0);
    }

    function handleLogoutError(message) {
        var logoutMessage = message || 'Your session has expired. Please log in again.';
        if (window.Swal) {
            Swal.fire({
                title: 'Session expired',
                text: logoutMessage,
                icon: 'error',
                confirmButtonText: 'Log in'
            }).then(function () {
                window.location.href = 'log_out.php';
            });
            return;
        }
        window.location.href = 'log_out.php';
    }

    function submitSubRouteForm(form) {
        if (!window.jQuery) {
            return;
        }
        var $form = jQuery(form);
        var $submitBtn = jQuery('#add_sub_route_submit');

        jQuery.ajax({
            type: 'POST',
            url: ADD_SUB_ROUTE_ENDPOINT,
            data: new FormData(form),
            contentType: false,
            processData: false,
            cache: false,
            // For testing: keep the raw response as text instead of letting
            // jQuery auto-parse it as JSON, so PHP warnings/notices mixed
            // into the output are visible instead of causing a silent parse failure.
            dataType: 'text',
            beforeSend: function () {
                $submitBtn.prop('disabled', true);
            },
            success: function (response) {
                alert(response);
            },
            error: function (xhr) {
                alert(xhr.responseText);
            },
            complete: function () {
                $submitBtn.prop('disabled', false);
            }
        });
    }

    function init() {
        if (!window.jQuery) {
            return;
        }

        $tabs = jQuery('.add-sub-route-wizard-tab');
        $navItems = jQuery('#add_sub_route_wizard_nav .list-item');
        if (!$tabs.length) {
            return;
        }

        injectShakeStyles();
        attachLiveClearing();
        showStep(0);
        initAllSelect2();

        // Delegated: the route explorer is redrawn whenever the route list reloads.
        jQuery(document).on('click', '.route-node-add-leg', function (e) {
            e.preventDefault();
            openSubRouteModal(this);
        });

        jQuery('#add_sub_route_next').on('click', function () {
            if (currentStep < $tabs.length - 1 && validateCurrentStep()) {
                showStep(currentStep + 1);
            }
        });

        jQuery('#add_sub_route_prev').on('click', function () {
            if (currentStep > 0) {
                showStep(currentStep - 1);
            }
        });

        // Bootstrap opens the modal via data-bs-toggle; relatedTarget is the
        // "Sub route" button, so copy its route_id into the hidden field.
        jQuery('#' + MODAL_ID).on('show.bs.modal', function (e) {
            if (e.relatedTarget) {
                setMainRouteId(e.relatedTarget);
            }
        });

        // select2 sizes itself off the visible DOM, so re-run once the modal
        // (and its width) is actually on screen.
        jQuery('#' + MODAL_ID).on('shown.bs.modal', function () {
            initAllSelect2();
            defaultFareNoticeShown = false;
        });

        jQuery('#add_sub_pick_up_point_btn').on('click', function () {
            addRow('sub_pick_up_points_rows', 'sub-pick-up-point-row');
        });
        jQuery('#sub_pick_up_points_rows').on('click', '.remove_sub_pick_up_point', function () {
            removeRow(this, 'sub_pick_up_points_rows', 'sub-pick-up-point-row');
        });

        jQuery('#add_sub_arrival_point_btn').on('click', function () {
            addRow('sub_arrival_point_rows', 'sub-arrival-point-row');
        });
        jQuery('#sub_arrival_point_rows').on('click', '.remove_sub_arrival_point', function () {
            removeRow(this, 'sub_arrival_point_rows', 'sub-arrival-point-row');
        });

        jQuery('#add_sub_ticket_price_btn').on('click', function () {
            addRow('sub_ticket_price_rows', 'sub-ticket-price-row');
        });
        jQuery('#sub_ticket_price_rows').on('click', '.remove_sub_ticket_price', function () {
            removeRow(this, 'sub_ticket_price_rows', 'sub-ticket-price-row');
        });

        jQuery('#add_sub_stop_btn').on('click', function () {
            addRow('sub_stops_rows', 'sub-route-stop-row');
        });
        jQuery('#sub_stops_rows').on('click', '.remove-sub-stop', function () {
            removeRow(this, 'sub_stops_rows', 'sub-route-stop-row');
        });

        jQuery(document).on('change', '#sub_route_starting_point', function () {
            loadLocationPoints(jQuery(this).val(), '#sub_pick_up_points select[name="starting_point[]"]');
        });

        jQuery(document).on('change', '#sub_route_ending_point', function () {
            loadLocationPoints(jQuery(this).val(), '#sub_arrival_point select[name="ending_point[]"]');
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form && form.id === 'add_sub_route_form') {
            e.preventDefault();
            e.stopImmediatePropagation();

            var basicInfoValid = validateBasicInfo();
            var pickUpValid = validatePickUpPoints();
            var arrivalValid = validateArrivalPoints();
            var ticketPriceValid = validateTicketPrice();
            var stopsValid = validateStops();
            if (!basicInfoValid || !pickUpValid || !arrivalValid || !ticketPriceValid || !stopsValid) {
                return;
            }

            submitSubRouteForm(form);
        }
    }, true);

})();
