<?php
/**
 * GET /tickets/api/console/vehicles.php — fleet list (T-P0-05).
 * Query: q
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/vehicle_seat_layout.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$tenantWhere = tessera_sql_tenant_eq();
$search = trim((string) ($_GET['q'] ?? ''));

$sql = 'SELECT id, name, driver, conductor, seats, status, state';
if (tessera_vehicle_has_layout_column($db)) {
	$sql .= ', seat_layout_json';
}
$sql .= " FROM vehicle WHERE $tenantWhere";
if ($search !== '') {
	$esc = mysqli_real_escape_string($db, $search);
	$sql .= " AND (name LIKE '%$esc%' OR driver LIKE '%$esc%' OR conductor LIKE '%$esc%')";
}
$sql .= ' ORDER BY name ASC LIMIT 500';

$items = [];
$q = mysqli_query($db, $sql);
while ($q && ($row = mysqli_fetch_assoc($q))) {
	$patterns = tessera_vehicle_row_patterns($row);
	$extras = tessera_vehicle_layout_extras($row);
	$layoutSeats = tessera_count_seats_in_patterns($patterns, $extras);
	$items[] = [
		'id' => (int) $row['id'],
		'name' => (string) $row['name'],
		'driver' => (string) $row['driver'],
		'conductor' => (string) $row['conductor'],
		'seats' => (int) $row['seats'],
		'status' => (string) $row['status'],
		'state' => (string) ($row['state'] ?? 'normal'),
		'layout_seats' => $layoutSeats,
		'layout_ok' => $layoutSeats === (int) $row['seats'],
		'has_custom_layout' => !empty($row['seat_layout_json']),
	];
}

tessera_console_ok([
	'items' => $items,
	'total' => count($items),
]);
