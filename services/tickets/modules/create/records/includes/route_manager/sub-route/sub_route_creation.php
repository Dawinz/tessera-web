<?php

/**
 * Sub route creation logic.
 *
 * Collects and validates the "Basic Info" step of the sub route wizard and
 * turns it into a new row in the `sub_route` table. Included by
 * modules/create/records/route_manager/sub_route.php, which stays
 * responsible for the request/response handling only.
 *
 * Requires Read.php and Create.php to be loaded first.
 */

if (!defined('SUB_ROUTE_TABLE')) {
    define('SUB_ROUTE_TABLE', 'sub_route');
}

if (!defined('ROUTE_COMPANY_INITIAL')) {
    define('ROUTE_COMPANY_INITIAL', 'SRC');
}

if (!defined('ROUTE_COMPANY_ID')) {
    define('ROUTE_COMPANY_ID', 1);
}

/**
 * Read the Basic Info step fields out of the submitted sub route form.
 *
 * @param array $post Usually $_POST.
 *
 * @return array Trimmed values keyed by field name.
 */
function collect_sub_route_input(array $post)
{
    return [
        'main_route_id'              => trim($post['main_route_id'] ?? ''),
        'sub_route_starting_point'   => trim($post['sub_route_starting_point'] ?? ''),
        'sub_route_ending_point'     => trim($post['sub_route_ending_point'] ?? ''),
        'sub_journey_duration_value' => trim($post['sub_journey_duration_value'] ?? ''),
        'sub_journey_duration_unit'  => trim($post['sub_journey_duration_unit'] ?? ''),
        'sub_distance_in_km'         => trim($post['sub_distance_in_km'] ?? ''),
        'sub_map_coordinates'        => trim($post['sub_map_coordinates'] ?? ''),
        'sub_description'            => trim($post['sub_description'] ?? ''),
        'sub_identifer'              => trim($post['sub_identifer'] ?? ''),
        'sub_active'                 => isset($post['sub_active']) ? 1 : 0,
    ];
}

/**
 * Check the Basic Info step values.
 *
 * @return array Human readable messages, empty when the input is valid.
 */
function validate_sub_route_input(array $input)
{
    $errors = [];

    if ($input['main_route_id'] === '') {
        $errors[] = 'The main route is missing.';
    }

    if ($input['sub_route_starting_point'] === '' || $input['sub_route_starting_point'] === 'Choose Starting Point') {
        $errors[] = 'Please choose the sub route starting point.';
    }

    if ($input['sub_route_ending_point'] === '' || $input['sub_route_ending_point'] === 'Choose Ending Point') {
        $errors[] = 'Please choose the sub route ending point.';
    }

    if ($input['sub_journey_duration_value'] === ''
        || !ctype_digit($input['sub_journey_duration_value'])
        || (int) $input['sub_journey_duration_value'] < 1
    ) {
        $errors[] = 'Please enter a valid journey duration in hours.';
    }

    if (!preg_match('/^[A-Za-z]{1,3}$/', $input['sub_identifer'])) {
        $errors[] = 'Please enter a route identifier (up to 3 letters).';
    }

    return $errors;
}

/**
 * Read the readable name and the abbreviation of a location.
 *
 * @return array{name:string, short:string}|null Null when the id does not exist.
 */
if (!function_exists('get_location')) {
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
}

/**
 * Abbreviation of a location, e.g. "Dar" for Dar es salaam.
 *
 * Falls back to the first three letters of the name for the locations that
 * were saved before the `short` column was filled in.
 *
 * @return string
 */
if (!function_exists('get_location_short')) {
    function get_location_short(array $location)
    {
        $short = trim($location['short']);

        if ($short !== '') {
            return $short;
        }

        $letters = preg_replace('/[^A-Za-z]/', '', $location['name']);

        return $letters === '' ? '' : ucfirst(strtolower(substr($letters, 0, 3)));
    }
}

/**
 * Next sequential sub route number.
 *
 * @return int
 */
function get_next_sub_route_id_no(PDO $pdo)
{
    $last_sub_route = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => [],
        'table'  => SUB_ROUTE_TABLE,
        'order'  => 'ORDER BY id DESC',
        'limit'  => 'LIMIT 1',
    ]);

    $route_id_no = $last_sub_route->available_row != 0 ? $last_sub_route->rows[0]['id'] : 0;

    return (int) $route_id_no + 1;
}

