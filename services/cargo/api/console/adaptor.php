<?php
/** GET /cargo/api/console/adaptor.php — day's luggage money lines (C-P3-01). */
declare(strict_types=1);

require_once __DIR__ . '/_boot.php';
require_once dirname(__DIR__, 2) . '/includes/tessera_cargo_leftovers.php';

[$db, $username] = tessera_cargo_console_boot('GET, OPTIONS');
tessera_console_require_method('GET');

$date = trim((string) ($_GET['date'] ?? ''));
$q = trim((string) ($_GET['q'] ?? ''));
tessera_console_ok(tessera_cargo_adaptor_list($db, $username, $date, $q));
