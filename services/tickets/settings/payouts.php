<?php
$map = [
	'payouts.php' => '../settings_payouts.php',
	'notifications.php' => '../settings_notifications.php',
	'staff.php' => '../settings_staff.php',
	'api.php' => '../settings_api.php',
	'webhooks.php' => '../settings_webhooks.php',
];
$base = basename($_SERVER['SCRIPT_NAME'] ?? '');
header('Location: ' . ($map[$base] ?? '../settings.php'), true, 302);
exit;
