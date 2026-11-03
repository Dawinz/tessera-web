<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<?php $tessera_page_label = 'Luggage checkout'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Tessera operator console" name="description" />
	<meta content="Tessera" name="author" />
	<link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
	<link rel="stylesheet" href="css/datapicker/datepicker3.css">
	<link rel="stylesheet" type="text/css" href="css/buttons.css">
	<link rel="stylesheet" type="text/css" href="css/datatable.css">
	<style>
		/* Checkout page - readable status rows + Dashonic chrome */
		.sr-checkout-page .page-title-box {
			padding-bottom: 0.75rem;
			margin-bottom: 0.5rem;
		}
		.sr-checkout-page .sr-filter-row {
			gap: 0.75rem 1rem;
			margin-bottom: 1rem;
		}
		.sr-checkout-page .sr-filter-row .form-label {
			margin-bottom: 0.35rem;
		}
		.sr-checkout-page #myInputTextField {
			display: block !important;
			visibility: visible !important;
			position: static !important;
			width: 100%;
			max-width: 360px;
			opacity: 1 !important;
			z-index: auto;
			margin: 0;
		}
		/* Code search above replaces the built-in DT search */
		.sr-checkout-page .dataTables_filter,
		.sr-checkout-page .dt-search {
			display: none !important;
		}
		.sr-checkout-page table.dataTable tbody tr,
		.sr-checkout-page table.dataTable tbody tr > td,
		.sr-checkout-page table.dataTable tbody tr > th {
			color: #1c2430 !important;
		}
		.sr-checkout-page table.dataTable tbody tr.sr-row-paid {
			background-color: #d1fae5 !important;
		}
		.sr-checkout-page table.dataTable tbody tr.sr-row-due {
			background-color: #ffe4ec !important;
		}
		.sr-checkout-page table.dataTable tbody tr.sr-row-open {
			background-color: #ffffff !important;
		}
		.sr-checkout-page table.dataTable tbody tr.sr-row-out {
			text-decoration: line-through;
			text-decoration-thickness: 2px;
			text-decoration-color: #dc3545;
		}
		.sr-checkout-page .dt-paging button.dt-paging-button,
		.sr-checkout-page .dt-paging button.dt-paging-button:disabled,
		.sr-checkout-page .dt-paging button.dt-paging-button[disabled],
		.sr-checkout-page .dt-paging .dt-paging-button,
		.sr-checkout-page .dataTables_wrapper .dataTables_paginate .paginate_button {
			color: #e8f1f6 !important;
			border: 1px solid rgba(183, 201, 212, 0.45) !important;
			background: #045a8d !important;
			border-radius: 0.25rem !important;
			margin: 0 2px !important;
			min-width: 2rem;
			padding: 0.35rem 0.55rem !important;
			opacity: 1 !important;
		}
		.sr-checkout-page .dt-paging button.dt-paging-button.current,
		.sr-checkout-page .dt-paging button.dt-paging-button:hover:not(:disabled):not(.disabled),
		.sr-checkout-page .dt-paging .dt-paging-button.current,
		.sr-checkout-page .dt-paging .dt-paging-button:hover:not(.disabled),
		.sr-checkout-page /* paginate styled by sr-platform-shell Book-list system */
		.sr-checkout-page .dt-paging button.dt-paging-button.disabled,
		.sr-checkout-page .dt-paging button.dt-paging-button:disabled {
			color: #9fb6c4 !important;
			background: #03314d !important;
		}
		.sr-checkout-page .dt-info,
		.sr-checkout-page .dataTables_wrapper .dataTables_info {
			color: #b7c9d4 !important;
		}
		.sr-checkout-page .dt-button {
			color: #fff !important;
			background-color: #0379BB !important;
			border: none !important;
			border-radius: 0.25rem !important;
		}
		.sr-checkout-page .table-responsive {
			overflow-x: auto;
		}
	</style>
</head>

