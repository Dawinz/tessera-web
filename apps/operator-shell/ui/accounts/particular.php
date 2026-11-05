<?php
/**
 * A-P1-12 modern Accounts particular categories (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Particular categories';
$setupLead = 'Groups for income and expense lines. Add the lines themselves on Particular detail.';
$setupRoot = 'tds-accounts-categories-root';
$setupSlice = 'A-P1-12';
$setupPage = 'particular.php';
$setupFlag = 'particular';
$setupCrumb = 'Setup';
$setupScreen = 'categories';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=particular_detail.php', 'label' => 'Particulars'],
	['href' => '/operator/app.php?module=accounts&page=item.php', 'label' => 'Items'],
];
require __DIR__ . '/_setup_page.php';
