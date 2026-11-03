<?php
include '../connect/connect.php';
$id_jobcard = $_POST['id'];
$route_name = $_POST['route_name'];
$date = $_POST['date'];
$checked="";
$user_checked="";
$select_jb=mysqli_query($db,"SELECT analysed,user_checked FROM   job_card  WHERE  date = '$date' and route_name='$route_name'  ");
$select_num_jb=mysqli_num_rows($select_jb);
if($select_num_jb !=0){
   	WHILE($get_jb=mysqli_fetch_array($select_jb)){
    	    $checked =$get_jb['analysed']; 
    	    $user_checked =$get_jb['user_checked']; 
			
	}
}




function seen_array($checked) {
	  if ($checked !="") {
		  echo "change_color_red";
	  }else{
		  echo "change_color_grey";
	  }
}
function seen_array_view($checked,$user_checked) {
	  if ($checked !="") {
		  echo "Analysed by $user_checked";
	  }else{
		  echo "Click if full analysed";
	  }
}


?>
<style>
#change_color_red{
	
	color:red;
}
#change_color_grey{
	
	color:#7987A1;
}
</style>
          <div class="az-mail-options">
           
			      <nav class="nav nav-pills">
		              <button class="btn btn-info" id="upload"style="">Upload <i class="fa fa-upload" aria-hidden="true"></i></button>

                    
                  </nav>

            <div class="btn-group">
              <button class="btn btn-light" id="copy_table"><i class="fa fa-copy" aria-hidden="true"></i></button>
              <button class="btn btn-light" id="print_table"><i class="fa fa-print" aria-hidden="true"></i></button>
              <button class="btn btn-light " id="excel_table"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
              <button class="btn btn-light " id="csv_table"  ><i class="bi bi-filetype-csv"></i>csv</button>
              <button class="btn btn-light " id="pdf_table"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
            
            </div><!-- btn-group -->
          </div>
 <a class="mark_jobcard <?php seen_array($checked); ?> eyeclass " id="<?php seen_array($checked); ?>" href="#"style="position:relative;"><i class="typcn typcn-eye  " ></i> <span class="results_eye"><?php seen_array_view($checked,$user_checked); ?></span></a>
	<div class="btn-group check_buttons" style="position: absolute;z-index: 10;margin-left:230px;margin-top:10px;display:non;">
              <button class="btn btn-danger check_buttons" id="delete_all" style="display:none;"><span id="delete_text">Delete</span> <i class="fa fa-trash" aria-hidden="true"></i></button>
             
            </div>
    <table style="margin-top:7px;"id="order-listing" class="table" cellspacing="0" width="100%">
                        <thead>
                          <tr class="bg-primary text-white">
                            
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>No</th>
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
$total_expenses =0;	

$select_paticular=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    date,bus,item,qty,price,date_insert,time_insert,detail,username,id

FROM 
	job_card_entries  WHERE date='$date' and route_name='$route_name'
ORDER BY 
	id ASC 
		
		");
$select_num_paticular=mysqli_num_rows($select_paticular);
if($select_num_paticular !=0){
   	WHILE($get_paticular=mysqli_fetch_array($select_paticular)){
    	    $row_num =$get_paticular['row_num'];
    	    $item =$get_paticular['item'];
    	    $id =$get_paticular['id'];
    	    $bus =$get_paticular['bus'];
    	    $date =$get_paticular['date'];
    	    $qty =$get_paticular['qty'];
    	    $price =$get_paticular['price'];
    	    $date_insert =$get_paticular['date_insert'];
    	    $username =$get_paticular['username'];
    	    $detail =$get_paticular['detail'];
			$total_expenses += $price;	

			?>
			<tr>
<td><input type="checkbox" class="checked<?php echo $row_num;?> checked_signle" id="<?php echo $id;?>" ></td>
<td> <?php echo $row_num;?></td>
<td> <?php echo $date;?></td>
<td> <?php echo $bus;?></td>
<td> <?php echo $item;?></td>
<td> <?php echo $qty;?></td>
<td> <?php echo number_format($price);?></td>
<td> <?php echo $detail;?></td>
<td> <?php echo $date;?></td>
<td> <?php echo $username;?></td>
<td> <div class="btn-group">
<button style="margin:2px;"class="btn btn-secondary text-white edit<?php echo $id;?>" id="" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="fa fa-pencil" aria-hidden="true"></i></button>
<button style="margin:2px;"class="btn btn-danger " id="delete<?php echo $id;?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
      
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

});


</script>
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
    url:"./delete/delete_entry_jobcard.php",
    method:"POST",
    data:{id:id},
	   beforeSend: function(){
		   $("#delete<?php echo $id;?>").text("Deleting...");
            },
    success:function(data){
		alert(data);

	id_jobcard="<?php echo $_POST['id'];?>";
	route_name="<?php echo $_POST['route_name'];?>";
	date="<?php echo $_POST['date'];?>";

	function myFunction(id) {
				
                        $.ajax(
                            {
                                url: 'search/job_card_details.php',
                                method: 'POST',
                                data: {
                                   id:id,
								   route_name:route_name,
								   date:date
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

					myFunction(id);


    }
   })
  }
});

			});
			</script>			
			<?php
}}

?>
	<tr>
<td></td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td></td>
<td> </td>
<td><strong>Total</strong> </td>
<td><strong><?php echo number_format($total_expenses,2);?> </strong></td>
<td></td>	
			
			</tr>
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

	 var table = new DataTable('#order-listing', {
		   lengthMenu: [100],
		 				   "language": {
	     searchPlaceholder: "Search",
        search: "",
		
     processing: ''
},
ordering: false,

"order": [],
    layout: {
        topStart: {
            buttons: [
			 {
			 extend: 'copy',
			 footer: true, 
			 class: 'buttons-copy',
			 init: function(api, node,cofig){
				 $(node).hide();
			 }
			 },
			 { extend: 'csv', footer: true, class: 'buttons-csv',init: function(api, node,cofig){
				 $(node).hide();
			 }},
			 { extend: 'print', footer: true, class: 'buttons-print',init: function(api, node,cofig){
				 $(node).hide();
			 }},
			 { extend: 'pdf', footer: true, class: 'buttons-pdf',init: function(api, node,cofig){
				 $(node).hide();
			 }},
			{ extend: 'excel', text: 'Save as Excel', class: 'buttons-excel',init: function(api, node,cofig){
				 $(node).hide();
			 }}
			],
		

        }
    }
});
table.buttons('.export').remove();
   $("#print_table").click(function(){
		  table.buttons('.buttons-print').trigger();

	  
   });  
   $("#copy_table").click(function(){
		  table.buttons('.buttons-copy').trigger();

   });  

   $("#pdf_table").click(function(){
		  table.buttons('.buttons-pdf').trigger();

   });  
   $("#excel_table").click(function(){
		  table.buttons('.buttons-excel').trigger();

   }); 
   $("#csv_table").click(function(){
		  table.buttons('.buttons-csv').trigger();

   });
   
   
   
</script>