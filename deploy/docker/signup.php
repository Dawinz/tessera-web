<?php
/**
 * Wave K2 — public self-serve signup → provisioning tenant + onboarding case.
 * Unauthenticated; does not load Control host isolation bootstrap.
 */
declare(strict_types=1);

/* Allow marketing site (Vercel) to POST signup to the Hetzner backend. */
$__origin = (string) ($_SERVER['HTTP_ORIGIN'] ?? '');
$__allowed = [
	'https://tessera.co.tz',
	'https://www.tessera.co.tz',
	'https://tessera-www.vercel.app',
];
$__extra = getenv('TESSERA_SIGNUP_CORS_ORIGINS');
if (is_string($__extra) && $__extra !== '') {
	foreach (preg_split('/\s*,\s*/', $__extra) ?: [] as $o) {
		if ($o !== '') {
			$__allowed[] = rtrim($o, '/');
		}
	}
}
if ($__origin !== '' && in_array($__origin, $__allowed, true)) {
	header('Access-Control-Allow-Origin: ' . $__origin);
	header('Access-Control-Allow-Methods: POST, OPTIONS');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Vary: Origin');
}
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
	http_response_code(204);
	exit;
}

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	http_response_code(405);
	echo json_encode(['ok' => false, 'error' => 'method']);
	exit;
}

/* Honeypot */
if (trim((string) ($_POST['website'] ?? '')) !== '') {
	echo json_encode(['ok' => true, 'queued' => true]);
	exit;
}

$rlPaths = [
	'/var/www/html/tickets/includes/tessera_rate_limit.php',
	dirname(__DIR__, 2) . '/services/tickets/includes/tessera_rate_limit.php',
];
foreach ($rlPaths as $rl) {
	if (is_file($rl)) {
		require_once $rl;
		break;
	}
}
if (function_exists('tessera_rate_limit_allow') && !tessera_rate_limit_allow('public_signup', 8, 600)) {
	http_response_code(429);
	echo json_encode(['ok' => false, 'error' => 'rate_limit']);
	exit;
}

$controlInc = is_dir('/var/www/html/control/includes')
	? '/var/www/html/control/includes'
	: dirname(__DIR__, 2) . '/apps/platform-web/includes';

require_once $controlInc . '/connect.php';
require_once $controlInc . '/control_golive.php';
require_once $controlInc . '/control_data.php';
/* Notify stack needs config + ops (not full Control bootstrap / host isolate). */
if (is_file($controlInc . '/control_config.php')) {
	require_once $controlInc . '/control_config.php';
}
if (is_file($controlInc . '/control_ops.php')) {
	require_once $controlInc . '/control_ops.php';
}
if (is_file($controlInc . '/control_alerts.php') && function_exists('control_config')) {
	require_once $controlInc . '/control_alerts.php';
}

$control_db_ok = isset($db) && $db instanceof mysqli && !$db->connect_error;

$company = trim((string) ($_POST['company'] ?? ''));
$slug = strtolower(trim((string) ($_POST['slug'] ?? '')));
$slug = preg_replace('/[^a-z0-9-]/', '', $slug) ?? '';
$contact = trim((string) ($_POST['contact_name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$notes = trim((string) ($_POST['notes'] ?? ''));

$reserved = ['www', 'console', 'control', 'api', 'mail', 'ftp', 'sr-classic', 'pilot-coach', 'staging', 'admin', 'app', 'tickets', 'cargo', 'accounts'];

if ($company === '' || strlen($company) < 2 || strlen($company) > 120) {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'company']);
	exit;
}
if ($slug === '' || strlen($slug) < 3 || strlen($slug) > 40 || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'slug']);
	exit;
}
if (in_array($slug, $reserved, true)) {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'slug_reserved']);
	exit;
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'email']);
	exit;
}
if ($contact === '') {
	http_response_code(400);
	echo json_encode(['ok' => false, 'error' => 'contact']);
	exit;
}

if ($control_db_ok && control_table_exists($db, 'tessera_tenants')) {
	$chk = @$db->query("SELECT id FROM tessera_tenants WHERE slug='" . $db->real_escape_string($slug) . "' LIMIT 1");
	if ($chk && $chk->num_rows > 0) {
		http_response_code(409);
		echo json_encode(['ok' => false, 'error' => 'slug_taken']);
		exit;
	}
}

$noteLine = '[self-serve] ' . ($notes !== '' ? $notes : 'Public signup from tessera.co.tz');
$case = control_create_case($db ?? null, $control_db_ok, [
	'company_name' => $company,
	'slug' => $slug,
	'provision_tenant' => true,
	'contact_name' => $contact,
	'contact_email' => $email,
	'contact_phone' => $phone,
	'owner_label' => 'Self-serve',
	'notes' => $noteLine,
], 'public-signup@tessera');

if (!$case) {
	http_response_code(500);
	echo json_encode(['ok' => false, 'error' => 'create_failed']);
	exit;
}

if (function_exists('control_notify') && function_exists('control_config')) {
	try {
		control_notify($db ?? null, $control_db_ok, [
			'type' => 'signup.self_serve',
			'severity' => 'info',
			'title' => 'New self-serve signup',
			'body' => "{$company} requested {$slug}.tessera.co.tz",
			'tenant_id' => isset($case['tenant_id']) ? (int) $case['tenant_id'] : null,
			'href' => 'onboarding_case.php?id=' . (int) ($case['id'] ?? 0),
			'dedupe_key' => 'signup:' . $slug . ':' . date('Y-m-d'),
		]);
	} catch (Throwable $e) {
		/* Signup already queued — notify is best-effort. */
	}
}

echo json_encode([
	'ok' => true,
	'queued' => true,
	'slug' => $slug,
	'domain' => $slug . '.tessera.co.tz',
	'case_id' => (int) ($case['id'] ?? 0),
]);
