<?php
/**
 * A-P1-18 Vehicle statement.
 */
declare(strict_types=1);

$reportTitle = 'Vehicle statement';
$reportLead = 'Ticket, road, and cargo income against paid trip costs for each vehicle.';
$reportRoot = 'tds-acc-vehicle-statement';
$reportSlice = 'A-P1-18';
$reportPage = 'vehicle_report.php';
$reportFlag = 'vehicle_report';
$reportKind = 'vehicle_statement';
$reportCrumb = 'Fleet reports';
$reportFields = ['q', 'route', 'vehicle', 'currency', 'dates'];
require __DIR__ . '/_report_shell.php';
