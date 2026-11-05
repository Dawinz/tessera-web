<?php
/**
 * A-P1-11 modern Accounts route currency (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Route currency';
$setupLead = 'Which currency a route collects, for the main leg or a sub leg.';
$setupRoot = 'tds-accounts-route-currency-root';
$setupSlice = 'A-P1-11';
$setupPage = 'route_currency.php';
$setupFlag = 'route_currency';
$setupCrumb = 'Currency';
$setupScreen = 'route-currency';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=currency.php', 'label' => 'Currency'],
	['href' => '/operator/app.php?module=accounts&page=currency_value.php', 'label' => 'Exchange rate'],
];
require __DIR__ . '/_setup_page.php';
