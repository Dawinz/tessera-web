<?php
declare(strict_types=1);
$printTitle = 'Physical booking';
$printLead = 'Build a hire sheet from a trip and print it at the counter.';
$printRoot = 'tds-tk-physical';
$printSlice = 'T-P2-01';
$printPage = 'physical_booking.php';
$printFlag = 'physical_booking';
$printKind = 'physical';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/tickets/physical_booking.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
