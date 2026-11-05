<?php
/**
 * Tessera operator shell - one Dashonic document for Tickets, Cargo, and Accounts.
 */
require_once __DIR__ . '/includes/shell_bootstrap.php';

$module = isset($_GET['module']) ? strtolower((string) $_GET['module']) : 'tickets';
if (!in_array($module, ['tickets', 'cargo', 'accounts'], true)) {
	$module = 'tickets';
}

$page = isset($_GET['page']) ? (string) $_GET['page'] : '';
if ($page === '') {
	$page = tessera_operator_spa_default_page($module);
}

$initialQuery = $_GET;
unset($initialQuery['module'], $initialQuery['page']);

try {
	$initial = tessera_operator_spa_capture($module, $page, $initialQuery);
} catch (Throwable $e) {
	$module = 'tickets';
	$page = 'home.php';
	$initial = tessera_operator_spa_capture($module, $page, []);
}

$sr_active_module = $module;
$sr_topnav_html = $initial['topnav_html'] ?? '';
$__rawTitle = trim((string) $initial['title']);
/* SPA fragments may already include "Page - Module - Operator | Tessera" - keep only the page label. */
if ($__rawTitle !== '') {
	$__titleParts = preg_split('/\s+-\s+/', $__rawTitle, 2);
	$__page_title = trim((string) ($__titleParts[0] ?? $__rawTitle));
} else {
	$__page_title = ucfirst($module);
}
$tessera_page_module = $module === 'accounts' ? 'Accounts' : ($module === 'cargo' ? 'Cargo' : 'Tickets');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base id="sr-spa-base" href="<?php echo htmlspecialchars('/' . ($module === 'accounts' ? 'accounts' : $module) . '/', ENT_QUOTES, 'UTF-8'); ?>">
	<?php
	require_once __DIR__ . '/includes/shell_paths.php';
	$__roots = tessera_operator_module_roots();
	$dashonicHead = $__roots['accounts'] . '/template/includes/dashonic_head.php';
	include $dashonicHead;
	?>
	<link href="/operator/css/tessera-design-tokens.css?v=<?php echo @filemtime(__DIR__ . '/css/tessera-design-tokens.css') ?: time(); ?>" rel="stylesheet">
	<link href="/operator/css/tessera-design-components.css?v=<?php echo @filemtime(__DIR__ . '/css/tessera-design-components.css') ?: time(); ?>" rel="stylesheet">
	<link href="/operator/css/tessera-operator-shell.css?v=<?php echo @filemtime(__DIR__ . '/css/tessera-operator-shell.css') ?: time(); ?>" rel="stylesheet">
	<?php if ($module === 'tickets' || $module === 'cargo'): ?>
	<link href="/tickets/vendors/select2/select2.min.css" rel="stylesheet">
	<link href="/tickets/vendors/jquery-ui/themes/smoothness/jquery-ui.min.css" rel="stylesheet">
	<script src="/tickets/vendors/jquery-ui/jquery-ui.min.js"></script>
	<script src="/tickets/vendors/select2/select2.min.js"></script>
	<script src="/tickets/assets/libs/apexcharts/apexcharts.min.js"></script>
	<?php endif; ?>
	<?php
	/* Page CSS must be real <link>/<style> in <head> - never nested inside <style>. */
	$__spaStyles = (string) ($initial['styles'] ?? '');
	if ($__spaStyles !== '') {
		echo preg_replace('/<(link|style)\b/i', '<$1 data-sr-spa-style="1"', $__spaStyles);
	}
	?>
</head>
<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark" class="sr-platform-body sr-operator-shell" data-sr-module="<?php echo htmlspecialchars($module, ENT_QUOTES, 'UTF-8'); ?>">

<?php include __DIR__ . '/includes/shell_header.php'; ?>

<div class="main-content" id="sr-spa-main">
	<?php /* Fragments already include .page-content - do not wrap again (double padding / empty gap). */ ?>
	<div id="sr-spa-viewport" data-initial-module="<?php echo htmlspecialchars($module, ENT_QUOTES, 'UTF-8'); ?>" data-initial-path="<?php echo htmlspecialchars($initial['path'], ENT_QUOTES, 'UTF-8'); ?>">
		<?php echo $initial['html']; ?>
	</div>
</div>

<div id="sr-spa-mount" aria-hidden="true"></div>

<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<script id="sr-spa-bootstrap" type="application/json"><?php
echo json_encode(
	[
		'initial' => $initial,
		'module' => $module,
		'spaUrl' => '/operator/spa.php',
	],
	JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP
);
?></script>
<script>
window.__TESSERA_OPERATOR_SHELL__ = JSON.parse(document.getElementById('sr-spa-bootstrap').textContent);
</script>
<script src="/operator/js/tessera-operator-shell.js?v=<?php echo @filemtime(__DIR__ . '/js/tessera-operator-shell.js') ?: time(); ?>"></script>
</body>
</html>
