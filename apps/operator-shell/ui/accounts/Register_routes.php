<?php
/**
 * A-P1-06 modern Accounts routes (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Routes';
$setupLead = 'Bring a Cargo route into Accounts. A main route also opens the return leg.';
$setupRoot = 'tds-accounts-routes-root';
$setupSlice = 'A-P1-06';
$setupPage = 'Register_routes.php';
$setupFlag = 'register_routes';
$setupCrumb = 'Setup';
$setupScreen = 'routes';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=Register_sub_route.php', 'label' => 'Sub routes'],
	['href' => '/operator/app.php?module=accounts&page=assign_route.php', 'label' => 'Assign route'],
];
require __DIR__ . '/_setup_page.php';
