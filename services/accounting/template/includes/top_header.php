<?php
/**
 * Accounts chrome header (Dashonic). Prefer auth_bootstrap.php before HTML.
 */
if (!isset($username) || $username === '') {
	require_once __DIR__ . '/auth_bootstrap.php';
}

$sr_ops_icon_map = [
	'manifest' => 'clipboard',
	'manifests' => 'clipboard',
	'cash book' => 'credit-card',
	'purchases' => 'shopping-cart',
	'financial reports' => 'trending-up',
	'fleet reports' => 'truck',
	'setup' => 'settings',
	'registration' => 'settings',
	'currency' => 'dollar-sign',
	'daily expenses' => 'credit-card',
	'withdraw' => 'trash-2',
	'report' => 'bar-chart-2',
];
?>
<?php
if (!function_exists('tessera_tenant_row')) {
	require_once __DIR__ . '/tessera_tenant.php';
}
tessera_abort_unknown_tenant();
tessera_require_module('accounts');
?>
<?php include __DIR__ . '/platform_chrome_head.php'; ?>
<?php
$tessera_tour_module = 'accounts';
$tessera_tour_role_label = $level ?? 'Staff';
include __DIR__ . '/tessera_tour_boot.php';
$tessera_states_module = 'accounts';
include __DIR__ . '/tessera_states_boot.php';
?>
<?php echo tessera_brand_css_vars(); ?>
<?php echo tessera_support_banner_html(); ?>
<script>
	document.documentElement.setAttribute('data-layout', 'horizontal');
	document.documentElement.setAttribute('data-topbar', 'dark');
	document.body && document.body.classList.add('sr-platform-body');
	document.addEventListener('DOMContentLoaded', function () {
		document.body.setAttribute('data-layout', 'horizontal');
		document.body.setAttribute('data-topbar', 'dark');
		document.body.classList.add('sr-platform-body');
	});
</script>

