<?php
/**
 * T-P0-04 modern Manifest (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/manifest.js') ?: time();

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
		<div class="container-fluid tds-page" id="tds-manifest-root" data-ui-slice="T-P0-04">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tickets</span><span>·</span><span>Manifest</span></p>
					<h1 class="tds-page-title">Manifest</h1>
					<p class="tds-page-lead">Daily departures, crew, seats sold, and ticket revenue.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=manifest.php&amp;ui_tickets_manifest=legacy">Use classic manifest</a>
				</div>
			</div>

			<div class="tds-kpi-grid" id="tds-manifest-kpis" aria-live="polite">
				<div class="tds-kpi"><p class="tds-kpi-label">Trips</p><p class="tds-kpi-value" data-kpi="trips">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Vehicles</p><p class="tds-kpi-value" data-kpi="buses">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Seats sold</p><p class="tds-kpi-value" data-kpi="seats">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Ticket sales</p><p class="tds-kpi-value" data-kpi="sales">—</p></div>
				<div class="tds-kpi"><p class="tds-kpi-label">Road collection</p><p class="tds-kpi-value" data-kpi="road">—</p></div>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-manifest-filters" aria-label="Manifest filters">
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-manifest-q">Search</label>
					<input class="tds-input" id="tds-manifest-q" type="search" placeholder="Date, vehicle, route, crew…">
				</div>
				<div class="tds-field" style="min-width:12rem">
					<label class="tds-label" for="tds-manifest-route">Route</label>
					<select class="tds-select" id="tds-manifest-route"><option value="">All routes</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-manifest-bus">Vehicle</label>
					<select class="tds-select" id="tds-manifest-bus"><option value="">All vehicles</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-manifest-from">From date</label>
					<input class="tds-input" id="tds-manifest-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-manifest-to">To date</label>
					<input class="tds-input" id="tds-manifest-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-manifest-apply">Apply</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-manifest-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-manifest-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-manifest-success" hidden role="status"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-manifest-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading manifests…</p>
					<span class="tds-skeleton" style="width:12rem;margin:0.75rem auto;display:block"></span>
				</div>
				<div id="tds-manifest-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No manifests match</p>
					<p class="tds-muted">Adjust route, vehicle, or date range.</p>
				</div>
				<div class="table-responsive" id="tds-manifest-wrap">
					<table class="tds-table" id="tds-manifest-table">
						<thead>
							<tr>
								<th>Departure</th>
								<th>Vehicle</th>
								<th>Route</th>
								<th>Driver</th>
								<th>Conductor</th>
								<th>Seats</th>
								<th>Ticket sales</th>
								<th>Road</th>
								<th>Gross</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody id="tds-manifest-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-manifest-pager" hidden>
					<p class="tds-pager-meta" id="tds-manifest-meta"></p>
					<div class="tds-row">
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-manifest-prev">Previous</button>
						<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-manifest-next">Next</button>
					</div>
				</div>
			</div>

			<dialog class="tds-card" id="tds-manifest-edit" style="border:1px solid var(--tds-line-strong);border-radius:var(--tds-radius-md);padding:0;max-width:26rem;width:calc(100% - 2rem)">
				<div class="tds-card-header">
					<h2 class="tds-card-title">Update manifest</h2>
					<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-manifest-edit-close">Close</button>
				</div>
				<div class="tds-card-body">
					<input type="hidden" id="tds-edit-id">
					<div class="tds-stack">
						<div class="tds-field">
							<label class="tds-label" for="tds-edit-bus">Vehicle</label>
							<select class="tds-select" id="tds-edit-bus"></select>
						</div>
						<div class="tds-field">
							<label class="tds-label" for="tds-edit-driver">Driver</label>
							<input class="tds-input" id="tds-edit-driver" type="text" autocomplete="off">
						</div>
						<div class="tds-field">
							<label class="tds-label" for="tds-edit-conductor">Conductor</label>
							<input class="tds-input" id="tds-edit-conductor" type="text" autocomplete="off">
						</div>
						<button type="button" class="tds-btn tds-btn-primary" id="tds-edit-save">Save changes</button>
					</div>
				</div>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<script src="/operator/ui/tickets/manifest.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
