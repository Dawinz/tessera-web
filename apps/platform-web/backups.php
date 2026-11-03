<?php
require_once __DIR__ . '/includes/auth_guard.php';

$error = null;
$canOps = control_can('manage_ops');
$pack = control_list_backups(50);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $canOps) {
	if (($_POST['action'] ?? '') === 'run_backup') {
		$res = control_run_backup();
		control_audit($db ?? null, !empty($control_db_ok), $control_user['email'], 'ops.backup', null, [
			'ok' => !empty($res['ok']),
			'error' => $res['error'] ?? null,
		]);
		if (!empty($res['ok'])) {
			header('Location: backups.php?ok=1');
			exit;
		}
		$error = 'Backup failed: ' . ($res['error'] ?? 'unknown');
	}
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$error = 'Only owners can trigger backups.';
}

$pack = control_list_backups(50);
$totalBytes = array_sum(array_column($pack['rows'], 'size'));
$latest = $pack['rows'][0] ?? null;
$page = control_page_int('page', 1);
$pag = control_paginate($pack['rows'], $page, 10);

$__page_title = 'Backups | Tessera Control';
$control_nav = 'backups';
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
			<div>
				<h4 class="mb-1">Backups</h4>
				<p class="text-muted mb-0 font-size-13"><code>scripts/backup-mysql.sh</code> · <?php echo control_h($pack['dir']); ?></p>
			</div>
			<?php if ($canOps): ?>
				<form method="post">
					<input type="hidden" name="action" value="run_backup">
					<button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Run MySQL backup for ticket, cargo, account?');">Run backup now</button>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php if (isset($_GET['ok'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Backup completed.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><pre class="mb-0 small"><?php echo control_h($error); ?></pre></div><?php endif; ?>

<div class="row g-3 mb-3">
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Backup files</div>
			<div class="control-kpi-value"><?php echo count($pack['rows']); ?></div>
			<div class="control-kpi-meta">In storage directory</div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Latest</div>
			<div class="control-kpi-value font-size-16"><?php echo control_h($latest['mtime'] ?? '—'); ?></div>
			<div class="control-kpi-meta"><?php echo $latest ? control_h($latest['name']) : 'No files yet'; ?></div>
		</div></div>
	</div>
	<div class="col-md-4">
		<div class="card control-kpi-card h-100"><div class="card-body">
			<div class="control-kpi-label">Total size</div>
			<div class="control-kpi-value font-size-16"><?php echo control_h(control_format_bytes((int) $totalBytes)); ?></div>
			<div class="control-kpi-meta">All listed files</div>
		</div></div>
	</div>
</div>

<div class="card control-table-panel">
	<div class="card-header bg-transparent border-bottom d-flex justify-content-between">
		<h5 class="control-panel-title mb-0">Recent files</h5>
		<span class="badge badge-soft-secondary"><?php echo $pag['total']; ?> total</span>
	</div>
	<?php if (!$pack['exists']): ?>
		<p class="text-muted p-3 mb-0">Backup directory does not exist yet — run a backup to create it.</p>
	<?php elseif (!$pag['items']): ?>
		<p class="text-muted p-3 mb-0">No backup files found.</p>
	<?php else: ?>
		<div class="control-table-scroll" style="max-height:360px">
			<table class="table table-hover mb-0 table-control">
				<thead class="table-light sticky-top">
					<tr><th>File</th><th>Size</th><th>Modified</th></tr>
				</thead>
				<tbody>
					<?php foreach ($pag['items'] as $f): ?>
						<tr>
							<td><code class="font-size-12"><?php echo control_h($f['name']); ?></code></td>
							<td><?php echo control_h(control_format_bytes((int) $f['size'])); ?></td>
							<td class="text-muted font-size-12"><?php echo control_h($f['mtime']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php if ($pag['pages'] > 1): ?>
		<div class="card-footer"><?php control_render_pagination($pag, []); ?></div>
		<?php endif; ?>
	<?php endif; ?>
</div>
</div>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
