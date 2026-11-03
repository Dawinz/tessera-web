<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
if (!isset($_SESSION['username'])) {
	header('Location: index.php');
	exit;
}
$hire = [
    'bus' => $_GET['bus'] ?? '',
    'route' => $_GET['route'] ?? '',
    'date' => $_GET['date'] ?? '',
    'time' => $_GET['time'] ?? '',
    'class' => $_GET['class'] ?? '',
    'seats' => (int) ($_GET['seats'] ?? 0),
    'fare' => (float) ($_GET['fare'] ?? 0),
];
$route_parts = array_map('trim', explode('->', $hire['route'], 2));
$route_from = $route_parts[0] ?? '';
$route_to = $route_parts[1] ?? '';
$time_parts = array_map('trim', preg_split('/[ - -]/u', $hire['time'], 2));
$depart_time = $time_parts[0] ?? '';
$arrive_time = $time_parts[1] ?? '';
$trip_date = DateTime::createFromFormat('!Y-m-d', $hire['date']);
$trip_date_label = $trip_date ? $trip_date->format('D, j M Y') : $hire['date'];
$ticket_no = 'HT-' . date('ymd') . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

$total_seats = $hire['seats'] > 0 ? $hire['seats'] : 45;
$seat_rows = [];
for ($i = 0; $i < (int) ceil($total_seats / 4); $i++) {
    $seat_rows[] = chr(65 + $i);
}

function hire_e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES);
}
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Physical booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Pichforest" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="assets/css/vehicle-manager.css?v=<?= filemtime(__DIR__ . '/assets/css/vehicle-manager.css') ?>" rel="stylesheet" type="text/css" />
    <link href="css/app/booking.css?v=<?= filemtime('css/app/booking.css') ?>" rel="stylesheet" type="text/css" />
    <link href="css/app/booking-dark.css?v=<?= filemtime('css/app/booking-dark.css') ?>" rel="stylesheet" type="text/css" />
    <link href="css/app/hire.css?v=<?= filemtime('css/app/hire.css') ?>" rel="stylesheet" type="text/css" />
    <script src="js/vendor/jquery-1.12.4.min.js"></script>

</head>

