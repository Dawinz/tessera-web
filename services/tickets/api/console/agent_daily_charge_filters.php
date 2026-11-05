<?php
/**
 * GET /tickets/api/console/agent_daily_charge_filters.php (T-P0-07).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_agent_daily_charge.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'tickets');

$canPick = tessera_agent_charge_can_pick_user($db, $username);
$users = $canPick ? tessera_agent_charge_users($db) : [$username];

tessera_console_ok([
	'can_pick_user' => $canPick,
	'users' => $users,
	'default_booker' => $username,
	'default_date_from' => date('Y-m-d'),
	'default_date_to' => date('Y-m-d'),
]);
