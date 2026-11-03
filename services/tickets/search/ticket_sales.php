<?php
require_once __DIR__ . '/../includes/tessera_tenant.php';
include '../connect.php';
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$route_name = $_POST['route_name'];
$username = $_POST['username'];
$total_cost = 0;
?>            
<style>

</style>
<div class="table-responsive"style="position:relative;bottom:45px">
	<div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="myInputTextField" class="form-control sr-table-search" placeholder="Search....">
		 </form>
      </div> 
                                            <table class="table mb-0" id="example">
                                                              <thead class="table-light">
<tr>                 

<th></th>
<th>Seat id</th>
<th>Seat no</th>
<th>Bus</th>
<th>Fullname</th>
<th>Route Name</th>
<th>Date </th>
<th>Username </th>
<th>Cost</th>

</tr>
				
                          </thead>
						  
<?php
$select_points=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id DESC ) row_num,issuer,id,bus,start_route,end_route,cost,seat_no,firstname,lastname,book_date,book_time  FROM  booking WHERE " . tessera_sql_tenant_eq() . " AND $username $route_name delete_book = '' and status !='booked' and  book_date >= '$start_date' and  book_date <= '$end_date'  ");
$select_num_points=mysqli_num_rows($select_points);
if($select_num_points !=0){
   	WHILE($get_points=mysqli_fetch_array($select_points)){
    	    $bus =$get_points['bus']; 
    	    $id =$get_points['id']; 
    	    $seat_no =$get_points['seat_no']; 
    	    $cost =$get_points['cost']; 
    	    $firstname =$get_points['firstname']; 
    	    $lastname =$get_points['lastname']; 
    	    $book_date =$get_points['book_date']; 
    	    $book_time =$get_points['book_time']; 
    	    $start_route =$get_points['start_route']; 
    	    $end_route =$get_points['end_route']; 
    	    $issuer =$get_points['issuer']; 
    	    $row_num =$get_points['row_num']; 
			$total_cost +=$cost;
			?>
			<tr>
			<td><?php echo $row_num;?></td>
			<td><?php echo $id;?></td>	
			<td><?php echo $seat_no;?></td>	
			<td><?php echo $bus;?></td>
			<td><?php echo "$firstname $lastname";?></td>
			<td><?php echo "$start_route to $end_route";?></td>
			<td><?php echo "$book_date ($book_time)";?></td>
			<td><?php echo "$issuer";?></td>
			<td><?php echo number_format($cost,2);?></td>
			</tr>
			<?php
			
}}
?>	
<tr>
			<td></td>
			<td></td>
			<td></td>	
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><strong>Total Sales</strong></td>
			<td><strong><?php echo number_format($total_cost,2);?></strong></td>
			</tr>					  
						  
						  
						                 	                           <!-- end tbody --> 
                                            </table><!-- end table --> 
                                        </div><br><!-- end card --> 
										
										<script>
										$(document).ready(function(){
    

new DataTable('#example', {
	             order:[],

    buttons: [
        'copy', 'excel', 'pdf'
    ],
    layout: {
        topStart: 'buttons'
    }
});
		 oTable = $('#example').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable.search($(this).val()).draw() ;
}); 
										});
										</script>