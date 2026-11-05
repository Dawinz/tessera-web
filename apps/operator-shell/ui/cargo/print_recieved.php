<?php
declare(strict_types=1);
$printTitle = 'Checkout receipt';
$printLead = 'Receipt for luggage that has been collected.';
$printRoot = 'tds-cg-print-receipt';
$printSlice = 'C-P2-05';
$printPage = 'print_recieved.php';
$printFlag = 'print_recieved';
$printKind = 'receipt';
$printModule = 'cargo';
$printCrumb = 'Prints';
$printApi = '/cargo/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
