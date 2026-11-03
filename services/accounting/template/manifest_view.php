<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'View manifest'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.placeholder_height { height: 600px; z-index: 1000; }
.dropzone { margin-bottom: 40px; margin-top: 40px; }
#ui-datepicker-div { z-index: 10000 !important; }
.select2-dropdown { z-index: 10060 !important; }
    </style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

 <!-- az-header -->
<?php
include 'includes/top_header.php';
include 'drop_down/income.php';
include 'drop_down/expenses.php';
//include 'drop_down/currency.php';
include 'drop_down/all_particular.php';
  $transdate = date('m-d-Y', time());
$this_page =  basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

 

   
 $current_month = date("F", strtotime('m'));
$name_daily_year = '';
$query_account = "SELECT name FROM daily_year ORDER BY ID DESC ";
$result_account = mysqli_query($db, $query_account);
while($row_account = mysqli_fetch_array($result_account))
{
 $name_daily_year .= '<option value="'.$row_account["name"].'">'.$row_account["name"].'</option>';
}


$cat_group = '';
$query = "SELECT front_name FROM  particular_category  ";
$result = mysqli_query($db, $query);
while($row = mysqli_fetch_array($result))
{
 $cat_group .= '<option value="'.$row["front_name"].'">'.$row["front_name"].'</option>';
}

$route_full_name = '';
$query = "SELECT fullname,name FROM route";
$result = mysqli_query($db, $query);
$select_route_num=mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
{
 $route_full_name .= '<option value="'.$row["fullname"].'">'.base64_decode($row["name"]).'</option>';
}



	$currency_value = "";
			$select_currency_value=mysqli_query($db,"SELECT ROW_NUMBER() OVER (ORDER BY id ASC ) row_num, second_point,value,first_point FROM currency_value  ");
$select_num_currency_value=mysqli_num_rows($select_currency_value);
if($select_num_currency_value !=0){
   	WHILE($get_currency_value=mysqli_fetch_array($select_currency_value)){
    	    $first_point =$get_currency_value['first_point']; 
    	    $second_point =$get_currency_value['second_point']; 
    	    $value_currency =$get_currency_value['value']; 
    	    $row_num =$get_currency_value['row_num']; 
			
			
		if($row_num != $select_num_currency_value){
						$currency_value .= '"'.$first_point.'-'.$second_point.'":'.$value_currency.', ';

		}else{
					$currency_value .= '"'.$first_point.'-'.$second_point.'":'.$value_currency.'';
	
		}
		
}}

?>

<div id ="currency_value" style="display:none;"><?php echo $currency_value; ?></div>

<?php
include 'modal/income_modal.php';
include 'modal/upload_route_entries.php';
include 'modal/user_charge.php';

?>

<span id="month" style="display:none;"><?php echo $current_month;?></span>


<script>

	
	function myFunction(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) {
                        $.ajax(
                            {
                                url: 'search/vehicle_account.php',
                                method: 'POST',
                                data: {
								   month:month,
								   year:year,
								   route:route,
								   trip_date:trip_date,
								   bus:bus,
								   point:point,
								   route_name:route_name,
								   start:start,
								   currency:currency,
								   currency_sign:currency_sign,
								   currency_value:currency_value,
								   level:level
                                },
								  beforeSend: function(){
            	$("#load_vehicle_account").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:550px'></span></p>"); 

            
            },
                                success: function (data) {
					if (window.srMountLedgerFragment) {
						window.srMountLedgerFragment(data);
					} else {
						$("#load_vehicle_account").html(data);
					}
                                },
                                dataType: 'text'
                            }
                        );
                    
			}	
			function myFunctionRound(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) {
                        $.ajax(
                            {
                                url: 'search/vehicle_account_round.php',
                                method: 'POST',
                                data: {
								   month:month,
								   year:year,
								   route:route,
								   trip_date:trip_date,
								   bus:bus,
								   point:point,
								   route_name:route_name,
								   start:start,
								   currency:currency,
								   currency_sign:currency_sign,
								   currency_value:currency_value,
								   level:level
                                },
								  beforeSend: function(){
            	$("#load_vehicle_account").html("<p class='placeholder-glow' ><span class='placeholder col-12' style='height:550px'></span></p>"); 

            
            },
                                success: function (data) {
					if (window.srMountLedgerFragment) {
						window.srMountLedgerFragment(data);
					} else {
						$("#load_vehicle_account").html(data);
					}
                                },
                                dataType: 'text'
                            }
                        );
                    
			}
