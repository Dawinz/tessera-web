<?php
/**
 * A-P1-07 modern Accounts sub routes (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Sub routes';
$setupLead = 'Attach an existing Accounts route under a main route.';
$setupRoot = 'tds-accounts-subroutes-root';
$setupSlice = 'A-P1-07';
$setupPage = 'Register_sub_route.php';
$setupFlag = 'register_sub_route';
$setupCrumb = 'Setup';
$setupScreen = 'subroutes';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=Register_routes.php', 'label' => 'Routes'],
	['href' => '/operator/app.php?module=accounts&page=assign_route.php', 'label' => 'Assign route'],
];
require __DIR__ . '/_setup_page.php';
