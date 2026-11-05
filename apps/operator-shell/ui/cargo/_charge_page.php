<?php
/**
 * Shared TDS shell for cargo charge variants. Set $charge before include.
 *
 * @var array{kind:string,root:string,slice:string,title:string,lead:string,crumb:string,classic:string} $charge
 */
declare(strict_types=1);

$charge = $charge ?? [];
$kind = (string) ($charge['kind'] ?? 'detail');
$root = (string) ($charge['root'] ?? 'tds-ops-charge-root');
$slice = (string) ($charge['slice'] ?? '');
$title = (string) ($charge['title'] ?? 'Charges');
$lead = (string) ($charge['lead'] ?? '');
$crumb = (string) ($charge['crumb'] ?? 'Charges');
$classic = (string) ($charge['classic'] ?? 'charges.php');
$flag = 'ui_cargo_' . preg_replace('/\.php$/', '', $classic);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/cargo_ops_charges.js') ?: time();

$showUser = in_array($kind, ['detail', 'summary', 'manifest-user'], true);
$showRoute = in_array($kind, ['detail', 'summary', 'multi'], true);
$showFromTo = in_array($kind, ['detail', 'summary', 'multi', 'manifest-mine'], true);
$showDate = $kind === 'manifest-user';
$showBus = in_array($kind, ['manifest-user', 'manifest-mine'], true);
$showQ = in_array($kind, ['detail', 'manifest-mine'], true);

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
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
		<div class="container-fluid tds-page" id="<?php echo htmlspecialchars($root, ENT_QUOTES, 'UTF-8'); ?>" data-kind="<?php echo htmlspecialchars($kind, ENT_QUOTES, 'UTF-8'); ?>" data-ui-slice="<?php echo htmlspecialchars($slice, ENT_QUOTES, 'UTF-8'); ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span><?php echo htmlspecialchars($crumb, ENT_QUOTES, 'UTF-8'); ?></span></p>
					<h1 class="tds-page-title"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h1>
					<p class="tds-page-lead"><?php echo htmlspecialchars($lead, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=<?php echo rawurlencode($classic); ?>&amp;<?php echo htmlspecialchars($flag, ENT_QUOTES, 'UTF-8'); ?>=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-toolbar tds-date-range" data-role="filters" aria-label="Charge filters">
				<div class="tds-field" data-role="user-wrap" <?php echo $showUser ? '' : 'hidden'; ?>>
					<label class="tds-label" for="<?php echo htmlspecialchars($root, ENT_QUOTES, 'UTF-8'); ?>-user">Seller</label>
					<select class="tds-select" data-role="user" id="<?php echo htmlspecialchars($root, ENT_QUOTES, 'UTF-8'); ?>-user"></select>
				</div>
				<div class="tds-field" data-role="route-wrap" <?php echo $showRoute ? '' : 'hidden'; ?> style="min-width:12rem">
					<label class="tds-label">Route</label>
					<select class="tds-select" data-role="route"><option value="">All my routes</option></select>
				</div>
				<div class="tds-field" data-role="bus-wrap" <?php echo $showBus ? '' : 'hidden'; ?> style="min-width:10rem">
					<label class="tds-label">Vehicle</label>
					<select class="tds-select" data-role="bus"><option value="">All vehicles</option></select>
				</div>
				<div class="tds-field" data-role="date-wrap" <?php echo $showDate ? '' : 'hidden'; ?>>
					<label class="tds-label">Trip date</label>
					<input class="tds-input" data-role="date" type="date">
				</div>
				<div class="tds-field" data-role="from-wrap" <?php echo $showFromTo ? '' : 'hidden'; ?>>
					<label class="tds-label">From date</label>
					<input class="tds-input" data-role="from" type="date">
				</div>
				<div class="tds-field" data-role="to-wrap" <?php echo $showFromTo ? '' : 'hidden'; ?>>
					<label class="tds-label">To date</label>
					<input class="tds-input" data-role="to" type="date">
				</div>
				<div class="tds-field" data-role="q-wrap" <?php echo $showQ ? '' : 'hidden'; ?> style="min-width:10rem;flex:1">
					<label class="tds-label">Search</label>
					<input class="tds-input" data-role="q" type="search" placeholder="Code, name…">
				</div>
				<button type="button" class="tds-btn tds-btn-primary" data-role="apply">Search</button>
			</div>

			<div class="tds-alert tds-alert-danger" data-role="error" hidden role="alert"></div>

			<div class="tds-kpi-grid" style="margin-top:var(--tds-space-4)">
				<div class="tds-kpi">
					<p class="tds-kpi-label">Seller</p>
					<p class="tds-kpi-value" data-role="seller" style="font-size:1.15rem">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label" data-role="count-label">Rows</p>
					<p class="tds-kpi-value" data-role="count">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Paid</p>
					<p class="tds-kpi-value" data-role="paid">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">To be paid</p>
					<p class="tds-kpi-value" data-role="tobe">—</p>
				</div>
			</div>

			<div class="tds-table-panel" style="margin-top:var(--tds-space-4)">
				<div data-role="loading" class="tds-empty" hidden><p class="tds-empty-title">Loading…</p></div>
				<div data-role="empty" class="tds-empty" hidden>
					<p class="tds-empty-title">Nothing in this range</p>
					<p class="tds-empty-text">Try another route, vehicle, or date window.</p>
				</div>
				<div data-role="wrap" class="tds-table-wrap" hidden>
					<table class="tds-table"><thead data-role="thead"></thead><tbody data-role="tbody"></tbody></table>
				</div>
				<div class="tds-pager" data-role="pager" hidden>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-role="prev">Previous</button>
					<span class="tds-muted" data-role="meta"></span>
					<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" data-role="next">Next</button>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/cargo_ops_charges.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
