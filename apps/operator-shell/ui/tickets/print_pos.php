<?php
declare(strict_types=1);
$printTitle = 'Print POS';
$printLead = 'Short passenger list for the driver.';
$printRoot = 'tds-tk-print-pos';
$printSlice = 'T-P2-11';
$printPage = 'print_pos.php';
$printFlag = 'print_pos';
$printKind = 'pos';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
