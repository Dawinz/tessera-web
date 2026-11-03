<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
include 'modules/connection/connect.php';
include 'modules/read/general/Read.php';
include 'connect.php';
$username_check = $_SESSION['username'];

$firstname = str_replace("'", "_", (string) ($_POST["firstname"] ?? ''));
$scode = (string) ($_POST["scode"] ?? '');
$s_no = (string) ($_POST["s_no"] ?? '');
$s_no_plain = $s_no;
$sender_no = (string) ($_POST["sender_no"] ?? ($scode . $s_no));
$Figure = '';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_insert = date('Y-m-d', time());
$month = date('m-Y', time());
$date_time_insert = date('h:i A', time());
$rand = rand(1, 9);
$sort_month = date('Ym', time());
$recipt_array = [];
$error = "";
$print_time = ''.date('d-m-Y', time()).' at '.date('H:i', time()).'';
if (isset($_SESSION['username']) && $_SESSION['username'] !== '') {
	// Session username exists

	$select = ['first_point,second_point,value'];
	$where = [];
	$currency_values = new Read(['select' => $select, 'pdo' => $pdo, 'where' => $where, 'table' => "currency_value", 'order' => "", 'limit' => ""]);
	$currency_value_info = $currency_values->rows;

	$select2 = mysqli_query($db, "SELECT firstname,surname,username  FROM  users  where username='$username_check'");
	$select_num2 = mysqli_num_rows($select2);
	if ($select_num2 != 0) {
		while ($get12 = mysqli_fetch_array($select2)) {
			$firstname_user = $get12['firstname'];
			$surname_user = $get12['surname'];
			$username_checked = $get12['username'];

			$fullname = "".ucfirst($firstname_user)." ".ucfirst($surname_user)."";
			if ($username_checked == "Nassir") {
				$firstStringCharacter = "N";
			} else {
				$firstStringCharacter = substr($firstname_user, 0, 1);

				$firstStringCharacter = strtoupper($firstStringCharacter);
			}


		}
	}

	$n = 15;
	$print_id = bin2hex(random_bytes($n));
	$print_id = "$print_id$date_time_insert";
	if (isset($_POST["firstname"])) {
		$order_id = uniqid();

		for ($count = 0; $count < count($_POST["r_firstname"]); $count++) {
			$r_firstname = str_replace("'", "_", $_POST["r_firstname"]);
			$r_firstname = $r_firstname[$count];
			$r_no = $_POST["r_no"][$count];
			$r_no_plain = $_POST["r_no"][$count];
			$route = $_POST["routs"][$count];
			$status_em = $_POST["status_em"][$count];
			$amount = $_POST["amount"][$count];
			$quantity = $_POST["quantity"][$count];
			$detail = $_POST["detail"][$count];
			$weight_name = $_POST["weight_name"][$count];
			$weight_value = $_POST["weight_value"][$count];
			$luggage_value = $_POST["Value"][$count];
			$rcode = $_POST["rcode"][$count];
			$weight_value = preg_replace('/[^0-9.]+/', '', $weight_value);
			$detail = str_replace("'", "_", $detail);
			$detail = base64_encode($detail);
			$paid_amount = 0;
			$to_be_amount = 0;

			$r_no = "$rcode$r_no";
			$sender_no = "$scode$s_no";


			if ($status_em == "Due payment") {
				$amount_paid = $_POST["amount_left"][$count];
				$amount_paid = preg_replace('/[^0-9.]+/', '', $amount_paid);
				$due_total = $amount - $amount_paid;
			} else {
				$amount_paid = 0;
				$due_total = 0;

			}

			$quantity = preg_replace('/[^0-9.]+/', '', $quantity);

			$amount = preg_replace('/[^0-9.]+/', '', $amount);
			$luggage_value = preg_replace('/[^0-9.]+/', '', $luggage_value);

			$select12 = mysqli_query($db, "SELECT initial,currency,currency_symbol,to_be_paid_currency,contact_1,contact_2,address_1,address_2  FROM  routes WHERE route_full_name='$route'");
			$select_num12 = mysqli_num_rows($select12);
			if ($select_num12 != 0) {
				while ($get132 = mysqli_fetch_array($select12)) {
					$initial = $get132['initial'];
					$currency = $get132['currency'];
					$currency_symbol = $get132['currency_symbol'];
					$to_be_paid_currency = $get132['to_be_paid_currency'];
					  $contact_no1 = $get132['contact_1']; 
 $contact_no2 = $get132['contact_2'];  
 $address_1 = $get132['address_1']; 
 $address_2 = $get132['address_2'];
				}
			}


			$select1 = mysqli_query($db, "SELECT code   FROM  code  WHERE location='$route' ");
			$select_num1 = mysqli_num_rows($select1);
			if ($select_num1 != 0) {
				while ($get13 = mysqli_fetch_array($select1)) {

					$code = $get13['code'];
					$code = $code + 1;

					$code_plus = "$initial$code$firstStringCharacter";
					$insert = mysqli_query($db, "UPDATE code SET code='$code' WHERE location='$route'");


				}
			} else {
				$start_no = 1;
				$code_plus = "$initial$start_no$firstStringCharacter";
				$insert = mysqli_query($db, "INSERT INTO code (code,location) VALUES
                ('1','$route')");

			}






			$select1 = mysqli_query($db, "SELECT id FROM parcel_info WHERE code='$code_plus' and account='$route' LIMIT 1");
			$select_num1 = $select1 ? mysqli_num_rows($select1) : 0;
			if ($select_num1 != 0) {
				$error = 'equal';
			} else {
				$option_choose = $status_em;
				$paid_val = 0;
				$to_be_val = 0;
				$due_val = 0;
				$answer_val = 0;
				switch ($option_choose) {
					case 'paid':
						$paid_amount = $amount;
						$paid_val = $amount;
						$to_be_val = 0;
						$due_val = 0;
						$answer_val = 0;
						break;
					case 'To be Payed':
						$to_be_amount = $amount;
						$paid_val = 0;
						$to_be_val = $amount;
						$due_val = 0;
						$answer_val = $amount_paid;
						break;
					case 'Due payment':
						$to_be_amount = $due_total;
						$paid_amount = $amount_paid;
						$paid_val = $amount_paid;
						$to_be_val = $due_total;
						$due_val = $amount_paid;
						$answer_val = $due_total;
						break;
					default:
						$error = 'invalid payment status';
						break;
				}

				if ($error === '') {
					// STRICT SQL: supply every NOT NULL column (open, due, remains, printed_time, etc.)
					$q = (string) ($quantity !== '' ? $quantity : '1');
					$amt = (string) ($amount !== '' ? $amount : '0');
					$lag = (string) ($luggage_value !== '' ? $luggage_value : '0');
					$wval = (string) ($weight_value !== '' ? $weight_value : '0');
					$sessUser = mysqli_real_escape_string($db, (string) $_SESSION['username']);
					$fn = mysqli_real_escape_string($db, (string) $firstname);
					$rfn = mysqli_real_escape_string($db, (string) $r_firstname);
					$snd = mysqli_real_escape_string($db, (string) $sender_no);
					$rno = mysqli_real_escape_string($db, (string) $r_no);
					$rte = mysqli_real_escape_string($db, (string) $route);
					$det = mysqli_real_escape_string($db, (string) $detail);
					$st = mysqli_real_escape_string($db, (string) $status_em);
					$codeEsc = mysqli_real_escape_string($db, (string) $code_plus);
					$di = mysqli_real_escape_string($db, (string) $date_insert);
					$tm = mysqli_real_escape_string($db, (string) $date_time_insert);
					$up = mysqli_real_escape_string($db, (string) $Figure);
					$mo = mysqli_real_escape_string($db, (string) $month);
					$sym = mysqli_real_escape_string($db, (string) $currency_symbol);
					$cty = mysqli_real_escape_string($db, (string) $currency);
					$pc = mysqli_real_escape_string($db, (string) $print_id);
					$wn = mysqli_real_escape_string($db, (string) $weight_name);

					$insert = mysqli_query(
						$db,
						"INSERT INTO parcel_info (
							s_name, s_surname, s_no, r_name, r_no, account, detail, status, amount, code,
							rec_status, question, store, answer, scanned, date_insert, time, uploader, username,
							paid, to_be, due, month, initial, country, cleared, print_code, payment_status,
							open, weight_name, weight_value, lugg_value, paid_remain, to_be_remain,
							quantity_remain, amount_remain, printed_time, edited
						) VALUES (
							'$fn','0','$snd','$rfn','$rno','$rte','$det','$st','$amt','$codeEsc',
							'No','$q','store','$answer_val','$q','$di','$tm','$up','$sessUser',
							'$paid_val','$to_be_val','$due_val','$mo','$sym','$cty','Not paid','$pc','$paid_val',
							'open','$wn','$wval','$lag','$paid_val','$to_be_val',
							'$q','0','0','no'
						)"
					);

					if (!$insert) {
						$error = 'insert failed: ' . mysqli_error($db);
					}
				}
			}


			if ($error === '') {
			$option_choose = $status_em;

			switch ($option_choose) {
				case "paid":
					store_charges($username_check, $status_em, $amount, $amount_paid, $due_total, $route);
					break;
				case "To be Payed":
					store_charges($username_check, $status_em, $amount, $amount_paid, $due_total, $route);
					break;
				case "Due payment":
					store_charges($username_check, $status_em, $amount, $amount_paid, $due_total, $route);
					break;
				default:
					break;
			}

			include 'update_contact.php';
			($weight_value == "") ? $weight_value="0" : "";
			$display_to_be = visiblity ($status_em,$to_be_paid_currency ?? '');
			$to_be_paid_value = to_be_paid_exchange($status_em,$to_be_amount,$currency_symbol,$to_be_paid_currency ?? '',$currency_value_info);
			$payment_status_r = status($status_em);
			$recipt_array[] = [
				'code' => $code_plus,
				'route_name' => $route,
				's_no' => $sender_no,
				'r_no' => $r_no,
				's_name' => $firstname,
				'r_name' => $r_firstname,
				'detail' => $detail,
				'quantity' => $quantity,
				'status' => $payment_status_r,
				'paid' => $paid_amount,
				'to_be' => $to_be_amount,
				'weight' => $weight_value,
				'metric' => $weight_name,
				'currency_symbol' => $currency_symbol,
				'username' => $Figure,
				'print_time' => $print_time,
				'to_be_paid_value' => round($to_be_paid_value,2),
				'to_be_paid_currency' => $to_be_paid_currency ?? '',
				'lag_Value' => $luggage_value,
				'address_1' => $address_1 ?? '',
				'address_2' => $address_2 ?? '',
				'contact_no1' => $contact_no1 ?? '',
				'contact_no2' => $contact_no2 ?? '',
				'visibility' => $display_to_be,
			];
			// Receipt images must never fail the parcel insert.
			try {
				if (is_file(__DIR__ . '/qrcode/vendor/autoload.php') && extension_loaded('gd')) {
					include 'qrcode/qrcode.php';
				}
			} catch (Throwable $e) {
				// ignore QR generation failures
			}
			try {
				if (is_file(__DIR__ . '/barcode/vendor/autoload.php')) {
					include 'barcode/barcode.php';
				}
			} catch (Throwable $e) {
				// ignore barcode generation failures
			}
			}
		}




	}
} else {
	$error = "Session Expired";
}


