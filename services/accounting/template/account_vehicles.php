<?php
require_once __DIR__ . '/includes/auth_bootstrap.php';
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now_start = date('m/01/Y', time());
$date_now_end = date('m/t/Y', time());
$date_today = date('Y-m-d', time());
$start_date_three_date = date("Y-m-01",strtotime("-3 Month"));
$start_date_six_date = date("Y-m-01",strtotime("-6 Month"));
$start_date_twelve_date = date("Y-01-01");
$new_date_now = date("m/d/Y", strtotime($date_today));
?>
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

<!-- az-header -->
<?php
include 'includes/top_header.php';
include 'canvas/manifest.php';
$username_selected = $username;
?>
 <div class="row"style="padding-top:10px;">
 <div class="col-md-2 az-content-left-mail">
 
          <div class="component-item "style="margin:30px;">
         

           <br> <label style="color:#6F6F6F;">Select Vehicle Group</label>
            <nav class="nav flex-column">
	<button class="btn  btn-indigo btn-block change_r first_b  " data-vehicl_cat="all">All Categories</button>
<?php
include 'connect/connect.php';
$select_route_name=mysqli_query($db,"SELECT ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    name FROM   vehicle_category    ");
$select_num_route_name=mysqli_num_rows($select_route_name);
if($select_num_route_name !=0){
   	WHILE($get_route_name=mysqli_fetch_array($select_route_name)){
    	    $name =$get_route_name['name']; 
    	    $row_num =$get_route_name['row_num']; 
          
?>

<button class="btn  btn-dark btn-block change_r first_b  " data-vehicl_cat="<?php echo "$name";?>"><?php echo $name;?></button>
<?php
    	  
	}
}
?>

            </nav>


          </div><!-- component-item -->

 </div>
 <div class="col-md-9"><br>
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
      $currnecy_value = 0;

$select_currency_value=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num, name,symbol FROM currency  ");
$select_num_currency_value=mysqli_num_rows($select_currency_value);
if($select_num_currency_value !=0){
   	WHILE($get_currency_value=mysqli_fetch_array($select_currency_value)){
    	    $curr_name =$get_currency_value['name']; 
    	    $symbol =$get_currency_value['symbol']; 
    	    $row_num =$get_currency_value['row_num']; 
			
            if($row_num==1){
$currnecy_value= $curr_name ;
$currnecy_symbol= $symbol ;
            }else{

            }

?>
  <a class="nav-link currency_selected <?php if($row_num==1){ echo "active"; $default_curr=$curr_name;}?>" data-bs-toggle="tab" href="#" data-currency_symbol="<?php echo $symbol;?>" data-currency_data="<?php echo "$curr_name";?>"><?php echo $curr_name;?></a>
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

             
              <a id="search_row"class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
            </nav>
          </div>
          <input type="hidden" id="vehicle_cat"value="all">
          <input type="hidden" id="currency" value="<?php echo $currnecy_value;?>">
          <input type="hidden" id="symbol" value="<?php echo $currnecy_symbol;?>">
           <div id="load_table"></div>
 </div>

 </div>
<script src="/accounts/js/template/insert/manifest.js"></script>
    <script src="/accounts/js/template/update/update_manifest.js"></script>
    <script src="/accounts/js/template/insert/lock.js"></script>

	    <script src="/accounts/js/template/load/vehicle_category.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
        <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js" type="text/javascript"></script>  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" type="text/javascript"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

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