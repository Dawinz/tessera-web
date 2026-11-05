<?php
declare(strict_types=1);
$printTitle = 'Transfer selected seats';
$printLead = 'Move checked seats onto a free seat on the target trip.';
$printRoot = 'tds-tk-transfer-selected';
$printSlice = 'T-P2-08';
$printPage = 'transfer_selected_seats.php';
$printFlag = 'transfer_selected_seats';
$printKind = 'transfer';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/tickets/transfer.js';
$printSelectable = false;
$printMode = 'selected';
require __DIR__ . '/../_print_shell.php';
