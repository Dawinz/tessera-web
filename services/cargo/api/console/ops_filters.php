<?php
/**
 * GET /cargo/api/console/ops_filters.php — routes, sellers, and buses for cargo ops screens.
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_charges.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$filters = tessera_cargo_charges_filters($db, $username);
$buses = [];
$bq = $db->query(
	"SELECT Name, MAX(date) AS last_date FROM vehicle_name
	 WHERE date >= '2020-01-01' GROUP BY Name ORDER BY last_date DESC LIMIT 60"
);
if ($bq) {
	while ($row = $bq->fetch_assoc()) {
		$name = trim((string) ($row['Name'] ?? ''));
		if ($name !== '') {
			$buses[] = $name;
		}
	}
}

tessera_console_ok([
	'routes' => $filters['routes'],
	'users' => $filters['users'],
	'can_pick_user' => $filters['can_pick_user'],
	'me' => $filters['me'],
	'buses' => $buses,
]);
