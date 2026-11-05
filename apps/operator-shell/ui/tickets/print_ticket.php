<?php
declare(strict_types=1);
$printTitle = 'Print ticket';
$printLead = 'One passenger ticket.';
$printRoot = 'tds-tk-print-ticket';
$printSlice = 'T-P2-12';
$printPage = 'print_ticket.php';
$printFlag = 'print_ticket';
$printKind = 'ticket';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
