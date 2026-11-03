<?php
session_start();
$username_loged=$_SESSION['username'];
?>
<style>
#change_color_red{
	
	color:red;
}
#change_color_grey{
	
	color:#4a4a4a;
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
.dt-search{
	Display:none;
}
#myInputTextField
{
width:200px;
position:relative;
left:1000px;
display:none;
}
th, td {
  border-style:solid;
  border-color: #96D4D4;
}
.tooltiptext {
	display:none;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
  margin-left:10px;
  box-shadow: 1px 1px grey;
}
</style>
<div id ="upload_modal"class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
     <div class="container" > 
	 <div class="center" style="align-content: center">
    <div class="dropzone">
        <div class="dz-message needsclick">
            <h1>Drop files here or click to upload.</h1>
        </div>
    </div>
    </div>
</div>
    </div>
  </div>
</div>

<?php
    include '../connect/connect.php';
  $month = $_POST['month'];
  $year = $_POST['year'];
  $route = $_POST['route'];
  $trip_date = $_POST['trip_date'];
  $bus = $_POST['bus'];
  $route_start = $_POST['point'];
  $start = $_POST['start'];
  $route_name = $_POST['route_name'];
  $currency_value_array = $_POST['currency_value'];
$currency_selected = $_POST['currency'];
  $currency_default = $_POST['currency_sign'];
  $trip_date_select = $trip_date;

  include '../drop_down/income.php';
include '../drop_down/expenses.php';
include '../drop_down/currency.php';
include '../drop_down/all_particular.php';
  include '../includes/canvas_edit.php';
  $to_pay_vec = 0;
  $paid_vec = 0;
  $amount_vec = 0;

if(  $start !="last"){
	$selected_point =" AND point='$route_start'"; 
}else{
	$selected_point="";
}
$select_currency=mysqli_query($db,"SELECT Currency,value_currency FROM   route_currency   where route_name ='$route'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $currency_main_route =$get_currency['Currency']; 
    	    $currency_value =$get_currency['value_currency']; 
			
	}
}


function currency($amount,$currency_value_array,$currency_selected,$currency,$currency_sign,$currency_default,$currency_main_route,$id_particular) {
if(($currency_selected =="Default") OR ($currency_sign == $currency_default)) {
	  echo ''.number_format($amount).' '.$currency_sign.'';
  } else{
	  
  
   $my_string = '{'.$currency_value_array.'}';


   // convert into array

    $my_array = json_decode($my_string);


foreach ($my_array as $x => $value) {
	$currency_value_select = "$currency-$currency_selected";
	//echo "$x .. $currency_value_select<br>";
	if($x==$currency_value_select){
			$new_value = $amount * $value;
	echo '<span id="hover_holder'.$id_particular.'">'.number_format($new_value,2).' '.$currency_default.'</span> <span class="tooltiptext" id="holder'.$id_particular.'">'.number_format($amount,2).'</span>';
	?>

	<script>
		$("#hover_holder<?php echo $id_particular;?>").mouseover(function(){
			$("#holder<?php echo $id_particular;?>").show();
		});	
		$("#hover_holder<?php echo $id_particular;?>").mouseout(function(){
			$("#holder<?php echo $id_particular;?>").hide();
		});
	</script>
	<?php
	}
				}
				}
}
$navigation_holder = "";
 ?>
 <div id="currency_value" style="display:none;" class="currency_value"><?php echo $currency_value_array;?></div>
 <div id="currency" style="display:none;" class="currency"><?php echo $currency_selected;?></div>
 <div id="currency_default" style="display:none;" class="currency_default"><?php echo $currency_default;?></div>
 <div id="currency_sign" style="display:none;" class="currency_sign"><?php echo $currency_default;?></div>

 
 <input type="hidden"id="month_ext" class="month1"value="<?php echo $month;?>">
 <input type="hidden"id="currency_year" class="currency_year"value="<?php echo $year;?>">
 <input type="hidden"id="main_route_ext" class="main_route"value="<?php echo $route;?>">
 <input type="hidden"id="route_name_ext" class="route_name"value="<?php echo $route_name;?>">
 <input type="hidden"id="trip_date_ext" class="trip_date"value="<?php echo $trip_date;?>">
 <input type="hidden"id="bus_ext" class="bus"value="<?php echo $bus;?>">
 <input type="hidden"id="route_start_ext" class="route_start"value="<?php echo $route_start;?>">
 <input type="hidden" id="start_ext" class="start_ext"value="<?php echo $start;?>">

 <input type="hidden"id="currency_ext" class="currency_ext"value="<?php echo $currency_selected;?>">
 <input type="hidden"id="currency_sign_ext" class="currency_sign_ext"value="<?php echo $currency_default;?>">
 <script>
 $(document).ready(function(){
current_route = $("#route_name_ext").val();
main_route_ext = $("#main_route_ext").val();
bus_ext = $("#bus_ext").val();
trip_date_ext = $("#trip_date_ext").val();
route_start_ext = $("#route_start_ext").val();


    srSafeDropzone(".dropzone", {
      url: 'upload/upload.php',
	   init: function() {
    this.on("sending", function(file, xhr, formData) {
      formData.append("current_route", current_route);
      formData.append("main_route_ext", main_route_ext);
      formData.append("bus_ext", bus_ext);
      formData.append("trip_date_ext", trip_date_ext);
      formData.append("route_start_ext", route_start_ext);
      console.log(formData)
    });
	 this.on("complete", function (file) {
 
			 $.ajax({
    url:"search/vehicle_attachment.php",
    method:"POST",
    data:{route_name:current_route,route:main_route_ext,bus:bus_ext,trip_date:trip_date_ext},
	   beforeSend: function(){

            
            },
    success:function(data){
$("#vehicle_attachment").html(data);


    }
   })

        });
  },

      width: 300,
      height: 300, 
      progressBarWidth: '100%',
      maxFileSize: '30MB'
    });
    });
	</script>


  <div class="sr-ledger-toolbar az-dashboard-nav">
            <nav class="nav sr-ledger-currency-tabs flex-wrap gap-1">
					        <a class="nav-link <?php if($currency_selected=="Default"){ echo "active";}?> currency_selected" data-bs-toggle="tab" href="#" id="">Default</a>

			<?php 
