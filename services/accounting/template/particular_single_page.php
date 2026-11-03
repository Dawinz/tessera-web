<?php
require_once __DIR__ . '/includes/auth_bootstrap.php';

$route_name = $_GET['route_name'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$selected_currency = $_GET['selected_currency'] ?? 'Default';
$category_query_function = $_GET['category_query_function'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Particular By Dates'; ?>
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

			<?php 
      include 'connect/connect.php';
$select_currency_value=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num, name,symbol FROM currency  ");
$select_num_currency_value=mysqli_num_rows($select_currency_value);
if($select_num_currency_value !=0){
   	WHILE($get_currency_value=mysqli_fetch_array($select_currency_value)){
    	    $curr_name =$get_currency_value['name']; 
    	    $symbol =$get_currency_value['symbol']; 
    	    $row_num =$get_currency_value['row_num']; 
			

?>
  <a class="nav-link currency_selected <?php if($row_num==1){ echo "active"; $default_curr=$curr_name;}?>" data-bs-toggle="tab" href="#" id="<?php echo $symbol;?>" data-currency_data="<?php echo "$curr_name";?>"><?php echo $curr_name;?></a>
<?php
}}
			
			?>
			
             
            </nav>

            <nav class="nav">
              <a id="copy_table2" class="nav-link this_nav" href="#"><i class="bx bx-copy"></i> Copy</a>
              <a id="print_table" class="nav-link" href="#"><i class="bx bx-printer"></i> Print</a>
              <a id="excel_table" class="nav-link" href="#"><i class="bx bx-export"></i> Excel</a>
              <a id="csv_table" class="nav-link" href="#"><i class="bi bi-filetype-csv"></i>Export to Csv</a>
              <a id="pdf_table" class="nav-link" href="#"><i class="fa fa-file-pdf-o"></i>Export to Pdf</a>

             
              <a class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
            </nav>
          </div>
  
          <div id="load_particulars">
		 
          </div>

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
<script src="/accounts/js/template/insert/particular_detail.js"></script>
    <script src="/accounts/js/template/update/particular_detail.js"></script>
    <script src="/accounts/js/template/add/particular.js"></script>

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
  route_name = "<?php echo $_GET['route_name'];?>";
  start_date = "<?php echo $_GET['start_date'];?>";
  end_date = "<?php echo $_GET['end_date'];?>";
  selected_currency = "<?php echo $_GET['selected_currency'];?>";
  category_query_function = "<?php echo $_GET['category_query_function'];?>";
     $.ajax({
    url:"load/category_partiucalar.php",
    method:"POST",
    data:{route_name:route_name,start_date:start_date,end_date:end_date,selected_currency:selected_currency,category_query_function:category_query_function},
	   beforeSend: function(){
$("#load_particulars").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:1100px'></span></p>"); 	
            },
    success:function(data){
$("#load_particulars").html(data);

    }
   });

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
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
