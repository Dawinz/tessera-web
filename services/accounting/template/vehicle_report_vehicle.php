<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<?php
date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now_start = date('m/01/Y', time());
$date_now_end = date('m/t/Y', time());
$date_today = date('Y-m-d', time());
$start_date_three_date = date("Y-m-01",strtotime("-3 Month"));
$start_date_six_date = date("Y-m-01",strtotime("-6 Month"));
$start_date_twelve_date = date("Y-01-01");

$start_date_adpted = $_GET['start_date'];
$end_date_adpted = $_GET['end_date'];

?>
<html lang="en">
  <head>
    <?php $__page_title = 'Vehcile  Expenses'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">
  <style>
.placeholder_height{
	height:600px;
	z-index: 1000;
}
.dropzone{
	margin-bottom:40px;
	margin-top:40px;
	margin-left:250px;
}
#ui-datepicker-div {
z-index: 10000 !important;
}
.select2-selection__rendered {
    line-height: 31px !important;
}
.select2-container .select2-selection--single {
    height: 36px !important;
    border-radius:0px;
}
.select2-selection__arrow {
    height: 34px !important;
}
.dt-search{
	Display:none;
}
#myInputTextField
{
width:200px;
position:relative;
left:1250px;
display:none;
}
</style>

 <!-- az-header -->
<?php
include 'includes/top_header.php';
include 'dependence/fetch_route_name.php';


?>
<span id="month" style="display:none;"><?php echo $current_month;?></span>

          <div class=" "style="padding:10px;">
      <div class="row">
	  <div class="col-12 ">
      <input type="hidden" value="<?php echo $start_date_adpted;?>" class="form-control fc-datepicker" id="start_date"placeholder="Start date">
               <input  type="hidden" value="<?php echo $end_date_adpted;?>" class="form-control fc-datepicker" id="end_date"placeholder="End date">
        <input type="hidden" value="" id="route_name">

		    <h2 class="az-content-title tx-24 mg-b-20">Vehicle Report</h2>
		              <div class="az-content-breadcrumb lh-1 mg-b-10">
            <span style="font-size:12px;"><span id="start_date_holder"><?php echo date("jS/M/Y",strtotime($date_now_start));?></span>  <span id="end_date_holder"><?php echo date("jS/M/Y",strtotime($date_today));?></span></span>
            
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
		              <a class="nav-link currency_selected <?php if($row_num==1){ echo "active";}?>" data-bs-toggle="tab" href="#" id="<?php echo $symbol;?>"><?php echo $curr_name;?></a>

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
              <a id="search_row" class="nav-link" href="#"><i class="fa fa-search"></i></a>
             
              <a class="nav-link" href="#"><i class="fas fa-ellipsis-h"></i></a>
            </nav>
          </div>
<div id="load_report"></div>
</div>

	

</div>
          </div><!-- card -->
<!-- az-content -->
<?php //include 'modal/income_exp.php';?>
 <!-- az-mail-compose -->

<input type="hidden" id="selected_currency"value="Tsh">


    <div class="az-footer ht-40">
      <div class="container ht-100p pd-t-0-f">
        <span><?php echo $footer;?></span>
      </div><!-- container -->
    </div><!-- az-footer -->

  
	
	<script src="/accounts/js/template/sweatalert2.js"></script>
	

    <script src="/accounts/js/jquery.cookie.js" type="text/javascript"></script>
	<script src="/accounts/js/template/addon/currency_multiple.js"></script>
	    <script src="/accounts/js/template/check_out_jobcard.js"></script>
	    <script src="/accounts/js/template/dependence/library.js"></script>

		    
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js" type="text/javascript"></script>  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" type="text/javascript"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script type="text/javascript" src="dropzone/dropzone.js"></script>

    <script src="/accounts/js/template/update/update_daily.js"></script>
    <script src="/accounts/js/template/reload/daily_load.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>

  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>

