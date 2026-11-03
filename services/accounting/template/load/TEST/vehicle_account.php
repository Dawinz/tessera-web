<link rel="stylesheet" href="/accounts/dropzone/dropzone.css">
<style>
.dropzone{
	margin-bottom:40px;
	margin-top:40px;
	margin-left:250px;
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
}
#change_color_red{
	
	color:red;
}
#change_color_grey{
	
	color:#7987A1;
}
</style>
<div id="blinker"></div>
<?php

$point = $_GET['point'];
$trip_date = $_GET['trip_date'];
$route_name = $_GET['route_name'];

$bus = $_GET['bus'];
$main_route = $_GET['main_route'];
$current_route = $_GET['current_route'];
$end_point = $_GET['end_point'];
$row_num_route = $_GET['row_num'];
$route_name = preg_replace('/_/',' ', $route_name);
$point = preg_replace('/_/',' ', $point);
$main_route = preg_replace('/_/',' ', $main_route);
$current_route_currency = preg_replace('/_/',' ', $current_route);
$end_point = preg_replace('/_/',' ', $end_point);
$to_be =0;
$paid =0;
   
if($point ==""){
	$point_sql = "";
}else{
	$point_sql = "and point='$point'";
}
 
include '../connect/connect.php';
include '../drop_down/income.php';
include '../drop_down/expenses.php';
include '../drop_down/currency.php';
include '../drop_down/all_particular.php';





$select_ticket_manifest=mysqli_query($db,"SELECT * FROM   manifest_entries   where bus ='$bus' and trip_date='$trip_date' and route_name ='$current_route_currency'  ");
$select_num_ticket_manifest=mysqli_num_rows($select_ticket_manifest);
if($select_num_ticket_manifest !=0){
   	WHILE($get_ticket_manifest=mysqli_fetch_array($select_ticket_manifest)){
    	    $to_be =$get_ticket_manifest['to_be']; 
    	    $paid =$get_ticket_manifest['paid']; 
    	  
	}
}
$amount = $to_be + $paid;
$select_currency=mysqli_query($db,"SELECT Currency,value_currency FROM   route_currency   where route_name ='$main_route'  ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $currency_main_route =$get_currency['Currency']; 
    	    $currency_value =$get_currency['value_currency']; 
			
	}
}

$currency_array = array();
$select_currency_select=mysqli_query($db,"SELECT name,value_no FROM   currency  ");
$select_num_currency_select=mysqli_num_rows($select_currency_select);
if($select_num_currency_select !=0){
	
   	WHILE($get_currency_select=mysqli_fetch_array($select_currency_select)){
    	    $currency_select_name =$get_currency_select['name']; 
    	    $currency_select_value_no =$get_currency_select['value_no']; 
			$currency_array[$currency_select_name] =  $currency_select_value_no;
			
	}
	
}

$select_route_name_current=mysqli_query($db,"SELECT Currency FROM  route_currency  where  route_name='$current_route_currency' LIMIT 1  ");
$select_num_route_name_current=mysqli_num_rows($select_route_name_current);
if($select_num_route_name_current !=0){
   	WHILE($get_route_name_current=mysqli_fetch_array($select_route_name_current)){
    	    $Currency_current =$get_route_name_current['Currency']; 
			

}}


$select_currency_value=mysqli_query($db,"SELECT value_no FROM   currency  WHERE name ='$Currency_current'");
$select_num_currency_value=mysqli_num_rows($select_currency_value);
if($select_num_currency_value !=0){
	
   	WHILE($get_currency_value=mysqli_fetch_array($select_currency_value)){
    	    $currency_select_value_no =$get_currency_value['value_no']; 

			
	}
	
}

$currency_sign = "";

$select_currency=mysqli_query($db,"SELECT checked,checked_count FROM   manifest  WHERE trip_date = '$trip_date' and bus='$bus'  ");
$select_num_currency=mysqli_num_rows($select_currency);

   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $checked =$get_currency['checked']; 
    	    $checked_count =$get_currency['checked_count']; 
			
	}



$checked_array = $checked;


function seen_array($checked_array,$point) {
  $new_array = explode(",", "$checked_array");
	  if (($key = array_search($point, $new_array)) !== false) {
		  echo "change_color_red";
	  }else{
		  echo "change_color_grey";
	  }
}




