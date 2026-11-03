<?php

/**
 * Route trip requirements logic.
 *
 * Collects the "Requirements" step of the route wizard (the requirements[]
 * checkboxes) and stores one row per ticked document in the
 * `Trip_requirment` table. Included by
 * modules/create/records/route_manager.php, which stays responsible for the
 * request/response handling only.
 *
 * Requires Read.php and Create.php to be loaded first.
 */

define('ROUTE_TRIP_REQUIREMENTS_TABLE', 'Trip_requirment');

/**
 * Read the ticked requirement checkboxes out of the submitted form.
 *
 * Unticked checkboxes are not posted at all, so only the checked ones arrive.
 *
 * @param array $post Usually $_POST.
 *
 * @return array Unique document names.
 */
function collect_trip_requirements(array $post)
{
    $submitted = $post['requirements'] ?? [];

    if (!is_array($submitted)) {
        $submitted = [$submitted];
    }

    $requirements = [];

    foreach ($submitted as $requirement) {
        $requirement = trim((string) $requirement);

        if ($requirement === '') {
            continue;
        }

        if (!in_array($requirement, $requirements, true)) {
            $requirements[] = $requirement;
        }
    }

    return $requirements;
}

/**
 * @return bool
 */
function trip_requirement_exists(PDO $pdo, $route_id, $name)
{
    $existing_requirement = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => [
            'route_id' => $route_id,
            'name'     => $name,
        ],
        'table'  => ROUTE_TRIP_REQUIREMENTS_TABLE,
        'order'  => '',
        'limit'  => '',
    ]);

    return $existing_requirement->available_row != 0;
}

/**
 * Store the trip requirements of a route.
 *
 * The step is optional, so an empty list simply stores nothing.
 *
 * @param string $route_id     The route_id of the route they belong to.
 * @param array  $requirements Names from collect_trip_requirements().
 *
 * @return array{status:int, body:array} Ready to be sent back to the browser.
 */
function create_trip_requirements(PDO $pdo, $route_id, array $requirements)
{
    foreach ($requirements as $name) {
        if (trip_requirement_exists($pdo, $route_id, $name)) {
            continue;
        }

        $data = [
            'name'     => $name,
            'route_id' => $route_id,
        ];

        new Create($pdo, $data, ROUTE_TRIP_REQUIREMENTS_TABLE);
    }

    return [
        'status' => 200,
        'body'   => [
            'success' => true,
            'data'    => 'added successfully',
        ],
    ];
}
