<?php
/**
 * Dashonic Vertical Dark shell — topbar + left sidebar (Tessera Control).
 * Pattern from Dashonic vertical dark demo; Control nav only (no operator modules).
 */
$control_nav = $control_nav ?? 'overview';
$control_name = $control_user['name'] ?? 'Platform owner';
$control_email = $control_user['email'] ?? '';
$control_initials = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $control_name) ?: 'P', 0, 1));
$control_brand = function_exists('control_branding_get')
	? control_branding_get($db ?? null, !empty($control_db_ok))
	: ['subtitle' => 'Control', 'logo_url' => ''];
$control_unread = function_exists('control_unread_notification_count')
	? control_unread_notification_count($db ?? null, !empty($control_db_ok))
	: 0;
$control_notifs = function_exists('control_list_notifications')
	? control_list_notifications($db ?? null, !empty($control_db_ok), ['unread_only' => true, 'limit' => 6])
	: [];

$nav_groups = [
	[
		'title' => 'Platform',
		'items' => [
			'overview' => ['label' => 'Overview', 'href' => 'overview.php', 'icon' => 'monitor'],
			'health' => ['label' => 'Health', 'href' => 'health.php', 'icon' => 'activity'],
			'backups' => ['label' => 'Backups', 'href' => 'backups.php', 'icon' => 'hard-drive'],
			'billing' => ['label' => 'Billing', 'href' => 'billing.php', 'icon' => 'credit-card'],
			'support' => ['label' => 'Support', 'href' => 'support.php', 'icon' => 'life-buoy'],
			'notifications' => ['label' => 'Notifications', 'href' => 'notifications.php', 'icon' => 'bell'],
			'audit' => ['label' => 'Audit', 'href' => 'audit.php', 'icon' => 'shield'],
			'settings' => ['label' => 'Settings', 'href' => 'settings.php', 'icon' => 'settings'],
		],
	],
	[
		'title' => 'Tenancy',
		'items' => [
			'operators' => ['label' => 'Operators', 'href' => 'operators.php', 'icon' => 'users'],
			'operator_new' => ['label' => 'Add operator', 'href' => 'operator_new.php', 'icon' => 'user-plus'],
			'onboarding' => ['label' => 'Onboarding', 'href' => 'onboarding.php', 'icon' => 'git-pull-request'],
			'onboarding_new' => ['label' => 'New case', 'href' => 'onboarding_new.php', 'icon' => 'plus-circle'],
		],
	],
];

