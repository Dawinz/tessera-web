<?php
/**
 * Operator settings — Dashonic topbar + fixed full-height left sidebar.
 */
if (!isset($settings_page_title)) {
	$settings_page_title = 'Operator settings';
}
if (!isset($settings_crumb)) {
	$settings_crumb = $settings_page_title;
}
if (!isset($settings_nav_active)) {
	$settings_nav_active = 'home';
}

$SETTINGS_BASE = $SETTINGS_BASE ?? '/settings';
$TICKETS_WEB = $TICKETS_WEB ?? '/tickets';
$ticketsIncludes = $ticketsIncludes ?? (dirname(__DIR__, 2) . '/tickets/includes');
if (!is_dir($ticketsIncludes)) {
	$ticketsIncludes = dirname(__DIR__, 3) . '/services/tickets/includes';
}

$settings_nav_all = [
	'home' => ['Overview', $SETTINGS_BASE . '/', 'uil-estate', null],
	'profile' => ['My profile', $SETTINGS_BASE . '/profile.php', 'uil-user-circle', null],
	'payouts' => ['Payouts', $SETTINGS_BASE . '/payouts.php', 'uil-money-withdraw', 'payouts'],
	'notifications' => ['Notifications', $SETTINGS_BASE . '/notifications.php', 'uil-bell', 'notifications'],
	'staff' => ['Staff & roles', $SETTINGS_BASE . '/staff.php', 'uil-users-alt', 'staff'],
	'api' => ['API keys', $SETTINGS_BASE . '/api.php', 'uil-key-skeleton', 'api_keys'],
	'webhooks' => ['Webhooks', $SETTINGS_BASE . '/webhooks.php', 'uil-link-alt', 'api_keys'],
	'company' => ['Company profile', $SETTINGS_BASE . '/company.php', 'uil-building', 'settings'],
];
$settings_nav = [];
foreach ($settings_nav_all as $key => $item) {
	$need = $item[3];
	if ($need === null || settings_can($need)) {
		$settings_nav[$key] = $item;
	}
}

