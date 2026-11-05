<?php
declare(strict_types=1);
$printTitle = 'Category vehicle list';
$printLead = 'Particulars for one vehicle inside a category.';
$printRoot = 'tds-acc-print-vehicles';
$printSlice = 'A-P2-02';
$printPage = 'category_vehicle_list.php';
$printFlag = 'category_vehicle_list';
$printKind = 'vehicles';
$printModule = 'accounts';
$printCrumb = 'Drill-downs';
$printApi = '/accounts/api/console/print_drill.php';
$printScript = '/operator/ui/prints.js';
$printSelectable = false;
$printMode = '';
require __DIR__ . '/../_print_shell.php';
