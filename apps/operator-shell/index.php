<?php
/**
 * Operator shell root - send staff to the SPA entry.
 */
$query = $_SERVER['QUERY_STRING'] ?? '';
$dest = '/operator/app.php' . ($query !== '' ? ('?' . $query) : '');
header('Location: ' . $dest, true, 302);
exit;
