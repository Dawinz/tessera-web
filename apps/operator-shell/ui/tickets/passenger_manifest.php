<?php
declare(strict_types=1);
$printTitle = 'Passenger manifest';
$printLead = 'Paid passengers on this trip.';
$printRoot = 'tds-tk-manifest';
$printSlice = 'T-P2-02';
$printPage = 'passenger_manifest.php';
$printFlag = 'passenger_manifest';
$printKind = 'manifest';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
