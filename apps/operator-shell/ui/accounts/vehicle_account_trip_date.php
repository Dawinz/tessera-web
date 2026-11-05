<?php
declare(strict_types=1);
$printTitle = 'Trip-date particulars';
$printLead = 'Particulars grouped by trip date.';
$printRoot = 'tds-acc-print-trip-date';
$printSlice = 'A-P2-04';
$printPage = 'vehicle_account_trip_date.php';
$printFlag = 'vehicle_account_trip_date';
$printKind = 'trip_date';
$printModule = 'accounts';
$printCrumb = 'Drill-downs';
$printApi = '/accounts/api/console/print_drill.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
