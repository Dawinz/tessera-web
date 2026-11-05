<?php
declare(strict_types=1);
$printTitle = 'Cargo manifest';
$printLead = 'Paid and to-be amounts by uploader.';
$printRoot = 'tds-cg-print-manifest';
$printSlice = 'C-P2-03';
$printPage = 'print_manifest.php';
$printFlag = 'print_manifest';
$printKind = 'manifest';
$printModule = 'cargo';
$printCrumb = 'Prints';
$printApi = '/cargo/api/console/print_document.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
