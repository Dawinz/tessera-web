<?php

/**
 * Route creation logic.
 *
 * Collects and validates the "Basic Info" step of the route wizard and turns
 * it into a new row in the `routes` table. Included by
 * modules/create/records/route_manager.php, which stays responsible for the
 * request/response handling only.
 *
 * Requires Read.php and Create.php to be loaded first.
 */

define('ROUTE_COMPANY_INITIAL', 'SRC');
define('ROUTE_COMPANY_ID', 1);

/**
 * Read the Basic Info step fields out of the submitted form.
 *
 * @param array $post Usually $_POST.
 *
 * @return array Trimmed values keyed by field name.
 */
function collect_route_input(array $post)
{
    return [
        'route_starting_point'   => trim($post['route_starting_point'] ?? ''),
        'route_ending_point'     => trim($post['route_ending_point'] ?? ''),
        'journey_duration_value' => trim($post['journey_duration_value'] ?? ''),
        'journey_duration_unit'  => trim($post['journey_duration_unit'] ?? ''),
        'distance_in_km'         => trim($post['distance_in_km'] ?? ''),
        'map_coordinates'        => trim($post['map_coordinates'] ?? ''),
        'description'            => trim($post['description'] ?? ''),
        'identifer'              => trim($post['identifer'] ?? ''),
        'active'                 => isset($post['active']) ? 1 : 0,
    ];
}

/**
 * Check the Basic Info step values.
 *
 * @return array Human readable messages, empty when the input is valid.
 */
function validate_route_input(array $input)
{
    $errors = [];

    if ($input['route_starting_point'] === '' || $input['route_starting_point'] === 'Choose Starting Point') {
        $errors[] = 'Please choose the route starting point.';
    }

    if ($input['route_ending_point'] === '' || $input['route_ending_point'] === 'Choose Ending Point') {
        $errors[] = 'Please choose the route ending point.';
    }

    if ($input['journey_duration_value'] === ''
        || !ctype_digit($input['journey_duration_value'])
        || (int) $input['journey_duration_value'] < 1
    ) {
        $errors[] = 'Please enter a valid journey duration in hours.';
    }

    if (!preg_match('/^[A-Za-z]{1,3}$/', $input['identifer'])) {
        $errors[] = 'Please enter a route identifier (up to 3 letters).';
    }

    return $errors;
}

/**
 * Read the readable name and the abbreviation of a location.
 *
 * @return array{name:string, short:string}|null Null when the id does not exist.
 */
function get_location(PDO $pdo, $location_id)
{
    $location = new Read([
        'select' => ['name', 'short'],
        'pdo'    => $pdo,
        'where'  => ['id' => $location_id],
        'table'  => 'locations_names',
        'order'  => '',
        'limit'  => '',
    ]);

    if ($location->available_row == 0) {
        return null;
    }

    return [
        'name'  => $location->rows[0]['name'],
        'short' => $location->rows[0]['short'],
    ];
}

/**
 * Abbreviation of a location, e.g. "Ars" for Arusha.
 *
 * Falls back to the first three letters of the name for the locations that
 * were saved before the `short` column was filled in.
 *
 * @return string
 */
function get_location_short(array $location)
{
    $short = trim($location['short']);

    if ($short !== '') {
        return $short;
    }

    $letters = preg_replace('/[^A-Za-z]/', '', $location['name']);

    return $letters === '' ? '' : ucfirst(strtolower(substr($letters, 0, 3)));
}

/**
 * Next sequential route number for the given company.
 *
 * @return int
 */
function get_next_route_id_no(PDO $pdo, $company_initial, $company_id)
{
    $last_route = new Read([
        'select' => ['route_id_no'],
        'pdo'    => $pdo,
        'where'  => ['company_initial' => $company_initial, 'company_id' => $company_id],
        'table'  => 'routes',
        'order'  => 'ORDER BY id DESC',
        'limit'  => 'LIMIT 1',
    ]);

    $route_id_no = $last_route->available_row != 0 ? $last_route->rows[0]['route_id_no'] : 0;

    return (int) $route_id_no + 1;
}

/**
 * @return bool
 */
function route_id_exists(PDO $pdo, $route_id)
{
    $existing_route = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => ['route_id' => $route_id],
        'table'  => 'routes',
        'order'  => '',
        'limit'  => '',
    ]);

    return $existing_route->available_row != 0;
}

/**
 * Build and store the route.
 *
 * @param array  $input    Validated values from collect_route_input().
 * @param string $uploader Username of the logged in user.
 *
 * @return array{status:int, body:array} Ready to be sent back to the browser.
 */
function create_route(PDO $pdo, array $input, $uploader)
{
    $company_initial = ROUTE_COMPANY_INITIAL;
    $company_id = ROUTE_COMPANY_ID;

    $starting_point = get_location($pdo, $input['route_starting_point']);
    $ending_point = get_location($pdo, $input['route_ending_point']);

    if ($starting_point === null || $ending_point === null) {
        return [
            'status' => 422,
            'body'   => [
                'success' => false,
                'message' => 'The selected starting or ending point could not be found.',
            ],
        ];
    }

    $starting_point_name = $starting_point['name'];
    $ending_point_name = $ending_point['name'];

    // e.g. Arusha to Dar es salaam becomes "Ars to Dar".
    $route_acronym_name = get_location_short($starting_point)
        . ' to ' . get_location_short($ending_point);

    $new_route_id_no = get_next_route_id_no($pdo, $company_initial, $company_id);
    $new_route_id = $input['identifer'] . $new_route_id_no . $company_initial;

    if (route_id_exists($pdo, $new_route_id)) {
        return [
            'status' => 409,
            'body'   => [
                'success' => false,
                'message' => 'This route ID already exists.',
            ],
        ];
    }

    $data = [
        'start_route'            => $starting_point_name,
        'end_route'              => $ending_point_name,
        'route_name'             => $starting_point_name . ' to ' . $ending_point_name,
        'route_initial_letters'  => $input['identifer'],
        'route_id'               => $new_route_id,
        'route_id_no'            => $new_route_id_no,
        'detail'                 => base64_encode($input['description']),
        'journey_duration_value' => $input['journey_duration_value'],
        'journey_duration_unit'  => $input['journey_duration_unit'],
        'map_coordinates'        => $input['map_coordinates'],
        'distance'               => $input['distance_in_km'],
        'company_id'             => $company_id,
        'company_initial'        => $company_initial,
        'state'                  => $input['active'],
        'route_acronym_name'     => $route_acronym_name,
        'uploader'               => $uploader,
    ];

    new Create($pdo, $data, 'routes');

    return [
        'status' => 200,
        'body'   => [
            'success'  => true,
            'route_id' => $new_route_id,
            'data'     => 'added successfully',
        ],
    ];
}
