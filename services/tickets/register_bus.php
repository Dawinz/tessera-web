<?php
/**
 * Legacy URL - unified Vehicle manager.
 */
if (!empty($GLOBALS['tessera_operator_spa_embed'])) {
	require __DIR__ . '/vehicle_manager.php';
	return;
}
$query = $_SERVER['QUERY_STRING'] ?? '';
$target = 'vehicle_manager.php' . ($query !== '' ? '?' . $query : '');
header('Location: ' . $target, true, 302);
exit;
