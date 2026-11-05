<?php
/**
 * A-P1-16 modern Accounts vehicle category particulars (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Vehicle category particulars';
$setupLead = 'Vehicles that belong to a category. The list is stored with the category.';
$setupRoot = 'tds-accounts-vehicle-particulars-root';
$setupSlice = 'A-P1-16';
$setupPage = 'vehicle_category_details.php';
$setupFlag = 'vehicle_category_details';
$setupCrumb = 'Setup';
$setupScreen = 'vehicle-particulars';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=vehicle_category.php', 'label' => 'Vehicle categories'],
];
require __DIR__ . '/_setup_page.php';
