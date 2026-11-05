<?php
/**
 * GET /tickets/api/console/sub_routes.php — Sub-routes for this tenant.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_network.php';

tessera_console_boot('GET, OPTIONS');
$db = tessera_network_guard('GET');
tessera_network_emit(tessera_network_sub_routes_list($db, $_GET));
