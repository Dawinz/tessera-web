<?php
/**
 * A-P1-23 Expense.
 */
declare(strict_types=1);

$reportTitle = 'Expense';
$reportLead = 'Paid trip costs by category against the previous period, plus office expenses.';
$reportRoot = 'tds-acc-expense';
$reportSlice = 'A-P1-23';
$reportPage = 'report_expenses.php';
$reportFlag = 'report_expenses';
$reportKind = 'expense';
$reportCrumb = 'Financial reports';
$reportFields = ['q', 'route', 'vehicle', 'currency', 'dates'];
require __DIR__ . '/_report_shell.php';
