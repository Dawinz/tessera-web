<?php
/**
 * A-P1-15 modern Accounts vehicle category (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Vehicle category';
$setupLead = 'Groups such as buses and trucks. Attach the vehicles on the next screen.';
$setupRoot = 'tds-accounts-vehicle-category-root';
$setupSlice = 'A-P1-15';
$setupPage = 'vehicle_category.php';
$setupFlag = 'vehicle_category';
$setupCrumb = 'Setup';
$setupScreen = 'vehicle-category';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=vehicle_category_details.php', 'label' => 'Vehicles'],
];
require __DIR__ . '/_setup_page.php';
