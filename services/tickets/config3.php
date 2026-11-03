<?php
/**
 * PDO connection for DataTables endpoints that use the cargo DB (parcel_info).
 */
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';

function tickets_luggage_datatables_error(int $draw, string $message): void {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode([
		'draw' => $draw,
		'recordsTotal' => 0,
		'recordsFiltered' => 0,
		'data' => [],
		'error' => $message,
	]);
	exit;
}

$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 0;

try {
	$conn = sr_pdo(getenv('SR_CARGO_DB') ?: 'cargo');
} catch (PDOException $e) {
	tickets_luggage_datatables_error($draw, 'Database unavailable');
}
