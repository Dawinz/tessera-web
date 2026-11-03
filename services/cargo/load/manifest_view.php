<style>
  @media print {
    @page {
      size: portrait;
    }
  }

  .names-cell {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 6px;
  }

  .total_td {
    border-top: 2px solid grey;
    border-bottom: 4px solid black;
  }

  .name-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #d1d5db;
    background: #ffffff;
    color: #111827;
    transition: background 0.15s, transform 0.1s;
    user-select: none;
  }

  .name-chip:hover {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #93c5fd;
  }

  input[type="checkbox"]:checked+.name-chip {
    background: #eff6ff;
    color: #1d4ed8;
    border-color: #93c5fd;
  }

  .name-chip:active {
    transform: scale(0.96);
  }

  .avatar {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    font-weight: 600;
  }

  .avatar-s {
    background: #dbeafe;
    color: #1e40af;
  }

  .avatar-ad {
    background: #ede9fe;
    color: #5b21b6;
  }

  .avatar-a {
    background: #d1fae5;
    color: #065f46;
  }

  .avatar-m {
    background: #fce7f3;
    color: #9d174d;
  }

  .avatar-k {
    background: #fef3c7;
    color: #92400e;
  }

  .black-border {
    border-top: 1.5px solid grey !important;
    border-bottom: 1.5px solid grey !important;
  }

  .last-row {
    border-bottom: none;
  }

  #user_table td,
  #user_table th {
    border: none !important;
    border-bottom: 1px solid #dee2e6 !important;
    /* keep bootstrap grey */
  }

  .total_currency {
    background: #edf2ef;
  }

  .dataTables_wrapper .dt-buttons {
    margin-bottom: 12px;
  }

  .dataTables_wrapper .dt-button {
    background: #12324d !important;
    color: #ffffff !important;
    border: 0 !important;
    border-radius: 5px !important;
    padding: 7px 14px !important;
    margin-right: 6px !important;
    font-size: 13px !important;
  }

  .dataTables_wrapper .dataTables_filter input {
    border: 1px solid #dce1e7;
    border-radius: 5px;
    padding: 6px 10px;
    margin-left: 8px;
  }

  #empTable tr.hidden {
    display: none !important;
  }

  #total_summation {
    border-top: 2px solid grey;

  }
</style>


<?php
$logoPath = $_SERVER['DOCUMENT_ROOT'] . '/cargo/pdf/srlogo.jpg';
$mime = mime_content_type($logoPath);
$logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));

?>
<?php

use BcMath\Number;
$title = '';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../connect.php';
include '../config2.php';
include '../modal/manifest/user_manifest_summary.php';
include '../modules/connection/connect.php';
include '../modules/read/general/Read.php';
include '../modules/update/general/Update.php';

$trip_date = $_POST['trip_date'];
$vehicle = $_POST['vehicle'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$route_name = $_POST['route_name'];
$status = $_POST['status_luggage'];
$type = $_POST['type'];
$username_session = $_POST['username'];
$row_remark = $_POST['row_remark'];
$remark = base64_decode($_POST['remark']);
$route_origin = base64_decode($_POST['route_origin']);
$routes = [];
$values = [];
$users = [];
$parcel_users = [];
$user_values = [];
$user_and_routes = [];
$no = 0;

if ($start_date != "") {
  $start_date = "AND trip_date>='$start_date'  ";
}
if ($end_date != "") {
  $end_date = "AND trip_date<='$end_date'  ";
}
if ($route_name != "") {
  $route_name = "AND parcel_route='$route_name'  ";
}
if ($status != "") {
  $status = "AND status='$status'  ";
}
if ($type == "transit") {
  $type = "AND transit='$type'  ";
} else if ($type == "Direct") {
  $type = "AND transit=''  ";
} else {
  $type = "";
}

$select = ['first_point,second_point,value'];
$where = [];
$currency_values = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "currency_value", 'order' => "", 'limit' => ""]);


?>