include 'includes/canvas_edit.php';

?> 

<span class="active_currency"style="display:none;"><?php echo "$Currency_current";?></span>
<span class="active_currency_value"style="display:none;"><?php echo "$currency_select_value_no";?></span>




 
 <div id ="upload_modal"class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
     <div class="container" > 
	 <div class="center" style="align-content: center">
    <div class="dropzone ">
        <div class="dz-message needsclick">
            <h1>Drop files here or click to upload.</h1>
        </div>
    </div>
    </div>
</div>
    </div>
  </div>
</div>

<div id="transfer_option"class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transfer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
          <div class="row row-sm mg-b-20">
            <div class="col-lg">
              <p class="mg-b-10">Trip date</p>
              <select class="form-control select2-no-search" id="trip_dates">
              <option>loading...</option>
              </select>
            </div>     <div class="col-lg">
              <p class="mg-b-10">Vehicles</p>
              <select class="form-control select2-no-search" id="transfer_bus">
                <option >Choose Bus Name</option>
              
              </select>
            </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="update_transfer"><span id="updated_tran_text">Save changes</span></button>
      </div>
    </div>
  </div>
</div>
 
	<?php include 'includes/expenses_table.php';?>
	<?php include 'includes/income_table.php';?>

 <div class=""style="padding-left:6px;">
           <div >

                    <div class="card-header card-header-tab">
                  <label class="az-content-label"><?php if($point==""){ echo "Round Trip";}else{echo $point;}?></label>
                  <p class="az-content-text">The first currency is the default Currency,Currency can be changed by selecting your preferred currency.</p>
				 
				  <div class="az-contact-action"style="position:absolute;top:30px;margin-right:20px;"> 
              <a class="mark_manifest" href="#"><i class="bx bx-show eyeclass" id="<?php seen_array($checked_array,$point); ?>"></i> Check If Analysed</a>
           </div>
</div>

</div>
          
          </div><!-- az-mail-list-header -->
          <div class="az-mail-options">
           
			      <nav class="nav nav-pills">
				  <?php
				  function select_currency($name,$current_route_currency,$db) {
					//  echo "$name $current_route_currency";
$select_route_name=mysqli_query($db,"SELECT Currency FROM  route_currency   where  route_name='$current_route_currency' LIMIT 1 ");
$select_num_route_name=mysqli_num_rows($select_route_name);
if($select_num_route_name !=0){
   	WHILE($get_route_name=mysqli_fetch_array($select_route_name)){
    	    $Currency =$get_route_name['Currency']; 
			

	}

if($Currency==$name){
	echo "active";
}else{
	echo "";
}

}else{
	echo "";
	
}
				  }			  
