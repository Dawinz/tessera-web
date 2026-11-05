<?php
/**
 * POST /tickets/api/console/route_transit_save.php — add a route transit.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_network_extra.php';

tessera_console_boot('POST, OPTIONS');
$db = tessera_network_guard('POST');
tessera_network_emit(tessera_extra_transit_save($db, tessera_network_body()));
