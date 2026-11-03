<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Tessera Accounts'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.error_class{
	border:1px solid red;
}

.select2-dropdown {  
  z-index: 10060 !important;/*1051;*/
}




</style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">
<?php
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
$vehicle = $_GET['vehicle'];
$selected_currency = base64_decode($_GET['selected_currency']);
$symbol = base64_decode($_GET['symbol']);
$category_query_function = base64_decode($_GET['category_query_function']);
$category_name = base64_decode($_GET['category_name']);
 $grand_total_final =0;
 $grand_total_expenses =0;
 $grand_total_income=0;


?>
<!-- az-header -->
<?php
include 'includes/top_header.php';
?>
    <div class="az-content pd-y-20 pd-lg-y-30 pd-xl-y-40">
      <div class="">
     
	 <?php
	 //include 'includes/left_nav_particular.php';
	 ?>
	 <!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column" style="padding:30px;">
      

          <div class="az-content-label mg-b-5">Particular Dates</div><br>

  <div class="az-dashboard-nav"> 
      

            <nav class="nav">
              <a id="copy_table2" class="nav-link this_nav" href="#"><i class="bx bx-copy"></i> Copy</a>
              <a id="print_table" class="nav-link" href="#"><i class="bx bx-printer"></i> Print</a>
              <a id="excel_table" class="nav-link" href="#"><i class="bx bx-export"></i> Excel</a>
              <a id="csv_table" class="nav-link" href="#"><i class="bi bi-filetype-csv"></i>Export to Csv</a>
              <a id="pdf_table" class="nav-link" href="#"><i class="fa fa-file-pdf-o"></i>Export to Pdf</a>

             
              <a class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
            </nav>
          </div>
  
           <table style="margin-bottom:100px; border:none;"id="order-listing" class="table" cellspacing="0" width="100%">
<thead>
<tr class="bg-primary text-white" style="font-color:grey;">
  
<td>Trip Date</td><td>Particular </td><td>Expenses </td>
 
</tr>

</thead>
<body>


<?php
$select_status=mysqli_query($db,"SELECT trip_date,route_name FROM   vehicle_account   where trip_date >= '$start_date' AND trip_date < '$end_date' and bus='$vehicle' $category_query_function group by route_name");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	  
    	    $route_name =$get_status['route_name']; 
    }}


$select_status=mysqli_query($db,"SELECT trip_date,route_name FROM   vehicle_account   where trip_date >= '$start_date' AND trip_date < '$end_date' and bus='$vehicle' $category_query_function group by trip_date");
$select_num_status=mysqli_num_rows($select_status);
if($select_num_status !=0){
   	WHILE($get_status=mysqli_fetch_array($select_status)){
    	    $trip_date =$get_status['trip_date']; 
    	    $route_name =$get_status['route_name']; 
$total_expenses =0; 
$total_income =0; 
$select=mysqli_query($db,"SELECT expenses,current_route,income FROM   vehicle_account   where trip_date = '$trip_date' and bus='$vehicle' $category_query_function ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	   
    	    $expenses =$get['expenses']; 
    	    $income =$get['income']; 
    	    $current_route =$get['current_route']; 


$select_route_main=mysqli_query($db,"SELECT Currency FROM   route_currency   where route_name ='$current_route'  ");
$select_num_route_main=mysqli_num_rows($select_route_main);
if($select_num_route_main !=0){
 	WHILE($get_main=mysqli_fetch_array($select_route_main)){
    	    $currency_curent =$get_main['Currency']; 
 }}

 if($selected_currency == $currency_curent){
$total_expenses += $expenses;
$total_income+= $income;
}else{
$select_route_currency=mysqli_query($db,"SELECT value  FROM   currency_value   where first_point ='$currency_curent' and second_point='$selected_currency' ");
$select_num_route_currency=mysqli_num_rows($select_route_currency);
if($select_num_route_currency !=0){
 	WHILE($get_currency=mysqli_fetch_array($select_route_currency)){
    	    $currency_value =$get_currency['value']; 
		
 }}
$total_expenses += $expenses*$currency_value;
$total_income+= $income*$currency_value;
}


    }}         
       
            ?>
            <tr class="bg-primary text-white" style="font-color:grey;">
  
<td><a href="manifest_view.php?route_name=<?php echo $route_name;?>&bus=<?php echo $vehicle;?>&trip_date=<?php echo $trip_date?>&checked=&main_page=manifest.php"><?php echo $trip_date;?></a></td>
<td><?php echo $category_name;?></td>
<td> <?php echo number_format($total_expenses);?></td>
 
</tr>
            <?php

  $grand_total = $total_income-$total_expenses;
  $grand_total_income +=$total_income;
  $grand_total_expenses +=$total_expenses;
  $grand_total_final +=$grand_total;
?>

<?php
    }}
?>
<tr style="border-top:2px solid black;border-bottom:2px solid black;">
<td></td>
<td><?php echo $category_name;?></td><td><?php echo number_format($grand_total_expenses);?> </td>
 
</tr>

<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
   </table>

          <hr class="mg-y-30">

       

          <div class="ht-40"></div>

          <div class="az-footer mg-t-auto">
            <div class="container">
              <span>&copy; 2024 Azia Responsive Bootstrap 5 Dashboard Template</span>
            </div><!-- container -->
          </div><!-- az-footer -->
        </div><!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->
<script src="/accounts/js/template/dependence/route_dependence.js"></script>
	    <script src="/accounts/js/template/fetch/fetch_particular_detail.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js" type="text/javascript"></script>  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" type="text/javascript"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script>

  $(document).ready(function(){

 

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
  });


 </script>