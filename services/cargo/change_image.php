<?php
$image= "DL1887876";	
$image_name = rand();
								include'connect.php';
			
										
										$select=mysqli_query($db,"SELECT *  FROM parcel_info WHERE code='$image' ");
$select_num=mysqli_num_rows($select);

if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $quantity=$get['question'];
			$date=$get['date']; 
			$trip=$get['account'];
			$s_no=$get['s_no']; 
			$s_name=$get['s_name'];
			$s_surname=$get['s_surname'];
			$r_name=$get['r_name'];
			$r_surname=$get['r_surname']; 	
			$r_no=$get['r_no'];
			$cost=$get['amount'];
			$due=$get['due']; 	
			$date_insert=$get['date_insert']; 
			$quantity=$get['question']; 
			$detail=$get['detail']; 
			$detail=base64_decode($detail);
			$detail2=$get['detail']; 
			$code = $get['code'];
		$status=$get['status']; 
		$time=$get['time']; 
		$uploader=$get['uploader']; 
		
		$username=$get['username']; 
		$bus=$get['bus'];
		$trip_date=$get['trip_date']; 
		$paid=$get['paid']; 
		$to_be=$get['to_be']; 
		$month=$get['month']; 
		
		
		$payment_status=$get['payment_status']; 
		$rec_status=$get['rec_status'];
		$answer=$get['answer']; 
		$store=$get['store']; 
		$edited=$get['edited']; 
		$scanned=$get['scanned']; 
     $amount_remain=$get['amount_remain']; 
	 		$loan=$get['loan']; 

	     
	}
}
 $s_name =  strtoupper($s_name) ;									
 $r_name =  strtoupper($r_name) ;									
$paid_final = number_format($paid,2);
$to_be_final = number_format($to_be,2);
$cost_final = number_format($cost,2);

											?>
											
											
											
<?php
function generateRandomString($length = 10) {
   $randomString = sha1(uniqid(rand(), true));
   $randomString = substr($randomString, 0, $length);

   return $randomString;
}
// Generate a random string of length 10
$randomString = generateRandomString(10);



?>											
											
<?php
// (A) CONTENT
// (A1) HTML
$html = <<<HTML
<img src="/cargo/images/sr.jpg"width="80" height="120"/>
<table>
     <tbody style="border: 1px solid black;border-right: 1px solid black;font-size:12px;">
            <tr  >
               
                <td class="la" ><strong>Code &nbsp&nbsp&nbsp </strong> </td>
                <td class="la" ></td>
                <td class="la" style="font-size:16px;"><b>$code</b></td>
            </tr>  <tr  >
               
                <td class="la" ><strong>Sender's name &nbsp&nbsp&nbsp </strong> </td>
                <td class="la" ></td>
                <td class="la" style="font-size:16px;"><b>$s_name</b></td>
            </tr>
			
			<tr  >
               
                <td class="la" ><strong>Recivers's name &nbsp&nbsp&nbsp <strong> </td>
                <td class="la" ></td>
                <td class="la" style="font-size:16px;"><b>$r_name</b></td>
            </tr> 
            <tr>
                <td class="la"><strong>From town</strong></td> <td class="la"></td>
                <td class="la"style="font-size:16px;"><b> $trip</b></td>
            </tr>
           <tr>
                <td class="la"><strong>Recivers's contacts</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:16px;"><b> $r_no</b></td>
            </tr>  <tr>
                <td class="la"><strong>Sender's contacts</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:16px;"><b> $s_no</b></td>
            </tr><tr>
                <td class="la"><strong>Quantity</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:18px;"><b>$quantity<b></td>
            </tr>
			<tr>
                <td class="la"><strong>Details</strong></td> <td class="la"></td>
                <td class="la"style="font-size:16px;"><b> $detail</b></td>
            </tr>
			<tr>
                <td class="la"><strong>To pay</strong></td> <td class="la"></td>
                <td class="la"style="font-size:16px;"><b> $to_be_final</b></td>
            </tr>
			<tr>
                <td class="la"><strong>Payed</strong></td> <td class="la"></td>
                <td class="la"style="font-size:16px;"><b> $paid_final</b></td>
            </tr>
			<tr>
                <td class="la"><strong>Totla Ammout</strong></td> <td class="la"></td>
                <td class="la"style="font-size:16px;"><b> $cost_final</b></td>
            </tr>
			
			
        </tbody>

</table>
Sr classic coach. Salut $r_name ,<br> votre colis numéroté $code est déjà arrivé,<br> s’il vous plaît passer à l’agence avec la facture et la photo du colis.<br> Nous sommes sur l’avenue kabalo coins sandoa en face avec l’école madini. Merci.

HTML;

// (A2) CSS
$css = <<<CSS
  body {
            text-align: center;
			
        }

        table {
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
        }

        table td,
        th {
            border: 1px solid black;
            padding: 3px;
        }

        .ra {
            text-align: right;
			border-left: none;
        }

        .la {
            text-align: left;
			border: none;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }
			.tr{
				border-left-style: dashed;
			}
        }
CSS;

// (B) USE API TO CREATE PNG
// (B1) CURL INIT
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://hcti.io/v1/image");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
  "html" => $html,
  "css" => $css
]));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, "6c1b137c-bdbf-4b5e-9e9f-fae76165a4cb" . ":" . "7706a251-071b-41e6-b8d3-9f52299aa26f"); // CHANGE TO YOUR OWN!
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);

// (B2) CURL EXEC
$result = curl_exec($ch);
if (curl_errno($ch)) { echo curl_error($ch); }
curl_close($ch);
$result = json_decode($result, true);

// (C) FETCH & SAVE PNG
$fh = fopen("ticket_image/$randomString.png", "w");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $result["url"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FILE, $fh);
curl_exec($ch);
curl_close($ch);
fclose($fh);
echo "OK";

$params=array(
'token' => 'cec0sgo4e85a6grm',
'to' => '+255715511420',
'image' => '/cargo/ticket_image/'.$randomString.'.png',
'caption' => ''
);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ultramsg.com/instance49345/messages/image",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => http_build_query($params),
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

?>
