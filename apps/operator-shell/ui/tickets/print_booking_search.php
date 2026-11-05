<?php
declare(strict_types=1);
$printTitle = 'Print booking search';
$printLead = 'Tickets that match a search.';
$printRoot = 'tds-tk-print-search';
$printSlice = 'T-P2-15';
$printPage = 'print_booking_search.php';
$printFlag = 'print_booking_search';
$printKind = 'booking_search';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
