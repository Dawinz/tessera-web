
<?php
$category_name =0;
$connect = sr_mysqli('cargo');
$name = '';
$query = "SELECT name FROM vehicles";
$result = mysqli_query($connect, $query);
$select_route_num=mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
{
 $name .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}
$connect = sr_mysqli('account');


$spare = '';
$query = "SELECT name FROM particulars WHERE category='Maintaince' OR category='Service'";
$result = mysqli_query($connect, $query);
$select_route_num=mysqli_num_rows($result);
if($select_route_num >1){
	 $spare .= '<option value="Choose Category"> Choose Category</option>';

}
while($row = mysqli_fetch_array($result))
{
 $spare .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}


$date_job = '';
$query = "SELECT date FROM job_card order by id desc";
$result = mysqli_query($connect, $query);
$select_route_num=mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
{
 $date_job .= '<option value="'.$row["date"].'">'.$row["date"].'</option>';
}

$select_category=mysqli_query($db,"SELECT name FROM particulars WHERE category='Maintaince' OR category='Service' order by id asc limit 1");
$select_num_category=mysqli_num_rows($select_category);
if($select_num_category !=0){
   	WHILE($get_category=mysqli_fetch_array($select_category)){
    	    $category_name =$get_category['name']; 
			
}}

$spare_part = '';
$query = "SELECT particular_name,name  	 FROM  particular_sub  WHERE particular_name='$category_name' ";
$result = mysqli_query($connect, $query);
$select_route_num=mysqli_num_rows($result);
if($select_route_num >1){

}
while($row = mysqli_fetch_array($result))
{
 $spare_part .= '<option value="'.$row["name"].'">'.$row["name"].'</option>';
}

?>


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"style="width:1300px;right:100px;right:230px;">
      	<form id="signup" enctype="multipart/form-data">
		<input type="hidde" name="route_name" id="route_name_modal" value="<?php  ?>">
<input type="hidden" name="date" value="<?php ?>">
<input type="hidden" name="id" value="<?php ?>">
    <div class="modal-header">
        <h5 class="modal-title">Garage Expenses</h5>
       
      </div>
          <div class="modal-body">

	<table class="table " id="item_table">
								<tr>
									<th>Job card Date</th>
									<th>Vehicle</th>
									<th>Category</th>
									<th>Item</th>
									<th>qty</th>
									<th>Price</th>
									<th>Detail</th>	
									

									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>

		  </div>
          <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="save">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
		  </form>
    </div>
  </div>
</div>

   <script>
        $(document).ready(function(){
			
	
			var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr>';

		html += '<td><select name="job_date[]" class="form-select  job_date" id="job_date" style="" data-live-search="true"><?php echo $date_job;?></select></td>';
		html += '<td><select name="Vehicle[]" class="form-select  Vehicle" id="Vehicle" style="" data-live-search="true"><option value="Choose Vehicle">Choose Vehicle</option><?php echo $name;?></select></td>';
		html += '<td><select name="item[]" class="form-select  item" id="item" style="" data-live-search="true"><?php echo $spare; ?></select></td>';
		html += '<td><select name="item[]" class="form-select  item" id="item" style="" data-live-search="true"><?php echo $spare_part; ?></select></td>';
		html += '<td><input name="quantity[]" type="text" id="qty"class="form-control  amount qty" placeholder="Quantity"></td>';
		html += '<td><input name="price[]" type="text" id="amount" class="form-control value_name amount price" placeholder="price"></td>';
		html += '<td><input name="detail[]" type="text" class="form-control " placeholder="Detail"></td>';
	
		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button>';
		}

		html += '<td>'+remove_button+'</td></tr>';

		return html;

	}
		$('#item_table').append(add_input_field(0));	
			
			
	$(document).on('click', '.add', function(){

		count++;

		$('#item_table').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');

	});
			$(document).on('click', '.remove', function(){

		$(this).closest('tr').remove();

	});	
			
          $('.select2').select2({
            
            searchInputPlaceholder: 'Search',
			dropdownParent: $("#modaldemo1")
          });
          });
		  
	
	

    </script>