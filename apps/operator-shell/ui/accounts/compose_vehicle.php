<?php
/**
 * A-P0-02 modern Accounts Compose vehicle ledger (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed']);
$jsV = @filemtime(__DIR__ . '/compose_vehicle.js') ?: time();

$route = (string) ($_GET['route_name'] ?? $_GET['route'] ?? '');
$bus = (string) ($_GET['bus'] ?? $_GET['vehicle'] ?? '');
$tripDate = (string) ($_GET['trip_date'] ?? $_GET['date'] ?? '');
$checked = (string) ($_GET['checked'] ?? '');

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}

$classicQs = http_build_query([
	'route_name' => $route,
	'bus' => $bus,
	'trip_date' => $tripDate,
	'checked' => $checked,
	'main_page' => 'manifest.php',
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Compose vehicle</title>
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
		<div class="container-fluid tds-page" id="tds-acc-compose-root" data-ui-slice="A-P0-02"
			data-route="<?php echo htmlspecialchars($route, ENT_QUOTES, 'UTF-8'); ?>"
			data-vehicle="<?php echo htmlspecialchars($bus, ENT_QUOTES, 'UTF-8'); ?>"
			data-date="<?php echo htmlspecialchars($tripDate, ENT_QUOTES, 'UTF-8'); ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Accounts</span><span>·</span><span>Compose</span></p>
					<h1 class="tds-page-title">Compose vehicle</h1>
					<p class="tds-page-lead" id="tds-acc-compose-lead">Trip income and expenses by boarding point.</p>
				</div>
				<div class="tds-row" style="flex-wrap:wrap;gap:var(--tds-space-2)">
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-acc-compose-lock">Lock</button>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-acc-compose-transfer">Transfer</button>
					<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-acc-compose-unlock" hidden>Unlock</button>
					<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-acc-compose-upload">Upload</button>
					<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-acc-compose-pull">Pull tickets &amp; cargo</button>
					<button type="button" class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-acc-compose-user-charge">User charges</button>
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=manifest.php">Manifests</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" id="tds-acc-compose-classic" href="/accounts/compose_vehicle.php?<?php echo htmlspecialchars($classicQs, ENT_QUOTES, 'UTF-8'); ?>">Use classic</a>
				</div>
			</div>

			<div class="tds-kpi-grid" id="tds-acc-compose-context" aria-label="Trip context" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-kpi"><span class="tds-kpi-label">Vehicle</span><strong class="tds-kpi-value" id="tds-acc-compose-vehicle">—</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Trip date</span><strong class="tds-kpi-value" id="tds-acc-compose-date">—</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Route</span><strong class="tds-kpi-value" id="tds-acc-compose-route">—</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Income</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-income">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Expenses</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-expenses">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Balance</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-balance">0</strong></div>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-acc-compose-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-acc-compose-success" hidden role="status"></div>
			<div class="tds-alert tds-alert-warning" id="tds-acc-compose-warn" hidden role="status"></div>

			<div class="tds-row" style="align-items:flex-start;gap:var(--tds-space-4);flex-wrap:wrap">
				<aside style="flex:0 0 12rem;min-width:10rem;max-width:16rem">
					<div class="tds-table-panel">
						<div class="tds-toolbar" style="justify-content:space-between">
							<strong>Points</strong>
						</div>
						<nav id="tds-acc-compose-points" class="tds-row" style="flex-direction:column;align-items:stretch;gap:var(--tds-space-2);padding:var(--tds-space-3)" aria-label="Boarding points"></nav>
					</div>
				</aside>

				<section style="flex:1 1 28rem;min-width:18rem">
					<div class="tds-toolbar tds-date-range" style="margin-bottom:var(--tds-space-3)">
						<div class="tds-field" style="min-width:12rem;flex:1 1 12rem">
							<label class="tds-label" for="tds-acc-compose-q">Search</label>
							<input class="tds-input" id="tds-acc-compose-q" type="search" placeholder="Category, particular, detail…">
						</div>
						<div class="tds-field" style="min-width:10rem">
							<label class="tds-label" for="tds-acc-compose-fx">Display currency</label>
							<select class="tds-select" id="tds-acc-compose-fx" aria-label="Foreign exchange display"></select>
						</div>
						<button type="button" class="tds-btn tds-btn-primary" id="tds-acc-compose-add-income">Income</button>
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-compose-add-expense">Expense</button>
					</div>

					<div class="tds-kpi-grid" style="margin-bottom:var(--tds-space-3)">
						<div class="tds-kpi"><span class="tds-kpi-label">Point income</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-point-income">0</strong></div>
						<div class="tds-kpi"><span class="tds-kpi-label">Point expenses</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-point-expenses">0</strong></div>
						<div class="tds-kpi"><span class="tds-kpi-label">Point balance</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-point-balance">0</strong></div>
						<div class="tds-kpi"><span class="tds-kpi-label">Lines</span><strong class="tds-kpi-value" id="tds-acc-compose-point-count">0</strong></div>
					</div>

					<div class="tds-table-panel">
						<div id="tds-acc-compose-loading" class="tds-empty" hidden>
							<p class="tds-empty-title">Loading ledger…</p>
						</div>
						<div id="tds-acc-compose-empty" class="tds-empty" hidden>
							<p class="tds-empty-title">No ledger lines</p>
							<p class="tds-empty-text">Add income or an expense for this boarding point.</p>
						</div>
						<div id="tds-acc-compose-wrap" class="tds-table-wrap" hidden>
							<table class="tds-table">
								<thead>
									<tr>
										<th></th>
										<th>Type</th>
										<th>Category</th>
										<th>Particular</th>
										<th>Amount</th>
										<th>Currency</th>
										<th>Detail</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="tds-acc-compose-tbody"></tbody>
							</table>
						</div>
					</div>
				</section>
			</div>

			<dialog class="tds-dialog" id="tds-acc-compose-dialog" aria-labelledby="tds-acc-compose-dialog-title">
				<form method="dialog" id="tds-acc-compose-form" class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-compose-dialog-title">Add income</h2>
					<input type="hidden" id="tds-acc-compose-edit-id" value="">
					<input type="hidden" id="tds-acc-compose-form-type" value="income">
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-form-category">Category</label>
						<select class="tds-select" id="tds-acc-compose-form-category" required></select>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-form-particular">Particular</label>
						<select class="tds-select" id="tds-acc-compose-form-particular" required></select>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-form-amount">Amount</label>
						<input class="tds-input" id="tds-acc-compose-form-amount" type="text" inputmode="decimal" required placeholder="0">
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-form-currency">Currency</label>
						<select class="tds-select" id="tds-acc-compose-form-currency"></select>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-form-detail">Detail</label>
						<input class="tds-input" id="tds-acc-compose-form-detail" type="text" placeholder="Optional note">
					</div>
					<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-4)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-compose-cancel">Cancel</button>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-acc-compose-save">Save</button>
					</div>
				</form>
			</dialog>

			<dialog class="tds-dialog" id="tds-acc-compose-lock-dialog" aria-labelledby="tds-acc-compose-lock-title">
				<form method="dialog" id="tds-acc-compose-lock-form" class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-compose-lock-title">Lock manifest</h2>
					<p class="tds-muted">Sets a password and marks this trip analysed so edits freeze.</p>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-lock-password">Password</label>
						<input class="tds-input" id="tds-acc-compose-lock-password" type="password" autocomplete="new-password" required>
					</div>
					<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-4)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-compose-lock-cancel">Cancel</button>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-acc-compose-lock-save">Lock</button>
					</div>
				</form>
			</dialog>

			<dialog class="tds-dialog" id="tds-acc-compose-transfer-dialog" aria-labelledby="tds-acc-compose-transfer-title">
				<form method="dialog" id="tds-acc-compose-transfer-form" class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-compose-transfer-title">Transfer lines</h2>
					<p class="tds-muted">Move the checked ledger lines onto another trip. Locked trips stay put.</p>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-transfer-target">Target trip</label>
						<select class="tds-select" id="tds-acc-compose-transfer-target" required></select>
					</div>
					<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-4)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-compose-transfer-cancel">Cancel</button>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-acc-compose-transfer-save">Transfer</button>
					</div>
				</form>
			</dialog>

			<dialog class="tds-dialog" id="tds-acc-compose-upload-dialog" aria-labelledby="tds-acc-compose-upload-title">
				<div class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-compose-upload-title">Attachments</h2>
					<p class="tds-muted">Proof files for this trip (receipts, scans).</p>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-file">Choose file</label>
						<input class="tds-input" id="tds-acc-compose-file" type="file">
					</div>
					<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-3)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-compose-upload-close">Close</button>
						<button type="button" class="tds-btn tds-btn-primary" id="tds-acc-compose-upload-save">Upload</button>
					</div>
					<div id="tds-acc-compose-attachments" class="tds-stack" style="margin-top:var(--tds-space-4);gap:var(--tds-space-2)"></div>
				</div>
			</dialog>

			<dialog class="tds-dialog" id="tds-acc-compose-pull-dialog" aria-labelledby="tds-acc-compose-pull-title">
				<form method="dialog" id="tds-acc-compose-pull-form" class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-compose-pull-title">Pull tickets &amp; cargo</h2>
					<p class="tds-muted">Loads ticket and cargo totals into this ledger for the selected cargo date.</p>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-compose-pull-date">Source trip date</label>
						<select class="tds-select" id="tds-acc-compose-pull-date" required></select>
					</div>
					<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-4)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-compose-pull-cancel">Cancel</button>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-acc-compose-pull-save">Pull</button>
					</div>
				</form>
			</dialog>

			<dialog class="tds-dialog" id="tds-acc-compose-charges-dialog" aria-labelledby="tds-acc-compose-charges-title">
				<div class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-compose-charges-title">User charges</h2>
					<p class="tds-muted">Cargo uploader paid / to-be for this boarding route.</p>
					<div class="tds-kpi-grid" style="margin-bottom:var(--tds-space-3)">
						<div class="tds-kpi"><span class="tds-kpi-label">Paid</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-charges-paid">0</strong></div>
						<div class="tds-kpi"><span class="tds-kpi-label">To pay</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-charges-topay">0</strong></div>
						<div class="tds-kpi"><span class="tds-kpi-label">Due</span><strong class="tds-kpi-value tds-mono" id="tds-acc-compose-charges-due">0</strong></div>
					</div>
					<div class="tds-table-wrap">
						<table class="tds-table">
							<thead><tr><th>Uploader</th><th>Paid</th><th>To be</th></tr></thead>
							<tbody id="tds-acc-compose-charges-tbody"></tbody>
						</table>
					</div>
					<div class="tds-row" style="justify-content:flex-end;margin-top:var(--tds-space-4)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-compose-charges-close">Close</button>
					</div>
				</div>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/accounts/compose_vehicle.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
