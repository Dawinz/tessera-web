<?php
declare(strict_types=1);
$printTitle = 'Vehicle report';
$printLead = 'Income and expense lines for one vehicle.';
$printRoot = 'tds-acc-print-vehicle';
$printSlice = 'A-P2-05';
$printPage = 'vehicle_report_vehicle.php';
$printFlag = 'vehicle_report_vehicle';
$printKind = 'vehicle';
$printModule = 'accounts';
$printCrumb = 'Drill-downs';
$printApi = '/accounts/api/console/print_drill.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