function to_be_paid_exchange($status_em,$value,$currency_symbol,$to_be_paid_currency,$currency_values){



  $exchange_rate = 1;
  foreach ($currency_values as $row) {
    $first_point = $row["first_point"];
    $second_point = $row["second_point"];
    if (($second_point == $to_be_paid_currency) AND ($first_point == $currency_symbol)) {
      $exchange_rate = $row["value"];
    }
  }
  $total_value = $value * $exchange_rate;

return $total_value;


}

function store_charges($username_check, $status_em, $amount, $amount_paid, $due_total, $route)
{
	include 'connect.php';
	$option_choose = $status_em;
	switch ($option_choose) {
		case "paid":
			include 'insert_paid.inc.php';

			break;
		case "To be Payed":

			include 'insert_to_be.inc.php';


			break;
		case "Due payment":


			include 'insert_due.inc.php';
			break;
		default:
			echo "Your favorite color is neither red, blue, nor green!";
	}


}
function visiblity ($status_em,$to_be_paid_currency){
	$display="";
	if(($status_em !="paid") AND ($to_be_paid_currency !="")){
		
	}else{
		$display ="display:none";
	}
	return $display;
}
function status($status){
if($status =="To be Payed"){
$status = "To be Paid";
}else if($status =="paid"){
$status = "Paid";	
}
return $status;
}

$boolean_response = ($error == "") ? true : false;

if ($boolean_response && is_file(__DIR__ . '/includes/tessera_cargo_notify.php')) {
	require_once __DIR__ . '/includes/tessera_cargo_notify.php';
	$__phone = (string) ($s_no_plain ?? $s_no ?? '');
	$__code = (string) ($code_plus ?? '');
	$__name = trim((string) ($_POST['firstname'] ?? ''));
	if ($__phone !== '' && $__code !== '') {
		tessera_cargo_notify(
			'cargo.registered',
			$__phone,
			$__code,
			"Habari {$__name}, mzigo wako {$__code} umesajiliwa."
		);
	}
}

echo json_encode([
	'success' => $boolean_response,
	'message' => $error,
	'data' => $recipt_array
]);

exit;