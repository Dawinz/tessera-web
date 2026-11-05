<?php
declare(strict_types=1);
$printTitle = 'Vehicle transfer list';
$printLead = 'Vehicles on this route and how many seats are free.';
$printRoot = 'tds-tk-vehicle-transfer';
$printSlice = 'T-P2-10';
$printPage = 'vehicle_transfer_list.php';
$printFlag = 'vehicle_transfer_list';
$printKind = 'vehicles';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
