<?php
/**
 * Boot TesseraStates CSS/JS on operator pages.
 *
 * Optional before include:
 *   $tessera_states_module     tickets|cargo|accounts|control
 *   $tessera_states_asset_base URL prefix (defaults below)
 *   $tessera_states_version    cache-buster string
 */
if (!empty($GLOBALS['tessera_states_booted'])) {
	return;
}
$GLOBALS['tessera_states_booted'] = true;

$__ts_module = isset($tessera_states_module)
	? (string) $tessera_states_module
	: (isset($tessera_tour_module) ? (string) $tessera_tour_module : 'tickets');

if (!isset($tessera_states_asset_base)) {
	if ($__ts_module === 'cargo') {
		$tessera_states_asset_base = '/cargo/assets/tessera-states';
	} elseif ($__ts_module === 'control') {
		$tessera_states_asset_base = '/control/assets/tessera-states';
	} else {
		/* Accounts reuses Tickets static assets (same as Dashonic / tours). */
		$tessera_states_asset_base = '/tickets/assets/tessera-states';
	}
}

$__ts_base = rtrim((string) $tessera_states_asset_base, '/');
$__ts_ver = isset($tessera_states_version) ? (string) $tessera_states_version : '20261027tbl';
$__ts_css = htmlspecialchars($__ts_base . '/css/tessera-states.css?v=' . rawurlencode($__ts_ver), ENT_QUOTES, 'UTF-8');
$__ts_js = htmlspecialchars($__ts_base . '/js/tessera-states.js?v=' . rawurlencode($__ts_ver), ENT_QUOTES, 'UTF-8');

$__ts_helpers = __DIR__ . '/tessera_states.php';
if (is_file($__ts_helpers)) {
	require_once $__ts_helpers;
}
?>
<link rel="stylesheet" href="<?php echo $__ts_css; ?>">
<script defer src="<?php echo $__ts_js; ?>"></script>
<?php
unset($__ts_module, $__ts_base, $__ts_ver, $__ts_css, $__ts_js, $__ts_helpers);
