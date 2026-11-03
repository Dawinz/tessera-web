<?php
require_once __DIR__ . '/includes/sr_env.php';
$db = sr_mysqli(getenv('SR_CARGO_DB') ?: 'cargo');
if (!$db) {
	die('Connection Failed');
}
