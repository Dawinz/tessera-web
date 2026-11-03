<?php
/**
 * Fixed operator chrome - ops nav is hydrated from SPA fragments.
 *
 * Expects: $sr_active_module, $sr_topnav_html (optional initial HTML)
 */
if (!isset($sr_active_module)) {
	$sr_active_module = 'tickets';
}
$sr_topnav_html = $sr_topnav_html ?? '';

require_once __DIR__ . '/shell_paths.php';
$__roots = tessera_operator_module_roots();
$ticketsRoot = $__roots['tickets'];
require_once $ticketsRoot . '/includes/platform_chrome_head.php';

$tessera_tour_module = $sr_active_module;
$tessera_tour_role_label = $level ?? 'Staff';
include $ticketsRoot . '/includes/tessera_tour_boot.php';
$tessera_states_module = $sr_active_module;
include $ticketsRoot . '/includes/tessera_states_boot.php';

echo tessera_brand_css_vars();
echo tessera_support_banner_html();

$sr_profile_home = '/' . ($sr_active_module === 'accounts' ? 'accounts' : $sr_active_module) . '/profile.php?u=' . urlencode($username);
$sr_logout_href = '/' . ($sr_active_module === 'accounts' ? 'accounts' : $sr_active_module) . '/log_out.php';

/* Chrome assets stay on Tickets paths - not affected by per-module <base> for viewport. */
$sr_avatar_src = '/tickets/assets/images/users/user.png';
$img = isset($image) ? trim((string) $image) : '';
if ($img !== '' && $img !== 'user.png') {
	$candidates = [
		[$ticketsRoot . '/uploads/' . $img, '/tickets/uploads/' . $img],
		[$ticketsRoot . '/img/faces/' . $img, '/tickets/img/faces/' . $img],
	];
	foreach ($candidates as $pair) {
		if (is_file($pair[0])) {
			$sr_avatar_src = $pair[1];
			break;
		}
	}
}
?>
<script>
	document.body && document.body.classList.add('sr-platform-body');
	document.addEventListener('DOMContentLoaded', function () {
		document.body.classList.add('sr-platform-body');
	});
</script>
<header id="page-topbar">
	<div class="navbar-header">
		<div class="d-flex">
			<div class="navbar-brand-box">
				<?php echo tessera_brand_markup('/operator/app.php'); ?>
			</div>
			<button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item sr-topnav-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content" aria-label="Toggle navigation" aria-controls="topnav-menu-content" aria-expanded="false">
				<i class="bx bx-menu"></i>
			</button>
		</div>
		<?php include $ticketsRoot . '/includes/platform_modules.php'; ?>
		<?php include $ticketsRoot . '/includes/platform_header_actions.php'; ?>
	</div>
	<div class="topnav" data-tour="topnav">
		<div class="container-fluid">
			<nav class="navbar navbar-light navbar-expand-lg topnav-menu">
				<div class="collapse navbar-collapse" id="topnav-menu-content">
					<?php echo $sr_topnav_html; ?>
				</div>
			</nav>
		</div>
	</div>
</header>