<script>
 
  </script>
 <script>
 $(document).ready(function(){
     
	 current_route = $("#current_route").val();
	 current_route = $(".current_route").val();
	 selected_currency = $("#selected_currency").val();
   route_name = $("#route_name"). val();
    $("#current_route").val(current_route);

start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
   route_name = $("#route_name"). val();

	
	function loadReportChart(start_date, end_date, selected_currency, route_name) {
		if (!$('#chart_div, #load_chart, #vehicle_chart').length) return;
		$.ajax({
			url: 'charts/vehicle_report.php',
			method: 'POST',
			data: {
				start_date: start_date,
				end_date: end_date,
				selected_currency: selected_currency,
				route_name: route_name
			},
			success: function (data) {
				var $host = $('#chart_div');
				if (!$host.length) $host = $('#load_chart');
				if (!$host.length) $host = $('#vehicle_chart');
				if ($host.length) $host.html(data);
			}
		});
	}

	function loadReport(start_date,end_date,selected_currency,route_name) {
				bus="<?php echo $_GET['bus'];?>";
     
                        $.ajax(
                            {
                                url: 'search/vehicle_report_vehicle.php',
                                method: 'POST',
                                data: {
								   start_date:start_date,
								   end_date:end_date,
								   selected_currency:selected_currency,
								   route_name:route_name,
								   bus:bus,
                                },
								  beforeSend: function(){
            	$("#load_report").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:400px'></span></p>"); 

            
            },
                                success: function (data) {
									

					$("#load_report").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}
	
					loadReport(start_date,end_date,selected_currency,route_name) ;
					loadReportChart(start_date,end_date,selected_currency,route_name) ;
	
	$(".currency_selected").click(function(){
    selected_currency = $(this).text();
    $("#selected_currency").val(selected_currency);

	start_date = $("#start_date").val();
	end_date = $("#end_date").val();
  loadReport(start_date,end_date,selected_currency,route_name) ;

  });
	$("#start_date").change(function(){
  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
    const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
 					loadReport(start_date,end_date,selected_currency,route_name) ;
					loadReportChart(start_date,end_date,selected_currency,route_name) ;
  });
	$("#end_date").change(function(){
  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
    const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
 					loadReport(start_date,end_date,selected_currency,route_name) ;
					loadReportChart(start_date,end_date,selected_currency,route_name) ;
  });
	$("#route_name").change(function(){
  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
  route_name = $("#route_name"). val();
  const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
 					loadReport(start_date,end_date,selected_currency,route_name) ;
					loadReportChart(start_date,end_date,selected_currency, route_name) ;
  });

$(".next").click(function(){
  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
//start date
  const  date = moment(start_date).format('YYYY-MM-DD');
  const new_date = new Date(date);
 new_date.setMonth(new_date.getMonth() + 1);
 new_date2= new_date.toISOString();
 new_start_date = new_date2.split('T')[0];
const  new_og_st_date = moment(new_start_date).format('MM/DD/YYYY');
$("#start_date").val(new_og_st_date);
  //end date
const  date_end = moment(end_date).format('YYYY-MM-DD');
  const new_end_date = new Date(date_end);
 new_end_date.setMonth(new_end_date.getMonth() + 1);
 new_date_end2= new_end_date.toISOString();
 new_end_date2 = new_date_end2.split('T')[0];

   const  new_og_end_date = moment(new_end_date).format('MM/DD/YYYY');
   var temp = new Date(new_og_end_date);
   $("#end_date").val(new_og_end_date);
   const  start_date_holder = moment(new_og_st_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(new_og_end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
});
$(".prev").click(function(){
  $(".next").attr("disabled", false);

  start_date = $("#start_date"). val();
  end_date = $("#end_date"). val();
//start date
  const  date = moment(start_date).format('YYYY-MM-DD');
  const new_date = new Date(date);
 new_date.setMonth(new_date.getMonth() - 1);
 new_date2= new_date.toISOString();
 new_start_date = new_date2.split('T')[0];
const  new_og_st_date = moment(new_start_date).format('MM/DD/YYYY');
$("#start_date").val(new_og_st_date);
  //end date
const  date_end = moment(end_date).format('YYYY-MM-DD');
  const new_end_date = new Date(date_end);
 new_end_date.setMonth(new_end_date.getMonth() - 1);
 new_date_end2= new_end_date.toISOString();
 new_end_date2 = new_date_end2.split('T')[0];
   const  new_og_end_date = moment(new_end_date).format('MM/DD/YYYY');
   var temp = new Date(new_og_end_date);
   $("#end_date").val(new_og_end_date);
   const  start_date_holder = moment(new_og_st_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(new_og_end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
   	loadReport(new_og_st_date,new_og_end_date,selected_currency,route_name) ;
					loadReportChart(new_og_st_date,new_og_end_date, selected_currency, route_name) ;
});

$(".current_month").click(function(){
start_date ="<?php echo $date_now_start;?>";
end_date ="<?php echo $date_today?>";
$(".current_month").attr("disabled", true);
$(".3month").attr("disabled", false);
$(".6month").attr("disabled", false);
$(".year").attr("disabled", false);
const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
		loadReport(start_date,end_date,selected_currency,route_name) ;
					loadReportChart(start_date,end_date,selected_currency, route_name) ;

});
$(".3month").click(function(){
start_date ="<?php echo $start_date_three_date?>";
end_date ="<?php echo $date_today?>";
$(".current_month").attr("disabled", false);
$(".3month").attr("disabled", true);
$(".6month").attr("disabled", false);
$(".year").attr("disabled", false);
const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
		loadReport(start_date,end_date,selected_currency,route_name) ;
					loadReportChart(start_date,end_date,selected_currency, route_name) ;

});
$(".6month").click(function(){
start_date ="<?php echo $start_date_six_date?>";
end_date ="<?php echo $date_today?>";
$(".current_month").attr("disabled", false);
$(".3month").attr("disabled", false);
$(".6month").attr("disabled", true);
$(".year").attr("disabled", false);
const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
		loadReport(start_date,end_date,selected_currency,route_name) ;
					loadReportChart(start_date,end_date,selected_currency, route_name) ;

});
$(".year").click(function(){
start_date ="<?php echo $start_date_twelve_date?>";
end_date ="<?php echo $date_today?>";
$(".current_month").attr("disabled", false);
$(".3month").attr("disabled", false);
$(".6month").attr("disabled", false);
$(".year").attr("disabled", true);
const  start_date_holder = moment(start_date).format('Do/MMM/YYYY');
const  end_date_holder = moment(end_date).format('Do/MMM/YYYY');
$("#start_date_holder").text(start_date_holder);
$("#end_date_holder").text(end_date_holder);
		loadReport(start_date,end_date,selected_currency,route_name) ;
					loadReportChart(start_date,end_date,selected_currency, route_name) ;

});
 });
 

    </script>
</body>
</html>
