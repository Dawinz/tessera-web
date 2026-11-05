<?php
declare(strict_types=1);
$printTitle = 'Passenger manifest';
$printLead = 'Select passengers to transfer or print.';
$printRoot = 'tds-tk-manifest-whole';
$printSlice = 'T-P2-03';
$printPage = 'passenger_manifest_whole.php';
$printFlag = 'passenger_manifest_whole';
$printKind = 'manifest_whole';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = true;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
