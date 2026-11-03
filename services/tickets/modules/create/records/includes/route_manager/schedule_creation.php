<?php

/**
 * Schedule creation logic.
 *
 * Collects the Add Schedule modal fields and stores one row per operating
 * day (weekly) or trip date (once) in `route_schedules`. Included by
 * modules/create/records/route_manager/schedule.php.
 *
 * Requires Read.php and Create.php to be loaded first.
 */

if (!defined('ROUTE_SCHEDULES_TABLE')) {
    define('ROUTE_SCHEDULES_TABLE', 'route_schedules');
}

if (!defined('ROUTE_COMPANY_ID')) {
    define('ROUTE_COMPANY_ID', 1);
}

/**
 * @param mixed $value
 *
 * @return array Unique trimmed strings, empty values dropped.
 */
function collect_posted_list($value)
{
    if (!isset($value) || $value === '') {
        return [];
    }

    if (!is_array($value)) {
        $value = [$value];
    }

    $items = [];

    foreach ($value as $item) {
        $item = trim((string) $item);

        if ($item === '') {
            continue;
        }

        $items[] = $item;
    }

    return array_values(array_unique($items));
}

/**
 * @param array $post Usually $_POST.
 *
 * @return array
 */
function collect_schedule_input(array $post)
{
    $repeat = trim($post['schedule_repeat'] ?? '');

    // The modal says "Daily"; the table stores that as weekly + operating days.
    if ($repeat === 'daily') {
        $repeat = 'weekly';
    }

    return [
        'route_id'        => trim($post['route_id'] ?? ''),
        'schedule_type'   => $repeat,
        'days'            => collect_posted_list($post['schedule_days'] ?? []),
        'trip_dates'      => collect_posted_list($post['schedule_once_dates'] ?? []),
        'reporting_time'  => trim($post['schedule_report_time'] ?? ''),
        'departure_time'  => trim($post['schedule_departure_time'] ?? ''),
        'arrival_time'    => trim($post['schedule_arrival_time'] ?? ''),
        'bus_id'          => trim($post['schedule_bus'] ?? ''),
        'notes'           => trim($post['schedule_notes'] ?? ''),
    ];
}

/**
 * @return array Human readable messages, empty when the input is valid.
 */
function validate_schedule_input(array $input)
{
    $errors = [];
    $allowed_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    if ($input['route_id'] === '') {
        $errors[] = 'Please choose a route before adding a schedule.';
    }

    if (!in_array($input['schedule_type'], ['weekly', 'once'], true)) {
        $errors[] = 'Please choose how this schedule repeats.';
    }

    if ($input['schedule_type'] === 'weekly') {
        $invalid_days = array_diff($input['days'], $allowed_days);
        if (empty($input['days'])) {
            $errors[] = 'Please choose at least one operating day.';
        } elseif (!empty($invalid_days)) {
            $errors[] = 'Please choose valid operating days.';
        }
    }

    if ($input['schedule_type'] === 'once') {
        if (empty($input['trip_dates'])) {
            $errors[] = 'Please choose at least one trip date.';
        } else {
            foreach ($input['trip_dates'] as $date) {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                    $errors[] = 'Please choose valid trip dates.';
                    break;
                }
            }
        }
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $input['departure_time'])) {
        $errors[] = 'Please choose a departure time.';
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $input['arrival_time'])) {
        $errors[] = 'Please choose an arrival time.';
    }

    if ($input['reporting_time'] !== '' && !preg_match('/^\d{2}:\d{2}$/', $input['reporting_time'])) {
        $errors[] = 'Please choose a valid report-by time.';
    }

    if ($input['bus_id'] === '' || !ctype_digit($input['bus_id'])) {
        $errors[] = 'Please choose a bus.';
    }

    return $errors;
}

/**
 * @return string|null Null when the bus does not exist.
 */
function get_schedule_vehicle_name(PDO $pdo_general, $bus_id)
{
    $vehicle = new Read([
        'select' => ['name'],
        'pdo'    => $pdo_general,
        'where'  => ['id' => $bus_id],
        'table'  => 'vehicle',
        'order'  => '',
        'limit'  => 'LIMIT 1',
    ]);

    if ($vehicle->available_row == 0) {
        return null;
    }

    return $vehicle->rows[0]['name'];
}

/**
 * Add the notes column if this database was created before it existed.
 */
function ensure_route_schedules_notes_column(PDO $pdo)
{
    static $ready = false;

    if ($ready) {
        return;
    }

    $column = $pdo->query("SHOW COLUMNS FROM `" . ROUTE_SCHEDULES_TABLE . "` LIKE 'notes'")->fetch(PDO::FETCH_ASSOC);

    if (!$column) {
        $pdo->exec(
            'ALTER TABLE `' . ROUTE_SCHEDULES_TABLE . '`
             ADD COLUMN `notes` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `reporting_time`'
        );
    }

    $ready = true;
}

/**
 * One row per operating day (weekly) or trip date (once).
 *
 * @return array{status:int, body:array}
 */
function create_schedule(PDO $pdo, PDO $pdo_general, array $input, $uploader)
{
    ensure_route_schedules_notes_column($pdo);

    $vehicle_name = get_schedule_vehicle_name($pdo_general, $input['bus_id']);

    if ($vehicle_name === null || $vehicle_name === '') {
        return [
            'status' => 422,
            'body'   => [
                'success' => false,
                'message' => 'The selected bus could not be found.',
            ],
        ];
    }

    $reporting_time = $input['reporting_time'] === '' ? null : $input['reporting_time'];
    $company_id = (string) ROUTE_COMPANY_ID;

    $rows = [];

    if ($input['schedule_type'] === 'weekly') {
        foreach ($input['days'] as $day) {
            $rows[] = [
                'route_id'        => $input['route_id'],
                'vehicle_name'    => $vehicle_name,
                'schedule_type'   => 'weekly',
                'day_of_week'     => $day,
                'trip_date'       => null,
                'departure_time'  => $input['departure_time'],
                'arrival_time'    => $input['arrival_time'],
                'reporting_time'  => $reporting_time,
                'notes'           => $input['notes'],
                'status'          => 'active',
                'company_id'      => $company_id,
                'uploader'        => $uploader,
            ];
        }
    } else {
        foreach ($input['trip_dates'] as $date) {
            $rows[] = [
                'route_id'        => $input['route_id'],
                'vehicle_name'    => $vehicle_name,
                'schedule_type'   => 'once',
                'day_of_week'     => null,
                'trip_date'       => $date,
                'departure_time'  => $input['departure_time'],
                'arrival_time'    => $input['arrival_time'],
                'reporting_time'  => $reporting_time,
                'notes'           => $input['notes'],
                'status'          => 'active',
                'company_id'      => $company_id,
                'uploader'        => $uploader,
            ];
        }
    }

    try {
        $pdo->beginTransaction();

        foreach ($rows as $row) {
            new Create($pdo, $row, ROUTE_SCHEDULES_TABLE);
        }

        $pdo->commit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        if ($e->getCode() === '23000') {
            return [
                'status' => 409,
                'body'   => [
                    'success' => false,
                    'message' => 'This bus is already scheduled for one of the selected days or dates.',
                ],
            ];
        }

        throw $e;
    }

    return [
        'status' => 200,
        'body'   => [
            'success' => true,
            'data'    => 'added successfully',
            'count'   => count($rows),
        ],
    ];
}
