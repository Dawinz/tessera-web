<?php
declare(strict_types=1);
$printTitle = 'Store manifest';
$printLead = 'Luggage still in the store.';
$printRoot = 'tds-cg-print-store';
$printSlice = 'C-P2-04';
$printPage = 'print_store.php';
$printFlag = 'print_store';
$printKind = 'store';
$printModule = 'cargo';
$printCrumb = 'Prints';
$printApi = '/cargo/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
