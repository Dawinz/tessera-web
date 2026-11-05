<?php
/**
 * A-P1-24 Payables.
 */
declare(strict_types=1);

$reportTitle = 'Payables';
$reportLead = 'Trip costs still marked To be paid, grouped by how long they have been outstanding.';
$reportRoot = 'tds-acc-payables';
$reportSlice = 'A-P1-24';
$reportPage = 'report_payables.php';
$reportFlag = 'report_payables';
$reportKind = 'payables';
$reportCrumb = 'Financial reports';
$reportFields = ['q', 'route', 'vehicle', 'currency', 'as_of'];
require __DIR__ . '/_report_shell.php';
