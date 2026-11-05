<?php
/**
 * POST /tickets/api/console/trip_template_delete.php — Delete a trip template.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_network.php';

tessera_console_boot('POST, OPTIONS');
$db = tessera_network_guard('POST');
tessera_network_emit(tessera_network_trip_template_delete($db, tessera_network_body()));