<body class="booking-page" data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include 'top_header.php'; ?>
        <!-- top_header.php leaves an HTML comment open; this closes it -->

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Physical booking</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="booking.php">Booking</a></li>
                                        <li class="breadcrumb-item active">Hire ticket</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xxl-8 col-xl-8 col-lg-7">
                            <div class="card booking-explorer-card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h6 class="booking-explorer-heading mb-0">Ticket details</h6>
                                    <span class="booking-explorer-count"><?= hire_e($ticket_no) ?></span>
                                </div>
                                <div class="card-body">
                                    <form class="hire-form" id="ticket_form" autocomplete="off" onsubmit="return false;">
                                        <div class="hire-form-section">
                                            <div class="hire-form-section-title d-flex align-items-center justify-content-between">
                                                <span>Seats &amp; passengers</span>
                                                <span class="booking-explorer-count" id="tk_seat_count">0 seats</span>
                                            </div>
                                            <div class="hire-seat-empty" id="tk_seat_empty">
                                                <i class="mdi mdi-seat-passenger"></i>
                                                Select seats on the chart to add passenger details
                                            </div>
                                            <div class="hire-passenger-list" id="tk_passengers"></div>
                                        </div>

                                        <div class="hire-form-section">
                                            <div class="hire-form-section-title">Payment</div>
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="tk_amount">Hire amount</label>
                                                    <input class="form-control" type="number" min="0" step="1000" id="tk_amount" value="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="tk_paid">Amount paid</label>
                                                    <input class="form-control" type="number" min="0" step="1000" id="tk_paid" value="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="tk_payment">Payment method</label>
                                                    <select class="form-select" id="tk_payment">
                                                        <option value="Cash">Cash</option>
                                                        <option value="M-Pesa">M-Pesa</option>
                                                        <option value="Tigo Pesa">Tigo Pesa</option>
                                                        <option value="Banking">Banking</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hire-actions">
                                            <a href="booking.php" class="btn btn-light">Back</a>
                                            <button type="button" class="btn btn-primary" id="tk_print">
                                                <i class="mdi mdi-printer-outline"></i> Print ticket
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="hire-print-only">
                                <div>
                                    <div class="hire-print-area">
                                        <div class="hire-ticket">
                                            <div class="hire-ticket-main">
                                                <div class="hire-ticket-head">
                                                    <div class="hire-sheet-brand">
                                                        Ticket Holder
                                                        <small>Bus hire ticket</small>
                                                    </div>
                                                    <span class="hire-ticket-badge" data-view="status">Paid</span>
                                                </div>

                                                <div class="hire-ticket-route">
                                                    <div class="hire-ticket-city">
                                                        <?= hire_e($route_from ?: ' - ') ?>
                                                        <small><?= hire_e($depart_time) ?></small>
                                                    </div>
                                                    <span class="hire-ticket-line"></span>
                                                    <div class="hire-ticket-city text-end">
                                                        <?= hire_e($route_to ?: ' - ') ?>
                                                        <small><?= hire_e($arrive_time) ?></small>
                                                    </div>
                                                </div>

                                                <div class="hire-ticket-grid">
                                                    <div><span class="hire-sheet-label">Date</span><strong><?= hire_e($trip_date_label ?: ' - ') ?></strong></div>
                                                    <div><span class="hire-sheet-label">Bus</span><strong><?= hire_e($hire['bus'] ?: ' - ') ?></strong></div>
                                                    <div><span class="hire-sheet-label">Class</span><strong><?= hire_e($hire['class'] ?: ' - ') ?></strong></div>
                                                    <div><span class="hire-sheet-label">Passengers</span><strong data-view="passengers">0</strong></div>
                                                    <div><span class="hire-sheet-label">Payment</span><strong data-view="payment"></strong></div>
                                                </div>

                                                <div class="hire-ticket-seats">
                                                    <span class="hire-sheet-label">Seats</span>
                                                    <div class="hire-ticket-seat-list" data-view="seats"> - </div>
                                                </div>

                                                <table class="hire-sheet-table hire-ticket-passengers" id="tk_preview_passengers" hidden>
                                                    <thead>
                                                        <tr>
                                                            <th>Seat</th>
                                                            <th>Passenger</th>
                                                            <th>Phone</th>
                                                            <th>ID</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="hire-ticket-stub">
                                                <div><span class="hire-sheet-label">Ticket no.</span><strong><?= hire_e($ticket_no) ?></strong></div>
                                                <div><span class="hire-sheet-label">Amount paid</span><strong class="hire-ticket-amount" data-view="paid"></strong></div>
                                                <div><span class="hire-sheet-label">Balance</span><strong data-view="balance"></strong></div>
                                                <div class="hire-ticket-code" aria-hidden="true"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-4 col-lg-5">
                            <div class="card booking-workspace-card">
                                <div class="card-header justify-content-between">
                                    <h6 class="booking-explorer-heading mb-0">Seat chart</h6>
                                    <div class="hire-chart-tools">
                                        <button type="button" class="btn btn-soft-primary btn-sm" id="tk_select_all">Select all</button>
                                        <button type="button" class="btn btn-soft-secondary btn-sm" id="tk_clear_seats">Clear</button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="vehicle-workspace-chart booking-seat-panel" id="tk_chart">
                                        <div class="vehicle-deck-wrap">
                                            <div class="vehicle-deck">
                                                <div class="vehicle-deck-front">
                                                    <span class="vehicle-door-slot"></span>
                                                    <div class="vehicle-seat-row vehicle-driver-row" style="width:224px">
                                                        <span class="vehicle-seat vehicle-steering-slot is-empty"></span>
                                                        <span class="vehicle-driver-gap">
                                                            <span class="vehicle-bus-number" title="Bus number">
                                                                <span class="vehicle-plate-screw" aria-hidden="true"></span>
                                                                <span class="vehicle-plate-text"><?= hire_e($hire['bus'] ?: ' - ') ?></span>
                                                                <span class="vehicle-plate-screw" aria-hidden="true"></span>
                                                            </span>
                                                        </span>
                                                        <span class="vehicle-seat vehicle-steering-slot" title="Steering wheel">
                                                            <img class="vehicle-steering-img" src="css/app/vehicle-steering.svg" alt="">
                                                        </span>
                                                    </div>
                                                    <span class="vehicle-door-slot"></span>
                                                </div>
                                                <?php $seat_counter = 0; ?>
                                                <?php foreach ($seat_rows as $row_index => $row_letter) { ?>
                                                <div class="vehicle-seat-row-wrap">
                                                    <span class="vehicle-door-slot">
                                                        <?php if ($row_index === 4) { ?>
                                                        <span class="vehicle-door" title="Door">Door</span>
                                                        <?php } ?>
                                                    </span>
                                                    <div class="vehicle-seat-row">
                                                        <?php
                                                        foreach ([1, 2, null, 3, 4] as $number) {
                                                            if ($number === null) {
                                                                echo '<span class="vehicle-seat is-aisle"></span>';
                                                                continue;
                                                            }
                                                            if ($seat_counter >= $total_seats) {
                                                                echo '<span class="vehicle-seat is-empty"></span>';
                                                                continue;
                                                            }
                                                            $seat_counter++;
                                                            $seat_no = $row_letter . $number;
                                                        ?>
                                                        <span class="vehicle-seat" data-seat="<?= hire_e($seat_no) ?>" role="button" tabindex="0" aria-pressed="false">
                                                            <img class="vehicle-seat-img" src="css/app/vehicle-seat.svg" alt="">
                                                            <span class="vehicle-seat-label"><?= hire_e($seat_no) ?></span>
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
                                                    <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch is-selected"></span> Selected</span>
                                                    <span class="vehicle-seat-legend-item"><span class="vehicle-seat-legend-swatch is-door"></span> Door</span>
                                                </div>
                                                <div class="booking-seat-summary">
                                                    <div class="booking-seat-summary-item">
                                                        <span>Selected</span>
                                                        <strong id="tk_summary_selected">0</strong>
                                                    </div>
                                                    <div class="booking-seat-summary-item">
                                                        <span>Available</span>
                                                        <strong id="tk_summary_available"><?= $total_seats ?></strong>
                                                    </div>
                                                    <div class="booking-seat-summary-item">
                                                        <span>Total seats</span>
                                                        <strong><?= $total_seats ?></strong>
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

    <template id="tk_passenger_template">
        <div class="booking-passenger-form" data-passenger>
            <div class="booking-passenger-form-head">
                <span class="booking-passenger-seat">Seat <strong data-seat-label></strong></span>
                <div class="hire-passenger-tools">
                    <label class="booking-foc-pill">
                        <input type="checkbox" name="foc">
                        <span>FOC</span>
                    </label>
                    <button type="button" class="btn btn-soft-danger btn-sm hire-passenger-remove" title="Remove seat" aria-label="Remove seat">
                        <i class="mdi mdi-close"></i>
                    </button>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label" data-for="firstname">First name</label>
                    <input class="form-control" type="text" name="firstname" placeholder="First name">
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-for="lastname">Last name</label>
                    <input class="form-control" type="text" name="lastname" placeholder="Last name">
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-for="phone">Phone number</label>
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
                    <label class="form-label" data-for="nationality">Nationality</label>
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
                    <label class="form-label" data-for="id_number">Identification</label>
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
                    <label class="form-label" data-for="email">Email</label>
                    <input class="form-control" type="email" name="email" inputmode="email" autocomplete="email" placeholder="name@example.com">
                </div>
                <div class="col-md-4">
                    <label class="form-label" data-for="discount">Discount</label>
                    <div class="booking-phone-group booking-discount-group">
                        <select class="booking-phone-code" name="discount_type" aria-label="Discount type">
                            <option value="amount" selected>TZS</option>
                            <option value="percent">%</option>
                        </select>
                        <input class="form-control" type="number" name="discount" min="0" step="1" inputmode="numeric" placeholder="0">
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="tk_preview_passenger_template">
        <tr>
            <td><span class="booking-my-seat" data-field="seat"></span></td>
            <td>
                <span data-field="name"></span>
                <span class="hire-ticket-foc" data-field="foc" hidden>FOC</span>
                <div class="booking-table-sub" data-field="nationality"></div>
            </td>
            <td>
                <span data-field="phone"></span>
                <div class="booking-table-sub" data-field="email"></div>
            </td>
            <td data-field="id_number"></td>
        </tr>
    </template>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        (function () {
            var fare = <?= json_encode($hire['fare']) ?>;
            var form = document.getElementById('ticket_form');
            var chart = document.getElementById('tk_chart');
            var list = document.getElementById('tk_passengers');
            var empty = document.getElementById('tk_seat_empty');
            var countEl = document.getElementById('tk_seat_count');
            var amountInput = document.getElementById('tk_amount');
            var paidInput = document.getElementById('tk_paid');
            var passengerTemplate = document.getElementById('tk_passenger_template');
            var previewTemplate = document.getElementById('tk_preview_passenger_template');
            var previewTable = document.getElementById('tk_preview_passengers');
            var amountTouched = false;
            var paidTouched = false;
            var views = {};
            document.querySelectorAll('[data-view]').forEach(function (el) {
                views[el.getAttribute('data-view')] = el;
            });

            function money(value) {
                return 'TZS ' + Number(value || 0).toLocaleString('en-US', { maximumFractionDigits: 0 });
            }

            function field(id) {
                return document.getElementById(id).value.trim();
            }

            function positive(value) {
                return Math.max(0, parseFloat(value) || 0);
            }

            function seatOrder(a, b) {
                var rowA = a.charCodeAt(0);
                var rowB = b.charCodeAt(0);
                return rowA === rowB ? parseInt(a.slice(1), 10) - parseInt(b.slice(1), 10) : rowA - rowB;
            }

            function seatNodes() {
                return Array.prototype.slice.call(chart.querySelectorAll('.vehicle-seat[data-seat]'));
            }

            function addPassenger(seatNo) {
                if (list.querySelector('[data-passenger][data-seat="' + seatNo + '"]')) {
                    return;
                }
                var node = passengerTemplate.content.cloneNode(true);
                var card = node.querySelector('[data-passenger]');
                card.setAttribute('data-seat', seatNo);
                card.querySelector('[data-seat-label]').textContent = seatNo;
                card.querySelectorAll('label[data-for]').forEach(function (label) {
                    var name = label.getAttribute('data-for');
                    var id = 'tk_' + name + '_' + seatNo;
                    label.setAttribute('for', id);
                    card.querySelector('[name="' + name + '"]').id = id;
                });
                card.querySelector('[name="foc"]').id = 'tk_foc_' + seatNo;

                var next = Array.prototype.find.call(list.children, function (existing) {
                    return seatOrder(existing.getAttribute('data-seat'), seatNo) > 0;
                });
                list.insertBefore(node, next || null);

                var nationality = card.querySelector('[name="nationality"]');
                if (window.Choices && nationality) {
                    card._nationalityChoice = new window.Choices(nationality, {
                        searchEnabled: true,
                        searchPlaceholderValue: 'Search nationality',
                        itemSelectText: '',
                        shouldSort: false,
                        allowHTML: false
                    });
                }
            }

            function removePassenger(seatNo) {
                var card = list.querySelector('[data-passenger][data-seat="' + seatNo + '"]');
                if (!card) {
                    return;
                }
                if (card._nationalityChoice) {
                    card._nationalityChoice.destroy();
                }
                card.remove();
            }

            function setSeat(seat, selected) {
                var seatNo = seat.getAttribute('data-seat');
                seat.classList.toggle('is-selected', selected);
                seat.setAttribute('aria-pressed', selected ? 'true' : 'false');
                if (selected) {
                    addPassenger(seatNo);
                } else {
                    removePassenger(seatNo);
                }
            }

            function readPassengers() {
                return Array.prototype.map.call(list.querySelectorAll('[data-passenger]'), function (card) {
                    function value(name) {
                        return card.querySelector('[name="' + name + '"]').value.trim();
                    }
                    var phone = value('phone');
                    var idNumber = value('id_number');
                    var idType = value('id_type');
                    return {
                        seat: card.getAttribute('data-seat'),
                        foc: card.querySelector('[name="foc"]').checked,
                        name: [value('firstname'), value('lastname')].filter(Boolean).join(' '),
                        phone: phone ? value('country_code') + ' ' + phone : '',
                        nationality: value('nationality'),
                        email: value('email'),
                        id_number: idNumber ? (idType ? idType + ' - ' : '') + idNumber : '',
                        discount: parseFloat(value('discount')) || 0,
                        discount_type: value('discount_type') || 'amount'
                    };
                });
            }

            function renderPreviewPassengers(passengers) {
                var body = previewTable.querySelector('tbody');
                body.innerHTML = '';
                passengers.forEach(function (passenger) {
                    var node = previewTemplate.content.cloneNode(true);
                    node.querySelector('[data-field="seat"]').textContent = passenger.seat;
                    node.querySelector('[data-field="name"]').textContent = passenger.name || ' - ';
                    node.querySelector('[data-field="foc"]').hidden = !passenger.foc;
                    node.querySelector('[data-field="nationality"]').textContent = passenger.nationality;
                    node.querySelector('[data-field="phone"]').textContent = passenger.phone || ' - ';
                    node.querySelector('[data-field="email"]').textContent = passenger.email;
                    node.querySelector('[data-field="id_number"]').textContent = passenger.id_number || ' - ';
                    body.appendChild(node);
                });
                previewTable.hidden = !passengers.length;
            }

            function render() {
                var passengers = readPassengers();
                var count = passengers.length;
                var total = seatNodes().length;

                empty.hidden = count > 0;
                countEl.textContent = count + (count === 1 ? ' seat' : ' seats');
                document.getElementById('tk_summary_selected').textContent = count;
                document.getElementById('tk_summary_available').textContent = total - count;

                if (!amountTouched) {
                    amountInput.value = passengers.reduce(function (sum, p) {
                        if (p.foc) {
                            return sum;
                        }
                        var off = p.discount_type === 'percent' ? fare * p.discount / 100 : p.discount;
                        return sum + Math.max(0, fare - Math.min(fare, off || 0));
                    }, 0);
                }
                if (!paidTouched) {
                    paidInput.value = amountInput.value;
                }

                views.passengers.textContent = count;
                views.payment.textContent = field('tk_payment');
                views.seats.textContent = count ? passengers.map(function (p) { return p.seat; }).join(', ') : ' - ';
                renderPreviewPassengers(passengers);

                var amount = positive(amountInput.value);
                var paid = positive(paidInput.value);
                var balance = Math.max(0, amount - paid);
                views.paid.textContent = money(paid);
                views.balance.textContent = money(balance);
                views.status.textContent = balance > 0 ? (paid > 0 ? 'Partly paid' : 'Unpaid') : 'Paid';
                views.status.classList.toggle('is-due', balance > 0);
            }

            function toggleSeatFromEvent(event) {
                var seat = event.target.closest('.vehicle-seat[data-seat]');
                if (!seat) {
                    return;
                }
                setSeat(seat, !seat.classList.contains('is-selected'));
                render();
            }

            chart.addEventListener('click', toggleSeatFromEvent);
            chart.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleSeatFromEvent(event);
                }
            });

            list.addEventListener('click', function (event) {
                var remove = event.target.closest('.hire-passenger-remove');
                if (!remove) {
                    return;
                }
                var seatNo = remove.closest('[data-passenger]').getAttribute('data-seat');
                var seat = chart.querySelector('.vehicle-seat[data-seat="' + seatNo + '"]');
                if (seat) {
                    setSeat(seat, false);
                }
                render();
            });

            document.getElementById('tk_select_all').addEventListener('click', function () {
                seatNodes().forEach(function (seat) {
                    setSeat(seat, true);
                });
                render();
            });

            document.getElementById('tk_clear_seats').addEventListener('click', function () {
                seatNodes().forEach(function (seat) {
                    setSeat(seat, false);
                });
                render();
            });

            amountInput.addEventListener('input', function () {
                amountTouched = true;
            });
            paidInput.addEventListener('input', function () {
                paidTouched = true;
            });
            form.addEventListener('input', render);
            form.addEventListener('change', render);
            document.getElementById('tk_print').addEventListener('click', function () {
                window.print();
            });
            render();
        })();
    </script>

</body>

</html>
