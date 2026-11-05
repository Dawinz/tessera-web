<?php
/**
 * POST /tickets/api/console/sub_route_delete.php — Delete a sub-route.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_network.php';

tessera_console_boot('POST, OPTIONS');
$db = tessera_network_guard('POST');
tessera_network_emit(tessera_network_sub_route_delete($db, tessera_network_body()));