<div class="panel export_panel" style="display:none">
  <div class="panel-title">Export fields</div>

  <div class="export-columns">
    <label><input type="checkbox" class="export-col" value="0" checked> No</label>
    <label><input type="checkbox" class="export-col" value="1" checked> Code</label>
    <label><input type="checkbox" class="export-col" value="2" checked> Route</label>
    <label><input type="checkbox" class="export-col" value="3" checked> Name of Owner</label>
    <label><input type="checkbox" class="export-col" value="4"> Sender No</label>
    <label><input type="checkbox" class="export-col" value="5" checked> Receiver No</label>
    <label><input type="checkbox" class="export-col" value="6" checked> Weight</label>
    <label><input type="checkbox" class="export-col" value="7" checked> Status</label>
    <label><input type="checkbox" class="export-col" value="8" checked> Description</label>
    <label><input type="checkbox" class="export-col" value="9" checked> Quantity</label>
    <label><input type="checkbox" class="export-col" value="10"> Charged Date</label>
    <label><input type="checkbox" class="export-col" value="11" checked> To Be Paid</label>
    <label><input type="checkbox" class="export-col" value="12" checked> Paid</label>
    <label><input type="checkbox" class="export-col" value="13" checked> Total Amount</label>
  </div>


</div>


<div class="panel export_panel" style="display:none">
  <div class="panel-title">Export Rows</div>
  <label><input type="checkbox" class="export-col_row" value="to_be_paid" checked> Charges Ammount</label>

</div>

<table class="table mb-0" id="empTable">
  <thead class="table-light">
    <tr>


      <th style="">No</th>
      <th style="width:320px;">Route</th>
      <th>Code</th>
      <th>Owner</th>
      <th>Sender no</th>
      <th>Receiver no</th>
      <th>Weight</th>
      <th style="width:150px;">status</th>
      <th style="width:300px;">Description</th>
      <th style="width:250px;">Quantity</th>
      <th>Charged Date</th>
      <th>To Be Paid</th>
      <th>Paid</th>
      <th>Total Ammount</th>


    </tr>
  </thead>
  <tbody>
    <?php


    $select = mysqli_query($db, "SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id desc 
	) row_num,parcel_date,parcel_route,weight_name,parcel_uploader,weight_value,status,code,username,status,checked,detail,loan,paid,to_be,code,quantity,q_left,trip_date,amount,quantity_insert,transit,sender_no,reciever_no
