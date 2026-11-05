<?php
declare(strict_types=1);
$printTitle = 'Print ticket table';
$printLead = 'Ticket details in a table.';
$printRoot = 'tds-tk-print-table';
$printSlice = 'T-P2-19';
$printPage = 'print_ticket_table.php';
$printFlag = 'print_ticket_table';
$printKind = 'ticket_table';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
