(function () {

    var UPDATE_TIME_ENDPOINT = 'modules/update/view/route-manager/depeture_time.php';

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

    function timeInputValue(value) {
        var match = String(value || '').match(/^(\d{2}:\d{2})/);
        return match ? match[1] : '';
    }

    function pad2(value) {
        return String(value).padStart(2, '0');
    }

    function timeParts(value) {
        var match = String(value || '').match(/^(\d{2}):(\d{2})/);
        return {
            hour: match ? match[1] : '',
            minute: match ? match[2] : ''
        };
    }

    function optionList(count, selected) {
        var html = '<option value="">--</option>';
        var index;
        for (index = 0; index < count; index += 1) {
            var option = pad2(index);
            html += '<option value="' + option + '"'
                + (option === selected ? ' selected' : '')
                + '>' + option + '</option>';
        }
        return html;
    }

    function timeSelectsHtml(idPrefix, value) {
        var parts = timeParts(value);
        return '<div class="point-time-24" id="' + idPrefix + '">'
            + '<select class="point-time-hour" aria-label="Hour">'
            + optionList(24, parts.hour)
            + '</select>'
            + '<span class="point-time-colon">:</span>'
            + '<select class="point-time-minute" aria-label="Minute">'
            + optionList(60, parts.minute)
            + '</select>'
            + '<span class="point-time-hint">24 hrs</span>'
            + '</div>';
    }

    function readTimeSelect(idPrefix) {
        var $wrap = jQuery('#' + idPrefix);
        var hour = $wrap.find('.point-time-hour').val();
        var minute = $wrap.find('.point-time-minute').val();
        if (!hour || !minute) {
            return '';
        }
        return hour + ':' + minute;
    }

    function notify(type, message) {
        if (window.alertify) {
            alertify.set('notifier', 'position', 'top-right');
            if (type === 'success') {
                alertify.success(message);
            } else {
                alertify.error(message);
            }
            return;
        }

        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type === 'success' ? 'success' : 'error',
                title: message,
                showConfirmButton: false,
                timer: 2000
            });
        }
    }

    function savePointTime(payload) {
        return jQuery.ajax({
            url: UPDATE_TIME_ENDPOINT,
            type: 'POST',
            dataType: 'json',
            data: payload
        }).then(function (response) {
            if (!response || !response.success) {
                throw new Error((response && response.message) || 'The time could not be saved.');
            }
            notify('success', 'Updated');
            return response;
        });
    }

    function openTimeEditor(button) {
        var $button = jQuery(button);
        var kind = $button.attr('data-point-kind') || 'departure';
        var pointName = $button.attr('data-point-name') || 'this point';
        var pointTime = timeInputValue($button.attr('data-point-time'));
        var reportTime = timeInputValue($button.attr('data-report-time'));
        var isDeparture = kind === 'departure';
        var timeLabel = isDeparture ? 'Departure time' : 'Arrival time';

        var html = '<div class="point-time-editor">'
            + (isDeparture
                ? '<label>Report by' + timeSelectsHtml('point_report_time', reportTime) + '</label>'
                : '')
            + '<label>' + escapeHtml(timeLabel)
            + timeSelectsHtml('point_point_time', pointTime) + '</label>'
            + '</div>';

        if (!window.Swal) {
            return;
        }

        Swal.fire({
            title: 'Update time',
            html: '<p class="text-muted mb-2">' + escapeHtml(pointName) + '</p>' + html,
            confirmButtonText: 'Update',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            focusConfirm: false,
            allowOutsideClick: function () {
                return !Swal.isLoading();
            },
            preConfirm: function () {
                var nextPointTime = readTimeSelect('point_point_time');
                var nextReportTime = isDeparture ? readTimeSelect('point_report_time') : '';

                if (!nextPointTime) {
                    Swal.showValidationMessage('Please enter the ' + timeLabel.toLowerCase() + '.');
                    return false;
                }

                if (isDeparture && !nextReportTime) {
                    Swal.showValidationMessage('Please enter the report time.');
                    return false;
                }

                return savePointTime({
                    point_id: $button.attr('data-point-id'),
                    kind: kind,
                    route_id: $button.attr('data-route-id') || jQuery('#route_points_panel').attr('data-route-id') || '',
                    point_name: pointName,
                    report_time: nextReportTime,
                    point_time: nextPointTime,
                    departure_time: isDeparture ? nextPointTime : '',
                    arrival_time: isDeparture ? '' : nextPointTime
                }).catch(function (error) {
                    var message = 'The time could not be saved.';
                    if (error && error.responseJSON && error.responseJSON.message) {
                        message = error.responseJSON.message;
                    } else if (error && error.message) {
                        message = error.message;
                    }
                    Swal.showValidationMessage(message);
                    return false;
                });
            }
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            if (typeof window.reloadRoutePoints === 'function') {
                var routeId = $button.attr('data-route-id')
                    || jQuery('#route_points_panel').attr('data-route-id');
                window.reloadRoutePoints(routeId);
            }
        });
    }

    if (!window.jQuery) {
        return;
    }

    jQuery(document).on('click', '#route_points_panel .point-item-set-time', function (e) {
        e.preventDefault();
        e.stopPropagation();
        openTimeEditor(this);
    });

})();
