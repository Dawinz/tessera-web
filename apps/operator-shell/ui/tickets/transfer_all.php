<?php
declare(strict_types=1);
$printTitle = 'Transfer all';
$printLead = 'Move every passenger on this trip.';
$printRoot = 'tds-tk-transfer-all';
$printSlice = 'T-P2-06';
$printPage = 'transfer_all.php';
$printFlag = 'transfer_all';
$printKind = 'transfer';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/tickets/transfer.js';
$printSelectable = false;
$printMode = 'all';
require __DIR__ . '/../_print_shell.php';
