  
  <?php
  $route_cnt_this = $_POST['route_cnt_this'];

  ?>
  <?php
  											include '../connect.php';



  ?>
    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel"><span id="route_cnt">Route Contact</span></h5>
                                   
                                                    </div>
  <table id="tranisst"  class="table table-hover ">
                                            <thead class="bg-light">
                                              <tr>                 
<th>Route</th>
<th>Name</th>
<th>Contact</th>

<th>Delete</th>
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
    name,contact,id,route_name

FROM 
	route_contact  WHERE     route_name='$route_cnt_this'
ORDER BY 
	id ASC 
		
		");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $route_name =$get_route['route_name'];
    	    $contact =$get_route['contact'];
    	    $name =$get_route['name'];
    	    $row_num =$get_route['row_num'];
    	    $id =$get_route['id'];
    	 ?>
		 <tr>
		
		 <td><?php echo $route_name;?></td>
		 <td><?php echo $name;?></td>
		 <td><?php echo $contact;?></td>
		 <td>		<button type="button" class="btn btn-soft-danger"id="delete<?php echo $id;?>">
                       <i class="uil uil-ban"></i> </button><span id="delete_text<?php echo $id;?>"></span></td>
		 </tr>
		 <script>
		 
		 $("#delete<?php echo $id;?>").click(function(){
		id ="<?php echo $id;?>";
				route_cnt_this = "<?php echo $route_cnt_this;?>";						

$("#delete_text<?php echo $id;?>").text("deleting..");
				$.ajax({
				url:"delete/delete_contact.php",
				method:"POST",
				data:{id:id},
				success:function(response){
			$.ajax({
				url:"search/contact_table.php",
				method:"POST",
				data:{route_cnt_this:route_cnt_this},
				success:function(response){
$("#contact_table").html(response);
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
									
?>		
											</tbody>
											
											
											
											</table>