<?php
/**
 * GET /tickets/api/console/vehicle_assignments.php?id= — trip templates + recent manifests (T-P0-05).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$id = (int) ($_GET['id'] ?? 0);
$tenantId = (int) tessera_current_tenant_id();
if ($id < 1 || $tenantId < 1) {
	tessera_console_error('validation_error', 'Vehicle id is required', 422);
}

$stmt = $db->prepare('SELECT name FROM vehicle WHERE id = ? AND tenant_id = ? LIMIT 1');
$stmt->bind_param('ii', $id, $tenantId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res ? $res->fetch_assoc() : null;
$stmt->close();
if (!$row) {
	tessera_console_error('not_found', 'Vehicle not found', 404);
}

$bus = mysqli_real_escape_string($db, (string) $row['name']);
$tenantWhere = tessera_sql_tenant_eq();

$templates = [];
$tq = mysqli_query(
	$db,
	"SELECT rd.id, rd.day, rd.departure, rd.arrival, rd.status,
	        COALESCE(NULLIF(r.fullname,''), CONCAT(r.start_route, ' to ', r.end_route)) AS route_name
	 FROM route_day rd
	 LEFT JOIN routes r ON (r.route_id = rd.route_id OR r.id = rd.route_id)
	 WHERE rd.bus = '$bus' AND rd.tenant_id = " . (int) $tenantId . "
	 ORDER BY rd.day ASC, route_name ASC
	 LIMIT 100"
);
while ($tq && ($t = mysqli_fetch_assoc($tq))) {
	$templates[] = [
		'id' => (int) $t['id'],
		'day' => (string) $t['day'],
		'departure' => (string) $t['departure'],
		'arrival' => (string) $t['arrival'],
		'status' => (string) $t['status'],
		'route_name' => (string) ($t['route_name'] ?? ''),
		'url' => '/tickets/register_trip.php',
	];
}

$manifests = [];
$mq = mysqli_query(
	$db,
	"SELECT id, trip_date, route_name, driver, conductor, start_location, end_location
	 FROM manifest
	 WHERE bus = '$bus'
	 ORDER BY trip_date DESC
	 LIMIT 30"
);
while ($mq && ($m = mysqli_fetch_assoc($mq))) {
	$routeLabel = (string) ($m['route_name'] ?? '');
	if ($routeLabel === '') {
		$routeLabel = trim((string) ($m['start_location'] ?? '') . ' → ' . (string) ($m['end_location'] ?? ''));
	}
	$manifests[] = [
		'id' => (int) $m['id'],
		'trip_date' => (string) $m['trip_date'],
		'route_name' => $routeLabel,
		'driver' => (string) ($m['driver'] ?? ''),
		'conductor' => (string) ($m['conductor'] ?? ''),
		'url' => '/operator/app.php?module=tickets&page=manifest.php&q=' . rawurlencode((string) $m['trip_date']),
	];
}

tessera_console_ok([
	'templates' => $templates,
	'manifests' => $manifests,
]);
