<?php
session_start();
$username_loged=$_SESSION['username'];
?>
<style>
#change_color_red { color: #b42318; font-weight: 600; }
#change_color_grey { color: #74788d; }
.sr-ledger-toolbar .nav-link { cursor: pointer; }
.dt-search { display: none; }
#myInputTextField { max-width: 280px; margin-left: auto; }
.tooltiptext {
  display: none;
  width: 120px;
  background: #212529;
  color: #fff;
  text-align: center;
  border-radius: 0.35rem;
  padding: 0.35rem 0.5rem;
  position: absolute;
  z-index: 20;
  margin-left: 0.5rem;
  box-shadow: 0 2px 8px rgba(0,0,0,.15);
  font-size: 0.75rem;
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

$referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
$refPath = (string) (parse_url($referer, PHP_URL_PATH) ?: '');
$this_page = pathinfo($refPath, PATHINFO_FILENAME);
$select_num=0;
$select_num_mainfest_point =0;
  $month = $_POST['month'] ?? '';
  $year = $_POST['year'] ?? '';
  $route = $_POST['route'] ?? '';
  $trip_date = $_POST['trip_date'] ?? '';
  $bus = $_POST['bus'] ?? '';
  $route_start = $_POST['point'] ?? '';
  $start = $_POST['start'] ?? '';
  $route_name = $_POST['route_name'] ?? '';
  $currency_value_array = $_POST['currency_value'] ?? '';
$currency_selected = $_POST['currency'] ?? '';
  $currency_default = $_POST['currency_sign'] ?? '';
  $level = $_POST['level'] ?? '';
  $trip_date_select = $trip_date;


if($this_page!="manifest_view.php"){
	$limit = "limit 1";
}else{
	$limit  = "";
}



  include '../connect/connect.php';
  require_once __DIR__ . '/../includes/assign_route_points.php';
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
              'include_summary' => true,
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
							<?php
if($this_page!="manifest_view.php"){
	echo '<th scope="col">Action</th>';
} else {
	echo '<th scope="col">Uploader</th>';
}
						?>
                          </tr>
                        </thead>
                        <tbody>
 <?php
$to_pay_vec =0;
$paid_vec = 0;
$amount_vec = 0;
function seen_array($checked_array,$route_start) {
  $new_array = explode(",", "$checked_array");
	  if (($key = array_search($route_start, $new_array)) !== false) {
		  echo "id='change_color_red'";

	  }else{
		  echo "id='change_color_grey'";

	  }
}

function seen_array_lock($checked_array,$route_start) {
  $new_array = explode(",", "$checked_array");
	  if (($key = array_search($route_start, $new_array)) !== false) {
	
?>
<script>

$(".show_btn").attr("disabled", true);
$(".show_btn_exp").attr("disabled", true);
$(".update_dis").attr("disabled", true);
$(".delete_dis").attr("disabled", true);
</script>
<?php
	  }else{
		  ?>
		  <script>
		  
$(".show_btn").attr("disabled", false);
$(".show_btn_exp").attr("disabled", false);
$(".update_dis").attr("disabled", false);
$(".delete_dis").attr("disabled", false);
		  
		  </script>
		  <?php

	  }
}

$total_first_income_final = 0;	
$total_first_expenses_final = 0;
$select_m=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by trip_date desc ) row_num, trip_date,bus,to_be,paid,checked,checked_count FROM   manifest   where route_name ='$route'  and bus='$bus' and trip_date='$trip_date'");
$select_num_m=mysqli_num_rows($select_m);
if($select_num_m !=0){
   	WHILE($get_m=mysqli_fetch_array($select_m)){
    	    $trip_date =$get_m['trip_date']; 
    	    //$bus =$get['bus']; 
    	    $paid =$get_m['paid']; 
    	    $to_be =$get_m['to_be']; 
	    	    $row_num_manifest =$get_m['row_num'];
				   $checked =$get_m['checked']; 
    	    $checked_count =$get_m['checked_count']; 

$checked_array = $checked;
seen_array_lock($checked_array,$route_start);
$select=mysqli_query($db,"SELECT to_be,paid,amount FROM   route_manifest   where bus ='$bus' and trip_date='$trip_date' and location='$route_name'");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $to_pay_vec =$get['to_be']; 
    	    $paid_vec =$get['paid']; 
    	    $amount_vec =$get['amount']; 
	}
	}

	
	$new_to_pay_vec = $to_pay_vec ;
		$new_paid_vec = $paid_vec ;	
	
	$my_string = '{'.$currency_value_array.'}';
    $my_array = json_decode($my_string);
	foreach ($my_array as $x => $value) {
	$currency_value_select = "$currency_main_route-$currency_selected";

	if($x==$currency_value_select){
		if($currency_selected !=$currency_main_route){
		$new_to_pay_vec = $to_pay_vec * $value;
		$new_paid_vec = $paid_vec * $value;

	
		}else{
			
		}

	}else{

	}
				}
	
	
	
