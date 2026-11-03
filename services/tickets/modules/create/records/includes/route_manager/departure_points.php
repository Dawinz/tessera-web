<?php

/**
 * Route departure points logic.
 *
 * Collects and validates the "Departure Points" step of the route wizard
 * (the starting_point[] selects) and stores one row per point in the
 * `route_depature_points` table. Included by
 * modules/create/records/route_manager.php, which stays responsible for the
 * request/response handling only.
 *
 * Requires Read.php and Create.php to be loaded first.
 */

define('ROUTE_DEPARTURE_POINTS_TABLE', 'route_depature_points');

/**
 * Read the departure point selects out of the submitted form.
 *
 * @param array $post Usually $_POST.
 *
 * @return array Unique route_location_points ids, placeholders removed.
 */
function collect_departure_points(array $post)
{
    $submitted = $post['starting_point'] ?? [];

    if (!is_array($submitted)) {
        $submitted = [$submitted];
    }

    $points = [];

    foreach ($submitted as $point_id) {
        $point_id = trim((string) $point_id);

        if ($point_id === '' || $point_id === 'Choose Starting Point') {
            continue;
        }

        if (!in_array($point_id, $points, true)) {
            $points[] = $point_id;
        }
    }

    return $points;
}

/**
 * Check the Departure Points step values.
 *
 * @param array $points Ids from collect_departure_points().
 *
 * @return array Human readable messages, empty when the input is valid.
 */
function validate_departure_points(array $points)
{
    $errors = [];

    if (empty($points)) {
        $errors[] = 'Please choose at least one departure point.';
    }

    return $errors;
}

/**
 * Read the readable name of a route location point.
 *
 * @return string|null Null when the id does not exist.
 */
function get_departure_point_name(PDO $pdo, $point_id)
{
    $point = new Read([
        'select' => ['name'],
        'pdo'    => $pdo,
        'where'  => ['id' => $point_id],
        'table'  => 'route_location_points',
        'order'  => '',
        'limit'  => '',
    ]);

    return $point->available_row != 0 ? $point->rows[0]['name'] : null;
}

/**
 * @return bool
 */
function departure_point_exists(PDO $pdo, $route_id, $name, $company_id)
{
    $existing_point = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => [
            'route_id'   => $route_id,
            'name'       => $name,
            'company_id' => $company_id,
        ],
        'table'  => ROUTE_DEPARTURE_POINTS_TABLE,
        'order'  => '',
        'limit'  => '',
    ]);

    return $existing_point->available_row != 0;
}

/**
 * Store the departure points of a route.
 *
 * @param string $route_id   The route_id of the route they belong to.
 * @param array  $points     Validated ids from collect_departure_points().
 * @param int    $company_id The company the route belongs to.
 *
 * @return array{status:int, body:array} Ready to be sent back to the browser.
 */
function create_departure_points(PDO $pdo, $route_id, array $points, $company_id)
{
    $names = [];

    foreach ($points as $point_id) {
        $name = get_departure_point_name($pdo, $point_id);

        if ($name === null) {
            return [
                'status' => 422,
                'body'   => [
                    'success' => false,
                    'message' => 'One of the selected departure points could not be found.',
                ],
            ];
        }

        if (!in_array($name, $names, true)) {
            $names[] = $name;
        }
    }

    foreach ($names as $name) {
        if (departure_point_exists($pdo, $route_id, $name, $company_id)) {
            continue;
        }

        $data = [
            'route_id'   => $route_id,
            'name'       => $name,
            'company_id' => $company_id,
        ];

        new Create($pdo, $data, ROUTE_DEPARTURE_POINTS_TABLE);
    }

    return [
        'status' => 200,
        'body'   => [
            'success' => true,
            'data'    => 'added successfully',
        ],
    ];
}