$select_currency=mysqli_query($db,"SELECT name,value_no,symbol FROM   currency    ");
$select_num_currency=mysqli_num_rows($select_currency);
if($select_num_currency !=0){
   	WHILE($get_currency=mysqli_fetch_array($select_currency)){
    	    $name =$get_currency['name']; 
    	    $value_no =$get_currency['value_no']; 
    	    $symbol =$get_currency['symbol']; 
			

			?>
<a class="currency nav-link <?php  select_currency($name,$current_route_currency,$db);?>" data-bs-toggle="tab" href="#" id="<?php echo $value_no;?>"><?php echo $name;?></a>
<span style="display:none;"class="symbol<?php echo $name;?>"><?php echo $symbol;?></span>			
			<?php
			
			

	}
}





			 ?>
				  
				  
                    
                  </nav>
            <div class="btn-group">
              <button class="btn btn-light" id="copy_table"><i class="fa fa-copy" aria-hidden="true"></i></button>
              <button class="btn btn-light" id="print_table"><i class="fa fa-print" aria-hidden="true"></i></button>
              <button class="btn btn-light " id="excel_table"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
              <button class="btn btn-light " id="csv_table"  ><i class="bi bi-filetype-csv"></i>csv</button>
              <button class="btn btn-light " id="pdf_table"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
            
            </div><!-- btn-group -->
          </div><!-- az-mail-options -->


          <div class="az-mail-list">
		     <div class="btn-group" style="position: absolute;z-index: 10;margin-left:5px;">
              <button class="btn btn-dark " id="reload"><span id="reload_text">Reload</span> <i class="fa fa-refresh" aria-hidden="true"></i></button>
              <button class="btn btn-info" id="upload"style="color:#f9f9f9;">Upload <i class="fa fa-upload" aria-hidden="true"></i></button>
             
            </div>  

			<div class="btn-group check_buttons" style="position: absolute;z-index: 10;margin-left:230px;display:none;">
              <button class="btn btn-danger " id="delete_all"><span id="delete_text">Delete</span> <i class="fa fa-trash" aria-hidden="true"></i></button>
              <button class="btn btn-warning" id="transfer_all"style="color:#f9f9f9;"><span id="transfer_text">Transfer</span>  <i class="fa fa-upload" aria-hidden="true"></i></button>
             
            </div>
			
			<?php
			include 'includes/reload.php';
			?>
                      <table style="margin-top:7px;"id="order-listing" class="table" cellspacing="0" width="100%">
                        <thead>
                          <tr class="bg-primary text-white">
                            
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>Amount</th>
                            <th>To Pay</th>
					      <th>Paid</th>
                            <th>Category</th>
                            <th>Particular</th>
                            <th>Income</th>
                            <th>Expenses</th>
                            <th>Detail</th>
                            <th>Affect</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
						<?php
						function css() {
							echo "style='border-bottom:1px solid black;'";
						}
			function change_color($row_num) {
  if($row_num==1){echo "style='background-color:green;color:white;'";}
}
$total_expenses =0;	
$total_income =0;	
$total_expneses_affect =0;	
$total_expneses_affect_paid =0;	
						$select_paticular=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    category,id,name,income,expenses,username,date_insert,time_insert,detail,time_insert,affect,currency,currency_sign,price

FROM 
	vehicle_account  WHERE     route_name='$route_name' $point_sql and trip_date='$trip_date'
ORDER BY 
	id ASC 
		
		");
