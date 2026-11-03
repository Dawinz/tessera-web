<?php
/**
 * Tessera Control application-states boot.
 */
if (!empty($GLOBALS['tessera_states_booted'])) {
	return;
}
$GLOBALS['tessera_states_booted'] = true;
$__ts_base = '/control/assets/tessera-states';
$__ts_ver = '20260906a';
require_once __DIR__ . '/tessera_states.php';
?>
<link rel="stylesheet" href="<?php echo htmlspecialchars($__ts_base . '/css/tessera-states.css?v=' . rawurlencode($__ts_ver), ENT_QUOTES, 'UTF-8'); ?>">
<script defer src="<?php echo htmlspecialchars($__ts_base . '/js/tessera-states.js?v=' . rawurlencode($__ts_ver), ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php
unset($__ts_base, $__ts_ver);
