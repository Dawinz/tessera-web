<?php
/**
 * A-P1-09 modern Accounts currency (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Currency';
$setupLead = 'Currencies used on routes, constants, and the ledger. The main currency stays as it is.';
$setupRoot = 'tds-accounts-currency-root';
$setupSlice = 'A-P1-09';
$setupPage = 'currency.php';
$setupFlag = 'currency';
$setupCrumb = 'Currency';
$setupScreen = 'currency';
$setupScripts = ['setup_crud.js', 'setup_screens.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=currency_value.php', 'label' => 'Exchange rate'],
	['href' => '/operator/app.php?module=accounts&page=route_currency.php', 'label' => 'Route currency'],
];
require __DIR__ . '/_setup_page.php';