$tenant = function_exists('tessera_tenant_row') ? (tessera_tenant_row() ?: []) : [];
$SETTINGS_SIDEBAR_W = 255;
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php echo settings_h($settings_page_title); ?> | Tessera</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Tessera operator settings" name="description" />
	<meta content="Tessera" name="author" />
	<link rel="shortcut icon" href="<?php echo settings_h($TICKETS_WEB); ?>/assets/images/favicon.ico">
	<link href="<?php echo settings_h($TICKETS_WEB); ?>/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="<?php echo settings_h($TICKETS_WEB); ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo settings_h($TICKETS_WEB); ?>/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<?php
	$sr_shell_href = $TICKETS_WEB . '/assets/css/sr-platform-shell.css';
	$sr_shell_file = dirname($ticketsIncludes) . '/assets/css/sr-platform-shell.css';
	$sr_shell_v = is_file($sr_shell_file) ? filemtime($sr_shell_file) : time();
	?>
	<link rel="stylesheet" href="<?php echo settings_h($sr_shell_href); ?>?v=<?php echo (int) $sr_shell_v; ?>&sr=10">
	<?php
	if (!empty($settings_extra_css)) {
		foreach ((array) $settings_extra_css as $cssHref) {
			echo '<link rel="stylesheet" href="' . settings_h((string) $cssHref) . '">' . "\n\t";
		}
	}
	?>
	<style id="tessera-settings-layout">
	:root {
		--sr-chrome-height: 70px;
		--sr-settings-sidebar: <?php echo (int) $SETTINGS_SIDEBAR_W; ?>px;
	}

	/* Topbar stays fixed across the full width */
	#page-topbar {
		position: fixed !important;
		top: 0 !important;
		left: 0 !important;
		right: 0 !important;
		width: 100% !important;
		z-index: 1005 !important;
		background: #03314d !important;
	}
	#page-topbar .navbar-header {
		display: flex !important;
		align-items: center !important;
		height: 70px !important;
		padding: 0 1.25rem !important;
		border-bottom: 1px solid rgba(255,255,255,.12) !important;
	}
	#page-topbar .navbar-header > .d-flex:first-child {
		flex: 0 0 240px !important;
		min-width: 240px !important;
		align-items: center !important;
		z-index: 2 !important;
	}
	#page-topbar .sr-header-actions {
		flex: 0 0 240px !important;
		min-width: 240px !important;
		margin-left: auto !important;
		display: flex !important;
		justify-content: flex-end !important;
		z-index: 2 !important;
	}
	.sr-modules-center {
		position: absolute !important;
		left: 50% !important;
		top: 50% !important;
		transform: translate(-50%, -50%) !important;
		z-index: 5 !important;
	}

	/*
	 * Dashonic vertical-menu — pinned to the LEFT edge, full height under topbar.
	 * Does not scroll with page content.
	 */
	body.tessera-settings-layout .vertical-menu {
		display: block !important;
		position: fixed !important;
		top: var(--sr-chrome-height, 70px) !important;
		left: 0 !important;
		right: auto !important;
		bottom: 0 !important;
		width: var(--sr-settings-sidebar) !important;
		margin: 0 !important;
		z-index: 1002 !important;
		background: #032d46 !important;
		border: 0 !important;
		border-right: 1px solid #043a5a !important;
		overflow: hidden !important;
	}
	body.tessera-settings-layout .vertical-menu .sidebar-menu-scroll {
		height: 100% !important;
		margin-top: 0 !important;
		overflow-y: auto !important;
	}
	body.tessera-settings-layout #sidebar-menu {
		padding: 1rem 0 2rem !important;
	}
	body.tessera-settings-layout #sidebar-menu .menu-title {
		padding: 12px 20px 8px !important;
		color: #7e93a0 !important;
		opacity: 1 !important;
	}
	body.tessera-settings-layout #sidebar-menu ul li a {
		display: flex !important;
		align-items: center !important;
		gap: .65rem !important;
		padding: .72rem 1rem !important;
		margin: 0 .65rem .15rem !important;
		color: #a6b0cf !important;
		font-size: 14.5px !important;
		border-radius: .25rem !important;
	}
	body.tessera-settings-layout #sidebar-menu ul li a i {
		font-size: 1.15rem !important;
		width: 1.35rem !important;
		text-align: center !important;
		color: inherit !important;
	}
	body.tessera-settings-layout #sidebar-menu ul li a:hover {
		color: #fff !important;
		background: rgba(255,255,255,.04) !important;
	}
	body.tessera-settings-layout #sidebar-menu ul li.mm-active > a,
	body.tessera-settings-layout #sidebar-menu ul li a.active {
		background: rgba(3, 142, 220, 0.18) !important;
		color: #038edc !important;
	}
	body.tessera-settings-layout .tessera-settings-meta {
		padding: 1rem 1.25rem;
		border-top: 1px solid #043a5a;
		margin-top: auto;
	}
	body.tessera-settings-layout .tessera-settings-meta p {
		margin: 0;
		font-size: 12px;
		color: #7e93a0;
	}

	/* Content column clears the fixed left sidebar + topbar */
	body.tessera-settings-layout .main-content {
		margin-left: var(--sr-settings-sidebar) !important;
		margin-right: 0 !important;
		overflow: visible !important;
	}
	body.tessera-settings-layout .page-content {
		margin-top: 0 !important;
		padding-top: calc(var(--sr-chrome-height, 70px) + 1.25rem) !important;
		padding-left: 1.25rem !important;
		padding-right: 1.25rem !important;
		min-height: calc(100vh - var(--sr-chrome-height, 70px));
	}
	body.tessera-settings-layout .footer {
		left: var(--sr-settings-sidebar) !important;
		right: 0 !important;
	}

	@media (max-width: 991.98px) {
		body.tessera-settings-layout .vertical-menu {
			display: none !important;
		}
		body.tessera-settings-layout.sidebar-enable .vertical-menu {
			display: block !important;
			z-index: 1006 !important;
		}
		body.tessera-settings-layout .main-content {
			margin-left: 0 !important;
		}
		body.tessera-settings-layout .footer {
			left: 0 !important;
		}
	}
	</style>
	<script>
	(function () {
		function srSyncChromeHeight() {
			var bar = document.getElementById('page-topbar');
			if (!bar) return;
			var h = Math.ceil(bar.getBoundingClientRect().height);
			if (h > 0) document.documentElement.style.setProperty('--sr-chrome-height', h + 'px');
		}
		document.addEventListener('DOMContentLoaded', srSyncChromeHeight);
		window.addEventListener('resize', srSyncChromeHeight);
		window.addEventListener('load', srSyncChromeHeight);
		setTimeout(srSyncChromeHeight, 50);
		setTimeout(srSyncChromeHeight, 300);
	})();
	</script>
	<?php
	$tessera_tour_module = 'tickets';
	$tessera_tour_role_label = $level ?? 'Staff';
	$tessera_tour_asset_base = $TICKETS_WEB . '/assets/tessera-tour';
	if (is_file($ticketsIncludes . '/tessera_tour_boot.php')) {
		include $ticketsIncludes . '/tessera_tour_boot.php';
	}
	$tessera_states_module = 'tickets';
	if (is_file($ticketsIncludes . '/tessera_states_boot.php')) {
		include $ticketsIncludes . '/tessera_states_boot.php';
	}
	if (function_exists('tessera_brand_css_vars')) {
		echo tessera_brand_css_vars();
	}
	?>
</head>
<body class="tessera-settings-layout sr-platform-body"
	data-topbar="dark"
	data-sidebar="dark"
	data-layout-mode="dark"
	data-sidebar-size="lg">
