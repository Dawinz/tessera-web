<?php
/**
 * Early staff gate for Cargo - include before any HTML / JSON output.
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
		require_once dirname(__DIR__, 2) . '/tickets/includes/tessera_operator_spa.php';
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
	|| str_starts_with($script, 'load_');

if ($isFetch) {
	http_response_code(401);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode([
		'draw' => isset($_POST['draw']) ? (int) $_POST['draw'] : 0,
		'recordsTotal' => 0,
		'recordsFiltered' => 0,
		'data' => [],
		'iTotalRecords' => 0,
		'iTotalDisplayRecords' => 0,
		'aaData' => [],
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

if (function_exists('tessera_redirect')) {
	tessera_redirect('index.php');
}
header('Location: index.php');
exit;
