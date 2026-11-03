<?php
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
include 'page_info_reload.php';
$vehicle = $_GET['vehicle'];
$transit = (isset($_GET['transit'])) ? $transit = $_GET['transit'] : $transit = "";

?>

<?php
$id = $_GET['code'];
$select2 = mysqli_query($db, "SELECT *  FROM parcel_info where code = '$id' and store='out' and scanned='0'");
$select_num2 = mysqli_num_rows($select2);

if ($select_num2 != 0) {

}
$select1 = mysqli_query($db, "SELECT *  FROM parcel_info where code = '$id'");
$select_num1 = mysqli_num_rows($select1);

if ($select_num1 != 0) {
  while ($get13 = mysqli_fetch_array($select1)) {
    $s_name = $get13['s_name'];
    $s_surname = $get13['s_surname'];
    $s_no = $get13['s_no'];
    $r_no = $get13['r_no'];
    $r_name = $get13['r_name'];
    $r_surname = $get13['r_surname'];
    $trip_name = $get13['account'];
    $detail = $get13['detail'];
    $code = $get13['code'];
    $due = $get13['due'];
    $scanned = $get13['scanned'];
    $cost = $get13['amount'];
    $s_q = $get13['scanned'];
    $quantity = $get13['question'];
    $amount_remain = $get13['amount_remain'];
    $status = $get13['status'];
    $paid = $get13['paid'];
    $to_be = $get13['to_be'];

    $due_to_pay = $get13['answer'];
    $due_paid_s = $get13['payment_status'];
    $paid_remain = $get13['paid_remain'];
    $to_be_remain = $get13['to_be_remain'];

  }
}
?>


<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8" />
  <title>Check Manifest</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Tessera" name="author" />
  <!-- App favicon -->
  <link rel="shortcut icon" href="assets/images/favicon.ico">

  <!-- Bootstrap Css -->
  <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>
<style>

</style>

