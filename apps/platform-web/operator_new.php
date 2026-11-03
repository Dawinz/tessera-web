<?php
require_once __DIR__ . '/includes/auth_guard.php';

$error = '';
$old = [
	'name' => '',
	'slug' => '',
	'plan' => 'standard',
	'start_onboarding' => '1',
	'contact_name' => '',
	'contact_email' => '',
	'notes' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$old = [
		'name' => trim((string) ($_POST['name'] ?? '')),
		'slug' => strtolower(trim((string) ($_POST['slug'] ?? ''))),
		'plan' => trim((string) ($_POST['plan'] ?? 'standard')),
		'start_onboarding' => !empty($_POST['start_onboarding']) ? '1' : '',
		'contact_name' => trim((string) ($_POST['contact_name'] ?? '')),
		'contact_email' => trim((string) ($_POST['contact_email'] ?? '')),
		'notes' => trim((string) ($_POST['notes'] ?? '')),
	];

	$created = control_create_tenant($db ?? null, !empty($control_db_ok), [
		'slug' => $old['slug'],
		'name' => $old['name'],
		'plan' => $old['plan'],
	], $control_user['email']);

	if ($created) {
		if ($old['start_onboarding']) {
			$case = control_create_case($db ?? null, !empty($control_db_ok), [
				'company_name' => $old['name'],
				'slug' => $old['slug'],
				'provision_tenant' => false,
				'contact_name' => $old['contact_name'],
				'contact_email' => $old['contact_email'],
				'notes' => $old['notes'],
				'owner_label' => $control_user['name'],
			], $control_user['email']);
			/* Link case to the tenant we just created */
			if ($case && !empty($control_db_ok) && $db && control_table_exists($db, 'tessera_onboarding_cases')) {
				$cid = (int) $case['id'];
				$tid = (int) $created['id'];
				@$db->query("UPDATE tessera_onboarding_cases SET tenant_id={$tid}, stage='provisioning' WHERE id={$cid} LIMIT 1");
			}
		}
		header('Location: operator.php?id=' . (int) $created['id'] . '&created=1');
		exit;
	}
	$error = 'Could not create operator. Use a unique slug (a–z, 0–9, hyphens) and ensure tenant migrations are applied.';
}

$__page_title = 'Add operator | Tessera Control';
$control_nav = 'operator_new';
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<div>
				<h4 class="mb-1">Add operator</h4>
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="overview.php">Control</a></li>
					<li class="breadcrumb-item"><a href="operators.php">Operators</a></li>
					<li class="breadcrumb-item active">Add</li>
				</ol>
			</div>
			<a href="operators.php" class="btn btn-soft-secondary">Back to operators</a>
		</div>
	</div>
</div>

<?php if ($error): ?>
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<?php echo control_h($error); ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
	</div>
<?php endif; ?>

<div class="row">
	<div class="col-xl-8">
		<div class="card">
			<div class="card-header bg-transparent border-bottom">
				<h5 class="card-title mb-0">Operator details</h5>
			</div>
			<div class="card-body">
				<form method="post" novalidate>
					<div class="row g-3">
						<div class="col-md-8">
							<label class="form-label" for="operator_name">Legal / display name <span class="text-danger">*</span></label>
							<input type="text" class="form-control form-control-lg" id="operator_name" name="name" required value="<?php echo control_h($old['name']); ?>" placeholder="e.g. Abood Express">
							<div class="form-text">Shown in the operator staff console header.</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="plan">Plan</label>
							<select class="form-select form-select-lg" id="plan" name="plan">
								<?php foreach (['standard' => 'Standard', 'flagship' => 'Flagship', 'enterprise' => 'Enterprise'] as $val => $label): ?>
									<option value="<?php echo $val; ?>" <?php echo $old['plan'] === $val ? 'selected' : ''; ?>><?php echo $label; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-8">
							<label class="form-label" for="operator_slug">Slug <span class="text-danger">*</span></label>
							<div class="input-group input-group-lg">
								<input type="text" class="form-control" id="operator_slug" name="slug" required pattern="[a-z0-9\-]+" value="<?php echo control_h($old['slug']); ?>" placeholder="abood">
								<span class="input-group-text">.tessera.co.tz</span>
							</div>
							<div class="form-text">Public staff URL preview: <strong id="slug_preview">your-slug.tessera.co.tz</strong></div>
						</div>
						<div class="col-md-4">
							<label class="form-label">Initial status</label>
							<input type="text" class="form-control form-control-lg" value="Provisioning" disabled>
						</div>
					</div>

					<hr class="my-4">

					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" role="switch" id="start_onboarding" name="start_onboarding" value="1" <?php echo $old['start_onboarding'] ? 'checked' : ''; ?>>
						<label class="form-check-label" for="start_onboarding">
							<strong>Also create an onboarding case</strong>
							<span class="d-block text-muted small">Recommended — opens the enterprise checklist for this operator.</span>
						</label>
					</div>

					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label" for="contact_name">Primary contact</label>
							<input type="text" class="form-control" id="contact_name" name="contact_name" value="<?php echo control_h($old['contact_name']); ?>" placeholder="Ops lead">
						</div>
						<div class="col-md-6">
							<label class="form-label" for="contact_email">Contact email</label>
							<input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo control_h($old['contact_email']); ?>" placeholder="ops@company.co.tz">
						</div>
						<div class="col-12">
							<label class="form-label" for="notes">Notes</label>
							<textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Routes, MID status, brand kit, go-live target…"><?php echo control_h($old['notes']); ?></textarea>
						</div>
					</div>

					<div class="d-flex flex-wrap gap-2 mt-4">
						<button type="submit" class="btn btn-primary btn-lg"><i class="mdi mdi-account-plus me-1"></i> Create operator</button>
						<a href="operators.php" class="btn btn-soft-secondary btn-lg">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-xl-4">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">What happens next</h5>
				<ol class="ps-3 text-muted mb-0">
					<li class="mb-2">Tenant is created with status <code>provisioning</code>.</li>
					<li class="mb-2">Domain target: <code>{slug}.tessera.co.tz</code> (DNS later).</li>
					<li class="mb-2">Operator gets Tickets, Cargo, and Accounts modules.</li>
					<li class="mb-2">Use onboarding checklist through UAT → go-live.</li>
					<li>Activate the tenant when ready for production traffic.</li>
				</ol>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Flagship reference</h5>
				<p class="text-muted mb-2">SR Classic Coach is tenant <strong>#1</strong> on <code>tessera.co.tz</code>.</p>
				<a href="operator.php?id=1" class="btn btn-sm btn-soft-primary">Open SR Classic</a>
			</div>
		</div>
	</div>
</div>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
