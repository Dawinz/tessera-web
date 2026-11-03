<?php
require_once __DIR__ . '/includes/boot.php';
require_once $ticketsRoot . '/includes/tessera_operator_api.php';
tessera_require_capability($db, $username, 'api_keys');

$msg = null;
$err = null;
$newKey = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = (string) ($_POST['action'] ?? '');
	if ($action === 'create') {
		$scopes = array_values(array_filter((array) ($_POST['scopes'] ?? [])));
		if (empty($scopes)) {
			$scopes = ['tickets:read', 'tickets:write', 'cargo:read'];
		}
		$created = tessera_api_key_create($db, $tenantId, (string) ($_POST['name'] ?? 'Default'), $scopes, $username);
		if ($created) {
			$newKey = $created['key'];
			$msg = 'API key created. Copy it now — it will not be shown again.';
		} else {
			$err = 'Could not create key (migration 014?).';
		}
	} elseif ($action === 'revoke') {
		$msg = tessera_api_key_revoke($db, $tenantId, (int) ($_POST['id'] ?? 0)) ? 'Key revoked.' : null;
		$err = $msg ? null : 'Revoke failed.';
	}
}
$keys = tessera_api_key_list($db, $tenantId);
$base = rtrim((string) (getenv('TESSERA_PUBLIC_URL') ?: 'https://tessera.co.tz'), '/');
$settings_page_title = 'API keys';
$settings_crumb = 'API keys';
$settings_nav_active = 'api';
require_once __DIR__ . '/includes/layout.php';
?>
<div class="card">
	<div class="card-header"><h4 class="card-title mb-0"><i class="uil uil-key-skeleton me-1"></i> API keys</h4></div>
	<div class="card-body">
		<p class="text-muted">Authenticate requests to <code><?php echo settings_h($base); ?>/tickets/api/v1/</code> with <code>Authorization: Bearer tess_…</code>.</p>
		<?php if ($msg): ?><div class="alert alert-success"><?php echo settings_h($msg); ?></div><?php endif; ?>
		<?php if ($err): ?><div class="alert alert-danger"><?php echo settings_h($err); ?></div><?php endif; ?>
		<?php if ($newKey): ?><div class="alert alert-warning"><strong>New key (copy once):</strong><br><code><?php echo settings_h($newKey); ?></code></div><?php endif; ?>
		<form method="post" class="row g-2 align-items-end mb-4">
			<input type="hidden" name="action" value="create">
			<div class="col-md-4">
				<label class="form-label">Name</label>
				<input class="form-control" name="name" value="Website" required>
			</div>
			<div class="col-md-6">
				<label class="form-label">Scopes</label>
				<div class="d-flex flex-wrap gap-2">
					<?php foreach (['tickets:read','tickets:write','cargo:read','cargo:write','webhooks:manage'] as $sc): ?>
						<label class="font-size-12 mb-0"><input type="checkbox" name="scopes[]" value="<?php echo $sc; ?>" checked> <?php echo $sc; ?></label>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="col-md-2"><button class="btn btn-primary w-100">Create</button></div>
		</form>
		<div class="table-responsive">
			<table class="table table-centered table-nowrap mb-0">
				<thead class="table-light"><tr><th>Name</th><th>Prefix</th><th>Status</th><th>Last used</th><th></th></tr></thead>
				<tbody>
				<?php if (empty($keys)): ?>
					<tr><td colspan="5" class="text-muted">No keys yet.</td></tr>
				<?php else: foreach ($keys as $k): ?>
					<tr>
						<td><?php echo settings_h($k['name']); ?></td>
						<td><code><?php echo settings_h($k['key_prefix']); ?>…</code></td>
						<td><?php echo settings_h($k['status']); ?></td>
						<td><?php echo settings_h($k['last_used_at'] ?? '—'); ?></td>
						<td>
							<?php if ($k['status'] === 'active'): ?>
								<form method="post" class="d-inline" onsubmit="return confirm('Revoke this key?');">
									<input type="hidden" name="action" value="revoke">
									<input type="hidden" name="id" value="<?php echo (int) $k['id']; ?>">
									<button class="btn btn-sm btn-soft-danger">Revoke</button>
								</form>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>
		<p class="text-muted font-size-13 mb-0 mt-3">Also configure <a href="webhooks.php">Webhooks</a>.</p>
	</div>
</div>
<?php settings_layout_end(); ?>
