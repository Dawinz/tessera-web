<?php
/**
 * A-P1-05 modern Accounts item (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Item';
$setupLead = 'Named lines staff pick on a job card or the ledger. Categories are managed separately.';
$setupRoot = 'tds-accounts-item-root';
$setupSlice = 'A-P1-05';
$setupPage = 'item.php';
$setupFlag = 'item';
$setupCrumb = 'Setup';
$setupScreen = 'item';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=particular.php', 'label' => 'Categories'],
	['href' => '/operator/app.php?module=accounts&page=garage_particular.php', 'label' => 'Garage items'],
];
require __DIR__ . '/_setup_page.php';