$select_currency_value=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num, name,symbol FROM currency  ");
$select_num_currency_value=mysqli_num_rows($select_currency_value);
if($select_num_currency_value !=0){
   	WHILE($get_currency_value=mysqli_fetch_array($select_currency_value)){
    	    $curr_name =$get_currency_value['name']; 
    	    $symbol =$get_currency_value['symbol']; 
    	    $row_num =$get_currency_value['row_num']; 
			
			if($currency_default==""){
	$currency_default = $symbol;
}
?>
		              <a class="nav-link currency_selected <?php if($currency_selected==$curr_name){ echo "active";}?>" data-bs-toggle="tab" href="#" id="<?php echo $symbol;?>"><?php echo $curr_name;?></a>

<?php
}}
			
			?>
			
             
            </nav>

            <?php
            require_once __DIR__ . '/../includes/sr_export_actions.php';
            sr_render_export_actions([
              'include_search' => true,
              'include_upload' => true,
            ]);
            ?>
          </div>

         <div class="sr-ledger-search-row d-flex justify-content-end mb-2">
         <input type="text" id="myInputTextField" class="form-control form-control-sm" placeholder="Search entries..." style="display:none;">
         </div>

  <div class="table-responsive sr-ledger-table-wrap">
  <table id="order-listing" class="table table-hover align-middle w-100 mb-0">
                        <thead class="table-light">
                          <tr>
                            <th scope="col"></th>
                            <th scope="col">Trip Date</th>
                            <th scope="col">Location</th>
                            <th scope="col">Category</th>
                            <th scope="col">Particular</th>
                            <th scope="col">Income</th>
                            <th scope="col">Expenses</th>
                            <th scope="col">Detail</th>
                            <th scope="col">Affect</th>
                            <th scope="col">Action</th>
                       
                          </tr>
                        </thead>
                        <tbody>
						  <tr >
						  <td ></td>
                 		  <td><?php echo '<div class="badge badge-success ms-auto">'.$bus.'</div>';?></td>
                        
                            <td ></td>
                            <td ></td>
							<td></td>
							                            <td></td>
                            <td></td>
                            <td></td>
                          
                            <td ></td>
                            <td ></td>

                       
                       
                          </tr>  
 <?php
