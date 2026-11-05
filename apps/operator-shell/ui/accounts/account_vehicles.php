<?php
/**
 * A-P1-19 Vehicle grouped accounts. account_vehciles.php opens this screen.
 */
declare(strict_types=1);

$reportTitle = 'Vehicle grouped accounts';
$reportLead = 'Income, expenses, and trip count for each vehicle in a fleet group.';
$reportRoot = 'tds-acc-vehicle-grouped';
$reportSlice = 'A-P1-19';
$reportPage = 'account_vehicles.php';
$reportFlag = 'account_vehicles';
$reportKind = 'vehicle_grouped';
$reportCrumb = 'Fleet reports';
$reportFields = ['q', 'route', 'vehicle_category', 'currency', 'dates'];
require __DIR__ . '/_report_shell.php';
