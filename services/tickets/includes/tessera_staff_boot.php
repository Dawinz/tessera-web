<?php
/**
 * Early staff gate - include before any HTML output on Tickets pages / DataTables endpoints.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
require_once __DIR__ . '/sr_env.php';
require_once __DIR__ . '/tessera_tenant.php';
tessera_abort_unknown_tenant();
if (function_exists('tessera_deny_if_tenant_suspended')) {
	tessera_deny_if_tenant_suspended('redirect');
}
if (!empty($_SESSION['username'])) {
	if (!function_exists('tessera_operator_shell_redirect_legacy_get')) {
		require_once __DIR__ . '/tessera_operator_spa.php';
	}
	tessera_operator_shell_redirect_legacy_get();
	return;
}

$script = strtolower(basename((string) ($_SERVER['SCRIPT_NAME'] ?? '')));
$isFetch = str_starts_with($script, 'fetch_');
$isMutator = str_starts_with($script, 'save_')
	|| str_starts_with($script, 'update_')
	|| str_starts_with($script, 'delete_')
	|| str_starts_with($script, 'insert_')
	|| str_starts_with($script, 'load_')
	|| str_starts_with($script, 'daily_')
	|| str_starts_with($script, 'location_');

if ($isFetch) {
	http_response_code(401);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode([
		'draw' => isset($_POST['draw']) ? (int) $_POST['draw'] : 0,
		'recordsTotal' => 0,
		'recordsFiltered' => 0,
		'data' => [],
		'error' => 'unauthorized',
	]);
	exit;
}

if ($isMutator) {
	http_response_code(401);
	header('Content-Type: text/plain; charset=utf-8');
	echo 'unauthorized';
	exit;
}

// Operator shell uses /login/ - never bounce to relative index.php
// (that is /operator/index.php, which redirects back to app.php -> loop).
$scriptPath = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
if ((defined('TESSERA_OPERATOR_SHELL_APP') && TESSERA_OPERATOR_SHELL_APP)
	|| str_contains($scriptPath, '/operator/')) {
	$next = (string) ($_SERVER['REQUEST_URI'] ?? '/operator/app.php');
	header('Location: /login/?next=' . rawurlencode($next), true, 302);
	exit;
}

tessera_redirect('index.php');
