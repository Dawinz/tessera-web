<?php
/**
 * A-P1-04 modern Accounts garage particular (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Garage particular';
$setupLead = 'Smaller items stored under a particular, such as a service or a spare.';
$setupRoot = 'tds-accounts-garage-root';
$setupSlice = 'A-P1-04';
$setupPage = 'garage_particular.php';
$setupFlag = 'garage_particular';
$setupCrumb = 'Setup';
$setupScreen = 'garage';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=item.php', 'label' => 'Items'],
	['href' => '/operator/app.php?module=accounts&page=particular.php', 'label' => 'Categories'],
];
require __DIR__ . '/_setup_page.php';
