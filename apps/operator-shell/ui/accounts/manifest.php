<?php
/**
 * A-P0-01 modern Accounts Manifest list (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed']);
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
	<title>Manifests</title>
<?php if (!$isSpa): ?>
	<base href="/accounts/">
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
		<div class="container-fluid tds-page" id="tds-acc-manifest-root" data-ui-slice="A-P0-01">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Accounts</span><span>·</span><span>Manifests</span></p>
					<h1 class="tds-page-title">Manifests</h1>
					<p class="tds-page-lead">Trip ledger list — register and edit manifests. Insert opens modern compose. View, delete, and lock stay on classic pages.</p>
				</div>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" id="tds-acc-manifest-open-create">Add manifest</button>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=manifest.php&amp;ui_accounts_manifest=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-kpi-grid" id="tds-acc-manifest-summary" aria-label="Manifest totals" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-kpi"><span class="tds-kpi-label">Trips</span><strong class="tds-kpi-value" id="tds-acc-manifest-count">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Income</span><strong class="tds-kpi-value tds-mono" id="tds-acc-manifest-income">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Expenses</span><strong class="tds-kpi-value tds-mono" id="tds-acc-manifest-expenses">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Balance</span><strong class="tds-kpi-value tds-mono" id="tds-acc-manifest-balance">0</strong></div>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-acc-manifest-filters" aria-label="Manifest filters">
				<div class="tds-field" style="min-width:12rem;flex:1 1 12rem">
					<label class="tds-label" for="tds-acc-manifest-q">Search</label>
					<input class="tds-input" id="tds-acc-manifest-q" type="search" placeholder="Code, vehicle, route…">
				</div>
				<div class="tds-field" style="min-width:14rem">
					<label class="tds-label" for="tds-acc-manifest-route">Route</label>
					<select class="tds-select" id="tds-acc-manifest-route"><option value="">All routes</option></select>
				</div>
				<div class="tds-field" style="min-width:10rem">
					<label class="tds-label" for="tds-acc-manifest-vehicle">Vehicle</label>
					<select class="tds-select" id="tds-acc-manifest-vehicle"><option value="">All vehicles</option></select>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-acc-manifest-from">From date</label>
					<input class="tds-input" id="tds-acc-manifest-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-acc-manifest-to">To date</label>
					<input class="tds-input" id="tds-acc-manifest-to" type="date">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-acc-manifest-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-manifest-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-acc-manifest-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-acc-manifest-success" hidden role="status"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-acc-manifest-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading manifests…</p>
				</div>
				<div id="tds-acc-manifest-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No manifests match</p>
					<p class="tds-empty-text">Widen the date range or add a new manifest.</p>
				</div>
				<div id="tds-acc-manifest-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Code</th>
								<th>Route</th>
								<th>Vehicle</th>
								<th>Trip date</th>
								<th>Income</th>
								<th>Expenses</th>
								<th>Balance</th>
								<th>Status</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tds-acc-manifest-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-acc-manifest-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-acc-manifest-prev">Previous</button>
					<span class="tds-muted" id="tds-acc-manifest-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-acc-manifest-next">Next</button>
				</div>
			</div>

			<dialog class="tds-dialog" id="tds-acc-manifest-dialog" aria-labelledby="tds-acc-manifest-dialog-title">
				<form method="dialog" id="tds-acc-manifest-form" class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-manifest-dialog-title">Add manifest</h2>
					<input type="hidden" id="tds-acc-manifest-edit-id" value="">
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-manifest-form-route">Route</label>
						<select class="tds-select" id="tds-acc-manifest-form-route" required></select>
					</div>
					<div class="tds-field" id="tds-acc-manifest-date-wrap">
						<label class="tds-label" for="tds-acc-manifest-form-date">Trip date</label>
						<select class="tds-select" id="tds-acc-manifest-form-date" required>
							<option value="">Choose trip date</option>
						</select>
					</div>
					<div class="tds-field" id="tds-acc-manifest-date-edit-wrap" hidden>
						<label class="tds-label" for="tds-acc-manifest-form-date-edit">Trip date</label>
						<input class="tds-input" id="tds-acc-manifest-form-date-edit" type="date">
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-manifest-form-vehicle">Vehicle</label>
						<select class="tds-select" id="tds-acc-manifest-form-vehicle" required></select>
					</div>
					<div class="tds-field" id="tds-acc-manifest-type-wrap">
						<label class="tds-label" for="tds-acc-manifest-form-type">Type</label>
						<select class="tds-select" id="tds-acc-manifest-form-type">
							<option value="Bus">Bus</option>
							<option value="Truck">Truck</option>
						</select>
					</div>
					<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-4)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-manifest-cancel">Cancel</button>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-acc-manifest-save">Save</button>
					</div>
				</form>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/accounts/manifest.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
