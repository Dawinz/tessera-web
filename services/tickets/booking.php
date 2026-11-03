<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit;
}
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Booking</title>
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
    <link href="assets/css/vehicle-manager.css?v=<?= filemtime(__DIR__ . '/assets/css/vehicle-manager.css') ?>" rel="stylesheet" type="text/css" />
    <link href="css/app/booking.css?v=<?= filemtime('css/app/booking.css') ?>" rel="stylesheet" type="text/css" />
    <link href="css/app/booking-dark.css?v=<?= filemtime('css/app/booking-dark.css') ?>" rel="stylesheet" type="text/css" />
    <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" type="text/css" href="css/datatable.css">
    <link rel="stylesheet" type="text/css" href="css/buttons.css">
    <script src="js/vendor/jquery-1.12.4.min.js"></script>

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


<body class="booking-page" data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">

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
                <div class="container-fluid">
                    <?php
                    $booking_trips = [
                        ['route' => 'DSM - ARU', 'from' => 'Dar es Salaam', 'to' => 'Arusha', 'departure' => '06:00', 'arrival' => '14:30', 'bus' => '1101AB25', 'class' => 'Luxury', 'price' => 35000, 'left' => 33, 'seats' => 45],
                        ['route' => 'DSM - ARU', 'from' => 'Dar es Salaam', 'to' => 'Arusha', 'departure' => '14:00', 'arrival' => '22:15', 'bus' => '1102AB25', 'class' => 'Semi-luxury', 'price' => 35000, 'left' => 21, 'seats' => 45],
                        ['route' => 'DSM - MWZ', 'from' => 'Dar es Salaam', 'to' => 'Mwanza', 'departure' => '07:30', 'arrival' => '21:00', 'bus' => 'T 234 ABC', 'class' => 'Luxury', 'price' => 45000, 'left' => 8, 'seats' => 49],
                        ['route' => 'ARU - DSM', 'from' => 'Arusha', 'to' => 'Dar es Salaam', 'departure' => '08:00', 'arrival' => '16:20', 'bus' => '1101AB25', 'class' => 'Luxury', 'price' => 35000, 'left' => 40, 'seats' => 45],
                        ['route' => 'DOD - MBY', 'from' => 'Dodoma', 'to' => 'Mbeya', 'departure' => '09:15', 'arrival' => '17:40', 'bus' => 'MB 118 TZ', 'class' => 'Ordinary', 'price' => 28000, 'left' => 29, 'seats' => 33],
                        ['route' => 'DSM - DOD', 'from' => 'Dar es Salaam', 'to' => 'Dodoma', 'departure' => '05:30', 'arrival' => '12:10', 'bus' => '1103AB25', 'class' => 'Luxury', 'price' => 30000, 'left' => 18, 'seats' => 45],
                        ['route' => 'MWZ - DSM', 'from' => 'Mwanza', 'to' => 'Dar es Salaam', 'departure' => '06:45', 'arrival' => '20:00', 'bus' => 'T 235 ABC', 'class' => 'Semi-luxury', 'price' => 45000, 'left' => 11, 'seats' => 49],
                        ['route' => 'ARU - MWZ', 'from' => 'Arusha', 'to' => 'Mwanza', 'departure' => '07:00', 'arrival' => '15:30', 'bus' => 'AR 441 TZ', 'class' => 'Ordinary', 'price' => 32000, 'left' => 24, 'seats' => 41],
                    ];
                    $selected_trip = $booking_trips[0];
                    $seat_rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
                    $booked_seats = ['A1', 'A2', 'B3', 'C4', 'D1', 'E2', 'F4', 'G1', 'H3'];
                    $hold_seats = ['C1', 'C2', 'K4'];
                    $total_chart_seats = count($seat_rows) * 4;
                    $total_booked = count($booked_seats);
                    $total_hold = count($hold_seats);
                    $total_empty = $total_chart_seats - $total_booked - $total_hold;
                    $my_bookings = [
                        ['seat' => 'A1', 'ticket' => '10421', 'name' => 'Amina Juma', 'phone' => '+255 712 334 221', 'cost' => 35000, 'date' => '18 Sep 2026', 'status' => 'complete'],
                        ['seat' => 'A2', 'ticket' => '10422', 'name' => 'Joseph Mwamba', 'phone' => '+255 754 118 090', 'cost' => 35000, 'date' => '18 Sep 2026', 'status' => 'booked'],
                        ['seat' => 'B3', 'ticket' => '10428', 'name' => 'Neema Ally', 'phone' => '+255 765 442 110', 'cost' => 35000, 'date' => '18 Sep 2026', 'status' => 'complete'],
                        ['seat' => 'C4', 'ticket' => '10431', 'name' => 'Peter Kimaro', 'phone' => '+255 713 990 441', 'cost' => 35000, 'date' => '18 Sep 2026', 'status' => 'hold'],
                        ['seat' => 'D1', 'ticket' => '10435', 'name' => 'Fatma Hassan', 'phone' => '+255 678 221 009', 'cost' => 35000, 'date' => '18 Sep 2026', 'status' => 'complete'],
                        ['seat' => 'E2', 'ticket' => '10438', 'name' => 'Daniel Mushi', 'phone' => '+255 755 880 112', 'cost' => 35000, 'date' => '18 Sep 2026', 'status' => 'booked'],
                        ['seat' => 'F4', 'ticket' => '10442', 'name' => 'Grace Lyimo', 'phone' => '+255 714 560 778', 'cost' => 35000, 'date' => '17 Sep 2026', 'status' => 'complete'],
                        ['seat' => 'G1', 'ticket' => '10447', 'name' => 'Samuel Nyerere', 'phone' => '+255 762 334 900', 'cost' => 35000, 'date' => '17 Sep 2026', 'status' => 'hold'],
                    ];
                    $my_booking_trips = [0, 0, 1, 0, 5, 1, 3, 0];
                    $my_booking_payments = ['Cash', 'M-Pesa', 'Tigo Pesa', 'Cash', 'Banking', 'M-Pesa', 'Cash', 'M-Pesa'];
                    $my_booking_agents = ['Halima (Counter 1)', 'Juma (Counter 2)', 'Online', 'Halima (Counter 1)', 'Juma (Counter 2)', 'Online', 'Halima (Counter 1)', 'Juma (Counter 2)'];
                    foreach ($my_bookings as $booking_index => $booking) {
                        $booking_trip = $booking_trips[$my_booking_trips[$booking_index] ?? 0];
                        $name_parts = explode(' ', $booking['name'], 2);
                        $my_bookings[$booking_index] += [
                            'trip_index' => $my_booking_trips[$booking_index] ?? 0,
                            'route' => $booking_trip['route'],
                            'route_detail' => $booking_trip['from'] . ' -> ' . $booking_trip['to'],
                            'bus' => $booking_trip['bus'],
                            'class' => $booking_trip['class'],
                            'departure' => $booking_trip['departure'],
                            'arrival' => $booking_trip['arrival'],
                            'firstname' => $name_parts[0],
                            'lastname' => $name_parts[1] ?? '',
                            'email' => strtolower(str_replace(' ', '.', $booking['name'])) . '@example.com',
                            'nationality' => 'Tanzanian',
                            'id_type' => 'National ID',
                            'id_number' => sprintf('1990%04d-%05d-0000%d', 1000 + $booking_index * 37, 12000 + $booking_index * 311, $booking_index + 1),
                            'payment' => $booking['status'] === 'hold' ? 'Pending' : $my_booking_payments[$booking_index],
                            'booked_by' => $my_booking_agents[$booking_index],
                            'booked_at' => date('d M Y, H:i', strtotime('2026-09-15 08:10') + $booking_index * 5400),
                            'hold_until' => $booking['status'] === 'hold' ? '18 Sep 2026, 05:30' : '',
                        ];
                    }
                    $booking_status_labels = ['complete' => 'Paid', 'booked' => 'Booked', 'hold' => 'Hold'];

                    $manifest_first = ['Amina', 'Joseph', 'Neema', 'Peter', 'Fatma', 'Daniel', 'Grace', 'Samuel', 'Rehema', 'John', 'Zawadi', 'Ibrahim', 'Mary', 'Hassan', 'Upendo', 'Emmanuel'];
                    $manifest_last = ['Juma', 'Mwamba', 'Ally', 'Kimaro', 'Hassan', 'Mushi', 'Lyimo', 'Nyerere', 'Said', 'Massawe', 'Mrema', 'Mollel', 'Swai', 'Temba'];
                    $manifest_statuses = ['complete', 'complete', 'booked', 'hold'];
                    $trip_manifests = [];
                    foreach ($booking_trips as $trip_index => $trip) {
                        mt_srand($trip_index + 1);
                        $passengers = [];
                        $booked = max(0, $trip['seats'] - $trip['left']);
                        for ($p = 0; $p < $booked; $p++) {
                            $passengers[] = [
                                'seat' => chr(65 + intdiv($p, 4)) . (($p % 4) + 1),
                                'ticket' => (string) (10400 + $trip_index * 100 + $p + 1),
                                'name' => $manifest_first[mt_rand(0, count($manifest_first) - 1)] . ' ' . $manifest_last[mt_rand(0, count($manifest_last) - 1)],
                                'phone' => sprintf('+255 7%02d %03d %03d', mt_rand(10, 79), mt_rand(0, 999), mt_rand(0, 999)),
                                'fare' => $trip['price'],
                                'status' => $manifest_statuses[mt_rand(0, count($manifest_statuses) - 1)],
                            ];
                        }
                        $trip_manifests[$trip_index] = $passengers;
                    }
                    mt_srand();
                    ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Booking</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apps</a></li>
                                        <li class="breadcrumb-item active">Available trips</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xxl-8 col-xl-8 col-lg-7">
                            <div class="card booking-explorer-card">
                                <div class="card-header">
                                    <h6 class="booking-explorer-heading mb-0">Filter destination</h6>
                                </div>
                                <div class="card-body">
                                    <form class="booking-filter" autocomplete="off">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label" for="booking_from">Start location</label>
                                                <select class="booking-location-select" id="booking_from">
                                                    <option value="">Select location</option>
                                                    <option value="Dar es Salaam" selected>Dar es Salaam</option>
                                                    <option value="Arusha">Arusha</option>
                                                    <option value="Mwanza">Mwanza</option>
                                                    <option value="Dodoma">Dodoma</option>
                                                    <option value="Mbeya">Mbeya</option>
                                                    <option value="Moshi">Moshi</option>
                                                    <option value="Morogoro">Morogoro</option>
                                                    <option value="Tanga">Tanga</option>
                                                    <option value="Iringa">Iringa</option>
                                                    <option value="Kigoma">Kigoma</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label" for="booking_to">End location</label>
                                                <select class="booking-location-select" id="booking_to">
                                                    <option value="">Select destination</option>
                                                    <option value="Dar es Salaam">Dar es Salaam</option>
                                                    <option value="Arusha" selected>Arusha</option>
                                                    <option value="Mwanza">Mwanza</option>
                                                    <option value="Dodoma">Dodoma</option>
                                                    <option value="Mbeya">Mbeya</option>
                                                    <option value="Moshi">Moshi</option>
                                                    <option value="Morogoro">Morogoro</option>
                                                    <option value="Tanga">Tanga</option>
                                                    <option value="Iringa">Iringa</option>
                                                    <option value="Kigoma">Kigoma</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label" for="booking_date">Trip date</label>
                                                <div class="booking-date-field">
                                                    <span class="booking-date-icon" aria-hidden="true">
                                                        <i class="mdi mdi-calendar-month-outline"></i>
                                                    </span>
                                                    <input class="form-control" type="text" id="booking_date" value="2026-09-18" placeholder="Select trip date">
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="booking-trip-table-shell">
                                        <div class="booking-trip-table-wrap">
                                        <table class="table table-hover align-middle mb-0 booking-trip-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Route name</th>
                                                    <th>Bus</th>
                                                    <th>Time</th>
                                                    <th>Class</th>
                                                    <th>Seats</th>
                                                    <th>Fare</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($booking_trips as $index => $trip) { ?>
                                                <tr class="<?= $index === 0 ? 'is-selected' : '' ?>">
                                                    <td><?= $index + 1 ?></td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($trip['route']) ?></strong>
                                                        <div class="booking-table-sub"><?= htmlspecialchars($trip['from']) ?> -> <?= htmlspecialchars($trip['to']) ?></div>
                                                    </td>
                                                    <td><?= htmlspecialchars($trip['bus']) ?></td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($trip['departure']) ?></strong>
                                                        <div class="booking-table-sub">Arr <?= htmlspecialchars($trip['arrival']) ?></div>
                                                    </td>
                                                    <td><?= htmlspecialchars($trip['class']) ?></td>
                                                    <td>
                                                        <span class="booking-trip-seats<?= $trip['left'] < 10 ? ' is-low' : '' ?>">
                                                            <?= (int) $trip['left'] ?>/<?= (int) $trip['seats'] ?>
                                                        </span>
                                                    </td>
                                                    <td class="booking-trip-price">TZS <?= number_format($trip['price']) ?></td>
                                                    <td class="text-center">
                                                        <div class="booking-my-actions booking-trip-actions">
                                                            <button type="button" class="btn btn-soft-primary btn-sm booking-trip-chart-btn">
                                                                View chart
                                                            </button>
                                                            <button type="button" class="btn btn-soft-success btn-sm" title="Hire bus" data-action="hire"
                                                                data-bs-toggle="modal" data-bs-target="#booking_hire_modal"
                                                                data-bus="<?= htmlspecialchars($trip['bus']) ?>"
                                                                data-route="<?= htmlspecialchars($trip['from'] . ' -> ' . $trip['to']) ?>"
                                                                data-time="<?= htmlspecialchars($trip['departure'] . ' - ' . $trip['arrival']) ?>"
                                                                data-class="<?= htmlspecialchars($trip['class']) ?>"
                                                                data-seats="<?= (int) $trip['seats'] ?>"
                                                                data-fare="<?= (int) $trip['price'] ?>">
                                                                <i class="mdi mdi-handshake-outline"></i> Hire
                                                            </button>
                                                            <button type="button" class="btn btn-soft-warning btn-sm" title="Lock seats" data-action="lock"
                                                                data-bs-toggle="modal" data-bs-target="#booking_lock_modal"
                                                                data-trip-index="<?= (int) $index ?>"
                                                                data-bus="<?= htmlspecialchars($trip['bus']) ?>"
                                                                data-route="<?= htmlspecialchars($trip['from'] . ' -> ' . $trip['to']) ?>"
                                                                data-time="<?= htmlspecialchars($trip['departure'] . ' - ' . $trip['arrival']) ?>"
                                                                data-seats="<?= (int) $trip['seats'] ?>">
                                                                <i class="mdi mdi-lock-outline"></i> Lock seats<span class="booking-lock-count" hidden></span>
                                                            </button>
                                                            <button type="button" class="btn btn-soft-info btn-sm" title="Manifest"
                                                                data-bs-toggle="modal" data-bs-target="#booking_manifest_modal"
                                                                data-trip-index="<?= (int) $index ?>"
                                                                data-bus="<?= htmlspecialchars($trip['bus']) ?>"
                                                                data-route="<?= htmlspecialchars($trip['from'] . ' -> ' . $trip['to']) ?>"
                                                                data-time="<?= htmlspecialchars($trip['departure'] . ' - ' . $trip['arrival']) ?>">
                                                                <i class="mdi mdi-clipboard-list-outline"></i> Manifest
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        </div>
                                        <div class="booking-trip-resize" role="separator" aria-label="Stretch trip list" title="Drag to stretch">
                                            <span class="booking-trip-resize-grip" aria-hidden="true">
                                                <i class="mdi mdi-chevron-double-up"></i>
                                                <i class="mdi mdi-chevron-double-down"></i>
                                            </span>
                                        </div>
                                        </div>
                                </div>
                            </div>

                            <div class="card booking-explorer-card booking-passenger-card" id="booking_passenger_panel" data-ticket-count="<?= count($my_bookings) ?>">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h6 class="booking-explorer-heading mb-0" id="booking_passenger_heading">My booking</h6>
                                    <div class="booking-pane-switch" id="booking_pane_switch" role="tablist" aria-label="Switch view" data-active="table">
                                        <span class="booking-pane-switch-thumb" aria-hidden="true"></span>
                                        <button type="button" role="tab" data-pane="table" aria-selected="false">
                                            <i class="mdi mdi-ticket-outline"></i> My booking
                                        </button>
                                        <button type="button" role="tab" data-pane="form" aria-selected="true">
                                            <i class="mdi mdi-account-edit-outline"></i> Passenger details
                                        </button>
                                    </div>
                                    <span class="booking-explorer-count" id="booking_passenger_count"><?= count($my_bookings) ?> tickets</span>
                                </div>
                                <div class="card-body">
                                    <div class="booking-passenger-slider">
                                        <div class="booking-passenger-track">
                                            <div class="booking-passenger-pane is-table">
                                                <div class="booking-my-toolbar">
                                                    <div class="booking-my-search">
                                                        <i class="mdi mdi-magnify" aria-hidden="true"></i>
                                                        <input type="search" id="booking_my_search" placeholder="Search name, ticket or seat">
                                                    </div>
                                                </div>
                                                <div class="booking-my-table-wrap">
                                                    <table class="table table-hover align-middle mb-0 booking-trip-table booking-my-table" id="booking_my_table">
                                                        <thead>
                                                            <tr>
                                                                <th>Seat</th>
                                                                <th>Ticket</th>
                                                                <th>Customer</th>
                                                                <th>Route</th>
                                                                <th>Fare</th>
                                                                <th>Trip date</th>
                                                                <th>Status</th>
                                                                <th class="text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($my_bookings as $booking) { ?>
                                                            <tr data-ticket="<?= htmlspecialchars($booking['ticket']) ?>">
                                                                <td>
                                                                    <span class="booking-my-seat is-<?= htmlspecialchars($booking['status']) ?>" data-cell="seat"><?= htmlspecialchars($booking['seat']) ?></span>
                                                                </td>
                                                                <td><?= htmlspecialchars($booking['ticket']) ?></td>
                                                                <td>
                                                                    <strong data-cell="name"><?= htmlspecialchars($booking['name']) ?></strong>
                                                                    <div class="booking-table-sub" data-cell="phone"><?= htmlspecialchars($booking['phone']) ?></div>
                                                                </td>
                                                                <td>
                                                                    <strong data-cell="route"><?= htmlspecialchars($booking['route']) ?></strong>
                                                                    <div class="booking-table-sub" data-cell="route_detail"><?= htmlspecialchars($booking['route_detail']) ?></div>
                                                                </td>
                                                                <td class="booking-trip-price" data-cell="cost">TZS <?= number_format($booking['cost']) ?></td>
                                                                <td data-cell="date"><?= htmlspecialchars($booking['date']) ?></td>
                                                                <td>
                                                                    <span class="booking-manifest-status is-<?= htmlspecialchars($booking['status']) ?>" data-cell="status"><?= htmlspecialchars($booking_status_labels[$booking['status']] ?? ucfirst($booking['status'])) ?></span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="booking-my-actions">
                                                                        <button type="button" class="btn btn-soft-info btn-sm" title="View" data-bs-toggle="modal" data-bs-target="#booking_my_view_modal"><i class="mdi mdi-eye-outline"></i></button>
                                                                        <button type="button" class="btn btn-soft-primary btn-sm" title="Print" data-bs-toggle="modal" data-bs-target="#booking_my_print_modal"><i class="mdi mdi-printer"></i></button>
                                                                        <button type="button" class="btn btn-soft-warning btn-sm" title="Unlock" data-bs-toggle="modal" data-bs-target="#booking_my_unlock_modal"><i class="mdi mdi-lock-open-variant-outline"></i></button>
                                                                        <button type="button" class="btn btn-soft-secondary btn-sm" title="Transfer" data-bs-toggle="modal" data-bs-target="#booking_my_transfer_modal"><i class="mdi mdi-swap-horizontal"></i></button>
                                                                        <button type="button" class="btn btn-soft-success btn-sm" title="Edit" data-bs-toggle="modal" data-bs-target="#booking_my_edit_modal"><i class="mdi mdi-pencil-outline"></i></button>
                                                                        <button type="button" class="btn btn-soft-danger btn-sm" title="Delete" data-bs-toggle="modal" data-bs-target="#booking_my_delete_modal"><i class="mdi mdi-trash-can-outline"></i></button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="booking-my-pagination">
                                                    <span class="booking-my-page-info" id="booking_my_page_info">Showing 1-4 of 8</span>
                                                    <div class="booking-my-page-btns" id="booking_my_page_btns"></div>
                                                </div>
                                            </div>
                                            <div class="booking-passenger-pane is-form">
                                                <div class="booking-passenger-empty" id="booking_passenger_empty">
                                                    <i class="mdi mdi-seat-passenger" aria-hidden="true"></i>
                                                    <strong>No seat selected</strong>
                                                    <span>Select a seat on the chart to add passenger details</span>
                                                </div>
                                                <div class="booking-passenger-list" id="booking_passenger_list"></div>
                                                <div class="booking-passenger-actions">
                                                    <div class="booking-payment-field">
                                                        <label class="form-label" for="booking_payment">Payment method</label>
                                                        <select class="form-select" id="booking_payment">
                                                            <option value="">Select payment</option>
                                                            <option value="cash">Cash</option>
                                                            <option value="mpesa">M-Pesa</option>
                                                            <option value="tigopesa">Tigo Pesa</option>
                                                            <option value="banking">Banking</option>
                                                            <option value="other">Other</option>
                                                        </select>
                                                    </div>
                                                    <button type="button" class="btn btn-primary booking-book-btn">Book</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-4 col-lg-5">
                            <div class="card booking-workspace-card">
                                <div class="card-body p-0">
                                    <div class="vehicle-workspace-chart booking-seat-panel">
                                        <div class="vehicle-deck-wrap">
                                            <div class="vehicle-deck">
                                                <div class="vehicle-deck-front">
                                                    <span class="vehicle-door-slot"></span>
                                                    <div class="vehicle-seat-row vehicle-driver-row" style="width:224px">
                                                        <span class="vehicle-seat vehicle-steering-slot is-empty"></span>
                                                        <span class="vehicle-driver-gap">
                                                            <span class="vehicle-bus-number" title="Bus number">
                                                                <span class="vehicle-plate-screw" aria-hidden="true"></span>
                                                                <span class="vehicle-plate-text"><?= htmlspecialchars($selected_trip['bus']) ?></span>
                                                                <span class="vehicle-plate-screw" aria-hidden="true"></span>
                                                            </span>
                                                        </span>
                                                        <span class="vehicle-seat vehicle-steering-slot" title="Steering wheel">
                                                            <img class="vehicle-steering-img" src="css/app/vehicle-steering.svg" alt="">
                                                        </span>
                                                    </div>
                                                    <span class="vehicle-door-slot"></span>
                                                </div>
                                                <?php foreach ($seat_rows as $row_index => $row_letter) { ?>
                                                <div class="vehicle-seat-row-wrap">
                                                    <span class="vehicle-door-slot">
                                                        <?php if ($row_index === 4) { ?>
                                                        <span class="vehicle-door" title="Door">Door</span>
                                                        <?php } ?>
                                                    </span>
                                                    <div class="vehicle-seat-row">
                                                        <?php
                                                        $numbers = [1, 2, null, 3, 4];
                                                        foreach ($numbers as $number) {
                                                            if ($number === null) {
                                                                echo '<span class="vehicle-seat is-aisle"></span>';
                                                                continue;
                                                            }
                                                            $seat_no = $row_letter . $number;
                                                            $state = '';
                                                            if (in_array($seat_no, $hold_seats, true)) {
                                                                $state = ' is-disabled';
                                                            } elseif (in_array($seat_no, $booked_seats, true)) {
                                                                $state = ' is-reserved';
                                                            }
                                                        ?>
                                                        <span class="vehicle-seat<?= $state ?>" data-seat="<?= htmlspecialchars($seat_no) ?>">
                                                            <img class="vehicle-seat-img" src="css/app/vehicle-seat.svg" alt="">
                                                            <span class="vehicle-seat-label"><?= htmlspecialchars($seat_no) ?></span>
                                                        </span>
                                                        <?php } ?>
                                                    </div>
                                                    <span class="vehicle-door-slot"></span>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <div class="booking-seat-side">
                                                <div class="vehicle-seat-legend">
                                                    <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch"></span> Empty</span>
                                                    <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch is-hold"></span> Hold</span>
                                                    <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch is-booked"></span> Booked</span>
                                                    <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch is-door"></span> Door</span>
                                                </div>
                                                <div class="booking-seat-summary">
                                                    <div class="booking-seat-summary-item">
                                                        <span>Booked seats</span>
                                                        <strong><?= $total_booked ?></strong>
                                                    </div>
                                                    <div class="booking-seat-summary-item">
                                                        <span>Hold seats</span>
                                                        <strong><?= $total_hold ?></strong>
                                                    </div>
                                                    <div class="booking-seat-summary-item">
                                                        <span>Empty seats</span>
                                                        <strong><?= $total_empty ?></strong>
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
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> &copy; Dashonic.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Crafted with <i class="mdi mdi-heart text-danger"></i> by <a
                                    href="https://Pichforest.com/" target="_blank" class="text-reset">Pichforest</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <div class="modal fade booking-hire-modal" id="booking_hire_modal" tabindex="-1" aria-labelledby="booking_hire_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="booking-explorer-heading mb-0" id="booking_hire_title">
                        <i class="mdi mdi-handshake-outline text-success"></i> Hire bus
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="booking-hire-trip">
                        <div class="booking-hire-trip-item">
                            <span>Route</span>
                            <strong data-hire="route"></strong>
                        </div>
                        <div class="booking-hire-trip-item">
                            <span>Bus</span>
                            <strong data-hire="bus"></strong>
                        </div>
                        <div class="booking-hire-trip-item">
                            <span>Date</span>
                            <strong data-hire="date"></strong>
                        </div>
                        <div class="booking-hire-trip-item">
                            <span>Time</span>
                            <strong data-hire="time"></strong>
                        </div>
                        <div class="booking-hire-trip-item">
                            <span>Class</span>
                            <strong data-hire="class"></strong>
                        </div>
                        <div class="booking-hire-trip-item">
                            <span>Seats</span>
                            <strong data-hire="seats"></strong>
                        </div>
                    </div>

                    <p class="booking-hire-prompt">How would you like to issue this hire?</p>
                    <div class="booking-hire-options" role="radiogroup">
                        <label class="booking-hire-option">
                            <input type="radio" name="hire_output" value="invoice">
                            <span class="booking-hire-option-icon is-invoice"><i class="mdi mdi-file-document-outline"></i></span>
                            <span class="booking-hire-option-text">
                                <strong>Generate invoice</strong>
                                <small>Bill the customer and collect payment later</small>
                            </span>
                            <i class="mdi mdi-check-circle booking-hire-option-check"></i>
                        </label>
                        <label class="booking-hire-option">
                            <input type="radio" name="hire_output" value="ticket">
                            <span class="booking-hire-option-icon is-ticket"><i class="mdi mdi-ticket-confirmation-outline"></i></span>
                            <span class="booking-hire-option-text">
                                <strong>Physical ticket</strong>
                                <small>Take payment now and print the ticket</small>
                            </span>
                            <i class="mdi mdi-check-circle booking-hire-option-check"></i>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary booking-book-btn" id="booking_hire_continue" disabled>Continue</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade booking-hire-modal booking-manifest-modal" id="booking_manifest_modal" tabindex="-1" aria-labelledby="booking_manifest_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h6 class="booking-explorer-heading mb-0" id="booking_manifest_title">
                            <i class="mdi mdi-clipboard-list-outline text-info"></i> Passenger manifest
                        </h6>
                        <div class="booking-manifest-meta" id="booking_manifest_meta"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="booking-manifest-toolbar">
                    <div class="booking-manifest-actions">
                        <button type="button" class="btn btn-soft-secondary btn-sm" data-manifest-action="transfer" disabled>
                            <i class="mdi mdi-swap-horizontal"></i> Transfer
                        </button>
                        <button type="button" class="btn btn-soft-warning btn-sm" data-manifest-action="unlock" disabled>
                            <i class="mdi mdi-lock-open-variant-outline"></i> Unlock
                        </button>
                        <button type="button" class="btn btn-soft-success btn-sm" data-manifest-action="book" disabled>
                            <i class="mdi mdi-check-circle-outline"></i> Book
                        </button>
                        <button type="button" class="btn btn-soft-danger btn-sm" data-manifest-action="delete" disabled>
                            <i class="mdi mdi-trash-can-outline"></i> Delete
                        </button>
                    </div>
                    <div class="booking-manifest-toolbar-right">
                        <span class="booking-explorer-count" id="booking_manifest_selected">0 selected</span>
                        <div class="booking-my-search">
                            <i class="mdi mdi-magnify" aria-hidden="true"></i>
                            <input type="search" id="booking_manifest_search" placeholder="Search name, ticket or seat">
                        </div>
                    </div>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-hover align-middle mb-0 booking-trip-table booking-my-table booking-manifest-table">
                        <thead>
                            <tr>
                                <th class="booking-manifest-check">
                                    <input class="form-check-input" type="checkbox" id="booking_manifest_all" aria-label="Select all passengers">
                                </th>
                                <th>Seat</th>
                                <th>Ticket</th>
                                <th>Passenger</th>
                                <th>Phone</th>
                                <th>Fare</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="booking_manifest_rows"></tbody>
                    </table>
                    <div class="booking-manifest-empty" id="booking_manifest_empty" hidden>No passengers on this trip</div>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="booking-my-page-info" id="booking_manifest_total"></span>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade booking-hire-modal booking-lock-modal" id="booking_lock_modal" tabindex="-1" aria-labelledby="booking_lock_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h6 class="booking-explorer-heading mb-0" id="booking_lock_title">
                            <i class="mdi mdi-lock-outline text-warning"></i> Lock seats
                        </h6>
                        <div class="booking-manifest-meta" id="booking_lock_meta"></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="vehicle-workspace-chart booking-seat-panel booking-lock-chart">
                                <div class="vehicle-deck-wrap">
                                    <div class="vehicle-deck">
                                        <div class="vehicle-deck-front">
                                            <span class="vehicle-door-slot"></span>
                                            <div class="vehicle-seat-row vehicle-driver-row" style="width:224px">
                                                <span class="vehicle-seat vehicle-steering-slot is-empty"></span>
                                                <span class="vehicle-driver-gap">
                                                    <span class="vehicle-bus-number" title="Bus number">
                                                        <span class="vehicle-plate-screw" aria-hidden="true"></span>
                                                        <span class="vehicle-plate-text" id="booking_lock_plate"></span>
                                                        <span class="vehicle-plate-screw" aria-hidden="true"></span>
                                                    </span>
                                                </span>
                                                <span class="vehicle-seat vehicle-steering-slot" title="Steering wheel">
                                                    <img class="vehicle-steering-img" src="css/app/vehicle-steering.svg" alt="">
                                                </span>
                                            </div>
                                            <span class="vehicle-door-slot"></span>
                                        </div>
                                        <div id="booking_lock_rows"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 booking-seat-panel booking-lock-side">
                            <div class="vehicle-seat-legend booking-lock-legend">
                                <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch"></span> Empty</span>
                                <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch is-selected"></span> Selected</span>
                                <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch is-hold"></span> Hold / locked</span>
                                <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch is-booked"></span> Booked</span>
                            </div>

                            <div class="booking-lock-selected">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Seats to lock</label>
                                    <button type="button" class="btn btn-link btn-sm p-0" id="booking_lock_clear" disabled>Clear</button>
                                </div>
                                <div class="booking-lock-chips" id="booking_lock_chips">
                                    <span class="booking-lock-chips-empty">Click empty seats on the chart</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="booking_lock_reason">Reason to lock <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="booking_lock_reason" rows="4" maxlength="255" placeholder="e.g. Reserved for staff travelling on this trip"></textarea>
                                <div class="invalid-feedback">Enter a reason for locking these seats.</div>
                            </div>

                            <div class="booking-seat-summary">
                                <div class="booking-seat-summary-item">
                                    <span>Selected</span>
                                    <strong id="booking_lock_sum_selected">0</strong>
                                </div>
                                <div class="booking-seat-summary-item">
                                    <span>Empty seats</span>
                                    <strong id="booking_lock_sum_empty">0</strong>
                                </div>
                                <div class="booking-seat-summary-item">
                                    <span>Locked seats</span>
                                    <strong id="booking_lock_sum_locked">0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="booking_lock_submit" disabled>
                        <i class="mdi mdi-lock-outline"></i> Lock seats
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade booking-hire-modal booking-my-modal" id="booking_my_view_modal" tabindex="-1" aria-labelledby="booking_my_view_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h6 class="booking-explorer-heading mb-0" id="booking_my_view_title">
                            <i class="mdi mdi-ticket-confirmation-outline text-info"></i> Ticket <span data-bk="ticket"></span>
                        </h6>
                        <div class="booking-manifest-meta">Booked <span data-bk="booked_at"></span> by <span data-bk="booked_by"></span></div>
                    </div>
                    <span class="booking-manifest-status ms-auto me-3" data-bk-status></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="booking-my-section-title"><i class="mdi mdi-account-outline"></i> Passenger</p>
                    <div class="booking-hire-trip">
                        <div class="booking-hire-trip-item"><span>Name</span><strong data-bk="name"></strong></div>
                        <div class="booking-hire-trip-item"><span>Phone</span><strong data-bk="phone"></strong></div>
                        <div class="booking-hire-trip-item"><span>Email</span><strong data-bk="email"></strong></div>
                        <div class="booking-hire-trip-item"><span>Nationality</span><strong data-bk="nationality"></strong></div>
                        <div class="booking-hire-trip-item"><span>ID type</span><strong data-bk="id_type"></strong></div>
                        <div class="booking-hire-trip-item"><span>ID number</span><strong data-bk="id_number"></strong></div>
                    </div>
                    <p class="booking-my-section-title"><i class="mdi mdi-bus"></i> Trip</p>
                    <div class="booking-hire-trip">
                        <div class="booking-hire-trip-item"><span>Route</span><strong data-bk="route_detail"></strong></div>
                        <div class="booking-hire-trip-item"><span>Trip date</span><strong data-bk="date"></strong></div>
                        <div class="booking-hire-trip-item"><span>Time</span><strong data-bk="time"></strong></div>
                        <div class="booking-hire-trip-item"><span>Bus</span><strong data-bk="bus"></strong></div>
                        <div class="booking-hire-trip-item"><span>Class</span><strong data-bk="class"></strong></div>
                        <div class="booking-hire-trip-item"><span>Seat</span><strong data-bk="seat"></strong></div>
                    </div>
                    <p class="booking-my-section-title"><i class="mdi mdi-cash"></i> Payment</p>
                    <div class="booking-hire-trip mb-0">
                        <div class="booking-hire-trip-item"><span>Fare</span><strong data-bk="fare"></strong></div>
                        <div class="booking-hire-trip-item"><span>Payment method</span><strong data-bk="payment"></strong></div>
                        <div class="booking-hire-trip-item"><span>Hold until</span><strong data-bk="hold_until"></strong></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-soft-primary" data-bk-switch="#booking_my_print_modal"><i class="mdi mdi-printer"></i> Print ticket</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade booking-hire-modal booking-my-modal" id="booking_my_print_modal" tabindex="-1" aria-labelledby="booking_my_print_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="booking-explorer-heading mb-0" id="booking_my_print_title">
                        <i class="mdi mdi-printer text-primary"></i> Print ticket
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="booking-ticket-print">
                        <div class="booking-ticket-head">
                            <div>
                                <strong>DASHONIC BUS SERVICES</strong>
                                <small>Passenger ticket</small>
                            </div>
                            <div class="text-end">
                                <small>Ticket no.</small>
                                <strong data-bk="ticket"></strong>
                            </div>
                        </div>
                        <div class="booking-ticket-route">
                            <div>
                                <small>From</small>
                                <strong data-bk="from"></strong>
                                <span data-bk="departure"></span>
                            </div>
                            <i class="mdi mdi-arrow-right" aria-hidden="true"></i>
                            <div class="text-end">
                                <small>To</small>
                                <strong data-bk="to"></strong>
                                <span data-bk="arrival"></span>
                            </div>
                        </div>
                        <div class="booking-ticket-grid">
                            <div><small>Passenger</small><strong data-bk="name"></strong></div>
                            <div><small>Phone</small><strong data-bk="phone"></strong></div>
                            <div><small>Trip date</small><strong data-bk="date"></strong></div>
                            <div><small>Seat</small><strong data-bk="seat"></strong></div>
                            <div><small>Bus</small><strong data-bk="bus"></strong></div>
                            <div><small>Class</small><strong data-bk="class"></strong></div>
                            <div><small>Fare</small><strong data-bk="fare"></strong></div>
                            <div><small>Payment</small><strong data-bk="payment"></strong></div>
                        </div>
                        <div class="booking-ticket-foot">
                            <span class="booking-ticket-barcode" aria-hidden="true"></span>
                            <small>Report 30 minutes before departure. Ticket is valid only for the date and seat shown.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="booking_my_print_btn"><i class="mdi mdi-printer"></i> Print</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade booking-hire-modal booking-my-modal" id="booking_my_unlock_modal" tabindex="-1" aria-labelledby="booking_my_unlock_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="booking-explorer-heading mb-0" id="booking_my_unlock_title">
                        <i class="mdi mdi-lock-open-variant-outline text-warning"></i> Unlock seat
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="booking-hire-trip">
                        <div class="booking-hire-trip-item"><span>Ticket</span><strong data-bk="ticket"></strong></div>
                        <div class="booking-hire-trip-item"><span>Seat</span><strong data-bk="seat"></strong></div>
                        <div class="booking-hire-trip-item"><span>Passenger</span><strong data-bk="name"></strong></div>
                        <div class="booking-hire-trip-item"><span>Route</span><strong data-bk="route"></strong></div>
                        <div class="booking-hire-trip-item"><span>Trip date</span><strong data-bk="date"></strong></div>
                        <div class="booking-hire-trip-item"><span>Hold until</span><strong data-bk="hold_until"></strong></div>
                    </div>
                    <div class="booking-my-alert is-warning" id="booking_my_unlock_blocked" hidden>
                        <i class="mdi mdi-alert-outline"></i>
                        <span>This ticket is <strong data-bk="status_label"></strong>, not on hold. Only held seats can be unlocked.</span>
                    </div>
                    <div id="booking_my_unlock_fields">
                        <p class="booking-my-note">Unlocking releases seat <strong data-bk="seat"></strong> so it can be sold to another passenger.</p>
                        <label class="form-label" for="booking_my_unlock_reason">Reason</label>
                        <select class="form-select" id="booking_my_unlock_reason">
                            <option value="Hold expired">Hold expired</option>
                            <option value="Customer cancelled">Customer cancelled</option>
                            <option value="Payment not received">Payment not received</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="booking_my_unlock_confirm"><i class="mdi mdi-lock-open-variant-outline"></i> Unlock seat</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade booking-hire-modal booking-my-modal" id="booking_my_transfer_modal" tabindex="-1" aria-labelledby="booking_my_transfer_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h6 class="booking-explorer-heading mb-0" id="booking_my_transfer_title">
                            <i class="mdi mdi-swap-horizontal text-secondary"></i> Transfer ticket <span data-bk="ticket"></span>
                        </h6>
                        <div class="booking-manifest-meta"><span data-bk="name"></span> - seat <span data-bk="seat"></span></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="booking-transfer-compare">
                        <div class="booking-transfer-card">
                            <small>Current trip</small>
                            <strong data-bk="route_detail"></strong>
                            <span><span data-bk="date"></span> - <span data-bk="time"></span></span>
                            <span>Bus <span data-bk="bus"></span> - Seat <span data-bk="seat"></span></span>
                        </div>
                        <i class="mdi mdi-arrow-right booking-transfer-arrow" aria-hidden="true"></i>
                        <div class="booking-transfer-card is-new">
                            <small>New trip</small>
                            <strong id="booking_transfer_new_route"> - </strong>
                            <span id="booking_transfer_new_time">Choose a trip below</span>
                            <span id="booking_transfer_new_bus"></span>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="booking_transfer_trip">New trip</label>
                            <select class="form-select" id="booking_transfer_trip"></select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="booking_transfer_date">Trip date</label>
                            <input class="form-control" type="text" id="booking_transfer_date" placeholder="Select date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="booking_transfer_seat">Seat</label>
                            <select class="form-select" id="booking_transfer_seat"></select>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="booking_transfer_reason">Reason</label>
                            <textarea class="form-control" id="booking_transfer_reason" rows="2" placeholder="e.g. Customer asked to travel a day later"></textarea>
                        </div>
                    </div>
                    <div class="booking-transfer-cost">
                        <div><span>Current fare</span><strong data-bk="fare"></strong></div>
                        <div><span>New fare</span><strong id="booking_transfer_new_fare"> - </strong></div>
                        <div><span>Transfer fee</span><strong id="booking_transfer_fee">TZS 2,000</strong></div>
                        <div class="is-total"><span>Customer pays</span><strong id="booking_transfer_due"> - </strong></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary booking-book-btn" id="booking_transfer_confirm" disabled><i class="mdi mdi-swap-horizontal"></i> Transfer</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade booking-hire-modal booking-my-modal" id="booking_my_edit_modal" tabindex="-1" aria-labelledby="booking_my_edit_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="booking_my_edit_form" novalidate>
                    <div class="modal-header">
                        <div>
                            <h6 class="booking-explorer-heading mb-0" id="booking_my_edit_title">
                                <i class="mdi mdi-pencil-outline text-success"></i> Edit passenger
                            </h6>
                            <div class="booking-manifest-meta">Ticket <span data-bk="ticket"></span> - seat <span data-bk="seat"></span> - <span data-bk="route"></span></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="booking_edit_firstname">First name</label>
                                <input class="form-control" type="text" id="booking_edit_firstname" name="firstname" required>
                                <div class="invalid-feedback">First name is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="booking_edit_lastname">Last name</label>
                                <input class="form-control" type="text" id="booking_edit_lastname" name="lastname" required>
                                <div class="invalid-feedback">Last name is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="booking_edit_phone">Phone number</label>
                                <input class="form-control" type="tel" id="booking_edit_phone" name="phone" required>
                                <div class="invalid-feedback">Phone number is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="booking_edit_email">Email</label>
                                <input class="form-control" type="email" id="booking_edit_email" name="email">
                                <div class="invalid-feedback">Enter a valid email.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="booking_edit_nationality">Nationality</label>
                                <select class="form-select" id="booking_edit_nationality" name="nationality">
                                    <option>Tanzanian</option>
                                    <option>Kenyan</option>
                                    <option>Ugandan</option>
                                    <option>Rwandan</option>
                                    <option>Burundian</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="booking_edit_id_number">Identification</label>
                                <div class="booking-phone-group booking-id-group">
                                    <select class="booking-phone-code booking-id-type" name="id_type" aria-label="ID type">
                                        <option value="National ID">National ID</option>
                                        <option value="Passport">Passport</option>
                                        <option value="Voter ID">Voter ID</option>
                                        <option value="Driver License">Driver License</option>
                                    </select>
                                    <input class="form-control" type="text" id="booking_edit_id_number" name="id_number" placeholder="ID number">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="mdi mdi-check-circle-outline"></i> Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade booking-hire-modal booking-my-modal" id="booking_my_delete_modal" tabindex="-1" aria-labelledby="booking_my_delete_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="booking-explorer-heading mb-0" id="booking_my_delete_title">
                        <i class="mdi mdi-trash-can-outline text-danger"></i> Delete ticket
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="booking-my-alert is-danger">
                        <i class="mdi mdi-alert-outline"></i>
                        <span>Ticket <strong data-bk="ticket"></strong> for <strong data-bk="name"></strong> (seat <strong data-bk="seat"></strong>, <span data-bk="route"></span> on <span data-bk="date"></span>) will be removed. This can't be undone.</span>
                    </div>
                    <label class="form-label" for="booking_my_delete_reason">Reason</label>
                    <select class="form-select mb-3" id="booking_my_delete_reason">
                        <option value="">Select reason</option>
                        <option value="Duplicate booking">Duplicate booking</option>
                        <option value="Customer cancelled">Customer cancelled</option>
                        <option value="Wrong passenger details">Wrong passenger details</option>
                        <option value="Other">Other</option>
                    </select>
                    <label class="booking-my-refund" id="booking_my_refund_wrap">
                        <input class="form-check-input" type="checkbox" id="booking_my_refund">
                        <span>Refund <strong data-bk="fare"></strong> via <span data-bk="payment"></span></span>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="booking_my_delete_confirm" disabled><i class="mdi mdi-trash-can-outline"></i> Delete ticket</button>
                </div>
            </div>
        </div>
    </div>

    <template id="booking_manifest_row_template">
        <tr>
            <td class="booking-manifest-check">
                <input class="form-check-input" type="checkbox" data-field="check">
            </td>
            <td><span class="booking-my-seat" data-field="seat"></span></td>
            <td data-field="ticket"></td>
            <td><strong data-field="name"></strong></td>
            <td data-field="phone"></td>
            <td class="booking-trip-price" data-field="fare"></td>
            <td><span class="booking-manifest-status" data-field="status"></span></td>
        </tr>
    </template>

    <script>
        window.BOOKING_MANIFESTS = <?= json_encode($trip_manifests, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
        window.BOOKING_TRIPS = <?= json_encode($booking_trips, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
        window.BOOKING_MY = <?= json_encode(array_column($my_bookings, null, 'ticket'), JSON_HEX_TAG | JSON_HEX_AMP) ?>;
        window.BOOKING_STATUS_LABELS = <?= json_encode($booking_status_labels + ['unlocked' => 'Unlocked']) ?>;
    </script>

    <template id="booking_passenger_template">
        <form class="booking-passenger-form" autocomplete="off">
            <div class="booking-passenger-form-head">
                <span class="booking-passenger-seat">Seat <strong data-seat-label></strong></span>
                <label class="booking-foc-pill">
                    <input type="checkbox" name="foc">
                    <span>FOC</span>
                </label>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label" data-label="firstname">First name</label>
                    <input class="form-control" type="text" name="firstname" placeholder="First name">
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-label="lastname">Last name</label>
                    <input class="form-control" type="text" name="lastname" placeholder="Last name">
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-label="phone">Phone number</label>
                    <div class="booking-phone-group">
                        <select class="booking-phone-code" name="country_code" aria-label="Country code">
                            <option value="+255" selected>+255 TZ</option>
                            <option value="+254">+254 KE</option>
                            <option value="+256">+256 UG</option>
                            <option value="+250">+250 RW</option>
                            <option value="+257">+257 BI</option>
                            <option value="+243">+243 CD</option>
                            <option value="+260">+260 ZM</option>
                            <option value="+265">+265 MW</option>
                            <option value="+258">+258 MZ</option>
                            <option value="+27">+27 ZA</option>
                            <option value="+971">+971 AE</option>
                            <option value="+91">+91 IN</option>
                            <option value="+86">+86 CN</option>
                            <option value="+44">+44 GB</option>
                            <option value="+1">+1 US</option>
                        </select>
                        <input class="form-control" type="tel" name="phone" inputmode="tel" placeholder="712 345 678">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-label="nationality">Nationality</label>
                    <select class="booking-nationality-select" name="nationality">
                        <option value="">Select nationality</option>
                        <option value="Tanzanian" selected>Tanzanian</option>
                        <option value="Kenyan">Kenyan</option>
                        <option value="Ugandan">Ugandan</option>
                        <option value="Rwandan">Rwandan</option>
                        <option value="Burundian">Burundian</option>
                        <option value="Congolese">Congolese</option>
                        <option value="Zambian">Zambian</option>
                        <option value="Malawian">Malawian</option>
                        <option value="Mozambican">Mozambican</option>
                        <option value="South African">South African</option>
                        <option value="Indian">Indian</option>
                        <option value="Chinese">Chinese</option>
                        <option value="British">British</option>
                        <option value="American">American</option>
                        <option value="German">German</option>
                        <option value="Emirati">Emirati</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-label="id_number">Identification</label>
                    <div class="booking-phone-group booking-id-group">
                        <select class="booking-phone-code booking-id-type" name="id_type" aria-label="ID type">
                            <option value="National ID" selected>National ID</option>
                            <option value="Passport">Passport</option>
                            <option value="Voter ID">Voter ID</option>
                            <option value="Driver License">Driver License</option>
                        </select>
                        <input class="form-control" type="text" name="id_number" placeholder="ID number">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-label="email">Email</label>
                    <input class="form-control" type="email" name="email" inputmode="email" autocomplete="email" placeholder="name@example.com">
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-label="discount">Discount</label>
                    <div class="booking-phone-group booking-discount-group">
                        <select class="booking-phone-code" name="discount_type" aria-label="Discount type">
                            <option value="amount" selected>TZS</option>
                            <option value="percent">%</option>
                        </select>
                        <input class="form-control" type="number" name="discount" min="0" step="1" inputmode="numeric" placeholder="0">
                    </div>
                </div>
            </div>
        </form>
    </template>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        (function () {
            var choiceOptions = {
                searchEnabled: true,
                searchPlaceholderValue: 'Search location',
                itemSelectText: '',
                shouldSort: false,
                allowHTML: false,
                placeholder: true,
                placeholderValue: 'Select location'
            };
            var fromSelect = document.getElementById('booking_from');
            var toSelect = document.getElementById('booking_to');
            if (window.Choices && fromSelect) {
                new window.Choices(fromSelect, Object.assign({}, choiceOptions, {
                    placeholderValue: 'Select location'
                }));
            }
            if (window.Choices && toSelect) {
                new window.Choices(toSelect, Object.assign({}, choiceOptions, {
                    placeholderValue: 'Select destination'
                }));
            }

            var dateInput = document.getElementById('booking_date');
            if (dateInput && typeof window.flatpickr === 'function') {
                window.flatpickr(dateInput, {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'D, j M Y',
                    allowInput: false,
                    disableMobile: true,
                    animate: true,
                    monthSelectorType: 'static',
                    onReady: function (selectedDates, dateStr, instance) {
                        instance.calendarContainer.classList.add('booking-flatpickr');
                        instance.input.setAttribute('readonly', 'readonly');
                        if (instance.altInput) {
                            instance.altInput.classList.add('form-control');
                            instance.altInput.setAttribute('placeholder', 'Select trip date');
                            instance.altInput.setAttribute('readonly', 'readonly');
                        }
                    }
                });
            }

            var wrap = document.querySelector('.booking-trip-table-wrap');
            var handle = document.querySelector('.booking-trip-resize');
            if (!wrap || !handle) {
                return;
            }

            var minHeight = 206;
            var startY = 0;
            var startHeight = 0;

            function tableHeight() {
                var table = wrap.querySelector('table');
                return table ? table.offsetHeight : minHeight;
            }

            function clientY(event) {
                return event.touches ? event.touches[0].clientY : event.clientY;
            }

            function onMove(event) {
                var next = startHeight + (clientY(event) - startY);
                next = Math.max(minHeight, Math.min(tableHeight(), next));
                wrap.style.height = next + 'px';
            }

            function onUp() {
                handle.classList.remove('is-dragging');
                document.body.style.userSelect = '';
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onUp);
                document.removeEventListener('touchmove', onMove);
                document.removeEventListener('touchend', onUp);
            }

            function onDown(event) {
                event.preventDefault();
                startY = clientY(event);
                startHeight = wrap.getBoundingClientRect().height;
                handle.classList.add('is-dragging');
                document.body.style.userSelect = 'none';
                document.addEventListener('mousemove', onMove);
                document.addEventListener('mouseup', onUp);
                document.addEventListener('touchmove', onMove, { passive: false });
                document.addEventListener('touchend', onUp);
            }

            handle.addEventListener('mousedown', onDown);
            handle.addEventListener('touchstart', onDown, { passive: false });
        })();

        (function () {
            var panel = document.getElementById('booking_passenger_panel');
            var list = document.getElementById('booking_passenger_list');
            var countEl = document.getElementById('booking_passenger_count');
            var heading = document.getElementById('booking_passenger_heading');
            var template = document.getElementById('booking_passenger_template');
            var chart = document.querySelector('.booking-seat-panel');
            if (!panel || !list || !template || !chart || !heading || !countEl) {
                return;
            }

            function isAvailableSeat(seat) {
                if (!seat || !seat.classList.contains('vehicle-seat') || !seat.getAttribute('data-seat')) {
                    return false;
                }
                return !seat.classList.contains('is-reserved')
                    && !seat.classList.contains('is-disabled')
                    && !seat.classList.contains('is-aisle')
                    && !seat.classList.contains('is-empty')
                    && !seat.classList.contains('vehicle-steering-slot');
            }

            function uniqueId(base, seatNo) {
                return 'booking_' + base + '_' + seatNo;
            }

            function addPassengerForm(seatNo) {
                if (list.querySelector('[data-seat="' + seatNo + '"]')) {
                    return;
                }
                var node = template.content.cloneNode(true);
                var form = node.querySelector('.booking-passenger-form');
                form.setAttribute('data-seat', seatNo);
                form.querySelector('[data-seat-label]').textContent = seatNo;

                form.querySelectorAll('label[data-label]').forEach(function (label) {
                    var key = label.getAttribute('data-label');
                    var id = uniqueId(key, seatNo);
                    label.setAttribute('for', id);
                    var field = form.querySelector('[name="' + (key === 'id_number' ? 'id_number' : key === 'id_type' ? 'id_type' : key) + '"]');
                    if (key === 'firstname') field = form.querySelector('[name="firstname"]');
                    if (key === 'lastname') field = form.querySelector('[name="lastname"]');
                    if (key === 'phone') field = form.querySelector('[name="phone"]');
                    if (key === 'nationality') field = form.querySelector('[name="nationality"]');
                    if (key === 'id_type') field = form.querySelector('[name="id_type"]');
                    if (key === 'id_number') field = form.querySelector('[name="id_number"]');
                    if (field) {
                        field.id = id;
                    }
                });

                var foc = form.querySelector('[name="foc"]');
                if (foc) {
                    foc.id = uniqueId('foc', seatNo);
                }

                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                });

                list.appendChild(form);

                var nationality = list.querySelector('[data-seat="' + seatNo + '"] [name="nationality"]');
                if (window.Choices && nationality) {
                    form._nationalityChoice = new window.Choices(nationality, {
                        searchEnabled: true,
                        searchPlaceholderValue: 'Search nationality',
                        itemSelectText: '',
                        shouldSort: false,
                        allowHTML: false
                    });
                }
            }

            function removePassengerForm(seatNo) {
                var form = list.querySelector('[data-seat="' + seatNo + '"]');
                if (!form) {
                    return;
                }
                if (form._nationalityChoice) {
                    form._nationalityChoice.destroy();
                }
                form.remove();
            }

            var paneSwitch = document.getElementById('booking_pane_switch');
            var slider = panel.querySelector('.booking-passenger-slider');
            var emptyEl = document.getElementById('booking_passenger_empty');
            var bookBtn = panel.querySelector('.booking-passenger-actions .booking-book-btn');
            var pane = 'table';

            function syncPanel() {
                var count = list.children.length;
                var ticketCount = parseInt(panel.getAttribute('data-ticket-count') || '0', 10);
                var showForm = pane === 'form';
                panel.classList.toggle('is-composing', showForm);
                panel.classList.toggle('has-passengers', count > 0);
                emptyEl.hidden = count > 0;
                bookBtn.disabled = !count;
                paneSwitch.setAttribute('data-active', pane);
                paneSwitch.querySelectorAll('[data-pane]').forEach(function (button) {
                    button.setAttribute('aria-selected', button.getAttribute('data-pane') === pane ? 'true' : 'false');
                });
                heading.textContent = showForm ? 'Passenger details' : 'My booking';
                countEl.textContent = showForm
                    ? count + (count === 1 ? ' seat' : ' seats')
                    : ticketCount + (ticketCount === 1 ? ' ticket' : ' tickets');
            }

            function showPane(next) {
                pane = next;
                syncPanel();
            }

            paneSwitch.addEventListener('click', function (event) {
                var button = event.target.closest('[data-pane]');
                if (button) {
                    showPane(button.getAttribute('data-pane'));
                }
            });

            var touchX = null;
            var touchY = null;
            slider.addEventListener('touchstart', function (event) {
                touchX = event.touches[0].clientX;
                touchY = event.touches[0].clientY;
            }, { passive: true });
            slider.addEventListener('touchend', function (event) {
                if (touchX === null) {
                    return;
                }
                var dx = event.changedTouches[0].clientX - touchX;
                var dy = event.changedTouches[0].clientY - touchY;
                touchX = null;
                if (Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy) * 1.5) {
                    showPane(dx < 0 ? 'form' : 'table');
                }
            }, { passive: true });

            chart.addEventListener('click', function (event) {
                var seat = event.target.closest('.vehicle-seat');
                if (!isAvailableSeat(seat)) {
                    return;
                }
                var seatNo = seat.getAttribute('data-seat');
                var selected = seat.classList.toggle('is-selected');
                if (selected) {
                    addPassengerForm(seatNo);
                    pane = 'form';
                } else {
                    removePassengerForm(seatNo);
                }
                syncPanel();
            });
            document.addEventListener('booking:my-bookings-changed', syncPanel);
            syncPanel();
        })();

        (function () {
            var table = document.getElementById('booking_my_table');
            var search = document.getElementById('booking_my_search');
            var info = document.getElementById('booking_my_page_info');
            var pager = document.getElementById('booking_my_page_btns');
            if (!table || !search || !info || !pager) {
                return;
            }

            var pageSize = 4;
            var page = 1;

            function allRows() {
                return Array.prototype.slice.call(table.querySelectorAll('tbody tr'));
            }

            function filteredRows() {
                var query = (search.value || '').toLowerCase().trim();
                return allRows().filter(function (row) {
                    return !query || row.textContent.toLowerCase().indexOf(query) !== -1;
                });
            }

            function renderPager(pages) {
                pager.innerHTML = '';

                function addButton(label, target, disabled, active) {
                    var button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'btn btn-sm ' + (active ? 'btn-primary' : 'btn-soft-primary');
                    button.textContent = label;
                    button.disabled = disabled;
                    button.addEventListener('click', function () {
                        page = target;
                        render();
                    });
                    pager.appendChild(button);
                }

                addButton('Prev', Math.max(1, page - 1), page <= 1, false);
                for (var i = 1; i <= pages; i += 1) {
                    addButton(String(i), i, false, i === page);
                }
                addButton('Next', Math.min(pages, page + 1), page >= pages, false);
            }

            function render() {
                var rows = allRows();
                var matches = filteredRows();
                var pages = Math.max(1, Math.ceil(matches.length / pageSize));
                if (page > pages) {
                    page = pages;
                }
                var start = (page - 1) * pageSize;
                var end = start + pageSize;
                rows.forEach(function (row) {
                    row.hidden = true;
                });
                matches.forEach(function (row, index) {
                    row.hidden = !(index >= start && index < end);
                });
                var from = matches.length ? start + 1 : 0;
                var to = Math.min(end, matches.length);
                info.textContent = 'Showing ' + from + '-' + to + ' of ' + matches.length;
                renderPager(pages);
            }

            search.addEventListener('input', function () {
                page = 1;
                render();
            });

            document.addEventListener('booking:my-bookings-changed', render);

            render();
        })();

        (function () {
            var modal = document.getElementById('booking_manifest_modal');
            var rowsEl = document.getElementById('booking_manifest_rows');
            var rowTemplate = document.getElementById('booking_manifest_row_template');
            var emptyEl = document.getElementById('booking_manifest_empty');
            var metaEl = document.getElementById('booking_manifest_meta');
            var selectedEl = document.getElementById('booking_manifest_selected');
            var totalEl = document.getElementById('booking_manifest_total');
            var searchInput = document.getElementById('booking_manifest_search');
            var allCheck = document.getElementById('booking_manifest_all');
            var actionButtons = modal ? modal.querySelectorAll('[data-manifest-action]') : [];
            if (!modal || !rowsEl || !rowTemplate) {
                return;
            }

            var statusLabels = { complete: 'Paid', booked: 'Booked', hold: 'Hold', unlocked: 'Unlocked' };
            var manifests = window.BOOKING_MANIFESTS || {};
            var trip = null;
            var passengers = [];
            var selected = {};

            function money(value) {
                return 'TZS ' + Number(value || 0).toLocaleString('en-US', { maximumFractionDigits: 0 });
            }

            function visiblePassengers() {
                var query = (searchInput.value || '').toLowerCase().trim();
                return passengers.filter(function (p) {
                    return !query || [p.seat, p.ticket, p.name, p.phone].join(' ').toLowerCase().indexOf(query) !== -1;
                });
            }

            function selectedPassengers() {
                return passengers.filter(function (p) {
                    return selected[p.ticket];
                });
            }

            function syncSelection() {
                var visible = visiblePassengers();
                var count = selectedPassengers().length;
                var visibleSelected = visible.filter(function (p) { return selected[p.ticket]; }).length;
                selectedEl.textContent = count + ' selected';
                allCheck.checked = visible.length > 0 && visibleSelected === visible.length;
                allCheck.indeterminate = visibleSelected > 0 && visibleSelected < visible.length;
                actionButtons.forEach(function (button) {
                    button.disabled = count === 0;
                });
                rowsEl.querySelectorAll('tr[data-ticket]').forEach(function (row) {
                    row.classList.toggle('is-selected', !!selected[row.getAttribute('data-ticket')]);
                });
            }

            function render() {
                var visible = visiblePassengers();
                rowsEl.innerHTML = '';
                visible.forEach(function (p) {
                    var node = rowTemplate.content.cloneNode(true);
                    var row = node.querySelector('tr');
                    row.setAttribute('data-ticket', p.ticket);
                    var check = node.querySelector('[data-field="check"]');
                    check.checked = !!selected[p.ticket];
                    check.setAttribute('aria-label', 'Select ' + p.name);
                    node.querySelector('[data-field="seat"]').textContent = p.seat;
                    node.querySelector('[data-field="ticket"]').textContent = p.ticket;
                    node.querySelector('[data-field="name"]').textContent = p.name;
                    node.querySelector('[data-field="phone"]').textContent = p.phone;
                    node.querySelector('[data-field="fare"]').textContent = money(p.fare);
                    var status = node.querySelector('[data-field="status"]');
                    status.textContent = statusLabels[p.status] || p.status;
                    status.classList.add('is-' + p.status);
                    rowsEl.appendChild(node);
                });
                emptyEl.hidden = visible.length > 0;
                emptyEl.textContent = passengers.length ? 'No passengers match your search' : 'No passengers on this trip';
                totalEl.textContent = passengers.length + (passengers.length === 1 ? ' passenger' : ' passengers') + ' on this trip';
                syncSelection();
            }

            modal.addEventListener('show.bs.modal', function (event) {
                var trigger = event.relatedTarget;
                if (!trigger) {
                    return;
                }
                var index = trigger.getAttribute('data-trip-index');
                manifests[index] = manifests[index] || [];
                passengers = manifests[index];
                selected = {};
                searchInput.value = '';
                trip = {
                    index: index,
                    bus: trigger.getAttribute('data-bus') || '',
                    route: trigger.getAttribute('data-route') || '',
                    time: trigger.getAttribute('data-time') || ''
                };
                metaEl.textContent = [trip.route, 'Bus ' + trip.bus, trip.time].filter(Boolean).join(' - ');
                render();
            });

            rowsEl.addEventListener('change', function (event) {
                if (!event.target.matches('[data-field="check"]')) {
                    return;
                }
                var ticket = event.target.closest('tr').getAttribute('data-ticket');
                if (event.target.checked) {
                    selected[ticket] = true;
                } else {
                    delete selected[ticket];
                }
                syncSelection();
            });

            rowsEl.addEventListener('click', function (event) {
                if (event.target.closest('input, button, a')) {
                    return;
                }
                var row = event.target.closest('tr[data-ticket]');
                if (!row) {
                    return;
                }
                var check = row.querySelector('[data-field="check"]');
                check.checked = !check.checked;
                check.dispatchEvent(new Event('change', { bubbles: true }));
            });

            allCheck.addEventListener('change', function () {
                visiblePassengers().forEach(function (p) {
                    if (allCheck.checked) {
                        selected[p.ticket] = true;
                    } else {
                        delete selected[p.ticket];
                    }
                });
                render();
            });

            searchInput.addEventListener('input', render);

            actionButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var action = button.getAttribute('data-manifest-action');
                    var chosen = selectedPassengers();
                    if (!chosen.length) {
                        return;
                    }
                    var seats = chosen.map(function (p) { return p.seat; }).join(', ');

                    if (action === 'delete') {
                        if (!window.confirm('Delete ' + chosen.length + ' ticket(s) for seats ' + seats + '?')) {
                            return;
                        }
                        for (var i = passengers.length - 1; i >= 0; i--) {
                            if (selected[passengers[i].ticket]) {
                                passengers.splice(i, 1);
                            }
                        }
                    } else if (action === 'book') {
                        chosen.forEach(function (p) {
                            if (p.status !== 'complete') {
                                p.status = 'booked';
                            }
                        });
                    } else if (action === 'unlock') {
                        chosen.forEach(function (p) {
                            if (p.status === 'hold') {
                                p.status = 'unlocked';
                            }
                        });
                    }

                    document.dispatchEvent(new CustomEvent('booking:manifest-action', {
                        detail: {
                            action: action,
                            trip: trip,
                            tickets: chosen.map(function (p) { return p.ticket; })
                        }
                    }));

                    selected = {};
                    render();
                });
            });
        })();

        (function () {
            var table = document.getElementById('booking_my_table');
            var panel = document.getElementById('booking_passenger_panel');
            var bookings = window.BOOKING_MY || {};
            var trips = window.BOOKING_TRIPS || [];
            var statusLabels = window.BOOKING_STATUS_LABELS || {};
            var transferFee = 2000;
            var current = null;
            if (!table) {
                return;
            }

            function money(value) {
                return 'TZS ' + Number(value || 0).toLocaleString('en-US', { maximumFractionDigits: 0 });
            }

            function view(booking) {
                var trip = trips[booking.trip_index] || {};
                return Object.assign({}, booking, {
                    from: trip.from || '',
                    to: trip.to || '',
                    time: booking.departure + ' - ' + booking.arrival,
                    fare: money(booking.cost),
                    hold_until: booking.hold_until || ' - ',
                    status_label: statusLabels[booking.status] || booking.status
                });
            }

            function fill(modal, booking) {
                var data = view(booking);
                modal.querySelectorAll('[data-bk]').forEach(function (el) {
                    var value = data[el.getAttribute('data-bk')];
                    el.textContent = value === '' || value === undefined ? ' - ' : value;
                });
                modal.querySelectorAll('[data-bk-status]').forEach(function (el) {
                    el.className = el.className.replace(/\bis-\S+/g, '').trim() + ' is-' + booking.status;
                    el.textContent = data.status_label;
                });
            }

            function rowFor(ticket) {
                return table.querySelector('tr[data-ticket="' + ticket + '"]');
            }

            function updateRow(booking) {
                var row = rowFor(booking.ticket);
                if (!row) {
                    return;
                }
                var data = view(booking);
                ['name', 'phone', 'route', 'route_detail', 'date', 'seat'].forEach(function (key) {
                    row.querySelector('[data-cell="' + key + '"]').textContent = data[key];
                });
                row.querySelector('[data-cell="cost"]').textContent = data.fare;
                ['seat', 'status'].forEach(function (key) {
                    var el = row.querySelector('[data-cell="' + key + '"]');
                    el.className = el.className.replace(/\bis-\S+/g, '').trim() + ' is-' + booking.status;
                });
                row.querySelector('[data-cell="status"]').textContent = data.status_label;
            }

            function notify(action, booking, extra) {
                document.dispatchEvent(new CustomEvent('booking:my-action', {
                    detail: Object.assign({ action: action, ticket: booking.ticket }, extra || {})
                }));
            }

            function hide(modal) {
                bootstrap.Modal.getOrCreateInstance(modal).hide();
            }

            var setups = {};

            document.querySelectorAll('.booking-my-modal').forEach(function (modal) {
                modal.addEventListener('show.bs.modal', function (event) {
                    var row = event.relatedTarget && event.relatedTarget.closest('tr[data-ticket]');
                    if (row) {
                        current = bookings[row.getAttribute('data-ticket')];
                    }
                    if (!current) {
                        event.preventDefault();
                        return;
                    }
                    fill(modal, current);
                    if (setups[modal.id]) {
                        setups[modal.id](modal, current);
                    }
                });
            });

            document.querySelectorAll('[data-bk-switch]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var from = button.closest('.modal');
                    var target = document.querySelector(button.getAttribute('data-bk-switch'));
                    from.addEventListener('hidden.bs.modal', function () {
                        bootstrap.Modal.getOrCreateInstance(target).show();
                    }, { once: true });
                    hide(from);
                });
            });

            document.getElementById('booking_my_print_btn').addEventListener('click', function () {
                document.body.classList.add('booking-printing-ticket');
                window.print();
                document.body.classList.remove('booking-printing-ticket');
                notify('print', current);
            });

            var unlockModal = document.getElementById('booking_my_unlock_modal');
            var unlockConfirm = document.getElementById('booking_my_unlock_confirm');
            setups.booking_my_unlock_modal = function (modal, booking) {
                var onHold = booking.status === 'hold';
                document.getElementById('booking_my_unlock_blocked').hidden = onHold;
                document.getElementById('booking_my_unlock_fields').hidden = !onHold;
                document.getElementById('booking_my_unlock_reason').selectedIndex = 0;
                unlockConfirm.disabled = !onHold;
            };
            unlockConfirm.addEventListener('click', function () {
                current.status = 'unlocked';
                current.payment = 'Released';
                current.hold_until = '';
                updateRow(current);
                notify('unlock', current, { reason: document.getElementById('booking_my_unlock_reason').value });
                hide(unlockModal);
            });

            var transferModal = document.getElementById('booking_my_transfer_modal');
            var transferTrip = document.getElementById('booking_transfer_trip');
            var transferDate = document.getElementById('booking_transfer_date');
            var transferSeat = document.getElementById('booking_transfer_seat');
            var transferReason = document.getElementById('booking_transfer_reason');
            var transferConfirm = document.getElementById('booking_transfer_confirm');
            var transferPicker = window.flatpickr ? window.flatpickr(transferDate, {
                dateFormat: 'j M Y',
                minDate: 'today',
                disableMobile: true,
                onChange: syncTransfer
            }) : null;

            function freeSeats(tripIndex) {
                var trip = trips[tripIndex];
                var taken = {};
                ((window.BOOKING_MANIFESTS || {})[tripIndex] || []).forEach(function (p) {
                    taken[p.seat] = true;
                });
                Object.keys((window.BOOKING_SEAT_LOCKS || {})[tripIndex] || {}).forEach(function (seat) {
                    taken[seat] = true;
                });
                var seats = [];
                for (var n = 0; n < trip.seats; n++) {
                    var seat = String.fromCharCode(65 + Math.floor(n / 4)) + ((n % 4) + 1);
                    if (!taken[seat]) {
                        seats.push(seat);
                    }
                }
                return seats;
            }

            function syncTransfer() {
                var trip = trips[transferTrip.value];
                var ready = !!trip && !!transferDate.value && !!transferSeat.value;
                document.getElementById('booking_transfer_new_route').textContent = trip ? trip.from + ' -> ' + trip.to : ' - ';
                document.getElementById('booking_transfer_new_time').textContent = trip
                    ? (transferDate.value || 'Pick a date') + ' - ' + trip.departure + ' - ' + trip.arrival
                    : 'Choose a trip below';
                document.getElementById('booking_transfer_new_bus').textContent = trip
                    ? 'Bus ' + trip.bus + (transferSeat.value ? ' - Seat ' + transferSeat.value : '')
                    : '';
                document.getElementById('booking_transfer_new_fare').textContent = trip ? money(trip.price) : ' - ';
                var due = trip ? Math.max(0, trip.price - current.cost) + transferFee : 0;
                document.getElementById('booking_transfer_due').textContent = trip ? money(due) : ' - ';
                transferConfirm.disabled = !ready;
            }

            function fillSeats() {
                transferSeat.innerHTML = '';
                if (transferTrip.value === '') {
                    transferSeat.disabled = true;
                    transferSeat.add(new Option('Choose trip first', ''));
                    return;
                }
                transferSeat.disabled = false;
                var seats = freeSeats(transferTrip.value);
                transferSeat.add(new Option(seats.length ? 'Select seat' : 'No free seats', ''));
                seats.forEach(function (seat) {
                    transferSeat.add(new Option(seat, seat));
                });
            }

            setups.booking_my_transfer_modal = function (modal, booking) {
                transferTrip.innerHTML = '';
                transferTrip.add(new Option('Select trip', ''));
                trips.forEach(function (trip, index) {
                    var label = trip.route + ' - ' + trip.departure + ' - ' + trip.bus + ' (' + trip.class + ')';
                    if (index === booking.trip_index) {
                        label += ' - current';
                    }
                    transferTrip.add(new Option(label, String(index)));
                });
                if (transferPicker) {
                    transferPicker.setDate(booking.date, false, 'j M Y');
                } else {
                    transferDate.value = booking.date;
                }
                transferReason.value = '';
                fillSeats();
                syncTransfer();
            };

            transferTrip.addEventListener('change', function () {
                fillSeats();
                syncTransfer();
            });
            transferSeat.addEventListener('change', syncTransfer);
            transferDate.addEventListener('change', syncTransfer);

            transferConfirm.addEventListener('click', function () {
                var index = parseInt(transferTrip.value, 10);
                var trip = trips[index];
                var previous = { route: current.route, date: current.date, seat: current.seat };
                Object.assign(current, {
                    trip_index: index,
                    route: trip.route,
                    route_detail: trip.from + ' -> ' + trip.to,
                    bus: trip.bus,
                    class: trip.class,
                    departure: trip.departure,
                    arrival: trip.arrival,
                    date: transferDate.value,
                    seat: transferSeat.value,
                    cost: Math.max(current.cost, trip.price)
                });
                updateRow(current);
                notify('transfer', current, { from: previous, reason: transferReason.value.trim(), fee: transferFee });
                hide(transferModal);
            });

            var editModal = document.getElementById('booking_my_edit_modal');
            var editForm = document.getElementById('booking_my_edit_form');
            setups.booking_my_edit_modal = function (modal, booking) {
                editForm.classList.remove('was-validated');
                ['firstname', 'lastname', 'phone', 'email', 'nationality', 'id_type', 'id_number'].forEach(function (name) {
                    editForm.elements[name].value = booking[name] || '';
                });
            };
            editForm.addEventListener('submit', function (event) {
                event.preventDefault();
                if (!editForm.checkValidity()) {
                    editForm.classList.add('was-validated');
                    return;
                }
                ['firstname', 'lastname', 'phone', 'email', 'nationality', 'id_type', 'id_number'].forEach(function (name) {
                    current[name] = editForm.elements[name].value.trim();
                });
                current.name = [current.firstname, current.lastname].filter(Boolean).join(' ');
                updateRow(current);
                notify('edit', current);
                hide(editModal);
            });

            var deleteModal = document.getElementById('booking_my_delete_modal');
            var deleteReason = document.getElementById('booking_my_delete_reason');
            var deleteConfirm = document.getElementById('booking_my_delete_confirm');
            var refund = document.getElementById('booking_my_refund');
            setups.booking_my_delete_modal = function (modal, booking) {
                var paid = booking.status === 'complete' || booking.status === 'booked';
                deleteReason.value = '';
                deleteConfirm.disabled = true;
                refund.checked = false;
                document.getElementById('booking_my_refund_wrap').hidden = !paid;
            };
            deleteReason.addEventListener('change', function () {
                deleteConfirm.disabled = !deleteReason.value;
            });
            deleteConfirm.addEventListener('click', function () {
                var row = rowFor(current.ticket);
                if (row) {
                    row.remove();
                }
                delete bookings[current.ticket];
                panel.setAttribute('data-ticket-count', Object.keys(bookings).length);
                notify('delete', current, { reason: deleteReason.value, refund: refund.checked });
                current = null;
                hide(deleteModal);
                document.dispatchEvent(new CustomEvent('booking:my-bookings-changed'));
            });
        })();

        (function () {
            var modal = document.getElementById('booking_lock_modal');
            var rowsEl = document.getElementById('booking_lock_rows');
            var chipsEl = document.getElementById('booking_lock_chips');
            var reasonInput = document.getElementById('booking_lock_reason');
            var submitBtn = document.getElementById('booking_lock_submit');
            var clearBtn = document.getElementById('booking_lock_clear');
            if (!modal || !rowsEl || !reasonInput || !submitBtn) {
                return;
            }

            var seatsPerRow = [1, 2, null, 3, 4];
            var locks = window.BOOKING_SEAT_LOCKS = window.BOOKING_SEAT_LOCKS || {};
            var trip = null;
            var trigger = null;
            var selected = [];

            function seatStates(index) {
                var states = {};
                ((window.BOOKING_MANIFESTS || {})[index] || []).forEach(function (p) {
                    if (p.status === 'complete' || p.status === 'booked') {
                        states[p.seat] = 'booked';
                    } else if (p.status === 'hold') {
                        states[p.seat] = 'hold';
                    }
                });
                Object.keys(locks[index] || {}).forEach(function (seat) {
                    states[seat] = 'locked';
                });
                return states;
            }

            function seatEl(seatNo, state) {
                var seat = document.createElement('span');
                seat.className = 'vehicle-seat';
                seat.setAttribute('data-seat', seatNo);
                if (state === 'booked') {
                    seat.classList.add('is-reserved');
                    seat.title = seatNo + ' - booked';
                } else if (state) {
                    seat.classList.add('is-disabled');
                    seat.title = state === 'locked' ? seatNo + ' - locked: ' + locks[trip.index][seatNo].reason : seatNo + ' - on hold';
                } else {
                    seat.setAttribute('role', 'button');
                    seat.setAttribute('tabindex', '0');
                    seat.setAttribute('aria-pressed', 'false');
                }
                var img = document.createElement('img');
                img.className = 'vehicle-seat-img';
                img.src = 'css/app/vehicle-seat.svg';
                img.alt = '';
                var label = document.createElement('span');
                label.className = 'vehicle-seat-label';
                label.textContent = seatNo;
                seat.appendChild(img);
                seat.appendChild(label);
                return seat;
            }

            function buildChart() {
                var states = seatStates(trip.index);
                var total = trip.seats;
                var rowCount = Math.ceil(total / 4);
                var counter = 0;
                var empty = 0;
                rowsEl.innerHTML = '';
                for (var r = 0; r < rowCount; r++) {
                    var wrap = document.createElement('div');
                    wrap.className = 'vehicle-seat-row-wrap';
                    var doorSlot = document.createElement('span');
                    doorSlot.className = 'vehicle-door-slot';
                    if (r === 4) {
                        doorSlot.innerHTML = '<span class="vehicle-door" title="Door">Door</span>';
                    }
                    var row = document.createElement('div');
                    row.className = 'vehicle-seat-row';
                    seatsPerRow.forEach(function (number) {
                        var slot;
                        if (number === null || counter >= total) {
                            slot = document.createElement('span');
                            slot.className = 'vehicle-seat ' + (number === null ? 'is-aisle' : 'is-empty');
                        } else {
                            counter++;
                            var seatNo = String.fromCharCode(65 + r) + number;
                            slot = seatEl(seatNo, states[seatNo]);
                            if (!states[seatNo]) {
                                empty++;
                            }
                        }
                        row.appendChild(slot);
                    });
                    var endSlot = document.createElement('span');
                    endSlot.className = 'vehicle-door-slot';
                    wrap.appendChild(doorSlot);
                    wrap.appendChild(row);
                    wrap.appendChild(endSlot);
                    rowsEl.appendChild(wrap);
                }
                document.getElementById('booking_lock_sum_empty').textContent = empty;
                document.getElementById('booking_lock_sum_locked').textContent = Object.keys(locks[trip.index] || {}).length;
            }

            function sync() {
                rowsEl.querySelectorAll('.vehicle-seat[role="button"]').forEach(function (seat) {
                    var on = selected.indexOf(seat.getAttribute('data-seat')) !== -1;
                    seat.classList.toggle('is-selected', on);
                    seat.setAttribute('aria-pressed', on ? 'true' : 'false');
                });
                chipsEl.innerHTML = '';
                if (!selected.length) {
                    chipsEl.innerHTML = '<span class="booking-lock-chips-empty">Click empty seats on the chart</span>';
                }
                selected.forEach(function (seatNo) {
                    var chip = document.createElement('button');
                    chip.type = 'button';
                    chip.className = 'booking-lock-chip';
                    chip.setAttribute('data-seat', seatNo);
                    chip.title = 'Remove ' + seatNo;
                    chip.innerHTML = '<span></span><i class="mdi mdi-close"></i>';
                    chip.firstChild.textContent = seatNo;
                    chipsEl.appendChild(chip);
                });
                document.getElementById('booking_lock_sum_selected').textContent = selected.length;
                clearBtn.disabled = !selected.length;
                submitBtn.disabled = !selected.length;
            }

            function toggleSeat(seat) {
                var seatNo = seat.getAttribute('data-seat');
                var at = selected.indexOf(seatNo);
                if (at === -1) {
                    selected.push(seatNo);
                } else {
                    selected.splice(at, 1);
                }
                sync();
            }

            function updateTriggerCount(button, index) {
                var badge = button && button.querySelector('.booking-lock-count');
                var count = Object.keys(locks[index] || {}).length;
                if (badge) {
                    badge.textContent = count;
                    badge.hidden = !count;
                }
            }

            modal.addEventListener('show.bs.modal', function (event) {
                trigger = event.relatedTarget;
                if (!trigger) {
                    return;
                }
                trip = {
                    index: trigger.getAttribute('data-trip-index'),
                    bus: trigger.getAttribute('data-bus') || '',
                    route: trigger.getAttribute('data-route') || '',
                    time: trigger.getAttribute('data-time') || '',
                    seats: parseInt(trigger.getAttribute('data-seats'), 10) || 0
                };
                selected = [];
                reasonInput.value = '';
                reasonInput.classList.remove('is-invalid');
                document.getElementById('booking_lock_meta').textContent = [trip.route, 'Bus ' + trip.bus, trip.time].filter(Boolean).join(' - ');
                document.getElementById('booking_lock_plate').textContent = trip.bus || ' - ';
                buildChart();
                sync();
            });

            rowsEl.addEventListener('click', function (event) {
                var seat = event.target.closest('.vehicle-seat[role="button"]');
                if (seat) {
                    toggleSeat(seat);
                }
            });

            rowsEl.addEventListener('keydown', function (event) {
                var seat = event.target.closest('.vehicle-seat[role="button"]');
                if (seat && (event.key === 'Enter' || event.key === ' ')) {
                    event.preventDefault();
                    toggleSeat(seat);
                }
            });

            chipsEl.addEventListener('click', function (event) {
                var chip = event.target.closest('.booking-lock-chip');
                if (chip) {
                    selected.splice(selected.indexOf(chip.getAttribute('data-seat')), 1);
                    sync();
                }
            });

            clearBtn.addEventListener('click', function () {
                selected = [];
                sync();
            });

            reasonInput.addEventListener('input', function () {
                reasonInput.classList.remove('is-invalid');
            });

            submitBtn.addEventListener('click', function () {
                var reason = reasonInput.value.trim();
                if (!selected.length) {
                    return;
                }
                if (!reason) {
                    reasonInput.classList.add('is-invalid');
                    reasonInput.focus();
                    return;
                }
                var lockedAt = new Date().toISOString();
                locks[trip.index] = locks[trip.index] || {};
                selected.forEach(function (seatNo) {
                    locks[trip.index][seatNo] = { reason: reason, locked_at: lockedAt };
                });
                document.dispatchEvent(new CustomEvent('booking:seats-locked', {
                    detail: { trip: trip, seats: selected.slice(), reason: reason }
                }));
                updateTriggerCount(trigger, trip.index);
                bootstrap.Modal.getOrCreateInstance(modal).hide();
            });
        })();

        (function () {
            var modal = document.getElementById('booking_hire_modal');
            var continueBtn = document.getElementById('booking_hire_continue');
            if (!modal || !continueBtn) {
                return;
            }

            var fields = {};
            modal.querySelectorAll('[data-hire]').forEach(function (el) {
                fields[el.getAttribute('data-hire')] = el;
            });
            var radios = modal.querySelectorAll('input[name="hire_output"]');
            var currentTrip = null;

            var hirePages = {
                invoice: 'generate_invoice.php',
                ticket: 'physical_booking.php'
            };

            function tripDate(display) {
                var input = document.getElementById('booking_date');
                if (!input) {
                    return '';
                }
                if (display && input._flatpickr && input._flatpickr.altInput) {
                    return input._flatpickr.altInput.value;
                }
                return input.value;
            }

            modal.addEventListener('show.bs.modal', function (event) {
                var trigger = event.relatedTarget;
                if (!trigger) {
                    return;
                }
                currentTrip = {
                    bus: trigger.getAttribute('data-bus') || '',
                    route: trigger.getAttribute('data-route') || '',
                    time: trigger.getAttribute('data-time') || '',
                    class: trigger.getAttribute('data-class') || '',
                    seats: trigger.getAttribute('data-seats') || '',
                    fare: trigger.getAttribute('data-fare') || '',
                    date: tripDate(false)
                };
                Object.keys(fields).forEach(function (key) {
                    fields[key].textContent = (key === 'date' ? tripDate(true) : currentTrip[key]) || ' - ';
                });
                radios.forEach(function (radio) {
                    radio.checked = false;
                });
                continueBtn.disabled = true;
            });

            radios.forEach(function (radio) {
                radio.addEventListener('change', function () {
                    continueBtn.disabled = !modal.querySelector('input[name="hire_output"]:checked');
                });
            });

            continueBtn.addEventListener('click', function () {
                var choice = modal.querySelector('input[name="hire_output"]:checked');
                if (!choice || !currentTrip || !hirePages[choice.value]) {
                    return;
                }
                window.location.href = hirePages[choice.value] + '?' + new URLSearchParams(currentTrip).toString();
            });
        })();
    </script>

</body>

</html>
