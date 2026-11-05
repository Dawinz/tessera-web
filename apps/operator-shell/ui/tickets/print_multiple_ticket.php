<?php
declare(strict_types=1);
$printTitle = 'Print multiple tickets';
$printLead = 'Several tickets on one sheet.';
$printRoot = 'tds-tk-print-multiple';
$printSlice = 'T-P2-17';
$printPage = 'print_multiple_ticket.php';
$printFlag = 'print_multiple_ticket';
$printKind = 'multiple';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
