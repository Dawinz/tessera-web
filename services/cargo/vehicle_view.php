<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/sr_env.php';
ob_start();
//index.php
$connect = sr_mysqli('cargo');
$country = '';
$query = "SELECT DISTINCT  name FROM vehicles  ";
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_array($result)) {
    $country .= '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
}



?>

<?php
//index.php
$connect2 = sr_mysqli('ticket');
$date_ticket = '';
$query2 = "SELECT trip_date FROM manifest GROUP BY trip_date ORDER BY MAX(id) DESC LIMIT 120";
$result2 = mysqli_query($connect2, $query2);
while ($row2 = mysqli_fetch_array($result2)) {
    $date_ticket .= '<option value="' . $row2["trip_date"] . '">' . $row2["trip_date"] . '</option>';
}
include 'dependence/all_dependency.php';
?>
<style>
    /* Manifest filters + table: clear controls, muted Unicons (not candy chips) */
    #ui-datepicker-div {
        position: absolute !important;
        top: auto !important;
        left: auto !important;
        z-index: 1200 !important;
    }
    #empTable_filter,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dt-search,
    .dt-search {
        display: none !important;
    }

    .sr-manifest-filters {
        border: 1px solid rgba(183, 201, 212, 0.35);
        margin-bottom: 1rem;
    }
    .sr-manifest-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 0.75rem 0.85rem;
    }
    .sr-filter-field {
        flex: 1 1 150px;
        min-width: 140px;
        max-width: 210px;
    }
    .sr-filter-search {
        flex: 1 1 240px;
        min-width: 220px;
        max-width: 320px;
    }
    .sr-filter-date {
        flex: 0 1 158px;
        max-width: 170px;
    }
    .sr-filter-actions {
        flex: 0 0 auto;
        margin-left: auto;
    }
    .sr-manifest-filters .form-label {
        display: block;
        margin-bottom: 0.3rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        color: #b7c9d4 !important;
    }
    /* Force readable light fields on the dark shell */
    .sr-manifest-filters .form-control,
    .sr-manifest-filters .select2-container--default .select2-selection--single {
        background: #ffffff !important;
        color: #1c2430 !important;
        border: 1px solid #c5ced6 !important;
        border-radius: 0.25rem !important;
        min-height: 38px;
        box-shadow: none !important;
    }
    .sr-manifest-filters .select2-container--default .select2-selection--single {
        height: 38px !important;
        padding-top: 4px;
    }
    .sr-manifest-filters .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1c2430 !important;
        line-height: 28px !important;
        padding-left: 10px !important;
    }
    .sr-manifest-filters .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d !important;
    }
    .sr-manifest-filters .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    body[data-layout-mode="dark"] .sr-manifest-filters .form-control,
    body[data-layout-mode="dark"] .sr-manifest-filters .select2-container--default .select2-selection--single {
        background: #ffffff !important;
        color: #1c2430 !important;
        border-color: #c5ced6 !important;
    }
    body[data-layout-mode="dark"] .sr-manifest-filters .form-control::placeholder,
    .sr-manifest-filters .form-control::placeholder {
        color: #495057 !important;
        opacity: 1 !important;
    }
    body[data-layout-mode="dark"] .sr-manifest-filters .select2-container--default .select2-selection--single .select2-selection__rendered,
    body[data-layout-mode="dark"] .sr-manifest-filters .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #495057 !important;
    }
    .sr-manifest-filters .form-control:focus {
        border-color: #038edc !important;
        box-shadow: 0 0 0 0.15rem rgba(3, 142, 220, 0.2) !important;
    }
    .sr-manifest-filters .input-group {
        display: flex !important;
        flex-wrap: nowrap !important;
        align-items: stretch;
        width: 100%;
        background: #fff;
        border: 1px solid #c5ced6;
        border-radius: 0.25rem;
        overflow: hidden;
    }
    .sr-manifest-filters .input-group-text {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        flex: 0 0 2.35rem;
        width: 2.35rem;
        background: #f3f6f9 !important;
        color: #495057 !important;
        border: 0 !important;
        border-radius: 0 !important;
        padding: 0 !important;
    }
    .sr-manifest-filters .input-group .form-control {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        width: 1% !important;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }
    .sr-manifest-filters .input-group:focus-within {
        border-color: #038edc;
        box-shadow: 0 0 0 0.15rem rgba(3, 142, 220, 0.2);
    }
    .sr-manifest-filters .btn-primary {
        white-space: nowrap;
    }
    .sr-manifest-filters .select2-container {
        width: 100% !important;
    }

    /* Table panel — match Tickets Book list dark Dashonic */
    .table-frame {
        border: 1px solid rgba(183, 201, 212, 0.28);
        border-radius: 0.35rem;
        overflow-x: auto;
        background: transparent;
    }
    #empTable {
        margin: 0 !important;
        width: 100% !important;
        --sr-ink: #e8f1f6;
        --sr-muted: #9fb0bd;
        --sr-line: rgba(183, 201, 212, 0.18);
        --sr-ico: #8aa4b3;
    }
    #empTable thead th {
        white-space: nowrap;
        font-weight: 600;
        font-size: 0.78rem;
        color: #d5e4ee !important;
        background: #043a5a !important;
        border-bottom: 1px solid var(--sr-line) !important;
        padding: 0.7rem 0.85rem !important;
        letter-spacing: 0;
        text-transform: none;
    }
    #empTable.table > :not(caption) > * > * {
        vertical-align: middle;
        padding: 0.65rem 0.85rem;
        border-bottom-color: var(--sr-line) !important;
    }
    #empTable tbody tr {
        background: transparent !important;
    }
    #empTable tbody tr:hover {
        background: rgba(255, 255, 255, 0.04) !important;
    }
    #empTable tbody td {
        color: var(--sr-ink) !important;
        font-size: 0.875rem;
    }
    #empTable .sr-mono {
        font-variant-numeric: tabular-nums;
        letter-spacing: 0.01em;
    }
    #empTable .sr-cell-ico {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        max-width: 100%;
    }
    #empTable .sr-cell-ico > i {
        color: var(--sr-ico);
        font-size: 0.95rem;
        line-height: 1;
        flex-shrink: 0;
    }
    #empTable .sr-id-cell {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }
    #empTable .sr-amt {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.15rem;
        font-variant-numeric: tabular-nums;
        line-height: 1.25;
    }
    #empTable .sr-amt-main {
        font-weight: 600;
        color: var(--sr-ink);
    }
    #empTable .sr-amt-sub {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.75rem;
        color: var(--sr-muted);
    }
    #empTable .sr-amt-sub > i {
        font-size: 0.85rem;
        color: var(--sr-ico);
    }
    #empTable .sr-cell-link {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        color: #9fd4f5 !important;
        text-decoration: none !important;
        max-width: 14rem;
    }
    #empTable .sr-cell-link > i {
        flex-shrink: 0;
        color: var(--sr-ico);
    }
    #empTable .sr-cell-link:hover {
        text-decoration: underline !important;
    }
    #empTable .sr-more-btn {
        width: 1.85rem;
        height: 1.85rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.25rem !important;
        border: 1px solid rgba(183, 201, 212, 0.35) !important;
        background: rgba(255, 255, 255, 0.04) !important;
        color: #d5e4ee !important;
    }
    #empTable .sr-more-btn:hover,
    #empTable .sr-more-btn:focus {
        background: rgba(3, 142, 220, 0.2) !important;
        border-color: rgba(3, 142, 220, 0.45) !important;
        color: #fff !important;
    }
    #empTable tbody tr.sr-row-missing-route td:nth-child(2) {
        color: #ffb4b4;
    }
    #empTable_wrapper .dataTables_info {
        color: #9fb0bd !important;
        font-size: 0.8125rem;
        padding-top: 0.85rem;
    }
    #empTable_wrapper .dataTables_paginate {
        padding-top: 0.65rem;
    }
    #empTable_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.25rem !important;
        border: 1px solid rgba(183, 201, 212, 0.28) !important;
        background: transparent !important;
        color: #e8f1f6 !important;
        margin: 0 0.15rem !important;
        padding: 0.25rem 0.55rem !important;
        box-shadow: none !important;
    }
    #empTable_wrapper .dataTables_paginate .paginate_button:hover {
        background: rgba(255, 255, 255, 0.06) !important;
        border-color: rgba(183, 201, 212, 0.45) !important;
        color: #fff !important;
    }
    #empTable_wrapper .dataTables_paginate .paginate_button.current,
    #empTable_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #038edc !important;
        border-color: #038edc !important;
        color: #fff !important;
    }
    #empTable_wrapper .dataTables_paginate .paginate_button.disabled,
    #empTable_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: transparent !important;
        border-color: rgba(183, 201, 212, 0.15) !important;
        color: #8aa4b3 !important;
    }
