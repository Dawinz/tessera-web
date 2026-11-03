<?php
/**
 * Delegate to the Tickets provisioner so seed lists cannot drift.
 */
$cands = [
	'/var/www/html/tickets/includes/tessera_db_provision.php',
	dirname(__DIR__, 2) . '/tickets/includes/tessera_db_provision.php',
];
foreach ($cands as $f) {
	if (is_file($f)) {
		require_once $f;
		return;
	}
}
