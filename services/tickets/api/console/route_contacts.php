<?php
/**
 * GET /tickets/api/console/route_contacts.php — route contact list.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/includes/tessera_console_api.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_rbac.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tenant.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_tickets_network_extra.php';

tessera_console_boot('GET, OPTIONS');
$db = tessera_network_guard('GET');
tessera_network_emit(tessera_extra_contacts_list($db, $_GET));
