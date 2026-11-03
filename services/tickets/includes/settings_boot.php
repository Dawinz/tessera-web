<?php
/**
 * Boot operator settings: session, DB, tenant, RBAC.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit;
}

$sessionUser = (string) $_SESSION['username'];
require_once __DIR__ . '/sr_env.php';
require_once __DIR__ . '/tessera_tenant.php';
require_once __DIR__ . '/tessera_rbac.php';
require_once __DIR__ . '/tessera_vault.php';
require_once dirname(__DIR__) . '/connect.php';

if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	http_response_code(500);
	echo 'Database unavailable.';
	exit;
}

$username = $sessionUser;
$tenantId = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 1;
$settings_role = tessera_user_role_code($db, $username);
$settings_caps = tessera_user_capabilities($db, $username);

/* Variables expected by top_header.php */
if (!isset($firstname)) {
	$u = $db->real_escape_string($username);
	$q = @$db->query("SELECT firstname, surname, role, image FROM users WHERE username='{$u}' LIMIT 1");
	$row = $q ? $q->fetch_assoc() : null;
	$firstname = (string) ($row['firstname'] ?? $username);
	$surname = (string) ($row['surname'] ?? '');
	$level = (string) ($row['role'] ?? $settings_role);
	$image = (string) ($row['image'] ?? '');
}

function settings_h(?string $s): string {
	return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}
