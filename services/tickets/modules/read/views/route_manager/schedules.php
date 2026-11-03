<?php

include_once __DIR__ . '/../../../connection/connect.php';
include_once __DIR__ . '/../../../connection/general_db.php';
require_once __DIR__ . '/../../../../includes/import_route_manager_compat.php';

$company_id = tessera_rm_company_id();
$requested_route_id = trim((string) ($_GET['route_id'] ?? ''));
$day_names = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

function schedule_clock($time)
{
    $time = trim((string) $time);
    if ($time === '') {
        return '';
    }
    if (preg_match('/^(\d{1,2}:\d{2})/', $time, $match)) {
        return $match[1];
    }
    return $time;
}

function schedule_trip_state($status)
{
    $status = strtolower(trim((string) $status));
    if ($status === 'cancelled' || $status === 'canceled') {
        return ['class' => 'is-cancelled', 'label' => 'Cancelled'];
    }
    return ['class' => 'is-open', 'label' => 'Open'];
}

/**
 * One left-border colour per bus on this board.
 *
 * @param string[] $names
 * @return array<string,string>
 */
function schedule_vehicle_colors(array $names)
{
    $palette = [
        '#038edc',
        '#0fbc8c',
        '#7c69ef',
        '#f06548',
        '#f7b84b',
        '#23b7e5',
        '#e83e8c',
        '#405189',
        '#26a69a',
        '#fd7e14',
        '#3577f1',
        '#f34e4e',
    ];

    $unique = array_values(array_unique(array_filter(array_map('strval', $names), function ($name) {
        return trim($name) !== '';
    })));
    sort($unique, SORT_STRING);

    $used = [];
    $colors = [];
    $palette_count = count($palette);

    foreach ($unique as $name) {
        $index = abs(crc32($name)) % $palette_count;
        for ($step = 0; $step < $palette_count && isset($used[$index]); $step++) {
            $index = ($index + 1) % $palette_count;
        }
        $used[$index] = true;
        $colors[$name] = $palette[$index];
    }

    return $colors;
}

function schedule_vehicle_lookup(PDO $pdo_general, array $names)
{
    $names = array_values(array_unique(array_filter($names, function ($name) {
        return $name !== '';
    })));

    if (empty($names)) {
        return [];
    }

    try {
        $placeholders = implode(',', array_fill(0, count($names), '?'));
        $stmt = $pdo_general->prepare(
            "SELECT name, seats FROM vehicle WHERE name IN ($placeholders)"
        );
        $stmt->execute($names);

        $vehicles = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $vehicle) {
            $vehicles[$vehicle['name']] = $vehicle;
        }

        return $vehicles;
    } catch (PDOException $e) {
        return [];
    }
}

function schedule_ymd($value)
{
    $value = trim((string) $value);
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return '';
    }
    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);
    return ($date && $date->format('Y-m-d') === $value) ? $value : '';
}

$filter_bus = trim((string) ($_GET['bus'] ?? ''));
$filter_date = schedule_ymd($_GET['date'] ?? '');
$filter_trip_date = schedule_ymd($_GET['trip_date'] ?? '');

$today = new DateTimeImmutable('today');
$anchor = $today;
if ($filter_date !== '') {
    $anchor = new DateTimeImmutable($filter_date);
} elseif ($filter_trip_date !== '') {
    $anchor = new DateTimeImmutable($filter_trip_date);
}

$week_days = [];
$week_monday = $anchor->modify('monday this week');
for ($offset = 0; $offset < 7; $offset++) {
    $week_days[] = $week_monday->modify("+$offset day");
}
$week_start = $week_days[0]->format('Y-m-d');
$week_end_date = $week_days[6]->format('Y-m-d');
$week_end = $week_days[6];
$week_range = $week_days[0]->format('j')
    . ($week_days[0]->format('n') === $week_end->format('n') ? '' : ' ' . $week_days[0]->format('M'))
    . ' - ' . $week_end->format('j M Y');

$route = tessera_rm_find_route($pdo, $requested_route_id, $company_id);
$route_label = $route
    ? $route['start_route'] . ' - ' . $route['end_route']
    : 'No route selected';
$route_id = $route['route_id'] ?? '';

$trips_by_day = [];
foreach ($day_names as $day_name) {
    $trips_by_day[$day_name] = [];
}

