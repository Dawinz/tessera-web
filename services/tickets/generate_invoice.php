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
$trip_date = DateTime::createFromFormat('!Y-m-d', $hire['date']);
$trip_date_label = $trip_date ? $trip_date->format('D, j M Y') : $hire['date'];
$invoice_items = [];
if ($hire['seats'] > 0 || $hire['fare'] > 0) {
    $invoice_items[] = [
        'description' => 'Bus hire - ' . ($hire['route'] ?: 'Private trip'),
        'detail' => implode(' - ', array_filter([
            $trip_date_label,
            $hire['time'],
            $hire['class'],
            $hire['bus'] ? 'Bus ' . $hire['bus'] : '',
        ])),
        'qty' => max(1, $hire['seats']),
        'unit' => $hire['fare'],
    ];
}
$invoice_no = 'INV-' . date('Ymd') . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
$issue_date = date('Y-m-d');
$due_date = date('Y-m-d', strtotime('+7 days'));

function hire_e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES);
}
?>
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Generate invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Pichforest" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
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
                                <h4 class="mb-0">Generate invoice</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="booking.php">Booking</a></li>
                                        <li class="breadcrumb-item active">Hire invoice</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form class="hire-form" id="invoice_form" autocomplete="off" onsubmit="return false;">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card booking-explorer-card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h6 class="booking-explorer-heading mb-0">Bill to</h6>
                                    <span class="booking-explorer-count"><?= hire_e($invoice_no) ?></span>
                                </div>
                                <div class="card-body">
                                    <div class="hire-form-section">
                                        <div class="hire-form-section-title">Hired trip</div>
                                        <div class="hire-trip-strip">
                                            <div><span>Route</span><strong><?= hire_e($hire['route'] ?: ' - ') ?></strong></div>
                                            <div><span>Bus</span><strong><?= hire_e($hire['bus'] ?: ' - ') ?></strong></div>
                                            <div><span>Date</span><strong><?= hire_e($trip_date_label ?: ' - ') ?></strong></div>
                                            <div><span>Time</span><strong><?= hire_e($hire['time'] ?: ' - ') ?></strong></div>
                                            <div><span>Class</span><strong><?= hire_e($hire['class'] ?: ' - ') ?></strong></div>
                                            <div><span>Seats</span><strong><?= $hire['seats'] ?: ' - ' ?></strong></div>
                                        </div>
                                    </div>

                                    <div class="hire-form-section">
                                        <div class="hire-form-section-title">Customer</div>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label" for="inv_customer">Customer name</label>
                                                <input class="form-control" type="text" id="inv_customer" data-bind="customer" placeholder="Full name">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="inv_company">Company</label>
                                                <input class="form-control" type="text" id="inv_company" data-bind="company" placeholder="Optional">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="inv_phone">Phone</label>
                                                <input class="form-control" type="tel" id="inv_phone" data-bind="phone" placeholder="+255 712 345 678">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="inv_email">Email</label>
                                                <input class="form-control" type="email" id="inv_email" data-bind="email" placeholder="name@example.com">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="inv_tin">TIN</label>
                                                <input class="form-control" type="text" id="inv_tin" data-bind="tin" placeholder="Optional">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="inv_due">Due date</label>
                                                <input class="form-control" type="text" id="inv_due" value="<?= hire_e($due_date) ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card booking-explorer-card hire-items-card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h6 class="booking-explorer-heading mb-0">Invoice items</h6>
                                    <button type="button" class="btn btn-soft-primary btn-sm hire-item-add" id="inv_add_item">
                                        <i class="mdi mdi-plus"></i> Add item
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="hire-form-section">
                                        <div class="hire-item-head">
                                            <span>Description</span>
                                            <span>Qty</span>
                                            <span>Unit price</span>
                                            <span class="text-end">Amount</span>
                                            <span></span>
                                        </div>
                                        <div class="hire-item-list" id="inv_items"></div>
                                    </div>

                                    <div class="hire-form-section">
                                        <div class="hire-form-section-title">Charges</div>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label" for="inv_discount">Discount</label>
                                                <input class="form-control" type="number" min="0" step="1000" id="inv_discount" value="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="inv_deposit">Deposit paid</label>
                                                <input class="form-control" type="number" min="0" step="1000" id="inv_deposit" value="0">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="inv_notes">Notes</label>
                                                <textarea class="form-control" id="inv_notes" data-bind="notes" rows="2" placeholder="Payment terms, pickup point, etc."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="hire-actions">
                                        <a href="booking.php" class="btn btn-light">Back</a>
                                        <button type="button" class="btn btn-primary" id="inv_print">
                                            <i class="mdi mdi-printer-outline"></i> Print invoice
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="card booking-explorer-card hire-preview-card">
                                <div class="card-header">
                                    <h6 class="booking-explorer-heading mb-0">Preview</h6>
                                </div>
                                <div class="card-body">
                                    <div class="hire-print-area">
                                        <div class="hire-sheet">
                                            <div class="hire-sheet-head">
                                                <div class="hire-sheet-brand">
                                                    Ticket Holder
                                                    <small>Bus hire services</small>
                                                </div>
                                                <div class="hire-sheet-title">
                                                    <h3>Invoice</h3>
                                                    <div><?= hire_e($invoice_no) ?></div>
                                                    <div>Issued <?= date('j M Y', strtotime($issue_date)) ?></div>
                                                    <div>Due <span data-view="due"></span></div>
                                                </div>
                                            </div>

                                            <div class="hire-sheet-parties">
                                                <div>
                                                    <span class="hire-sheet-label">Bill to</span>
                                                    <strong data-view="customer">Customer name</strong>
                                                    <div data-view="company"></div>
                                                    <div data-view="phone"></div>
                                                    <div data-view="email"></div>
                                                    <div data-view="tin"></div>
                                                </div>
                                                <div>
                                                    <span class="hire-sheet-label">Trip</span>
                                                    <strong><?= hire_e($hire['route']) ?></strong>
                                                    <div><?= hire_e($trip_date_label) ?> - <?= hire_e($hire['time']) ?></div>
                                                    <div>Bus <?= hire_e($hire['bus']) ?> - <?= hire_e($hire['class']) ?></div>
                                                </div>
                                            </div>

                                            <table class="hire-sheet-table">
                                                <thead>
                                                    <tr>
                                                        <th class="is-index">#</th>
                                                        <th>Description</th>
                                                        <th class="is-amount">Qty</th>
                                                        <th class="is-amount">Unit price</th>
                                                        <th class="is-amount">Amount (TZS)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="inv_preview_items"></tbody>
                                            </table>

                                            <div class="hire-sheet-totals">
                                                <div><span>Subtotal</span><span data-view="subtotal"></span></div>
                                                <div><span>Discount</span><span data-view="discount"></span></div>
                                                <div><span>Deposit paid</span><span data-view="deposit"></span></div>
                                                <div class="is-grand"><span>Balance due</span><span data-view="balance"></span></div>
                                            </div>

                                            <div class="hire-sheet-notes" data-view="notes" hidden></div>

                                            <div class="hire-sheet-foot">Thank you for choosing us.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
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

    <template id="invoice_item_form_template">
        <div class="hire-item-row" data-item>
            <div class="hire-item-desc">
                <input class="form-control" type="text" name="description" placeholder="Item description">
                <input class="form-control hire-item-detail" type="text" name="detail" placeholder="Details (optional)">
            </div>
            <input class="form-control" type="number" name="qty" min="0" step="1" value="1" aria-label="Quantity">
            <input class="form-control" type="number" name="unit" min="0" step="1000" value="0" aria-label="Unit price">
            <span class="hire-item-amount" data-field="amount">0</span>
            <button type="button" class="btn btn-soft-danger btn-sm hire-item-remove" title="Remove item" aria-label="Remove item">
                <i class="mdi mdi-trash-can-outline"></i>
            </button>
        </div>
    </template>

    <template id="invoice_item_template">
        <tr>
            <td class="is-index" data-field="index"></td>
            <td>
                <strong data-field="description"></strong>
                <div class="booking-table-sub" data-field="detail"></div>
            </td>
            <td class="is-amount" data-field="qty"></td>
            <td class="is-amount" data-field="unit"></td>
            <td class="is-amount" data-field="amount"></td>
        </tr>
    </template>

    <template id="invoice_item_empty_template">
        <tr>
            <td class="hire-sheet-empty" colspan="5">No invoice items yet</td>
        </tr>
    </template>

    <script>
        window.INVOICE_ITEMS = <?= json_encode($invoice_items, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    </script>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        (function () {
            var form = document.getElementById('invoice_form');
            var dueInput = document.getElementById('inv_due');
            var views = {};
            document.querySelectorAll('[data-view]').forEach(function (el) {
                var key = el.getAttribute('data-view');
                (views[key] = views[key] || []).push(el);
            });

            function money(value) {
                return Number(value || 0).toLocaleString('en-US', { maximumFractionDigits: 0 });
            }

            function setView(key, text) {
                (views[key] || []).forEach(function (el) {
                    el.textContent = text;
                });
            }

            function numberValue(id) {
                return Math.max(0, parseFloat(document.getElementById(id).value) || 0);
            }

            var itemList = document.getElementById('inv_items');
            var previewItems = document.getElementById('inv_preview_items');
            var itemFormTemplate = document.getElementById('invoice_item_form_template');
            var itemTemplate = document.getElementById('invoice_item_template');
            var emptyTemplate = document.getElementById('invoice_item_empty_template');

            function positive(value) {
                return Math.max(0, parseFloat(value) || 0);
            }

            function addItem(item, focus) {
                var node = itemFormTemplate.content.cloneNode(true);
                var row = node.querySelector('[data-item]');
                item = item || {};
                row.querySelector('[name="description"]').value = item.description || '';
                row.querySelector('[name="detail"]').value = item.detail || '';
                row.querySelector('[name="qty"]').value = item.qty != null ? item.qty : 1;
                row.querySelector('[name="unit"]').value = item.unit != null ? item.unit : 0;
                itemList.appendChild(node);
                if (focus) {
                    row.querySelector('[name="description"]').focus();
                }
                render();
            }

            function readItems() {
                return Array.prototype.map.call(itemList.querySelectorAll('[data-item]'), function (row) {
                    var qty = positive(row.querySelector('[name="qty"]').value);
                    var unit = positive(row.querySelector('[name="unit"]').value);
                    return {
                        row: row,
                        description: row.querySelector('[name="description"]').value.trim(),
                        detail: row.querySelector('[name="detail"]').value.trim(),
                        qty: qty,
                        unit: unit,
                        amount: qty * unit
                    };
                });
            }

            function renderItems(items) {
                previewItems.innerHTML = '';
                var visible = items.filter(function (item) {
                    return item.description || item.amount;
                });
                if (!visible.length) {
                    previewItems.appendChild(emptyTemplate.content.cloneNode(true));
                }
                visible.forEach(function (item, index) {
                    var node = itemTemplate.content.cloneNode(true);
                    node.querySelector('[data-field="index"]').textContent = index + 1;
                    node.querySelector('[data-field="description"]').textContent = item.description || 'Item';
                    var detail = node.querySelector('[data-field="detail"]');
                    detail.textContent = item.detail;
                    detail.hidden = !item.detail;
                    node.querySelector('[data-field="qty"]').textContent = money(item.qty);
                    node.querySelector('[data-field="unit"]').textContent = money(item.unit);
                    node.querySelector('[data-field="amount"]').textContent = money(item.amount);
                    previewItems.appendChild(node);
                });
                items.forEach(function (item) {
                    item.row.querySelector('[data-field="amount"]').textContent = money(item.amount);
                });
            }

            function render() {
                form.querySelectorAll('[data-bind]').forEach(function (input) {
                    var key = input.getAttribute('data-bind');
                    var value = input.value.trim();
                    if (key === 'customer') {
                        setView(key, value || 'Customer name');
                    } else if (key === 'tin') {
                        setView(key, value ? 'TIN ' + value : '');
                    } else {
                        setView(key, value);
                    }
                });
                (views.notes || []).forEach(function (el) {
                    el.hidden = !el.textContent;
                });

                var items = readItems();
                renderItems(items);
                var amount = items.reduce(function (sum, item) {
                    return sum + item.amount;
                }, 0);
                var discount = Math.min(numberValue('inv_discount'), amount);
                var deposit = numberValue('inv_deposit');
                setView('subtotal', money(amount));
                setView('discount', discount ? '- ' + money(discount) : '0');
                setView('deposit', deposit ? '- ' + money(deposit) : '0');
                setView('balance', 'TZS ' + money(Math.max(0, amount - discount - deposit)));

                var due = dueInput._flatpickr && dueInput._flatpickr.selectedDates[0];
                setView('due', due ? due.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : dueInput.value);
            }

            if (typeof window.flatpickr === 'function') {
                window.flatpickr(dueInput, {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'D, j M Y',
                    disableMobile: true,
                    minDate: 'today',
                    onReady: function (selectedDates, dateStr, instance) {
                        instance.calendarContainer.classList.add('booking-flatpickr');
                        if (instance.altInput) {
                            instance.altInput.classList.add('form-control');
                        }
                    },
                    onChange: render
                });
            }

            form.addEventListener('input', render);
            document.getElementById('inv_add_item').addEventListener('click', function () {
                addItem(null, true);
            });
            itemList.addEventListener('click', function (event) {
                var remove = event.target.closest('.hire-item-remove');
                if (remove) {
                    remove.closest('[data-item]').remove();
                    render();
                }
            });
            document.getElementById('inv_print').addEventListener('click', function () {
                window.print();
            });

            (window.INVOICE_ITEMS || []).forEach(function (item) {
                addItem(item, false);
            });
            render();
        })();
    </script>

</body>

</html>