$to_pay_vec =0;
$paid_vec = 0;
$amount_vec = 0;
$select_num_mainfest_point =0;
function seen_array($checked_array,$route_start) {
  $new_array = explode(",", "$checked_array");
	  if (($key = array_search($route_start, $new_array)) !== false) {
		  echo "id='change_color_red'";

	  }else{
		  echo "id='change_color_grey'";

	  }
}
$total_first_income_final = 0;	
$total_first_expenses_final = 0;




$count = 0;
	




$select_point=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num, point,id,main_route,route_name,end_point FROM assign_route WHERE   username='$username_loged' AND main_route='$route'  ORDER BY id ASC ");
$select_num_point=mysqli_num_rows($select_point);
if($select_num_point !=0){
	

   	WHILE($get_point=mysqli_fetch_array($select_point)){
    	    $point =$get_point['point'];
    	   
    	    $id =$get_point['id'];
    	    $main_route =$get_point['main_route'];
    	    $current_route =$get_point['route_name'];
    	    $row_num_assign =$get_point['row_num'];
			
$end_point =$get_point['end_point'];
$total_first_income = 0;	
$total_first_expenses = 0;	
$total_expneses_affect =0;	
$total_expneses_affect_paid =0;	
$count = $row_num + 1;


$total_income =0;
$total_expenses =0;


$select_paticular=mysqli_query($db," SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num,
 category,id,name,income,expenses,username,date_insert,time_insert,detail,time_insert,affect,currency,currency_sign,price,point
FROM 
vehicle_account  WHERE   route_name='$route' and trip_date='$trip_date'   and bus='$bus' and current_route='$current_route' and affect !='To be Paid'");
$select_num_paticular=mysqli_num_rows($select_paticular);
if($select_num_paticular !=0){
   	WHILE($get_paticular=mysqli_fetch_array($select_paticular)){
    	    $expenses =$get_paticular['expenses'];
    	    $income =$get_paticular['income'];
    	    $category =$get_paticular['category'];
    	    $name =$get_paticular['name'];
    	    $row_num_entries =$get_paticular['row_num'];
    	    $date_insert =$get_paticular['date_insert'];
    	    $time_insert =$get_paticular['time_insert'];
    	    $detail =$get_paticular['detail'];
    	    $username =$get_paticular['username'];
    	    $id =$get_paticular['id'];
    	    $id_particular =$get_paticular['id'];
    	    $affect =$get_paticular['affect'];
    	    $currency =$get_paticular['currency'];
    	    $currency_sign =$get_paticular['currency_sign'];
    	    $price =$get_paticular['price'];
    	    $point =$get_paticular['point'];
		
		$total_first_income +=$income;
		$total_first_expenses +=$expenses;
				
if($affect=="To be Paid"){
				$total_expneses_affect += $expenses;	

			}	if($affect=="paid"){
				$total_expneses_affect_paid += $expenses;	

			}
			
$my_string = '{'.$currency_value_array.'}';
    $my_array = json_decode($my_string);
	if($currency_selected=="Default"){
	$currency_selected=$currency_main_route;
	
	}else{
		
	}	
	if($currency_selected ==$currency){
	
	$total_income +=  $income;
	$total_expenses +=  $expenses;
	
	if($affect=="To be Paid"){
				$total_expneses_affect += $expenses;	

			}	if($affect=="paid"){
				$total_expneses_affect_paid += $expenses;	

			}
	
	}
	foreach ($my_array as $x => $value) {
	$currency_value_select = "$currency-$currency_selected";

	if($x==$currency_value_select){
		if($currency_selected !=$currency){
		$new_value = $income * $value;
		$new_value_exp = $expenses * $value;
	$total_income +=  $new_value;
	$total_expenses +=  $new_value_exp;

if($affect=="To be Paid"){
				$total_expneses_affect += $expenses;	

			}	if($affect=="paid"){
				$total_expneses_affect_paid += $expenses;	

			}

		}else{
				
		}

	}else{

	}
				}


		?>

		<tr class="">
		
		<td >
	
		<strong style="color:#575757;"></td>
		 <td><strong style="color:#575757;"><?php  if(($row_num_entries==1) AND ($row_num_assign==1)){ echo $trip_date;}else{}?></strong></td>
                            <td style="color:#575757;"><strong><?php  if($row_num_entries==1){ echo $point;}else{}?></strong></td>
                            <td><div id="category<?php echo $id;?>"><?php echo $category;?></div></td>
                            <td><div id="particular<?php echo $id;?>"><?php echo $name;?></div></td>
	      <td id="income_tr<?php echo $id;?>" <?php if($income!=0){ echo "style='background:green;color:white;'";}?>><span id="income<?php echo $id;?>" class="income_display<?php echo $row_num?>"><?php
if($income==0){
echo "-";	
}else{

	  $currency_selected = $_POST['currency'];

currency($income,$currency_value_array,$currency_selected,$currency,$currency_sign,$currency_default,$currency_main_route,$id_particular);
}
							?>
						
							
				
							</td>
		        <td ><span id="expenses<?php echo $id;?>" class="expenses_display<?php echo $row_num?>"><?php if($expenses==0){
echo "-";	
}else{
	  $currency_selected = $_POST['currency'];

currency($expenses,$currency_value_array,$currency_selected,$currency,$currency_sign,$currency_default,$currency_main_route,$id_particular);

}?>

</td>
		     <td id="detail<?php echo $id;?>"><?php echo $detail;?></td>
                            <td style="white-space: nowrap"id="affect<?php echo $id;?>"><?php echo $affect;?></td>
                          <td> <div class="btn-group">
              <button style="margin:2px;"class="button_round_success edit<?php echo $id;?>" id="<?php echo $currency;?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="fa fa-pencil" aria-hidden="true"></i></button>
              <button style="margin:2px;"class="button_round_danger " id="delete<?php echo $id;?>"><span class="delete_text<?php echo $id;?>"></span><i class="fa fa-trash" aria-hidden="true"></i></button>
        
            </div></td>
		</tr>
		
		<script>
		
	$("#delete<?php echo $id;?>").click(function(){
		id = "<?php echo $id;?>";
		$(".delete_text<?php echo $id;?>").text("deleting...");
		  $.ajax({
    url:"delete/delete_entry.php",
    method:"POST",
    data:{id:id},
	   beforeSend: function(){
	
            	$("#load_vehicle_account").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:550px'></span></p>"); 
            },
    success:function(data){
alert(data);
month  = "<?php echo $month;?>";

			myFunctionRound(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign) ;


  }
   });
	});
		</script>
			<script>
			$(".edit<?php echo $id;?>").click(function(){
				
$("#part_Category_update").text("<?php echo $category;?>");
$("#part_Category_update").val("<?php echo $category;?>");
$("#part_detail_update").text("<?php echo $name;?>");
$("#part_detail_update").val("<?php echo $name;?>");
$("#currency_update").text("<?php echo $currency;?>");
$("#currency_update").val("<?php echo $currency;?>");
$(".amount_income_update").val("<?php echo $price;?>");
$(".detail_update").val("<?php echo $detail;?>");
$(".part_id").val("<?php echo $id;?>");
			});
			
			</script>
		<?php
		
		
}}
if($select_num_paticular !=0){
?>
     

						  <tr >
                 		  <td></td>
                        <td style="color:#575757;"><strong></strong></td>
                            <td style="color:#575757;"><strong></strong></td>
                            <td style="color:#575757;"></td>
							<td></td>
							                            <td></td>
                            <td></td>
                            <td></td>
                          
                            <td style="border-top:2px solid #c9c9c9;border-bottom:2px solid #c9c9c9;"><strong>Total Income</strong></td>
                            <td style="border-top:2px solid #c9c9c9;border-bottom:2px solid #c9c9c9;"><strong><?php echo ''.number_format($total_income,2).''.$currency_default.''?></strong></td>

                       
                       
                          </tr>		  <tr >
                 		  <td></td>
                        <td style="color:#575757;"><strong></strong></td>
                            <td style="color:#575757;"><strong></strong></td>
                            <td style="color:#575757;"></td>
							<td></td>
							                            <td></td>
                            <td></td>
                            <td></td>
                          
                            <td style="border-top:2px solid #c9c9c9;border-bottom:2px solid #c9c9c9;"><strong>Total Expenses</strong></td>
                            <td style="border-top:2px solid #c9c9c9;border-bottom:2px solid #c9c9c9;"><strong><?php echo ''.number_format($total_expenses,2).''.$currency_default.''?></strong></td>

                       
                       
                          </tr>   
						  <tr >
       		<td></td>
       		<td></td>
                          <td ></td>
                            <td></td>
                            <td></td>
                            
                            <td></td>
                            <td></td>
                            <td></td>
                       <td style="border-top:2px solid #c9c9c9;border-bottom:2px solid #c9c9c9;"><strong>Balance </strong></td>
                            <td style="border-top:2px solid #c9c9c9;border-bottom:2px solid #c9c9c9;"><strong><?php $total_to_be_exp = $total_income-$total_expenses; echo ''.number_format($total_to_be_exp,2).' '.$currency_default.''?></strong></td>
                       
                          </tr>     

<?php
}
$total_first_income_final +=$total_income;
$total_first_expenses_final +=$total_expenses;

}}


?>



  
					
					
<?php


	
	?>
	 <script>
		 $(".checked_update<?php echo $row_num_manifest;?>").click(function(){
			my_seen = $(this).attr('id');
			if(my_seen=="change_color_grey"){
	$(this).removeAttr('id');
	$(this).attr('id', 'change_color_red');
}else{
$(this).removeAttr('id');
$(this).attr('id', 'change_color_grey');

}	
bus = "<?php echo $bus;?>";
	trip_date = "<?php echo $trip_date;?>";
	start_point = "<?php echo $route_start;?>";
	 $.ajax({
    url:"update/update_seen.php",
    method:"POST",
    data:{bus:bus,trip_date:trip_date,start_point:start_point},
	   beforeSend: function(){
	
            },
    success:function(data){
alert(data);

    }
   })			

		 });
		 </script>
	<?php		
			
$total_balance = $total_first_income_final-$total_first_expenses_final
?>

    <tr >
	  		
   <td></td>
                                 <td style="color:#575757;"></td>
                            <td></td>
                            <td style="color:#575757;"></td>  
							<td></td>
                            
                            <td></td>
                            <td></td>
                         
                     <td></td>
                       <td style="border-top:2px solid black;border-bottom:2px solid black;"><strong>Total Income</strong></td>
                            <td style="border-top:2px solid black;border-bottom:2px solid black;"><strong><?php echo ''.number_format($total_first_income_final,2).''.$currency_default.'';?> </strong></td>
                            
                          </tr>  
						      <tr >
	  		
   <td></td>
                                 <td style="color:#575757;"></td>
                            <td></td>
                            <td style="color:#575757;"></td>  
							<td></td>
                            
                            <td></td>
                            <td></td>
                         
                     <td></td>
                       <td style="border-top:2px solid black;border-bottom:2px solid black;"><strong>Total Expenses</strong></td>
                            <td style="border-top:2px solid black;border-bottom:2px solid black;"><strong><?php echo ''.number_format($total_first_expenses_final,2).''.$currency_default.'';?> </strong></td>
                            
                          </tr>      <tr >
	  		
   <td></td>
                                 <td style="color:#575757;"></td>
                            <td></td>
                            <td style="color:#575757;"></td>  
							<td></td>
                            
                            <td></td>
                            <td></td>
                         
                     <td></td>
                       <td style="border-top:2px solid black;border-bottom:2px solid black;<?php if($total_balance < 0){echo "";}else{echo "";}?>"><strong>Balance</strong></td>
                            <td style="border-top:2px solid black;border-bottom:2px solid black;<?php if($total_balance < 0){echo "";}else{echo "";}?>"><strong><?php echo ''.number_format($total_balance,2).''.$currency_default.'';?> </strong></td>
                            
                          </tr>  
						  <?php

						  ?>
</tbody>

</table>
</div>


		<script>
		
$(function() {
    var target = $('#<?php echo $navigation_holder;?>');
    if (target.length) {
        $('html,body').animate({
            scrollTop: target.offset().top
        }, 500);
        return false;
		
    }
	if ($.fn.effect) { $('#<?php echo $navigation_holder;?>').effect('shake'); }
});
$("#upload_account").click(function(){
	$("#upload_modal").modal("show");
});

</script>
 
<script>

$(".currency_selected").click(function(){
	currency = $(this).text();
	currency_value = $("#currency_value").text();
	currency_sign = $(this).attr('id');
			myFunctionRound(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign) ;
});

$(document).ready(function(){
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

   $("#search_row").click(function(){
		  $("#myInputTextField").toggle();

   });
   					 oTable2 = $('#order-listing').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#myInputTextField').keyup(function(){
      oTable2.search($(this).val()).draw() ;
}); 
});

 $(document).ready(function(){
	 

    // Dropzone init handled above (srSafeDropzone with upload hooks)
    });
	
</script>
