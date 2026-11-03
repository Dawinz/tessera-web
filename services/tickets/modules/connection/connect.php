<?php
/**
 * Import module PDO - Tessera ticket DB + staff session (replaces legacy connect.php).
 */
require_once __DIR__ . '/../../includes/tessera_staff_boot.php';
require_once __DIR__ . '/../../includes/sr_env.php';

if (!isset($pdo) || !($pdo instanceof PDO)) {
	try {
		$pdo = sr_pdo('ticket');
	} catch (Throwable $e) {
		http_response_code(503);
		header('Content-Type: text/plain; charset=utf-8');
		echo 'Database unavailable';
		exit;
	}
}
