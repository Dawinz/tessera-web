<?php

include_once __DIR__ . '/../../../connection/connect.php';
require_once __DIR__ . '/../../../../includes/import_route_manager_compat.php';

$company_id = tessera_rm_company_id();
$routeKey = tessera_rm_route_key_sql('r');
$subKey = tessera_rm_sub_route_key_sql('s');
$parentKey = tessera_rm_route_key_sql('pr');

$routes_stmt = $pdo->prepare(
    "SELECT r.id, {$routeKey} AS route_id, r.route_name,
            COALESCE(NULLIF(r.route_initial_letters, ''), UPPER(LEFT(r.route_name, 3))) AS route_initial_letters,
            r.start_route, r.end_route,
            COALESCE(r.journey_duration_value, 0) AS journey_duration_value,
            COALESCE(r.journey_duration_unit, 'hrs') AS journey_duration_unit,
            COALESCE(r.distance, 0) AS distance, f.amount, f.currency
     FROM routes r
     LEFT JOIN route_fair f ON f.route_id = {$routeKey} AND f.state = :state
     WHERE r.tenant_id = :tenant_id
       AND (r.route_type = 'Main Route' OR r.route_type IS NULL OR r.route_type = '')
     ORDER BY r.id ASC"
);
$routes_stmt->execute([
    'state'     => 'default',
    'tenant_id' => tessera_rm_tenant_id(),
]);
$routes_array = $routes_stmt->fetchAll(PDO::FETCH_ASSOC);
$routes_count = count($routes_array);

$sub_routes_stmt = $pdo->prepare(
    "SELECT COALESCE(NULLIF(s.route_id, ''), {$subKey}) AS route_id,
            COALESCE(NULLIF(s.parent_route, ''), {$parentKey}) AS parent_route,
            s.route_name, s.starting_point, s.ending_point,
            COALESCE(s.journey_duration_value, 0) AS journey_duration_value,
            COALESCE(s.journey_duration_unit, 'hrs') AS journey_duration_unit,
            COALESCE(s.distance, 0) AS distance, COALESCE(s.state, 1) AS state,
            f.amount, f.currency
     FROM sub_route s
     INNER JOIN routes pr ON pr.id = s.main_route_no
     LEFT JOIN route_fair f ON f.route_id = COALESCE(NULLIF(s.route_id, ''), {$subKey}) AND f.state = :state
     WHERE pr.tenant_id = :tenant_id
     ORDER BY s.id ASC"
);
$sub_routes_stmt->execute([
    'state'     => 'default',
    'tenant_id' => tessera_rm_tenant_id(),
]);

$sub_routes_by_parent = [];
foreach ($sub_routes_stmt->fetchAll(PDO::FETCH_ASSOC) as $sub_route) {
    $sub_routes_by_parent[$sub_route['parent_route']][] = $sub_route;
}

