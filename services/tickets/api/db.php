<?php
/**
 * Shared DB connection for mobile / POS booking API.
 */
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../includes/sr_env.php';

try {
	$pdo = sr_pdo(getenv('SR_DB_NAME') ?: 'ticket');
	$GLOBALS['pdo'] = $pdo;
} catch (PDOException $e) {
	http_response_code(500);
	header('Content-Type: application/json');
	echo json_encode([
		'error' => 'Database connection failed',
		'detail' => $e->getMessage(),
	]);
	exit;
}
