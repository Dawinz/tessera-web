<?php
/**
 * Cross-module switcher: Tickets | Cargo | Accounts (entitlement-aware).
 */
if (!isset($sr_active_module)) {
	$sr_active_module = 'cargo';
}

require_once __DIR__ . '/sr_feather_icons.php';
if (!function_exists('tessera_module_url')) {
	require_once __DIR__ . '/sr_env.php';
}
if (!function_exists('tessera_tenant_has_module')) {
	require_once __DIR__ . '/tessera_tenant.php';
}

$sr_modules_all = [
	'tickets' => [
		'label' => 'Tickets',
		'icon' => 'file-text',
		'href' => tessera_module_url('tickets'),
	],
	'cargo' => [
		'label' => 'Cargo',
		'icon' => 'briefcase',
		'href' => tessera_module_url('cargo', 'home.php'),
	],
	'accounts' => [
		'label' => 'Accounts',
		'icon' => 'dollar-sign',
		'href' => tessera_module_url('accounts', 'manifest.php'),
	],
];

$sr_modules = [];
foreach ($sr_modules_all as $key => $mod) {
	if (tessera_tenant_has_module($key)) {
		$sr_modules[$key] = $mod;
	}
}
?>
<nav class="sr-modules-center" aria-label="Platform modules" data-tour="modules">
	<ul class="navbar-nav sr-modules-inline flex-row align-items-center">
		<?php foreach ($sr_modules as $key => $mod): ?>
			<li class="nav-item">
				<a class="nav-link sr-module-link<?php echo $sr_active_module === $key ? ' is-active active' : ''; ?>"
					data-tour="module-<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>"
					href="<?php echo htmlspecialchars($mod['href'], ENT_QUOTES, 'UTF-8'); ?>">
					<?php echo sr_feather_icon($mod['icon']); ?>
					<span><?php echo htmlspecialchars($mod['label'], ENT_QUOTES, 'UTF-8'); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
