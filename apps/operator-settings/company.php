<?php
require_once __DIR__ . '/includes/boot.php';
tessera_require_capability($db, $username, 'settings');

$msg = null;
$err = null;
$reg = function_exists('tessera_registry_mysqli') ? tessera_registry_mysqli() : $db;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $reg instanceof mysqli) {
	$display = trim((string) ($_POST['display_name'] ?? ''));
	$legal = trim((string) ($_POST['legal_name'] ?? ''));
	$color = trim((string) ($_POST['primary_color'] ?? ''));
	$logo = trim((string) ($_POST['logo_url'] ?? ''));
	if ($display === '' || $legal === '') {
		$err = 'Display name and legal name are required.';
	} else {
		if ($color !== '' && !preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
			$err = 'Primary colour must be a hex value like #E53E3E.';
		} else {
			$tid = (int) $tenantId;
			$d = $reg->real_escape_string($display);
			$l = $reg->real_escape_string($legal);
			$c = $reg->real_escape_string($color);
			$g = $reg->real_escape_string($logo);
			$ok = @$reg->query(
				"UPDATE tessera_tenants SET
					display_name='{$d}',
					legal_name='{$l}',
					primary_color=" . ($color === '' ? 'NULL' : "'{$c}'") . ",
					logo_url=" . ($logo === '' ? 'NULL' : "'{$g}'") . "
				 WHERE id={$tid} LIMIT 1"
			);
			$msg = $ok ? 'Company profile saved.' : null;
			$err = $ok ? null : 'Could not save company profile.';
		}
	}
}

$tenant = function_exists('tessera_tenant_row') ? (tessera_tenant_row() ?: []) : [];
/* Re-read after save */
if ($msg && $reg instanceof mysqli) {
	$q = @$reg->query('SELECT * FROM tessera_tenants WHERE id=' . (int) $tenantId . ' LIMIT 1');
	$row = $q ? $q->fetch_assoc() : null;
	if ($row) {
		$tenant = $row;
	}
}

$settings_page_title = 'Company profile';
$settings_crumb = 'Company';
$settings_nav_active = 'company';
require_once __DIR__ . '/includes/layout.php';
?>
<div class="card">
	<div class="card-header">
		<h4 class="card-title mb-0"><i class="uil uil-building me-1"></i> Company profile</h4>
	</div>
	<div class="card-body">
		<p class="text-muted">How your bus company appears in the operator console. Domain and plan are managed by Tessera Control.</p>
		<?php if ($msg): ?><div class="alert alert-success"><?php echo settings_h($msg); ?></div><?php endif; ?>
		<?php if ($err): ?><div class="alert alert-danger"><?php echo settings_h($err); ?></div><?php endif; ?>
		<form method="post" class="row g-3">
			<div class="col-md-6">
				<label class="form-label">Display name</label>
				<input class="form-control" name="display_name" required value="<?php echo settings_h($tenant['display_name'] ?? ''); ?>">
			</div>
			<div class="col-md-6">
				<label class="form-label">Legal name</label>
				<input class="form-control" name="legal_name" required value="<?php echo settings_h($tenant['legal_name'] ?? ''); ?>">
			</div>
			<div class="col-md-4">
				<label class="form-label">Slug</label>
				<input class="form-control" value="<?php echo settings_h($tenant['slug'] ?? ''); ?>" disabled>
			</div>
			<div class="col-md-4">
				<label class="form-label">Status</label>
				<input class="form-control" value="<?php echo settings_h($tenant['status'] ?? ''); ?>" disabled>
			</div>
			<div class="col-md-4">
				<label class="form-label">Plan</label>
				<input class="form-control" value="<?php echo settings_h($tenant['plan'] ?? ''); ?>" disabled>
			</div>
			<div class="col-md-4">
				<label class="form-label">Primary colour</label>
				<input class="form-control" name="primary_color" placeholder="#E53E3E" value="<?php echo settings_h($tenant['primary_color'] ?? ''); ?>">
			</div>
			<div class="col-md-8">
				<label class="form-label">Logo URL</label>
				<input class="form-control" name="logo_url" placeholder="https://…" value="<?php echo settings_h($tenant['logo_url'] ?? ''); ?>">
			</div>
			<div class="col-md-12">
				<label class="form-label">Primary domain</label>
				<input class="form-control" value="<?php echo settings_h($tenant['primary_domain'] ?? ''); ?>" disabled>
				<div class="form-text">DNS and custom domains are set in Tessera Control.</div>
			</div>
			<div class="col-12">
				<button class="btn btn-primary"><i class="uil uil-save me-1"></i> Save company profile</button>
			</div>
		</form>
	</div>
</div>
<?php settings_layout_end(); ?>