?>
<div class="col-xxl-3 col-xl-5 col-lg-5" id="route_network_panel">
    <!-- Route explorer. Each route is a details element, so opening and
                             closing a route needs no script. Sub routes are nested under
                             the parent whose route_id matches sub_route.parent_route. -->
    <div class="card route-explorer-card">
        <div class="card-header route-explorer-header">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="route-explorer-heading mb-0">
                    Route Network
                    <span class="route-explorer-count"><?php echo (int) $routes_count; ?></span>
                </h6>
                <button type="button" class="btn btn-soft-primary btn-sm route-explorer-add" data-bs-toggle="modal"
                    data-bs-target="#myModal" title="Add Route">
                    <i class="uil uil-plus"></i> Add Route
                </button>
            </div>
        </div><!-- end card header -->
        <div class="card-body p-0">
            <div class="route-explorer">

                <?php foreach ($routes_array as $index => $route) {
                    $legs = $sub_routes_by_parent[$route['route_id']] ?? [];
                    $legs_count = count($legs);
                    $legs_label = $legs_count === 1 ? 'leg' : 'legs';
                ?>
                <details class="route-node" data-route-id="<?php echo htmlspecialchars($route['route_id']); ?>"
                    data-route-name="<?php echo htmlspecialchars($route['route_name']); ?>"
                    data-start-route="<?php echo htmlspecialchars($route['start_route']); ?>"
                    data-end-route="<?php echo htmlspecialchars($route['end_route']); ?>"<?php echo $index === 0 ? ' open' : ''; ?>>
                    <summary class="route-node-head" title="<?php echo htmlspecialchars($route['route_name']); ?>">
                        <div class="route-node-head-main">
                            <span class="route-node-badge"><?php echo htmlspecialchars($route['route_initial_letters']); ?></span>
                            <div class="route-node-title">
                                <span class="route-node-name"><?php echo htmlspecialchars($route['route_name']); ?></span>
                                <span class="route-node-path">
                                    <span class="route-node-dot"></span><?php echo htmlspecialchars($route['start_route']); ?>
                                    <span class="route-node-line"></span><?php echo htmlspecialchars($route['end_route']); ?>
                                </span>
                            </div>
                            <span class="route-node-caret"><i class="uil uil-angle-down"></i></span>
                        </div>
                        <div class="route-node-stats">
                            <span class="route-stat"><i class="uil uil-clock"></i><?php echo htmlspecialchars($route['journey_duration_value'] . ' ' . $route['journey_duration_unit']); ?></span>
                            <span class="route-stat"><i class="uil uil-map-marker-alt"></i><?php echo htmlspecialchars($route['distance']); ?>
                                km</span>
                            <span class="route-stat"><i class="uil uil-directions"></i><?php echo (int) $legs_count; ?>
                                <?php echo $legs_label; ?></span>
                            <span class="route-stat route-stat-fare"><?php
                                if (!empty($route['amount'])) {
                                    echo htmlspecialchars($route['currency'] . ' ' . number_format((float) $route['amount']));
                                }
                            ?></span>
                        </div>
                    </summary>
                    <div class="route-node-legs">
                        <div class="route-node-legs-inner">
                            <div class="route-node-legs-title">Sub routes
                                <span class="route-node-legs-count"><?php echo (int) $legs_count; ?></span>
                                <button type="button" class="route-node-add-leg"
                                    data-bs-toggle="modal" data-bs-target="#sub_route_modal"
                                    data-route-id="<?php echo htmlspecialchars($route['route_id']); ?>"
                                    data-route-name="<?php echo htmlspecialchars($route['route_name']); ?>"
                                    title="Add a sub route to <?php echo htmlspecialchars($route['route_name']); ?>">
                                    <i class="uil uil-plus"></i>Sub route
                                </button>
                            </div>
                            <?php if ($legs_count === 0) { ?>
                            <p class="text-muted mb-2">No sub routes yet</p>
                            <?php } else { ?>
                            <ol class="route-leg-list">
                                <?php foreach ($legs as $leg_index => $leg) {
                                    $leg_classes = 'route-leg';
                                    if ($leg_index === 0) {
                                        $leg_classes .= ' is-first';
                                    }
                                    if ($leg_index === $legs_count - 1) {
                                        $leg_classes .= ' is-last';
                                    }
                                    if ((int) $leg['state'] === 0) {
                                        $leg_classes .= ' is-inactive';
                                    }
                                    $leg_name = $leg['starting_point'] . ' - ' . $leg['ending_point'];
                                ?>
                                <li class="<?php echo $leg_classes; ?>"
                                    data-route-id="<?php echo htmlspecialchars($leg['route_id']); ?>"
                                    data-route-name="<?php echo htmlspecialchars($leg['route_name'] ?: $leg_name); ?>"
                                    data-start-route="<?php echo htmlspecialchars($leg['starting_point']); ?>"
                                    data-end-route="<?php echo htmlspecialchars($leg['ending_point']); ?>">
                                    <span class="route-leg-marker"><span class="route-leg-index"><?php echo $leg_index + 1; ?></span></span>
                                    <div class="route-leg-body">
                                        <div class="route-leg-head">
                                            <span class="route-leg-name"><?php echo htmlspecialchars($leg_name); ?></span>
                                            <span class="route-leg-fare"><?php
                                                if (!empty($leg['amount'])) {
                                                    echo htmlspecialchars($leg['currency'] . ' ' . number_format((float) $leg['amount']));
                                                }
                                            ?></span>
                                        </div>
                                        <div class="route-leg-meta">
                                            <span class="route-leg-time"><i class="uil uil-clock-three"></i><?php echo htmlspecialchars($leg['journey_duration_value'] . ' ' . $leg['journey_duration_unit']); ?></span>
                                            <span class="route-leg-distance"><?php echo htmlspecialchars($leg['distance']); ?> km</span>
                                        </div>
                                    </div>
                                </li>
                                <?php } ?>
                            </ol>
                            <?php } ?>
                        </div>
                    </div>
                </details>
                <?php } ?>



            </div>
        </div>
        <!-- end card body -->
    </div>
    <!-- end card -->
</div>
