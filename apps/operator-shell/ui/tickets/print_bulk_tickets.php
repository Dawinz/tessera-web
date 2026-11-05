<?php
declare(strict_types=1);
$printTitle = 'Print bulk tickets';
$printLead = 'Tickets queued for this sign-in.';
$printRoot = 'tds-tk-print-bulk';
$printSlice = 'T-P2-16';
$printPage = 'print_bulk_tickets.php';
$printFlag = 'print_bulk_tickets';
$printKind = 'bulk';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
