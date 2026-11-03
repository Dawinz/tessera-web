

<?php
session_start();
$username_signed=$_SESSION['username'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now_start = date('m/01/Y', time());
$date_now_end = date('m/t/Y', time());
$date_today = date('Y-m-d', time());
$new_date_now = date("m/d/Y", strtotime($date_today));
$grand_total_amount =0;
$grand_occurance  =0;
$category_name  ="";
$expenses = 0;
?>
<style>
.badge {
  cursor: pointer;
}
</style>

<?php
include '../connect/connect.php';
include 'includes/category_table_filter_vehicle_single.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); // for errors during PHP's startup
error_reporting(E_ALL);
$category_checked ="";
$category_no = $_POST['category_no'];
$vehicle = $_POST['vehicle_category'];
$category_properites = $_POST['category_properites'];
$start_date =  date("Y-m-d", strtotime($_POST['start_date']));
$end_date =  date("Y-m-d", strtotime($_POST['end_date']));
$selected_currency = $_POST['selected_currency'];
$select_route_currency=mysqli_query($db,"SELECT symbol FROM   currency   where name ='$selected_currency'  ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
WHILE($get=mysqli_fetch_array($select_route_currency)){
    	    $symbol =$get['symbol']; 
 }}

$start_date_word =  date("jS/M/Y", strtotime("$start_date"));
$end_date_word =  date("jS/M/Y", strtotime("$end_date"));


if($category_properites=="category"){
$category_query = "particular_category";
$category_query_selection = "";
}else{
$category_query = "particulars";
$category_query_selection = ",category";
}

if($vehicle!=""){
$vehicle_category_query = " WHERE name='$vehicle' ";
}else{
$vehicle_category_query ="";	
}

//echo $category_query;

$category_no_array = explode(",", $category_no);



?>

		              <div class="az-content-breadcrumb lh-1 mg-b-10">
            <span style="font-size:12px;"><span id="start_date_holder"><?php echo $start_date_word;?></span>  <span id="end_date_holder"><?php echo $end_date_word ;?></span></span>
            
          </div>

<table style="margin-bottom:100px; border:none;"id="order-listing" class="table" cellspacing="0" width="100%">
 

<thead>
<tr class="bg-primary text-white" style="font-color:grey;">
  
<td>No</td>
<td>Trip Date</td>
<td>Particular</td>
<td>Vehicle</td>
<td>Occurance</td>
<td>Amount</td>
 
</tr>

</thead>
<tbody>
<?php
$count = 0;
$grand_total_amount = 0;
$grand_total_occurance = 0;
foreach ($category_no_array as $particular) {
	$count ++;
	$total_expenses_singel = 0;
	$total_occurance = 0;

?>


<?php


$select_particular=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id desc )
 row_num, name $category_query_selection FROM   $category_query   where id='$particular'"  );
$select_num_particular=mysqli_num_rows($select_particular);
if($select_num_particular!=0){
   	WHILE($get_particular=mysqli_fetch_array($select_particular)){
    	    $category_name =$get_particular['name']; 
			if($category_properites!="category"){
    	    $category_checked =$get_particular['category']; 
}else{
  $category_checked = $get_particular['name']; 
}
	}}

$category_query_function = category_query_function($category_name,$category_properites,$category_checked);

//echo  $category_query_function;
$select=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by trip_date asc )
 row_num, name,route_name,trip_date  FROM   vehicle_account where trip_date >= '$start_date' AND trip_date < '$end_date' and bus='$vehicle'  $category_query_function and expenses !='0' group by trip_date "  );
