(function () {

    var ADD_SCHEDULE_ENDPOINT = 'modules/create/records/route_manager/schedule.php';

    function syncTimeHidden($wrap) {
        var hour = $wrap.find('.schedule-time-hour').val();
        var minute = $wrap.find('.schedule-time-minute').val();
        $wrap.find('input[type="hidden"]').val((hour && minute) ? (hour + ':' + minute) : '');
    }

    function syncAllTimes(form) {
        jQuery(form).find('.schedule-time-24').each(function () {
            syncTimeHidden(jQuery(this));
        });
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

    function submitScheduleForm(form) {
        if (!window.jQuery) {
            return;
        }

        syncAllTimes(form);

        var $routeId = jQuery('#schedule_route_id');
        if ($routeId.length && !$routeId.val()) {
            $routeId.val(
                jQuery('#route_points_panel').attr('data-route-id')
                || jQuery('.route-node[open]').first().attr('data-route-id')
                || jQuery('.route-node').first().attr('data-route-id')
                || ''
            );
        }

        var $submitBtn = jQuery('#add_schedule_submit');

        jQuery.ajax({
            type: 'POST',
            url: ADD_SCHEDULE_ENDPOINT,
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
                var result = response;
                if (typeof result === 'string') {
                    try {
                        result = JSON.parse(result);
                    } catch (err) {
                        result = null;
                    }
                }

                if (result && result.logout) {
                    handleLogoutError(result.message);
                    return;
                }

                if (!result || result.success === false) {
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Unable to save schedule',
                            text: (result && result.message) ? result.message : 'Please try again.',
                            icon: 'error'
                        });
                    }
                    return;
                }

                if (window.Swal) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Schedule has been saved',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }

                var routeId = jQuery('#schedule_route_id').val()
                    || jQuery('#route_schedule_panel').attr('data-route-id')
                    || jQuery('#route_points_panel').attr('data-route-id')
                    || '';
                var modalEl = document.getElementById('schedule_modal');
                if (modalEl && window.bootstrap && bootstrap.Modal) {
                    var modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) {
                        modal.hide();
                    }
                } else if (window.jQuery && jQuery.fn.modal) {
                    jQuery('#schedule_modal').modal('hide');
                }
                if (typeof window.reloadRouteSchedules === 'function') {
                    window.reloadRouteSchedules(routeId);
                }
            },
            error: function (xhr) {
                var result = {};
                try {
                    result = JSON.parse(xhr.responseText);
                } catch (err) {}
                if (result && result.logout) {
                    handleLogoutError(result.message);
                    return;
                }
                if (window.Swal) {
                    Swal.fire({
                        title: 'Unable to save schedule',
                        text: (result && result.message) ? result.message : 'The schedule could not be saved. Please try again.',
                        icon: 'error'
                    });
                }
            },
            complete: function () {
                $submitBtn.prop('disabled', false);
            }
        });
    }

    if (!window.jQuery) {
        return;
    }

    jQuery(function () {
        jQuery(document).on('click', '#add_schedule_submit', function (e) {
            e.preventDefault();
            var form = document.getElementById('add_schedule_form');
            if (form) {
                submitScheduleForm(form);
            }
        });

        jQuery(document).on('submit', '#add_schedule_form', function (e) {
            e.preventDefault();
            submitScheduleForm(this);
        });
    });

})();
