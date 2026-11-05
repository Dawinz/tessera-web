<?php
declare(strict_types=1);
$printTitle = 'Print ticket and luggage';
$printLead = 'Luggage receipt that travels with the ticket.';
$printRoot = 'tds-tk-print-luggage';
$printSlice = 'T-P2-14';
$printPage = 'print_ticket_luggage.php';
$printFlag = 'print_ticket_luggage';
$printKind = 'ticket_luggage';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
