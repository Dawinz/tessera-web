<?php
include '../connect/connect.php';
$month = $_POST['month'];
$route_name = $_POST['route'];
$year = $_POST['year'];

$bus_array = [ ]; 




?>
<style>
#change_color_red{
	
	color:red;
}
#change_color_grey{
	
	color:#7987A1;
}
.button_round_danger {
  background-color: #DC3545; /* Green */
  border: none;
  color: white;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}.button_round_success {
  background-color: #138496; /* Green */
  border: none;
  color: white;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>
  
	<div class="btn-group check_buttons" style="position: absolute;z-index: 10;margin-left:230px;margin-top:10px;display:non;">
              <button class="btn btn-danger check_buttons" id="delete_all" style="display:none;"><span id="delete_text">Delete</span> <i class="fa fa-trash" aria-hidden="true"></i></button>
             
            </div>
    <table style="margin-top:7px;"id="order-listing" class="table" cellspacing="0" width="100%">
                        <thead>
                          <tr class="bg-primary text-white">
                            
                          
                            <th>DATE</th>
                            <th>BUS</th>
					      <th>ITEM</th>
                            <th>QUANTITY</th>
                            <th>PRICE</th>
				<th >Detail</th>
                            <th>Date insert</th>
                            <th>Username</th>
                            <th></th>
                            
                          </tr>
                        </thead>
                        <tbody>
						
								<?php
								
								//echo "$month $year $route_name";
								$total_monthly_sum=array();
			$total_expenses_monthly =0;	
			$select_open=mysqli_query($db,"SELECT 
			ROW_NUMBER() OVER (
		order by id asc
	) row_num,id,date
			
			FROM   job_card   where route_name ='$route_name' and month ='$month' and year = '$year'  ");
$select_num_open=mysqli_num_rows($select_open);
if($select_num_open !=0){
   	WHILE($get_open=mysqli_fetch_array($select_open)){
    	    $first_row =$get_open['row_num']; 
    	    $date =$get_open['date'];
			$total_expenses =0;	

$select_paticular=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num,date,vehicle FROM job_card_vehicle  WHERE date='$date' and route_name='$route_name' and month ='$month' and year = '$year' ");
$select_num_paticular=mysqli_num_rows($select_paticular);
if($select_num_paticular !=0){
   	WHILE($get_paticular=mysqli_fetch_array($select_paticular)){
    	    $row_num_sec =$get_paticular['row_num'];
    	
    	    $bus_detail =$get_paticular['vehicle'];
    	    $date_detail =$get_paticular['date'];
			
			
			$select_paticular_vehicle=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id desc) row_num,route_name,date,bus,item,qty,price,date_insert,time_insert,detail,username,id FROM job_card_entries  WHERE date='$date_detail' and bus='$bus_detail' and route_name='$route_name' and month ='$month' and year = '$year' ");
$select_num_paticular_vehicle=mysqli_num_rows($select_paticular_vehicle);
if($select_num_paticular_vehicle !=0){
   	WHILE($get_paticular_vehicle=mysqli_fetch_array($select_paticular_vehicle)){
    	    $row_num =$get_paticular_vehicle['row_num'];
    	    $item =$get_paticular_vehicle['item'];
    	    $id =$get_paticular_vehicle['id'];
    	    $bus =$get_paticular_vehicle['bus'];
    	    $date =$get_paticular_vehicle['date'];
    	    $qty =$get_paticular_vehicle['qty'];
    	    $price =$get_paticular_vehicle['price'];
    	    $date_insert =$get_paticular_vehicle['date_insert'];
    	    $username =$get_paticular_vehicle['username'];
    	    $detail =$get_paticular_vehicle['detail'];
    	    $route_name =$get_paticular_vehicle['route_name'];
			$total_expenses +=$price;
			
			$bus_array[$bus] = $price; 

	
/*
foreach ($bus_array as $keyName => $valueName) { 
    echo  $keyName . " => " . $valueName . "\n" ; 
	echo "$first_row";
} 
*/
?>

		<tr>

<td> <span style="color:grey;font-weight:bold;"><?php  if(($row_num==1)and ($row_num_sec==1)){echo $date;}else{echo "-";}?></span></td>
<td> <?php echo $bus;?></td>
<td> <?php echo $item;?></td>
<td> <?php echo $qty;?></td>
<td> <?php echo number_format($price,2);?></td>
<td> <?php echo $detail;?></td>
<td> <?php echo $date_insert;?></td>
<td> <?php echo $username;?></td>
<td> <div class="btn-group">
<button style="margin:2px;"class="button_round_success edit<?php echo $id;?>" id="" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="fa fa-pencil" aria-hidden="true"></i></button>
<button style="margin:2px;"class=" button_round_danger" id="delete<?php echo $id;?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
      
 </div></td>	
			
			</tr>
			
			<script>
$(".edit<?php echo $id;?>").click(function(){


$("#qty_update").val("<?php echo $qty;?>");
$("#amount_update").val("<?php echo $price;?>");
$("#detail").val("<?php echo $detail;?>");
$("#vehicle_choose").text("<?php echo $bus;?>");
$("#item_update_ch").text("<?php echo $item;?>");
$("#Vehicle_update").val("<?php echo $bus;?>");
$("#item_update").val("<?php echo $item;?>");
$("#id_no").val("<?php echo $id;?>");
$("#date_update_text").text("<?php echo $date;?>");
$("#date_update_text").val("<?php echo $date;?>");

});


</script>
		<script>
		year = $(".currency_year").val();
route = $("#current_route").val();
month = $("#month").text();
			$("#delete<?php echo $id;?>").click(function(){
       var id= "<?php echo $id;?>";
	   Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {

id_jobcard="<?php echo $id;?>";
route_name="<?php echo $route_name;?>";
	date="<?php echo $date;?>";

    $.ajax({
    url:"./delete/delete_entry_jobcard.php",
    method:"POST",
    data:{id:id},
	   beforeSend: function(){
		   $("#delete<?php echo $id;?>").text("Deleting...");
            },
    success:function(data){
		alert(data);

    $.ajax(
                            {
                                url: 'search/job_card_details.php',
                                method: 'POST',
                                data: {
                                  
								   route:route,
								   month:month,
								   year:year
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
									
									  $("#placeholder_veh").removeClass("placeholder");

					$("#load_vehicle_account").html(data);

                                },
                                dataType: 'text'
                            }
                        );


    }
   })


  }
});

			});
			</script>	
<?php
}}



}}	
$select_paticular_sum=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num,date,vehicle FROM job_card_vehicle  WHERE date='$date' and route_name='$route_name' and month ='$month' and year = '$year' ");
$select_num_paticular_sum=mysqli_num_rows($select_paticular_sum);
if($select_num_paticular_sum !=0){
   	WHILE($get_paticular_sum=mysqli_fetch_array($select_paticular_sum)){
    	    //$row_num_sec =$get_paticular['row_num'];
    	
    	    $bus_detail_sum =$get_paticular_sum['vehicle'];
    	    $date_detail_sum =$get_paticular_sum['date'];
			
		$bus_total = 0;
			$select_paticular_vehicle_sum=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id desc) row_num,route_name,date,bus,item,qty,price,date_insert,time_insert,detail,username,id FROM job_card_entries  WHERE date='$date_detail_sum' and bus='$bus_detail_sum' and route_name='$route_name' and month ='$month' and year = '$year' ");
$select_num_paticular_vehicle_sum=mysqli_num_rows($select_paticular_vehicle_sum);
if($select_num_paticular_vehicle_sum !=0){
   	WHILE($get_paticular_vehicle_sum=mysqli_fetch_array($select_paticular_vehicle_sum)){
    	   
    	    $price =$get_paticular_vehicle_sum['price'];
			$bus_total += $price;
			
}}
			
			
		?>
		
	<tr style="border-top:1px solid black;">
<td style="color:grey;"><strong><?php echo $bus_detail_sum;?></strong></td>
<td  style="color:grey;"><strong><?php echo number_format($bus_total);?></strong></td>

<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> <td>

<td>
 </div></td>	
			
			</tr>
<?php
}}
?>
<tr style="border-top:1px solid black;">
<td ><strong>Total</strong></td>
<td><strong><?php echo number_format($total_expenses)?></strong></td>

<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> <td>

<td>
 </div></td>	
			
			</tr>
<?php

array_push($total_monthly_sum,"$total_expenses");
}}
$total_monthly_array = array_sum($total_monthly_sum);
?>
<tr style="border-top:2px solid grey;">
<td ><strong>Monthly Total</strong></td>
<td><strong><?php  echo number_format($total_monthly_array,2);?></strong></td>

<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> <td>

<td>
 </div></td>	
			
			</tr>

<?php


?>			
						

</tbody>
</table>
<span style="display:none;" id="route_name_update"><?php echo $route_name;?></span>
<span style="display:none;"id="id_update"><?php echo $id_jobcard;?></span>
<span style="display:none;"id= "date_update"><?php echo $date;?></span>
	<div id="num_row"style="display:none;"><?php echo $select_num_paticular;?></div>
          <div style="display:none"id="selected_checked"></div>
	    <script src="/accounts/js/template/check_out_jobcard.js"></script>
			    <script src="/accounts/js/template/delete_all_jobcard.js"></script>

	    <script src="/accounts/js/template/update/update_seen_jobcard.js"></script>

<script>


$(document).ready(function(){
	$("#upload").click(function(){
$("#upload_modal").modal("show");
});
});

   
   
</script>