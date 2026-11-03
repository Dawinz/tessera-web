<?php
/**
 * Legacy URL - unified Vehicle manager.
 */
$query = $_SERVER['QUERY_STRING'] ?? '';
$target = 'vehicle_manager.php' . ($query !== '' ? '?' . $query : '');
header('Location: ' . $target, true, 302);
exit;
