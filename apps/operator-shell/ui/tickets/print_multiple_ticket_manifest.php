<?php
declare(strict_types=1);
$printTitle = 'Print multiple from manifest';
$printLead = 'Every paid passenger on the trip.';
$printRoot = 'tds-tk-print-manifest-tickets';
$printSlice = 'T-P2-18';
$printPage = 'print_multiple_ticket_manifest.php';
$printFlag = 'print_multiple_ticket_manifest';
$printKind = 'multiple_manifest';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