$count = 0;
$total_income =0;
$total_expenses =0;


	?>
		<tr  >
		<td></td>
		<td>
		<?php 
		if($trip_date_select==$trip_date){
			?>
		<div id="navigate_hr"><?php echo ' <div class="badge badge-success ms-auto">'.$bus.'</div>'; if($row_num_manifest !=1){$navigation_holder="navigate_hr";}?></div>

			<?php
		}

		?>
		</td>
		<td>
			<?php
		if($trip_date_select==$trip_date){
			?>
		<div ><?php  if ($level=="Super Administrator"){
		?>
		 <div <?php seen_array($checked_array,$route_start); ?> class="checked_update<?php echo $row_num_manifest;?>"   ><i class="far fa-eye"></i> </div>
		
		 
		<?php
			
			}?></div>

			<?php
		}

		?>
		</td>
		
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		
		</tr>
	<?php
  $select_num_paticular =0;
  $assign_points = tessera_assign_route_points($db, (string) $username_loged, (string) $route, $selected_point);
  $select_num_point = count($assign_points);
   if($select_num_point !=0){
   	foreach ($assign_points as $get_point) {
    	    $point =$get_point['point'];
    	    $row_num =$get_point['row_num'];
    	    $id =$get_point['id'];
    	    $main_route =$get_point['main_route'];
    	    $current_route =$get_point['route_name'];
			
 $end_point =$get_point['end_point'];
 $total_first_income = 0;	
 $total_first_expenses = 0;	
 $total_expneses_affect =0;	
 $total_expneses_affect_paid =0;	
 $count = $row_num + 1;
 $select_paticular=mysqli_query($db," SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num,
 category,id,name,income,expenses,username,date_insert,time_insert,detail,time_insert,affect,currency,currency_sign,price,fullname_user
 FROM 
 vehicle_account  WHERE   route_name='$route' and point='$point' and trip_date='$trip_date'  and bus='$bus'
  ORDER BY 
	id ASC ");
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
    	    $fullname_user =$get_paticular['fullname_user'];
		
		$total_first_income +=$income;
		$total_first_expenses +=$expenses;
				

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

			}	if($affect=="Paid"){
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
				$total_expneses_affect += $new_value_exp;	

			}	else if($affect=="Paid"){
				$total_expneses_affect_paid += $new_value_exp;	

			}

		}else{
				
		}

	}else{

	}
				}


		?>
	
		<tr class="">
		
		<td >
		<strong style="color:#575757;"><?php  if($row_num_entries==1){ if($row_num_manifest >=$row_num){echo "<span>$row_num_manifest <img src='./icon/right-arrow.png'></span>";} }else{}?></strong>
		</td>
		 <td><strong style="color:#575757;"><?php  if($row_num_entries==1){ echo $trip_date;}else{}?></strong></td>
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
                            <td style="white-space: nowrap"id="affect<?php echo $id;?>" style=""><span style="<?php if($affect=="To be Paid"){ echo "color:red;";}?>"><?php echo $affect;?></span></td>
                          <td>
  <?php
  if($this_page!="manifest_view.php"){
  ?>
		  <div class="btn-group btn-group-sm">
              <button type="button" class="btn btn-soft-primary sr-act-btn update_dis js-ledger-edit edit<?php echo (int) $id; ?>"
                data-id="<?php echo (int) $id; ?>"
                data-category="<?php echo htmlspecialchars((string) $category, ENT_QUOTES, 'UTF-8'); ?>"
                data-particular="<?php echo htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'); ?>"
                data-currency="<?php echo htmlspecialchars((string) $currency, ENT_QUOTES, 'UTF-8'); ?>"
                data-amount="<?php echo htmlspecialchars((string) ($price ?? (($income > 0) ? $income : $expenses)), ENT_QUOTES, 'UTF-8'); ?>"
                data-detail="<?php echo htmlspecialchars((string) $detail, ENT_QUOTES, 'UTF-8'); ?>"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"
                title="Update"><i class="mdi mdi-pencil"></i></button>
              <button type="button" class="btn btn-soft-danger sr-act-btn delete_dis js-ledger-delete"
                data-id="<?php echo (int) $id; ?>" title="Delete"><i class="mdi mdi-delete"></i></button>
            </div>
  <?php	
  }else{
	echo $fullname_user;
	?>
	
	<?php

 }
 ?>
				
			
			</td>
		</tr>
		<?php
		
		
}}
$total_first_income_final +=$total_income;
$total_first_expenses_final +=$total_expenses;
$total_paid_exp = $total_income-$total_expneses_affect_paid; 
?>

      <tr >
	  		
   <td></td>
                                 <td style="color:#575757;"><strong>To be Paid</strong></td>
                            <td><strong style="color:#575757;">Paid</strong></td>
                            <td style="color:#575757;"><strong>Total</strong></td>  
							<td></td>
                            
                            <td></td>
                            <td></td>
                         
                     <td></td>
                       <td style="border-top:2px solid black;border-bottom:2px solid black;"><strong>Total Income</strong></td>
                            <td style="border-top:2px solid black;border-bottom:2px solid black;"><strong><?php echo ''.number_format($total_income,2).''.$currency_default.'';?> </strong></td>
                            
                          </tr>  

						  <tr >
                 		  <td></td>
                        <td style="color:#575757;"><strong><?php if($to_pay_vec !=0){echo number_format($to_pay_vec);}else{echo "-";}?></strong></td>
                            <td style="color:#575757;"><strong><?php if($paid_vec !=0){echo number_format($paid_vec);}else{echo "-";}?></strong></td>
                            <td style="color:#575757;"><strong><?php  if($amount_vec !=0){echo number_format($amount_vec);}else{echo "-";}?></strong></td>
							<td></td>
							                            <td></td>
                            <td></td>
                            <td></td>
                          
                            <td style="border-top:2px solid black;border-bottom:2px solid black;"><strong>Total Expenses</strong></td>
                            <td style="border-top:2px solid black;border-bottom:2px solid black;"><strong><?php echo ''.number_format($total_expneses_affect_paid,2).''.$currency_default.''?></strong></td>

                       
                       
                          </tr>   
					  

						  <tr style="">
                            		<td></td>
                            		<td></td>
  <td></td>
                            <td></td>
							 <td ></td>
                        
                            <td></td>
                            <td></td>
                            <td></td>
                               <td style="border-top:2px solid black;border-bottom:2px solid black;background:green;color:white;"><strong>Actual Balance </strong></td>
                            <td style="width:150px;border-top:2px solid black;border-bottom:2px solid black;background:green;color:white;"><strong><?php echo ''.number_format($total_paid_exp,2).' '.$currency_default.'' ?></strong></td>
                          
                       
                          </tr>	 
				
