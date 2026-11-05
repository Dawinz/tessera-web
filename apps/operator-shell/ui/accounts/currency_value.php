<?php
/**
 * A-P1-10 modern Accounts exchange rate (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Exchange rate';
$setupLead = 'How much of the second currency you get for one of the first.';
$setupRoot = 'tds-accounts-exchange-root';
$setupSlice = 'A-P1-10';
$setupPage = 'currency_value.php';
$setupFlag = 'currency_value';
$setupCrumb = 'Currency';
$setupScreen = 'exchange';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=currency.php', 'label' => 'Currency'],
	['href' => '/operator/app.php?module=accounts&page=route_currency.php', 'label' => 'Route currency'],
];
require __DIR__ . '/_setup_page.php';
