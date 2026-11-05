<?php
declare(strict_types=1);
$printTitle = 'Transfer multiple';
$printLead = 'Move the passengers you select.';
$printRoot = 'tds-tk-transfer-multiple';
$printSlice = 'T-P2-07';
$printPage = 'transfer_multiple.php';
$printFlag = 'transfer_multiple';
$printKind = 'transfer';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/tickets/transfer.js';
$printSelectable = false;
$printMode = 'multiple';
require __DIR__ . '/../_print_shell.php';
