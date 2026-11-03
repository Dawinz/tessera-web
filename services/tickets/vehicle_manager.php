<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/vehicle_seat_layout.php';

$preselectId = (int) ($_GET['vehicle'] ?? 0);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<?php $tessera_page_label = 'Vehicle manager'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/icons.min.css" rel="stylesheet">
	<link href="assets/css/app.min.css" rel="stylesheet">
	<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/vehicle-manager.css?v=<?php echo filemtime(__DIR__ . '/assets/css/vehicle-manager.css'); ?>">
</head>
<body class="vehicle-manager-page" data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">

<div id="layout-wrapper">
	<?php include 'top_header.php'; ?>

	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">

				<div class="row">
					<div class="col-12">
						<div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
							<div>
								<h4 class="mb-1">Vehicle manager</h4>
								<p class="text-muted mb-0 small">Fleet registry, seat layout designer, and trip assignments - all in one place.</p>
							</div>
							<div class="page-title-right d-flex gap-2">
								<button type="button" class="btn btn-primary btn-sm" id="vm-add-vehicle">
									<i class="uil uil-plus me-1"></i> Add vehicle
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="row g-3 vm-shell">
					<div class="col-lg-4 col-xl-3">
						<div class="card vm-fleet-card mb-0">
							<div class="card-header py-3">
								<label class="form-label small text-muted mb-1" for="vm-fleet-search">Fleet</label>
								<input type="search" id="vm-fleet-search" class="form-control form-control-sm" placeholder="Search plate, driver..." autocomplete="off">
							</div>
							<div class="card-body p-0">
								<div id="vm-fleet-list" class="vm-fleet-list" aria-live="polite">
									<div class="vm-fleet-empty text-muted small p-3">Loading vehicles...</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-8 col-xl-9">
						<div id="vm-workspace-empty" class="card vm-workspace-empty mb-0">
							<div class="card-body text-center py-5">
								<div class="vm-workspace-empty__icon mb-3"><i class="uil uil-bus"></i></div>
								<h5 class="mb-2">Select a vehicle</h5>
								<p class="text-muted mb-3">Choose a bus from the fleet list, or add a new one to get started.</p>
								<button type="button" class="btn btn-soft-primary btn-sm" id="vm-add-vehicle-empty"><i class="uil uil-plus"></i> Add vehicle</button>
							</div>
						</div>

						<div id="vm-workspace" class="d-none">
							<div class="card mb-3 vm-hero-card">
								<div class="card-body py-3">
									<div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
										<div>
											<div class="d-flex align-items-center gap-2 flex-wrap mb-1">
												<h5 class="mb-0" id="vm-hero-name">-</h5>
												<span class="badge vm-status-badge" id="vm-hero-status">-</span>
												<span class="badge bg-soft-info text-info" id="vm-hero-type">-</span>
											</div>
											<p class="text-muted small mb-0" id="vm-hero-meta">-</p>
										</div>
										<div class="d-flex gap-2 flex-wrap">
											<button type="button" class="btn btn-soft-danger btn-sm" id="vm-delete-vehicle"><i class="uil uil-trash-alt"></i> Delete</button>
											<button type="button" class="btn btn-soft-success btn-sm" id="vm-save-vehicle"><i class="uil uil-check"></i> Save details</button>
										</div>
									</div>
								</div>
							</div>

							<div class="card mb-0">
								<div class="card-header p-0 border-bottom-0">
									<ul class="nav nav-tabs nav-tabs-custom card-header-tabs mx-3 mt-2" role="tablist">
										<li class="nav-item" role="presentation">
											<button class="nav-link active" data-bs-toggle="tab" data-bs-target="#vm-tab-overview" type="button" role="tab">Overview</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" data-bs-toggle="tab" data-bs-target="#vm-tab-layout" type="button" role="tab">Seat layout</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" data-bs-toggle="tab" data-bs-target="#vm-tab-assignments" type="button" role="tab">Assignments</button>
										</li>
									</ul>
								</div>
								<div class="card-body tab-content pt-4">

									<div class="tab-pane fade show active" id="vm-tab-overview" role="tabpanel">
										<div class="row g-3">
											<div class="col-md-6">
												<label class="form-label" for="vm-field-name">Vehicle number / plate</label>
												<input type="text" class="form-control" id="vm-field-name">
											</div>
											<div class="col-md-3">
												<label class="form-label" for="vm-field-seats">Total seats</label>
												<input type="number" min="1" max="120" class="form-control" id="vm-field-seats">
											</div>
											<div class="col-md-3">
												<label class="form-label" for="vm-field-state">Vehicle type</label>
												<select class="form-select" id="vm-field-state">
													<option value="normal">Standard</option>
													<option value="vip">VIP</option>
												</select>
											</div>
											<div class="col-md-6">
												<label class="form-label" for="vm-field-driver">Default driver</label>
												<input type="text" class="form-control" id="vm-field-driver">
											</div>
											<div class="col-md-6">
												<label class="form-label" for="vm-field-conductor">Default conductor</label>
												<input type="text" class="form-control" id="vm-field-conductor">
											</div>
											<div class="col-md-6">
												<label class="form-label" for="vm-field-detail">Notes / model</label>
												<input type="text" class="form-control" id="vm-field-detail" placeholder="e.g. MAN diesel, 2022">
											</div>
											<div class="col-md-6">
												<label class="form-label" for="vm-field-dedicate">Dedicated seat label</label>
												<input type="text" class="form-control" id="vm-field-dedicate" placeholder="e.g. A1 - for premium fare rules">
												<div class="form-text">Optional seat used in VIP / dedicated pricing on some routes.</div>
											</div>
											<div class="col-12">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="vm-field-active" checked>
													<label class="form-check-label" for="vm-field-active">Active in fleet</label>
												</div>
											</div>
										</div>
										<hr class="border-secondary border-opacity-25 my-4">
										<div class="row g-3 vm-stats-row">
											<div class="col-sm-4">
												<div class="vm-stat-tile">
													<span class="vm-stat-tile__label">Schedule templates</span>
													<strong class="vm-stat-tile__value" id="vm-stat-templates">0</strong>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="vm-stat-tile">
													<span class="vm-stat-tile__label">Manifest rows</span>
													<strong class="vm-stat-tile__value" id="vm-stat-manifests">0</strong>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="vm-stat-tile">
													<span class="vm-stat-tile__label">Ticket bookings</span>
													<strong class="vm-stat-tile__value" id="vm-stat-bookings">0</strong>
												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane fade" id="vm-tab-layout" role="tabpanel">
										<div class="row g-4">
											<div class="col-xl-7">
												<div class="vm-layout-toolbar d-flex flex-wrap gap-2 align-items-center mb-3">
													<button type="button" class="btn btn-soft-secondary btn-sm" id="vm-layout-add-row"><i class="uil uil-plus"></i> Add row</button>
													<button type="button" class="btn btn-soft-secondary btn-sm" id="vm-layout-add-extra"><i class="uil uil-corner-down-right"></i> Extra seat</button>
													<div class="btn-group btn-group-sm flex-wrap" role="group" aria-label="Layout templates">
														<button type="button" class="btn btn-outline-secondary vm-layout-template" data-seats="37">37</button>
														<button type="button" class="btn btn-outline-secondary vm-layout-template" data-seats="41">41</button>
														<button type="button" class="btn btn-outline-secondary vm-layout-template" data-seats="45">45</button>
														<button type="button" class="btn btn-outline-secondary vm-layout-template" data-seats="47">47</button>
														<button type="button" class="btn btn-outline-secondary vm-layout-template" data-seats="49">49</button>
														<button type="button" class="btn btn-outline-secondary vm-layout-template" data-seats="53">53</button>
														<button type="button" class="btn btn-outline-secondary vm-layout-template" data-seats="57">57</button>
														<button type="button" class="btn btn-outline-secondary vm-layout-template" data-seats="59">59</button>
													</div>
													<button type="button" class="btn btn-soft-warning btn-sm ms-auto" id="vm-layout-reset"><i class="uil uil-redo"></i> Reset</button>
													<button type="button" class="btn btn-primary btn-sm" id="vm-layout-save"><i class="uil uil-save"></i> Save layout</button>
												</div>
												<p class="text-muted small mb-2">Drag rows to reorder. Drag seats between slots. Click a slot to cycle seat -> aisle -> remove.</p>
												<div class="vm-layout-canvas-wrap">
													<div class="vm-layout-front" aria-hidden="true"><i class="uil uil-arrow-right"></i> Front</div>
													<div id="vm-layout-canvas" class="vm-layout-canvas"></div>
													<div id="vm-layout-extras" class="vm-layout-extras"></div>
												</div>
											</div>
											<div class="col-xl-5">
												<div class="vm-layout-side card bg-transparent border border-secondary border-opacity-25 mb-3">
													<div class="card-body">
														<h6 class="mb-3">Layout summary</h6>
														<ul class="list-unstyled vm-layout-summary mb-0">
															<li><span>Capacity</span><strong id="vm-layout-capacity">-</strong></li>
															<li><span>Layout seats</span><strong id="vm-layout-count">-</strong></li>
															<li><span>Match</span><strong id="vm-layout-match" class="vm-layout-match">-</strong></li>
														</ul>
													</div>
												</div>
												<div class="vm-layout-preview card bg-transparent border border-secondary border-opacity-25">
													<div class="card-body">
														<h6 class="mb-3">Seat chart preview</h6>
														<div id="vm-layout-preview-chart" class="vm-layout-preview-chart" aria-hidden="false"></div>
														<div id="vm-layout-labels" class="vm-layout-labels small text-muted mt-3">-</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane fade" id="vm-tab-assignments" role="tabpanel">
										<div class="row g-4">
											<div class="col-lg-6">
												<h6 class="mb-3">Weekday schedule templates</h6>
												<div id="vm-assign-templates" class="vm-assign-list"></div>
												<p class="small text-muted mt-2 mb-0">Manage templates on <a href="register_trip.php">Register trip</a>.</p>
											</div>
											<div class="col-lg-6">
												<h6 class="mb-3">Recent manifests</h6>
												<div id="vm-assign-manifests" class="vm-assign-list"></div>
												<p class="small text-muted mt-2 mb-0">Open <a href="manifest.php">Manifest</a> for live trips.</p>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<footer class="footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-6"><script>document.write(new Date().getFullYear())</script> &copy; Tessera.</div>
				</div>
			</div>
		</footer>
	</div>
