<?php
/**
 * Cargo console API bootstrap (staff session + cargo DB).
 */
declare(strict_types=1);

$ticketsIncludes = dirname(__DIR__, 3) . '/tickets/includes';
require_once $ticketsIncludes . '/tessera_console_api.php';
require_once $ticketsIncludes . '/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_register.php';

/**
 * @return array{0:mysqli,1:string} db + username
 */
function tessera_cargo_console_boot(string $methods = 'GET, OPTIONS'): array
{
	tessera_console_boot($methods);
	$username = tessera_console_require_session();

	require dirname(__DIR__, 2) . '/connect.php';
	if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
		tessera_console_error('db_unavailable', 'Database unavailable', 503);
	}
	tessera_console_require_capability($db, $username, 'cargo');
	return [$db, $username];
}
