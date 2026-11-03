<?php

/**
 * Sub route stops logic.
 *
 * Collects and validates the "Stops" step of the sub route wizard
 * (the stops[] / stop_duration[] rows) and stores one row per stop in the
 * `route_stops` table. Included by
 * modules/create/records/route_manager/sub_route.php, which stays
 * responsible for the request/response handling only.
 *
 * Requires Read.php and Create.php to be loaded first.
 */

if (!defined('SUB_ROUTE_STOPS_TABLE')) {
    define('SUB_ROUTE_STOPS_TABLE', 'route_stops');
}

/**
 * Read the stop rows out of the submitted sub route form.
 *
 * @param array $post Usually $_POST.
 *
 * @return array List of rows keyed by stop_id, duration.
 */
function collect_sub_stops(array $post)
{
    $stop_ids = $post['stops'] ?? [];
    $durations = $post['stop_duration'] ?? [];

    if (!is_array($stop_ids)) {
        $stop_ids = [$stop_ids];
    }

    if (!is_array($durations)) {
        $durations = [$durations];
    }

    $stops = [];

    foreach ($stop_ids as $index => $stop_id) {
        $stop_id = trim((string) $stop_id);
        $duration = trim((string) ($durations[$index] ?? ''));

        if ($stop_id === 'Choose Stop') {
            $stop_id = '';
        }

        // A stop row is optional as a whole, so skip the untouched ones.
        if ($stop_id === '' && $duration === '') {
            continue;
        }

        $stops[] = [
            'stop_id'  => $stop_id,
            'duration' => $duration,
        ];
    }

    return $stops;
}

/**
 * Check the Stops step values.
 *
 * Stops are optional, but once a row is started both the stop and its
 * duration have to be filled in.
 *
 * @param array $stops Rows from collect_sub_stops().
 *
 * @return array Human readable messages, empty when the input is valid.
 */
function validate_sub_stops(array $stops)
{
    $errors = [];
    $seen_stop_ids = [];

    foreach ($stops as $stop) {
        if ($stop['stop_id'] === '' || $stop['duration'] === '') {
            $errors[] = 'Please fill in both stop and duration, or leave both empty.';
        } elseif (in_array($stop['stop_id'], $seen_stop_ids, true)) {
            $errors[] = 'Each stop can only be added once.';
        } else {
            $seen_stop_ids[] = $stop['stop_id'];
        }
    }

    return array_values(array_unique($errors));
}

/**
 * @return bool
 */
function sub_stop_id_exists(PDO $pdo, $stop_id)
{
    $stop = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => ['id' => $stop_id],
        'table'  => 'stops',
        'order'  => '',
        'limit'  => '',
    ]);

    return $stop->available_row != 0;
}

/**
 * @return bool
 */
function sub_route_stop_exists(PDO $pdo, $route_id, $stop_id, $company_id)
{
    $existing_stop = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => [
            'route_id'   => $route_id,
            'stop_id'    => $stop_id,
            'company_id' => $company_id,
        ],
        'table'  => SUB_ROUTE_STOPS_TABLE,
        'order'  => '',
        'limit'  => '',
    ]);

    return $existing_stop->available_row != 0;
}

/**
 * Store the stops of a sub route.
 *
 * @param string $route_id   The route_id of the sub route they belong to.
 * @param array  $stops      Validated rows from collect_sub_stops().
 * @param int    $company_id The company the sub route belongs to.
 *
 * @return array{status:int, body:array} Ready to be sent back to the browser.
 */
function create_sub_stops(PDO $pdo, $route_id, array $stops, $company_id)
{
    foreach ($stops as $stop) {
        if (!sub_stop_id_exists($pdo, $stop['stop_id'])) {
            return [
                'status' => 422,
                'body'   => [
                    'success' => false,
                    'message' => 'One of the selected stops could not be found.',
                ],
            ];
        }
    }

    foreach ($stops as $stop) {
        if (sub_route_stop_exists($pdo, $route_id, $stop['stop_id'], $company_id)) {
            continue;
        }

        $data = [
            'stop_id'    => $stop['stop_id'],
            'route_id'   => $route_id,
            'company_id' => $company_id,
            'duration'   => $stop['duration'],
        ];

        new Create($pdo, $data, SUB_ROUTE_STOPS_TABLE);
    }

    return [
        'status' => 200,
        'body'   => [
            'success' => true,
            'data'    => 'added successfully',
        ],
    ];
}
