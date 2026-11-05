<?php
/**
 * Fold: Cargo profile opens Settings profile.
 */
declare(strict_types=1);
$hub = [
	'id' => 'tds-fold-cargo-profile',
	'slice' => 'FOLD-cargo-profile',
	'title' => 'Profile',
	'crumb' => 'Cargo',
	'section' => 'Settings',
	'lead' => 'Your profile is in Settings, shared with Tickets and Accounts.',
	'paragraphs' => ['Name, phone, password, and photo are edited once on the profile page.'],
	'base' => '/cargo/',
	'move' => 'profile',
	'links' => [
		['href' => '/settings/profile.php', 'label' => 'Open profile', 'primary' => true, 'id' => 'tds-fold-cargo-profile-go'],
	],
];
require dirname(__DIR__) . '/_hub.php';
