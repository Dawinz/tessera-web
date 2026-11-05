<?php
declare(strict_types=1);
$printTitle = 'Expense slip';
$printLead = 'A single cargo expense.';
$printRoot = 'tds-cg-print-pos';
$printSlice = 'C-P2-06';
$printPage = 'print_pos.php';
$printFlag = 'print_pos';
$printKind = 'pos';
$printModule = 'cargo';
$printCrumb = 'Prints';
$printApi = '/cargo/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