</div>

<div class="modal fade" id="vm-create-modal" tabindex="-1" aria-labelledby="vm-create-modal-label" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="vm-create-modal-label">Add vehicle</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3">
					<div class="col-12">
						<label class="form-label" for="vm-create-name">Vehicle number / plate</label>
						<input type="text" class="form-control" id="vm-create-name">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="vm-create-driver">Driver</label>
						<input type="text" class="form-control" id="vm-create-driver">
					</div>
					<div class="col-md-6">
						<label class="form-label" for="vm-create-conductor">Conductor</label>
						<input type="text" class="form-control" id="vm-create-conductor">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="vm-create-seats">Total seats</label>
						<input type="number" min="1" max="120" class="form-control" id="vm-create-seats" value="47">
					</div>
					<div class="col-md-4">
						<label class="form-label" for="vm-create-state">Vehicle type</label>
						<select class="form-select" id="vm-create-state">
							<option value="normal">Standard</option>
							<option value="vip">VIP</option>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label" for="vm-create-detail">Notes</label>
						<input type="text" class="form-control" id="vm-create-detail" placeholder="Optional">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="vm-create-submit">Create vehicle</button>
			</div>
		</div>
	</div>
</div>

<script src="assets/libs/jquery/jquery.min.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/bootstrap-jquery-bridge.js"></script>
<script src="assets/libs/metismenujs/metismenujs.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="assets/js/app.js"></script>
<script>
window.VM_PRESELECT_ID = <?php echo (int) $preselectId; ?>;
</script>
<script src="assets/js/vehicle_manager.js?v=<?php echo filemtime(__DIR__ . '/assets/js/vehicle_manager.js'); ?>"></script>
</body>
</html>
