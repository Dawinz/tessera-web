<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$trip_date = @$_GET['date'];
$vehicle = @$_GET['date3'];
$driver = @base64_decode($_GET['driver']);
$Conductor = @base64_decode($_GET['Conductor']);
$route = @base64_decode($_GET['route']);
$code = @base64_decode($_GET['code']);
$remark = @$_GET['remark'];
include 'connect.php';
include 'dependence/all_dependency.php';
if ($trip_date === '' || $trip_date === null || (int) date('Y', strtotime((string) $trip_date) ?: 0) < 2000) {
	$latest = tessera_cargo_latest_trip($db);
	if ($latest) {
		$trip_date = $latest['date'];
		$vehicle = $latest['Name'];
		$driver = $latest['Driver'];
		$Conductor = $latest['Conductor'];
		$route = $latest['Route'];
		$code = $latest['code'];
		$_GET['date'] = $latest['date'];
		$_GET['date3'] = $latest['Name'];
	}
}
?>
<!doctype html>
<style>

</style>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <?php $tessera_page_label = 'Vehicle report'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Tessera" name="author" />
<!-- fullcalendar css -->
    <link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

    <!-- One of the following themes -->
    <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/classic.min.css" /> <!-- 'classic' theme -->
    <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/monolith.min.css" /> <!-- 'monolith' theme -->
    <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/nano.min.css" /> <!-- 'nano' theme -->

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
    <link rel="stylesheet" href="css/datapicker/datepicker3.css">
    <link rel="stylesheet" type="text/css" href="css/buttons.css">
    
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'>
    
        <link rel="stylesheet" href="style.css">

</head>
<style>
    button.dt-button,
    div.dt-button,
    a.dt-button,
    input.dt-button {
        border-radius: 2px;
        color: black;
        background-color: #F5F6F8;
    }

    .stock {
        text-decoration: line-through;
        text-decoration-thickness: 2px;
        text-decoration-color: red;

    }
    .page-section {
  padding: 18px;
  background: #f5f7fb;
}

.page-header {
  margin-bottom: 14px;
}

.page-header h2 {
  margin: 0;
  color: #1f2937;
  font-size: 22px;
  font-weight: 700;
}

.page-header p {
  margin: 4px 0 0;
  color: #6b7280;
  font-size: 14px;
}

.panel {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 16px;
  margin-bottom: 14px;
}

.panel-title {
  font-size: 15px;
  font-weight: 700;
  color: #374151;
  margin-bottom: 12px;
}

.vehicle-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
  gap: 12px;
}

.vehicle-grid div {
  background: #f9fafb;
  border: 1px solid #edf0f3;
  border-radius: 6px;
  padding: 10px 12px;
}

.vehicle-grid span {
  display: block;
  color: #6b7280;
  font-size: 12px;
  margin-bottom: 4px;
}

.vehicle-grid strong {
  color: #111827;
  font-size: 14px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
  gap: 14px;
}

.form-group label {
  display: block;
  font-size: 13px;
  font-weight: 600;
  color: #4b5563;
  margin-bottom: 6px;
}



.export-columns {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 9px 14px;
}

.export-columns label {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 14px;
  font-weight: 500;
  color: #4b5563;
  margin: 0;
}

.export-columns input {
  width: 15px;
  height: 15px;
}
.table-toolbar {
  display: flex;
  gap: 10px;
  align-items: center;
  margin: 14px 0;
}

</style>

<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark">

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <?php include 'top_header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />   
<link rel="stylesheet" type="text/css" href="select_css.css">

<style>
.filter-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
  align-items: end;
}

.filter-item label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
}

.filter-item .form-select,
.filter-item .form-control,
.filter-item .bootstrap-select {
  width: 100% !important;
}

.filter-item .bootstrap-select > .dropdown-toggle {
  width: 100%;
}
</style>
        <!-- ========== Left Sidebar Start ========== -->

        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Manfest luggage list</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                    <li class="breadcrumb-item active">List of Luggages</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <div>
                                        <div style="display:none;"id="remark"><?php echo $remark; ?></div>
                                        <input type="hidden" id="row_remark"value="<?php echo @$_GET['row_remark']; ?>"></input>

                                    </div>
        
    <div class="panel">
    <div class="panel-title">Vehicle details</div>

    <div class="vehicle-grid">
      <div><span>Manifest ID</span><strong><?php echo $code; ?></strong></div>
      <div><span>Vehicle Name</span><strong><span class="vehicle_name"><?php echo $vehicle; ?></span></strong></div>
      <div><span>Trip Date</span><strong><span class="vehicle_date"><?php echo htmlspecialchars(tessera_cargo_date_label($trip_date), ENT_QUOTES, 'UTF-8'); ?></span></strong></div>
      <div><span>Driver</span><strong><?php echo $driver; ?></strong></div>
      <div><span>Conductor</span><strong><?php echo $Conductor; ?></strong></div>
    </div>
    <div class="table-toolbar">
  <button type="button"  class="btn btn-primary text-dark filter_btn"  >
   <span class="mdi mdi-filter-menu"></span>
 Show Filters
  </button>

  <button type="button"  class="btn btn-primary text-dark option_btn"  >
    <span class="mdi mdi-export"></span>
