<?php
/**
 * C-P0-04 modern Vehicle manifest list (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/vehicle_view.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Manifest</title>
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
		<div class="container-fluid tds-page" id="tds-manifest-root" data-ui-slice="C-P0-04">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Manifest</span></p>
					<h1 class="tds-page-title">Manifest</h1>
					<p class="tds-page-lead">Register and edit trip manifests. Load parcels, send SMS, remarks, and delete empty manifests here.</p>
				</div>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" id="tds-manifest-open-create">Register manifest</button>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=vehicle_view.php&amp;ui_cargo_vehicle_view=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-manifest-filters" aria-label="Manifest filters">
				<div class="tds-field" style="min-width:12rem;flex:1 1 12rem">
					<label class="tds-label" for="tds-manifest-q">Search</label>
					<input class="tds-input" id="tds-manifest-q" type="search" placeholder="Code, vehicle, driver…">
				</div>
				<div class="tds-field" style="min-width:12rem">
					<label class="tds-label" for="tds-manifest-route">Route</label>
					<select class="tds-select" id="tds-manifest-route"><option value="">All routes</option></select>
				</div>
				<div class="tds-field" style="min-width:10rem">
					<label class="tds-label" for="tds-manifest-vehicle">Vehicle</label>
					<select class="tds-select" id="tds-manifest-vehicle"><option value="">All vehicles</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-manifest-from">From date</label>
					<input class="tds-input" id="tds-manifest-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-manifest-to">To date</label>
					<input class="tds-input" id="tds-manifest-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-manifest-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-manifest-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-manifest-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-manifest-success" hidden role="status"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-manifest-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading manifests…</p>
				</div>
				<div id="tds-manifest-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No manifests match</p>
					<p class="tds-empty-text">Widen the date range or register a new manifest.</p>
				</div>
				<div id="tds-manifest-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Id</th>
								<th>Route</th>
								<th>Vehicle</th>
								<th>Date</th>
								<th>Driver</th>
								<th>Conductor</th>
								<th>To pay</th>
								<th>Paid</th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tds-manifest-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-manifest-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-manifest-prev">Previous</button>
					<span class="tds-muted" id="tds-manifest-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-manifest-next">Next</button>
				</div>
			</div>

			<dialog class="tds-dialog" id="tds-manifest-dialog" aria-labelledby="tds-manifest-dialog-title">
				<form method="dialog" id="tds-manifest-form" class="tds-card" style="min-width:min(32rem,92vw);margin:0;border:0;box-shadow:none">
					<div class="tds-card-body">
						<h2 class="tds-section-title" id="tds-manifest-dialog-title">Register manifest</h2>
						<input type="hidden" id="tds-manifest-edit-id" value="">
						<div class="tds-field" style="margin-top:var(--tds-space-3)">
							<label class="tds-label" for="tds-manifest-form-route">Route</label>
							<select class="tds-select" id="tds-manifest-form-route" required></select>
						</div>
						<div class="tds-field">
							<label class="tds-label" for="tds-manifest-form-vehicle">Vehicle</label>
							<select class="tds-select" id="tds-manifest-form-vehicle" required></select>
						</div>
						<div class="tds-toolbar" style="align-items:flex-end">
							<div class="tds-field" style="flex:1">
								<label class="tds-label" for="tds-manifest-form-driver">Driver</label>
								<input class="tds-input" id="tds-manifest-form-driver" type="text">
							</div>
							<div class="tds-field" style="flex:1">
								<label class="tds-label" for="tds-manifest-form-conductor">Conductor</label>
								<input class="tds-input" id="tds-manifest-form-conductor" type="text">
							</div>
						</div>
						<div class="tds-field">
							<label class="tds-label" for="tds-manifest-form-date">Date</label>
							<input class="tds-input" id="tds-manifest-form-date" type="date" required>
						</div>
						<div class="tds-row" style="margin-top:var(--tds-space-4);justify-content:flex-end;gap:var(--tds-space-2)">
							<button type="button" class="tds-btn tds-btn-secondary" id="tds-manifest-cancel">Cancel</button>
							<button type="submit" class="tds-btn tds-btn-primary" id="tds-manifest-save">Save</button>
						</div>
					</div>
				</form>
			</dialog>

			<dialog class="tds-card" id="tds-manifest-remark" style="border:1px solid var(--tds-line-strong);border-radius:var(--tds-radius-md);padding:0;max-width:26rem;width:calc(100% - 2rem)">
				<div class="tds-card-body">
					<div class="tds-row" style="justify-content:space-between;align-items:center;margin-bottom:var(--tds-space-3)">
						<h2 class="tds-card-title" style="margin:0">Remark</h2>
						<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-manifest-remark-close">Close</button>
					</div>
					<p class="tds-muted" id="tds-manifest-remark-label" style="margin-top:0">Add a remark for this manifest</p>
					<input type="hidden" id="tds-manifest-remark-id">
					<div class="tds-field" style="margin-bottom:var(--tds-space-4)">
						<label class="tds-label" for="tds-manifest-remark-text">Remark</label>
						<textarea class="tds-input" id="tds-manifest-remark-text" rows="4" placeholder="Notes for this trip…"></textarea>
					</div>
					<button type="button" class="tds-btn tds-btn-primary" id="tds-manifest-remark-save">Save remark</button>
				</div>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/vehicle_view.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
