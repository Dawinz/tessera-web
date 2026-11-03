  <table id="tranisst"  class="table table-hover ">
                                            <thead class="bg-light">
                                              <tr>                 

<th></th>
<th>Contact</th>
<th></th>


</tr>
                                                <!-- end tr -->
                                            </thead>
											<tbody>
											<?php
include '../connect.php';
$select_route=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    code,id

FROM 
	contact_code  WHERE     route_id='".$_POST['route_id']."'
ORDER BY 
	id ASC 
		
		");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	
    	    $row_num =$get_route['row_num'];
    	    $code =$get_route['code'];
    	    $id =$get_route['id'];
    	   
    	 ?>
		 <tr>
		
		
		 <td><?php echo $row_num;?></td>
		 <td><?php echo $code;?></td>
		 <td>		<button type="button" class="btn btn-soft-danger"id="delete<?php echo $id;?>">
                       <i class="uil uil-ban"></i> </button><span id="delete_text"></span></td>
		 </tr>
		 <script>
		 
		 $("#delete<?php echo $id;?>").click(function(){
		id ="<?php echo $id;?>";
				route_cnt_this = "<?php echo $_POST['route_id'];?>";						

$("#delete_text").text("deleting..");
				$.ajax({
				url:"delete/delete_country_code.php",
				method:"POST",
				data:{id:id},
				success:function(response){

			$.ajax({
				url:"search/country_code.php",
				method:"POST",
				data:{route_id:route_cnt_this},
				success:function(response){
$("#coutry_code_table").html(response);
}
			
			});


}
			
			});
		
		 });
		 </script>
		 
		 <?php
			
}}
											?>
											
											
	
											</tbody>
											
											
											
											</table>