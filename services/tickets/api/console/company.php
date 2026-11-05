<?php
/**
 * GET /tickets/api/console/company.php — company profile (S-01).
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_settings_company.php';

tessera_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');
$username = tessera_console_require_session();

require_once dirname(__DIR__, 2) . '/connect.php';
if (!isset($db) || !($db instanceof mysqli) || $db->connect_error) {
	tessera_console_error('db_unavailable', 'Database unavailable', 503);
}
tessera_console_require_capability($db, $username, 'settings');

$result = tessera_settings_company_get();
if (empty($result['ok'])) {
	tessera_console_error('not_found', (string) ($result['error'] ?? 'Company not found'), 404);
}

tessera_console_ok(['item' => $result['item']]);