<div id="layout-wrapper">
	<header id="page-topbar">
		<div class="navbar-header">
			<div class="d-flex">
				<button type="button"
					class="btn btn-sm px-3 font-size-16 header-item d-lg-none"
					id="vertical-menu-btn"
					aria-label="Toggle settings menu">
					<i class="fa fa-fw fa-bars"></i>
				</button>
				<div class="navbar-brand-box">
					<?php
					if (function_exists('tessera_brand_markup')) {
						echo tessera_brand_markup($TICKETS_WEB . '/home.php');
					} else {
						echo '<a href="' . settings_h($TICKETS_WEB) . '/home.php" class="logo logo-light"><span class="logo-lg tessera-brand">' . settings_h($tenant['display_name'] ?? 'Tessera') . '</span></a>';
					}
					?>
				</div>
			</div>
			<?php
			$sr_active_module = '';
			if (is_file($ticketsIncludes . '/platform_modules.php')) {
				include $ticketsIncludes . '/platform_modules.php';
			}
			$sr_settings_href = $SETTINGS_BASE . '/';
			$sr_logout_href = $TICKETS_WEB . '/log_out.php';
			$sr_profile_home = $SETTINGS_BASE . '/profile.php';
			$sr_avatar_src = $TICKETS_WEB . '/assets/images/users/user.png';
			$img = isset($image) ? trim((string) $image) : '';
			if ($img !== '' && is_file(dirname($ticketsIncludes) . '/uploads/' . $img)) {
				$sr_avatar_src = $TICKETS_WEB . '/uploads/' . rawurlencode($img);
			}
			if (is_file($ticketsIncludes . '/platform_header_actions.php')) {
				include $ticketsIncludes . '/platform_header_actions.php';
			}
			?>
		</div>
	</header>

	<!-- ========== Left Sidebar Start (Dashonic vertical-menu) ========== -->
	<div class="vertical-menu" data-tour="settings-sidebar">
		<div data-simplebar class="h-100 sidebar-menu-scroll">
			<div id="sidebar-menu">
				<ul class="metismenu list-unstyled" id="side-menu">
					<li class="menu-title">Operator settings</li>
					<?php foreach ($settings_nav as $key => $item): ?>
						<li class="<?php echo $settings_nav_active === $key ? 'mm-active' : ''; ?>">
							<a href="<?php echo settings_h($item[1]); ?>"
								class="<?php echo $settings_nav_active === $key ? 'active' : ''; ?>"
								data-tour="settings-nav-<?php echo settings_h($key); ?>">
								<i class="uil <?php echo settings_h($item[2]); ?>"></i>
								<span><?php echo settings_h($item[0]); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="tessera-settings-meta">
					<p>Tenant #<?php echo (int) $tenantId; ?></p>
					<p><?php echo settings_h($settings_role); ?></p>
				</div>
			</div>
		</div>
	</div>
	<!-- Left Sidebar End -->

	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<div class="page-title-box d-flex align-items-center justify-content-between">
							<h4 class="mb-0"><?php echo settings_h($settings_page_title); ?></h4>
							<div class="page-title-right">
								<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href="<?php echo settings_h($TICKETS_WEB); ?>/home.php">Apps</a></li>
									<li class="breadcrumb-item"><a href="<?php echo settings_h($SETTINGS_BASE); ?>/">Settings</a></li>
									<li class="breadcrumb-item active"><?php echo settings_h($settings_crumb); ?></li>
								</ol>
							</div>
						</div>
					</div>
				</div>
<?php

function settings_layout_end(): void {
	global $TICKETS_WEB, $settings_extra_scripts;
	?>
			</div>
		</div>
		<footer class="footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-6">
						<script>document.write(new Date().getFullYear())</script> &copy; Tessera.
					</div>
					<div class="col-sm-6">
						<div class="text-sm-end d-none d-sm-block">Operator settings</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
</div>
<script src="<?php echo htmlspecialchars($TICKETS_WEB, ENT_QUOTES, 'UTF-8'); ?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo htmlspecialchars($TICKETS_WEB, ENT_QUOTES, 'UTF-8'); ?>/assets/libs/metismenujs/metismenujs.min.js"></script>
<script src="<?php echo htmlspecialchars($TICKETS_WEB, ENT_QUOTES, 'UTF-8'); ?>/assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?php echo htmlspecialchars($TICKETS_WEB, ENT_QUOTES, 'UTF-8'); ?>/assets/libs/feather-icons/feather.min.js"></script>
<script src="<?php echo htmlspecialchars($TICKETS_WEB, ENT_QUOTES, 'UTF-8'); ?>/assets/js/app.js"></script>
<script>
(function () {
	var btn = document.getElementById('vertical-menu-btn');
	if (btn) {
		btn.addEventListener('click', function () {
			document.body.classList.toggle('sidebar-enable');
		});
	}
})();
</script>
<?php
	if (!empty($settings_extra_scripts)) {
		echo $settings_extra_scripts;
	}
?>
</body>
</html>
	<?php
}
