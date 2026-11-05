<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$vehicle = $_GET['bus'] ?? '';
$date = $_GET['date'] ?? '';
include 'page_info_reload.php';
include 'modules/connection/connect.php';
if ($date === '' || (int) date('Y', strtotime($date) ?: 0) < 2000) {
	$latest = $pdo->query("SELECT Name, date, Driver, Conductor, code, Route, status, currency FROM vehicle_name WHERE date >= '2020-01-01' ORDER BY date DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
	if ($latest) {
		$vehicle = $latest['Name'];
		$date = $latest['date'];
		$_GET['bus'] = $latest['Name'];
		$_GET['date'] = $latest['date'];
		$_GET['driver'] = base64_encode((string) $latest['Driver']);
		$_GET['conductor'] = base64_encode((string) $latest['Conductor']);
		$_GET['manifest_id'] = base64_encode((string) $latest['code']);
		$_GET['route_name'] = base64_encode((string) $latest['Route']);
		$_GET['currency'] = base64_encode((string) ($latest['currency'] ?? ''));
		$_GET['open'] = $latest['status'];
		$_GET['id'] = $_GET['id'] ?? base64_encode('1');
	}
}
include 'modules/read/general/Read.php';



?>

<!doctype html>
<html lang="en">
<style>
    .card-wrap {
        width: 100%;

        background: #1a1a2e;
        border-radius: 20px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        position: relative;
    }

    #mo_delete {
        display: none;
    }

    #mo_delete2 {
        display: none;
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

    .status_badge {
        background-color: green;

    }
</style>

<head>

    <meta charset="utf-8" />
    <?php $tessera_page_label = 'Check luggage'; include __DIR__ . '/includes/tessera_page_head.php'; ?><meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />

</head>


<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark">
    <!-- <body data-layout="horizontal"> -->
    <div id="layout-wrapper">
        <?php include 'top_header.php'; ?>
        <link href="css/general/check_luggages.css<?php echo "?" . modified_time("css/general/check_luggages.css"); ?>"
            rel="stylesheet" type="text/css" />

        <style>
.alertify-notifier.ajs-top {
    top: 50% !important;
    transform: translateY(-50%);
}

