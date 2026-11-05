<?php
/**
 * Shared TDS shell for Accounts reports (A-P1-18…25).
 *
 * Expects: $reportTitle, $reportLead, $reportRoot, $reportSlice, $reportPage, $reportFlag, $reportKind
 * Optional: $reportCrumb, $reportFields (list of q|route|vehicle|currency|dates|as_of|vehicle_category|ledger)
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed']);
$reportTitle = $reportTitle ?? 'Report';
$reportLead = $reportLead ?? '';
$reportRoot = $reportRoot ?? 'tds-acc-report';
$reportSlice = $reportSlice ?? '';
$reportPage = $reportPage ?? 'report_trips.php';
$reportFlag = $reportFlag ?? 'report_trips';
$reportKind = $reportKind ?? '';
$reportCrumb = $reportCrumb ?? 'Reports';
$reportFields = $reportFields ?? ['route', 'currency', 'dates'];
$jsV = @filemtime(__DIR__ . '/reports.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}

$legacy = '/operator/app.php?module=accounts&page=' . rawurlencode($reportPage)
	. '&ui_accounts_' . rawurlencode($reportFlag) . '=legacy';

$reportNav = [
	['vehicle_report.php', 'Vehicle statement'],
	['account_vehicles.php', 'Vehicle grouped'],
	['account_filter.php', 'Category & particular'],
	['report_cash_flow.php', 'Cash flow'],
	['report_revenue.php', 'Revenue'],
	['report_expenses.php', 'Expense'],
	['report_payables.php', 'Payables'],
	['report_currency.php', 'Currency position'],
	['report_trips.php', 'Trip profitability'],
	['report_profit_loss.php', 'Profit & loss'],
];

$show = static function (string $name) use ($reportFields): bool {
	return in_array($name, $reportFields, true);
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo htmlspecialchars($reportTitle, ENT_QUOTES, 'UTF-8'); ?></title>
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
		<div class="container-fluid tds-page" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>" data-ui-slice="<?php echo htmlspecialchars($reportSlice, ENT_QUOTES, 'UTF-8'); ?>" data-report="<?php echo htmlspecialchars($reportKind, ENT_QUOTES, 'UTF-8'); ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Accounts</span><span>·</span><span><?php echo htmlspecialchars($reportCrumb, ENT_QUOTES, 'UTF-8'); ?></span></p>
					<h1 class="tds-page-title"><?php echo htmlspecialchars($reportTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
					<p class="tds-page-lead"><?php echo htmlspecialchars($reportLead, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="<?php echo htmlspecialchars($legacy, ENT_QUOTES, 'UTF-8'); ?>">Use classic</a>
				</div>
			</div>

			<div class="tds-row" style="flex-wrap:wrap;margin-bottom:var(--tds-space-4)" aria-label="Accounts reports">
<?php foreach ($reportNav as $nav):
	$href = '/operator/app.php?module=accounts&page=' . rawurlencode($nav[0]);
	$current = $nav[0] === $reportPage;
	?>
				<a class="tds-btn tds-btn-sm <?php echo $current ? 'tds-btn-primary' : 'tds-btn-secondary'; ?>" href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>><?php echo htmlspecialchars($nav[1], ENT_QUOTES, 'UTF-8'); ?></a>
<?php endforeach; ?>
			</div>

			<p class="tds-muted" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-period" style="margin-bottom:var(--tds-space-3)"></p>

			<div class="tds-kpi-grid" aria-label="Report totals" style="margin-bottom:var(--tds-space-4)">
<?php for ($i = 0; $i < 4; $i++): ?>
				<div class="tds-kpi"><span class="tds-kpi-label" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-k<?php echo $i; ?>l"></span><strong class="tds-kpi-value tds-mono" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-k<?php echo $i; ?>v">—</strong></div>
<?php endfor; ?>
			</div>

			<div class="tds-toolbar tds-date-range" aria-label="Report filters">
<?php if ($show('q')): ?>
				<div class="tds-field" style="min-width:12rem;flex:1 1 12rem">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-q">Search</label>
					<input class="tds-input" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-q" type="search" placeholder="Vehicle, route, or line…">
				</div>
<?php endif; ?>
<?php if ($show('route')): ?>
				<div class="tds-field" style="min-width:14rem">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-route">Route</label>
					<select class="tds-select" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-route"><option value="">All routes</option></select>
				</div>
<?php endif; ?>
<?php if ($show('vehicle')): ?>
				<div class="tds-field" style="min-width:10rem">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-vehicle">Vehicle</label>
					<select class="tds-select" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-vehicle"><option value="">All vehicles</option></select>
				</div>
<?php endif; ?>
<?php if ($show('vehicle_category')): ?>
				<div class="tds-field" style="min-width:12rem">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-vcategory">Vehicle group</label>
					<select class="tds-select" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-vcategory"><option value="">All groups</option></select>
				</div>
<?php endif; ?>
<?php if ($show('currency')): ?>
				<div class="tds-field" style="min-width:8rem">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-currency">Currency</label>
					<select class="tds-select" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-currency"></select>
				</div>
<?php endif; ?>
<?php if ($show('ledger')): ?>
				<div class="tds-field" style="min-width:10rem">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-mode">Show</label>
					<select class="tds-select" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-mode">
						<option value="category">Categories</option>
						<option value="particular">Particulars</option>
					</select>
				</div>
				<div class="tds-field" style="min-width:16rem;flex:1 1 16rem">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-lines">Lines</label>
					<select class="tds-select" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-lines" multiple size="4"></select>
				</div>
<?php endif; ?>
<?php if ($show('dates')): ?>
				<div class="tds-field">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-from">From date</label>
					<input class="tds-input" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-from" type="date">
				</div>
				<div class="tds-field">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-to">To date</label>
					<input class="tds-input" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-to" type="date">
				</div>
<?php endif; ?>
<?php if ($show('as_of')): ?>
				<div class="tds-field">
					<label class="tds-label" for="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-asof">As of</label>
					<input class="tds-input" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-asof" type="date">
				</div>
<?php endif; ?>
				<button type="button" class="tds-btn tds-btn-primary" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-apply">Search</button>
				<button type="button" class="tds-btn tds-btn-secondary" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-reset">Reset</button>
			</div>

			<div class="tds-alert tds-alert-danger" id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-error" hidden role="alert"></div>

			<div id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-extra"></div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-loading" class="tds-empty" hidden>
					<p class="tds-empty-title">Loading report…</p>
				</div>
				<div id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-empty" class="tds-empty" hidden>
					<p class="tds-empty-title">Nothing for these filters</p>
					<p class="tds-empty-text">Widen the dates or clear a route, vehicle, or line.</p>
				</div>
				<div id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-wrap" class="tds-table-wrap" hidden>
					<table class="tds-table">
						<thead id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-head"></thead>
						<tbody id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-body"></tbody>
						<tfoot id="<?php echo htmlspecialchars($reportRoot, ENT_QUOTES, 'UTF-8'); ?>-foot"></tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/accounts/reports.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
