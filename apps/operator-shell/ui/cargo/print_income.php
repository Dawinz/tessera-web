<?php
declare(strict_types=1);
$printTitle = 'Income slip';
$printLead = 'Cargo income voucher.';
$printRoot = 'tds-cg-print-income';
$printSlice = 'C-P2-07';
$printPage = 'print_income.php';
$printFlag = 'print_income';
$printKind = 'income';
$printModule = 'cargo';
$printCrumb = 'Prints';
$printApi = '/cargo/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
