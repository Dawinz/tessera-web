<?php
/**
 * S-04 Payouts — TDS payout profile + settlements.
 */
require_once __DIR__ . '/includes/boot.php';
tessera_require_capability($db, $username, 'payouts');

$settings_page_title = 'Payouts';
$settings_crumb = 'Payouts';
$settings_nav_active = 'payouts';
$settings_extra_css = [
	'/operator/css/tessera-design-tokens.css',
	'/operator/css/tessera-design-components.css',
];
require_once __DIR__ . '/includes/layout.php';

$jsV = @filemtime(__DIR__ . '/payouts.js') ?: time();
?>
<div class="tds-page" id="tds-settings-payouts-root" data-ui-slice="S-04" style="padding:0">
	<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-4)">
		<div>
			<p class="tds-breadcrumb"><span>Settings</span><span>·</span><span>Payouts</span></p>
			<h1 class="tds-page-title">Payouts</h1>
			<p class="tds-page-lead">Tessera collects customer payments for you and pays out to this bank or mobile money account. You do not need to connect a payment gateway here.</p>
		</div>
	</div>

	<div class="tds-alert tds-alert-danger" id="tds-settings-payouts-error" hidden role="alert"></div>
	<div class="tds-alert tds-alert-success" id="tds-settings-payouts-success" hidden role="status"></div>

	<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
		<div class="tds-toolbar">
			<strong>Payout details</strong>
			<span class="tds-badge" id="tds-payouts-status" style="margin-left:auto">—</span>
		</div>
		<form id="tds-settings-payouts-form" class="tds-dialog-body" style="padding:var(--tds-space-4)">
			<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap">
				<div class="tds-field" style="flex:1 1 12rem">
					<label class="tds-label" for="tds-payouts-method">Preferred method</label>
					<select class="tds-input" id="tds-payouts-method" name="preferred_method"></select>
				</div>
				<div class="tds-field" style="flex:1 1 12rem">
					<label class="tds-label" for="tds-payouts-momo-network">Mobile money network</label>
					<input class="tds-input" id="tds-payouts-momo-network" name="momo_network" placeholder="M-Pesa / Tigo Pesa / Airtel Money" autocomplete="off">
				</div>
				<div class="tds-field" style="flex:1 1 12rem">
					<label class="tds-label" for="tds-payouts-momo-msisdn">Mobile money number</label>
					<input class="tds-input" id="tds-payouts-momo-msisdn" name="momo_msisdn" autocomplete="off">
				</div>
				<div class="tds-field" style="flex:1 1 14rem">
					<label class="tds-label" for="tds-payouts-momo-name">Mobile money account name</label>
					<input class="tds-input" id="tds-payouts-momo-name" name="momo_account_name" autocomplete="off">
				</div>
			</div>
			<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap;margin-top:var(--tds-space-3)">
				<div class="tds-field" style="flex:1 1 12rem">
					<label class="tds-label" for="tds-payouts-bank-name">Bank name</label>
					<input class="tds-input" id="tds-payouts-bank-name" name="bank_name" autocomplete="off">
				</div>
				<div class="tds-field" style="flex:1 1 12rem">
					<label class="tds-label" for="tds-payouts-account-name">Account name</label>
					<input class="tds-input" id="tds-payouts-account-name" name="account_name" autocomplete="off">
				</div>
				<div class="tds-field" style="flex:1 1 12rem">
					<label class="tds-label" for="tds-payouts-account-number">Account number</label>
					<input class="tds-input" id="tds-payouts-account-number" name="account_number" autocomplete="off">
				</div>
				<div class="tds-field" style="flex:1 1 12rem">
					<label class="tds-label" for="tds-payouts-branch">Branch</label>
					<input class="tds-input" id="tds-payouts-branch" name="bank_branch" autocomplete="off">
				</div>
			</div>
			<div class="tds-field" style="margin-top:var(--tds-space-3)">
				<label class="tds-label" for="tds-payouts-notes">Notes</label>
				<textarea class="tds-input" id="tds-payouts-notes" name="notes" rows="2"></textarea>
			</div>
			<div class="tds-row" style="justify-content:flex-end;margin-top:var(--tds-space-4)">
				<button type="submit" class="tds-btn tds-btn-primary" id="tds-payouts-save">Save payout profile</button>
			</div>
		</form>
	</div>

	<div class="tds-table-panel">
		<div class="tds-toolbar"><strong>Settlements</strong></div>
		<p class="tds-muted" style="padding:0 var(--tds-space-4);margin:var(--tds-space-2) 0 0">Amounts Tessera owes you / has paid out.</p>
		<div class="table-responsive">
			<table class="tds-table">
				<thead>
					<tr>
						<th>Period</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Paid at</th>
					</tr>
				</thead>
				<tbody id="tds-payouts-settlements-tbody">
					<tr><td colspan="4" class="tds-muted">Loading…</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script src="/settings/payouts.js?v=<?php echo (int) $jsV; ?>"></script>
<?php settings_layout_end(); ?>
