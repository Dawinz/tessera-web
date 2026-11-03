<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Particular & Category'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.error_class{
	border:1px solid red;
}

.select2-dropdown {  
  z-index: 10060 !important;/*1051;*/
}
td.highlight {
        font-weight: bold;
        color: red;
 
    }
 
td.highlight_normal {
        font-weight: bold;
        color: black;
    }
 
   
input[type='date']::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
}

</style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">
<?php
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now_start = date('m/01/Y', time());
$date_now_end = date('m/t/Y', time());
$date_today = date('Y-m-d', time());
$start_date_three_date = date("Y-m-01",strtotime("-3 Month"));
$start_date_six_date = date("Y-m-01",strtotime("-6 Month"));
$start_date_twelve_date = date("Y-01-01");
$new_date_now = date("m/d/Y", strtotime($date_today));
?>
<!-- az-header -->
<?php
include 'includes/top_header.php';
include 'canvas/manifest.php';
include 'dependence/all.php';



$db_cargo = cargo_db();

$vehicle_name = "";
$vehicle_category = new Vehicles();
$vehicle_category->set_details($vehicle_name, $db_cargo);

?>
 <div class="row"style="padding:10px;">
<div style="padding:20px;"class="col-md-2 az-content-left az-content-left-contacts">
<input type="hidden" id="category_properites" >
          <div class="az-content-breadcrumb lh-1 mg-b-10">
            <span>Select Categories & Particular</span>
          
          </div>

          <nav class="nav az-nav-line az-nav-line-chat">
            <a href="" data-bs-toggle="tab" class="nav-link active categoryies">Categories</a>
            <a href="" data-bs-toggle="tab" class="nav-link particular">Particulars</a>
          </nav> <br>
          <input type="text" class="sr-table-search form-control" id="search">

          <div id="azContactList" class="az-contacts-list">
            
       <!-- az-contact-item -->

    <div style="padding-top: 10px;">
<div id="load_category"></div>
    </div>
    
          </div><!-- az-contacts-list -->

        </div>
 <div class="col-md-9">
     <div id="array_buttons"></div>
<div id="array_no" style="display:none;"></div>
<br>
 <div class="az-mail-header">
            <div>
          
              <button type="button" class="btn btn-outline-secondary prev"><i class="icon ion-ios-arrow-back"></i></button>
                <button type="button" class="btn btn-outline-secondary next" disabled><i class="icon ion-ios-arrow-forward"></i></button>
            </div>      <div>
          
            
             <div class="btn-group" role="group">
               <input value="<?php echo $date_now_start;?>" class="form-control fc-datepicker" id="start_date"placeholder="Start date">
               <input value="<?php echo $new_date_now;?>" class="form-control fc-datepicker" id="end_date"placeholder="End date">
             
              </div>
            </div>
          <div>
             
             
              <div class="row">
  <div class="col">
      <select class="form-select select2 " id="vehicle_category"style="width:300px;">
  <option value="">All Vehicle</option>
      <?php  $vehicle_category->get_details();?>
      </select>
</div>

      </div>
            </div>
            <div role="group">
                <button type="button"class="btn btn-outline-secondary current_month selected_duration" data-month="0" disabled>Current</button>
                <button type="button" class="btn btn-outline-secondary 3month selected_duration" data-month="3" >3 MONTH</button>
                <button type="button" class="btn btn-outline-secondary 6month selected_duration" data-month="6" >6 MONTH</button>
                <button type="button" class="btn btn-outline-secondary year  selected_duration" data-month="12" >YEAR</button>
              </div>
          </div>
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
              <a id="copy_table" class="nav-link" href="#"><i class="bx bx-copy"></i> Copy</a>
              <a id="print_table" class="nav-link" href="#"><i class="bx bx-printer"></i> Print</a>
              <a id="excel_table" class="nav-link" href="#"><i class="bx bx-export"></i> Excel</a>
              <a id="csv_table" class="nav-link" href="#"><i class="bi bi-filetype-csv"></i>Export to Csv</a>
              <a id="pdf_table" class="nav-link" href="#"><i class="fa fa-file-pdf-o"></i>Export to Pdf</a>

             
              <a class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
            </nav>
          </div>
          <input type="hidden" value="<?php echo $default_curr;?>"id="selected_currency">
    <div id="load_table"></div>
</div>
</div>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js" type="text/javascript"></script>  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" type="text/javascript"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
           <script src="https://momentjs.com/downloads/moment.js"></script>



	    <script src="/accounts/js/template/load/single_vehicle.js<?php echo $url_time;?>"></script>

<script src="/accounts/js/template/load/filter_category_vehicle_single.js<?php echo $url_time;?>"></script>
  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
<script>
  $(document).ready(function(){
 $('.fc-datepicker').datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
        });

    


        
  });
</script>