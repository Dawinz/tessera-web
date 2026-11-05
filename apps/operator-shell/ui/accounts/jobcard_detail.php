<?php
/**
 * A-P1-02 modern Accounts job card detail (TDS).
 */
declare(strict_types=1);

$setupTitle = 'Job card detail';
$setupLead = 'Pick a job card, then record the vehicle, item, quantity, and price.';
$setupRoot = 'tds-accounts-jobcard-detail-root';
$setupSlice = 'A-P1-02';
$setupPage = 'jobcard_detail.php';
$setupFlag = 'jobcard_detail';
$setupCrumb = 'Purchases';
$setupScripts = ['jobcard_detail.js'];
$setupLinks = [
	['href' => '/operator/app.php?module=accounts&page=jobcard.php', 'label' => 'Job cards'],
	['href' => '/operator/app.php?module=accounts&page=jobcard_entries.php', 'label' => 'All lines'],
];
$cardId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$setupAttrs = ' data-card-id="' . $cardId . '"';
require __DIR__ . '/_setup_page.php';
