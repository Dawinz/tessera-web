<?php
declare(strict_types=1);
$printTitle = 'Print A4';
$printLead = 'Ticket laid out for an A4 page.';
$printRoot = 'tds-tk-print-a4';
$printSlice = 'T-P2-13';
$printPage = 'printa4.php';
$printFlag = 'printa4';
$printKind = 'a4';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