$select_num_paticular=mysqli_num_rows($select_paticular);
if($select_num_paticular !=0){
   	WHILE($get_paticular=mysqli_fetch_array($select_paticular)){
    	    $expenses =$get_paticular['expenses'];
    	    $income =$get_paticular['income'];
    	    $category =$get_paticular['category'];
    	    $name =$get_paticular['name'];
    	    $row_num =$get_paticular['row_num'];
    	    $date_insert =$get_paticular['date_insert'];
    	    $time_insert =$get_paticular['time_insert'];
    	    $detail =$get_paticular['detail'];
    	    $username =$get_paticular['username'];
    	    $id =$get_paticular['id'];
    	    $affect =$get_paticular['affect'];
    	    $currency =$get_paticular['currency'];
    	    $currency_sign =$get_paticular['currency_sign'];
    	    $price =$get_paticular['price'];
			if($affect=="To be Paid"){
				$total_expneses_affect += $expenses;	

			}	if($affect=="paid"){
				$total_expneses_affect_paid += $expenses;	

			}
			
			
			foreach ($currency_array as $x => $y) {
 
 
 if($x ==$currency){
	 $placeholder = $y * $price;
 
 }
}
			
$total_expenses += $expenses;	
$total_income += $income;	
			?>
			<span style="display:none;"class="amount_change<?php echo $row_num?>"><?php echo $placeholder;?></span>
                          <tr id="tr_an<?php echo $id;?>">
                           <td><input type="checkbox" class="checked<?php echo $row_num;?> checked_signle" id="<?php echo $id;?>" ></td>
                            <td <?php change_color($row_num);?>><?php if($row_num==1){ if($amount!=0){echo ''.number_format($amount).'<small class="tx-secondary">'.$currency_sign.'</small>';}else{echo "-";}}?></td>
                            <td <?php change_color($row_num);?>><?php if($row_num==1){if($amount!=0){echo ''.number_format($to_be).'<small class="tx-secondary">'.$currency_sign.'</small>';}else{echo "-";}}?></td>
                            <td <?php change_color($row_num);?>><?php if($row_num==1){if($amount!=0){echo ''.number_format($paid).'<small class="tx-secondary">'.$currency_sign.'</small>';}else{echo "-";}}?></td>
                            <td><div id="category<?php echo $id;?>"><?php echo $category;?></div></td>
                            <td><div id="particular<?php echo $id;?>"><?php echo $name;?></div></td>
                            <td id="income_tr<?php echo $id;?>" <?php if($income!=0){ echo "style='background:green;color:white;'";}?>><span id="income<?php echo $id;?>" class="income_display<?php echo $row_num?>"><?php
if($income==0){
echo "-";	
}else{
echo ''.number_format($income).'</span><small class="tx-secondary" id="income_symbol'.$row_num.'">'.$currency_sign.'</small>';	
}
							?>
						
							
							<?php
	if($currency_main_route != $currency){
		?>
		<span class="tooltiptext" id="income_place<?php echo $id;?>"><?php echo number_format($placeholder);?></span>
		<script>
		$("#income_tr<?php echo $id;?>").mouseover(function(){
			$("#income_place<?php echo $id;?>").show();
		});	
		$("#income_tr<?php echo $id;?>").mouseout(function(){
			$("#income_place<?php echo $id;?>").hide();
		});
		</script>
		<?php
	}
							?>
							</td>
                            <td ><span id="expenses<?php echo $id;?>" class="expenses_display<?php echo $row_num?>"><?php if($expenses==0){
echo "-";	
}else{
echo ''.number_format($expenses).'</span><small class="tx-secondary" id="expenses_symbol'.$row_num.'">'.$currency_sign.'</small>';	
}?>

</td>
                            <td id="detail<?php echo $id;?>"><?php echo $detail;?></td>
                            <td id="affect<?php echo $id;?>"><?php echo $affect;?></td>
                          <td> <div class="btn-group">
              <button style="margin:2px;"class="btn btn-secondary text-white edit<?php echo $id;?>" id="<?php echo $currency;?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="fa fa-pencil" aria-hidden="true"></i></button>
              <button style="margin:2px;"class="btn btn-danger " id="delete<?php echo $id;?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
        
            </div></td>
	<?php
	include 'includes/update.php';
	include 'includes/delete.php';
	?>
			
                          </tr>
                   
                    <?php
}}

					?>
           <tr >
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td <?php css();?>>Total Expenses</td>
		   <td <?php css();?>><span class="total1"><?php echo number_format($total_expenses,2)?></span><span class="tplacholder1"style="display:none;"><?php echo "".$total_expenses*$currency_select_value_no."";?></span><small class="tx-secondary" id="total_sy1"><?php echo $currency_sign;?></small></td>
		   </tr>    
		   <tr>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td <?php css();?>>Total income</td>
		   <td <?php css();?>><span class="total2"><?php echo number_format($total_income,2)?></span><span class="tplacholder2" style="display:none;"><?php echo "".$total_income*$currency_select_value_no."";?></span><small class="tx-secondary" id="total_sy2"><?php echo $currency_sign;?></small></td>
		   </tr>  
		   <tr>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td <?php css();?>>Balance to be paid</td>
		   <td <?php css();?>><span class="total3"><?php $total_to_be_exp = $to_be-$total_expneses_affect; echo number_format($total_to_be_exp,2)?></span><span class="tplacholder3" style="display:none;"><?php echo "".$total_to_be_exp*$currency_select_value_no."";?></span><small class="tx-secondary" id="total_sy3"><?php echo $currency_sign;?></small></td>
		   </tr> 	
		   <tr>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td <?php css();?>>Balance Paid</td>
		   <td <?php css();?>><span class="total4"><?php $total_paid_exp = $total_income-$total_expneses_affect_paid; echo number_format($total_paid_exp,2)?> </span><span class="tplacholder4" style="display:none;"><?php echo "".$total_paid_exp*$currency_select_value_no."";?></span><small class="tx-secondary" id="total_sy4"><?php echo $currency_sign;?></small></td>
		   </tr>  <tr>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td <?php css();?>>Gross Balance</td>
		   <td <?php css();?>><span class="total5"><?php $total_gross = $to_be+$total_income-$total_expenses; echo number_format($total_gross,2)?></span><span class="tplacholder5" style="display:none;"><?php echo "".$total_gross*$currency_select_value_no."";?></span><small class="tx-secondary" id="total_sy5"><?php echo $currency_sign;?></small></td>
		   </tr>               
                        </tbody>
                      </table>
	<div id="num_row"style="display:none;"><?php echo $select_num_paticular;?></div>
          <div id="selected_checked"></div>
          <div id="checked_bus"style="display:none;"><?php echo $bus;?></div>
          <div id="checd_trip_date" style="display:none;"><?php echo $trip_date;?></div>
          <div id="checd_route" style="display:none;"><?php echo $route_name;?></div>
          <div id="rpoint" style="display:none;"><?php echo $point;?></div>
          <div id="row_num_route" style="display:none;"><?php echo $row_num_route;?></div>
          <div id="current_route_c" style="display:none;"><?php echo $current_route;?></div>
          <div id="end_point_c" style="display:none;"><?php echo $end_point;?></div>
          <div id="adfasf"></div>
		  
		  </div><!-- az-mail-list -->
		  <script src="/accounts/lib/jquery/notify.js"></script>
    <script src="/accounts/lib/jquery/notify.min.js"></script>
	    <script src="/accounts/js/template/insert/income.js"></script>
	    <script src="/accounts/js/template/check_out.js"></script>
	    <script src="/accounts/js/template/delete_all.js"></script>
	    <script src="/accounts/js/template/transfer_all.js"></script>
	    <script src="/accounts/js/template/instant_transfer.js"></script>
	    <script src="/accounts/js/template/insert/expenses.js"></script>
	    <script src="/accounts/js/template/dependence/expenses.js"></script>
	    <script src="/accounts/js/template/update/update_seen.js"></script>
