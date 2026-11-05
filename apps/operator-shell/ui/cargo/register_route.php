<?php
/**
 * C-P1-14 modern Cargo routes and locations (TDS).
 * Missing register_route_points.php and register_location_name.php menus land here.
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/register_route.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Register route</title>
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
		<div class="container-fluid tds-page" id="tds-cargo-route-root" data-ui-slice="C-P1-14">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Setup</span></p>
					<h1 class="tds-page-title">Register route</h1>
					<p class="tds-page-lead" id="tds-cargo-route-note">Locations and route stops live on this page. The old Route points and Locations menu items open here.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=register_route.php&amp;ui_cargo_register_route=legacy">Use classic</a>
				</div>
			</div>
			<div class="tds-alert tds-alert-danger" id="tds-cargo-route-err" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-cargo-route-ok" hidden role="status"></div>

			<div class="tds-card">
				<div class="tds-card-body">
					<h2 class="tds-card-title">Locations</h2>
					<p class="tds-muted">Add a place, then choose it as the start or end of a route.</p>
					<form id="tds-cargo-location-form" class="tds-row" style="align-items:flex-end;margin-top:var(--tds-space-3)">
						<div class="tds-field" style="flex:1 1 16rem">
							<label class="tds-label" for="tds-cargo-location-name">Location name</label>
							<input class="tds-input" id="tds-cargo-location-name" type="text" maxlength="100" required>
						</div>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-cargo-location-add">Add location</button>
					</form>
					<p class="tds-muted" id="tds-cargo-location-loading">Loading locations…</p>
					<div class="tds-empty" id="tds-cargo-location-empty" hidden>
						<p class="tds-empty-title">No locations yet</p>
						<p class="tds-muted">Add the first town or branch.</p>
					</div>
					<div class="tds-table-wrap" id="tds-cargo-location-wrap" hidden>
						<table class="tds-table">
							<thead><tr><th>Name</th><th></th></tr></thead>
							<tbody id="tds-cargo-locations"></tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="tds-card" style="margin-top:var(--tds-space-4)">
				<div class="tds-card-body">
					<div class="tds-page-title-box">
						<div>
							<h2 class="tds-card-title">Routes</h2>
							<p class="tds-muted">Receipt initial is the short code printed on cargo tickets.</p>
						</div>
						<button type="button" class="tds-btn tds-btn-primary" id="tds-cargo-route-add">Add route</button>
					</div>
					<form id="tds-cargo-route-form" hidden style="margin-top:var(--tds-space-3)">
						<input type="hidden" id="tds-cargo-route-id" value="">
						<div class="tds-row" style="align-items:flex-end">
							<div class="tds-field" style="flex:1 1 14rem">
								<label class="tds-label" for="tds-cargo-route-start">Starting point</label>
								<select class="tds-select" id="tds-cargo-route-start" required></select>
							</div>
							<div class="tds-field" style="flex:1 1 14rem">
								<label class="tds-label" for="tds-cargo-route-end">Ending point</label>
								<select class="tds-select" id="tds-cargo-route-end" required></select>
							</div>
							<div class="tds-field" style="flex:1 1 14rem">
								<label class="tds-label" for="tds-cargo-route-currency">Currency</label>
								<select class="tds-select" id="tds-cargo-route-currency" required></select>
							</div>
							<div class="tds-field" style="flex:1 1 12rem">
								<label class="tds-label" for="tds-cargo-route-initial">Receipt initial</label>
								<input class="tds-input" id="tds-cargo-route-initial" type="text" maxlength="20" required>
							</div>
							<div class="tds-field" style="flex:1 1 14rem">
								<label class="tds-label" for="tds-cargo-route-display">Initial name</label>
								<input class="tds-input" id="tds-cargo-route-display" type="text" maxlength="60" placeholder="Dar to Nairobi">
							</div>
						</div>
						<div class="tds-row" style="align-items:flex-end;margin-top:var(--tds-space-3)">
							<div class="tds-field" style="flex:1 1 14rem">
								<label class="tds-label" for="tds-cargo-route-c1">Contact one</label>
								<input class="tds-input" id="tds-cargo-route-c1" type="text" maxlength="100">
							</div>
							<div class="tds-field" style="flex:1 1 14rem">
								<label class="tds-label" for="tds-cargo-route-c2">Contact two</label>
								<input class="tds-input" id="tds-cargo-route-c2" type="text" maxlength="100">
							</div>
							<div class="tds-field" style="flex:1 1 14rem">
								<label class="tds-label" for="tds-cargo-route-a1">Address one</label>
								<input class="tds-input" id="tds-cargo-route-a1" type="text" maxlength="100">
							</div>
							<div class="tds-field" style="flex:1 1 14rem">
								<label class="tds-label" for="tds-cargo-route-a2">Address two</label>
								<input class="tds-input" id="tds-cargo-route-a2" type="text" maxlength="100">
							</div>
							<div class="tds-field" style="flex:1 1 10rem">
								<label class="tds-label" for="tds-cargo-route-tobe">To-be-paid currency</label>
								<input class="tds-input" id="tds-cargo-route-tobe" type="text" maxlength="10" placeholder="TZS">
							</div>
						</div>
						<div class="tds-row" style="margin-top:var(--tds-space-3)">
							<div class="tds-field" style="flex:1 1 16rem">
								<label class="tds-label" for="tds-cargo-route-t1">Terms and agreement one</label>
								<textarea class="tds-input" id="tds-cargo-route-t1" rows="2"></textarea>
							</div>
							<div class="tds-field" style="flex:1 1 16rem">
								<label class="tds-label" for="tds-cargo-route-t2">Terms and agreement two</label>
								<textarea class="tds-input" id="tds-cargo-route-t2" rows="2"></textarea>
							</div>
						</div>
						<p class="tds-muted" id="tds-cargo-route-lock" hidden>This route is already used. Start, end, and receipt initial stay as they are.</p>
						<div class="tds-row" style="margin-top:var(--tds-space-3)">
							<button type="submit" class="tds-btn tds-btn-primary" id="tds-cargo-route-save">Save route</button>
							<button type="button" class="tds-btn tds-btn-ghost" id="tds-cargo-route-cancel">Cancel</button>
						</div>
					</form>
					<p class="tds-muted" id="tds-cargo-route-loading">Loading routes…</p>
					<div class="tds-empty" id="tds-cargo-route-empty" hidden>
						<p class="tds-empty-title">No routes yet</p>
						<p class="tds-muted">Add a location pair and a receipt initial.</p>
					</div>
					<div class="tds-table-wrap" id="tds-cargo-route-wrap" hidden>
						<table class="tds-table">
							<thead>
								<tr>
									<th>Route</th>
									<th>Receipt initial</th>
									<th>Currency</th>
									<th>Contacts</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="tds-cargo-routes"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/register_route.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
