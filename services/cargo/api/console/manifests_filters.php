<?php
/**
 * GET /cargo/api/console/manifests_filters.php — routes + vehicles (C-P0-04).
 */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_manifests.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

tessera_console_ok(tessera_cargo_manifests_filters($db, $username));
