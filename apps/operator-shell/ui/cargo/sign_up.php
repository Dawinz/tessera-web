<?php
/**
 * C-P1-16 Cargo sign up merges into Settings staff (TDS).
 * Cargo users are the same directory Settings already writes through to cargo.
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/sign_up.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Register user</title>
<?php if (!$isSpa): ?>
	<base href="/cargo/">
	<?php include $__roots['accounts'] . '/template/includes/dashonic_head.php'; ?>
	<link href="/operator/css/tessera-design-tokens.css" rel="stylesheet">
	<link href="/operator/css/tessera-design-components.css" rel="stylesheet">
<?php endif; ?>
</head>
<body <?php echo $isSpa ? '' : 'data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark" class="sr-platform-body sr-operator-shell"'; ?>>
<?php if (!$isSpa): ?>
<?php include dirname(__DIR__, 2) . '/includes/shell_header.php'; ?>
<div class="main-content">
<?php endif; ?>
	<div class="page-content">
		<div class="container-fluid tds-page" id="tds-cargo-signup-root" data-ui-slice="C-P1-16">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Setup</span></p>
					<h1 class="tds-page-title">Register user</h1>
					<p class="tds-page-lead">Staff accounts are created in Settings and copied into Cargo. Opening the staff directory.</p>
				</div>
			</div>
			<div class="tds-card">
				<div class="tds-card-body">
					<p>Add people, set roles, and choose menu access on the staff page. Cargo menus follow that same person.</p>
					<div class="tds-row" style="margin-top:var(--tds-space-4)">
						<a class="tds-btn tds-btn-primary" id="tds-cargo-signup-staff" href="/settings/staff.php">Open staff</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/sign_up.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
