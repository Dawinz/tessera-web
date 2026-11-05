<?php
declare(strict_types=1);
$printTitle = 'Luggage tickets';
$printLead = 'Several luggage receipts. The classic filename keeps the original spelling.';
$printRoot = 'tds-cg-print-multiple';
$printSlice = 'C-P2-02';
$printPage = 'print_ticket_mulitple.php';
$printFlag = 'print_ticket_mulitple';
$printKind = 'multiple';
$printModule = 'cargo';
$printCrumb = 'Prints';
$printApi = '/cargo/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