$filter_buses = [];
if ($route_id !== '') {
    $bus_stmt = $pdo->prepare(
        'SELECT DISTINCT vehicle_name
         FROM route_schedules
         WHERE route_id = :route_id AND tenant_id = :tenant_id
         ORDER BY vehicle_name ASC'
    );
    $bus_stmt->execute([
        'route_id'  => $route_id,
        'tenant_id' => tessera_rm_tenant_id(),
    ]);
    foreach ($bus_stmt->fetchAll(PDO::FETCH_ASSOC) as $bus_row) {
        $name = trim((string) $bus_row['vehicle_name']);
        if ($name !== '') {
            $filter_buses[] = $name;
        }
    }

    $schedules_sql =
        "SELECT vehicle_name, schedule_type, day_of_week, trip_date,
                DATE_FORMAT(departure_time, '%H:%i') AS departure_time,
                DATE_FORMAT(arrival_time, '%H:%i') AS arrival_time,
                notes, status
         FROM route_schedules
         WHERE route_id = :route_id
           AND tenant_id = :tenant_id
           AND (
                (schedule_type = 'weekly' AND day_of_week IN ('Mon','Tue','Wed','Thu','Fri','Sat','Sun'))
             OR (schedule_type = 'once' AND trip_date BETWEEN :week_start AND :week_end)
           )";
    $schedule_params = [
        'route_id'   => $route_id,
        'tenant_id'  => tessera_rm_tenant_id(),
        'week_start' => $week_start,
        'week_end'   => $week_end_date,
    ];
    if ($filter_bus !== '') {
        $schedules_sql .= ' AND vehicle_name = :bus';
        $schedule_params['bus'] = $filter_bus;
    }
    $schedules_sql .= ' ORDER BY departure_time ASC, vehicle_name ASC';

    $schedules_stmt = $pdo->prepare($schedules_sql);
    $schedules_stmt->execute($schedule_params);

    $vehicle_names = [];
    $rows = $schedules_stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $vehicle_names[] = trim((string) $row['vehicle_name']);
    }
    $vehicles = schedule_vehicle_lookup($pdo_general, $vehicle_names);
    $vehicle_colors = schedule_vehicle_colors($vehicle_names);

    $filter_date_day = $filter_date !== '' ? (new DateTimeImmutable($filter_date))->format('D') : '';

    foreach ($rows as $row) {
        $schedule_type = trim((string) ($row['schedule_type'] ?? ''));
        $trip_date_value = schedule_ymd(substr((string) ($row['trip_date'] ?? ''), 0, 10));
        $day_key = '';
        if ($schedule_type === 'once' && $trip_date_value !== '') {
            $trip_date = DateTimeImmutable::createFromFormat('Y-m-d', $trip_date_value);
            if ($trip_date) {
                $day_key = $trip_date->format('D');
            }
        } else {
            $day_key = trim((string) $row['day_of_week']);
        }

        if (!isset($trips_by_day[$day_key])) {
            continue;
        }

        if ($filter_date !== '' && $day_key !== $filter_date_day) {
            continue;
        }
        if ($filter_date !== '' && $schedule_type === 'once' && $trip_date_value !== $filter_date) {
            continue;
        }
        if ($filter_trip_date !== '') {
            if ($schedule_type !== 'once' || $trip_date_value !== $filter_trip_date) {
                continue;
            }
        }

        $vehicle_name = trim((string) $row['vehicle_name']);
        $vehicle = $vehicles[$vehicle_name] ?? null;
        $state = schedule_trip_state($row['status'] ?? '');

        $trips_by_day[$day_key][] = [
            'vehicle_name'   => $vehicle_name,
            'accent'         => $vehicle_colors[$vehicle_name] ?? '#038edc',
            'departure_time' => schedule_clock($row['departure_time'] ?? ''),
            'arrival_time'   => schedule_clock($row['arrival_time'] ?? ''),
            'notes'          => trim((string) ($row['notes'] ?? '')),
            'seats'          => $vehicle ? (int) $vehicle['seats'] : 0,
            'state_class'    => $state['class'],
            'status_label'   => $state['label'],
        ];
    }
}

$trip_count = 0;
foreach ($trips_by_day as $day_trips) {
    $trip_count += count($day_trips);
}

if ($filter_bus !== '' && !in_array($filter_bus, $filter_buses, true)) {
    $filter_buses[] = $filter_bus;
    sort($filter_buses, SORT_STRING);
}