</script>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid sr-accounts-page sr-manifest-compose-page sr-manifest-workspace">
<?php
$__manifest_compose_mode = 'view';
include __DIR__ . '/includes/manifest_compose_toolbar.php';
?>
      <div class="row g-3 sr-manifest-layout">
        <div class="col-lg-3 col-xl-2 sr-manifest-sidebar-col">
          <div class="sr-side-panel az-content-left-mail">
          <div class="az-mail-menu">
          

 

       <label class="az-content-label tx-13 mg-b-10">Route points</label>
       <nav class="nav az-nav-column mg-b-20">
                    <div id="azChatList" class="az-chat-list"style="margin-top:7px;">
			<?php
		$route_name = $_GET['route_name'];
		$trip_date = $_GET['trip_date'];
		$bus = $_GET['bus'];
		$checked = $_GET['checked'];
	
		require_once __DIR__ . '/includes/assign_route_points.php';
		$pointUser = (string) $username;
		$assign_points = tessera_assign_route_points($db, $pointUser, (string) $route_name);
$select_num_point = count($assign_points);
if($select_num_point !=0){
   	foreach ($assign_points as $get_point) {
    	    $point =$get_point['point'];
    	    $row_num =$get_point['row_num'];
    	    $id =$get_point['id'];
    	    $main_route =$get_point['main_route'];
    	    $current_route =$get_point['route_name'];
    	    $end_point =$get_point['end_point'];
			
		
			
			?>
			
			
            <div class="media choose_one <?php if($row_num == 1){ echo "selected";}else{}?>" id="choose_one<?php echo $id;?>" style="cursor: pointer;">            
              <div class="media-body">
                <div class="media-contact-name">
				
				<div class="row">
                  <span ><?php echo $point;?></span>
                <span > 

				  </span>
                </div>
				
				</div>

              </div><!-- media-body -->
            </div><!-- media -->       
 <?php if($row_num == 1){ 
 
 ?>
 <script>
$(document).ready(function(){

	
$("#point_value").val("<?php echo $point;?>");
$("#point_name").text("<?php echo $point;?>");
$("#current_route").val("<?php echo $current_route;?>");
$("#end_point").val("<?php echo $end_point;?>");

route_name = "<?php echo $current_route;?>";

	month = $("#month1").text();
	year = $(".currency_year").val();
	route = $("#route_name1").text();
	trip_date = $("#date1").text();
	bus = $("#bus1").text();
	point = "<?php echo $point;?>";
	start = "first";
	$("#start").val(start);
	currency_value = $("#currency_value").text();
		currency="Default";
		level = "<?php echo $level;?>";
		currency_sign = "";
						myFunction(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) ;


});
 
 </script>
<?php
 }else{}
 $new_point = str_replace(" " , "_",  $point);
 ?>
 
 <script>
 $(document).ready(function(){

 $("#choose_one<?php echo $id;?>").click(function(){
	  $(".choose_one").removeClass("selected");
	  $(this).addClass("selected");
$("#point_value").val("<?php echo $point;?>");
$("#point_name").text("<?php echo $point;?>");
$("#current_route").val("<?php echo $current_route;?>");
month = $("#month1").text();
	year = $(".currency_year").val();
	route = "<?php echo $main_route;?>";
	route_name = "<?php echo $current_route;?>";
	trip_date = $("#date1").text();
	bus = $("#bus1").text();
	point = "<?php echo $point;?>";
	start = "middle";
	$("#start").val(start);
		currency_value = $("#currency_value").text();
		currency="Default";
				currency_sign = "";
		level = "<?php echo $level;?>";

	myFunction(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) ;

});
});
 </script>
 
 <?php
 
 
 ?>
    <?php
}}
	?>
	
	   <div class="media choose_one " id="choose_one" style="cursor: pointer;">            
              <div class="media-body">
                <div class="media-contact-name">
				
				<div class="row">
                  <span >Round trip</span>
                  <span > 

				  </span>
                </div>
				
				</div>

              </div><!-- media-body -->
            </div>
	<script>
 $(document).ready(function(){

 $("#choose_one").click(function(){
	  $(".choose_one").removeClass("selected");
	  $(this).addClass("selected");
$("#point_value").val("<?php echo $point;?>");
$("#point_name").text("<?php echo $point;?>");

   
   $("#point_value").val("<?php echo $point;?>");
$("#point_name").text("<?php echo $point;?>");
month = $("#month1").text();;
	year = $(".currency_year").val();
	route = $("#route_name1").text();
	$("#current_route").val("<?php echo $_GET['route_name'];?>");
	route_name = "<?php echo $_GET['route_name'];?>";
	trip_date = $("#date1").text();
	bus = $("#bus1").text();
	point = "round";
			currency_value = $("#currency_value").text();
		currency_sign = "";
		currency_value = $("#currency_value").text();
		level = "<?php echo $level;?>";


	start="last";
			currency="Default";

   						myFunctionRound(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign,level) ;

});
});
 </script>
          </div>
            </nav>

        <hr class="mg-y-30">

            <label class="az-content-label tx-13 mg-b-20">Year </label>
            <div class="az-profile-social-list">
			<select class="form-select currency_year" id="currency_year" ><?php echo $name_daily_year;?></select>
              <!-- media -->
           <!-- media -->
            </div> 

		<div id="vehicle_attachment"></div>
          
          </div><!-- az-mail-menu -->
        </div><!-- side panel -->
        </div><!-- col sidebar -->
        <div class="col-lg-9 col-xl-10 sr-manifest-main-col">
          <div class="sr-content-card az-content-body az-content-body-mail">
            <div id="placeholder_veh" class="sr-ledger-host w-100">
              <div id="load_vehicle_account" class="sr-vehicle-ledger w-100">
                <div class="placeholder-glow sr-ledger-loading">
                  <span class="placeholder col-12 rounded d-block" style="height:420px;"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php //include 'modal/income_exp.php';?>
 <!-- az-mail-compose -->
