<?php
require_once dirname(__DIR__, 2) . '/includes/sr_env.php';
try {
	$pdo_acc = sr_pdo(getenv('SR_ACCOUNT_DB') ?: 'account');
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
}