Export Options
  </button>
</div>

  </div>

  <div class="panel filter_panel" style="display:none;">
    <div class="panel-title">Filters</div>

    <div class="filter-grid">
      <div class="form-group">
        <label>Routes</label>
       <select class="form-select selectpicker" id="route_name" style="width: 100% ">
                                                <option value="">All Routes</option>

                                                <?php
                                                my_assigned_routes($db, $_SESSION['username']);
                                                ?>
                                            </select>
      </div>

      <div class="form-group">
        <label>Status</label>
     <select class="form-select selectpicker" id="status_luggage" style="width: 100% ">
                                                <option value="">All Status</option>
                                                <option value="To be Payed">To be paid</option>
                                                <option value="paid">Paid</option>
                                                <option value="Due payment">Due payment</option>
                                            </select>
      </div>

      <div class="form-group">
        <label>Luggage Type</label>
      <select class="form-select selectpicker" id="type" style="width: 100% ">
                                                <option value="">All Type</option>
                                                <option value="Direct">Direct</option>
                                                <option value="transit">Tranist</option>
                                            </select>
      </div>

      <div class="form-group">
        <label>Starting Date</label>
       <input type="text" id="start_date" class="form-control dateselect "
                                                placeholder="date" value="">
      </div>

      <div class="form-group">
        <label>Ending Date</label>
        <input type="text" id="end_date" class="form-control dateselect"
                                                placeholder="date" value="" />
      </div>
    </div>
  </div>

  

                <div class="row">

                    <div class="card">
                        <div class="card-header justify-content-between d-flex align-items-center">

                        </div><!-- end card header -->
                      
                        <br>
                        <!-- end card -->
                        <div id="load_manifest_view"></div>
                        <!-- end col -->
                    </div>
                    <!-- end page title -->



                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->



            </div>
            <!-- End Page-content -->
            <input type="hidden" value="<?php echo $trip_date; ?>" id="trip_date" />
            <input type="hidden" value="<?php echo $vehicle; ?>" id="vehicle" />
            <input type="hidden" value="<?php echo $_GET['route']; ?>" id="main_route" />
            <input type="hidden" value="<?php echo $_SESSION['username']; ?>" id="username_name" />




            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> &copy; Tessera.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Crafted with <i class="mdi mdi-heart text-danger"></i> by <a
                                    href="https://Pichforest.com/" target="_blank" class="text-reset">Tessera</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->


    <!-- /Right-bar -->

    <!-- end row -->

    <!-- end row -->

    <!-- end row -->
    </div>
    <!-- container-fluid -->
    </div>
    <!-- End Page-content -->



    </div>

    <!-- JAVASCRIPT -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
    <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>

    <!-- plugin js -->
    <script src="assets/libs/fullcalendar/main.min.js"></script>
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

    <!-- Calendar init -->
    <script src="assets/js/pages/calendar.init.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/pages/form-advanced.init.js"></script>
    <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
    <script src="assets/libs/@simonwep/pickr/pickr.min.js"></script>
    <script src="js/sweetalert.js"></script>
    <script src="manifest_view.js<?php echo "?" . modified_time("manifest_view.js") . ""; ?>"></script>
    <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
    <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  	

    <script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
    <script type="text/javascript" language="javascript" src="datatable/button.js"></script>
    <script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
    <script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
    <script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
    <script type="text/javascript" src="datatable/html.js"></script>
    <script type="text/javascript" src="datatable/print.js"></script>


    <!-- apexcharts init -->

</body>

</html>

<script>
    $(document).ready(function () {

			     $('.selectpicker').select2({
    });	


        $('.dateselect').datepicker({
            todayHighlight: true,
            orientation: "top left",
            format: "yyyy-mm-dd",
            autoclose: true



        });


    });

</script>