<?php


}}
	}	
	
	?>
	 
	 <script>
		 $(".checked_update<?php echo $row_num_manifest;?>").click(function(){
			
			my_seen = $(this).attr('id');
			
if(my_seen=="change_color_grey"){
$(this).removeAttr('id');
$(this).attr('id', 'change_color_red');
$(".show_btn").attr("disabled", true);
$(".show_btn_exp").attr("disabled", true);
$(".update_dis").attr("disabled", true);
$(".delete_dis").attr("disabled", true);
}else{
$(this).removeAttr('id');
$(this).attr('id', 'change_color_grey');
$(".show_btn").attr("disabled", false);
$(".show_btn_exp").attr("disabled", false);
$(".update_dis").attr("disabled", false);  
$(".delete_dis").attr("disabled", false);
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
//alert(data);
    }
   });			

		 });
		 </script>
	<?php		
			

}


?>

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
	level = $("#level").text();
	
			myFunction(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) ;
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
	
	$("#usersummary").click(function(){
$("#usercharge").modal("show");
current_route = "<?php echo $route_name;?>";
trip_date = "<?php echo $trip_date;?>";
bus = "<?php echo $bus;?>";
route = "<?php echo $route;?>";

 $.ajax(
                            {
                                url: 'search/user_charge.php',
                                method: 'POST',
                                data: {
								   current_route:current_route,
								   trip_date:trip_date,
								   route:route,
								   bus:bus
								
                                },
								  beforeSend: function(){
	
            $("#load_summary").html("<p class='placeholder-glow'> <span class='placeholder col-12' style='height:600px;'></span></p>");
            
            },
                                success: function (data) {
								$("#load_summary").html(data);

               },
                                dataType: 'text'
                            }
                        );

	});