FROM 
	manifest    WHERE trip_date ='$trip_date' and bus = '$vehicle' $route_name $type  $status $start_date $end_date
		");
    $select_num = mysqli_num_rows($select);
    if ($select_num != 0) {
      while ($get = mysqli_fetch_array($select)) {
        $no = $get['row_num'];
        $username = $get['username'];
        $amount = $get['amount'];
        $detail = $get['detail'];
        $to_be = $get['to_be'];
        $paid = $get['paid'];
        $loan = $get['loan'];
        $code = $get['code'];
        $q_left = $get['q_left'];
        $quantity = $get['quantity'];
        $parcel_route = $get['parcel_route'];
        $status = $get['status'];
        $checked = $get['checked'];
        $parcel_date = $get['parcel_date'];
        $weight_name = $get['weight_name'];
        $weight_value = $get['weight_value'];
        $quantity_insert = $get['quantity_insert'];
        $transit = $get['transit'];
        $sender_no = $get['sender_no'];
        $reciever_no = $get['reciever_no'];
        $parcel_uploader = $get['parcel_uploader'];
        $transit = ($transit != "") ? $transit = '<i class="bx bx-redo" style="color:red;"></i>' : $transit = "";
        $quanity_display = ($quantity == $quantity_insert) ? $quanity_display = $quantity_insert : $quanity_display = "$quantity_insert out of $quantity";
        $status = ($status == "paid") ? $status = "Paid" : $status = $status;
        $status = ($status == "To be Payed") ? $status = "To be Paid" : $status = $status;

        $values[] = [
          "route_name" => $parcel_route,
          "paid" => $paid,
          "to_be" => $to_be,
          "code" => $code,
          "q_left" => $q_left,
          "quantity" => $quantity,
          "parcel_route" => $parcel_route,
          "status" => $status,
          "checked" => $checked,
          "parcel_date" => $parcel_date,
          "weight_name" => $weight_name,
          "quantity_insert" => $quantity_insert,
          "transit" => $transit,
          "sender_no" => $sender_no,
          "reciever_no" => $reciever_no,
          "parcel_uploader" => $parcel_uploader,
          "username" => $username,
          "weight_value" => $weight_value,
          "detail" => $detail,
          "quanity_display" => $quanity_display,
          "amount" => $amount,

        ];
        $user_values[] = [
          "route_name" => $parcel_route,
          "username" => $parcel_uploader,
          "paid" => $paid,
          "to_be" => $to_be
        ];


        if (in_array($parcel_route, $routes)) {
        } else {
          $routes[] = $parcel_route;
        }
        if (in_array($parcel_uploader, $parcel_users)) {
        } else {
          $parcel_users[] = $parcel_uploader;
        }



        $exists = array_filter($users, function ($user) use ($parcel_uploader, $parcel_route) {
          return $user['username'] === $parcel_uploader && $user['route_name'] === $parcel_route;
        });

        if (empty($exists)) {
          $users[] = [
            'username' => $parcel_uploader,
            'route_name' => $parcel_route,

          ];
        }



        ?>

        <?php
      }
    }
    ///////////Total rows
    ?>

    <?php
    $total_luggages = 0;
    foreach ($routes as $route_name_each) {
      ?>

      <?php

      $luggage_no = 0;
      foreach ($values as $get) {

        $status = $get['status'];



        if ($route_name_each == $get['route_name']) {
          $luggage_no++;
          $total_luggages++;
          ?>
          <tr <?php if ($status == "Paid") {
            echo "style='background-color:#99ff9c'";
          } else if ($status == "Due payment") {
            echo "style='background-color:#ffe8f0'";
          }
          if ($checked == "out") {
            echo "class='stock'";
          } ?>>
            <td style="background:white;"><?php echo $total_luggages; ?></td>
            <td style="background:white;">
              <strong><?php echo ($luggage_no == 1) ? strtoupper($get['parcel_route']) : ""; ?></strong>
            </td>
            <td><strong><?php echo $get['transit'];
            echo $get['code']; ?></strong></td>
            <td><?php echo $get['username']; ?></td>
            <td><?php echo $get['sender_no']; ?></td>
            <td><?php echo $get['reciever_no']; ?></td>
            <td><?php echo "" . $get['weight_value'] . "" . $get['weight_name'] . ""; ?></td>
            <td><?php echo $status; ?></td>
            <td><?php echo base64_decode($get['detail']); ?></td>
            <td><?php echo $get['quanity_display']; ?></td>
            <td><?php echo date("jS M Y", strtotime($get['parcel_date'])) ?></td>
            <td><?php echo ($get['to_be'] == 0) ? "-" : number_format($get['to_be']); ?></td>
            <td><?php echo ($get['paid'] == 0) ? "-" : number_format($get['paid']); ?></td>
            <td><?php echo ($get['amount'] == 0) ? "-" : number_format($get['amount']); ?></td>
          </tr>

          <?php

        }

      }
    }
    ?>

    <tr class="summation">
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td><strong>Agent Names</strong></td>
      <td><strong>Route</strong></td>
      <td><strong>Currency</strong></td>
      <td><strong>To be paid</strong></td>
      <td><strong>Paid</strong></td>
      <td><strong>Total</strong></td>


    </tr>
    <?php
    $route_currency = [];
    foreach ($routes as $route_name) {
      $total_to_be_paid = 0;
      $total_paid = 0;
      //currency 
      $stmt = $conn->prepare("SELECT currency_symbol from routes where route_full_name ='$route_name'");
      $stmt->execute();
      $currency = $stmt->fetch(PDO::FETCH_ASSOC);
      $currency_symbol = $currency['currency_symbol'];

      foreach ($values as $row) {
        // echo" ".$route_name." ". $row["route_name"] . "<br>";
    
        if ($route_name == $row["route_name"]) {
          $total_to_be_paid += $row["to_be"];
          $total_paid += $row["paid"];
        }
      }

      $route_currency[] = [
        "route_name" => $route_name,
        "currency" => $currency_symbol,

      ];

      ?>
      <tr class="summation">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>


        </td>
        <td></td>
        <td>
          <strong>Net Total</strong>
        </td>
        <td>
          <strong><?php echo $route_name; ?></strong>
        </td>

        <td>
          <h6><span class="badge bg-success "><?php echo $currency_symbol; ?></span></h6>
        </td>
        <td><strong><?php echo " " . number_format($total_to_be_paid) . " " ?></strong></td>
        <td><strong><?php echo " " . number_format($total_paid) . " " ?></strong></td>
        <td><strong><?php echo " " . number_format($total_paid + $total_to_be_paid) . " " ?></strong></td>


      </tr>
      <?php


    }
    $default_currency = default_currency($route_currency, $route_origin);

    include 'include/user_sum.php';

    ?>
    <!-- end tbody -->
    <tr class="summation">
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td class=""></td>
      <td id="total_summation" class="">
        <div class="names-cell">
          <label class="name-chip user_charge_all" for="All Users"><span class="avatar avatar-s"><i
                class="fas fa-receipt"></i></span> Users Summary</label>
        </div>
      </td>
      <td id="total_summation">
        <strong>All Routes</strong>
      </td>

      <td id="total_summation">
        <h6><span class="badge bg-success "><?php echo $default_currency; ?></span></h6>
      </td>
      <td id="total_summation"><strong><?php echo number_format($grand_to_be); ?></strong></td>
      <td id="total_summation"><strong><?php echo number_format($grand_paid); ?></strong></td>
      <td id="total_summation"><strong><?php echo number_format($grand_total_amount); ?></strong></td>


    </tr>

  </tbody>



  <!-- end tbody -->
