<?php
/**
 * Fold: Tickets profile, company, and roles open Settings.
 */
declare(strict_types=1);

$screen = strtolower((string) ($GLOBALS['tessera_leftover_screen'] ?? 'profile'));
$pages = [
	'company_info' => [
		'title' => 'Company',
		'lead' => 'Company name, logo, and brand live in Settings.',
		'body' => 'Open company settings to change the name staff and passengers see.',
		'href' => '/settings/company.php',
		'label' => 'Open company',
	],
	'roles' => [
		'title' => 'Roles',
		'lead' => 'Menu access is set on the staff page.',
		'body' => 'Pick a person, then choose which menus they can open.',
		'href' => '/settings/staff.php?tab=menus',
		'label' => 'Open staff menus',
	],
];
$spec = $pages[$screen] ?? [
	'title' => 'Profile',
	'lead' => 'Your name, password, and photo are on the Settings profile.',
	'body' => 'Tickets profile and user profile both open that page.',
	'href' => '/settings/profile.php',
	'label' => 'Open profile',
];
$hub = [
	'id' => 'tds-fold-settings',
	'slice' => 'FOLD-settings',
	'title' => $spec['title'],
	'crumb' => 'Tickets',
	'section' => 'Settings',
	'lead' => $spec['lead'],
	'move' => $screen,
	'paragraphs' => [$spec['body']],
	'base' => '/tickets/',
	'links' => [
		['href' => $spec['href'], 'label' => $spec['label'], 'primary' => true, 'id' => 'tds-fold-settings-go'],
	],
];
require dirname(__DIR__) . '/_hub.php';
