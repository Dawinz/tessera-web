<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit;
}
if (!function_exists('modifed_time')) {
	function modifed_time(string $file): string {
		$path = __DIR__ . '/' . ltrim($file, '/');
		return file_exists($path) ? 'v=' . filemtime($path) : 'v=1';
	}
}
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Route Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Pichforest" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
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
    <link href="css/app/route_manager.css?v=<?= filemtime('css/app/route_manager.css') ?>" rel="stylesheet"
        type="text/css" />
    <link href="css/app/route-manager-dark.css?v=<?= filemtime('css/app/route-manager-dark.css') ?>" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/leaflet/leaflet.css" rel="stylesheet" type="text/css" />
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" type="text/css" href="css/datatable.css">
    <link rel="stylesheet" type="text/css" href="css/buttons.css">
    <script src="js/vendor/jquery-1.12.4.min.js"></script>


    <script src="assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>

    <!-- quill js -->
    <script src="assets/libs/quill/quill.min.js"></script>

    <!-- init js -->
    <script src="assets/js/pages/form-editor.init.js"></script>

</head>

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>


<body class="route-manager-page" data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <?php include 'top_header.php'; ?>


        <!-- ========== Left Sidebar Start ========== -->

        <!-- Left Sidebar End -->


        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="select_css.css">
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <?php
            include 'modules/connection/connect.php';
            include 'modules/connection/general_db.php';
            include 'modules/read/general/Read.php';
            $select = ['name', 'id', 'short'];
            $where = [];
            $locations_names = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "locations_names", 'order' => "", '' => ""]);
            $location_names_array = $locations_names->rows;

            $select = ['name', 'id'];
            $where = [];
            $currency = new Read(['select' => $select, 'pdo' => $pdo_general, 'where' => $where, 'table' => "currency", 'order' => "", '' => ""]);
            $currency_array = $currency->rows;

            $select = ['name', 'id', 'rating'];
            $where = [];
            $vehicle_class = new Read(['select' => $select, 'pdo' => $pdo_general, 'where' => $where, 'table' => "vehcile_category", 'order' => "", '' => ""]);
            $vehicle_class_array = $vehicle_class->rows;

            $select = ['name', 'id', 'category'];
            $where = [];
            $stops = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "stops", 'order' => "", '' => ""]);
            $stops_array = $stops->rows;
            $add_stops = '';
            foreach ($stops_array as $stop) {
                $add_stops .= '<option value="' . htmlspecialchars($stop['id']) . '">' . htmlspecialchars($stop['name']) . ' (' . htmlspecialchars($stop['category']) . ')</option>';
            }

            $add_vehicle_class = '';
            foreach ($vehicle_class_array as $vehicle_class) {
                $rating = number_format(min(max((float) $vehicle_class['rating'], 0), 5), 1, '.', '');
                $add_vehicle_class .= '<option value="' . htmlspecialchars($vehicle_class['id']) . '" data-rating="' . htmlspecialchars($rating) . '">' . htmlspecialchars($vehicle_class['name']) . '</option>';
            }

            $add_currency = '';
            foreach ($currency_array as $currency) {
                $add_currency .= '<option value="' . htmlspecialchars($currency['id']) . '">' . htmlspecialchars($currency['name']) . '</option>';
            }

            $add_route_locations = '';
            foreach ($location_names_array as $location) {
                $add_route_locations .= '<option value="' . htmlspecialchars($location['id']) . '">' . htmlspecialchars($location['name']) . '</option>';
            }
            $route_abbreviation = '';

            // The schedule panel is placeholder content, but its dates come from the
            // current week so the calendar still lines up with today.
            $today = new DateTimeImmutable('today');
            $week_days = [];
            for ($offset = 0; $offset < 7; $offset++) {
                $week_days[] = (new DateTimeImmutable('monday this week'))->modify("+$offset day");
            }
            $week_end = $week_days[6];
            $week_range = $week_days[0]->format('j')
                . ($week_days[0]->format('n') === $week_end->format('n') ? '' : ' ' . $week_days[0]->format('M'))
                . ' - ' . $week_end->format('j M Y');

            $day_class = function ($index) use ($week_days, $today) {
                return $week_days[$index]->format('Y-m-d') === $today->format('Y-m-d') ? ' is-today' : '';
            };

            // Real route names for the map markers. No lat/lng columns exist yet,
            // so each route is placed on a small ring around a default centre.
            $map_routes_stmt = $pdo->prepare(
                'SELECT route_name, start_route, end_route FROM routes WHERE company_id = :company_id'
            );
            $map_routes_stmt->execute(['company_id' => 1]);
            $map_routes_array = $map_routes_stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="page-content">


                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Route Management</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                    <li class="breadcrumb-item active">List of Routs</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="parent">

                </div>

                <div class="row">

                    <div class="col-xxl-3 col-xl-5 col-lg-5" id="route_network_panel" aria-busy="true">
                        <div class="card route-explorer-card">
                            <div class="card-header route-explorer-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="route-explorer-heading mb-0">
                                        Route Network
                                        <span class="route-explorer-count route-skeleton-shimmer route-explorer-skeleton-count"
                                            aria-hidden="true"></span>
                                    </h6>
                                    <button type="button" class="btn btn-soft-primary btn-sm route-explorer-add"
                                        data-bs-toggle="modal" data-bs-target="#myModal" title="Add Route">
                                        <i class="uil uil-plus"></i> Add Route
                                    </button>
                                </div>
                            </div><!-- end card header -->
                            <div class="card-body p-0">
                                <div class="route-explorer" role="status">
                                    <span class="visually-hidden">Loading route network...</span>

                                    <div class="route-node is-skeleton is-skeleton-open" aria-hidden="true">
                                        <div class="route-node-head-main">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-badge"></span>
                                            <div class="route-node-title">
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-name"
                                                    style="width:74%"></span>
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-path"
                                                    style="width:52%"></span>
                                            </div>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-caret"></span>
                                        </div>
                                        <div class="route-node-stats">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:58px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:64px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:52px"></span>
                                            <span
                                                class="route-skeleton-shimmer route-explorer-skeleton-stat route-explorer-skeleton-fare"
                                                style="width:78px"></span>
                                        </div>
                                        <div class="route-node-legs">
                                            <div class="route-node-legs-inner">
                                                <div class="route-node-legs-title">
                                                    <span
                                                        class="route-skeleton-shimmer route-explorer-skeleton-legs-label"></span>
                                                    <span
                                                        class="route-skeleton-shimmer route-explorer-skeleton-legs-count"></span>
                                                    <span
                                                        class="route-skeleton-shimmer route-explorer-skeleton-add-leg"></span>
                                                </div>
                                                <ol class="route-leg-list">
                                                    <li class="route-leg is-first is-skeleton-leg">
                                                        <span class="route-leg-marker">
                                                            <span
                                                                class="route-skeleton-shimmer route-explorer-skeleton-leg-index"></span>
                                                        </span>
                                                        <div class="route-leg-body">
                                                            <div class="route-leg-head">
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-name"
                                                                    style="width:62%"></span>
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-fare"></span>
                                                            </div>
                                                            <div class="route-leg-meta">
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-meta"
                                                                    style="width:44%"></span>
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-meta"
                                                                    style="width:22%"></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="route-leg is-skeleton-leg">
                                                        <span class="route-leg-marker">
                                                            <span
                                                                class="route-skeleton-shimmer route-explorer-skeleton-leg-index"></span>
                                                        </span>
                                                        <div class="route-leg-body">
                                                            <div class="route-leg-head">
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-name"
                                                                    style="width:54%"></span>
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-fare"></span>
                                                            </div>
                                                            <div class="route-leg-meta">
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-meta"
                                                                    style="width:38%"></span>
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-meta"
                                                                    style="width:20%"></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="route-leg is-last is-skeleton-leg">
                                                        <span class="route-leg-marker">
                                                            <span
                                                                class="route-skeleton-shimmer route-explorer-skeleton-leg-index"></span>
                                                        </span>
                                                        <div class="route-leg-body">
                                                            <div class="route-leg-head">
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-name"
                                                                    style="width:58%"></span>
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-fare"></span>
                                                            </div>
                                                            <div class="route-leg-meta">
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-meta"
                                                                    style="width:40%"></span>
                                                                <span
                                                                    class="route-skeleton-shimmer route-explorer-skeleton-leg-meta"
                                                                    style="width:24%"></span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="route-node is-skeleton" aria-hidden="true">
                                        <div class="route-node-head-main">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-badge"></span>
                                            <div class="route-node-title">
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-name"
                                                    style="width:68%"></span>
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-path"
                                                    style="width:48%"></span>
                                            </div>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-caret"></span>
                                        </div>
                                        <div class="route-node-stats">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:54px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:60px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:48px"></span>
                                            <span
                                                class="route-skeleton-shimmer route-explorer-skeleton-stat route-explorer-skeleton-fare"
                                                style="width:72px"></span>
                                        </div>
                                    </div>

                                    <div class="route-node is-skeleton" aria-hidden="true">
                                        <div class="route-node-head-main">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-badge"></span>
                                            <div class="route-node-title">
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-name"
                                                    style="width:72%"></span>
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-path"
                                                    style="width:44%"></span>
                                            </div>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-caret"></span>
                                        </div>
                                        <div class="route-node-stats">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:62px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:68px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:50px"></span>
                                            <span
                                                class="route-skeleton-shimmer route-explorer-skeleton-stat route-explorer-skeleton-fare"
                                                style="width:80px"></span>
                                        </div>
                                    </div>

                                    <div class="route-node is-skeleton" aria-hidden="true">
                                        <div class="route-node-head-main">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-badge"></span>
                                            <div class="route-node-title">
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-name"
                                                    style="width:58%"></span>
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-path"
                                                    style="width:40%"></span>
                                            </div>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-caret"></span>
                                        </div>
                                        <div class="route-node-stats">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:50px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:58px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:46px"></span>
                                            <span
                                                class="route-skeleton-shimmer route-explorer-skeleton-stat route-explorer-skeleton-fare"
                                                style="width:70px"></span>
                                        </div>
                                    </div>

                                    <div class="route-node is-skeleton" aria-hidden="true">
                                        <div class="route-node-head-main">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-badge"></span>
                                            <div class="route-node-title">
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-name"
                                                    style="width:70%"></span>
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-path"
                                                    style="width:50%"></span>
                                            </div>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-caret"></span>
                                        </div>
                                        <div class="route-node-stats">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:56px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:62px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:48px"></span>
                                            <span
                                                class="route-skeleton-shimmer route-explorer-skeleton-stat route-explorer-skeleton-fare"
                                                style="width:74px"></span>
                                        </div>
                                    </div>

                                    <div class="route-node is-skeleton" aria-hidden="true">
                                        <div class="route-node-head-main">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-badge"></span>
                                            <div class="route-node-title">
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-name"
                                                    style="width:76%"></span>
                                                <span class="route-skeleton-shimmer route-explorer-skeleton-path"
                                                    style="width:56%"></span>
                                            </div>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-caret"></span>
                                        </div>
                                        <div class="route-node-stats">
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:60px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:66px"></span>
                                            <span class="route-skeleton-shimmer route-explorer-skeleton-stat"
                                                style="width:52px"></span>
                                            <span
                                                class="route-skeleton-shimmer route-explorer-skeleton-stat route-explorer-skeleton-fare"
                                                style="width:82px"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                    <div class="col-xxl-6 col-xl-7 col-lg-7" id="route_schedule_panel" aria-busy="true">
                        <!-- Weekly board. Real trips come from route_schedules via
                             js/ajax-load/route_manager/schedules.js. Dates in the
                             rail stay on the current Monday-Sunday week. -->
                        <div class="card route-schedule-card">
                            <div class="card-header route-schedule-header">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="route-schedule-heading mb-0">
                                        Schedules
                                        <span class="route-skeleton-shimmer route-schedule-skeleton-count"
                                            aria-hidden="true"></span>
                                    </h6>
                                    <button type="button" class="btn btn-soft-primary btn-sm route-schedule-add"
                                        data-bs-toggle="modal" data-bs-target="#schedule_modal" title="Add Schedule">
                                        <i class="uil uil-plus"></i> Add Schedule
                                    </button>
                                </div>
                                <div class="route-schedule-subhead">
                                    <span class="route-skeleton-shimmer route-schedule-skeleton-route"
                                        aria-hidden="true"></span>
                                    <span class="route-schedule-range"><?= $week_range ?></span>
                                </div>
                            </div><!-- end card header -->
                            <div class="card-body p-0">
                                <div class="route-schedule" role="status">
                                    <span class="visually-hidden">Loading schedules...</span>
                                    <?php
                                    $schedule_day_names = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                    foreach ($schedule_day_names as $index => $day_name) {
                                    ?>
                                    <section class="schedule-day is-skeleton<?= $day_class($index) ?>" aria-hidden="true">
                                        <div class="schedule-day-rail">
                                            <span class="schedule-day-name"><?= $day_name ?></span>
                                            <span class="schedule-day-date"><?= $week_days[$index]->format('j') ?></span>
                                            <span class="route-skeleton-shimmer route-schedule-skeleton-day-count"></span>
                                        </div>
                                        <div class="schedule-day-body">
                                            <ul class="schedule-trip-list">
                                                <li class="schedule-trip">
                                                    <span class="route-skeleton-shimmer route-schedule-skeleton-time"></span>
                                                    <div class="schedule-trip-body">
                                                        <div class="schedule-trip-head">
                                                            <span class="route-skeleton-shimmer route-schedule-skeleton-bus"></span>
                                                            <span class="route-skeleton-shimmer route-schedule-skeleton-status"></span>
                                                        </div>
                                                        <span class="route-skeleton-shimmer route-schedule-skeleton-bar"></span>
                                                    </div>
                                                </li>
                                                <?php if ($index % 2 === 0) { ?>
                                                <li class="schedule-trip">
                                                    <span class="route-skeleton-shimmer route-schedule-skeleton-time"></span>
                                                    <div class="schedule-trip-body">
                                                        <div class="schedule-trip-head">
                                                            <span class="route-skeleton-shimmer route-schedule-skeleton-bus" style="width:48%"></span>
                                                            <span class="route-skeleton-shimmer route-schedule-skeleton-status"></span>
                                                        </div>
                                                        <span class="route-skeleton-shimmer route-schedule-skeleton-bar"></span>
                                                    </div>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </section>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                    <?php include 'modules/read/views/modal/route_points.php'; ?>
                </div>

                <!-- Add Route Modal -->
                <?php include 'modules/read/views/modal/register_route.php'; ?>
                <!-- end Add Route Modal -->

                <!-- Add Sub Route Modal, opened from the "Sub route" button of a route -->
                <?php include 'modules/read/views/modal/register_sub_route.php'; ?>
                <!-- end Add Sub Route Modal -->

                <!-- Add Schedule Modal -->
                <?php include 'modules/read/views/modal/register_schedule.php'; ?>
                <!-- end Add Schedule Modal -->

                <!-- Route Details modal, filled in by js/filter/read/route_manager/route_details.js -->
                <div class="modal fade route-details-modal" id="route_details_modal" tabindex="-1" role="dialog"
                    aria-labelledby="route_details_title" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <h5 class="modal-title" id="route_details_title">Route Details</h5>
                                    <small class="text-muted" id="route_details_subtitle"></small>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="route_details_body"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                <!-- end Route Details modal -->

                <script>
                    (function () {
                        var $tabs = $('.add-route-wizard-tab');
                        var $navItems = $('#add_route_wizard_nav .list-item');
                        var currentStep = 0;

                        function showStep(index) {
                            $tabs.removeClass('active-step').eq(index).addClass('active-step');
                            $navItems.removeClass('active done');
                            $navItems.each(function (i) {
                                if (i < index) {
                                    $(this).addClass('done');
                                } else if (i === index) {
                                    $(this).addClass('active');
                                }
                            });
                            $('#add_route_prev').toggleClass('d-none', index === 0);
                            $('#add_route_next').toggleClass('d-none', index === $tabs.length - 1);
                            $('#add_route_submit').toggleClass('d-none', index !== $tabs.length - 1);
                            currentStep = index;
                        }

                        $('#add_route_next').on('click', function () {
                            if (currentStep < $tabs.length - 1) {
                                showStep(currentStep + 1);
                            }
                        });

                        $('#add_route_prev').on('click', function () {
                            if (currentStep > 0) {
                                showStep(currentStep - 1);
                            }
                        });

                        $('#myModal').on('shown.bs.modal', function () {
                            showStep(0);
                        });

                        showStep(0);

                        $('#add_pick_up_point_btn').on('click', function () {
                            var $newRow = $('#pick_up_points_rows .pick-up-point-row:first').clone();
                            $newRow.find('select').val($newRow.find('select option:first').val());
                            $('#pick_up_points_rows').append($newRow);
                        });

                        $('#pick_up_points_rows').on('click', '.remove_pick_up_point', function () {
                            if ($('#pick_up_points_rows .pick-up-point-row').length > 1) {
                                $(this).closest('.pick-up-point-row').remove();
                            }
                        });

                        $('#add_arrival_point_btn').on('click', function () {
                            var $newRow = $('#arrival_point_rows .arrival-point-row:first').clone();
                            $newRow.find('select').val($newRow.find('select option:first').val());
                            $('#arrival_point_rows').append($newRow);
                        });

                        $('#arrival_point_rows').on('click', '.remove_arrival_point', function () {
                            if ($('#arrival_point_rows .arrival-point-row').length > 1) {
                                $(this).closest('.arrival-point-row').remove();
                            }
                        });

                        $('#add_ticket_price_btn').on('click', function () {
                            var $newRow = $('#ticket_price_rows .ticket-price-row:first').clone();
                            $newRow.find('select').val($newRow.find('select option:first').val());
                            $newRow.find('input').val('');
                            $('#ticket_price_rows').append($newRow);
                        });

                        $('#ticket_price_rows').on('click', '.remove_ticket_price', function () {
                            if ($('#ticket_price_rows .ticket-price-row').length > 1) {
                                $(this).closest('.ticket-price-row').remove();
                            }
                        });

                        $('#add_stop_btn').on('click', function () {
                            var $newRow = $('#stops_rows .route-stop-row:first').clone();
                            $newRow.find('input').val('');
                            $newRow.find('select').val($newRow.find('select option:first').val());
                            $('#stops_rows').append($newRow);
                        });

                        $('#stops_rows').on('click', '.remove-stop', function () {
                            if ($('#stops_rows .route-stop-row').length > 1) {
                                $(this).closest('.route-stop-row').remove();
                            }
                        });
                    })();
                </script>
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
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
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
    <script src="js/insert/route_insert.js"></script>
    <script src="js/insert/add_transit.js"></script>
    <script src="update_route.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
    <script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>

    <!-- init js -->
    <script src="assets/js/pages/form-advanced.init.js"></script>
    <script src="js/insert/wizard/register_route.js?<?= modifed_time("js/insert/wizard/register_route.js") ?>"></script>
    <script
        src="js/insert/wizard/register_sub_route.js?<?= modifed_time("js/insert/wizard/register_sub_route.js") ?>"></script>
    <script
        src="js/dependence/location_dependecy.js?<?= modifed_time("js/dependence/location_dependecy.js") ?>"></script>
    <script
        src="js/dependence/sub_location_dependecy.js?<?= modifed_time("js/dependence/sub_location_dependecy.js") ?>"></script>
    <script
        src="js/filter/read/route_manager/route_details.js?<?= modifed_time("js/filter/read/route_manager/route_details.js") ?>"></script>
    <script
        src="js/ajax-load/route_manager/routes.js?<?= modifed_time("js/ajax-load/route_manager/routes.js") ?>"></script>
    <script
        src="js/ajax-load/route_manager/route_points.js?<?= modifed_time("js/ajax-load/route_manager/route_points.js") ?>"></script>
    <script
        src="js/ajax-load/route_manager/schedules.js?<?= modifed_time("js/ajax-load/route_manager/schedules.js") ?>"></script>
    <script
        src="js/insert/wizard/register_schedule.js?<?= modifed_time("js/insert/wizard/register_schedule.js") ?>"></script>
    <script
        src="js/insert/route-manager/schedule.js?<?= modifed_time("js/insert/route-manager/schedule.js") ?>"></script>
    <script
        src="js/update/route_manger/depature_time.js?<?= modifed_time("js/update/route_manger/depature_time.js") ?>"></script>

    <script src="assets/libs/leaflet/leaflet.js"></script>
    <script>
        var MAP_ROUTES = <?= json_encode(array_map(function ($route) {
            return [
                'name' => $route['route_name'],
                'from' => $route['start_route'],
                'to' => $route['end_route'],
            ];
        }, $map_routes_array), JSON_UNESCAPED_UNICODE) ?>;

        function initRoutesMap() {
            var mapEl = document.getElementById("routes_map");
            if (!mapEl || typeof L === "undefined") {
                return;
            }

            var center = [-1.286389, 36.817223];
            var map = L.map(mapEl).setView(center, 6);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // No lat/lng columns exist for routes yet, so each real route name
            // is placed on a small ring around the default centre for now.
            var radius = 0.35;
            MAP_ROUTES.forEach(function (route, index) {
                var angle = (index / MAP_ROUTES.length) * 2 * Math.PI;
                var lat = center[0] + radius * Math.sin(angle);
                var lng = center[1] + radius * Math.cos(angle);
                var label = route.name + (route.from && route.to ? ' (' + route.from + ' -> ' + route.to + ')' : '');
                L.marker([lat, lng]).addTo(map)
                    .bindPopup(label)
                    .bindTooltip(route.name, { permanent: true, direction: "top", className: "routes-map-label" });
            });
        }

        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", initRoutesMap);
        } else {
            initRoutesMap();
        }
    </script>

</body>

</html>