</style>

<!doctype html>
<style>
.overlay-panel {
  width: min(920px, 100%);
  max-height: 88vh;
  overflow: auto;
  background: #ffffff;
  border-radius: 8px;
  box-shadow: 0 24px 70px rgba(0, 0, 0, 0.28);
}

.overlay-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 18px 20px;
  border-bottom: 1px solid #e5e7eb;
}

.overlay-header h2 {
  margin: 0;
  font-size: 20px;
}



.table-wrap {
  position: relative;
  overflow-x: auto;
}

.table-frame {
  position: relative;
  overflow-x: auto;
}




.muted {
  color: #6b7280;
}

.table-body-overlay {
  position: absolute;
  top: 55px;
  right: 0;
  bottom: 0;
  left: 0;
  display: none;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.82);
  color: #243447;
  font-weight: 600;
  backdrop-filter: blur(2px);
}

.table-frame.is-loading .table-body-overlay {
  display: flex;
}

.loader {
  width: 28px;
  height: 28px;
  margin-right: 10px;
  border: 3px solid #cbd5e1;
  border-top-color: #243447;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 640px) {
  .table-overlay {
    align-items: flex-start;
    padding: 16px;
  }

  .overlay-panel {
    margin-top: 24px;
  }

  th,
  td {
    padding: 12px;
    white-space: nowrap;
  }

  .table-body-overlay {
    top: 45px;
  }
}

