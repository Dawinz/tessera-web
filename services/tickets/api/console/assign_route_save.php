<?php
/**
 * POST /tickets/api/console/assign_route_save.php — tick or clear a route for a user.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_network_extra.php';

tessera_console_boot('POST, OPTIONS');
$db = tessera_network_guard('POST');
tessera_network_emit(tessera_extra_assign_save($db, tessera_network_body()));
