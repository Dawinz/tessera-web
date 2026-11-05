<?php
/**
 * A-P1-14 modern Accounts particular single (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Particular single';
$setupLead = 'Ledger lines posted against one particular, with an optional date range and currency.';
$setupRoot = 'tds-accounts-particular-single-root';
$setupSlice = 'A-P1-14';
$setupPage = 'particular_single_page.php';
$setupFlag = 'particular_single_page';
$setupCrumb = 'Setup';
$setupScripts = ['particular_single.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=particular_detail.php', 'label' => 'Particulars'],
	['href' => '/operator/app.php?module=accounts&page=particular.php', 'label' => 'Categories'],
];
$particular = isset($_GET['particular']) ? (string) $_GET['particular'] : '';
$setupAttrs = ' data-particular="' . htmlspecialchars($particular, ENT_QUOTES, 'UTF-8') . '"';
require __DIR__ . '/_setup_page.php';
