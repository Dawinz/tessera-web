<?php
/**
 * C-P0-01 modern Register luggage (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/register_luggage.js') ?: time();

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Register luggage</title>
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
		<div class="container-fluid tds-page" id="tds-reg-root" data-ui-slice="C-P0-01">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Register</span></p>
					<h1 class="tds-page-title">Register luggage</h1>
					<p class="tds-page-lead">Create parcels for a sender — paid, to be paid, or due payment.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=register_luggage.php&amp;ui_cargo_register_luggage=legacy">Use classic</a>
				</div>
			</div>

			<div class="tds-alert tds-alert-danger" id="tds-reg-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-reg-success" hidden role="status"></div>

			<div class="tds-kpi-grid" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-kpi">
					<p class="tds-kpi-label">Today paid</p>
					<p class="tds-kpi-value" id="tds-reg-paid">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Today to be paid</p>
					<p class="tds-kpi-value" id="tds-reg-tobe">—</p>
				</div>
				<div class="tds-kpi">
					<p class="tds-kpi-label">Today total</p>
					<p class="tds-kpi-value" id="tds-reg-total">—</p>
				</div>
			</div>

			<div class="tds-card" style="margin-bottom:var(--tds-space-4)">
				<div class="tds-card-body">
					<h2 class="tds-section-title" style="margin-bottom:var(--tds-space-3)">Sender</h2>
					<div class="tds-toolbar" style="align-items:flex-end">
						<div class="tds-field" style="min-width:12rem;flex:1">
							<label class="tds-label" for="tds-reg-sname">Full name</label>
							<input class="tds-input" id="tds-reg-sname" type="text" autocomplete="name" style="text-transform:uppercase">
						</div>
						<div class="tds-field">
							<label class="tds-label" for="tds-reg-scode">Code</label>
							<select class="tds-select" id="tds-reg-scode"></select>
						</div>
						<div class="tds-field" style="min-width:10rem;position:relative">
							<label class="tds-label" for="tds-reg-sphone">Phone</label>
							<input class="tds-input" id="tds-reg-sphone" type="tel" inputmode="numeric" maxlength="11" placeholder="7XXXXXXXX" autocomplete="off">
							<div id="tds-reg-phone-suggest" class="tds-card" hidden role="listbox" aria-label="Matching contacts" style="position:absolute;left:0;right:0;top:100%;z-index:20;margin-top:0.25rem;max-height:16rem;overflow:auto;box-shadow:var(--tds-shadow-md,0 8px 24px rgba(0,0,0,.18))"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="tds-row" style="justify-content:space-between;align-items:center;margin-bottom:var(--tds-space-3)">
				<h2 class="tds-section-title" style="margin:0">Receivers</h2>
				<button type="button" class="tds-btn tds-btn-secondary tds-btn-sm" id="tds-reg-add"><i class="uil uil-plus"></i> Add receiver</button>
			</div>
			<div id="tds-reg-receivers"></div>

			<div class="tds-row" style="margin-top:var(--tds-space-4);gap:var(--tds-space-3)">
				<button type="button" class="tds-btn tds-btn-primary" id="tds-reg-submit">Register</button>
				<span class="tds-muted" id="tds-reg-agent" style="align-self:center"></span>
			</div>

			<div class="tds-card" id="tds-reg-receipts" hidden style="margin-top:var(--tds-space-5)">
				<div class="tds-card-body">
					<h2 class="tds-section-title">Registered</h2>
					<div class="tds-table-wrap">
						<table class="tds-table">
							<thead>
								<tr>
									<th>Code</th>
									<th>Receiver</th>
									<th>Route</th>
									<th>Payment</th>
									<th>Amount</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="tds-reg-receipt-body"></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/register_luggage.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
