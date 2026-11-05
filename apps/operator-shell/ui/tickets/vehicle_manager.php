<?php
/**
 * T-P0-05 modern Vehicle manager (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/vehicle_manager.js') ?: time();
$preselect = (int) ($_GET['vehicle'] ?? 0);

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Vehicle manager</title>
<?php if (!$isSpa): ?>
	<base href="/tickets/">
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
		<div class="container-fluid tds-page" id="tds-vehicle-root" data-ui-slice="T-P0-05" data-preselect="<?php echo $preselect; ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Vehicle manager</span></p>
					<h1 class="tds-page-title">Vehicle manager</h1>
					<p class="tds-page-lead">Fleet registry, seat layout, and trip assignments.</p>
				</div>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" id="tds-vm-add">Add vehicle</button>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=vehicle_manager.php&amp;ui_tickets_vehicle_manager=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-vm-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-vm-success" hidden role="status"></div>

			<div class="tds-fleet-shell">
				<div class="tds-card">
					<div class="tds-card-header">
						<h2 class="tds-card-title">Fleet</h2>
					</div>
					<div class="tds-card-body" style="padding-bottom:var(--tds-space-3)">
						<input class="tds-input" id="tds-vm-search" type="search" placeholder="Search plate, driver…">
					</div>
					<div class="tds-fleet-list" id="tds-vm-fleet" aria-live="polite">
						<div class="tds-empty" style="padding:var(--tds-space-6)"><p class="tds-muted">Loading fleet…</p></div>
					</div>
				</div>

				<div>
					<div class="tds-card" id="tds-vm-empty">
						<div class="tds-card-body">
							<div class="tds-empty">
								<p class="tds-empty-title">Select a vehicle</p>
								<p class="tds-muted">Choose a bus from the fleet list, or add a new one.</p>
								<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-vm-add-empty">Add vehicle</button>
							</div>
						</div>
					</div>

					<div id="tds-vm-workspace" hidden>
						<div class="tds-card" style="margin-bottom:var(--tds-space-4)">
							<div class="tds-card-body">
								<div class="tds-row" style="justify-content:space-between">
									<div>
										<div class="tds-row" style="gap:var(--tds-space-2)">
											<h2 class="tds-card-title" id="tds-vm-hero-name" style="margin:0">—</h2>
											<span class="tds-badge tds-badge-neutral" id="tds-vm-hero-status">—</span>
											<span class="tds-badge tds-badge-info" id="tds-vm-hero-type">—</span>
										</div>
										<p class="tds-muted" id="tds-vm-hero-meta" style="margin:0.35rem 0 0">—</p>
									</div>
									<div class="tds-row" style="gap:var(--tds-space-2)">
										<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-vm-delete" hidden>Delete</button>
										<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" id="tds-vm-save">Save details</button>
									</div>
								</div>
							</div>
						</div>

						<div class="tds-card">
							<div class="tds-card-body">
								<div class="tds-tabs" role="tablist">
									<button type="button" class="tds-tab is-active" data-tab="overview">Overview</button>
									<button type="button" class="tds-tab" data-tab="layout">Seat layout</button>
									<button type="button" class="tds-tab" data-tab="assignments">Assignments</button>
								</div>

								<div id="tds-vm-panel-overview">
									<div class="tds-row" style="align-items:flex-end">
										<div class="tds-field" style="flex:1 1 12rem">
											<label class="tds-label" for="tds-vm-name">Vehicle number / plate</label>
											<input class="tds-input" id="tds-vm-name" type="text">
										</div>
										<div class="tds-field">
											<label class="tds-label" for="tds-vm-seats">Total seats</label>
											<input class="tds-input" id="tds-vm-seats" type="number" min="1" max="120">
										</div>
										<div class="tds-field">
											<label class="tds-label" for="tds-vm-state">Vehicle type</label>
											<select class="tds-select" id="tds-vm-state">
												<option value="normal">Standard</option>
												<option value="vip">VIP</option>
											</select>
										</div>
									</div>
									<div class="tds-row" style="margin-top:var(--tds-space-3);align-items:flex-end">
										<div class="tds-field" style="flex:1 1 12rem">
											<label class="tds-label" for="tds-vm-driver">Default driver</label>
											<input class="tds-input" id="tds-vm-driver" type="text">
										</div>
										<div class="tds-field" style="flex:1 1 12rem">
											<label class="tds-label" for="tds-vm-conductor">Default conductor</label>
											<input class="tds-input" id="tds-vm-conductor" type="text">
										</div>
									</div>
									<div class="tds-row" style="margin-top:var(--tds-space-3);align-items:flex-end">
										<div class="tds-field" style="flex:1 1 12rem">
											<label class="tds-label" for="tds-vm-detail">Notes / model</label>
											<input class="tds-input" id="tds-vm-detail" type="text" placeholder="e.g. MAN diesel, 2022">
										</div>
										<div class="tds-field" style="flex:1 1 12rem">
											<label class="tds-label" for="tds-vm-dedicate">Dedicated seat label</label>
											<input class="tds-input" id="tds-vm-dedicate" type="text" placeholder="e.g. A1">
										</div>
									</div>
									<label class="tds-muted" style="display:inline-flex;gap:0.4rem;align-items:center;margin-top:var(--tds-space-3);font-size:var(--tds-fs-sm)">
										<input type="checkbox" id="tds-vm-active" checked> Active in fleet
									</label>
									<div class="tds-kpi-grid" style="margin-top:var(--tds-space-5);margin-bottom:0">
										<div class="tds-kpi"><p class="tds-kpi-label">Schedule templates</p><p class="tds-kpi-value" id="tds-vm-stat-templates">0</p></div>
										<div class="tds-kpi"><p class="tds-kpi-label">Manifest rows</p><p class="tds-kpi-value" id="tds-vm-stat-manifests">0</p></div>
										<div class="tds-kpi"><p class="tds-kpi-label">Ticket bookings</p><p class="tds-kpi-value" id="tds-vm-stat-bookings">0</p></div>
									</div>
								</div>

								<div id="tds-vm-panel-layout" hidden>
									<div class="tds-row" style="margin-bottom:var(--tds-space-3)">
										<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-layout-add-row">Add row</button>
										<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-layout-add-extra">Extra seat</button>
										<span class="tds-muted" style="font-size:var(--tds-fs-sm)">Templates:</span>
										<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm tds-layout-tpl" data-seats="37">37</button>
										<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm tds-layout-tpl" data-seats="41">41</button>
										<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm tds-layout-tpl" data-seats="45">45</button>
										<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm tds-layout-tpl" data-seats="49">49</button>
										<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm tds-layout-tpl" data-seats="57">57</button>
										<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm tds-layout-tpl" data-seats="59">59</button>
										<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" id="tds-layout-save">Save layout</button>
									</div>
									<p class="tds-muted" style="font-size:var(--tds-fs-sm);margin-bottom:var(--tds-space-3)">Click a cell to cycle seat → aisle → empty. Capacity must match total seats.</p>
									<div class="tds-row" style="align-items:stretch">
										<div style="flex:1 1 18rem">
											<div class="tds-seat-chart">
												<div class="tds-muted" style="font-size:var(--tds-fs-xs)">Front</div>
												<div id="tds-layout-canvas"></div>
												<div id="tds-layout-extras"></div>
											</div>
										</div>
										<div style="flex:0 1 14rem">
											<div class="tds-card" style="height:100%">
												<div class="tds-card-header"><h3 class="tds-card-title">Summary</h3></div>
												<div class="tds-card-body">
													<p class="tds-muted" style="margin:0">Capacity: <strong id="tds-layout-capacity">—</strong></p>
													<p class="tds-muted" style="margin:0.35rem 0 0">In layout: <strong id="tds-layout-count">—</strong></p>
													<p class="tds-muted" style="margin:0.35rem 0 var(--tds-space-3)">Match: <strong id="tds-layout-match">—</strong></p>
													<div class="tds-layout-preview" id="tds-layout-preview"></div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div id="tds-vm-panel-assignments" hidden>
									<div class="tds-row" style="align-items:stretch">
										<div class="tds-card" style="flex:1 1 16rem">
											<div class="tds-card-header"><h3 class="tds-card-title">Schedule templates</h3></div>
											<div class="tds-card-body" id="tds-vm-templates"><p class="tds-muted">—</p></div>
										</div>
										<div class="tds-card" style="flex:1 1 16rem">
											<div class="tds-card-header"><h3 class="tds-card-title">Recent manifests</h3></div>
											<div class="tds-card-body" id="tds-vm-manifests"><p class="tds-muted">—</p></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<dialog class="tds-card" id="tds-vm-create" style="border:1px solid var(--tds-line-strong);border-radius:var(--tds-radius-md);padding:0;max-width:26rem;width:calc(100% - 2rem)">
				<div class="tds-card-header">
					<h2 class="tds-card-title">Add vehicle</h2>
					<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-vm-create-close">Close</button>
				</div>
				<div class="tds-card-body">
					<div class="tds-stack">
						<div class="tds-field"><label class="tds-label" for="tds-create-name">Vehicle number</label><input class="tds-input" id="tds-create-name" type="text"></div>
						<div class="tds-field"><label class="tds-label" for="tds-create-seats">Seats</label><input class="tds-input" id="tds-create-seats" type="number" min="1" max="120" value="49"></div>
						<div class="tds-field"><label class="tds-label" for="tds-create-state">Type</label><select class="tds-select" id="tds-create-state"><option value="normal">Standard</option><option value="vip">VIP</option></select></div>
						<div class="tds-field"><label class="tds-label" for="tds-create-driver">Driver</label><input class="tds-input" id="tds-create-driver" type="text"></div>
						<div class="tds-field"><label class="tds-label" for="tds-create-conductor">Conductor</label><input class="tds-input" id="tds-create-conductor" type="text"></div>
						<div class="tds-field"><label class="tds-label" for="tds-create-detail">Notes</label><input class="tds-input" id="tds-create-detail" type="text"></div>
						<button type="button" class="tds-btn tds-btn-primary" id="tds-vm-create-save">Create</button>
					</div>
				</div>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/vehicle_manager.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
