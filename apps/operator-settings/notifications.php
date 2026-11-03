<?php
require_once __DIR__ . '/includes/boot.php';
require_once $ticketsRoot . '/includes/tessera_messaging.php';
tessera_require_capability($db, $username, 'notifications');

$msg = null;
$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$events = tessera_messaging_default_events();
	foreach ($events as $code => $chs) {
		foreach (array_keys($chs) as $ch) {
			$events[$code][$ch] = !empty($_POST['ev'][$code][$ch]);
		}
	}
	$ok = tessera_messaging_save($db, $tenantId, [
		'sms_enabled' => !empty($_POST['sms_enabled']),
		'sms_provider' => $_POST['sms_provider'] ?? '',
		'sms_sender_id' => $_POST['sms_sender_id'] ?? '',
		'sms_username' => $_POST['sms_username'] ?? '',
		'sms_api_key' => $_POST['sms_api_key'] ?? '',
		'whatsapp_enabled' => !empty($_POST['whatsapp_enabled']),
		'whatsapp_provider' => $_POST['whatsapp_provider'] ?? '',
		'whatsapp_instance' => $_POST['whatsapp_instance'] ?? '',
		'whatsapp_api_key' => $_POST['whatsapp_api_key'] ?? '',
		'push_enabled' => !empty($_POST['push_enabled']),
		'push_service_url' => $_POST['push_service_url'] ?? '',
		'push_api_key' => $_POST['push_api_key'] ?? '',
		'events' => $events,
	], $username);
	$msg = $ok ? 'Notification settings saved.' : null;
	$err = $ok ? null : 'Save failed (run migration 013).';
}
$cfg = tessera_messaging_get($db, $tenantId);
$settings_page_title = 'Notifications';
$settings_crumb = 'Notifications';
$settings_nav_active = 'notifications';
require_once __DIR__ . '/includes/layout.php';
?>
<div class="card">
	<div class="card-header"><h4 class="card-title mb-0"><i class="uil uil-bell me-1"></i> Channels</h4></div>
	<div class="card-body">
		<p class="text-muted">Use your own SMS / WhatsApp credentials. Push applies if you ship a customer app. Secrets are encrypted at rest.</p>
		<?php if ($msg): ?><div class="alert alert-success"><?php echo settings_h($msg); ?></div><?php endif; ?>
		<?php if ($err): ?><div class="alert alert-danger"><?php echo settings_h($err); ?></div><?php endif; ?>
		<form method="post" class="vstack gap-4" autocomplete="off">
			<section>
				<div class="form-check form-switch mb-2">
					<input class="form-check-input" type="checkbox" name="sms_enabled" value="1" id="sms_enabled" <?php echo !empty($cfg['sms_enabled']) ? 'checked' : ''; ?>>
					<label class="form-check-label" for="sms_enabled"><strong>SMS</strong></label>
				</div>
				<div class="row g-2">
					<div class="col-md-4">
						<label class="form-label">Provider</label>
						<select name="sms_provider" class="form-select">
							<?php foreach (['nextsms' => 'NextSMS', 'mshastra' => 'Mshastra', 'other' => 'Other'] as $k => $lab): ?>
								<option value="<?php echo $k; ?>" <?php echo ($cfg['sms_provider'] ?? '') === $k ? 'selected' : ''; ?>><?php echo $lab; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label">Sender ID</label>
						<input class="form-control" name="sms_sender_id" value="<?php echo settings_h($cfg['sms_sender_id'] ?? ''); ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label">API username</label>
						<input class="form-control" name="sms_username" value="<?php echo settings_h($cfg['sms_username'] ?? ''); ?>">
					</div>
					<div class="col-md-6">
						<label class="form-label">API key <?php echo !empty($cfg['sms_api_key_last4']) ? '(ends …' . settings_h($cfg['sms_api_key_last4']) . ')' : ''; ?></label>
						<input type="password" class="form-control" name="sms_api_key" placeholder="Leave blank to keep">
					</div>
				</div>
			</section>
			<section>
				<div class="form-check form-switch mb-2">
					<input class="form-check-input" type="checkbox" name="whatsapp_enabled" value="1" id="wa_enabled" <?php echo !empty($cfg['whatsapp_enabled']) ? 'checked' : ''; ?>>
					<label class="form-check-label" for="wa_enabled"><strong>WhatsApp</strong> (when enabled)</label>
				</div>
				<div class="row g-2">
					<div class="col-md-4">
						<label class="form-label">Provider</label>
						<input class="form-control" name="whatsapp_provider" value="<?php echo settings_h($cfg['whatsapp_provider'] ?? ''); ?>" placeholder="ultramsg / other">
					</div>
					<div class="col-md-4">
						<label class="form-label">Instance</label>
						<input class="form-control" name="whatsapp_instance" value="<?php echo settings_h($cfg['whatsapp_instance'] ?? ''); ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label">API token</label>
						<input type="password" class="form-control" name="whatsapp_api_key" placeholder="Leave blank to keep">
					</div>
				</div>
			</section>
			<section>
				<div class="form-check form-switch mb-2">
					<input class="form-check-input" type="checkbox" name="push_enabled" value="1" id="push_enabled" <?php echo !empty($cfg['push_enabled']) ? 'checked' : ''; ?>>
					<label class="form-check-label" for="push_enabled"><strong>App push</strong></label>
				</div>
				<div class="row g-2">
					<div class="col-md-8">
						<label class="form-label">Push service URL</label>
						<input class="form-control" name="push_service_url" value="<?php echo settings_h($cfg['push_service_url'] ?? ''); ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label">Push API key</label>
						<input type="password" class="form-control" name="push_api_key" placeholder="Leave blank to keep">
					</div>
				</div>
			</section>
			<section>
				<h5 class="font-size-14 mb-2">Events</h5>
				<div class="table-responsive">
					<table class="table table-sm table-nowrap mb-0">
						<thead class="table-light"><tr><th>Event</th><th>SMS</th><th>WhatsApp</th><th>Push</th></tr></thead>
						<tbody>
						<?php foreach (($cfg['events'] ?? tessera_messaging_default_events()) as $code => $chs): ?>
							<tr>
								<td><code><?php echo settings_h($code); ?></code></td>
								<?php foreach (['sms','whatsapp','push'] as $ch): ?>
									<td><input type="checkbox" class="form-check-input" name="ev[<?php echo settings_h($code); ?>][<?php echo $ch; ?>]" value="1" <?php echo !empty($chs[$ch]) ? 'checked' : ''; ?>></td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</section>
			<button class="btn btn-primary align-self-start"><i class="uil uil-save me-1"></i> Save notifications</button>
		</form>
	</div>
</div>
<?php settings_layout_end(); ?>