<header id="page-topbar" class="sr-accounts-chrome">
	<div class="navbar-header">
		<div class="d-flex">
			<div class="navbar-brand-box">
				<?php echo tessera_brand_markup('app.php'); ?>
			</div>
			<button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item sr-topnav-toggle" id="azMenuShow"
				data-bs-toggle="collapse" data-bs-target="#topnav-menu-content"
				aria-label="Toggle navigation" aria-controls="topnav-menu-content" aria-expanded="false">
				<i class="bx bx-menu"></i>
			</button>
		</div>

		<?php
		$sr_active_module = 'accounts';
		include __DIR__ . '/platform_modules.php';
		?>

		<?php
		$sr_avatar_src = null;
		$img = trim((string) $image);
		if ($img !== '') {
			$face = __DIR__ . '/..//accounts/img/faces/' . $img;
			if (is_file($face)) {
				$sr_avatar_src = '/accounts/img/faces/' . $img;
			}
		}
		if ($sr_avatar_src === null) {
			$sr_avatar_src = is_file(__DIR__ . '/..//accounts/img/faces/user.png')
				? '/accounts/img/faces/user.png'
				: '/tickets/assets/images/users/user.png';
		}
		$sr_logout_href = 'log_out.php';
		/* Profile lives in Tickets (shared staff identity); keep absolute so /accounts/ does not 404. */
		$sr_profile_home = '/tickets/profile.php?u=' . urlencode((string) $username);
		unset($sr_search_wide);
		include __DIR__ . '/platform_header_actions.php';
		?>
	</div>

	<div class="topnav" data-tour="topnav">
		<div class="container-fluid">
			<nav class="navbar navbar-light navbar-expand-lg topnav-menu">
				<?php $this_page = basename($_SERVER['PHP_SELF']); ?>
				<div class="collapse navbar-collapse" id="topnav-menu-content">
					<ul class="navbar-nav">
						<?php
						$levelEsc = mysqli_real_escape_string($db, (string) $level);
						$select2 = mysqli_query($db, "SELECT * FROM category WHERE category='$levelEsc' ORDER BY arrangement ASC ");
						$select_num2 = $select2 ? mysqli_num_rows($select2) : 0;
						if ($select_num2 != 0) {
							while ($get12 = mysqli_fetch_array($select2)) {
								$role_name = $get12['name'];
								$link = $get12['link'];

								/* Module pills already switch products — never show Tickets/Cargo leftovers here. */
								if (preg_match('/^(cargo|accounts?|tickets?)$/i', trim((string) $role_name))) {
									continue;
								}
								if (preg_match('#(?:^|/|\.\./)(?:cargo|tickets?|accounts?|sr_up|ticket)(/|$|\.php)#i', (string) $link)
									|| preg_match('#https?://localhost/#i', (string) $link)) {
									continue;
								}

								$catEsc = mysqli_real_escape_string($db, (string) $role_name);
								$userEsc = mysqli_real_escape_string($db, (string) $username);
								$select254 = mysqli_query($db, "SELECT * FROM roles_assigned where username='$userEsc' and category='$catEsc'");
								$select_num254 = $select254 ? mysqli_num_rows($select254) : 0;
								$is_dropdown = ($select_num254 > 1);

								if (isset($_GET['main_page'])) {
									$marker = ($role_name == $_GET['main_page']) ? "active" : "";
								} else {
									$marker = ($link == $this_page) ? "active" : "";
								}

								$icon_key = strtolower(trim($role_name));
								$db_icon = trim((string) ($get12['image'] ?? ''));
								if ($db_icon !== '' && stripos($db_icon, 'typcn') === false) {
									$feather_name = preg_replace('/[^a-z0-9-]/', '', strtolower($db_icon)) ?: 'file-text';
								} else {
									$feather_name = isset($sr_ops_icon_map[$icon_key]) ? $sr_ops_icon_map[$icon_key] : 'file-text';
								}
								$nav_id = 'ops-' . preg_replace('/\W+/', '-', $icon_key);
								?>
								<li class="nav-item <?php echo $is_dropdown ? 'dropdown' : ''; ?> <?php echo $marker ? $marker . ' sr-nav-current' : ''; ?>">
									<a class="nav-link<?php echo $is_dropdown ? ' dropdown-toggle arrow-none' : ''; ?><?php echo $marker ? ' active' : ''; ?>"
										href="<?php echo $is_dropdown ? '#' : htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>"
										<?php if ($is_dropdown): ?>id="<?php echo htmlspecialchars($nav_id, ENT_QUOTES, 'UTF-8'); ?>"
										role="button" data-bs-toggle="dropdown" data-bs-display="static"
										aria-haspopup="true" aria-expanded="false" <?php endif; ?>>
										<?php echo sr_feather_icon($feather_name); ?>
										<span data-key="t-horizontal"><?php echo htmlspecialchars($role_name, ENT_QUOTES, 'UTF-8'); ?></span>
									</a>
									<?php if ($is_dropdown): ?>
										<div class="dropdown-menu" aria-labelledby="<?php echo htmlspecialchars($nav_id, ENT_QUOTES, 'UTF-8'); ?>">
											<?php
											$select25 = mysqli_query($db, "SELECT * FROM roles_assigned where username='$username' and category='$role_name' ORDER BY arrangement + 0 ASC");
											if (mysqli_num_rows($select25) != 0) {
												while ($get125 = mysqli_fetch_array($select25)) {
													$role_name_view = $get125['name'];
													$file = $get125['file'];
													$file_url = $file . "?main_page=" . rawurlencode($role_name);
													?>
													<a href="<?php echo htmlspecialchars($file_url, ENT_QUOTES, 'UTF-8'); ?>"
														class="dropdown-item"><?php echo htmlspecialchars($role_name_view, ENT_QUOTES, 'UTF-8'); ?></a>
													<?php
												}
											}
											?>
										</div>
									<?php endif; ?>
								</li>
								<?php
							}
						}
						?>
					</ul>
				</div>
			</nav>
		</div>
	</div>
</header>
