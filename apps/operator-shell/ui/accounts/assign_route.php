<?php
/**
 * A-P1-17 modern Accounts assign route (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Assign route';
$setupLead = 'Choose a staff member, then tick the Accounts routes they can work.';
$setupRoot = 'tds-accounts-assign-root';
$setupSlice = 'A-P1-17';
$setupPage = 'assign_route.php';
$setupFlag = 'assign_route';
$setupCrumb = 'Setup';
$setupScripts = ['assign_route.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=Register_routes.php', 'label' => 'Routes'],
	['href' => '/operator/app.php?module=accounts&page=Register_sub_route.php', 'label' => 'Sub routes'],
];
require __DIR__ . '/_setup_page.php';
