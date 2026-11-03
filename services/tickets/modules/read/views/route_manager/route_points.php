<?php

include_once __DIR__ . '/../../../connection/connect.php';
require_once __DIR__ . '/../../../create/records/includes/route_manager/point_times.php';
require_once __DIR__ . '/../../../../includes/import_route_manager_compat.php';

$company_id = tessera_rm_company_id();
$requested_route_id = trim((string) ($_GET['route_id'] ?? ''));

function read_route_points(PDO $pdo, $table, $route_id, $company_id)
{
    $point_tables = [
        'route_depature_points' => [
            'times'  => ROUTE_DEPARTURE_TIMES_TABLE,
            'clock'  => 't.departure_time',
            'report' => "DATE_FORMAT(t.report_time, '%H:%i')",
        ],
        'route_arrival_points' => [
            'times'  => ROUTE_ARRIVAL_TIMES_TABLE,
            'clock'  => 't.arrival_time',
            'report' => 'NULL',
        ],
    ];

    if (!isset($point_tables[$table])) {
        return [];
    }

    ensure_route_point_tables($pdo);
    ensure_route_point_time_tables($pdo);

    $times_table = $point_tables[$table]['times'];
    $clock_column = $point_tables[$table]['clock'];
    $report_sql = $point_tables[$table]['report'];

    $stmt = $pdo->prepare(
        "SELECT p.id, p.name,
                {$report_sql} AS report_time,
                DATE_FORMAT({$clock_column}, '%H:%i') AS point_time,
                (SELECT lp.location FROM route_location_points lp WHERE lp.name = p.name LIMIT 1) AS location
         FROM {$table} p
         LEFT JOIN {$times_table} t
            ON t.point_id = p.id
           AND t.route_id = p.route_id
           AND t.company_id = p.company_id
         WHERE p.route_id = :route_id AND p.company_id = :company_id
         ORDER BY p.id ASC"
    );
    $stmt->execute([
        'route_id'   => $route_id,
        'company_id' => $company_id,
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$route = tessera_rm_find_route($pdo, $requested_route_id, $company_id);
$route_label = $route
    ? $route['start_route'] . ' - ' . $route['end_route']
    : 'No route selected';
$route_id = $route['route_id'] ?? '';

$departure_points = $route_id === ''
    ? []
    : read_route_points($pdo, 'route_depature_points', $route_id, $company_id);
$arrival_points = $route_id === ''
    ? []
    : read_route_points($pdo, 'route_arrival_points', $route_id, $company_id);

?>
<div id="route_points_panel" data-route-id="<?php echo htmlspecialchars($route_id); ?>"
    data-route-name="<?php echo htmlspecialchars($route ? ($route['route_name'] ?? '') : ''); ?>"
    data-start-route="<?php echo htmlspecialchars($route ? ($route['start_route'] ?? '') : ''); ?>"
    data-end-route="<?php echo htmlspecialchars($route ? ($route['end_route'] ?? '') : ''); ?>">
    <div class="card route-points-card">
        <div class="card-header route-points-header">
            <h6 class="route-points-heading mb-1">Departure &amp; Arrival</h6>
            <span class="route-points-route"><?php echo htmlspecialchars($route_label); ?></span>
        </div><!-- end card header -->
        <div class="card-body p-0">
            <div class="route-points">

                <section class="point-group is-departure">
                    <header class="point-group-head">
                        <span class="point-group-icon"><i class="uil uil-sign-out-alt"></i></span>
                        <span class="point-group-title">Departure Points</span>
                        <span class="point-group-count"><?php echo count($departure_points); ?></span>
                    </header>
                    <?php if (empty($departure_points)) { ?>
                    <p class="point-group-empty">No departure points yet</p>
                    <?php } else { ?>
                    <ul class="point-list">
                        <?php foreach ($departure_points as $index => $point) {
                            $location = trim((string) ($point['location'] ?? ''));
                            if ($location === '' && !empty($route['start_route'])) {
                                $location = $route['start_route'];
                            }
                            $depart_minutes = (6 * 60) + ($index * 30);
                            $report_minutes = $depart_minutes - 30;
                            $depart_time = trim((string) ($point['point_time'] ?? ''));
                            $report_time = trim((string) ($point['report_time'] ?? ''));
                            if ($depart_time === '') {
                                $depart_time = sprintf('%02d:%02d', intdiv($depart_minutes, 60) % 24, $depart_minutes % 60);
                            }
                            if ($report_time === '') {
                                $report_time = sprintf('%02d:%02d', intdiv($report_minutes, 60) % 24, $report_minutes % 60);
                            }
                        ?>
                        <li class="point-item">
                            <span class="point-item-marker"></span>
                            <div class="point-item-body">
                                <span class="point-item-name"><?php echo htmlspecialchars($point['name']); ?></span>
                                <?php if ($location !== '') { ?>
                                <span class="point-item-location"><?php echo htmlspecialchars($location); ?></span>
                                <?php } ?>
                                <span class="point-item-report"><i class="uil uil-clock"></i>Report
                                    by <?php echo htmlspecialchars($report_time); ?></span>
                            </div>
                            <span class="point-item-time" title="Departure time"><?php echo htmlspecialchars($depart_time); ?></span>
                            <button type="button" class="point-item-set-time"
                                data-point-id="<?php echo (int) $point['id']; ?>"
                                data-point-kind="departure"
                                data-route-id="<?php echo htmlspecialchars($route_id); ?>"
                                data-point-name="<?php echo htmlspecialchars($point['name']); ?>"
                                data-report-time="<?php echo htmlspecialchars($report_time); ?>"
                                data-point-time="<?php echo htmlspecialchars($depart_time); ?>"
                                title="Update time">
                                <i class="uil uil-pen"></i>
                            </button>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </section>

                <section class="point-group is-arrival">
                    <header class="point-group-head">
                        <span class="point-group-icon"><i class="uil uil-sign-in-alt"></i></span>
                        <span class="point-group-title">Arrival Points</span>
                        <span class="point-group-count"><?php echo count($arrival_points); ?></span>
                    </header>
                    <?php if (empty($arrival_points)) { ?>
                    <p class="point-group-empty">No arrival points yet</p>
                    <?php } else { ?>
                    <ul class="point-list">
                        <?php foreach ($arrival_points as $index => $point) {
                            $location = trim((string) ($point['location'] ?? ''));
                            if ($location === '' && !empty($route['end_route'])) {
                                $location = $route['end_route'];
                            }
                            $arrive_minutes = (13 * 60 + 30) + ($index * 30);
                            $arrive_time = trim((string) ($point['point_time'] ?? ''));
                            if ($arrive_time === '') {
                                $arrive_time = sprintf('%02d:%02d', intdiv($arrive_minutes, 60) % 24, $arrive_minutes % 60);
                            }
                        ?>
                        <li class="point-item">
                            <span class="point-item-marker"></span>
                            <div class="point-item-body">
                                <span class="point-item-name"><?php echo htmlspecialchars($point['name']); ?></span>
                                <?php if ($location !== '') { ?>
                                <span class="point-item-location"><?php echo htmlspecialchars($location); ?></span>
                                <?php } ?>
                            </div>
                            <span class="point-item-time" title="Arrival time"><?php echo htmlspecialchars($arrive_time); ?></span>
                            <button type="button" class="point-item-set-time"
                                data-point-id="<?php echo (int) $point['id']; ?>"
                                data-point-kind="arrival"
                                data-route-id="<?php echo htmlspecialchars($route_id); ?>"
                                data-point-name="<?php echo htmlspecialchars($point['name']); ?>"
                                data-point-time="<?php echo htmlspecialchars($arrive_time); ?>"
                                title="Update time">
                                <i class="uil uil-pen"></i>
                            </button>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </section>

            </div>
        </div>
        <!-- end card body -->
    </div>
    <!-- end card -->
</div>
