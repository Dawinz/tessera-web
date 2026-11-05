<?php
/**
 * A-P0-04 modern Accounts Withdraw / cash deposits (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed']);
$jsV = @filemtime(__DIR__ . '/withdraw.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Withdraw</title>
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
		<div class="container-fluid tds-page" id="tds-acc-withdraw-root" data-ui-slice="A-P0-04">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Accounts</span><span>·</span><span>Cash book</span></p>
					<h1 class="tds-page-title">Withdraw</h1>
					<p class="tds-page-lead">Cash deposits that fund daily expenses. Daily spending is on Daily expenses.</p>
				</div>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" id="tds-acc-withdraw-open-add">Add cash</button>
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=daily.php">Daily expenses</a>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=accounts&amp;page=withdraw.php&amp;ui_accounts_withdraw=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-kpi-grid" aria-label="Cash totals" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-kpi"><span class="tds-kpi-label">Deposits</span><strong class="tds-kpi-value" id="tds-acc-withdraw-count">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Page deposited</span><strong class="tds-kpi-value tds-mono" id="tds-acc-withdraw-deposited">0</strong></div>
				<div class="tds-kpi"><span class="tds-kpi-label">Cash on hand</span><strong class="tds-kpi-value tds-mono" id="tds-acc-withdraw-remain">0</strong></div>
			</div>

			<div class="tds-toolbar tds-date-range" aria-label="Cash filters">
				<div class="tds-field" style="min-width:14rem;flex:1 1 14rem">
					<label class="tds-label" for="tds-acc-withdraw-route">Route</label>
					<select class="tds-select" id="tds-acc-withdraw-route">
						<option value="">All routes</option>
					</select>
				</div>
				<div class="tds-field" style="min-width:12rem;flex:1 1 12rem">
					<label class="tds-label" for="tds-acc-withdraw-q">Search</label>
					<input class="tds-input" id="tds-acc-withdraw-q" type="search" placeholder="Authorizer, issuer, date…">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" id="tds-acc-withdraw-apply">Search</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-acc-withdraw-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-acc-withdraw-success" hidden role="status"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="tds-acc-withdraw-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading cash…</p>
				</div>
				<div id="tds-acc-withdraw-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">No cash deposits</p>
					<p class="tds-empty-text">Add cash for a route to fund daily expenses.</p>
				</div>
				<div id="tds-acc-withdraw-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead>
							<tr>
								<th>Effective date</th>
								<th>Route</th>
								<th>Authorizer</th>
								<th>Cash</th>
								<th>Balance</th>
								<th>Issuer</th>
								<th>Inserted</th>
							</tr>
						</thead>
						<tbody id="tds-acc-withdraw-tbody"></tbody>
					</table>
				</div>
				<div class="tds-pager" id="tds-acc-withdraw-pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-acc-withdraw-prev">Previous</button>
					<span class="tds-muted" id="tds-acc-withdraw-meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-acc-withdraw-next">Next</button>
				</div>
			</div>

			<dialog class="tds-dialog" id="tds-acc-withdraw-dialog" aria-labelledby="tds-acc-withdraw-dialog-title">
				<form method="dialog" id="tds-acc-withdraw-form" class="tds-dialog-body">
					<h2 class="tds-dialog-title" id="tds-acc-withdraw-dialog-title">Add cash</h2>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-withdraw-form-route">Route</label>
						<select class="tds-select" id="tds-acc-withdraw-form-route" required></select>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-withdraw-form-date">Effective date</label>
						<input class="tds-input" id="tds-acc-withdraw-form-date" type="date" required>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-withdraw-form-amount">Amount</label>
						<input class="tds-input" id="tds-acc-withdraw-form-amount" type="text" inputmode="decimal" required placeholder="0">
					</div>
					<div class="tds-field">
						<label class="tds-label" for="tds-acc-withdraw-form-authorizer">Authorizer</label>
						<input class="tds-input" id="tds-acc-withdraw-form-authorizer" type="text" required placeholder="Who authorized">
					</div>
					<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-4)">
						<button type="button" class="tds-btn tds-btn-secondary" id="tds-acc-withdraw-cancel">Cancel</button>
						<button type="submit" class="tds-btn tds-btn-primary" id="tds-acc-withdraw-save">Save</button>
					</div>
				</form>
			</dialog>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/accounts/withdraw.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