$select_num=mysqli_num_rows($select);
if($select_num!=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	
    	    $row_num_first =$get['row_num']; 
    	    $trip_date =$get['trip_date']; 
			$amount = total_category($db,$vehicle,$category_name,$selected_currency,$trip_date,$category_query_function,$sum);
			$id_holder = "$row_num_first$count";
			$total_expenses_singel +=$amount;
			$total_occurance +=$sum;
?>
<tr>
<td><?php echo  ($row_num_first == 1) ? "$count" : ""; ?></td>
<td><img src="/accounts/img/extra/right_arrow.png"class="arrow_select"data-open_id="<?php echo $id_holder;?>"><?php echo "$trip_date $id_holder"; ?></td>
<td><?php echo $category_name;?></td>
<td><?php echo $vehicle;?></td>
<td><?php echo $sum;?></td>
<td><?php echo "".number_format($amount)." $symbol";?></td>

</tr>

<?php
$select_trip_date=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id desc )
 row_num, name,route_name,trip_date,point,expenses,current_route,id,category  FROM   vehicle_account where trip_date = '$trip_date' and   bus='$vehicle' $category_query_function  and expenses !='0' "  );
$select_num_trip_date=mysqli_num_rows($select_trip_date);
if($select_num_trip_date!=0){
   	WHILE($get_trip_date=mysqli_fetch_array($select_trip_date)){
    	    $route_name =$get_trip_date['route_name']; 
    	    $expenses =$get_trip_date['expenses']; 
    	    $point =$get_trip_date['point']; 
    	    $current_route =$get_trip_date['current_route']; 
    	    $id_manifest =$get_trip_date['id']; 
    	    $name_child =$get_trip_date['name']; 
    	    $category =$get_trip_date['category']; 
            $expenses = exp_currency($db,$expenses,$selected_currency,$current_route);
			
?>

<tr style="display:none;" class="child_row<?php echo $id_holder;?>">
<td></td>
<td><a href="manifest_view.php?route_name=<?php echo $route_name;?>&bus=<?php echo $vehicle;?>&trip_date=<?php echo $trip_date?>&checked=&main_page=manifest.php&link=<?php echo $id_manifest;?>" style="color:black;"><u><?php echo "$route_name<strong>($point)</strong> ";?></a></td>
<td><?php echo  "$category - $name_child "; ?></td>
<td></td>
<td></td>
<td><?php echo "".number_format( $expenses )." $symbol"?></td>

</tr>
<?php

	}}

?><?php


	}}
	$grand_total_amount +=$total_expenses_singel;
	$grand_total_occurance +=$total_occurance;
?>
<tr >
<td></td>
<td></td>
<td></td>
<td><strong>Total</strong></td>
<td><strong><?php echo $total_occurance;?></strong></td>
<td><strong><?php echo "".number_format( $total_expenses_singel )." $symbol"?></strong></td>

</tr>
<?php

}
?>
</tbody>
<tfoot>
	<tr >
<td></td>
<td></td>
<td></td>

<td><strong>Total</strong></td>
<td><strong><?php echo $grand_total_occurance;?></strong></td>
<td><strong><?php echo "".number_format($grand_total_amount)."".$symbol."";?></strong></td>

</tr>
</tfoot>
</table>
<?php
function category_query_function($name,$category_properites,$category_checked){
  
if($category_properites!="category"){
$category_query_properies = " AND name = '$name' and category='$category_checked'  ";
}else{
$category_query_properies = "AND  category='$category_checked'  ";
}
return $category_query_properies;
}
function exp_currency($db,$expenses,$selected_currency,$current_route){

	$total_expenses =0;
           
$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}

 if($selected_currency == $currency_curent){
$total_expenses += $expenses;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_expenses += $expenses*$currency_value;
}


 

return $total_expenses;

}

?>
<script>

  	
	 var table = new DataTable('#order-listing', {
		   lengthMenu: [100],
       	   searching: false,

		 				   "language": {

		
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
   $(".arrow_select").click(function(){
	open_id = $(this).data("open_id");

   var img = $(this).attr('src');

    if (img === '/accounts/img/extra/right_arrow.png') {
		
    $(this).attr('src', '/accounts/img/extra/down_arrow.png');
			$(".child_row"+open_id).slideDown("slow");

    } else {
         $(this).attr('src', '/accounts/img/extra/right_arrow.png');
			$(".child_row"+open_id).hide();

    }


	//$(".child_row"+open_id).show();
   })
</script>
