  
  <?php
  $route_cnt_this = $_POST['route_cnt_this'];

  ?>
  <?php
  											include '../connect.php';

  $select=mysqli_query($db,"SELECT *  FROM  route_transit WHERE route_name='$route_cnt_this'");
$select_num=mysqli_num_rows($select);
if($select_num ==0){

}else{
	

  ?>
   <h5 class="modal-title" id="myModalLabel"><span id="" style="margin:20px;">Transits</span></h5>
  <table id="tranisst"  class="table table-hover ">
                                            <thead class="bg-light">
                                                <tr>
												<td>No</td>
                                                    <th >
                                                  Route 
                                                    </th>
                                                    
													<th></th>
												
                                                </tr>
                                                <!-- end tr -->
                                            </thead>
											<tbody>
											<?php

											
											
																	$select_route=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    route_prev,id,route_name

FROM 
	route_transit  WHERE     route_name='$route_cnt_this'
ORDER BY 
	id ASC 
		
		");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $route_route =$get_route['route_prev'];
    	    $this_route =$get_route['route_name'];
    	    $row_num =$get_route['row_num'];
    	    $id =$get_route['id'];
    	 ?>
		 <tr>
		 <td><?php echo $row_num;?></td>
		 <td><?php echo $route_route;?></td>
		 <td>		<button type="button" class="btn btn-soft-danger"id="delete<?php echo $id;?>">
                       <i class="uil uil-ban"></i> </button><span id="delete_text"></span></td>
		 </tr>
		 <script>
		 
		 $("#delete<?php echo $id;?>").click(function(){
		id ="<?php echo $id;?>";
				route_cnt_this = "<?php echo $route_cnt_this;?>";						

$("#delete_text").text("deleting..");
				$.ajax({
				url:"delete/delete_transit.php",
				method:"POST",
				data:{id:id},
				success:function(response){
$("#example").DataTable().ajax.reload();

			$.ajax({
				url:"search/transit_table.php",
				method:"POST",
				data:{route_cnt_this:route_cnt_this},
				success:function(response){
					
$("#transit_single_table").html(response);
}
			
			});


}
			
			});
		
		 });
		 </script>
		 
		 <?php
			
}}
											?>
											
											
									<?php
									
}?>		
											</tbody>
											
											
											
											</table>