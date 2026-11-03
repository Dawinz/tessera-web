<?php
/**
 * Shared Dashonic shell for operator Settings pages (inside Tickets console).
 * Expects: $settings_page_title, $settings_crumb, $settings_nav_active
 * Session + $db + $username + $tenantId prepared by caller via settings_boot.php
 */
if (!isset($settings_page_title)) {
	$settings_page_title = 'Settings';
}
if (!isset($settings_crumb)) {
	$settings_crumb = $settings_page_title;
}
if (!isset($settings_nav_active)) {
	$settings_nav_active = 'home';
}

$settings_nav_all = [
	'home' => ['Overview', 'settings.php', 'uil-setting', null],
	'payouts' => ['Payouts', 'settings_payouts.php', 'uil-money-withdraw', 'payouts'],
	'notifications' => ['Notifications', 'settings_notifications.php', 'uil-bell', 'notifications'],
	'staff' => ['Staff & roles', 'settings_staff.php', 'uil-users-alt', 'staff'],
	'api' => ['API keys', 'settings_api.php', 'uil-key-skeleton', 'api_keys'],
	'webhooks' => ['Webhooks', 'settings_webhooks.php', 'uil-link', 'api_keys'],
	'company' => ['Company profile', 'settings_company.php', 'uil-building', 'settings'],
];
$settings_nav = [];
foreach ($settings_nav_all as $key => $item) {
	$need = $item[3];
	if ($need === null || (isset($db, $username) && function_exists('tessera_can') && tessera_can($db, $username, $need))) {
		$settings_nav[$key] = $item;
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php
		if (!function_exists('tessera_document_title')) {
			require_once __DIR__ . '/tessera_tenant.php';
		}
		echo htmlspecialchars(tessera_document_title($settings_page_title, 'Settings'), ENT_QUOTES, 'UTF-8');
	?></title>
	<?php echo tessera_favicon_link_tags('/tickets/assets/images'); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Tessera operator settings" name="description" />
	<meta content="Tessera" name="author" />
	<link rel="shortcut icon" href="assets/images/favicon.ico">
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="./style.css">
</head>
<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">
<div id="layout-wrapper">
<?php
$sr_profile_home = 'profile.php';
$sr_settings_href = 'settings.php';
include __DIR__ . '/../top_header.php';
?>
	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">
						<div class="page-title-box d-flex align-items-center justify-content-between">
							<h4 class="mb-0"><?php echo htmlspecialchars($settings_page_title, ENT_QUOTES, 'UTF-8'); ?></h4>
							<div class="page-title-right">
								<ol class="breadcrumb m-0">
									<li class="breadcrumb-item"><a href="home.php">Apps</a></li>
									<li class="breadcrumb-item"><a href="settings.php">Operator settings</a></li>
									<li class="breadcrumb-item active"><?php echo htmlspecialchars($settings_crumb, ENT_QUOTES, 'UTF-8'); ?></li>
								</ol>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-3">
						<div class="card" data-tour="settings-sidebar">
							<div class="card-header">
								<h5 class="card-title mb-0"><i class="uil uil-setting me-1"></i> Operator settings</h5>
							</div>
							<div class="card-body p-2">
								<div class="nav flex-column nav-pills" role="tablist">
									<?php foreach ($settings_nav as $key => $item): ?>
										<a class="nav-link<?php echo $settings_nav_active === $key ? ' active' : ''; ?>"
											href="<?php echo htmlspecialchars($item[1], ENT_QUOTES, 'UTF-8'); ?>"
											data-tour="settings-nav-<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>">
											<i class="uil <?php echo htmlspecialchars($item[2], ENT_QUOTES, 'UTF-8'); ?> me-1"></i>
											<?php echo htmlspecialchars($item[0], ENT_QUOTES, 'UTF-8'); ?>
										</a>
									<?php endforeach; ?>
								</div>
								<p class="text-muted font-size-12 mb-0 mt-3 px-2">
									Tenant #<?php echo (int) ($tenantId ?? 0); ?> - <?php echo htmlspecialchars((string) ($settings_role ?? ''), ENT_QUOTES, 'UTF-8'); ?>
								</p>
							</div>
						</div>
					</div>
					<div class="col-xl-9">
<?php

function settings_layout_end(): void {
	?>
					</div>
				</div>
			</div>
		</div>
		<footer class="footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-6">Tessera</div>
					<div class="col-sm-6"><div class="text-sm-end d-none d-sm-block">Operator console</div></div>
				</div>
			</div>
		</footer>
	</div>
</div>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
<script src="assets/libs/metismenujs/metismenujs.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
	<?php
}
