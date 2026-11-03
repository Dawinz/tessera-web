<?php
require_once __DIR__ . '/includes/auth_guard.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$created = control_create_case($db ?? null, !empty($control_db_ok), [
		'company_name' => $_POST['company_name'] ?? '',
		'slug' => $_POST['slug'] ?? '',
		'provision_tenant' => !empty($_POST['provision_tenant']),
		'contact_name' => $_POST['contact_name'] ?? '',
		'contact_email' => $_POST['contact_email'] ?? '',
		'contact_phone' => $_POST['contact_phone'] ?? '',
		'go_live_date' => $_POST['go_live_date'] ?? '',
		'owner_label' => $_POST['owner_label'] ?? '',
		'notes' => $_POST['notes'] ?? '',
	], $control_user['email']);
	if ($created) {
		header('Location: onboarding_case.php?id=' . (int) $created['id']);
		exit;
	}
	$error = 'Could not create case. Company name is required, and onboarding tables must exist for live writes.';
}

$__page_title = 'New onboarding | Tessera Control';
$control_nav = 'onboarding_new';
include __DIR__ . '/includes/control_layout_start.php';
?>

<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<div>
				<h4 class="mb-1">New onboarding case</h4>
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item"><a href="overview.php">Control</a></li>
					<li class="breadcrumb-item"><a href="onboarding.php">Onboarding</a></li>
					<li class="breadcrumb-item active">New</li>
				</ol>
			</div>
			<a href="onboarding.php" class="btn btn-soft-secondary">Back</a>
		</div>
	</div>
</div>

<?php if ($error): ?><div class="alert alert-danger"><?php echo control_h($error); ?></div><?php endif; ?>

<div class="row">
	<div class="col-xl-8">
		<div class="card">
			<div class="card-body">
				<form method="post">
					<div class="row g-3">
						<div class="col-md-8">
							<label class="form-label">Company name <span class="text-danger">*</span></label>
							<input type="text" name="company_name" class="form-control form-control-lg" required placeholder="Kilimanjaro Express">
						</div>
						<div class="col-md-4">
							<label class="form-label">Slug</label>
							<input type="text" name="slug" id="operator_slug" class="form-control form-control-lg" placeholder="kilimanjaro" pattern="[a-z0-9\-]*">
							<div class="form-text"><span id="slug_preview">your-slug.tessera.co.tz</span></div>
						</div>
						<div class="col-12">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" name="provision_tenant" id="provision_tenant" value="1" checked>
								<label class="form-check-label" for="provision_tenant">
									<strong>Provision tenant now</strong>
									<span class="d-block text-muted small">Creates <code>tessera_tenants</code> row in provisioning (slug required).</span>
								</label>
							</div>
						</div>
						<div class="col-md-6">
							<label class="form-label">Contact name</label>
							<input type="text" name="contact_name" class="form-control">
						</div>
						<div class="col-md-6">
							<label class="form-label">Contact phone</label>
							<input type="text" name="contact_phone" class="form-control">
						</div>
						<div class="col-md-6">
							<label class="form-label">Contact email</label>
							<input type="email" name="contact_email" class="form-control">
						</div>
						<div class="col-md-3">
							<label class="form-label">Go-live</label>
							<input type="date" name="go_live_date" class="form-control">
						</div>
						<div class="col-md-3">
							<label class="form-label">Owner</label>
							<input type="text" name="owner_label" class="form-control" placeholder="Dawson">
						</div>
						<div class="col-12">
							<label class="form-label">Notes</label>
							<textarea name="notes" class="form-control" rows="4"></textarea>
						</div>
					</div>
					<div class="mt-4 d-flex gap-2">
						<button type="submit" class="btn btn-primary btn-lg">Create case</button>
						<a href="onboarding.php" class="btn btn-soft-secondary btn-lg">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-xl-4">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Enterprise playbook</h5>
				<p class="text-muted mb-0">Kickoff → provision → migration → UAT → training → go-live → hypercare. See docs note <code>21 Enterprise onboarding</code>.</p>
			</div>
		</div>
	</div>
</div>

<?php include __DIR__ . '/includes/control_layout_end.php'; ?>
