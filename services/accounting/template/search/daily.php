<style>
#change_color_red { color: #b42318; font-weight: 600; }
#change_color_grey { color: #74788d; }
</style>

<?php
include '../connect/connect.php';
$route_name = $_POST['route'];

$amount_remain=0;
$total_expenses = 0;


$month = $_POST['month'];
$route = $_POST['route'];
$year = date('Y', time());


?>
<script>
	function myFunction(month,year,route) {
				
                        $.ajax(
                            {
                                url: 'search/daily.php',
                                method: 'POST',
                                data: {
								   month:month,
								   year:year,
								   route:route
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
					if (window.srMountAjaxHtml) {
						window.srMountAjaxHtml('load_daily_account', data);
					} else {
						$("#load_daily_account").html(data);
					}
                                },
                                dataType: 'text'
                            }
                        );
                    
			}
</script>
  <div class="table-responsive sr-daily-table-wrap">
  <table id="order-listing" class="table table-hover align-middle w-100 mb-0">
                        <thead class="table-light">
                          <tr>
					<th scope="col" style="width:2rem;"></th>
					<th scope="col">Date</th>
					<th scope="col">Category</th>
					<th scope="col">Particular</th>
					<th scope="col">Item</th>
					<th scope="col">Qty</th>
					<th scope="col">Vehicle</th>
					<th scope="col">Detail</th>
					<th scope="col">Username</th>
					<th scope="col">Account</th>
					<th scope="col">Amount DR</th>
					<th scope="col">Amount CR</th>
					<th scope="col">Balance</th>
					<th scope="col" class="text-center">Action</th>
                </tr>
                        </thead>
                        <tbody>
		
		
		
			
			
			<?php
$new_row = 0;
						$select_paticular=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id asc
	) row_num,item,cat_group,account,vehicle,route_name,vehicle,cash_id,cash_date,approved,date,detail,amount,cash_hand,amount_curr,username,time,username,id,stock_amount,amount_remain,name,account_from,balance,insert_date_time,quantity

FROM 
	office_expenses  where  month='$month' and year ='$year' and route_name='$route'
	
		
		");
$select_num_paticular=mysqli_num_rows($select_paticular);
if($select_num_paticular !=0){
   	WHILE($get_paticular=mysqli_fetch_array($select_paticular)){
    	    $row_num =$get_paticular['row_num'];
    	    $id =$get_paticular['id'];
			$total_expenses += $get_paticular['amount']; 
			$stock_amount = $get_paticular['stock_amount']; 
			$amount_remain = $get_paticular['amount_remain']; 
			$new_row = $new_row +1 ;
?>

						<tr>
												<td><?php echo $get_paticular['row_num'];?></td>
																		<td ><span style="font-weight:bold;"><?php echo  date("d.m.Y", strtotime($get_paticular['date']));  ?></span></td>

												<td><?php echo $get_paticular['cat_group'];?></td>

						<td><?php echo $get_paticular['name'];?></td>
						<td><?php  if($get_paticular['item']!="Choose Item"){echo $get_paticular['item'];}else{echo "-";};?></td>
						<td><?php  if($get_paticular['quantity']!="0"){echo $get_paticular['quantity'];}else{echo "-";};?></td>
						<td><?php  if($get_paticular['vehicle']!="Choose Vehicle"){echo $get_paticular['vehicle'];}else{echo "-";};?></td>
												
						<td><?php if($get_paticular['detail']==""){echo "-";}else{echo $get_paticular['detail'];}?></td>
						<td><?php  if($get_paticular['cash_hand']==0){echo $get_paticular['username'];}else{ echo "-";}?></td>
						<td><?php echo $get_paticular['account'];?></td>
						

						<td><strong><?php if($stock_amount==0){echo "-";}else{echo number_format($get_paticular['stock_amount']);}?></strong></td>
						<td><strong><?php echo number_format($get_paticular['amount']);?></strong></td>
						<td><strong><?php echo number_format($get_paticular['amount_remain']);?></strong></td>
						<td class="text-center text-nowrap">
<div class="btn-group btn-group-sm" role="group">
              <button type="button" class="btn btn-soft-primary sr-act-btn edit<?php echo $id; ?>" id="edit<?php echo $id; ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop" title="Edit"><i class="mdi mdi-pencil"></i></button>
<script>
$("#edit<?php echo $id;?>").click(function(){

$("#choose_group_update").text("<?php echo $get_paticular['cat_group'];?>");
$("#choose_group_update").val("<?php echo $get_paticular['cat_group'];?>");
$("#expenses_update_text").text("<?php echo $get_paticular['name'];?>");
$("#expenses_update_text").val("<?php echo $get_paticular['name'];?>");
$("#vehicle_update").text("<?php echo $get_paticular['vehicle'];?>");
$("#date_update").val("<?php echo $get_paticular['date'];?>");
$("#amount_update").val("<?php echo $get_paticular['amount'];?>");
$("#detail_update").val("<?php echo $get_paticular['detail'];?>");
$("#item_update_holder").text("<?php echo $get_paticular['item'];?>");
$("#item_update_holder").val("<?php echo $get_paticular['item'];?>");

$("#vehicle_update_text").text("<?php echo $get_paticular['vehicle'];?>");
$("#vehicle_update_text").val("<?php echo $get_paticular['vehicle'];?>");
$("#current_route_update").val("<?php echo $get_paticular['id'];?>");
$("#quantity_update").val("<?php echo $get_paticular['quantity'];?>");
});
</script>             

			 <button type="button" class="btn btn-soft-danger sr-act-btn" id="delete<?php echo $id; ?>" title="Delete"><i class="mdi mdi-delete"></i></button>
		<script>
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
	    $.ajax({
    url:"./delete/delete_daily.php",
    method:"POST",
    data:{id:id},
	   beforeSend: function(){
		   $("#delete<?php echo $id;?>").prop('disabled', true).attr('title', 'Deleting...');
            },
    success:function(data){
					myFunction(month,year,route) ;



    },
	complete: function(){
		   $("#delete<?php echo $id;?>").prop('disabled', false).attr('title', 'Delete');
	}
   })
  }
});

			});
			</script>	
            </div>
        </td>
						</tr>
						
						<?php
}}
						?>
			
			
			<?php
			
			
			

			
			?>
			
			
				<tr>						<td></td>

				        <td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						
						 <td><strong>Total Expenses</strong></td>
						<td class="sr-total-cell"><strong><span><?php echo number_format($total_expenses);?></span></strong></td>
						<td></td>
						<td></td>
						</tr>
			
						</tbody>
						</table>
</div>