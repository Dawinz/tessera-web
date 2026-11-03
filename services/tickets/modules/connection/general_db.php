<?php
/**
 * Import “general” DB - vehicle categories / shared reference.
 * Uses transport_general when present; otherwise falls back to ticket DB.
 */
require_once __DIR__ . '/connect.php';

if (!isset($pdo_general) || !($pdo_general instanceof PDO)) {
	$pdo_general = $pdo;
	try {
		$cfg = sr_db_config('ticket');
		$host = $cfg['host'];
		$port = $cfg['port'];
		$user = $cfg['user'];
		$pass = $cfg['pass'];
		$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, 'transport_general');
		$try = new PDO($dsn, $user, $pass, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		]);
		$chk = $try->query("SHOW TABLES LIKE 'vehcile_category'");
		if ($chk && $chk->fetchColumn()) {
			$pdo_general = $try;
		}
	} catch (Throwable $e) {
		$pdo_general = $pdo;
	}
}

// Local/dev fallback when transport_general is absent.
try {
	$chkLocal = $pdo->query("SHOW TABLES LIKE 'vehcile_category'");
	if (!$chkLocal || !$chkLocal->fetchColumn()) {
		$pdo->exec(
			'CREATE TABLE IF NOT EXISTS vehcile_category (
				id int(11) NOT NULL AUTO_INCREMENT,
				name varchar(40) DEFAULT NULL,
				rating float NOT NULL DEFAULT 0,
				detail text DEFAULT NULL,
				partiuclar text DEFAULT NULL,
				PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
		);
		$pdo->exec(
			"INSERT IGNORE INTO vehcile_category (id, name, rating) VALUES
			(1,'Luxury',5),(2,'Semi Luxury',4),(3,'Standard',3)"
		);
	}
} catch (Throwable $e) {
	// non-fatal
}