</table>

<?php

$data = ['to_pay' => $grand_to_be, 'paid' => $grand_paid, 'due' => $grand_total_amount];
$where = ["date" => $trip_date,"Name" => $vehicle];
$update_manifest = new Update($pdo, $data, 'vehicle_name', $where);
?>
<div id="remarks" style="border:1px solid black;width:800px;">
  <div style="padding:5px 0px 0px 10px;">
    <span>Remark:</span>
    <div id="html_remark">
      <?= $remark ?>
    </div>

  </div>
</div>
<?php

function default_currency($route_currency, $route_name)
{
  $this_route_curreny = "";
  $currency = "";
  foreach ($route_currency as $row) {
    $route_name_select = $row["route_name"];
    $currency = $row["currency"];

    if ($route_name == $route_name_select) {
      $this_route_curreny = $currency;
    }

  }
  return $this_route_curreny;
}
function users_array($users, $route_name, $user_values)
{
  $names = '<div class="names-cell">';

  foreach ($users as $row) {
    $user_to_be = 0;
    $user_paid = 0;
    $user_to_paye_no = 0;
    $user_pay_no = 0;

    if ($route_name == $row["route_name"]) {

      foreach ($user_values as $row_val) {
        $username = $row_val["username"];
        $route_name_user = $row_val["route_name"];

        if (($route_name === $route_name_user) && ($username === $row["username"])) {
          $user_to_be += $row_val["to_be"];
          $user_paid += $row_val["paid"];

          ($row_val["to_be"] != 0) ? $user_to_paye_no += 1 : "";
          ($row_val["paid"] != 0) ? $user_pay_no += 1 : "";
        }

      }

      $names .= '<label class="name-chip user_charge_name" for="' . $row["username"] . '" data-username  = "' . $row["username"] . '"  data-to_be = "' . $user_to_be . '" data-paid="' . $user_paid . '" data-route_name="' . $route_name . '" data-to_be_no = "' . $user_to_paye_no . '" data-paid_no = "' . $user_pay_no . '"><span class="avatar avatar-s"><i class="fas fa-receipt"></i></span>' . $row["username"] . '</label>';

    }
  }
  $names .= '</div>';
  return $names;
}
?>
<div style="display:none;" id="user_table_function">
  <?php echo user_html_table($routes, $grand_total_org, $route_origin, $parcel_users, $currency_total) ?>