</style>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <?php $tessera_page_label = 'Vehicle manifest'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Tessera" name="author" />
<!-- fullcalendar css -->
    <link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'>
    <link rel="stylesheet" href="style.css">

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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="select_css.css">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />

</head>


<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark">

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <?php include 'top_header.php'; ?>


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
                            <h4 class="mb-0">Manifest Vehicle List</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                    <li class="breadcrumb-item active">List of vehicles</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <?php
                $username = $_SESSION['username'];


                $route_name_fullname = '';
                $route_array = array();

                $select2 = mysqli_query($db, "SELECT route_full_name,status  FROM  assigned_routes where username='$username' order by preference desc");
                $select_num2 = mysqli_num_rows($select2);
                if ($select_num2 != 0) {
                    while ($get12 = mysqli_fetch_array($select2)) {
                        $route_full_name = $get12['route_full_name'];
                        $status = $get12['status'];


                        $route_name_fullname .= "Route='$route_full_name' OR ";
                        $fullname_man = "$route_full_name.$status";



                    }
                }




                //echo $new_array;
                $connect = sr_mysqli('cargo');

                $username = $_SESSION['username'];

                $route_name_id = '';
                $query = "SELECT route_full_name FROM assigned_routes where username='$username' order by preference desc";
                $result = mysqli_query($connect, $query);
                while ($row = mysqli_fetch_array($result)) {
                    $fullname = $row["route_full_name"];
                    $route_name_id .= "<option value=\"(Route='$fullname') AND \">$fullname</option>";
                }


                $date_now = date('Y-m-d', time());




                ?>

                <div class="row">
                    <div class="card sr-manifest-filters">
                        <div class="card-body py-3">
                            <div class="sr-manifest-toolbar">
                                <div class="sr-filter-field">
                                    <label class="form-label" for="vehicle_name_selected">Vehicle</label>
                                    <select name="vehicle" id="vehicle_name_selected" class="form-control selectpicker"
                                        data-live-search="true">
                                        <option value="">Choose vehicle</option><?php vehicle($db); ?>
                                    </select>
                                </div>
                                <div class="sr-filter-field">
                                    <label class="form-label" for="route">Route</label>
                                    <select name="route" id="route" class="form-control selectpicker"
                                        data-live-search="true">
                                        <option value="">Choose route</option><?php echo $route_name_id; ?>
                                    </select>
                                </div>
                                <div class="sr-filter-field sr-filter-date">
                                    <label class="form-label" for="start_date">From</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="uil uil-calender" aria-hidden="true"></i></span>
                                        <input class="form-control dateselect" type="text" value=""
                                            id="start_date" placeholder="yyyy-mm-dd" autocomplete="off">
                                    </div>
                                </div>
                                <div class="sr-filter-field sr-filter-date">
                                    <label class="form-label" for="end_date">To</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="uil uil-calender" aria-hidden="true"></i></span>
                                        <input class="form-control dateselect" type="text" value=""
                                            id="end_date" placeholder="yyyy-mm-dd" autocomplete="off">
                                    </div>
                                </div>
                                <div class="sr-filter-field sr-filter-search">
                                    <label class="form-label" for="search">Search</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="uil uil-search" aria-hidden="true"></i></span>
                                        <input class="sr-table-search form-control" type="search" value="" id="search"
                                            placeholder="Manifest, vehicle, driver..." autocomplete="off">
                                    </div>
                                </div>
                                <div class="sr-filter-actions">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target=".bs-example-modal-center">
                                        <i class="uil uil-plus" aria-hidden="true"></i> Register Manifest
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div><!-- end filters -->

                    <div class="table-responsive">
                        <div class="table-wrap">
                            <div class="table-frame sr-table-panel is-loading" aria-busy="true">
                                <table class="table mb-0" id="empTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Manifest Id</th>
                                            <th>Route</th>
                                            <th>Vehicle</th>
                                            <th>Date</th>
                                            <th>Driver</th>
                                            <th>Conductor</th>
                                            <th>Currency</th>
                                            <th class="text-end">To be paid</th>
                                            <th class="text-end">Paid</th>
                                            <th class="text-end">Total</th>
                                            <th>Remarks</th>
                                            <th>More</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>
                                            <td class="muted">
                                                <p class="placeholder-glow"><span class="placeholder col-12"></span></p>
                                            </td>

                                        </tr>
                                    </tbody>
                                    <!-- end tbody -->
                                </table>
                                 <div class="table-body-overlay" role="status" aria-live="polite">
                                                    <span class="loader" aria-hidden="true"></span>
                                                    Loading table data...
                                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->

                <!-- end col -->
            </div>
            <!-- end page title -->



            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


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
    <!-- End Page-content -->

    <?php
    include 'modal/manifest_registration.php';
    include 'modal/remarks.php';

    ?>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">UPDATE MANIFEST</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div style="display:none;" id="id_update"></div>
        <div class="offcanvas-body">
            <table>
                <tr>
                    <td> <button style="margin:10px;" class="btn btn-outline-dark" id="open_update"><i
                                class="fa fa-plus" aria-hidden="true"></i> Add vehicle</button><br></td>

                    <td>

                        <div class="row" id="show_update" style="display:none;">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="nk-int-st">
                                    <input type="text" style="margin:10px;" class="form-control"
                                        placeholder="Vehicle Name" id="name_update">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <button id="save_update" class="btn btn-outline-dark" style="margin:10px;"><i
                                        class="notika-icon notika-checked"></i> Add</button>

                            </div>
                        </div>

                    </td>



                </tr>
            </table>

            <div class="nk-int-mk sl-dp-mn sm-res-mg-t-10"><br>

            </div>
            <div class="bootstrap-select fm-cmp-mg">
                <label class="nk-label">Vehicle Number</label>

                <select class="form-select" data-live-search="true" id="vehicle_update">
                    <option id="vehicle_option"></option>
                    <?php echo $country; ?>
                </select>
            </div>
            <br>
            <div class="row">

                <div class="form-group ic-cmp-int float-lb floating-lb">
                    <div class="form-ic-cmp">
                    </div>
                    <div class="nk-int-st">
                        <label class="nk-label">Driver's Name</label>

                        <input type="text" class="form-control" id="driver_update">
                        <div id="view_place"
                            style="position:absolute;background-color:white;z-index:1000;display:none;">
                            <br>
                        </div>
                    </div>
                </div>
                <div class="form-group ic-cmp-int float-lb floating-lb">
                    <div class="form-ic-cmp">
                    </div>
                    <div class="nk-int-st">
                        <label class="nk-label" id='chide'>Conductor's name</label>
                        <input type="text" class="form-control" id="conductor_update">
                        <br>
                    </div>
                </div>
                <div class="form-group ic-cmp-int float-lb floating-lb">
                    <div class="form-ic-cmp">
                    </div>
                    <div class="nk-int-st">


                        <div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
                            <div class="input-group date nk-int-st">
                                <span class="input-group-addon"></span>

                                <input type="text" class="form-control" id="time2_update">
                            </div>
                        </div>
                        <br>
                        <label class="form-label" for="validationCustom02">Select Route</label>

                        <select name="route" id="route_update" class="form-select" data-live-search="true"><?php
                        if (isset($_GET['route'])) {
                            echo "  <option>" . $_GET['route'] . "</option>";
                            $select12 = mysqli_query($db, "SELECT *  FROM  assigned_routes where username='$username' ");
                            $select_num12 = mysqli_num_rows($select12);
                            if ($select_num12 == 0) {

                            } else if ($select_num12 == 1) {

                                while ($get132 = mysqli_fetch_array($select12)) {
                                    $route_full_name = $get132['route_full_name'];
                                    echo "  <option>" . $route_full_name . "</option>";
                                }
                            } else if ($select_num12 >= 2) {

                                while ($get132 = mysqli_fetch_array($select12)) {
                                    $route_full_name = $get132['route_full_name'];
                                    echo "<option>" . $route_full_name . "</option>";
                                }
                            }
                        } else {
                            $select12 = mysqli_query($db, "SELECT *  FROM  assigned_routes where username='$username' order by id desc");
                            $select_num12 = mysqli_num_rows($select12);
                            if ($select_num12 == 0) {

                            } else if ($select_num12 == 1) {

                                while ($get132 = mysqli_fetch_array($select12)) {
                                    $route_full_name = $get132['route_full_name'];
                                    echo "  <option>" . $route_full_name . "</option>";
                                }
                            } else if ($select_num12 >= 2) {

                                while ($get132 = mysqli_fetch_array($select12)) {
                                    $route_full_name = $get132['route_full_name'];
                                    echo "  <option>" . $route_full_name . "</option>";
                                }
                            }
                        }
                        ?></select><br>
                        <div class="material-design-btn">

                            <button class="btn btn-primary" style="padding-top:10px;padding-bottom:10px;"
                                id="savebtn2_update">Update Manifest</button>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <input type="hidden" value="<?php echo $route_name_fullname; ?>" id="all_routes">


    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> &copy; Tessera.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Pichforest.com/"
                            target="_blank" class="text-reset">Tessera</a>
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

    <?php

    if ($route_name_fullname !== '') {
    	$route_name_fullname = substr($route_name_fullname, 0, -3);
    	$route_name_fullname = "($route_name_fullname) and  ";
    } else {
    	$route_name_fullname = '';
    }

    ?>

    </div>

    <!-- Page scripts only - shell already ships jQuery, Bootstrap, Select2, Feather. -->
    <script src="js/sweetalert.js"></script>
    <script src="insert_vehicle_ticket.js"></script>
    <script src="insert_vehicle.js?<?php echo modified_time('insert_vehicle.js'); ?>"></script>
    <script src="update_vehicle.js"></script>
    <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
    <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript" charset="utf8" src="js/datatable.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
    <script src="js/insert/remark.js"></script>
    <!-- apexcharts init -->

