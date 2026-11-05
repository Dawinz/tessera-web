<?php
declare(strict_types=1);
$printTitle = 'Passenger seat chart';
$printLead = 'Free and taken seats for this trip.';
$printRoot = 'tds-tk-seat-chart';
$printSlice = 'T-P2-09';
$printPage = 'passenger_seat_chart.php';
$printFlag = 'passenger_seat_chart';
$printKind = 'seat_chart';
$printModule = 'tickets';
$printCrumb = 'Prints';
$printApi = '/tickets/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
