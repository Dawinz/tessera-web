<?php
/**
 * Unified operator login — one session for Tickets, Cargo, and Accounts.
 */
require_once __DIR__ . '/boot.php';

header('Content-Type: application/json; charset=utf-8');

/* Do not authenticate on apex — clients should POST to the operator host */
if (function_exists('tessera_host_is_platform_marketing') && tessera_host_is_platform_marketing()) {
	$next = tessera_login_safe_next($_POST['next'] ?? ($_GET['next'] ?? ''));
	$dest = function_exists('tessera_operator_console_url')
		? tessera_operator_console_url('/login/' . ($next !== '' ? ('?next=' . rawurlencode($next)) : ''))
		: 'https://srclassiccoach.tessera.co.tz/login/';
	echo json_encode([
		'ok' => false,
		'error' => 'wrong_host',
		'message' => 'Sign in on your operator console.',
		'redirect' => $dest,
	]);
	exit;
}

if (!tessera_rate_limit_allow('operator_login')) {
	echo json_encode(['ok' => false, 'error' => 'rate_limited', 'message' => 'Too many attempts. Please wait and try again.']);
	exit;
}

if (function_exists('tessera_tenant_blocks_access') && tessera_tenant_blocks_access()) {
	if (function_exists('tessera_audit_blocked_access')) {
		tessera_audit_blocked_access('login', isset($_POST['username']) ? trim((string) $_POST['username']) : '');
	}
	echo json_encode(['ok' => false, 'error' => 'suspended', 'message' => 'This operator is suspended. Contact the Tessera platform owner.']);
	exit;
}

$username = isset($_POST['username']) ? trim((string) $_POST['username']) : '';
$password = isset($_POST['password']) ? (string) $_POST['password'] : '';
$next = tessera_login_safe_next($_POST['next'] ?? ($_GET['next'] ?? ''));

if ($username === '' || $password === '') {
	echo json_encode(['ok' => false, 'error' => 'invalid', 'message' => 'Enter your username and password.']);
	exit;
}

$candidates = [
	['source' => 'ticket', 'db' => $db instanceof mysqli ? $db : null],
	['source' => 'cargo', 'db' => tessera_login_mysqli(getenv('SR_CARGO_DB') ?: 'cargo')],
	['source' => 'account', 'db' => tessera_login_mysqli(getenv('SR_ACCOUNT_DB') ?: 'account')],
];

$matched = null;
foreach ($candidates as $c) {
	/** @var mysqli|null $conn */
	$conn = $c['db'];
	if (!$conn instanceof mysqli) {
		continue;
	}
	$u = $conn->real_escape_string($username);
	$q = @$conn->query(
		"SELECT username, role, password, firstname, surname, image, status
		 FROM users
		 WHERE status='active' AND (username='{$u}' OR email='{$u}' OR number='{$u}')
		 LIMIT 1"
	);
	if (!$q || !($row = $q->fetch_assoc())) {
		continue;
	}
	if (!tessera_password_verify($password, $row['password'])) {
		continue;
	}
	$matched = [
		'source' => $c['source'],
		'row' => $row,
		'conn' => $conn,
	];
	break;
}

if ($matched === null) {
	echo json_encode(['ok' => false, 'error' => 'nothing', 'message' => 'Wrong username or password.']);
	exit;
}

$row = $matched['row'];
$conn = $matched['conn'];
$canonical = (string) $row['username'];

$_SESSION['username'] = $canonical;
$_SESSION['role'] = (string) ($row['role'] ?? '');
$_SESSION['firstname'] = (string) ($row['firstname'] ?? '');
$_SESSION['surname'] = (string) ($row['surname'] ?? '');
$first = (string) ($row['firstname'] ?? '');
$sur = (string) ($row['surname'] ?? '');
$_SESSION['figure'] = $first . ($sur !== '' ? '.' . substr($sur, 0, 1) . '.' : '');

/* Upgrade legacy plaintext password in the DB that authenticated */
if (!preg_match('/^\$(2[ay]|argon2)/', (string) $row['password'])) {
	$h = $conn->real_escape_string(tessera_password_hash($password));
	$un = $conn->real_escape_string($canonical);
	@$conn->query("UPDATE users SET password='{$h}' WHERE username='{$un}' LIMIT 1");
}

$redirect = $next !== ''
	? $next
	: tessera_login_default_home($matched['source'], (string) ($row['role'] ?? ''));

echo json_encode([
	'ok' => true,
	'redirect' => $redirect,
	'source' => $matched['source'],
	'username' => $canonical,
]);