</body>

</html>

<script>
    $(document).ready(function () {



        if ($.fn.select2) {
            $('.selectpicker').select2({});
        }
        if ($.fn.datepicker) {
            $('.dateselect').datepicker({
                todayHighlight: true,
                format: "yyyy-mm-dd",
            });
        }

        route_name_select = "<?php echo $route_name_fullname; ?>";
        /* Lean API + date_sort index keep unfiltered first page fast - do not hide rows with a surprise date cut-off. */
        start_date = $("#start_date").val() || "";
        end_date = $("#end_date").val() || "";
        vehicle = $("#vehicle_name_selected").val() || "";

        var manifestTable = null;
        var searchTimer = null;

        function setTableLoading(on) {
            var tableFrame = document.querySelector(".table-frame");
            if (tableFrame) {
                tableFrame.classList.toggle("is-loading", !!on);
                tableFrame.setAttribute("aria-busy", on ? "true" : "false");
            }
        }

        function currentFilters() {
            return {
                route: ($("#route option:selected").text() || "").trim(),
                route_name_select: $("#route").val() || route_name_select || "",
                start_date: $("#start_date").val() || "",
                end_date: $("#end_date").val() || "",
                vehicle: $("#vehicle_name_selected").val() || ""
            };
        }

        function reloadManifest(resetPaging) {
            if (!manifestTable) {
                initManifestTable();
                return;
            }
            setTableLoading(true);
            manifestTable.ajax.reload(null, resetPaging !== false);
        }

        function initManifestTable() {
            var tableFrame = document.querySelector(".table-frame");
            setTableLoading(true);
            manifestTable = $('#empTable').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                language: {
                    processing: "Loading manifests...",
                    emptyTable: "No manifests for this filter.",
                    zeroRecords: "No matching manifests."
                },
                serverMethod: 'post',
                ajax: {
                    url: 'fetch_vehicle_view.php?<?php echo modified_time("fetch_vehicle_view.php"); ?>',
                    type: "POST",
                    data: function (d) {
                        var f = currentFilters();
                        d.route = f.route;
                        d.route_name_select = f.route_name_select;
                        d.start_date = f.start_date;
                        d.end_date = f.end_date;
                        d.vehicle = f.vehicle;
                    },
                    error: function () {
                        setTableLoading(false);
                        if (window.TesseraStates && TesseraStates.toast) {
                            TesseraStates.toast({ type: 'error', message: 'Could not load manifests. Try again.' });
                        }
                    }
                },
                columns: [
                    { data: 'code' },
                    { data: 'Route' },
                    { data: 'Name' },
                    { data: 'date' },
                    { data: 'Driver' },
                    { data: 'Conductor' },
                    { data: 'currency' },
                    { data: 'to_pay', className: 'text-end' },
                    { data: 'paid', className: 'text-end' },
                    { data: 'total', className: 'text-end' },
                    { data: 'remark_test' },
                    { data: 'options', orderable: false }
                ],
                dom: 'Brtip',
                lengthMenu: [[25, 50, 100], [25, 50, 100]],
                pageLength: 25,
                buttons: [
                    { extend: 'copyHtml5', footer: true },
                    { extend: 'excelHtml5', footer: true },
                    { extend: 'csvHtml5', footer: true },
                    { extend: 'print', footer: true },
                    { extend: 'pdfHtml5', footer: true }
                ],
                preXhr: function () { setTableLoading(true); },
                drawCallback: function () {
                    setTableLoading(false);
                    if (tableFrame) {
                        tableFrame.classList.remove("is-loading");
                        tableFrame.setAttribute("aria-busy", "false");
                    }
                },
                rowCallback: function (row, data) {
                    $(row).removeClass('sr-row-missing-route');
                    if (!data.Route || String(data.Route).replace(/<[^>]+>/g, '').trim() === '') {
                        $(row).addClass('sr-row-missing-route');
                    }
                }
            });
            oTable = manifestTable;
            $('#search').off('keyup.manifest').on('keyup.manifest', function () {
                var q = $(this).val();
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    if (manifestTable) manifestTable.search(q).draw();
                }, 280);
            });
        }

        initManifestTable();

        $("#route, #vehicle_name_selected, #start_date, #end_date").off('change.manifest').on('change.manifest', function () {
            reloadManifest(true);
        });

        /* One shared actions menu - filled from row data-* (keeps AJAX payloads tiny). */
        if (!$('#sr-manifest-shared-menu').length) {
            $('body').append(
                '<div id="sr-manifest-shared-menu" class="dropdown-menu dropdown-menu-end shadow" style="display:none;position:fixed;z-index:1080;min-width:12rem">' +
                '<a class="dropdown-item" data-act="view" href="#"><i class="uil uil-eye"></i> View</a>' +
                '<a class="dropdown-item" data-act="checkout" href="#"><i class="uil uil-check"></i> Luggage Checkout</a>' +
                '<a class="dropdown-item" data-act="load" href="#"><i class="uil uil-download-alt"></i> Load</a>' +
                '<a class="dropdown-item" data-act="charges" href="#"><i class="uil uil-print"></i> User Charges</a>' +
                '<button type="button" class="dropdown-item" data-act="edit"><i class="uil uil-pen"></i> Edit</button>' +
                '<button type="button" class="dropdown-item" data-act="sms"><i class="uil uil-whatsapp text-success"></i> SMS</button>' +
                '<button type="button" class="dropdown-item" data-act="delete"><i class="uil uil-times"></i> Delete</button>' +
                '<button type="button" class="dropdown-item" data-act="remark"><i class="uil uil-comment-alt-chart-lines"></i> Remark</button>' +
                '</div>'
            );
        }
        function b64(s) { try { return window.btoa(unescape(encodeURIComponent(s || ''))); } catch (e) { return ''; } }
        function hideSharedMenu() {
            $('#sr-manifest-shared-menu').hide();
        }
        $(document).on('click.manifestMore', function () { hideSharedMenu(); });
        $(document).on('click.manifestMore', '#empTable .js-manifest-more', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $btn = $(this);
            var d = $btn.data();
            var $menu = $('#sr-manifest-shared-menu');
            var view = 'vehicle_report_new.php?date=' + encodeURIComponent(d.date) +
                '&date2=' + encodeURIComponent(d.date) +
                '&date3=' + encodeURIComponent(d.name) +
                '&open=' + encodeURIComponent(d.status) +
                '&route=' + encodeURIComponent(b64(d.route)) +
                '&driver=' + encodeURIComponent(b64(d.driver)) +
                '&Conductor=' + encodeURIComponent(b64(d.conductor)) +
                '&code=' + encodeURIComponent(b64(d.code));
            var checkout = 'vehicle_report_new_edit.php?date=' + encodeURIComponent(d.date) +
                '&date2=' + encodeURIComponent(d.date) +
                '&date3=' + encodeURIComponent(d.name) +
                '&open=' + encodeURIComponent(d.status);
            var load = 'check_luggages.php?bus=' + encodeURIComponent(d.name) +
                '&date=' + encodeURIComponent(d.date) +
                '&id=' + encodeURIComponent(b64(String(d.id))) +
                '&open=' + encodeURIComponent(d.status) +
                '&driver=' + encodeURIComponent(b64(d.driver)) +
                '&conductor=' + encodeURIComponent(b64(d.conductor)) +
                '&manifest_id=' + encodeURIComponent(b64(d.code)) +
                '&route_name=' + encodeURIComponent(b64(d.route)) +
                '&currency=' + encodeURIComponent(b64(d.currency));
            var charges = 'print_manifest.php?date=' + encodeURIComponent(d.date) +
                '&date2=' + encodeURIComponent(d.date) +
                '&name=' + encodeURIComponent(d.name) +
                '&open=' + encodeURIComponent(d.status) +
                '&driver=' + encodeURIComponent(d.driver) +
                '&conductor=' + encodeURIComponent(d.conductor);
            $menu.find('[data-act=view]').attr('href', view);
            $menu.find('[data-act=checkout]').attr('href', checkout).css('pointer-events', d.canCheckout == '1' ? '' : 'none').toggleClass('disabled', d.canCheckout != '1');
            $menu.find('[data-act=load]').attr('href', load).css('pointer-events', d.canLoad == '1' ? '' : 'none').toggleClass('disabled', d.canLoad != '1');
            $menu.find('[data-act=charges]').attr('href', charges);
            $menu.find('[data-act=edit],[data-act=delete]').prop('disabled', d.canEdit != '1').toggleClass('disabled', d.canEdit != '1');
            $menu.find('[data-act=sms]').prop('disabled', d.canSms != '1').toggleClass('disabled', d.canSms != '1');
            $menu.data('row', d);
            var rect = this.getBoundingClientRect();
            $menu.css({
                display: 'block',
                top: Math.min(rect.bottom + 4, window.innerHeight - 280) + 'px',
                left: Math.max(8, rect.right - 200) + 'px'
            });
        });
        $(document).on('click.manifestMore', '#sr-manifest-shared-menu [data-act]', function (e) {
            var act = $(this).data('act');
            var d = $('#sr-manifest-shared-menu').data('row') || {};
            if (act === 'view' || act === 'checkout' || act === 'load' || act === 'charges') {
                hideSharedMenu();
                return; /* let link navigate */
            }
            e.preventDefault();
            hideSharedMenu();
            if (act === 'edit') {
                $('#vehicle_option').text(d.name || '');
                $('#driver_update').val(d.driver || '');
                $('#conductor_update').val(d.conductor || '');
                $('#time2_update').val(d.date || '');
                $('#id_update').text(d.id || '');
                try { $('#route_update').val(d.route || ''); } catch (err) { /* ignore */ }
                var off = document.getElementById('offcanvasRight');
                if (off && window.bootstrap && bootstrap.Offcanvas) {
                    bootstrap.Offcanvas.getOrCreateInstance(off).show();
                }
            } else if (act === 'sms') {
                $('.loader' + d.id).show();
                $.ajax({
                    url: 'send_bulk.php',
                    method: 'POST',
                    data: { bus: d.name, date: d.date },
                    success: function () {
                        if (window.swal) swal({ title: 'Sms Sent', text: 'Data Succesfull Insert.', timer: 3000, showConfirmButton: false });
                        if (manifestTable) manifestTable.ajax.reload(null, false);
                    },
                    complete: function () { $('.loader' + d.id).hide(); }
                });
            } else if (act === 'delete') {
                $('.loader' + d.id).show();
                $.ajax({
                    url: 'delete_vehicle_name.php',
                    method: 'POST',
                    data: { delete_id: d.id },
                    success: function (response) {
                        if ($.trim(response) === 'found') {
                            if (window.swal) swal('Found!', 'Data Found inside!', 'warning');
                            $('.loader' + d.id).hide();
                        } else if (manifestTable) {
                            manifestTable.ajax.reload(null, false);
                        }
                    }
                });
            } else if (act === 'remark') {
                $("#remark").modal("show");
                $("#manifest_id").val(d.id);
                $("#manifest_name_id").text(d.name || '');
                var remark_html = d.remark || '';
                if (!remark_html) remark_html = "<p>Add Remark</p>";
                else {
                    try { remark_html = atob(remark_html); } catch (err) { remark_html = "<p>Add Remark</p>"; }
                }
                var container = $("#editor").get(0);
                var existingQuill = container && window.Quill ? Quill.find(container) : null;
                if (existingQuill) existingQuill.root.innerHTML = remark_html;
            }
        });

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        $('#time2').val(mm + '/' + dd + '/' + yyyy);
    });

