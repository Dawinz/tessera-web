<?php
/**
 * Tessera Control guided tours boot.
 */
if (!empty($GLOBALS['tessera_tour_booted'])) {
	return;
}
$GLOBALS['tessera_tour_booted'] = true;
$__tt_cfg = [
	'module' => 'control',
	'role' => 'owner',
	'roleLabel' => $control_name ?? 'Platform owner',
	'assetBase' => '/control/assets/tessera-tour',
	'autoStart' => true,
];
$__tt_base = '/control/assets/tessera-tour';
?>
<link rel="stylesheet" href="<?php echo htmlspecialchars($__tt_base . '/vendor/driver.js/driver.css', ENT_QUOTES, 'UTF-8'); ?>">
<link rel="stylesheet" href="<?php echo htmlspecialchars($__tt_base . '/css/tessera-tour.css', ENT_QUOTES, 'UTF-8'); ?>">
<script>window.TesseraTourConfig = <?php echo json_encode($__tt_cfg, JSON_UNESCAPED_SLASHES); ?>;</script>
<script src="<?php echo htmlspecialchars($__tt_base . '/vendor/driver.js/driver.js.iife.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<script src="<?php echo htmlspecialchars($__tt_base . '/js/tessera-tour.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<script src="<?php echo htmlspecialchars($__tt_base . '/js/tours/control.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php
unset($__tt_cfg, $__tt_base);
