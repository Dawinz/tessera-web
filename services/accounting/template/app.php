<?php
/**
 * Legacy Accounts shell entry - forwards to platform operator shell.
 */
require_once dirname(__DIR__, 2) . '/tickets/includes/tessera_operator_spa.php';

$page = isset($_GET['page']) ? (string) $_GET['page'] : 'manifest.php';
$query = $_GET;
unset($query['page']);
$url = tessera_operator_shell_url('accounts', $page);
if ($query !== []) {
	$url .= '&' . http_build_query($query);
}
header('Location: ' . $url, true, 302);
exit;
