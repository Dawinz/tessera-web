<?php
require_once __DIR__ . '/bootstrap.php';

if (empty($_SESSION['control_email'])) {
	header('Location: index.php');
	exit;
}

$control_user = [
	'email' => (string) $_SESSION['control_email'],
	'name' => (string) ($_SESSION['control_name'] ?? 'Platform owner'),
	'role' => (string) ($_SESSION['control_role'] ?? 'owner'),
	'id' => (int) ($_SESSION['control_admin_id'] ?? 0),
];

/* Throttled alert scan on authenticated pages */
control_alerts_scan($db ?? null, !empty($control_db_ok), false);
