<?php
/**
 * PDO connection for DataTables / fetch_* endpoints (ticket DB).
 */
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';

try {
	$conn = sr_pdo('ticket');
} catch (PDOException $e) {
	header('Content-Type: application/json; charset=utf-8');
	http_response_code(500);
	echo json_encode([
		'draw' => isset($_POST['draw']) ? (int) $_POST['draw'] : 0,
		'recordsTotal' => 0,
		'recordsFiltered' => 0,
		'data' => [],
		'error' => 'Database unavailable',
	]);
	exit;
}
