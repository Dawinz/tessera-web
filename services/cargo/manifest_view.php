                     <table class="table mb-0" id="empTable">
           <thead class="table-light">
           <tr>
                   
					
					<th style="">No</th>
					<th style="">Route</th>
					<th>Code</th>
					<th>Name of Owner</th>
					<th>Sender no</th>
					<th>Receiver no</th>
					<th>Weight</th>          
					<th>Description of Goods</th>          
					<th>status</th>          
					<th>To Be Paid</th>
					<th>Paid</th>
					<th>loan</th>
					<th>Total Ammount</th>					
					  <th>Exported Quantity</th>
					  <th>Quantity Left</th>
					   <th>Charged Date</th>
				
					  
                </tr>
                </thead>
				<tbody>
				<?php
				function sender_no ($code,$db){

$select=mysqli_query($db,"SELECT s_no  FROM  parcel_info WHERE code='$code' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $s_no =$get['s_no'];

    }}
echo $s_no;

}
function reciver_no ($code,$db){

$select=mysqli_query($db,"SELECT s_no  FROM  parcel_info WHERE code='$code' ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $s_no =$get['s_no'];

    }}
echo $s_no;

}

								$select=mysqli_query($db,"SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id desc 
	) row_num,parcel_date,parcel_route,weight_name,weight_value,status,code,username,status,checked,detail,loan,paid,to_be,code,quantity,q_left,trip_date,amount,quantity_insert
FROM 
	manifest    WHERE trip_date ='$date1' and bus = '$date3' $man_date
		");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
WHILE($get=mysqli_fetch_array($select)){
$no =$get['row_num'];
$username =$get['username'];
$amount =$get['amount'];
$detail =$get['detail'];
$to_be =$get['to_be'];
$paid =$get['paid'];
$loan =$get['loan'];
$code =$get['code'];
$q_left =$get['q_left'];
$quantity =$get['quantity'];
$parcel_route =$get['parcel_route'];
$status =$get['status'];
$checked =$get['checked'];
$parcel_date =$get['parcel_date'];
  $weight_name=$get['weight_name']; 
 $weight_value=$get['weight_value']; 
 $quantity_insert=$get['quantity_insert']; 
?>
<tr
<?php
if($status=="paid"){
	echo"style='background-color:#99ff9c'";
}else if($status=="Due payment"){
		echo"style='background-color:#ffe8f0'";
}

if($checked=="out"){
	echo"class='stock'";
}

?>>
				<td>
				<?php echo "$no";?>
				</td>
				<td>
				<?php echo $parcel_route ;?>
				</td>
				<td>
				<?php echo $code;?>
				</td>
				<td>
				<?php echo $username;?>
				</td>
                				<td>
				<?php sender_no($code,$db);?>
				</td>
                				<td>
				<?php reciver_no($code,$db);?>
				</td>
                <td>
				<?php echo "$weight_value$weight_name";?>
				</td>
				<td>
				<?php echo  base64_decode($detail);?>
				</td><td>
				<?php echo  ($status);?>
				</td><td>
				<?php if($to_be=="0"){echo "-";}else{echo number_format($to_be,2);}?>
				</td><td>
				<?php if($paid==""){echo "-";}else{echo number_format($paid,2);}?>
				</td><td>
				<?php if($loan=="0"){echo "-";}else{echo "<span style='color:red;'>".number_format($loan,2)."</span>";}?>
				</td>
				<td>
				<?php echo number_format($amount,2);?>
				</td>
				<td>
				<?php echo "$q_left/$quantity";?>
				</td>	
				<td>
				<?php echo "$quantity_insert";?>
				</td>	
				<td>
				<?php echo date("d-m-Y", strtotime($parcel_date));?>
				</td>
				</tr>
<?php
}}
				
				?>
					  <?php
$to_be_array=array();
$paid_array=array();	
$loan_array=array();	
			
$select=mysqli_query($db,"SELECT parcel_route  FROM  manifest    WHERE trip_date ='$date1' and bus = '$date3' group by parcel_route ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
$parcel_route =$get['parcel_route'];
$sum_to_be=0;
$sum_paid=0;
$sum_loan=0;

$select_route=mysqli_query($db,"SELECT  paid,to_be,loan FROM  manifest    WHERE trip_date ='$date1' and bus = '$date3' and parcel_route='$parcel_route' $man_date ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
$paid =$get_route['paid'];
$to_be =$get_route['to_be'];
$loan =$get_route['loan'];
$sum_to_be += $to_be;	
$sum_paid += $paid;
$sum_loan += $loan;

$sum_to_be_total=0;
$sum_paid_total=0;
	
}}

  ?>
  <tr style="font-weight:bold;">
 <td></td>
  <td>
  <?php
  echo $parcel_route ;
  ?>
  </td>
  
  <td>To be paid</td>
  <td><?php echo  number_format($sum_to_be,2);?></td>
  <td>Paid</td>
  <td><?php echo  number_format($sum_paid,2);?></td>
   <td>Loan</td>
  <td style="color:red;"><?php echo number_format($sum_loan,2)?></td>  
  <td>Total Amount</td>
  <td><?php echo number_format($sum_to_be+$sum_paid+$sum_loan,2);?></td>
 
  <td></td>  
  <td></td>  
  <td></td>  
  <td></td>  
  <td></td>  
  <td></td>  
  </tr>  

  <?php
array_push($paid_array, $sum_paid);
array_push($to_be_array, $sum_to_be);
array_push($loan_array, $sum_loan);
	
		}
}

?>
  <tr style="font-weight:bold;">
 <td></td>
  <td>
  Net Total
  </td>
  
  <td></td>
  <td><?php 
  	$to_be_total = array_sum($to_be_array);
echo number_format($to_be_total,2);
  ?></td>
  <td></td>
  <td><?php 
  	$paid_total = array_sum($paid_array);
				echo number_format($paid_total,2);
  ?></td>
  <td></td>
  <td style="color:red;"><?php 
  	$loan_total = array_sum($loan_array);
				echo number_format($loan_total,2);
  ?></td>
  <td></td>
  <td><?php echo number_format($to_be_total+$paid_total+$loan_total,2);?></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  
  </tr>
<?php
							  
							  ?>
							  
				</tbody>
				
				
	
                                          <!-- end tbody --> 
                                            </table>