<?php
/**
 * A-P1-22 Revenue.
 */
declare(strict_types=1);

$reportTitle = 'Revenue';
$reportLead = 'Ticket sales, road collection, and cargo by route.';
$reportRoot = 'tds-acc-revenue';
$reportSlice = 'A-P1-22';
$reportPage = 'report_revenue.php';
$reportFlag = 'report_revenue';
$reportKind = 'revenue';
$reportCrumb = 'Financial reports';
$reportFields = ['q', 'route', 'vehicle', 'currency', 'dates'];
require __DIR__ . '/_report_shell.php';