<span style="display:none;" id="route_name_update"><?php echo $_GET['route_name']?></span>
<span style="display:none;"id="id_update"><?php echo $_GET['id']?></span>
<span style="display:none;"id= "date_update"><?php echo $_GET['date']?></span>
<span style="display:none;" id="route_name1"><?php echo $_GET['route_name']?></span>
<span style="display:none;"id="id1"><?php echo $_GET['id']?></span>
<span style="display:none;"id= "date1"><?php echo $_GET['trip_date']?></span>
<span style="display:none;"id= "bus1"><?php echo $_GET['bus']?></span>
<span style="display:none;"id= "month1"><?php echo $current_month;?></span>
<span style="display:none;"id= "level"><?php echo $level;?></span>

<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/jquery.cookie.js" type="text/javascript"></script>
<script src="/accounts/js/template/addon/currency_multiple.js"></script>

<script>
 
  </script>
 <script>
  if (window.Dropzone) { Dropzone.autoDiscover = false; }
 $(document).ready(function(){
	 var month = $("#month1").text() || '';
	 var year = $(".currency_year").val() || '';
	 var route_name = $("#route_name1").text() || '';
	 var route = route_name;
	 var trip_date = $("#date1").text() || '';
	 var bus = $("#bus1").text() || '';

$(".tab-link").click(function(){
month = $(this).text();
year = $("#currency_year").val();
route_name = $("#route_name_ext").val();
route = $("#main_route_ext").val();
trip_date = $("#trip_date_ext").val();
bus = $("#bus_ext").val();
point = $("#route_start_ext").val();
start = $("#start_ext").val();
currency_value = $("#currency_value").text();
currency = $("#currency_ext").val();
currency_sign = $(".currency_sign_ext").val();
myFunction(month,year,route,trip_date,bus,point,start,route_name,currency_value,currency,currency_sign) ;

});
	 
			 $.ajax({
    url:"search/vehicle_attachment.php",
    method:"POST",
    data:{route_name:route_name,route:route,bus:bus,trip_date:trip_date},
	   beforeSend: function(){

            
            },
    success:function(data){
$("#vehicle_attachment").html(data);


    }
   }) 
	 $(".add_exp").click(function(){
current_route = $(".current_route").val();
$("#route_name").val(current_route);
	 });
	 

		   $("#signup").on('submit', function(e){
	

        e.preventDefault();
		
		count = 1;
		
error="";
	
		$("select[name='Vehicle[]']").each(function(){
vehicle = $.trim(($(this).val()));

			if(vehicle == 'Choose Vehicle')
			{

error += "<li>Choose Vehicle.</li>";
	}
	
		});	
		
	$('.item').each(function(){	
	item =  $.trim(($(this).val()));
	if(item=="Choose Item"){
error += "<li>Choose Item.</li>";
	}
	
		});	
	$('.qty').each(function(){		
qty = $(".qty").val();
	if(qty==""){
error += "<li>Add Quantity.</li>";
	}
		});	
		
			$('.price').each(function(){	
		
	amount = $("#amount").val();
	if(amount==""){
error += "<li>Add amount.</li>";
	}
	
	});	
	
			if(error == '')
		{
		
		
		   $.ajax({
            type: 'POST',
            url: 'insert/jobcard.php',
            data: new FormData(this),
           
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
							    $("#save").attr("disabled", true);
	  $("#save").text("Add Currency..");
            
            },
            success: function(response){
				
									myFunction(month,year,route);

				  $("#save").attr("disabled", false);
				alert(response);
	if(($.trim(response)) =="found"){
				$("#save").notify(
 "Currency found",
 "error",
  { position:"right" }
);
	
									

	}else{
$("#save").notify(
 "Saved Successfull",
 "success",
  { position:"right" }
);
		  $("#save").text("Save changes");
								
$('#signup').trigger("reset");
$("#example1").DataTable().ajax.reload();

				}
            }
        });
		}else{
						Swal.fire({
  title: "Missing Info",
  html: error,
  icon: "warning"
});			
		}
	
	});
	
	
	
	
	
	
	
	
 });
 
      $(function(){
        'use strict'

        if ($.fn.lightSlider && $('#navComplex').length) $('#navComplex').lightSlider({
          autoWidth: true,
          pager: false,
          slideMargin: 3
        });

        $('.az-nav-tabs .tab-link').on('click', function(e) {
          e.preventDefault();
          $(this).addClass('active');
          $(this).parent().siblings().find('.tab-link').removeClass('active');

          var target = $(this).attr('href');
          $(target).addClass('active');
          $(target).siblings().removeClass('active');
        })

      });
	  
	  
	  $(".show_btn").click(function(){
		  $(".entrie_header").css("background","#2B57D9");
		  $(".income_type").css("color","white");
		 $(".income_type").text("Income");
		 $("#acc_type").val("Income");
		 
		 	 acc_name= "Income";
		 
		    $.ajax({
    url:"./dependence/fetch_expenses_once.php",
    method:"POST",
    data:{acc_name:acc_name},
	   beforeSend: function(){
	//$("#part_detail"+my_id+" option:selected" ).text("loading...");
            
            },
    success:function(data){
$("#expens_cat").html(data);
$(".part_Category").html(data);
    }
   })
		 
		 
	  }); 
	  
	  $(".show_btn_exp").click(function(){
		  $(".entrie_header").css("background","#3E4759");
		  		  $(".income_type").css("color","white");

		 $(".income_type").text("Expenses");
		 $("#acc_type").val("Expenses");
		 acc_name= "Expenses";
		 
		    $.ajax({
    url:"./dependence/fetch_expenses_once.php",
    method:"POST",
    data:{acc_name:acc_name},
	   beforeSend: function(){
	//$("#part_detail"+my_id+" option:selected" ).text("loading...");
            
            },
    success:function(data){
$("#expens_cat").html(data);
$(".part_Category").html(data);
    }
   })

	  });
    </script>
</body>
</html>
