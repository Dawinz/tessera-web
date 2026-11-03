<?php
require_once __DIR__ . '/includes/auth_guard.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = (string) ($_POST['action'] ?? '');
	if ($action === 'mark_all') {
		control_mark_notifications_read($db ?? null, !empty($control_db_ok), null);
		header('Location: notifications.php?cleared=1');
		exit;
	}
	if ($action === 'mark_one') {
		control_mark_notifications_read($db ?? null, !empty($control_db_ok), (int) ($_POST['id'] ?? 0));
		header('Location: notifications.php');
		exit;
	}
	if ($action === 'scan' && control_can('manage_ops')) {
		control_alerts_scan($db ?? null, !empty($control_db_ok), true);
		header('Location: notifications.php?scan=1');
		exit;
	}
}

$allRows = control_list_notifications($db ?? null, !empty($control_db_ok), ['limit' => 200]);
$unread = control_unread_notification_count($db ?? null, !empty($control_db_ok));
$page = control_page_int('page', 1);
$pag = control_paginate($allRows, $page, 12);
$rows = $pag['items'];

$severityCounts = ['danger' => 0, 'warning' => 0, 'info' => 0, 'success' => 0];
foreach ($allRows as $n) {
	$s = (string) ($n['severity'] ?? 'info');
	if (isset($severityCounts[$s])) {
		$severityCounts[$s]++;
	}
}

$__page_title = 'Notifications | Tessera Control';
$control_nav = 'notifications';
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="control-page">
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
			<div>
				<h4 class="mb-1">Notifications</h4>
				<p class="text-muted mb-0 font-size-13">Platform alerts · suspend · webhooks</p>
			</div>
			<div class="d-flex gap-2">
				<?php if (control_can('manage_ops')): ?>
					<form method="post"><input type="hidden" name="action" value="scan"><button class="btn btn-soft-secondary btn-sm" type="submit">Scan now</button></form>
				<?php endif; ?>
				<?php if ($unread > 0): ?>
					<form method="post"><input type="hidden" name="action" value="mark_all"><button class="btn btn-primary btn-sm" type="submit">Mark all read</button></form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php if (isset($_GET['cleared'])): ?><div class="alert alert-success alert-dismissible fade show py-2">All marked read.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if (isset($_GET['scan'])): ?><div class="alert alert-success alert-dismissible fade show py-2">Alert scan completed.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="row g-3 mb-3">
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Unread</div>
		<div class="control-kpi-value text-<?php echo $unread ? 'danger' : 'body'; ?>"><?php echo (int) $unread; ?></div>
	</div></div></div>
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Total</div>
		<div class="control-kpi-value"><?php echo $pag['total']; ?></div>
	</div></div></div>
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Critical</div>
		<div class="control-kpi-value"><?php echo $severityCounts['danger']; ?></div>
	</div></div></div>
	<div class="col-md-3"><div class="card control-kpi-card h-100"><div class="card-body">
		<div class="control-kpi-label">Warnings</div>
		<div class="control-kpi-value"><?php echo $severityCounts['warning']; ?></div>
	</div></div></div>
</div>

<div class="card control-table-panel">
	<div class="card-header bg-transparent border-bottom d-flex justify-content-between">
		<h5 class="control-panel-title mb-0">Inbox</h5>
		<span class="badge badge-soft-<?php echo $unread ? 'danger' : 'secondary'; ?>"><?php echo (int) $unread; ?> unread</span>
	</div>
	<?php if (!$rows): ?>
		<p class="text-muted p-3 mb-0">No notifications yet.</p>
	<?php else: ?>
		<ul class="list-group list-group-flush">
			<?php foreach ($rows as $n): ?>
				<li class="list-group-item d-flex justify-content-between align-items-start gap-3 py-2 <?php echo empty($n['read_at']) ? '' : 'opacity-75'; ?>">
					<div class="min-w-0">
						<div class="d-flex align-items-center gap-2 mb-1">
							<span class="badge badge-soft-<?php echo control_h($n['severity']); ?>"><?php echo control_h($n['type']); ?></span>
							<?php if (empty($n['read_at'])): ?><span class="badge badge-soft-primary">new</span><?php endif; ?>
						</div>
						<div class="fw-semibold font-size-13"><?php echo control_h($n['title']); ?></div>
						<?php if (!empty($n['body'])): ?><div class="text-muted font-size-12 text-truncate"><?php echo control_h($n['body']); ?></div><?php endif; ?>
						<div class="text-muted font-size-11 mt-1"><?php echo control_h($n['created_at']); ?></div>
					</div>
					<div class="d-flex flex-column gap-1 flex-shrink-0">
						<?php if (!empty($n['href'])): ?><a class="btn btn-sm btn-soft-primary" href="<?php echo control_h($n['href']); ?>">Open</a><?php endif; ?>
						<?php if (empty($n['read_at'])): ?>
							<form method="post">
								<input type="hidden" name="action" value="mark_one">
								<input type="hidden" name="id" value="<?php echo (int) $n['id']; ?>">
								<button class="btn btn-sm btn-soft-secondary" type="submit">Read</button>
							</form>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php if ($pag['pages'] > 1): ?>
		<div class="card-footer"><?php control_render_pagination($pag, []); ?></div>
		<?php endif; ?>
	<?php endif; ?>
</div>
</div>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
