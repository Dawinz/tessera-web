<?php
/** GET /tickets/api/console/earnings_profile.php — month sales for a booker (T-P3-02). */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_leftovers.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();
require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$who = trim((string) ($_GET['u'] ?? ''));
if ($who === '') {
	$who = $username;
}
if (!preg_match('/^[A-Za-z0-9._@-]{1,64}$/', $who)) {
	tessera_console_error('validation_error', 'Enter a staff username', 422, ['fields' => ['u' => 'invalid']]);
}

tessera_console_ok(tessera_tickets_earnings_profile($db, $who));
