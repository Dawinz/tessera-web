<?php
require_once __DIR__ . '/includes/auth_guard.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$case = control_get_case($db ?? null, !empty($control_db_ok), $id);
if (!$case) {
	http_response_code(404);
	echo 'Onboarding case not found.';
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_case') {
	$ids = $_POST['checklist_id'] ?? [];
	$labelsIn = $_POST['checklist_label'] ?? [];
	$doneIds = $_POST['checklist_done'] ?? [];
	if (!is_array($ids)) $ids = [];
	if (!is_array($labelsIn)) $labelsIn = [];
	if (!is_array($doneIds)) $doneIds = [];
	$checklist = [];
	foreach ($ids as $i => $cid) {
		$checklist[] = [
			'id' => (string) $cid,
			'label' => (string) ($labelsIn[$i] ?? $cid),
			'done' => in_array($cid, $doneIds, true),
		];
	}
	control_update_case($db ?? null, !empty($control_db_ok), $id, [
		'stage' => $_POST['stage'] ?? $case['stage'],
		'notes' => $_POST['notes'] ?? '',
		'go_live_date' => $_POST['go_live_date'] ?? '',
		'owner_label' => $_POST['owner_label'] ?? '',
		'hypercare_rota' => $_POST['hypercare_rota'] ?? '',
		'checklist' => $checklist,
	], $control_user['email']);
	header('Location: onboarding_case.php?id=' . $id . '&saved=1');
	exit;
}

$case = control_get_case($db ?? null, !empty($control_db_ok), $id);
$checklist = control_parse_checklist($case['checklist_json'] ?? null);
$done = count(array_filter($checklist, static fn($i) => !empty($i['done'])));
$tenant = !empty($case['tenant_id'])
	? control_get_tenant($db ?? null, !empty($control_db_ok), (int) $case['tenant_id'])
	: null;
$labels = control_stage_labels();
$pct = count($checklist) ? (int) round(($done / count($checklist)) * 100) : 0;

$__page_title = 'Onboarding · ' . $case['company_name'];
$control_nav = 'onboarding_case';
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<div>
				<h4 class="mb-1"><?php echo control_h($case['company_name']); ?></h4>
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="overview.php">Control</a></li>
					<li class="breadcrumb-item"><a href="onboarding.php">Onboarding</a></li>
					<li class="breadcrumb-item active">Case #<?php echo (int) $case['id']; ?></li>
				</ol>
			</div>
			<div class="d-flex gap-2 align-items-center">
				<span class="badge badge-soft-info"><?php echo control_h($labels[$case['stage']] ?? $case['stage']); ?></span>
				<?php if ($tenant): ?>
					<a class="btn btn-soft-secondary" href="operator.php?id=<?php echo (int) $tenant['id']; ?>">Open tenant</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php if (isset($_GET['saved'])): ?>
	<div class="alert alert-success alert-dismissible fade show">Case saved.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card mb-3">
	<div class="card-body">
		<div class="d-flex justify-content-between mb-2">
			<span class="text-muted">Checklist progress</span>
			<span class="fw-semibold"><?php echo $done; ?>/<?php echo count($checklist); ?> · <?php echo $pct; ?>%</span>
		</div>
		<div class="progress" style="height:8px">
			<div class="progress-bar bg-primary" style="width:<?php echo $pct; ?>%"></div>
		</div>
	</div>
</div>

<form method="post">
	<input type="hidden" name="action" value="update_case">
	<input type="hidden" name="id" value="<?php echo (int) $id; ?>">
	<div class="row">
		<div class="col-xl-7">
			<div class="card">
				<div class="card-header bg-transparent border-bottom">
					<h5 class="card-title mb-0">Enterprise checklist</h5>
				</div>
				<div class="card-body">
					<?php foreach ($checklist as $item): ?>
						<div class="form-check border rounded p-3 mb-2">
							<input class="form-check-input" type="checkbox" name="checklist_done[]" value="<?php echo control_h($item['id']); ?>" id="chk_<?php echo control_h($item['id']); ?>" <?php echo !empty($item['done']) ? 'checked' : ''; ?>>
							<input type="hidden" name="checklist_id[]" value="<?php echo control_h($item['id']); ?>">
							<input type="hidden" name="checklist_label[]" value="<?php echo control_h($item['label']); ?>">
							<label class="form-check-label" for="chk_<?php echo control_h($item['id']); ?>"><?php echo control_h($item['label']); ?></label>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<div class="col-xl-5">
			<div class="card">
				<div class="card-header bg-transparent border-bottom">
					<h5 class="card-title mb-0">Case details</h5>
				</div>
				<div class="card-body">
					<div class="mb-3">
						<label class="form-label">Stage</label>
						<select name="stage" class="form-select">
							<?php foreach ($labels as $key => $label): ?>
								<option value="<?php echo control_h($key); ?>" <?php echo $case['stage'] === $key ? 'selected' : ''; ?>><?php echo control_h($label); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label">Go-live date</label>
						<input type="date" name="go_live_date" class="form-control" value="<?php echo control_h($case['go_live_date'] ?? ''); ?>">
					</div>
					<div class="mb-3">
						<label class="form-label">Internal owner</label>
						<input type="text" name="owner_label" class="form-control" value="<?php echo control_h($case['owner_label'] ?? ''); ?>">
					</div>
					<div class="mb-3">
						<label class="form-label">Hypercare rota</label>
						<input type="text" name="hypercare_rota" class="form-control" value="<?php echo control_h($case['hypercare_rota'] ?? ''); ?>" placeholder="On-call name / shift">
					</div>
					<div class="mb-3">
						<label class="form-label">Notes</label>
						<textarea name="notes" class="form-control" rows="5"><?php echo control_h($case['notes'] ?? ''); ?></textarea>
					</div>
					<dl class="row small">
						<dt class="col-4 text-muted">Contact</dt>
						<dd class="col-8"><?php echo control_h($case['contact_name'] ?: '—'); ?><?php if (!empty($case['contact_email'])): ?><br><span class="text-muted"><?php echo control_h($case['contact_email']); ?></span><?php endif; ?></dd>
						<dt class="col-4 text-muted">Slug</dt>
						<dd class="col-8"><code><?php echo control_h($case['slug'] ?: '—'); ?></code></dd>
						<dt class="col-4 text-muted">Tenant</dt>
						<dd class="col-8"><?php echo $tenant ? '#' . (int) $tenant['id'] . ' · ' . control_h($tenant['status']) : 'Not linked'; ?></dd>
					</dl>
					<button type="submit" class="btn btn-primary w-100">Save case</button>
				</div>
			</div>
		</div>
	</div>
</form>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
