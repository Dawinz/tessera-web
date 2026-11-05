<?php
declare(strict_types=1);
$printTitle = 'Luggage ticket';
$printLead = 'One luggage receipt.';
$printRoot = 'tds-cg-print-ticket';
$printSlice = 'C-P2-01';
$printPage = 'print_ticket.php';
$printFlag = 'print_ticket';
$printKind = 'ticket';
$printModule = 'cargo';
$printCrumb = 'Prints';
$printApi = '/cargo/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
