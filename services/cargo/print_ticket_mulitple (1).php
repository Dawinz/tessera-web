
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<?php
$print_code= $_GET['id'];	



								include'connect.php';
			
										
$select=mysqli_query($db,"SELECT *  FROM parcel_info WHERE print_code='$print_code' ");
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
	 		$print_code=$get['print_code']; 
		  $weight_value=$get['weight_value'];
		  $weight_name=$get['weight_name'];
		  $lugg_value=$get['lugg_value'];
		
	     
						?>
											
											
												<?php
	$select1544=mysqli_query($db,"SELECT *  FROM  routes WHERE route_full_name='$trip'");
$select_num1544=mysqli_num_rows($select1544);
if($select_num1544 !=0){
   	WHILE($get13544=mysqli_fetch_array($select1544)){
 

 $start_route = $get13544['start_route']; 
 $end_route = $get13544['end_route']; 
  $contact_no1 = $get13544['contact_1']; 
 $contact_no2 = $get13544['contact_2'];  
 $address_1 = $get13544['address_1']; 
 $address_2 = $get13544['address_2'];
 $term_1_new1 = $get13544['term_1_new1']; 
 $term_2_new = $get13544['term_2_new']; 
 $currency_symbol = $get13544['currency_symbol']; 
 

}}	?>
											
											
												<?php
	if($status=="paid"){
		echo "
<style>

body {

  -webkit-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Safari 4+ */
  -moz-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Fx 5+ */
  -o-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Opera 12+ */
  animation: NAME-YOUR-ANIMATION 1s infinite;  /* IE 10+, Fx 29+ */
}

@-webkit-keyframes NAME-YOUR-ANIMATION {
  0%, 49% {
    background-color: #027011;
  
  }
  50%, 100% {
    background-color: #027011;

  }
}
</style>
		";
	}else if($status=="Due payment"){
		echo "
<style>

body {

  -webkit-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Safari 4+ */
  -moz-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Fx 5+ */
  -o-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Opera 12+ */
  animation: NAME-YOUR-ANIMATION 1s infinite;  /* IE 10+, Fx 29+ */
}

@-webkit-keyframes NAME-YOUR-ANIMATION {
  0%, 49% {
    background-color: #9c0312;
  
  }
  50%, 100% {
    background-color: #9c0312;

  }
}
</style>
		";
	}
	?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Print</title>
</head>
<body>
    <style>
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
    </style>
	    <button class="no-print" onclick="window.print();">Print via Browser</button>    <br> <br>

    <table style="border: none;font-size:14px;">
        <thead>
            <tr>
                <th style="border: none"></th>
                <th style="border: none">
                    <P>
					<div >
					<div style="font-size:25px;"><?php echo $code;?></div>
					</div>
					<img src="sr.jpg"width="100"height="100"/><br> Address : <?php echo $address_1?><br> Address : <?php echo $address_2?><br>	 Contact : <?php echo $contact_no1;?><br> Contact  : <?php echo $contact_no2?> <br>email: operations@srclassiccoach.co.tz</P>
                </th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th style="font-size:14px;"colspan=3><?php echo ucwords($trip)?></th>
           
            </tr>
        </thead>
        <tbody style="border-left: 1px solid black;border-right: 1px solid black;font-size:12px;">
            <tr  >
               
                <td class="la" ><strong>Sender's name &nbsp&nbsp&nbsp </strong> </td>
                <td class="la" ></td>
                <td class="la" style="font-size:16px;"><b><?php echo str_replace("_" , "'", strtoupper($s_name));?></b></td>
            </tr>
			
			<tr  >
               
                <td class="la" ><strong>Recivers's name &nbsp&nbsp&nbsp <strong> </td>
                <td class="la" ></td>
                <td class="la" style="font-size:16px;"><b><?php echo str_replace("_" , "'", strtoupper($r_name));  ?></b></td>
            </tr> 
            <tr>
                <td class="la"><strong>From town</strong></td> <td class="la"></td>
                <td class="la"style="font-size:16px;"><b><?php echo $trip?></b></td>
            </tr>
           <tr>
                <td class="la"><strong>Recivers's contacts</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:16px;"><b><?php echo $r_no?></b></td>
            </tr>  <tr>
                <td class="la"><strong>Sender's contacts</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:16px;"><b><?php echo  str_replace("_" , "'", strtoupper($s_no));?></b></td>
            </tr><tr>
                <td class="la"><strong>Quantity</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:18px;"><b><?php echo $quantity?><b></td>
            </tr>
			<tr>
                <td class="la"><strong>Weight</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:18px;"><b><?php echo "$weight_value$weight_name"?><b></td>
            </tr>			
			<tr>
                <td class="la"><strong>Luggage Value</strong></td>  <td class="la"></td>
                <td class="la"style="font-size:18px;"><b><?php echo number_format($lugg_value);?><b></td>
            </tr>
			<tr>
                <td class="la"><strong>Details</strong></td> <td class="la"></td>
                <td class="la"style="font-size:16px;"><b><?php echo str_replace("_" , "'", strtoupper($detail))?></b></td>
            </tr>
        </tbody>   <tbody style="border-top: 1px solid black;border-bottom:1px solid black">
            <tr  >
               
                <td class="la" colspan=2><strong>issued by &nbsp&nbsp&nbsp  <?php
			
				echo $uploader;
				?> </strong></td>
                <td class="la" style="border-left: 1px solid black;"> <strong><?php if($status=="Due payment"){
					echo"Total Ammount ";
				}else{
					echo"Cash Paid";
				}?> &nbsp&nbsp</strong><?php
				if($status == "To be Payed"){
					echo "0.00";
				}else{
				echo ' '.number_format($cost,2).''	;
				}
				
				
				?><br> <strong>To be Paid &nbsp&nbsp</strong><?php
				if($status == "To be Payed"){
						echo number_format($cost,2)	;
				}else if($status == "Due payment"){
								echo ''.number_format($cost - $due,2).''	;

				}else{
					echo "0,00";
				}
				
				
				?><strong><br> Total paid &nbsp&nbsp&nbsp </strong> <?php if($due==0){echo number_format($paid ,2);}else{echo number_format($due ,2);}?>
				           					<?php
	if($loan == 0){
		
	}else{
		?>
						<strong><br> Total Loan &nbsp&nbsp&nbsp </strong> <?php echo number_format($loan ,2)?>

		<?php
		
	}
	?>
				</td>
    
            </tr>
            <tr>
                <td class="la"colspan=2> <strong>issued on &nbsp&nbsp&nbsp <?php echo "". $date_insert = date("d-m-Y", strtotime($date_insert))  ." at $time"?></strong></td> 
                <td class="la" style="border-left: 1px solid black;border-top: 1px solid ;"><strong><?php
				if($status == "paid"){
					echo "Total Payed";
				}else if($status == "To be Payed"){
					echo "Total To be paid";
				}else if($status == "Due payment"){
					echo "Total To be paid";
				}
				?>  </strong> <?php  $new_cost = $cost - $due; echo '  '. number_format($new_cost + $loan ,2).'   '.$currency_symbol .' ';?></td>
            </tr>   
			
			<?php
