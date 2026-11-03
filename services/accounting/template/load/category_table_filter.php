

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


?>
<style>
.badge {
  cursor: pointer;
}
</style>

<?php
include '../connect/connect.php';
include 'includes/category_table_filter_vehicle.php';

$db_up = cargo_db();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); // for errors during PHP's startup
error_reporting(E_ALL);
$category_checked ="";
$category_no = $_POST['category_no'];
$vehicle_category = $_POST['vehicle_category'];
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

if($vehicle_category!=""){
$vehicle_category_query = " WHERE name='$vehicle_category' ";
}else{
$vehicle_category_query ="";	
}



?>

		              <div class="az-content-breadcrumb lh-1 mg-b-10">
            <span style="font-size:12px;"><span id="start_date_holder"><?php echo $start_date_word;?></span>  <span id="end_date_holder"><?php echo $end_date_word ;?></span></span>
            
          </div>

<table style="margin-bottom:100px; border:none;"id="order-listing" class="table" cellspacing="0" width="100%">
 

<thead>
<tr class="bg-primary text-white" style="font-color:grey;">
  
<td>No</td>
<td>Group</td>
<td>Vehicle</td>
<td>Particular</td>
<td>Occurance</td>
<td>Amount</td>
 
</tr>

</thead>
<body>
<?php
$id_particular = 0;
$select_category=mysqli_query($db,"SELECT  ROW_NUMBER() OVER (order by id desc )
 row_num,name FROM   vehicle_category  $vehicle_category_query  ");
$select_num_category=mysqli_num_rows($select_category);
if($select_num_category !=0){
WHILE($get_category=mysqli_fetch_array($select_category)){
$name =$get_category['name']; 
$row_num_category =$get_category['row_num']; 

$count = 0;




$category = "";

$category_no_array = explode(",", $category_no);

if ($category_no!= "") {
$count_row = 0;
$grand_total_amount_combine = 0;
foreach ($category_no_array as $value) {

$count ++;


$select=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id desc )
 row_num, name $category_query_selection FROM   $category_query   where id='$value'"  );
$select_num=mysqli_num_rows($select);
if($select_num!=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $category_name =$get['name']; 
			if($category_properites!="category"){
    	    $category_checked =$get['category']; 
}else{
  $category_checked = $get['name']; 
}
	}}

$category_query_function = category_query_function($category_name,$category_properites,$category_checked);

?>

<?php
$total_amount =0;
$total_occurance =0;

$select_vehicle=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id asc  )row_num,  name,id FROM   vehicle_particular_details WHERE category_name='$name'  ");
$select_num_vehicle=mysqli_num_rows($select_vehicle);
if($select_num_vehicle !=0){
WHILE($get_vehicle=mysqli_fetch_array($select_vehicle)){
$vehicle_name =$get_vehicle['name']; 
$row_num_vehicle =$get_vehicle['row_num']; 
$id_particular ++; 
$child_tr=array();
$exp_array=array();
$amount_return = total_category($db,$vehicle_name,$category_name,$selected_currency,$start_date,$end_date,$category_query_function,$sum,$child_tr,$id_particular,$exp_array);

$total_occurance +=$sum;
$amount = array_sum($exp_array);
$total_amount += $amount;
?>

<tr>
<td ><?php echo (($count == 1) AND($row_num_vehicle == 1)) ? "($row_num_category)" : '';?></td>
<td ><strong><?php echo (($count == 1) AND($row_num_vehicle == 1)) ? $name : '';?></strong></td>
<td ><img src="/accounts/img/extra/right_arrow.png"class="arrow_select"data-open_id="<?php echo $id_particular;?>"><?php echo $vehicle_name ;?></td>
<td ><?php echo $category_name;?></td>
<td ><?php  echo ($sum == 0) ? "-" : "".$sum."";?></td>
<td ><?php  echo ($amount == 0) ? "-" : "".number_format($amount)."";?></td>

</tr>
<?php

foreach ($child_tr as $tr_row) {
echo "$tr_row";

}
}}
$grand_total_amount += $total_amount;
$grand_occurance += $total_occurance;
?>
<tr>
<td ></td>
<td ></td>
<td ></td>
<td style="border-top:2px solid black;border-bottom:2px solid black;"><strong>Total</strong></td>
<td style="border-top:2px solid black;border-bottom:2px solid black;"><strong><?php  echo ($total_occurance == 0) ? "-" : "".$total_occurance."";?></strong></td>
<td style="border-top:2px solid black;border-bottom:2px solid black;"><strong><?php  echo ($total_amount == 0) ? "-" : "".number_format($total_amount)."";?></strong></td>
</tr>
<?php

}
}else{
?>

<tr>
<td ></td>
<td ></td>
<td ></td>
<td ><strong class="" >Particular not selected</strong> <i class="fa fa-fw fa-exclamation-triangle text-warning"></i></td>
<td ><strong class="" >Particular not selected</strong> <i class="fa fa-fw fa-exclamation-triangle text-warning"></i></td>
<td ></td>

</tr>
<?php
}
?>


<?php

 }}
?>
</body>
<tfoot>

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


?>
<script>
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
</script>