// Delegated ledger edit/delete (survives AJAX reloads; no per-row <script>).
$(document).off('click.ledgerEdit', '.js-ledger-edit').on('click.ledgerEdit', '.js-ledger-edit', function () {
	var $btn = $(this);
	var category = $btn.data('category') || '';
	var particular = $btn.data('particular') || '';
	var currency = $btn.data('currency') || '';
	var amount = $btn.data('amount') || '';
	var detail = $btn.data('detail') || '';
	var id = $btn.data('id') || '';
	$('#part_Category_update').text(category).val(category);
	$('#part_detail_update').text(particular).val(particular);
	$('#currency_update').text(currency).val(currency);
	$('.amount_income_update').val(amount);
	$('.detail_update').val(detail);
	$('.part_id').val(id);
});

$(document).off('click.ledgerDelete', '.js-ledger-delete').on('click.ledgerDelete', '.js-ledger-delete', function () {
	var $btn = $(this);
	var id = $btn.data('id');
	if (!id) {
		return;
	}
	var doDelete = function () {
		$btn.prop('disabled', true);
		$.ajax({
			url: 'delete/delete_entry.php',
			method: 'POST',
			data: { id: id },
			success: function () {
				$btn.prop('disabled', false);
				if (window.TesseraStates) {
					TesseraStates.toastOk('Entry deleted', 'The ledger line was removed.');
				}
				if (typeof myFunction === 'function') {
					myFunction(month, year, route, trip_date, bus, point, start, route_name, currency_value, currency, currency_sign, level);
				}
			},
			error: function () {
				$btn.prop('disabled', false);
				if (window.TesseraStates) {
					TesseraStates.toastErr('Delete failed', 'Could not delete this entry.');
				} else {
					alert('Could not delete this entry.');
				}
			}
		});
	};
	if (window.Swal) {
		Swal.fire({
			title: 'Delete this entry?',
			text: 'This cannot be undone.',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it'
		}).then(function (result) {
			if (result.isConfirmed) {
				doDelete();
			}
		});
	} else if (window.confirm('Delete this entry?')) {
		doDelete();
	}
});
</script>
