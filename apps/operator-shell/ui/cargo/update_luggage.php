<?php
/**
 * C-P1-04 modern Update luggage (TDS).
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$jsV = @filemtime(__DIR__ . '/update_luggage.js') ?: time();
$luggageId = (int) ($_GET['u'] ?? $_GET['id'] ?? 0);

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Update luggage</title>
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
		<div class="container-fluid tds-page" id="tds-update-luggage-root" data-ui-slice="C-P1-04" data-luggage-id="<?php echo $luggageId; ?>">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Cargo</span><span>·</span><span>Update luggage</span></p>
					<h1 class="tds-page-title">Update luggage</h1>
					<p class="tds-page-lead">Edit a parcel that is not already on a manifest.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="/operator/app.php?module=cargo&amp;page=update_luggage.php&amp;ui_cargo_update_luggage=legacy<?php echo $luggageId ? '&amp;u=' . $luggageId : ''; ?>">Use classic</a>
				</div>
			</div>
			<div class="tds-toolbar">
				<div class="tds-field" style="min-width:12rem">
					<label class="tds-label" for="tds-upd-code">Find by code</label>
					<input class="tds-input" id="tds-upd-code" type="search" placeholder="Receipt code">
				</div>
				<button type="button" class="tds-btn tds-btn-secondary" id="tds-upd-find">Find</button>
			</div>
			<div class="tds-alert tds-alert-danger" id="tds-upd-error" hidden role="alert"></div>
			<div class="tds-alert tds-alert-success" id="tds-upd-ok" hidden role="status"></div>
			<div id="tds-upd-empty" class="tds-empty"><p class="tds-empty-title">Choose a luggage</p><p class="tds-empty-text">Open this page from My luggages, or search by receipt code.</p></div>
			<form id="tds-upd-form" class="tds-table-panel" hidden style="margin-top:var(--tds-space-4);padding:var(--tds-space-4)">
				<div class="tds-toolbar">
					<div class="tds-field"><label class="tds-label" for="tds-upd-sender">Sender</label><input class="tds-input" id="tds-upd-sender" required></div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-sphone">Sender phone</label><input class="tds-input" id="tds-upd-sphone"></div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-receiver">Receiver</label><input class="tds-input" id="tds-upd-receiver" required></div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-rphone">Receiver phone</label><input class="tds-input" id="tds-upd-rphone"></div>
				</div>
				<div class="tds-toolbar">
					<div class="tds-field" style="min-width:12rem"><label class="tds-label" for="tds-upd-route">Route</label><select class="tds-select" id="tds-upd-route"></select></div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-status">Payment</label>
						<select class="tds-select" id="tds-upd-status">
							<option value="paid">Paid</option>
							<option value="To be Payed">To be paid</option>
							<option value="Due payment">Due payment</option>
						</select>
					</div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-amount">Amount</label><input class="tds-input" id="tds-upd-amount" inputmode="decimal"></div>
					<div class="tds-field" id="tds-upd-due-wrap" hidden><label class="tds-label" for="tds-upd-due">Amount paid now</label><input class="tds-input" id="tds-upd-due" inputmode="decimal"></div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-qty">Quantity</label><input class="tds-input" id="tds-upd-qty" inputmode="numeric"></div>
				</div>
				<div class="tds-toolbar">
					<div class="tds-field"><label class="tds-label" for="tds-upd-wval">Weight</label><input class="tds-input" id="tds-upd-wval"></div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-wname">Unit</label>
						<select class="tds-select" id="tds-upd-wname"><option value="kg">kg</option><option value="g">g</option></select>
					</div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-lugg">Luggage value</label><input class="tds-input" id="tds-upd-lugg"></div>
					<div class="tds-field"><label class="tds-label" for="tds-upd-loan">Loan</label><input class="tds-input" id="tds-upd-loan" inputmode="decimal"></div>
				</div>
				<div class="tds-field">
					<label class="tds-label" for="tds-upd-detail">Detail</label>
					<textarea class="tds-input" id="tds-upd-detail" rows="3" style="height:auto;min-height:5.5rem;padding:var(--tds-space-2)"></textarea>
				</div>
				<div class="tds-row" style="margin-top:var(--tds-space-4)">
					<button type="submit" class="tds-btn tds-btn-primary" id="tds-upd-save">Save</button>
					<a class="tds-btn tds-btn-secondary" id="tds-upd-status-link" href="#">View status</a>
					<a class="tds-btn tds-btn-ghost" id="tds-upd-print" href="#" target="_blank" rel="noopener">Print</a>
				</div>
			</form>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__, 2) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="/operator/ui/cargo/update_luggage.js?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