/**
 * Initial letters of the parent route, e.g. "AD" for Arusha to Dar.
 *
 * @return string Empty when the parent route_id does not exist.
 */
function get_main_route_initial_letter(PDO $pdo, $main_route_id)
{
    $main_route = new Read([
        'select' => ['route_initial_letters'],
        'pdo'    => $pdo,
        'where'  => ['route_id' => $main_route_id],
        'table'  => 'routes',
        'order'  => '',
        'limit'  => 'LIMIT 1',
    ]);

    if ($main_route->available_row == 0) {
        return '';
    }

    return $main_route->rows[0]['route_initial_letters'];
}

/**
 * @return bool
 */
function sub_route_acronym_exists(PDO $pdo, $parent_route, $route_acronym_name)
{
    $existing_sub_route = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => [
            'parent_route'       => $parent_route,
            'route_acronym_name' => $route_acronym_name,
        ],
        'table'  => SUB_ROUTE_TABLE,
        'order'  => '',
        'limit'  => '',
    ]);

    return $existing_sub_route->available_row != 0;
}

/**
 * Build and store the sub route.
 *
 * @param array  $input    Validated values from collect_sub_route_input().
 * @param string $uploader Username of the logged in user.
 *
 * @return array{status:int, body:array} Ready to be sent back to the browser.
 */
function create_sub_route(PDO $pdo, array $input, $uploader)
{
    $company_initial = ROUTE_COMPANY_INITIAL;
    $company_id = ROUTE_COMPANY_ID;

    $starting_point = get_location($pdo, $input['sub_route_starting_point']);
    $ending_point = get_location($pdo, $input['sub_route_ending_point']);

    if ($starting_point === null || $ending_point === null) {
        return [
            'status' => 422,
            'body'   => [
                'success' => false,
                'message' => 'The selected starting or ending point could not be found.',
            ],
        ];
    }

    $main_initial_letter = get_main_route_initial_letter($pdo, $input['main_route_id']);

    if ($main_initial_letter === '') {
        return [
            'status' => 422,
            'body'   => [
                'success' => false,
                'message' => 'The selected main route could not be found.',
            ],
        ];
    }

    $starting_point_name = $starting_point['name'];
    $ending_point_name = $ending_point['name'];

    // e.g. Dar es salaam to Mbeya becomes "Dar to Mbe".
    $route_acronym_name = get_location_short($starting_point)
        . ' to ' . get_location_short($ending_point);

    if (sub_route_acronym_exists($pdo, $input['main_route_id'], $route_acronym_name)) {
        return [
            'status' => 409,
            'body'   => [
                'success' => false,
                'message' => 'A sub route with this name already exists for the selected main route.',
            ],
        ];
    }

    $new_route_id_no = get_next_sub_route_id_no($pdo);
    $new_route_id = $main_initial_letter . '-' . $input['sub_identifer'] . $new_route_id_no . $company_initial;

    $data = [
        'starting_point'         => $starting_point_name,
        'ending_point'           => $ending_point_name,
        'route_name'             => $starting_point_name . ' to ' . $ending_point_name,
        'parent_route'           => $input['main_route_id'],
        'route_initial_letters'  => $input['sub_identifer'],
        'route_id'               => $new_route_id,
        'route_id_no'            => $new_route_id_no,
        'detail'                 => base64_encode($input['sub_description']),
        'journey_duration_value' => $input['sub_journey_duration_value'],
        'journey_duration_unit'  => $input['sub_journey_duration_unit'],
        'map_coordinates'        => $input['sub_map_coordinates'],
        'distance'               => $input['sub_distance_in_km'],
        'company_id'             => $company_id,
        'company_initial'        => $company_initial,
        'state'                  => $input['sub_active'],
        'route_acronym_name'     => $route_acronym_name,
        'uploader'               => $uploader,
    ];

    new Create($pdo, $data, SUB_ROUTE_TABLE);

    return [
        'status' => 200,
        'body'   => [
            'success'  => true,
            'route_id' => $new_route_id,
            'data'     => 'added successfully',
        ],
    ];
}
