<?php
/**
 * A-P1-08 modern Accounts constants (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Constants';
$setupLead = 'Standing amounts for a route, such as border fees or allowances.';
$setupRoot = 'tds-accounts-constants-root';
$setupSlice = 'A-P1-08';
$setupPage = 'constants.php';
$setupFlag = 'constants';
$setupCrumb = 'Setup';
$setupScreen = 'constants';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=particular.php', 'label' => 'Categories'],
	['href' => '/operator/app.php?module=accounts&page=Register_routes.php', 'label' => 'Routes'],
];
require __DIR__ . '/_setup_page.php';