if((($status == "To be Payed") OR ($status == "Due payment")) and ($trip=="Dar es salaam to Lubumbashi")) {
			?>
			<tr>
                <td class="la"colspan=2> </td> 
                <td class="la" style="border-left: 1px solid black;border-top: 1px solid ;"><strong><?php
		echo "Total to be paid in dollars ";
				?>  </strong> <?php  $new_cost = $cost - $due; $dollar_new_cost = $new_cost/2000; echo '$  '.number_format($dollar_new_cost,2).''?><span>1 dollar = 2000 Tsh</span></td>
            </tr>
           <?php
		   
			}
		   ?>		

		   <?php
if((($status == "To be Payed") OR ($status == "Due payment")) and ($trip=="Johanesburg to Dar es salaam")) {
			?>
			<tr>
                <td class="la"colspan=2> </td> 
                <td class="la" style="border-left: 1px solid black;border-top: 1px solid ;"><strong><?php
		echo "Total to be paid in Tsh ";
				?>  </strong> <?php  $new_cost = $cost - $due; $dollar_new_cost = $new_cost*170; echo '  '.number_format($dollar_new_cost,2).'/='?><br><span>1 rand = 170 Tshs</span></td>
            </tr>
           <?php
			}
			
		   ?>	 

		   <?php
if((($status == "To be Payed") OR ($status == "Due payment")) and ($trip=="Johanesburg to Lubumbashi")) {
			?>
			<tr>
                <td class="la"colspan=2> </td> 
                <td class="la" style="border-left: 1px solid black;border-top: 1px solid ;"><strong><?php
		echo "Total to be paid in Dollar ";
				?>  </strong> <?php  $new_cost = $cost - $due; $dollar_new_cost = $new_cost/13; echo '  '.number_format($dollar_new_cost,2).'/='?><br><span>1 dollar = 13 Rand</span></td>
            </tr>
           <?php
			}
			
		   ?>  
		   <?php
if((($status == "To be Payed") OR ($status == "Due payment")) and ($trip=="Lubumbashi to Johanesburg")) {
			?>
			<tr>
                <td class="la"colspan=2> </td> 
                <td class="la" style="border-left: 1px solid black;border-top: 1px solid ;"><strong><?php
		echo "Total to be paid in Rand ";
				?>  </strong> <?php  $new_cost = $cost - $due; $dollar_new_cost = $new_cost * 16; echo '  '.number_format($dollar_new_cost,2).'/='?><br><span>1 dollar = 16 Rand</span></td>
            </tr>
           <?php
			}
			
		   ?> 
		   <?php
if((($status == "To be Payed") OR ($status == "Due payment")) and ($trip=="Dar es salaam to Johanesburg")) {
			?>
			<tr>
                <td class="la"colspan=2> </td> 
                <td class="la" style="border-left: 1px solid black;border-top: 1px solid ;"><strong><?php
		echo "Total to be paid in Rand ";
				?>  </strong> <?php  $new_cost = $cost - $due; $dollar_new_cost = $new_cost / 140; echo '  '.number_format($dollar_new_cost,2).'/='?><br><span>1 dollar = 140 Rand</span></td>
            </tr>
           <?php
			}
			
		   ?>
        </tbody>
         <footer>
            <tr>
                 <th style="font-size:12px;"colspan=3>Terms and Condition<br>
		<?php
		if($status=="To be Payed"){
			?>
<?php
echo base64_decode($term_2_new);
?>			<?php
			
		}else{
			?>
<?php
echo base64_decode($term_1_new1);

?>	
			<?php
		}
		?>
		
				</th>
				
           
            </tr>
        </footer>

    </table>
	
    <br>

    <button class="no-print" onclick="window.print();">Print via Browser</button>    <br> <br>
	
    <br><br><hr class="no-print" ><br>
	
	<?php
}}
	?>
	
  
  
    <script src="js/vendor/jquery-1.12.4.min.js"></script>

    <script language="JavaScript">
window.addEventListener("afterprint", function(event) {
    setTimeout(function() {
        window.close();
    }, 1000); // Execute on the next tick
});

	window.onload = function () {
    window.print();
}


    </script>
</body>
</html>
