<?php
session_start();
$username_signed=$_SESSION['username'];
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now_start = date('m/01/Y', time());
$date_now_end = date('m/t/Y', time());
$date_today = date('Y-m-d', time());
$new_date_now = date("m/d/Y", strtotime($date_today));
?>
<style>
.badge {
  cursor: pointer;
}
</style>

<?php
include '../connect/connect.php';
include 'includes/category_table.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); // for errors during PHP's startup
error_reporting(E_ALL);
$category_checked ="";
$category_no = $_POST['category_no'];
$route_name_full = $_POST['routes'];
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
$grand_total_amount = 0;

if($category_properites=="category"){
$category_query = "particular_category";
$category_query_selection = "";
}else{
$category_query = "particulars";
$category_query_selection = ",category";
}


?>

		              <div class="az-content-breadcrumb lh-1 mg-b-10">
            <span style="font-size:12px;"><span id="start_date_holder"><?php echo $start_date_word;?></span>  <span id="end_date_holder"><?php echo $end_date_word ;?></span></span>
            
          </div>

<table style="margin-bottom:100px; border:none;"id="order-listing" class="table" cellspacing="0" width="100%">
 

<thead>
<tr class="bg-primary text-white" style="font-color:grey;">
  
<td>No</td><td>Name</td><td>Route</td><td>Ammount</td>
 
</tr>

</thead>
<body>
<?php
$category = "";
$category_no_array = explode(",", $category_no);
$count_row = 0;
$grand_total_amount_combine = 0;
foreach ($category_no_array as $value) {

  $name="";
$select=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id desc )
 row_num, name $category_query_selection FROM   $category_query   where id='$value'"  );
$select_num=mysqli_num_rows($select);
if($select_num!=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $row_num =$get['row_num']; 
    	    $name =$get['name']; 
          $count_row = $count_row+1;
if($category_properites!="category"){
    	    $category_checked =$get['category']; 
}else{
  $category_checked = $get['name']; 
}

         $category_query_function =  category_query_function($name,$category_properites,$category_checked);
       
?>

<?php
$grand_total_amount = 0;

$select_route=mysqli_query($db,"SELECT ROW_NUMBER() OVER (order by id asc )
 row_num,route_name  FROM  assign_route where $route_name_full username='$username_signed' and status='main route' order by id asc ");
$select_num_route=mysqli_num_rows($select_route);
if($select_num_route !=0){
   	WHILE($get_route=mysqli_fetch_array($select_route)){
    	    $row_num_route =$get_route['row_num'];  
    	    $route_name =$get_route['route_name'];  

          $total_amount = total_category($db,$name,$selected_currency,$route_name,$start_date,$end_date,$category_query_function );
	?>
  <tr class="bg-primary text-white" style="font-color:grey; <?php if($total_amount==0){echo "display:none;";}?>">
  
<td><?php echo ($row_num_route==1) ? "($count_row)" : "";?></td><td><?php echo ($row_num_route==1) ? "$name" : "";?></td>
<td><a style="color:black;"href="particular_single_page.php?route_name=<?php echo $route_name;?>&start_date=<?php echo $start_date;?>&end_date=<?php echo $end_date;?>&category_query_function=<?php echo $category_query_function?>&name=<?php echo $name;?>&selected_currency=<?php echo $selected_currency;?> "><u><?php echo $route_name;?></u></a></td><td><?php echo "".number_format($total_amount)."".$symbol."";?></td>
 
</tr>
  <?php
	$grand_total_amount += $total_amount;
  		
	}
}

    }}
    $grand_total_amount_combine +=$grand_total_amount;

?>
<tr>
<td> </td><td></td><td><strong>Total Amount</strong></td> <td><strong><?php echo  "".number_format( $grand_total_amount)." ".$symbol."";?></strong></td>
  </tr>
<?php

}

?>
<tr style="border-top:2px solid black;border-bottom:2px solid black;">
<td> </td><td></td><td><strong>Grand Total Amount</strong></td> <td><strong><?php echo "".number_format( $grand_total_amount_combine)." ".$symbol."";?></strong></td>
  </tr>
</body>
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