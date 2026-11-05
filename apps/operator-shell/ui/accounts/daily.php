<?php
/**
 * A-P0-03 modern Accounts Daily expenses (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed']);
$jsV = @filemtime(__DIR__ . '/daily.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Daily expenses</title>
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
		<div class="container-fluid tds-page" id="tds-acc-daily-root" data-ui-slice="A-P0-03">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Accounts</span><span>·</span><span>Cash book</span></p>
					<h1 class="tds-page-title">Daily expenses</h1>
					<p class="tds-page-lead">Office expenses against the route cash float. Add cash on Withdraw.</p>
				</div>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" id="tds-acc-daily-open-add">Add expense</button>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=daily.php&amp;ui_accounts_daily=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-kpi-grid" aria-label="Daily totals" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-kpi"><span class="tds-kpi-label">Lines</span><strong class="tds-kpi-value" id="tds-acc-daily-count">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Expenses</span><strong class="tds-kpi-value tds-mono" id="tds-acc-daily-expenses">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Cash remain</span><strong class="tds-kpi-value tds-mono" id="tds-acc-daily-cash">0</strong></div>
			</div>

			<div class="tds-toolbar tds-date-range" id="tds-acc-daily-filters" aria-label="Daily filters">
				<div class="tds-field" style="min-width:14rem">
					<label class="tds-label" for="tds-acc-daily-route">Route</label>
					<select class="tds-select" id="tds-acc-daily-route"></select>
				</div>
				<div class="tds-field" style="min-width:10rem">
					<label class="tds-label" for="tds-acc-daily-month">Month</label>
					<select class="tds-select" id="tds-acc-daily-month"></select>
				</div>
				<div class="tds-field" style="min-width:7rem">
					<label class="tds-label" for="tds-acc-daily-year">Year</label>
					<select class="tds-select" id="tds-acc-daily-year"></select>
				</div>
				<div class="tds-field" style="min-width:12rem;flex:1 1 12rem">
					<label class="tds-label" for="tds-acc-daily-q">Search</label>
					<input class="tds-input" id="tds-acc-daily-q" type="search" placeholder="Category, particular, detail…">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-acc-daily-apply">Search</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-acc-daily-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-acc-daily-success" hidden role="status"></div>
			<div class="tds-alert tds-alert-warning" id="tds-acc-daily-warn" hidden role="status"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-acc-daily-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading expenses…</p>
				</div>
				<div id="tds-acc-daily-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No expenses this month</p>
					<p class="tds-empty-text">Add an expense against the route cash float.</p>
				</div>
				<div id="tds-acc-daily-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Date</th>
								<th>Category</th>
								<th>Particular</th>
								<th>Vehicle</th>
								<th>Account</th>
								<th>Amount</th>
								<th>Balance</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tds-acc-daily-tbody"></tbody>
					</table>
				</div>
			</div>

			<dialog class="tds-dialog" id="tds-acc-daily-dialog" aria-labelledby="tds-acc-daily-dialog-title">
				<form method="dialog" id="tds-acc-daily-form" class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-daily-dialog-title">Add expense</h2>
					<input type="hidden" id="tds-acc-daily-edit-id" value="">
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-daily-form-category">Category</label>
						<select class="tds-select" id="tds-acc-daily-form-category" required></select>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-daily-form-particular">Particular</label>
						<select class="tds-select" id="tds-acc-daily-form-particular" required></select>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-daily-form-date">Date</label>
						<input class="tds-input" id="tds-acc-daily-form-date" type="date" required>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-daily-form-amount">Amount</label>
						<input class="tds-input" id="tds-acc-daily-form-amount" type="text" inputmode="decimal" required placeholder="0">
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-daily-form-account">Account</label>
						<select class="tds-select" id="tds-acc-daily-form-account">
							<option value="Cash">Cash</option>
							<option value="Cheque">Cheque</option>
						</select>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-daily-form-vehicle">Vehicle</label>
						<select class="tds-select" id="tds-acc-daily-form-vehicle">
							<option value="">None</option>
						</select>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-daily-form-detail">Detail</label>
						<input class="tds-input" id="tds-acc-daily-form-detail" type="text" placeholder="Optional note">
					</div>
					<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-4)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-daily-cancel">Cancel</button>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-acc-daily-save">Save</button>
					</div>
				</form>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/accounts/daily.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
