<?php
/**
 * A-P1-13 modern Accounts particular detail (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Particular detail';
$setupLead = 'Named lines inside a category, and whether they are already paid.';
$setupRoot = 'tds-accounts-particular-root';
$setupSlice = 'A-P1-13';
$setupPage = 'particular_detail.php';
$setupFlag = 'particular_detail';
$setupCrumb = 'Setup';
$setupScreen = 'particular-detail';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=particular.php', 'label' => 'Categories'],
	['href' => '/operator/app.php?module=accounts&page=particular_single_page.php', 'label' => 'One particular'],
];
require __DIR__ . '/_setup_page.php';
