<?php
include '../connect/connect.php';

$route_name = base64_decode($_GET['r_name']);
$status = base64_decode($_GET['status']);

?>


<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Currency </th>
      <th scope="col">Delete</th>
    </tr>
  </thead>
  <tbody>
  
  <?php
  $select_route_name_current=mysqli_query($db,"
  SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,Currency,id
FROM 
	route_currency  WHERE     route_name='$route_name' and status='$status'
ORDER BY 
	id ASC 
  ");
$select_num_route_name_current=mysqli_num_rows($select_route_name_current);
if($select_num_route_name_current !=0){
   	WHILE($get_route_name_current=mysqli_fetch_array($select_route_name_current)){
    	    $Currency =$get_route_name_current['Currency']; 
    	    $id =$get_route_name_current['id']; 
  ?>
    <tr>
      <th scope="row"><?php echo $get_route_name_current['row_num'];?></th>
      <td><?php echo $Currency;?></td>
      <td><button class="btn btn-danger btn-sm" id="currency<?php echo $id;?>"><i class="bx bx-trash"></i></button></td>
<script>
$("#currency<?php echo $id;?>").click(function(){
	id= "<?php echo $id;?>";
	Currency= "<?php echo $Currency;?>";
		 $.ajax({
				url:"delete/route_currency.php",
				method:"POST",
				data:{id:id,Currency:Currency},
				success:function(response){
					alert(response);
						$('#example1').DataTable().ajax.reload();
			}

			
			});
	
});
</script>
    </tr>
<?php
}}
?>
  </tbody>
</table>