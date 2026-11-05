<?php
declare(strict_types=1);
$printTitle = 'Passenger manifest';
$printLead = 'Seat, phone, fare, and status for this trip.';
$printRoot = 'tds-tk-manifest-whole2';
$printSlice = 'T-P2-04';
$printPage = 'passenger_manifest_whole2.php';
$printFlag = 'passenger_manifest_whole2';
$printKind = 'manifest_whole2';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = true;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