<script src="/accounts/js/template/currency.js"></script>
	    <script src="/accounts/js/template/addon/currency_multiple.js"></script>
		<script src="dropzone/dropzone.js"></script>


<script>

$(document).ready(function(){
	

	    $(".dropzone").dropzone({
      url: 'upload/upload.php?trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&current_route=<?php echo $current_route;?>&route_name=<?php echo str_replace(" " , "_",  $route_name);?>&point=<?php echo str_replace(" " , "_",  $point);?>',
      width: 300,
      height: 300, 
      progressBarWidth: '100%',
      maxFileSize: '5MB',
	  
	    init: function () {
        this.on("complete", function (file) {
           $("#load_attachment").load('load/attachment.php?trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&current_route=<?php echo $current_route;?>&route_name=<?php echo str_replace(" " , "_",  $route_name);?>&point=<?php echo str_replace(" " , "_",  $point);?>');
        });
    }
	  
	  
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
   
   $("#placeholder_veh").removeClass("placeholder");
   $("#placeholder_veh").removeClass("placeholder_height");

$(".currency").click(function(){
	currency = $(this).text();
	currency_value  = $(this).attr('id');
	number_row = $("#num_row").text();
	number_row = parseInt(number_row);
	number_row = number_row + 1;
	symbol = $(".symbol"+currency).text();
	
	let text = "";
for (let i = 1; i < number_row; i++) {
	
	amount = $(".amount_change"+i).text();
	if(amount=="0"){

}else{
	amount = amount.replace(/,/g,"");
	new_amount = parseInt(amount)/parseInt(currency_value);
	
	const price = new_amount;
const formattedPrice = price.toLocaleString({ style: 'currency' });
	
	
	income_display = $(".income_display"+i).text();
	expenses_display = $(".expenses_display"+i).text();
	
 if(income_display !="-"){
	$(".income_display"+i).text(formattedPrice);
	$("#income_symbol"+i).text(symbol); 
 }else if(expenses_display !="-"){
	$(".expenses_display"+i).text(formattedPrice);
	$("#expenses_symbol"+i).text(symbol); 
 }

}
}

let text_total = "";
total_number= 6;
for (let t = 1; t < total_number; t++) {
	$("#total_sy"+t).text(symbol);
	placeholder_total = $(".tplacholder"+t).text();
	active_currency = $(".active_currencyy").text();
	active_currency_value = $(".active_currency_value").text();
		new_amount_total = parseInt(placeholder_total)/parseInt(currency_value);
	const price_total = new_amount_total;
const formattedPrice_price = price_total.toLocaleString({ style: 'currency' });
	$(".total"+t).text(formattedPrice_price);
	text_total += placeholder_total;




}
$("#adfasf").text(currency);


});




});

   $(function(){
        'use strict'

        $('#checkAll').on('click', function(){
          if($(this).is(':checked')) {
            $('.az-mail-list .ckbox input').each(function(){
              $(this).closest('.az-mail-item').addClass('selected');
              $(this).attr('checked', true);
            });

            $('.az-mail-options .btn:not(:first-child)').removeClass('disabled');
          } else {
            $('.az-mail-list .ckbox input').each(function(){
              $(this).closest('.az-mail-item').removeClass('selected');
              $(this).attr('checked', false);
            });

            $('.az-mail-options .btn:not(:first-child)').addClass('disabled');
          }
        });

        $('.az-mail-item .az-mail-checkbox input').on('click', function(){
          if($(this).is(':checked')) {
            $(this).attr('checked', false);
            $(this).closest('.az-mail-item').addClass('selected');

            $('.az-mail-options .btn:not(:first-child)').removeClass('disabled');

          } else {
            $(this).attr('checked', true);
            $(this).closest('.az-mail-item').removeClass('selected');

            if(!$('.az-mail-list .selected').length) {
              $('.az-mail-options .btn:not(:first-child)').addClass('disabled');
            }
          }
        });

        $('.az-mail-star').on('click', function(e){
          $(this).toggleClass('active');
        });

      

        $('.az-mail-compose-header a:first-child').on('click', function(e){
          e.preventDefault();
          $('.az-mail-compose').toggleClass('az-mail-compose-minimize');
        })

        $('.az-mail-compose-header a:nth-child(2)').on('click', function(e){
          e.preventDefault();
          $(this).find('.fas').toggleClass('fa-compress');
          $(this).find('.fas').toggleClass('fa-expand');
          $('.az-mail-compose').toggleClass('az-mail-compose-compress');
          $('.az-mail-compose').removeClass('az-mail-compose-minimize');
        });

        $('.az-mail-compose-header a:last-child').on('click', function(e){
          e.preventDefault();
          $('.az-mail-compose').hide(100);
          $('.az-mail-compose').removeClass('az-mail-compose-minimize');
		  alert();
        });


      });

</script>

	<script>
		$(document).ready(function(){
 
						var count = 0;
	
	function add_input_field(count)
	{
new_count = count+1;
		var html = '';

		html += '<tr>';

		html += '<td>'+new_count +'</td>';
		html += '<td><select name="part_Category[]" class="form-select part_Category" id="part_Category'+count+'" style="" data-live-search="true"><option value="Choose Category">Choose Category</option><?php echo $particular_category; ?></select></td>';
		html += '<td><select name="part_detail[]" class="form-select  part_detail" id="part_detail'+count+'" style="" data-live-search="true"><option value="Choose Particular">Choose Particular</option></select></td>';
		html += '<td><select name="part_Currency[]" class="form-select  particular_currency" id="particular_currency" style="" data-live-search="true"><?php echo $particular_Currency;?></select></td>';

		html += '<td><input name="amount[]" type="text" class="form-control value_name amount amount_income" ></td>';
		html += '<td><input name="detail[]" type="text" class="form-control value_name detail" ></td>';

		var remove_button = '';

		if(count > 0)
		{
			remove_button = '<button type="button" name="remove" class="btn btn-danger btn-sm remove_exp"><i class="fas fa-minus"></i></button>';
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

	})	
	
	
	
	
	$(document).on('change','.part_Category', function(){
my_id = $(this).attr('id')
my_id = my_id.replace(/\D/g,'');
part_category = $("#part_Category"+my_id+" option:selected" ).text();

	    $.ajax({
    url:"./dependence/fetch_income.php",
    method:"POST",
    data:{part_category:part_category},
	   beforeSend: function(){
	$("#part_detail"+my_id+" option:selected" ).text("loading...");
            
            },
    success:function(data){
$('#part_detail'+my_id+'').html(data);
    }
   })

});
	
	
	  $('.show_btn').on('click', function(e){
          e.preventDefault();
		  
		       $('#item_table').find("tr:gt(0)").remove();
$('#item_table').append(add_input_field(0));	
          $('.show_income').show();
        });	 

		$('.show_btn_exp').on('click', function(e){
          e.preventDefault();
		  
		  $('.show_exp').show();
        });
	});






		
	</script>



 <?php include 'includes/expenses_modal.php';?>       
 <?php include 'includes/document_upload.php';?>       