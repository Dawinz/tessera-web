<?php
/**
 * A-P1-01 modern Accounts job cards (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Job cards';
$setupLead = 'Open a purchase day for a route, then add garage lines on the detail screen.';
$setupRoot = 'tds-accounts-jobcard-root';
$setupSlice = 'A-P1-01';
$setupPage = 'jobcard.php';
$setupFlag = 'jobcard';
$setupCrumb = 'Purchases';
$setupScreen = 'jobcard';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=jobcard_detail.php', 'label' => 'Card detail'],
	['href' => '/operator/app.php?module=accounts&page=jobcard_entries.php', 'label' => 'All lines'],
];
require __DIR__ . '/_setup_page.php';
