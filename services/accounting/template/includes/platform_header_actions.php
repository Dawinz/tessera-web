<?php
/**
 * Shared top-bar actions (search + profile) - Tickets / Cargo / Accounts.
 *
 * Expects: $Figure, $firstname, $surname, $level, $image
 * Optional: $sr_profile_home, $sr_logout_href, $sr_avatar_src, $sr_search_wide, $sr_settings_href
 */
if (!isset($sr_logout_href)) {
	$sr_logout_href = 'log_out.php';
}
if (!isset($sr_settings_href)) {
	$sr_settings_href = '/settings/';
}
if (!isset($sr_avatar_src)) {
	$default_avatar = 'assets/images/users/user.png';
	$sr_avatar_src = $default_avatar;
	$img = isset($image) ? trim((string) $image) : '';
	if ($img !== '' && $img !== 'user.png') {
		$candidates = [
			[dirname(__DIR__) . '/uploads/' . $img, 'uploads/' . $img],
			[dirname(__DIR__) . '/img/faces/' . $img, 'img/faces/' . $img],
			[dirname(__DIR__) . '/../img/faces/' . $img, '../img/faces/' . $img],
		];
		foreach ($candidates as $pair) {
			if (is_file($pair[0])) {
				$sr_avatar_src = $pair[1];
				break;
			}
		}
	}
}
$sr_search_wide = !empty($sr_search_wide);
$sr_avatar_initial = strtoupper(substr(trim((string) ($firstname ?? $Figure ?? 'U')), 0, 1));
if ($sr_avatar_initial === '') {
	$sr_avatar_initial = 'U';
}
$sr_show_operator_settings = false;
if (isset($db, $username) && $db instanceof mysqli && function_exists('tessera_can')) {
	foreach (['settings', 'payouts', 'staff', 'notifications', 'api_keys'] as $__cap) {
		if (tessera_can($db, (string) $username, $__cap)) {
			$sr_show_operator_settings = true;
			break;
		}
	}
} elseif (isset($level) && (stripos((string) $level, 'admin') !== false || stripos((string) $level, 'account') !== false)) {
	$sr_show_operator_settings = true;
}
?>
<div class="d-flex sr-header-actions align-items-center">
	<div class="dropdown d-inline-block">
		<button type="button" class="btn header-item" data-bs-toggle="dropdown" data-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false" title="Search">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search icon-sm" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
		</button>
		<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 sr-header-search-menu<?php echo $sr_search_wide ? ' sr-header-search-wide' : ''; ?>">
			<form class="p-2" onsubmit="return false;">
				<div class="search-box">
					<div class="position-relative">
						<input type="text" id="searchBox22" class="sr-table-search form-control rounded bg-light border-0" placeholder="Search...">
						<i class="mdi mdi-magnify search-icon"></i>
						<div id="response"></div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="dropdown d-inline-block" data-tour="profile">
		<button type="button" class="btn header-item user text-start d-flex align-items-center"
			id="page-header-user-dropdown" data-bs-toggle="dropdown" data-toggle="dropdown"
			aria-haspopup="true" aria-expanded="false">
			<span class="sr-avatar-wrap">
				<img class="rounded-circle header-profile-user"
					src="<?php echo htmlspecialchars($sr_avatar_src, ENT_QUOTES, 'UTF-8'); ?>"
					alt=""
					onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
				<span class="sr-avatar-fallback" aria-hidden="true"><?php echo htmlspecialchars($sr_avatar_initial, ENT_QUOTES, 'UTF-8'); ?></span>
			</span>
			<span class="ms-2 d-none d-sm-block user-item-desc">
				<span class="user-name"><?php echo htmlspecialchars($Figure, ENT_QUOTES, 'UTF-8'); ?></span>
				<span class="user-sub-title"><?php echo htmlspecialchars($level, ENT_QUOTES, 'UTF-8'); ?></span>
			</span>
		</button>
		<div class="dropdown-menu dropdown-menu-end pt-0">
			<div class="p-3 bg-primary border-bottom">
				<h6 class="mb-0 text-white"><?php echo htmlspecialchars(trim($firstname . ' ' . $surname), ENT_QUOTES, 'UTF-8'); ?></h6>
				<p class="mb-0 font-size-11 text-white-50 fw-semibold"><?php echo htmlspecialchars($level, ENT_QUOTES, 'UTF-8'); ?></p>
			</div>
			<?php if (!empty($sr_profile_home)): ?>
				<a class="dropdown-item" href="<?php echo htmlspecialchars($sr_profile_home, ENT_QUOTES, 'UTF-8'); ?>">Profile</a>
			<?php endif; ?>
			<?php if ($sr_show_operator_settings): ?>
				<a class="dropdown-item" href="<?php echo htmlspecialchars($sr_settings_href, ENT_QUOTES, 'UTF-8'); ?>" data-tour="settings-link">Operator settings</a>
			<?php endif; ?>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="#" data-tour-action="toggle" title="Show or hide onboarding guidelines">
				<span class="tessera-tour-toggle-dot" data-tour-toggle-dot aria-hidden="true"></span>
				<span data-tour-toggle-label>Guidelines: Off</span>
			</a>
			<a class="dropdown-item" href="#" data-tour-action="start">Start guided tour</a>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="<?php echo htmlspecialchars($sr_logout_href, ENT_QUOTES, 'UTF-8'); ?>">Logout</a>
		</div>
	</div>
</div>
