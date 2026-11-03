<?php
include '../../../connection/general_db.php';
include '../../../read/Read.php';

$select = ['name', 'id'];
$where = [];
$vehicle_class = new Read(['select' => $select, 'pdo' => $pdo_general, 'where' => $where, 'table' => "vehicle", 'order' => "", '' => ""]);
$buses = $vehicle_class->rows;

$add_buses = '';
foreach ($buses as $bus) {
    $add_buses .= '<option value="' . htmlspecialchars($bus['id']) . '">' . htmlspecialchars($bus['name']) . '</option>';
}

if (!function_exists('schedule_time_options')) {
    function schedule_time_options($count, $selected = '')
    {
        $html = '';
        for ($i = 0; $i < $count; $i++) {
            $value = sprintf('%02d', $i);
            $html .= '<option value="' . $value . '"'
                . ($value === $selected ? ' selected' : '')
                . '>' . $value . '</option>';
        }
        return $html;
    }
}

if (!function_exists('schedule_time_picker')) {
    function schedule_time_picker($id, $hour, $minute)
    {
        return
            '<div class="schedule-time-24" data-time-input="' . htmlspecialchars($id, ENT_QUOTES) . '">'
            . '<span class="schedule-time-icon"><i class="uil uil-clock"></i></span>'
            . '<select class="schedule-time-hour" aria-label="Hour">'
            . schedule_time_options(24, $hour)
            . '</select>'
            . '<span class="schedule-time-colon">:</span>'
            . '<select class="schedule-time-minute" aria-label="Minute">'
            . schedule_time_options(60, $minute)
            . '</select>'
            . '<span class="schedule-time-hint">24 hrs</span>'
            . '<input type="hidden" id="' . htmlspecialchars($id, ENT_QUOTES) . '" name="'
            . htmlspecialchars($id, ENT_QUOTES) . '" value="'
            . htmlspecialchars($hour . ':' . $minute, ENT_QUOTES) . '">'
            . '</div>';
    }
}
?>
<div class="modal fade" id="schedule_modal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl add-route-modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="scheduleModalLabel">Add Schedule</h5>
                    <small class="text-muted d-block" id="schedule_modal_route_name">Select a route</small>
                    <small class="text-muted" id="schedule_modal_route_detail"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add_schedule_form" class="needs-validation" novalidate autocomplete="off">
                <input type="hidden" id="schedule_route_id" name="route_id" value="">
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h5>Trip Details</h5>
                        <p class="card-title-desc mb-0">Choose the days, times and bus for this schedule</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block mb-2">Repeat
                            <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-2 requirement-check-group">
                            <label class="requirement-check-pill" for="schedule_repeat_daily">
                                <input type="radio" class="form-check-input" id="schedule_repeat_daily"
                                    name="schedule_repeat" value="daily" checked>
                                <span>Daily</span>
                            </label>
                            <label class="requirement-check-pill" for="schedule_repeat_once">
                                <input type="radio" class="form-check-input" id="schedule_repeat_once"
                                    name="schedule_repeat" value="once">
                                <span>Once</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3 schedule-daily-fields">
                        <label class="form-label fw-semibold d-block mb-2">Operating days
                            <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-2 requirement-check-group">
                            <label class="requirement-check-pill" for="schedule_day_mon">
                                <input type="checkbox" class="form-check-input" id="schedule_day_mon"
                                    name="schedule_days[]" value="Mon">
                                <span>Mon</span>
                            </label>
                            <label class="requirement-check-pill" for="schedule_day_tue">
                                <input type="checkbox" class="form-check-input" id="schedule_day_tue"
                                    name="schedule_days[]" value="Tue">
                                <span>Tue</span>
                            </label>
                            <label class="requirement-check-pill" for="schedule_day_wed">
                                <input type="checkbox" class="form-check-input" id="schedule_day_wed"
                                    name="schedule_days[]" value="Wed">
                                <span>Wed</span>
                            </label>
                            <label class="requirement-check-pill" for="schedule_day_thu">
                                <input type="checkbox" class="form-check-input" id="schedule_day_thu"
                                    name="schedule_days[]" value="Thu">
                                <span>Thu</span>
                            </label>
                            <label class="requirement-check-pill" for="schedule_day_fri">
                                <input type="checkbox" class="form-check-input" id="schedule_day_fri"
                                    name="schedule_days[]" value="Fri">
                                <span>Fri</span>
                            </label>
                            <label class="requirement-check-pill" for="schedule_day_sat">
                                <input type="checkbox" class="form-check-input" id="schedule_day_sat"
                                    name="schedule_days[]" value="Sat">
                                <span>Sat</span>
                            </label>
                            <label class="requirement-check-pill" for="schedule_day_sun">
                                <input type="checkbox" class="form-check-input" id="schedule_day_sun"
                                    name="schedule_days[]" value="Sun">
                                <span>Sun</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3 schedule-once-fields">
                        <label class="form-label fw-semibold d-block mb-2">Trip dates
                            <span class="text-danger">*</span></label>
                        <div id="schedule_once_dates">
                            <div class="row g-2 mb-2 schedule-once-date-row align-items-center">
                                <div class="col-lg-4">
                                    <div class="input-group schedule-datepicker-group">
                                        <span class="input-group-text"><i class="uil uil-calendar-alt"></i></span>
                                        <input type="text" class="form-control schedule-datepicker"
                                            name="schedule_once_dates[]" placeholder="Choose date" readonly>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-schedule-date"
                                        tabindex="-1" title="Remove date">
                                        <i class="uil uil-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-soft-success btn-sm" id="add_schedule_date_btn">
                            <i class="uil uil-plus me-1"></i>Add trip date
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="schedule_report_time">Report by</label>
                                <?= schedule_time_picker('schedule_report_time', '05', '30') ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="schedule_departure_time">Departure time
                                    <span class="text-danger">*</span></label>
                                <?= schedule_time_picker('schedule_departure_time', '06', '00') ?>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="schedule_arrival_time">Arrival time
                                    <span class="text-danger">*</span></label>
                                <?= schedule_time_picker('schedule_arrival_time', '14', '00') ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="schedule_bus">Bus
                                    <span class="text-danger">*</span></label>
                                <select class="form-select" id="schedule_bus"
                                    name="schedule_bus">
                                    <option value="">Choose Bus</option>
                                    <?= $add_buses ?></select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold" for="schedule_notes">Notes</label>
                        <input type="text" class="form-control" id="schedule_notes" name="schedule_notes"
                            placeholder="Optional note for this departure">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" id="add_schedule_submit">
                        <i class="uil uil-plus me-1"></i>Save Schedule
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>