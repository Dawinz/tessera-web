<?php
declare(strict_types=1);
$printTitle = 'Transfer from manifest';
$printLead = 'Move passengers from this trip onto another vehicle.';
$printRoot = 'tds-tk-transfer';
$printSlice = 'T-P2-05';
$printPage = 'transfer.php';
$printFlag = 'transfer';
$printKind = 'transfer';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/tickets/transfer.js';
$printSelectable = false;
$printMode = 'from';
require __DIR__ . '/../_print_shell.php';
