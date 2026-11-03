<?php
/**
 * Operator API v1 bootstrap - Bearer API key auth, tenant-scoped.
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
	http_response_code(204);
	exit;
}

require_once dirname(__DIR__, 2) . '/includes/sr_env.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_operator_api.php';
require_once dirname(__DIR__, 2) . '/connect.php';

if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	http_response_code(503);
	echo json_encode(['ok' => false, 'error' => 'db_unavailable']);
	exit;
}

function v1_json($data, int $code = 200): void {
	http_response_code($code);
	echo json_encode($data);
	exit;
}

function v1_bearer(): string {
	$h = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
	if (preg_match('/Bearer\s+(\S+)/i', $h, $m)) {
		return $m[1];
	}
	return (string) ($_GET['api_key'] ?? '');
}

function v1_require_key(mysqli $db, ?string $scope = null): array {
	$key = tessera_api_key_authenticate($db, v1_bearer());
	if (!$key) {
		v1_json(['ok' => false, 'error' => 'unauthorized'], 401);
	}
	if ($scope !== null && !in_array($scope, $key['scopes'] ?? [], true) && !in_array('*', $key['scopes'] ?? [], true)) {
		v1_json(['ok' => false, 'error' => 'forbidden_scope', 'need' => $scope], 403);
	}
	return $key;
}
