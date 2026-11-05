<?php
/**
 * A-P1-20 Category and particular filter.
 */
declare(strict_types=1);

$reportTitle = 'Category & particular';
$reportLead = 'Trip costs by category or particular, split by route. Leave lines unselected to include every line.';
$reportRoot = 'tds-acc-category-filter';
$reportSlice = 'A-P1-20';
$reportPage = 'account_filter.php';
$reportFlag = 'account_filter';
$reportKind = 'category_filter';
$reportCrumb = 'Financial reports';
$reportFields = ['q', 'route', 'currency', 'ledger', 'dates'];
require __DIR__ . '/_report_shell.php';