</script>

<script>
    $(document).ready(function () {
        $('.action').change(function () {

            if ($(this).val() != '') {
                var action = $(this).attr("id");
                var query = $(this).val();
                var result = '';
                if (action == "country") {
                    result = 'state';
                    $('#car').val(query);
                }
                if (action == "city") {
                    result = 'city';
                }
                $.ajax({
                    url: "fetch_dependent_ticket.php",
                    method: "POST",
                    data: { action: action, query: query },
                    success: function (data) {
                        $('#' + result).html(data);

                    }
                })
            }
        });
        $('#state').change(function () {
            var state = $("#state option:selected").text();
            $('#vehicle').val(state);
        });
        $('#empTable tbody').on('click', '.remark_c', function () {
            $("#remark").modal("show");


            var remark_html = $(this).data("remark");
            var manifest_id = $(this).data("manifest_id");
            var name = $(this).data("name");

            $("#manifest_id").val(manifest_id);
            $("#manifest_name_id").text(name);

            if (remark_html == "") {
                remark_html = "<p>Add Remark</p>";
            } else {
                remark_html = atob(remark_html);

            }


            var container = $("#editor").get(0);
            existingQuill = Quill.find(container);

            if (existingQuill) {
                existingQuill.root.innerHTML = remark_html;
            } else {

                var quill = new Quill("#editor-id", { theme: "snow" });
            }

        });

        /* Delegated actions - replaces per-row <script> in fetch_vehicle_view.php */
        $(document).on('click', '#empTable .js-manifest-delete', function () {
            var id = $(this).data('id');
            $('.loader' + id).show();
            $.ajax({
                url: 'delete_vehicle_name.php',
                method: 'POST',
                data: { delete_id: id },
                success: function (response) {
                    if ($.trim(response) === 'found') {
                        if (window.swal) swal('Found!', 'Data Found inside!', 'warning');
                        $('.loader' + id).hide();
                    } else {
                        $('#empTable').DataTable().ajax.reload(null, false);
                    }
                }
            });
        });
        $(document).on('click', '#empTable .js-manifest-edit', function () {
            var $el = $(this);
            $('#vehicle_option').text($el.data('name') || '');
            $('#driver_update').val($el.data('driver') || '');
            $('#conductor_update').val($el.data('conductor') || '');
            $('#time2_update').val($el.data('date') || '');
            $('#id_update').text($el.data('id') || '');
            var route = $el.data('route') || '';
            $('#route_update option:selected').text(route);
            try { $('#route_update').val(route); } catch (e) { /* ignore */ }
        });
        $(document).on('click', '#empTable .js-manifest-sms', function () {
            var $el = $(this);
            var id = $el.data('id');
            $('.loader' + id).show();
            $.ajax({
                url: 'send_bulk.php',
                method: 'POST',
                data: { bus: $el.data('bus'), date: $el.data('date') },
                success: function () {
                    if (window.swal) {
                        swal({ title: 'Sms Sent', text: 'Data Succesfull Insert.', timer: 3000, showConfirmButton: false });
                    }
                    $('#empTable').DataTable().ajax.reload(null, false);
                },
                complete: function () {
                    $('.loader' + id).hide();
                }
            });
        });
    });
        $(document).on('click', '#empTable_paginate .paginate_button:not(.disabled)', function () {
        const overlay = document.querySelector(".table-overlay");
        const closeButton = document.querySelector(".close-button");
        const tableFrame = document.querySelector(".table-frame");

        tableFrame.classList.add("is-loading");
        tableFrame.setAttribute("aria-busy", "true");
    });
</script>

<?php
ob_end_flush();
?>