<?php
/**
 * A-P1-21 Cash flow.
 */
declare(strict_types=1);

$reportTitle = 'Cash flow';
$reportLead = 'Trip income, paid trip costs, office expenses, and cash-book deposits, with a running balance.';
$reportRoot = 'tds-acc-cash-flow';
$reportSlice = 'A-P1-21';
$reportPage = 'report_cash_flow.php';
$reportFlag = 'report_cash_flow';
$reportKind = 'cash_flow';
$reportCrumb = 'Financial reports';
$reportFields = ['route', 'currency', 'dates'];
require __DIR__ . '/_report_shell.php';
