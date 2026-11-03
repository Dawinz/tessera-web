<?php
require_once __DIR__ . '/includes/boot.php';
require_once $ticketsRoot . '/includes/tessera_payouts.php';
tessera_require_capability($db, $username, 'payouts');

$msg = null;
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$ok = tessera_save_tenant_payout($db, $tenantId, [
		'preferred_method' => $_POST['preferred_method'] ?? 'mobile_money',
		'bank_name' => $_POST['bank_name'] ?? '',
		'account_name' => $_POST['account_name'] ?? '',
		'account_number' => $_POST['account_number'] ?? '',
		'bank_branch' => $_POST['bank_branch'] ?? '',
		'momo_network' => $_POST['momo_network'] ?? '',
		'momo_msisdn' => $_POST['momo_msisdn'] ?? '',
		'momo_account_name' => $_POST['momo_account_name'] ?? '',
		'notes' => $_POST['notes'] ?? '',
	], $username, true);
	$msg = $ok ? 'Payout profile saved. Verification may be required before the next settlement.' : null;
	$err = $ok ? null : 'Could not save (ensure migration 011 is applied).';
}

$payout = tessera_get_tenant_payout($db, $tenantId);
$settlements = tessera_list_settlements($db, $tenantId, 15);
$settings_page_title = 'Payouts';
$settings_crumb = 'Payouts';
$settings_nav_active = 'payouts';
require_once __DIR__ . '/includes/layout.php';
?>
<div class="card">
	<div class="card-header">
		<h4 class="card-title mb-0"><i class="uil uil-money-withdraw me-1"></i> Payout details</h4>
	</div>
	<div class="card-body">
		<p class="text-muted">Tessera collects customer payments for you and pays out to this bank or mobile-money account. You do not need to connect a payment gateway here.</p>
		<?php if ($msg): ?><div class="alert alert-success alert-dismissible fade show"><?php echo settings_h($msg); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
		<?php if ($err): ?><div class="alert alert-danger"><?php echo settings_h($err); ?></div><?php endif; ?>
		<p class="mb-3">Status: <span class="badge badge-soft-<?php echo ($payout['verified_status'] ?? '') === 'verified' ? 'success' : 'warning'; ?>"><?php echo settings_h($payout['verified_status'] ?? 'unverified'); ?></span></p>
		<form method="post" class="row g-3">
			<div class="col-md-4">
				<label class="form-label">Preferred method</label>
				<select name="preferred_method" class="form-select">
					<option value="mobile_money" <?php echo ($payout['preferred_method'] ?? '') === 'mobile_money' ? 'selected' : ''; ?>>Mobile money</option>
					<option value="bank" <?php echo ($payout['preferred_method'] ?? '') === 'bank' ? 'selected' : ''; ?>>Bank transfer</option>
				</select>
			</div>
			<div class="col-md-4">
				<label class="form-label">Mobile money network</label>
				<input class="form-control" name="momo_network" value="<?php echo settings_h($payout['momo_network'] ?? ''); ?>" placeholder="M-Pesa / Tigo Pesa / Airtel Money">
			</div>
			<div class="col-md-4">
				<label class="form-label">Mobile money number</label>
				<input class="form-control" name="momo_msisdn" value="<?php echo settings_h($payout['momo_msisdn'] ?? ''); ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Mobile money account name</label>
				<input class="form-control" name="momo_account_name" value="<?php echo settings_h($payout['momo_account_name'] ?? ''); ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Bank name</label>
				<input class="form-control" name="bank_name" value="<?php echo settings_h($payout['bank_name'] ?? ''); ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Account name</label>
				<input class="form-control" name="account_name" value="<?php echo settings_h($payout['account_name'] ?? ''); ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Account number</label>
				<input class="form-control" name="account_number" value="<?php echo settings_h($payout['account_number'] ?? ''); ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Branch</label>
				<input class="form-control" name="bank_branch" value="<?php echo settings_h($payout['bank_branch'] ?? ''); ?>">
			</div>
			<div class="col-12">
				<label class="form-label">Notes</label>
				<textarea class="form-control" name="notes" rows="2"><?php echo settings_h($payout['notes'] ?? ''); ?></textarea>
			</div>
			<div class="col-12">
				<button class="btn btn-primary"><i class="uil uil-save me-1"></i> Save payout profile</button>
			</div>
		</form>
	</div>
</div>
<div class="card">
	<div class="card-header">
		<h4 class="card-title mb-0">Settlements</h4>
	</div>
	<div class="card-body">
		<p class="text-muted">Amounts Tessera owes you / has paid out.</p>
		<?php if (empty($settlements)): ?>
			<p class="text-muted mb-0">No settlements yet.</p>
		<?php else: ?>
			<div class="table-responsive">
				<table class="table table-centered table-nowrap mb-0">
					<thead class="table-light"><tr><th>Period</th><th>Amount</th><th>Status</th><th>Paid at</th></tr></thead>
					<tbody>
					<?php foreach ($settlements as $s): ?>
						<tr>
							<td><?php echo settings_h($s['period_label']); ?></td>
							<td><?php echo number_format((float) $s['amount_tzs'], 0); ?> <?php echo settings_h($s['currency']); ?></td>
							<td><?php echo settings_h($s['status']); ?></td>
							<td><?php echo settings_h($s['paid_at'] ?? '—'); ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php settings_layout_end(); ?>
