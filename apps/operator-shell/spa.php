<?php
/**
 * Unified operator SPA fragment API - routes to module capture.
 */
require_once __DIR__ . '/includes/shell_bootstrap.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Tessera-Operator-Spa: 1');
/* Browser soft-cache handles revisits; keep CDN/proxy from sharing user HTML. */
header('Cache-Control: private, no-store');

$module = isset($_GET['module']) ? strtolower((string) $_GET['module']) : 'tickets';
if (!in_array($module, ['tickets', 'cargo', 'accounts'], true)) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid module.'], JSON_UNESCAPED_UNICODE);
	exit;
}

$page = isset($_GET['page']) ? (string) $_GET['page'] : tessera_operator_spa_default_page($module);
$query = $_GET;
unset($query['module'], $query['page'], $query['spa']);

/**
 * Short per-user fragment cache. Cuts repeat PHP include cost when the same
 * staff member reopens the same screen within ~45s (nav thrashing / back).
 * Skips pages with extra query args (filters / deep links stay live).
 */
$spaCacheFile = null;
$spaCacheTtl = 45;
if ($query === []) {
	$userKey = (string) ($_SESSION['username'] ?? '');
	$hostKey = (string) ($_SERVER['HTTP_HOST'] ?? '');
	if ($userKey !== '') {
		$cacheDir = rtrim(sys_get_temp_dir(), '/') . '/tessera_spa_cache';
		if (!is_dir($cacheDir)) {
			@mkdir($cacheDir, 0700, true);
		}
		$spaCacheFile = $cacheDir . '/' . hash(
			'sha256',
			'tessera_spa_v2|' . $hostKey . '|' . $userKey . '|' . $module . '|' . $page
		) . '.json';
		if (is_file($spaCacheFile) && (time() - (int) filemtime($spaCacheFile)) < $spaCacheTtl) {
			$cachedJson = @file_get_contents($spaCacheFile);
			if (is_string($cachedJson) && $cachedJson !== '') {
				header('X-Tessera-Spa-Cache: HIT');
				echo $cachedJson;
				exit;
			}
		}
	}
}

try {
	$payload = tessera_operator_spa_capture($module, $page, $query);
	$json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	if ($spaCacheFile !== null && is_string($json) && $json !== '') {
		@file_put_contents($spaCacheFile, $json, LOCK_EX);
		header('X-Tessera-Spa-Cache: MISS');
	}
	echo $json;
} catch (Throwable $e) {
	http_response_code(400);
	echo json_encode([
		'error' => 'Could not load page fragment.',
		'detail' => $e->getMessage(),
	], JSON_UNESCAPED_UNICODE);
}
