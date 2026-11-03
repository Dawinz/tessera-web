<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - POS Receipt Template Html Css</title>
  <link rel="stylesheet" href="./pos.css">

</head>
<body>
<!-- partial:index.partial.html -->
<div id="invoice-POS">
    
    <center id="top">
      <div class="logo"></div>
      <div class="info"> 
        <h2>Sr classic coach</h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->

    				<?php
					include'connect.php';
$id = $_GET['code'];
$select1=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$id'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $s_name=$get13['s_name']; 
			$s_surname=$get13['s_surname'];
			$s_no=$get13['s_no'];
			$r_no=$get13['r_no']; 
			$r_name=$get13['r_name'];   
            $r_surname=$get13['r_surname'];   
		 $amount=$get13['amount'];
		  $detail=$get13['detail'];
		  $code=$get13['code'];
		
	}
}
	?>
    <div id="mid">
      <div class="info">
        <h2>Contact Info</h2>
        <p> 
            Address : street city, state 0000</br>
            Phone   : 555-555-5555</br>
			    code  : <?php echo $code;?></br>
        </p>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="bot">

					<div id="table">
						<table>
							<tr class="tabletitle">
								<td class="item"><h2>Item</h2></td>
								
								<td class="Rate"><h2>Sub Total</h2></td>
							</tr>

							<tr class="service">
								<td class="tableitem"><p class="itemtext"><?php echo base64_decode($detail);?></p></td>
								
								<td class="tableitem"><p class="itemtext"><?php echo $amount;?></p></td>
							</tr>

							

							
						



							<tr class="tabletitle">
								<td></td>
								<td class="Rate"><h2>Total</h2></td>
								<td class="payment"><h2><?php echo number_format($amount,2);?></h2></td>
							</tr>

						</table>
					</div><!--End Table-->

					<div id="legalcopy">
						<p class="legal"><strong>Thank you for your business!</strong> 
					</div>

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->
<!-- partial -->
  
</body>
</html>