?>
<div class="col-xxl-6 col-xl-7 col-lg-7" id="route_schedule_panel"
    data-route-id="<?php echo htmlspecialchars($route_id); ?>"
    data-route-name="<?php echo htmlspecialchars($route ? ($route['route_name'] ?? '') : ''); ?>"
    data-start-route="<?php echo htmlspecialchars($route ? ($route['start_route'] ?? '') : ''); ?>"
    data-end-route="<?php echo htmlspecialchars($route ? ($route['end_route'] ?? '') : ''); ?>"
    data-filter-bus="<?php echo htmlspecialchars($filter_bus); ?>"
    data-filter-date="<?php echo htmlspecialchars($filter_date); ?>"
    data-filter-trip-date="<?php echo htmlspecialchars($filter_trip_date); ?>">
    <div class="card route-schedule-card">
        <div class="card-header route-schedule-header">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="route-schedule-heading mb-0">
                    Schedules
                    <span class="route-schedule-count"><?php echo (int) $trip_count; ?></span>
                </h6>
                <button type="button" class="btn btn-soft-primary btn-sm route-schedule-add"
                    data-bs-toggle="modal" data-bs-target="#schedule_modal" title="Add Schedule">
                    <i class="uil uil-plus"></i> Add Schedule
                </button>
            </div>
            <div class="route-schedule-subhead">
                <span class="route-schedule-route"><?php echo htmlspecialchars($route_label); ?></span>
                <span class="route-schedule-range"><?php echo htmlspecialchars($week_range); ?></span>
            </div>
            <div class="route-schedule-filters">
                <select class="form-select form-select-sm" id="schedule_filter_bus" name="bus" aria-label="Filter by bus">
                    <option value="">All buses</option>
                    <?php foreach ($filter_buses as $bus_name) { ?>
                    <option value="<?php echo htmlspecialchars($bus_name); ?>"<?php echo $bus_name === $filter_bus ? ' selected' : ''; ?>>
                        <?php echo htmlspecialchars($bus_name); ?>
                    </option>
                    <?php } ?>
                </select>
                <input type="text" class="form-control form-control-sm schedule-filter-datepicker"
                    id="schedule_filter_date" name="date" value="<?php echo htmlspecialchars($filter_date); ?>"
                    placeholder="Date" aria-label="Filter by date" readonly>
            </div>
        </div><!-- end card header -->
        <div class="card-body p-0">
            <div class="route-schedule">
                <?php foreach ($day_names as $index => $day_name) {
                    $day_date = $week_days[$index];
                    $day_trips = $trips_by_day[$day_name];
                    $day_trip_count = count($day_trips);
                    $is_today = $day_date->format('Y-m-d') === $today->format('Y-m-d');
                    $day_classes = $is_today ? ' is-today' : '';
                    if ($day_trip_count === 0) {
                        $day_classes .= ' is-empty';
                    }
                    $bus_label = $day_trip_count === 1 ? '1 bus' : $day_trip_count . ' buses';
                    ?>
                <section class="schedule-day<?php echo $day_classes; ?>">
                    <div class="schedule-day-rail">
                        <span class="schedule-day-name"><?php echo htmlspecialchars($day_name); ?></span>
                        <span class="schedule-day-date"><?php echo htmlspecialchars($day_date->format('j')); ?></span>
                        <span class="schedule-day-count"><?php echo htmlspecialchars($bus_label); ?></span>
                    </div>
                    <div class="schedule-day-body">
                        <?php if ($day_trip_count === 0) { ?>
                        <p class="schedule-day-empty">No trips</p>
                        <?php } else { ?>
                        <ul class="schedule-trip-list">
                            <?php foreach ($day_trips as $trip) { ?>
                            <li class="schedule-trip <?php echo htmlspecialchars($trip['state_class']); ?>"
                                style="--trip-accent: <?php echo htmlspecialchars($trip['accent']); ?>;"<?php echo $trip['notes'] !== '' ? ' title="' . htmlspecialchars($trip['notes']) . '"' : ''; ?>>
                                <span class="schedule-trip-time"><?php echo htmlspecialchars($trip['departure_time']); ?><small><?php echo htmlspecialchars($trip['arrival_time']); ?></small></span>
                                <div class="schedule-trip-body">
                                    <div class="schedule-trip-head">
                                        <span class="schedule-trip-bus"><?php echo htmlspecialchars($trip['vehicle_name']); ?></span>
                                        <span class="schedule-trip-status"><?php echo htmlspecialchars($trip['status_label']); ?></span>
                                    </div>
                                    <?php if ($trip['seats'] > 0) { ?>
                                    <div class="schedule-trip-meta">
                                        <span class="schedule-trip-seats"><?php echo (int) $trip['seats']; ?> seats</span>
                                    </div>
                                    <?php } ?>
                                    <span class="schedule-trip-bar"><span style="width:0%"></span></span>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </div>
                </section>
                <?php } ?>
            </div>
        </div>
        <!-- end card body -->
    </div>
    <!-- end card -->
</div>
