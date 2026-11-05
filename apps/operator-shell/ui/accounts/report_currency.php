<?php
/**
 * A-P1-25 Currency position.
 */
declare(strict_types=1);

$reportTitle = 'Currency position';
$reportLead = 'Income and paid expenses in each currency, converted to the primary currency.';
$reportRoot = 'tds-acc-currency';
$reportSlice = 'A-P1-25';
$reportPage = 'report_currency.php';
$reportFlag = 'report_currency';
$reportKind = 'currency_position';
$reportCrumb = 'Financial reports';
$reportFields = ['q', 'route', 'vehicle', 'dates'];
require __DIR__ . '/_report_shell.php';