function control_nav_active(string $key, string $current): string {
	if ($key === $current) {
		return ' mm-active';
	}
	/* Detail pages highlight parent */
	if ($key === 'operators' && in_array($current, ['operators', 'operator'], true)) {
		return ' mm-active';
	}
	if ($key === 'onboarding' && in_array($current, ['onboarding', 'onboarding_case'], true)) {
		return ' mm-active';
	}
	return '';
}
?>
<header id="page-topbar">
	<div class="navbar-header">
		<div class="d-flex">
			<div class="navbar-brand-box" data-tour="control-brand">
				<a href="overview.php" class="logo logo-light">
					<span class="logo-sm"><span class="tessera-brand-lockup"><span class="brand">T</span></span></span>
					<span class="logo-lg"><span class="tessera-brand-lockup"><span class="brand">Tessera</span></span></span>
				</a>
			</div>

			<button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn" aria-label="Toggle sidebar">
				<i class="fa fa-fw fa-bars"></i>
			</button>

			<form class="app-search d-none d-lg-block" method="get" action="operators.php">
				<div class="position-relative">
					<input type="text" class="form-control" name="q" placeholder="Search operators…">
					<span class="uil-search"></span>
				</div>
			</form>
		</div>

		<div class="d-flex">
			<div class="dropdown d-inline-block d-lg-none ms-2">
				<button type="button" class="btn header-item" data-bs-toggle="dropdown">
					<i class="icon-sm" data-feather="search"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
					<form class="p-3" method="get" action="operators.php">
						<div class="form-group m-0">
							<div class="input-group">
								<input type="text" class="form-control" name="q" placeholder="Search operators…">
								<button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<div class="dropdown d-none d-lg-inline-block">
				<button type="button" class="btn header-item" data-bs-toggle="dropdown">
					<span class="d-none d-xl-inline-block ms-1 fw-medium">Quick create</span>
					<i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
					<i class="icon-sm d-xl-none" data-feather="plus-circle"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-end">
					<a class="dropdown-item" href="operator_new.php"><i class="mdi mdi-account-plus me-1"></i> Add operator</a>
					<a class="dropdown-item" href="onboarding_new.php"><i class="mdi mdi-clipboard-plus-outline me-1"></i> New onboarding case</a>
				</div>
			</div>

			<div class="dropdown d-inline-block">
				<button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown" data-bs-toggle="dropdown">
					<i class="uil-bell"></i>
					<?php if ($control_unread > 0): ?>
						<span class="badge bg-danger rounded-pill"><?php echo $control_unread > 9 ? '9+' : (int) $control_unread; ?></span>
					<?php endif; ?>
				</button>
				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
					<div class="p-3">
						<div class="row align-items-center">
							<div class="col">
								<h5 class="m-0 font-size-16">Platform alerts</h5>
							</div>
							<div class="col-auto">
								<a href="notifications.php" class="small">View all</a>
							</div>
						</div>
					</div>
					<div data-simplebar style="max-height: 230px;">
						<?php if (!$control_notifs): ?>
							<div class="p-3 text-muted font-size-13">No unread alerts.</div>
						<?php else: ?>
							<?php foreach ($control_notifs as $n): ?>
								<a href="<?php echo control_h($n['href'] ?: 'notifications.php'); ?>" class="text-reset notification-item">
									<div class="d-flex border-top px-3 py-2">
										<div class="flex-grow-1">
											<h6 class="mb-1"><?php echo control_h($n['title']); ?></h6>
											<div class="font-size-12 text-muted">
												<p class="mb-1"><?php echo control_h($n['body'] ?: $n['type']); ?></p>
												<p class="mb-0"><i class="mdi mdi-clock-outline"></i> <?php echo control_h($n['created_at']); ?></p>
											</div>
										</div>
									</div>
								</a>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
					<div class="p-2 border-top">
						<a class="btn btn-sm btn-link font-size-14 w-100 text-center" href="notifications.php">
							Open notification center
						</a>
					</div>
				</div>
			</div>

			<div class="dropdown d-inline-block" data-tour="profile">
				<button type="button" class="btn header-item d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown">
					<span class="control-avatar me-2"><?php echo control_h($control_initials); ?></span>
					<span class="d-none d-xl-inline-block ms-1 fw-medium"><?php echo control_h($control_name); ?></span>
					<i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-end">
					<div class="dropdown-header bg-soft-primary text-white">
						<h6 class="mb-0 text-white"><?php echo control_h($control_name); ?></h6>
						<p class="mb-0 font-size-11 text-white-50 fw-semibold"><?php echo control_h($control_email); ?></p>
					</div>
					<a class="dropdown-item" href="health.php"><i class="mdi mdi-heart-pulse text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Platform health</span></a>
					<a class="dropdown-item" href="settings.php"><i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Settings</span></a>
					<a class="dropdown-item" href="operators.php"><i class="mdi mdi-domain text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Operators</span></a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#" data-tour-action="toggle" title="Show or hide onboarding guidelines">
						<span class="tessera-tour-toggle-dot" data-tour-toggle-dot aria-hidden="true"></span>
						<span data-tour-toggle-label>Guidelines: Off</span>
					</a>
					<a class="dropdown-item" href="#" data-tour-action="start">Start guided tour</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="log_out.php"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Sign out</span></a>
				</div>
			</div>
		</div>
	</div>
</header>

<div class="vertical-menu" data-tour="control-sidebar">
	<div class="navbar-brand-box">
		<a href="overview.php" class="logo logo-light">
			<span class="logo-sm">
				<span class="tessera-brand-lockup"><span class="brand">T</span></span>
			</span>
			<span class="logo-lg">
				<span class="tessera-brand-lockup">
					<span class="brand">Tessera</span>
					<span class="product"><?php echo control_h($control_brand['subtitle'] ?? 'Control'); ?></span>
				</span>
			</span>
		</a>
	</div>

	<button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
		<i class="fa fa-fw fa-bars"></i>
	</button>

	<div data-simplebar class="sidebar-menu-scroll">
		<div id="sidebar-menu">
			<ul class="metismenu list-unstyled" id="side-menu">
				<?php foreach ($nav_groups as $group): ?>
					<li class="menu-title"><?php echo control_h($group['title']); ?></li>
					<?php foreach ($group['items'] as $key => $item):
						$activeClass = control_nav_active($key, $control_nav);
						$linkActive = ($key === $control_nav || ($key === 'operators' && $control_nav === 'operator') || ($key === 'onboarding' && $control_nav === 'onboarding_case')) ? ' active' : '';
						$tourAttr = in_array($key, ['overview', 'operators', 'onboarding', 'health'], true)
							? ' data-tour="control-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '"'
							: '';
					?>
						<li class="<?php echo trim($activeClass); ?>"<?php echo $tourAttr; ?>>
							<a href="<?php echo control_h($item['href']); ?>" class="<?php echo trim($linkActive); ?>"<?php echo $tourAttr; ?>>
								<i class="icon nav-icon" data-feather="<?php echo control_h($item['icon']); ?>"></i>
								<span class="menu-item"><?php echo control_h($item['label']); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				<?php endforeach; ?>

				<li class="menu-title">Operator product</li>
				<li>
					<a href="https://tessera.co.tz/tickets/" target="_blank" rel="noopener">
						<i class="icon nav-icon" data-feather="external-link"></i>
						<span class="menu-item">Open staff console</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
