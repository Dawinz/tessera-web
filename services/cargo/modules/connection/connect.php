<?php
require_once dirname(__DIR__, 2) . '/includes/sr_env.php';
try {
	$pdo = sr_pdo(getenv('SR_CARGO_DB') ?: 'cargo');
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
}
