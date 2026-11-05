<?php
/**
 * A-P1-03 modern Accounts job card entries (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Job card entries';
$setupLead = 'Every garage line across job cards. A line needs a job card for that date and route.';
$setupRoot = 'tds-accounts-entries-root';
$setupSlice = 'A-P1-03';
$setupPage = 'jobcard_entries.php';
$setupFlag = 'jobcard_entries';
$setupCrumb = 'Purchases';
$setupScreen = 'entries';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=jobcard.php', 'label' => 'Job cards'],
	['href' => '/operator/app.php?module=accounts&page=jobcard_detail.php', 'label' => 'Card detail'],
];
require __DIR__ . '/_setup_page.php';
