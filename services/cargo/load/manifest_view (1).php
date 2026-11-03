               <?php

use BcMath\Number;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
   include'../connect.php';
   $trip_date = $_POST['trip_date'];            
   $vehicle = $_POST['vehicle'];            
   $start_date = $_POST['start_date'];            
   $end_date = $_POST['end_date'];        
   $route_name = $_POST['route_name'];        
   $status = $_POST['status_luggage'];        
   $type = $_POST['type'];        
   $no = 0;
 
if($start_date!=""){
    $start_date = "AND trip_date>='$start_date'  ";
}
if($end_date!=""){
    $end_date = "AND trip_date<='$end_date'  ";
}
if($route_name!=""){
    $route_name = "AND parcel_route='$route_name'  ";
}
if($status!=""){
    $status = "AND status='$status'  ";
}
if($type=="transit"){
    $type = "AND transit='$type'  ";
}else if ($type=="Direct"){
$type = "AND transit=''  ";
}else{
     $type="";  
}



               ?>
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
	) row_num,parcel_date,parcel_route,weight_name,weight_value,status,code,username,status,checked,detail,loan,paid,to_be,code,quantity,q_left,trip_date,amount,quantity_insert,transit
FROM 
	manifest    WHERE trip_date ='$trip_date' and bus = '$vehicle' $route_name $type  $status $start_date $end_date
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
 $transit=$get['transit']; 

 $transit = ($transit != "") ? $transit='<i class="bx bx-redo" style="color:red;"></i>' : $transit="";
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
				<?php echo $transit; echo $code;?>
				</td>
				<td>
				<?php echo $username;?>
				</td>
                				<td>
				<?php sender_no($code,$db);?>
				</td>
                				<td>

				<?php  reciver_no($code,$db);?>
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
				<?php if($paid=="0"){echo "-";}else{echo number_format($paid,2);}?>
				</td>
				<td>
				<?php   if($amount=="0"){echo "-";}else{echo number_format($amount,2);}?>
				</td>
				<td>
				<?php echo "$q_left/$quantity";?>
				</td>	
				<td>
				<?php echo "$quantity_insert";?>
				</td>	
				<td>
				<?php echo date("jS M Y", strtotime($parcel_date));?>
				</td>
				</tr>
<?php
}}
$sum_to_be_total=0;
$sum_paid_total=0;

$select=mysqli_query($db,"SELECT parcel_route  FROM  manifest    WHERE trip_date ='$trip_date' and bus = '$vehicle' group by parcel_route ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
$parcel_route =$get['parcel_route'];
$sum_to_be=0;
$sum_paid=0;
$sum_loan=0;
$select_route=mysqli_query($db,"SELECT  paid,to_be,loan FROM  manifest    WHERE trip_date ='$trip_date' and bus = '$vehicle' and parcel_route='$parcel_route'  ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
$paid =$get_route['paid'];
$to_be =$get_route['to_be'];
$loan =$get_route['loan'];
$sum_to_be += $to_be;	
$sum_paid += $paid;
$sum_loan += $loan;


	
}}
$sum_to_be_total+=$sum_to_be;
$sum_paid_total+=$sum_paid ;
?>
<tr style="border-top:2px solid black;">
    <td></td>
    <td><?php echo $parcel_route;?></td>
    <td>To be paid</td> 
    <td><?php echo number_format($sum_to_be)?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<tr>
    <td></td>
    <td></td>
    <td>Paid</td>
    <td><?php echo number_format($sum_paid);?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr>
    <td></td>
    <td></td>
    <td>Amount</td>
    <td><?php echo number_format($sum_paid+$sum_to_be);?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
<tr style="border-bottom:1px solid black;">
    <td></td>
    <td></td>
    <td>Recipts</td>
    <td><?php echo $select_num_route;?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>

<?php

    }}
				
				?>
			<tr >
    <td></td>
    <td></td>
    <td>Total To be paid</td>
    <td><?php echo number_format($sum_to_be_total)?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>		
			<tr >
    <td></td>
    <td></td>
    <td>Total paid</td>
    <td><?php echo number_format($sum_paid_total)?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>		
			<tr >
    <td></td>
    <td></td>
    <td>Total Amount</td>
    <td><?php echo number_format($sum_paid_total+$sum_to_be_total)?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>		
			
                    
			<tr >
    <td></td>
    <td></td>
    <td>Recipts</td>
    <td><?php echo number_format($no)?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>		
                    

							  
				</tbody>
				
				
	
                                          <!-- end tbody --> 
                                            </table>

<script>
         $('#empTable').DataTable({
			
				"order": [],
				"lengthMenu": [[300, 325, 450, -1], [300, 325, 450, "All"]],
                   dom: 'Bfrtip',
				   buttons: [
                { extend: 'copyHtml5', footer: true,className: 'red'},
            { extend: 'excelHtml5', footer: true,className: 'red' },
            { extend: 'csvHtml5', footer: true,className: 'red' },
			{ extend: 'print', 
			className: 'red',
			 autoPrint: true
              
                 }],
            });
   
</script>