
<?php
require  __DIR__ . '/vendor/autoload.php';

use Pimple\Container;
use Mulopwe\Orange\SMS\OnlineSms;
use Mulopwe\Orange\SMS\Http\SMSClient;

$container = new Container();

$container['clientID'] = 'AsHA9HTcLQBRFAZmoBpwdAwkSlzoYuW3';
$container['clientSecret'] = 'eiVH6qkPf296gCcZ';

$smsClient = new SMSClient($container['clientID'], $container['clientSecret'], $container);

$onlineSms = new OnlineSms($smsClient);


$container = new Container();

$container['clientID'] = 'AsHA9HTcLQBRFAZmoBpwdAwkSlzoYuW3';
$container['clientSecret'] = 'eiVH6qkPf296gCcZ';

$smsClient = new SMSClient($container['clientID'], $container['clientSecret'], $container);

$onlineSms = new OnlineSms($smsClient);

include 'connect.php';
if (is_file(__DIR__ . '/includes/tessera_cargo_notify.php')) {
	require_once __DIR__ . '/includes/tessera_cargo_notify.php';
}
$button ='<strong><svg style="color:#b81004"xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
  <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
</svg></strong>';
$button=base64_encode($button);
$bus = $_POST['bus'];
$date = $_POST['date'];
$newDate = date("d-M-y", strtotime($date));  
$arv_date=date('d-M-y', strtotime($date. ' + 5

 days'));$arv_date=date('d-M-y', strtotime($date. ' + 5    days'));
$select135=mysqli_query($db,"SELECT *  FROM  manifest  WHERE trip_date='$date' and bus='$bus'");
$select_num135=mysqli_num_rows($select135);
if($select_num135 !=0){
   	WHILE($get1335=mysqli_fetch_array($select135)){
    	    $code=$get1335['code'];   
		

$rand =rand() ;

$select=mysqli_query($db,"SELECT *  FROM  parcel_info  WHERE code='$code' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	 $r_no=$get['r_no'];  
			$code=$get['code'];
			$account=$get['account'];  
			$r_name=$get['r_name'];   
			$s_name=$get['s_name'];
			if (function_exists('tessera_cargo_notify')) {
				$__recv = (string) $get['r_no'];
				tessera_cargo_notify(
					'cargo.arrived',
					$__recv,
					(string) $code,
					"Habari {$r_name}, mzigo {$code} umefika. Tafadhali pita ofisini na risiti."
				);
			}
			$r_no = substr($r_no, 1);
			$r_no_str = strlen($r_no);
			if($r_no_str ==12){
			
			$select1=mysqli_query($db,"SELECT *  FROM  routes where route_full_name='$account' ");
$select_num1=mysqli_num_rows($select1);
if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $start_route =$get13['start_route']; 
    	    $end_route =$get13['end_route']; 
					$route_full_name =$get13['route_full_name']; 
		

	}
}
			
			
		
	

		
switch ($route_full_name) {

  case "Dar es salaam to Lubumbashi":
  	
$number = "$r_no"; 
$message="Sr classic coach. Salut $r_name  votre colis numéroté $code est déjà arrivé, sil vous plaît passer à lagence avec la facture et la photo du colis. Merci.";



$onlineSms->from('243897701399') //sender number
          ->to(''.$r_no.'') //recipient number
          ->message(''.$message.'') //message
          ->send();




  
    break;

  case "Dar es salaam to Arusha":
    $number = "+$r_no"; 
$message="Habari *$r_name* Mzigo wako wenye nambari *$code* umefika *$end_route* Pita kwenye office zetu zilizoko Arusha kuuchukua mzigo wako.Karbu sana ";
$message = base64_encode($message);
 $insert=mysqli_query
 ($db,
 "INSERT INTO  load_sms  (code,no,sms,code_generated) VALUES 
('$code','$r_no','$message','$rand')");  
    break; 
	case "Nairobi to Dar es salaam":
    $number = "+$r_no"; 
$message="Habari *$r_name* Mzigo wako wenye nambari *$code* umefika *$end_route* Pita kwenye office zetu zilizoko Lumumba Ungoni,Dar es salaam kuuchukua mzigo wako.Karbu sana ";
$message = base64_encode($message);
 $insert=mysqli_query
 ($db,
 "INSERT INTO  load_sms  (code,no,sms,code_generated) VALUES 
('$code','$r_no','$message','$rand')");  
    break;
  default:
    echo "";
}

			}else{
			
			
			}
			
	
	}

}else{
	
}

		   $insert=mysqli_query($db,"UPDATE vehicle_name SET img='$button',disable='disabled' WHERE date='$date' and Name='$bus'");
	?>

	<?php
	}
}
?>