.alertify-notifier.ajs-center {
    left: 50% !important;
    transform: translate(-50%, -50%);
}
        </style>
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    <div class="bg-overlay"></div>
                    <div class="row">
                        <div class="panel">

                            <?php
                            $select = ['first_point,second_point,value'];
                            $where = [];
                            $currency_values = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "currency_value", 'order' => "", 'limit' => ""]);
                            $currency_string = json_encode($currency_values->rows);
                            $remark = @$_GET["remark"];
                            ?>
                            <div id="manifest_data" data-vehicle="<?= $_GET['bus'] ?>"
                                data-trip_date="<?= $_GET['date'] ?>" data-manifest_id="<?= $_GET['manifest_id'] ?>"
                                data-username="<?= $_SESSION['username'] ?>" data-currency="<?= $_GET['currency'] ?>"
                                data-route_name="" data-id="<?= $_GET['id'] ?>"   data-vehicle="<?= $_GET['bus'] ?>"   >
                                <div id="currency_string" style="display:none;"><?= $currency_string ?></div>
                                <div id="remark_html" style="display:none;">
                                    <?= htmlspecialchars(base64_decode($remark)); ?></div>
                                <div id="blink" style="display:none;"></div>

                            </div>
                            <div class="vehicle-grid" style="text-align: center;">
                                <div><span>Manifest ID</span><strong><span><span><img
                                                    src="assets/images/manifest/manifest.png"
                                                    style="width:28px"></span><?= base64_decode($_GET['manifest_id']) ?></span></strong>
                                </div>
                                <div><span>Vehicle Name</span><strong><span><img
                                                src="assets/images/manifest/transport.png"
                                                style="width:28px"></span><span><?= $_GET['bus'] ?></span></strong>
                                </div>
                                <div><span>Trip Date</span><strong><span><img src="assets/images/manifest/trip_date.png"
                                                style="width:28px">
                                        </span><span><?= htmlspecialchars(date('jS M Y', strtotime($date)), ENT_QUOTES, 'UTF-8'); ?></span></strong>
                                </div>
                                <div><span>Driver</span><strong><span><span><img src="assets/images/manifest/driver.png"
                                                    style="width:28px;">
                                            </span><?= htmlspecialchars(base64_decode($_GET['driver'])); ?></span></strong>
                                </div>
                                <div><span>Conductor</span><strong><span><span><img
                                                    src="assets/images/manifest/conductor.png" style="width:28px;">
                                            </span><?= htmlspecialchars(base64_decode($_GET['conductor'])); ?></span></strong>
                                </div>
                            </div>



                        </div>
                    </div>
                    <?php include 'modal/remarks.php'; ?>

                    <div class="row align-items-stretch">
                        <div class="col-lg-6 luggage_attentication">
                            <div class="card card-wrap">
                                <div id="server_response" class="d-none"></div>
                                <!-- end card header -->
                                <div class="sections-wrapper">
                                    <section class="luggage_check" style="height:570px;">
                                        <div class="card-header justify-content-between d-flex align-items-center">
                                            <h4 class="card-title">Load Cargo</h4>
                                            <span id="remark_btn" class="btn btn-sm btn-soft-secondary"><span
                                                    class="mdi mdi-comment-edit-outline"></span> Add Remarks </span>
                                        </div>

                                        <div class="card-body">


                                            <section class="stretch-summary" aria-label="Payment totals">
                                                <div class="stretch-item">
                                                    <span>Total Paid</span>
                                                    <strong class="summary-value" id="totalPayed"></strong>
                                                </div>

                                                <div class="stretch-divider" aria-hidden="true"></div>

                                                <div class="stretch-item">
                                                    <span>Total To Be Paid</span>
                                                    <strong id="totalToBePaid"></strong>
                                                </div>

                                                <div class="stretch-divider" aria-hidden="true"></div>

                                                <div class="stretch-item">
                                                    <span>Total Amount</span>
                                                    <strong id="totalAmount"></strong>
                                                </div>
                                            </section>
                                            <div class="text-center py-5">

                                                <div class="mb-4">


                                                    <h5 class="">Luggage Code</h5>
                                                    <p>Fill in the luggage code to register it on the manifest.</p>
                                                </div>
                                                <br><br>


                                                <form autocomplete="off">
                                                    <div class="form-floating form-floating-custom mb-3">

                                                        <input type="text" class="form-control" id="code"
                                                            placeholder="Enter Code" value="" autofocus>


                                                        <div class="form-floating-icon ">
                                                            <i class="uil uil-pen"></i>
                                                        </div>
                                                    </div>




                                                    <div class="mt-3">
                                                        <button class="btn  w-100 btn btn-primary" type="button"
                                                            id="send">
                                                            <img src="assets/images/gif/general/loader_clock.gif"
                                                                class=" search_loader"
                                                                style="width:22px; display:none;"><i
                                                                class="uil uil-search"></i> Search</button>
                                                    </div>
                                                </form>

                                            </div>


                                        </div>
                                    </section>
                                    <section class="upload_manifest" style="display:none;">

                                        <div class="hero">
                                            <div>
                                                <div class="code-label">Reference code</div>
                                                <div class="code-value"><span class="code_no_holder"></span></div>
                                            </div>
                                            <a class="more-detail">
                                                <div class="status-pill" style="cursor: pointer;">
                                                    <span class="dot"></span>
                                                    more details
                                                </div>
                                            </a>
                                        </div>
                                        <?php
                                        $route_name = base64_decode($_GET['route_name']);
                                        $parts = explode(" to ", $route_name);

                                        $from = trim($parts[0]); 
                                        $to = trim($parts[1]);
                                        ?>
                                        <div class="route">
                                            <div class="route-city">
                                                <span>From</span>
                                                <?php echo $from; ?>
                                            </div>
                                            <div class="route-line">
                                                <div class="line"></div>
                                                <i class="ti ti-plane route-icon" aria-hidden="true"></i><span class="">
                                                    <h6><span id="status_holder"
                                                            class="status_badge badge badge-secondary">To be
                                                            paid</span> </h6>
                                                </span>
                                                <div class="line"></div>
                                            </div>
                                            <div class="route-city">
                                                <span>To</span>
                                                <?php echo $to; ?>
                                            </div>
                                        </div>

                                        <!-- Parties section -->
                                        <div class="divider-label">Pricing</div>
                                        <div class="rows row_data ">
                                            <div class="row ">
                                                <div class="col text-center">
                                                    <div class="row-label">
                                                        <i class="ti ti-user-circle icon" aria-hidden="true"></i>
                                                        Quantity
                                                    </div>
                                                    <div class=""><span id="actual_qty" class="pricing_items"></span>
                                                    </div>
                                                </div>
                                                <div class="col text-center">
                                                    <div class="row-label">
                                                        <i class="ti ti-user-check icon" aria-hidden="true"></i>
                                                        <span class="">To be paid</span>
                                                    </div>
                                                    <div class="row-value"><span class="pricing_items"
                                                            id="actual_to_be"></span></div>
                                                </div>
                                                <div class="col text-center">
                                                    <div class="row-label">
                                                        <i class="ti ti-box icon" aria-hidden="true"></i>
                                                        Paid
                                                    </div>
                                                    <div class="row-value "><span><span id="actual_paid"
                                                                class="pricing_items"></span></span></div>
                                                </div>
                                                <div class="col text-center">
                                                    <div class="row-label">
                                                        <i class="ti ti-box icon" aria-hidden="true"></i>
                                                        Amount
                                                    </div>
                                                    <div class="row-value "><span id="actual_amount"
                                                            class="pricing_items"></span></div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="divider-label">Payment</div>
                                        <div class="row_data center_div">
                                            <h5><span class="red_round qty_remain_holder"></span> Luggage(s) in store
                                            </h5>
                                        </div>

                                        <div class="row_data">
                                            <div class="mb-3">
                                                <label>Quantity</label>
                                                <div class="nk-int-st">
                                                    <input type="text" class="form-control" id="qty"
                                                        placeholder="QUANTITY" value=""
                                                        pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"
                                                        autofocus>
                                                </div>
                                            </div>

                                            <div class="mb-3" id="to_be_panel">
                                                <label>To be paid</label>
                                                <div class="nk-int-st">
                                                    <input type="text" class="form-control" id="to_be"
                                                        placeholder="To be paid" value=""
                                                        pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                                                </div>
                                            </div>

                                            <div class="mb-3" id="paid_panel">
                                                <label>Paid</label>
                                                <div class="nk-int-st">
                                                    <input type="text" class="form-control" id="paid"
                                                        placeholder="Payed" value=""
                                                        pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2" style="padding-top:10px;">
                                                <button class="btn btn-dark w-25" type="button" id="back_btn"><-
                                                    Back</button>

                                                <button class="btn  w-75 btn btn-primary" type="button" id="load_item">
                                                    <img src="assets/images/gif/general/loader_clock.gif"
                                                        class=" search_loader_load" style="width:22px; display:none;">->
                                                    Load</button>
                                            </div>

                                        </div>


                                        <!-- Footer actions -->



                                    </section>
                                </div>
                            </div><!-- end card -->

                        </div><!-- end col -->

                        <div class="col-lg-6 luggage_details">
                            <div class="card">
                                <div class="card-header justify-content-between d-flex align-items-center">
                                    <h4 class="card-title">Cargo Manifest</h4>
                                    <a href="vehicle_report_new.php?date=<?= $_GET['date'] ?>&date2=<?= $_GET['date'] ?>&date3=<?= $_GET['bus'] ?>&open=<?= $_GET['open'] ?>&route=<?= $_GET['route_name']; ?>&driver=<?= $_GET['driver'] ?>&Conductor=<?= $_GET['conductor'] ?>&code=<?= $_GET['driver'] ?>&remark=<?= $_GET['remark'] ?> "
                                        class="btn btn-sm btn-soft-secondary">View more <i
                                            class="mdi mdi-arrow-right align-middle"></i></a>
                                </div><!-- end card header -->
                                <div class="table-overlay" role="dialog" aria-modal="true"
                                    aria-labelledby="overlay-title">
                                    <section class="overlay-panel">

                                        <div style="padding:10px 20px 10px 20px;">
                                            <input class="sr-table-search form-control " type="text" value="" id="search"
                                                placeholder="Search Luggage...">
                                        </div>

                                        <div class="table-wrap" >
                                            <div class="table-frame is-loading" aria-busy="true">
                                                <table class="table mb-0" id="empTable" style="width:100%;">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th></th>
                                                            <th>Code</th>                                 
                                                            <th>Quantity</th>
                                                            <th>To paid</th>
                                                            <th>Paid</th>

                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>
                                                            <td class="muted">
                                                                <p class="placeholder-glow"><span
                                                                        class="placeholder col-12"></span></p>
                                                            </td>

                                                        </tr>


                                                    </tbody>
                                                </table>

                                                <div class="table-body-overlay" role="status" aria-live="polite">
                                                    <span class="loader" aria-hidden="true"></span>
                                                    Loading table data...
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div><!-- end card -->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
<!-- JAVASCRIPT -->


<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
<script src="assets/libs/metismenujs/metismenujs.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>





<script src="assets/js/pages/3jquery.js"></script>
<script src="toast.script.js"></script>
<!-- notification init -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script src="assets/libs/alertifyjs/build/alertify.min.js"></script>

<script src="js/sweatalert2.js"></script>
<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
<script src="unlock.js"></script>
<script src="currency.js"></script>
<script src="checked.js?<?php echo modified_time("checked.js"); ?>"></script>
<script src="load_parcel.js?<?php echo modified_time("load_parcel.js"); ?>"></script>
<script src="fetch_manifest.js?<?php echo htmlspecialchars(modified_time('fetch_manifest.js'), ENT_QUOTES, 'UTF-8'); ?>"></script>
<script src="js/insert/remark.js?<?php echo htmlspecialchars(modified_time('js/insert/remark.js'), ENT_QUOTES, 'UTF-8'); ?>"></script>
<script
    src="js/delete/manifest/delete_luggage.js?<?php echo htmlspecialchars(modified_time('js/delete/manifest/delete_luggage.js'), ENT_QUOTES, 'UTF-8'); ?>"></script>