<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/tessera_tenant.php';
tessera_abort_unknown_tenant();
if (empty($_SESSION['username'])) {
	tessera_redirect('index.php');
}
$connect = sr_mysqli('ticket');
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <?php $tessera_page_label = 'Manifest'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Pichforest" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="datatable/datatable.css">
    <link href="css/app/booking.css?v=<?= filemtime('css/app/booking.css') ?>" rel="stylesheet" type="text/css" />
    <link href="css/app/manifest.css?v=<?= filemtime('css/app/manifest.css') ?>" rel="stylesheet" type="text/css" />
    <link href="css/app/manifest-dark.css?v=<?= filemtime('css/app/manifest-dark.css') ?>" rel="stylesheet" type="text/css" />
    <script src="js/vendor/jquery-1.12.4.min.js"></script>

</head>

<style>
    .dataTables_filter {
        display: none;
    }
</style>


<body class="manifest-page" data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include 'top_header.php'; ?>

        <?php
        $username = $_SESSION['username'];
        $route_name2 = '';
        $query = "SELECT route_name,route_id,start_route,end_route FROM assigned_routes WHERE username='$username'";
        $result = mysqli_query($connect, $query);
        while ($row = mysqli_fetch_array($result)) {
            $route_id_option = $row["route_id"];
            $fullname = $row["start_route"] . ' to ' . $row["end_route"];
            $route_name2 .= "<option value=\"route_id='$route_id_option' AND \">$fullname</option>";
        }

        $vehicle = '';
        $query = "SELECT name FROM vehicle";
        $result = mysqli_query($connect, $query);
        while ($row = mysqli_fetch_array($result)) {
            $vehicle .= "<option value=\"bus='" . mysqli_real_escape_string($connect, $row["name"]) . "' AND \">" . htmlspecialchars($row["name"], ENT_QUOTES, 'UTF-8') . "</option>";
        }
        ?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Manifest</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                        <li class="breadcrumb-item active">Manifest list</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row manifest-dashboard">
                        <div class="col-12">
                            <div class="manifest-stat-grid">
                                <div class="card sr-table-panel booking-explorer-card manifest-stat">
                                    <span class="manifest-stat-icon is-primary"><i class="mdi mdi-bus-clock"></i></span>
                                    <div>
                                        <span class="manifest-stat-label">Trips</span>
                                        <strong class="manifest-stat-value" data-stat="trips">0</strong>
                                    </div>
                                </div>
                                <div class="card booking-explorer-card manifest-stat">
                                    <span class="manifest-stat-icon is-info"><i class="mdi mdi-bus-multiple"></i></span>
                                    <div>
                                        <span class="manifest-stat-label">Buses</span>
                                        <strong class="manifest-stat-value" data-stat="buses">0</strong>
                                    </div>
                                </div>
                                <div class="card booking-explorer-card manifest-stat">
                                    <span class="manifest-stat-icon is-warning"><i class="mdi mdi-seat-passenger"></i></span>
                                    <div>
                                        <span class="manifest-stat-label">Booked seats</span>
                                        <strong class="manifest-stat-value" data-stat="seats">0</strong>
                                    </div>
                                </div>
                                <div class="card booking-explorer-card manifest-stat">
                                    <span class="manifest-stat-icon is-success"><i class="mdi mdi-ticket-confirmation-outline"></i></span>
                                    <div>
                                        <span class="manifest-stat-label">Ticket sales</span>
                                        <strong class="manifest-stat-value" data-stat="sales" data-money>TZS 0</strong>
                                    </div>
                                </div>
                                <div class="card booking-explorer-card manifest-stat">
                                    <span class="manifest-stat-icon is-secondary"><i class="mdi mdi-road-variant"></i></span>
                                    <div>
                                        <span class="manifest-stat-label">Road collection</span>
                                        <strong class="manifest-stat-value" data-stat="road" data-money>TZS 0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card booking-explorer-card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h6 class="booking-explorer-heading mb-0">Manifest list</h6>
                                    <span class="booking-explorer-count" id="manifest_count">0 manifests</span>
                                </div>
                                <div class="card-body">
                                    <form class="booking-filter" autocomplete="off" onsubmit="return false;">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label" for="route_name">Route</label>
                                                <select class="booking-location-select" id="route_name">
                                                    <option value="">All routes</option>
                                                    <?php echo $route_name2; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="vecihle">Vehicle</label>
                                                <select class="booking-location-select" id="vecihle">
                                                    <option value="">All vehicles</option>
                                                    <?php echo $vehicle; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label" for="start_date">From date</label>
                                                <div class="booking-date-field">
                                                    <span class="booking-date-icon" aria-hidden="true">
                                                        <i class="mdi mdi-calendar-month-outline"></i>
                                                    </span>
                                                    <input class="form-control manifest-date" type="text" id="start_date" value="" placeholder="Any date">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label" for="end_date">To date</label>
                                                <div class="booking-date-field">
                                                    <span class="booking-date-icon" aria-hidden="true">
                                                        <i class="mdi mdi-calendar-month-outline"></i>
                                                    </span>
                                                    <input class="form-control manifest-date" type="text" id="end_date" value="" placeholder="Any date">
                                                </div>
                                            </div>
                                            <div class="col-md-2 manifest-filter-actions">
                                                <button type="button" class="btn btn-soft-secondary w-100" id="manifest_reset">
                                                    <i class="mdi mdi-filter-remove-outline"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="booking-my-toolbar manifest-toolbar">
                                        <div class="manifest-dt-buttons" id="manifest_dt_buttons"></div>
                                        <div class="booking-my-search">
                                            <i class="mdi mdi-magnify" aria-hidden="true"></i>
                                            <input type="search" id="myInputTextField" placeholder="Search date, bus or route">
                                        </div>
                                    </div>

                                    <div class="booking-my-table-wrap manifest-table-wrap">
                                        <table class="table table-hover align-middle mb-0 booking-trip-table booking-my-table manifest-table" id="example">
                                            <thead>
                                                <tr>
                                                    <th>Departure</th>
                                                    <th>Vehicle No.</th>
                                                    <th>Route</th>
                                                    <th>Driver</th>
                                                    <th>Conductor</th>
                                                    <th>Seats Sold</th>
                                                    <th>Ticket Revenue (TZS)</th>
                                                    <th>Road Collection (TZS)</th>
                                                    <th>Gross Revenue (TZS)</th>
                                                    <th>Remarks</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="booking-my-pagination manifest-pagination" id="manifest_pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> &copy; Tessera.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block text-muted">
                                SR Classic Coach
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <div class="offcanvas offcanvas-end manifest-offcanvas" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h6 class="booking-explorer-heading mb-0" id="offcanvasRightLabel">Update manifest</h6>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form class="needs-validation" name="event-form" id="form-event" novalidate>
                <div id="id_no" style="display:none;"></div>
                <div id="route_day_id" style="display:none;"></div>
                <div id="trip_date" style="display:none;"></div>
                <div id="buss" style="display:none;"></div>
                <div class="mb-3">
                    <label class="form-label" for="vehicel">Vehicle</label>
                    <select class="form-select" id="vehicel">
                        <option id="v_update"></option>
                        <?php echo $vehicle; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="drivers_name">Driver's name</label>
                    <input class="form-control" type="text" id="drivers_name" placeholder="Driver">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="conductor_update">Conductor's name</label>
                    <input class="form-control" type="text" id="conductor_update" placeholder="Conductor">
                </div>
                <button type="button" class="btn btn-primary booking-book-btn" id="save_changes">Change</button>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="js/sweetalert.js"></script>
    <script type="text/javascript" charset="utf8" src="js/datatable.js"></script>
    <script src="update_manifest.js"></script>
    <script>
        $(document).ready(function () {
            var routeSelect = document.getElementById('route_name');
            var vehicleSelect = document.getElementById('vecihle');
            var countEl = document.getElementById('manifest_count');
            var choices = [];
            var pickers = [];

            function reload() {
                if (window.oTable) {
                    window.oTable.ajax.reload();
                }
            }

            if (window.Choices) {
                [
                    [routeSelect, 'Search route'],
                    [vehicleSelect, 'Search vehicle']
                ].forEach(function (pair) {
                    choices.push(new window.Choices(pair[0], {
                        searchEnabled: true,
                        searchPlaceholderValue: pair[1],
                        itemSelectText: '',
                        shouldSort: false,
                        allowHTML: false
                    }));
                });
            }
            $(routeSelect).on('change', reload);
            $(vehicleSelect).on('change', reload);

            if (typeof window.flatpickr === 'function') {
                document.querySelectorAll('.manifest-date').forEach(function (input) {
                    pickers.push(window.flatpickr(input, {
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'D, j M Y',
                        allowInput: false,
                        disableMobile: true,
                        monthSelectorType: 'static',
                        onReady: function (selectedDates, dateStr, instance) {
                            instance.calendarContainer.classList.add('booking-flatpickr');
                            if (instance.altInput) {
                                instance.altInput.classList.add('form-control');
                                instance.altInput.setAttribute('placeholder', 'Any date');
                                instance.altInput.setAttribute('readonly', 'readonly');
                            }
                        },
                        onChange: reload
                    }));
                });
            }

            document.getElementById('manifest_reset').addEventListener('click', function () {
                choices.forEach(function (choice) {
                    choice.setChoiceByValue('');
                });
                routeSelect.value = '';
                vehicleSelect.value = '';
                pickers.forEach(function (picker) {
                    picker.clear(false);
                });
                $('#myInputTextField').val('');
                window.oTable.search('');
                reload();
            });

            function formatNumber(value) {
                return Number(value || 0).toLocaleString('en-US', { maximumFractionDigits: 0 });
            }

            function renderDashboard(summary) {
                if (!summary) {
                    return;
                }
                document.querySelectorAll('.manifest-stat-value[data-stat]').forEach(function (el) {
                    var value = summary[el.getAttribute('data-stat')];
                    el.textContent = (el.hasAttribute('data-money') ? 'TZS ' : '') + formatNumber(value);
                });
            }

            $('#example').on('xhr.dt', function (event, settings, json) {
                if (json) {
                    renderDashboard(json.summary);
                }
            });

            window.oTable = $('#example').DataTable({
                stripeClasses: [],
                processing: true,
                serverSide: true,
                serverMethod: 'post',
                ajax: {
                    url: 'fetch_manifest.php',
                    type: 'POST',
                    data: function (d) {
                        d.route = $('#route_name').val();
                        d.vecihle = $('#vecihle').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [
                    { data: 'trip_date_display' },
                    { data: 'buss' },
                    { data: 'route_name' },
                    { data: 'driver' },
                    { data: 'conductor' },
                    { data: 'seats' },
                    { data: 'ammount' },
                    { data: 'road_collection' },
                    { data: 't_amount', orderable: false, className: 'booking-trip-price' },
                    { data: 'remark', orderable: false },
                    { data: 'options', orderable: false, className: 'text-end' }
                ],
                lengthMenu: [[50, 350, 450, -1], [50, 325, 450, "All"]],
                language: {
                    processing: 'Loading manifests...',
                    info: 'Showing _START_-_END_ of _TOTAL_',
                    infoEmpty: 'Showing 0-0 of 0',
                    infoFiltered: '',
                    paginate: { previous: 'Prev', next: 'Next' }
                },
                dom: 'Brtip',
                buttons: [
                    { extend: 'copyHtml5', footer: true, exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'excelHtml5', footer: true, exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'csvHtml5', footer: true, exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'print', footer: true, exportOptions: { columns: ':not(:last-child)' } },
                    { extend: 'pdfHtml5', footer: true, exportOptions: { columns: ':not(:last-child)' } }
                ],
                initComplete: function () {
                    var wrapper = $('#example_wrapper');
                    wrapper.find('.dt-buttons').appendTo('#manifest_dt_buttons');
                    wrapper.find('.dataTables_info, .dataTables_paginate').appendTo('#manifest_pagination');
                },
                drawCallback: function () {
                    var total = this.api().page.info().recordsDisplay;
                    countEl.textContent = total + (total === 1 ? ' manifest' : ' manifests');
                    $('#example .manifest-options-toggle').each(function () {
                        bootstrap.Dropdown.getOrCreateInstance(this, {
                            popperConfig: function (config) {
                                config.strategy = 'fixed';
                                return config;
                            }
                        });
                    });
                },
                rowCallback: function (row, data) {
                    $(row).removeClass('is-due is-paid');
                    if (data.status == 'Due payment') {
                        $(row).addClass('is-due');
                    } else if (data.status == 'payed') {
                        $(row).addClass('is-paid');
                    }
                }
            });

            $('#myInputTextField').on('input', function () {
                window.oTable.search($(this).val()).draw();
            });

            $('#example').on('click', '.manifest-change', function () {
                var item = $(this);
                $('#id_no').text(item.data('id'));
                $('#route_day_id').text(item.data('route-day-id'));
                $('#trip_date').text(item.data('trip-date'));
                $('#buss').text(item.data('buss'));
                $('#v_update').text(item.data('buss'));
                $('#drivers_name').val(item.data('driver'));
                $('#conductor_update').val(item.data('conductor'));
            });

            $('#example').on('click', '.manifest-delete', function () {
                var id = $(this).data('id');
                if (!window.confirm('Delete this manifest?')) {
                    return;
                }
                $.ajax({
                    url: 'delete_manifest.php',
                    method: 'POST',
                    data: { id: id },
                    success: function (response) {
                        if ($.trim(response) == 'cant') {
                            swal({
                                title: 'Entries found',
                                text: 'you cannot delete.',
                                timer: 1000,
                                showConfirmButton: false
                            });
                        }
                        window.oTable.ajax.reload(null, false);
                    }
                });
            });
        });
    </script>

</body>

</html>
