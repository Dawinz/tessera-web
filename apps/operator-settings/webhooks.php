<?php
require_once __DIR__ . '/includes/boot.php';
require_once $ticketsRoot . '/includes/tessera_operator_api.php';
tessera_require_capability($db, $username, 'api_keys');

$msg = null;
$err = null;
$newSecret = null;
$allEvents = [
	'booking.confirmed', 'booking.paid', 'booking.cancelled',
	'cargo.status_changed', 'payout.sent',
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = (string) ($_POST['action'] ?? '');
	if ($action === 'create') {
		$ev = array_values(array_filter((array) ($_POST['events'] ?? [])));
		$created = tessera_webhook_create($db, $tenantId, (string) ($_POST['url'] ?? ''), $ev ?: ['*'], $username);
		if ($created) {
			$newSecret = $created['secret'];
			$msg = 'Webhook registered. Copy the signing secret once.';
		} else {
			$err = 'Invalid HTTPS URL or migration 014 missing.';
		}
	} elseif ($action === 'disable') {
		tessera_webhook_disable($db, $tenantId, (int) ($_POST['id'] ?? 0));
		$msg = 'Webhook disabled.';
	}
}
$hooks = tessera_webhook_list($db, $tenantId);
$settings_page_title = 'Webhooks';
$settings_crumb = 'Webhooks';
$settings_nav_active = 'webhooks';
require_once __DIR__ . '/includes/layout.php';
?>
<div class="card">
	<div class="card-header"><h4 class="card-title mb-0"><i class="uil uil-link me-1"></i> Outbound webhooks</h4></div>
	<div class="card-body">
		<p class="text-muted">Tessera POSTs signed JSON to your HTTPS endpoint. Verify <code>X-Tessera-Signature: sha256=…</code> with your secret.</p>
		<?php if ($msg): ?><div class="alert alert-success"><?php echo settings_h($msg); ?></div><?php endif; ?>
		<?php if ($err): ?><div class="alert alert-danger"><?php echo settings_h($err); ?></div><?php endif; ?>
		<?php if ($newSecret): ?><div class="alert alert-warning"><strong>Signing secret (copy once):</strong><br><code><?php echo settings_h($newSecret); ?></code></div><?php endif; ?>
		<form method="post" class="vstack gap-3 mb-4">
			<input type="hidden" name="action" value="create">
			<div>
				<label class="form-label">HTTPS URL</label>
				<input type="url" class="form-control" name="url" placeholder="https://yoursite.example/hooks/tessera" required>
			</div>
			<div>
				<label class="form-label">Events</label>
				<div class="d-flex flex-wrap gap-2">
					<?php foreach ($allEvents as $ev): ?>
						<label class="font-size-12 mb-0"><input type="checkbox" name="events[]" value="<?php echo $ev; ?>" checked> <?php echo $ev; ?></label>
					<?php endforeach; ?>
				</div>
			</div>
			<button class="btn btn-primary align-self-start">Add webhook</button>
		</form>
		<div class="table-responsive">
			<table class="table table-centered table-nowrap mb-0">
				<thead class="table-light"><tr><th>URL</th><th>Events</th><th>Status</th><th></th></tr></thead>
				<tbody>
				<?php if (empty($hooks)): ?>
					<tr><td colspan="4" class="text-muted">None yet.</td></tr>
				<?php else: foreach ($hooks as $h): ?>
					<tr>
						<td class="font-size-12"><?php echo settings_h($h['url']); ?></td>
						<td class="font-size-12"><?php echo settings_h(implode(', ', $h['events'] ?? [])); ?></td>
						<td><?php echo settings_h($h['status']); ?></td>
						<td>
							<?php if ($h['status'] === 'active'): ?>
								<form method="post" class="d-inline">
									<input type="hidden" name="action" value="disable">
									<input type="hidden" name="id" value="<?php echo (int) $h['id']; ?>">
									<button class="btn btn-sm btn-soft-secondary">Disable</button>
								</form>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php settings_layout_end(); ?>