<body>

  <!-- <body data-layout="horizontal"> -->

  <div class="authentication-bg min-vh-100">
    <div class="bg-overlay bg-white"></div>
    <div class="container">
      <div class="d-flex flex-column min-vh-100 px-3 pt-4">
        <div class="row justify-content-center my-auto">
          <div class="col-xxl-4 col-xl-6" style="width:60%; overflow-y: hidden; ">
            <div class="card">
              <div class="card-header justify-content-between d-flex align-items-center">
                <h4 class="card-title" id="status_check"><?php echo $status; ?></h4>
                <a href="check_luggages.php?id=<?php echo $vehicle; ?>&date=<?php echo $_GET['date']; ?>&open=open"
                  class="btn btn-sm btn-soft-secondary">Back <i class="mdi mdi-arrow-right align-middle"></i></a>
              </div><!-- end card header -->
              <div class="card-body">

                <h3 style="display:none;"><br>
                  <br>LEFT amount : <span id="amount_remain_new"><?php
                  if ($status == "To be Payed") {
                    if ($amount_remain == 0) {
                      echo $to_be;
                    } else {
                      echo $amount_remain;
                    }

                  } else if ($status == "Due payment") {
                    if ($amount_remain == 0) {
                      echo $to_be;
                    } else {
                      echo $amount_remain;
                    }
                  } else if ($status == "paid") {
                    if ($amount_remain == 0) {
                      echo $paid;
                    } else {
                      echo "$amount_remain";
                    }

                  }

                  ?></span>
                </h3>
                <div class="table-responsive">
                  <table class="table mb-0">

                    <tbody>




                      <tr>
                      <tr>
                        <td>Code</td>
                        <td id="code"><strong><?php echo $code ?></strong></td>
                      </tr>
                      <td>Sender's Name</td>
                      <td><?php echo $s_name; ?></td>
                      </tr>
                      <tr>
                        <td>Reciver's Name</td>
                        <td><?php echo $r_name; ?></td>
                      </tr>
                      <tr>
                        <td>Trip</td>
                        <td><?php echo $trip_name; ?></td>
                      </tr>
                      <tr>
                        <td>Quantity</td>
                        <td><?php echo $quantity; ?></td>
                      </tr>

                      <tr>
                        <td>Ammount</td>
                        <td id="code"><?php echo number_format($cost) ?></td>
                      </tr>
                      <tr>
                        <td>To Be Payed</td>
                        <td id="code"><?php echo number_format($to_be) ?></td>
                      </tr>
                      <tr>
                        <td>Paid</td>
                        <td id="code"><?php echo number_format($paid) ?></td>
                      </tr>



                      <tr>
                        <td>Detail</td>
                        <td><?php echo base64_decode($detail) ?></td>
                      </tr>


                    </tbody>


                  </table>
                  <br>

                  <?php
                  if ($quantity != 0) {
                    ?>

                    <h5><span style="color:red"> <?php echo $scanned; ?></span>, Luggages are in the store</h5>

                    <div class="row">



                      <div class="mb-3">
                        <div class="nk-int-st">
                          <input type="text" class="form-control" id="qty" placeholder="QUANTITY"
                            value="<?php quantity_function($quantity, $scanned, $transit) ?>"
                            pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autofocus>
                        </div>
                      </div>

                      <div class="mb-3" <?php if ($to_be == 0) {
                        echo "style='display:none;'";
                      } ?>>
                        <div class="nk-int-st">
                          <input type="text" class="form-control" id="to_be" placeholder="To be paid"
                            value="<?php tobe_function($to_be_remain, $transit); ?>" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                            data-type="currency">
                        </div>
                      </div>

                      <div class="mb-3" <?php if ($paid == 0) {
                        echo "style='display:none;'";
                      } ?>>
                        <div class="nk-int-st">
                          <input type="text" class="form-control" id="paid" placeholder="Payed"
                            value="<?php paid_function($paid_remain, $transit); ?>"
                            pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency">
                        </div>
                      </div>




                    </div>
                    <div id="vehicle_name" style="display:none;"><?php echo $vehicle; ?></div>
                    <div id="code_no" style="display:none;"><?php echo $code; ?></div>
                    <div id="trip_date" style="display:none;"><?php echo $_GET['date']; ?></div>
                    <div id="transit" style="display:none;"><?php echo $transit; ?></div>
                    <?php
                  } else {

                  }


                  function quantity_function($quantity, $scanned, $transit)
                  {
                    if (($quantity == 1) OR ($scanned == "1")) {
                      echo "1";
                    } else if ($transit == "transit") {
                      echo "0";
                    }
                  }
                  function tobe_function($to_be_remain, $transit)
                  {
                    if ($transit == "transit") {
                      echo "0";
                    } else {
                      echo $to_be_remain;
                    }
                  }

                  function paid_function($paid_remain, $transit)
                  {
                    if ($transit == "transit") {
                      echo "0";
                    } else {
                      echo $paid_remain;
                    }
                  }
                  ?>

                  <br>

                  <div class="col-md-6">
                    <button id="send" class="btn btn-info w-100">Post</button>
                    <br> <br>
                  </div>
                </div><!-- end table responsive -->
              </div><!-- end card body -->
            </div><!-- end card -->
          </div>
        </div><!-- end row -->


      </div>
    </div><!-- end container -->
  </div>
  <!-- end authentication section -->

  <!-- JAVASCRIPT -->
  <script src="js/vendor/jquery-1.12.4.min.js"></script>

  <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
  <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
  <script src="assets/libs/simplebar/simplebar.min.js"></script>
  <script src="assets/libs/feather-icons/feather.min.js"></script>
  <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
  <!-- notification init -->
  <script src="assets/js/pages/notification.init.js"></script>
  <script src="js/sweetalert.js"></script>
  <script src="save.js?<?php echo htmlspecialchars(modified_time('save.js'), ENT_QUOTES, 'UTF-8'); ?>"></script>
  <script src="currency.js"></script>
  <script src="log_out_user.js"></script>
</body>

</html>
<script>

  $(document).ready(function () {
    $(function () { $('#number').focus(); });
  });
</script>