<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">

	<div id="layout-wrapper">
		<?php include 'top_header.php'; ?>

		<div class="main-content">
			<div class="page-content sr-checkout-page">
				<div class="container-fluid">

					<div class="row">
						<div class="col-12">
							<div class="page-title-box d-flex align-items-center justify-content-between">
								<h4 class="mb-0">Luggage check out</h4>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item"><a href="javascript: void(0);">Cargo</a></li>
										<li class="breadcrumb-item active">Luggage in store</li>
									</ol>
								</div>
							</div>
						</div>
					</div>

					<?php $username = $_SESSION['username']; ?>

					<div class="row">
						<div class="col-12">
							<div class="card sr-table-panel">
								<div class="card-header">
									<h4 class="card-title mb-0">Store inventory</h4>
								</div>
								<div class="card-body">
									<div class="row sr-filter-row align-items-end">
										<div class="col-md-4 col-lg-3">
											<label class="form-label" for="route">Select route</label>
											<select name="route" id="route" class="form-select action">
												<?php
												if (isset($_GET['route'])) {
													$route = $_GET['route'];
													echo '<option>' . htmlspecialchars($route, ENT_QUOTES, 'UTF-8') . '</option>';
												}
												$select12 = mysqli_query($db, "SELECT * FROM assigned_routes where username='" . mysqli_real_escape_string($db, $username) . "' order by id asc");
												$select_num12 = mysqli_num_rows($select12);
												if ($select_num12 == 0) {
													echo '<option>You are not Registered</option>';
												} else {
													while ($get132 = mysqli_fetch_array($select12)) {
														$route_full_name = $get132['route_full_name'];
														echo '<option>' . htmlspecialchars($route_full_name, ENT_QUOTES, 'UTF-8') . '</option>';
													}
												}
												?>
											</select>
										</div>
										<div class="col-md-4 col-lg-3">
											<label class="form-label" for="myInputTextField">Search code</label>
											<input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search code" autocomplete="off">
										</div>
									</div>

									<div class="table-responsive">
										<table class="table table-hover mb-0" id="empTable">
											<thead class="table-light">
												<tr>
													<th>Name of owner</th>
													<th>Weight</th>
													<th>Description of goods</th>
													<th>Amount</th>
													<th>To be paid</th>
													<th>Paid</th>
													<th>Vehicle</th>
													<th>Date</th>
													<th>Cargo rec. no</th>
													<th>Exported quantity</th>
													<th>Checkout</th>
													<th>Print</th>
													<th>Id no</th>
												</tr>
											</thead>
										</table>
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
						<div class="col-sm-6">
							<script>document.write(new Date().getFullYear())</script> &copy; Tessera.
						</div>
						<div class="col-sm-6">
							<div class="text-sm-end d-none d-sm-block">
								Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="#" class="text-reset">Tessera</a>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="js/vendor/jquery-1.12.4.min.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/bootstrap-jquery-bridge.js"></script>
	<script src="assets/libs/metismenujs/metismenujs.min.js"></script>
	<script src="assets/libs/simplebar/simplebar.min.js"></script>
	<script src="assets/libs/feather-icons/feather.min.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script src="assets/js/app.js"></script>
	<script src="js/sweetalert.js"></script>
	<script src="select_checked_luggagae.js"></script>

<?php
if (isset($_GET['route'])) {
	$route = $_GET['route'];
} else {
	$route = '';
	$select12 = mysqli_query($db, "SELECT * FROM assigned_routes where username='" . mysqli_real_escape_string($db, $username) . "' order by id asc limit 1");
	$select_num12 = mysqli_num_rows($select12);
	if ($select_num12 != 0) {
		while ($get1323 = mysqli_fetch_array($select12)) {
			$route = $get1323['route_full_name'];
		}
	}
}
?>
	<script>
		$(document).ready(function () {
			$(".spinner-border").hide();
			var route = <?php echo json_encode($route, JSON_UNESCAPED_UNICODE); ?>;

			$('#empTable').DataTable({
				processing: true,
				serverSide: true,
				serverMethod: 'post',
				order: [[12, 'desc']],
				ajax: {
					url: 'fetch_clear_manifest.php',
					type: 'POST',
					data: { route: route }
				},
				columns: [
					{ data: 'username' },
					{ data: 'Weight' },
					{ data: 'detail' },
					{ data: 'amount' },
					{ data: 'to_be' },
					{ data: 'paid' },
					{ data: 'bus' },
					{ data: 'date' },
					{ data: 'code' },
					{ data: 'left' },
					{ data: 'action' },
					{ data: 'print' },
					{ data: 'id' }
				],
				lengthMenu: [100],
				dom: 'Bfrtip',
				rowCallback: function (row, data) {
					$(row).removeClass('sr-row-paid sr-row-due sr-row-open sr-row-out');
					$(row).find('td').css('color', '#1c2430');
					var status = String(data.status || '');
					if (status === 'Due payment') {
						$(row).addClass('sr-row-due');
					} else if (status === 'paid') {
						$(row).addClass('sr-row-paid');
					} else {
						$(row).addClass('sr-row-open');
					}
					if (String(data.checked || '') === 'out') {
						$(row).addClass('sr-row-out');
					}
				},
				preDrawCallback: function () {
					var el = $('div.dataTables_filter label');
					if (!el.parent('form').length) {
						el.wrapAll('<form></form>').parent()
							.attr('autocomplete', 'off')
							.attr('autofill', 'off');
					}
				}
			});

			var oTable = $('#empTable').DataTable();
			$('#myInputTextField').on('keyup', function () {
				oTable.search($(this).val()).draw();
			});

			$(document).on('click', '#empTable .js-cargo-checkout', function () {
				var $btn = $(this);
				var id = $btn.data('id') || $('#id' + String($btn.attr('id') || '').replace('check', '')).text();
				var checked = $('#checked' + id).text();
				var runClear = function () {
					$('#loading' + id).show();
					$.ajax({
						url: 'check_recieved.php',
						method: 'POST',
						data: { id: id },
						success: function () {
							$('#loading' + id).hide();
							$('#empTable').DataTable().ajax.reload();
							$btn.prop('disabled', true);
						}
					});
				};
				if (checked === 'out') {
					if (window.swal) {
						swal({
							title: 'Are you sure?',
							text: 'You are about to uncheck the luggage!',
							icon: 'warning',
							buttons: true,
							dangerMode: true
						}).then(function (willDelete) {
							if (willDelete) runClear();
						});
					} else if (window.confirm('Uncheck this luggage?')) {
						runClear();
					}
				} else {
					runClear();
				}
			});
		});
	</script>
</body>
</html>
