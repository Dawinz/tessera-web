<?php
/**
 * Enter a time-limited support session into the operator Tickets console.
 * Token is one-time; created from Tessera Control.
 */
ini_set('display_errors', '0');
error_reporting(E_ERROR | E_PARSE);

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/includes/tessera_tenant.php';

$token = (string) ($_GET['token'] ?? '');
if ($token === '' || strlen($token) < 32) {
	http_response_code(400);
	echo 'Invalid support link.';
	exit;
}

$hash = hash('sha256', $token);
$hash_e = $db->real_escape_string($hash);
$q = @$db->query(
	"SELECT * FROM tessera_support_tokens
	 WHERE token_hash='{$hash_e}' AND used_at IS NULL AND expires_at > NOW()
	 LIMIT 1"
);
$row = $q ? $q->fetch_assoc() : null;
if (!$row) {
	http_response_code(403);
	echo 'Support link expired or already used.';
	exit;
}

$username = (string) $row['target_username'];
$username_e = $db->real_escape_string($username);
$uq = @$db->query("SELECT username, status FROM users WHERE username='{$username_e}' LIMIT 1");
$user = $uq ? $uq->fetch_assoc() : null;
if (!$user || strtolower((string) $user['status']) !== 'active') {
	http_response_code(403);
	echo 'Target staff user unavailable.';
	exit;
}

$id = (int) $row['id'];
@$db->query("UPDATE tessera_support_tokens SET used_at=NOW() WHERE id={$id} LIMIT 1");

/* Audit consumption via platform audit if available */
if (function_exists('control_audit')) {
	/* not loaded in tickets - write directly */
}
$actor_e = $db->real_escape_string((string) $row['actor']);
$tid = (int) $row['tenant_id'];
$meta = $db->real_escape_string(json_encode(['target' => $username, 'token_id' => $id]));
@$db->query(
	"INSERT INTO tessera_platform_audit (actor, action, tenant_id, meta_json)
	 VALUES ('{$actor_e}', 'support.session_enter', {$tid}, '{$meta}')"
);

$_SESSION['username'] = $username;
$_SESSION['tessera_support'] = 1;
$_SESSION['tessera_support_actor'] = (string) $row['actor'];
$_SESSION['tessera_support_expires'] = strtotime((string) $row['expires_at']);
$_SESSION['tessera_support_tenant_id'] = $tid;

header('Location: home.php');
exit;