</div>
<script src="js/modal/manifest/user_charge.js"></script>

<script>
  $(document).ready(function () {
    <?php if ($row_remark == "yes") {
      ?>
      scrollToDiv();
      <?php
    } ?>
    function scrollToDiv() {
      const element = document.getElementById("remarks");
      element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    function getSelectedColumns() {
      return $('.export-col:checked').map(function () {
        return parseInt(this.value, 10);
      }).get();
    }

    function exportVisibleRowsOnly(idx, data, node) {
      return !$(node).hasClass('hidden') && $(node).is(':visible');
    }

    $('#empTable').DataTable({
      order: [],
      lengthMenu: [[300, 325, 450, -1], [300, 325, 450, "All"]],
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'copyHtml5',
          footer: true,
          className: 'red',
          exportOptions: {
            rows: exportVisibleRowsOnly,
            columns: function (idx) {
              return getSelectedColumns().includes(idx);
            }
          }
        },
        {
          extend: 'excelHtml5',
          footer: true,
          className: 'red',

          exportOptions: {
            rows: exportVisibleRowsOnly,
            columns: function (idx) {
              return getSelectedColumns().includes(idx);
            }
          }
        },
        {
          extend: 'csvHtml5',
          footer: true,
          className: 'red',
          exportOptions: {
            rows: exportVisibleRowsOnly,
            columns: function (idx) {
              return getSelectedColumns().includes(idx);
            }

          }
        },

        {
          extend: 'pdfHtml5',
          footer: true,
          orientation: 'landscape',
          pageSize: 'A4',
          title: '',
          filename: 'Cargo <?php echo $vehicle; ?>-<?php echo $trip_date; ?>',
          exportOptions: {
            rows: exportVisibleRowsOnly,
            columns: function (idx) {
              return getSelectedColumns().includes(idx);
            }
          },
          customize: function (doc) {

            // ── Config ────────────────────────────────────────────────
            
            var logo = '<?php echo $logoBase64; ?>';
            var companyName = 'Sr Classic Coach';
            var address = 'Lind & Iring,Ilala,Dar es salaam,Tanzania';
            var contact = 'Phone: +255 (682) 756-6699   |   Email: classiccoachcoltd@gmail.com';
            var website = 'www.srclassiccoach.co.tz';
            var vehicle = '<?php echo $vehicle; ?>';
            var tripDate = '<?php echo $trip_date; ?>';
            var printedBy = '<?php echo htmlspecialchars($_POST["username"]) ?? "System"; ?>';
            var printedAt = '<?php echo date("d M Y  h:i A"); ?>';

            var brandColor = '#1a3c6e';   // dark blue - change to your brand
            var accentColor = '#e8edf5';   // light blue tint for alt rows / header bg

            // ── Header ────────────────────────────────────────────────
            doc.content.unshift({
              margin: [0, 0, 0, 8],
              stack: [

                // Logo + Company block
                {
                  columns: [
                    {
                      image: logo,
                      width: 75,
                      margin: [0, 2, 12, 0]
                    },
                    {
                      stack: [
                        { text: companyName, bold: true, fontSize: 18, color: brandColor, margin: [0, 0, 0, 3] },
                        { text: address, fontSize: 8, color: '#444' },
                        { text: contact, fontSize: 8, color: '#444' },
                        { text: website, fontSize: 8, color: '#444', italics: true }
                      ]
                    }
                  ]
                },

                // Thick brand divider
                {
                  canvas: [{ type: 'line', x1: 0, y1: 0, x2: 820, y2: 0, lineWidth: 2, lineColor: brandColor }],
                  margin: [0, 8, 0, 6]
                },

                // Report title
                {
                  text: 'TRIP MANIFEST REPORT',
                  alignment: 'center',
                  bold: true,
                  fontSize: 12,
                  color: brandColor,
                  margin: [0, 0, 0, 6]
                },

                // Thin divider
                {
                  canvas: [{ type: 'line', x1: 0, y1: 0, x2: 820, y2: 0, lineWidth: 0.5, lineColor: '#cccccc' }],
                  margin: [0, 0, 0, 6]
                },

                // Trip meta - two columns
                {
                  columns: [
                    {
                      stack: [
                        { text: [{ text: 'Vehicle:  ', bold: true, color: brandColor }, vehicle], fontSize: 9 },
                        { text: [{ text: 'Trip Date: ', bold: true, color: brandColor }, tripDate], fontSize: 9 }
                      ]
                    },
                    {
                      stack: [
                        { text: [{ text: 'Printed By: ', bold: true, color: brandColor }, printedBy], fontSize: 9, alignment: 'right' },
                        { text: [{ text: 'Printed At: ', bold: true, color: brandColor }, printedAt], fontSize: 9, alignment: 'right' }
                      ]
                    }
                  ],
                  margin: [0, 0, 0, 6]
                },

                // Final thin divider before table
                {
                  canvas: [{ type: 'line', x1: 0, y1: 0, x2: 820, y2: 0, lineWidth: 0.5, lineColor: '#cccccc' }],
                  margin: [0, 0, 0, 4]
                }
              ]
            });

            // ── Table styling ─────────────────────────────────────────
            doc.defaultStyle.fontSize = 7;
            doc.styles.tableHeader = {
              bold: true,
              fontSize: 8,
              color: '#ffffff',
              fillColor: brandColor,
              alignment: 'center'
            };
            doc.pageMargins = [10, 10, 10, 20];

            var tableNode = doc.content.find(n => n.table);
            if (tableNode) {
             
  var colCount = tableNode.table.body[0].length;
  tableNode.table.widths = Array(colCount).fill('*');
  tableNode.table.widths[0] = 25; // reduce ID column width
  tableNode.table.widths[1] = 125; // reduce ID column width

              // Zebra-stripe body rows
              tableNode.table.body.forEach(function (row, i) {
                if (i === 0) return; // skip header
                row.forEach(function (cell) {
                  if (typeof cell === 'object') {
                    cell.fillColor = i % 2 === 0 ? accentColor : '#ffffff';
                  }
                });
              });

              tableNode.layout = {
                hLineWidth: () => 0.4,
                vLineWidth: () => 0.4,
                hLineColor: () => '#cccccc',
                vLineColor: () => '#cccccc',
                paddingLeft: () => 3,
                paddingRight: () => 3,
                paddingTop: () => 3,
                paddingBottom: () => 3
              };
            }

            // ── Footer: page numbers ──────────────────────────────────
            doc.footer = function (currentPage, pageCount) {
              return {
                columns: [
                  { text: companyName + ' - Confidential', fontSize: 7, color: '#888', margin: [10, 0, 0, 0] },
                  { text: 'Page ' + currentPage + ' of ' + pageCount, fontSize: 7, color: '#888', alignment: 'right', margin: [0, 0, 10, 0] }
                ],
                margin: [0, 4, 0, 0]
              };
            };

          }
        }
      ]
    });

    $(".export-col_row").click(function () {

      if ($(".summation").hasClass("hidden")) {

        $(".summation").removeClass("hidden");


      } else {

        $(".summation").addClass("hidden");
      }

    })

  });

</script>