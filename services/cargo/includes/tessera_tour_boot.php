<?php
/**
 * Boot TesseraTour (driver.js) on operator pages.
 *
 * Set before include (optional):
 *   $tessera_tour_module      tickets|cargo|accounts
 *   $tessera_tour_role_code   owner|manager|tickets_agent|cargo_agent|accountant
 *   $tessera_tour_role_label  display string
 *   $tessera_tour_asset_base  URL prefix (defaults below)
 */
if (!function_exists('tessera_tour_nav_attr')) {
	/** data-tour attribute for a nav href (best-effort slug). */
	function tessera_tour_nav_attr(string $link): string {
		$path = parse_url($link, PHP_URL_PATH);
		$base = strtolower(basename($path !== null && $path !== '' ? $path : $link));
		$base = preg_replace('/\?.*$/', '', $base) ?: '';
		$key = preg_replace('/\.php$/i', '', $base) ?: '';
		$aliases = [
			'routes' => 'nav-routes',
			'routs' => 'nav-routes',
			'home' => 'nav-home',
			'manifest' => 'nav-manifest',
			'register' => 'nav-register',
			'all_luggages' => 'nav-all',
			'all_luggage' => 'nav-all',
			'vehicle_report' => 'nav-vehicle',
			'vehicle_report_new' => 'nav-vehicle',
			'expenses' => 'nav-expenses',
			'office_expenses' => 'nav-expenses',
			'daily' => 'nav-expenses',
		];
		$attr = $aliases[$key] ?? ('nav-' . preg_replace('/[^a-z0-9]+/', '-', $key));
		return ' data-tour="' . htmlspecialchars($attr, ENT_QUOTES, 'UTF-8') . '"';
	}
}

if (!empty($GLOBALS['tessera_tour_booted'])) {
	return;
}
$GLOBALS['tessera_tour_booted'] = true;

$__tt_module = isset($tessera_tour_module) ? (string) $tessera_tour_module : 'tickets';
$__tt_label = isset($tessera_tour_role_label)
	? (string) $tessera_tour_role_label
	: (isset($level) ? (string) $level : 'Staff');
$__tt_role = isset($tessera_tour_role_code) ? (string) $tessera_tour_role_code : '';

$__tt_rbac = __DIR__ . '/tessera_rbac.php';
if (is_file($__tt_rbac)) {
	require_once $__tt_rbac;
}

if ($__tt_role === '' && isset($db) && !empty($username) && $db instanceof mysqli && function_exists('tessera_user_role_code')) {
	$__tt_role = tessera_user_role_code($db, (string) $username);
}

if ($__tt_role === '') {
	if (function_exists('tessera_map_legacy_role')) {
		$__tt_role = tessera_map_legacy_role($__tt_label);
	} else {
		$l = strtolower($__tt_label);
		if (str_contains($l, 'super')) {
			$__tt_role = 'owner';
		} elseif (str_contains($l, 'admin')) {
			$__tt_role = 'manager';
		} elseif (str_contains($l, 'account')) {
			$__tt_role = 'accountant';
		} elseif (str_contains($l, 'cargo')) {
			$__tt_role = 'cargo_agent';
		} else {
			$__tt_role = $__tt_module === 'cargo' ? 'cargo_agent' : ($__tt_module === 'accounts' ? 'accountant' : 'tickets_agent');
		}
	}
}

if (!isset($tessera_tour_asset_base)) {
	/* Accounts reuses Tickets static assets (same as Dashonic head). */
	if ($__tt_module === 'cargo') {
		$tessera_tour_asset_base = '/cargo/assets/tessera-tour';
	} else {
		$tessera_tour_asset_base = '/tickets/assets/tessera-tour';
	}
}
$__tt_base = rtrim((string) $tessera_tour_asset_base, '/');
$__tt_pack = preg_replace('/[^a-z]/', '', $__tt_module) ?: 'tickets';
$__tt_cfg = [
	'module' => $__tt_module,
	'role' => $__tt_role,
	'roleLabel' => $__tt_label,
	'assetBase' => $__tt_base,
	'autoStart' => true,
];
?>
<link rel="stylesheet" href="<?php echo htmlspecialchars($__tt_base . '/vendor/driver.js/driver.css', ENT_QUOTES, 'UTF-8'); ?>">
<link rel="stylesheet" href="<?php echo htmlspecialchars($__tt_base . '/css/tessera-tour.css', ENT_QUOTES, 'UTF-8'); ?>">
<script>window.TesseraTourConfig = <?php echo json_encode($__tt_cfg, JSON_UNESCAPED_SLASHES); ?>;</script>
<script src="<?php echo htmlspecialchars($__tt_base . '/vendor/driver.js/driver.js.iife.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<script src="<?php echo htmlspecialchars($__tt_base . '/js/tessera-tour.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<script src="<?php echo htmlspecialchars($__tt_base . '/js/tours/' . $__tt_pack . '.js', ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php
unset($__tt_module, $__tt_label, $__tt_role, $__tt_base, $__tt_cfg, $__tt_pack, $__tt_rbac);
