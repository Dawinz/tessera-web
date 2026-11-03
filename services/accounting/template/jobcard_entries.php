<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Job Card Expenses'; ?>
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

</style>

 <!-- az-header -->
<?php
include 'includes/top_header.php';

  $transdate = date('m-d-Y', time());

 

   
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


$select_first=mysqli_query($db,"SELECT fullname,name FROM route order by id asc limit 1");
$select_num_first=mysqli_num_rows($select_first);
if($select_num_first !=0){
   	WHILE($get_first=mysqli_fetch_array($select_first)){
    	    $fullname_first =$get_first['fullname']; 
			
}}




$route_full_name_first = '';
$query = "SELECT id,date FROM job_card WHERE route_name ='$fullname_first' order by id desc";
$result = mysqli_query($db, $query);
$select_route_num=mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
{
 $route_full_name_first .= '<option value="'.$row["date"].'">'.$row["date"].'</option>';
}




$route_full_name = '';
$query = "SELECT fullname,name FROM route";
$result = mysqli_query($db, $query);
$select_route_num=mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
{
 $route_full_name .= '<option value="'.$row["fullname"].'">'.base64_decode($row["name"]).'</option>';
}
include 'modal/job_card_exp.php';

?>
<span id="month" style="display:none;"><?php echo $current_month;?></span>

          <div class="card sr-table-panel ">
            <div class="card-header card-header-tab">
              <div class="az-nav-tabs">
                <div id="navComplex">
				<?php
				$select_month=mysqli_query($db,"SELECT name FROM   months    ");
$select_num_month=mysqli_num_rows($select_month);
if($select_num_month !=0){
   	WHILE($get_month=mysqli_fetch_array($select_month)){
    	    $month_name =$get_month['name']; 
				?>
                  
                  <div class="tab-item"><a href="#azTab12" class="tab-link <?php if($current_month==$month_name){ echo "active";}?>"><span id="this_tab"><?php echo $month_name ;?><span></a></div>
				  <?php
}}
				  ?>
                </div>
              </div><!-- az-nav-complex -->
            </div><!-- card-header -->
  <!-- card-body -->
          </div><!-- card -->

<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasTopLabel">Update Entry</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
<form id="update_currency" enctype="multipart/form-data">
<input type="hidden" name="id_no" id="id_no">
<table class="table table-bordered">
<tr>
<td><select name="date_update" class="form-select" id="date_update" style="" data-live-search="true"><option id="date_update_text"value="Choose Vehicle">Choose Vehicle</option><?php echo $route_full_name_first;?></select></td>
<td><select name="Vehicle_update" class="form-select" id="Vehicle_update" style="" data-live-search="true"><option id="vehicle_choose"value="Choose Vehicle">Choose Vehicle</option><?php echo $name;?></select></td>
<td><select name="item_update" class="form-select" id="item_update" style="" data-live-search="true"><option id="item_update_ch">adsf</option><?php echo $spare; ?></select></td>
<td><input name="quantity_update" type="text" id="qty_update"class="form-control  amount" placeholder="Quantity"></td>
<td><input name="price_update" type="text" id="amount_update" class="form-control value_name amount" placeholder="price"></td>
<td><input name="detail_update" id="detail"type="text" class="form-control " placeholder="Detail"></td>
</tr>
</table>
 <div class="modal-footer">
            <button type="submit"class="btn btn-indigo" id="udpdate"><span id="uptext">Save changes</span></button>
          </div>
		   </form>
  </div>
</div>


    <div class="az-content az-content-mail">
      <div class="row" style="padding-right:40px;padding-left:40px;">
        <div class="az-content-left az-content-left-mail col-4">

          <div class="az-content-header">
            <a href="" id="azMenuShow" class="az-header-menu-icon"><span></span></a>
            <a href="index.html" class="az-logo">az<span>i</span>a</a>
            <a href="" id="azContentLeftHide" class="az-header-arrow">
              <i class="icon ion-md-arrow-forward d-sm-none"></i>
              <i class="icon ion-md-close d-none d-sm-block"></i>
            </a>
          </div><!-- az-content-header -->

  
          <div class="az-mail-menu">
          

            <nav class="nav flex-column">
              
	<button class="btn btn-indigo btn-with-icon btn-block add_exp" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="bx bx-plus"></i> Add Expenses</button>

            </nav>
 


        <hr class="mg-y-30">

            <label class="az-content-label tx-13 mg-b-20">Year </label>
            <div class="az-profile-social-list">
			<select class="form-select currency_year" id="currency_year" ><?php echo $name_daily_year;?></select>
              <!-- media -->
           <!-- media -->
            </div>  <hr class="mg-y-30">

            <label class="az-content-label tx-13 mg-b-20">Route </label>
            <div class="az-profile-social-list">
			<select class="form-select current_route" name ="current_route" id="current_route" ><?php echo $route_full_name;?></select>
              <!-- media -->
           <!-- media -->
            </div>   

			<hr class="mg-y-30">

            <label class="az-content-label tx-13 mg-b-20">Quick settings </label>
            <div class="az-profile-social-list">
	   <nav class="nav az-nav-column az-nav-calendar-event">
            <a href="jobcard.php" class="nav-link" ><i class="icon ion ion-md-today tx-success"></i> <div>Add Jobcard Date</div></a>
         
          </nav>
            </div>
          </div><!-- az-mail-menu -->
        </div><!-- az-content-left -->
		<div class="az-content-body az-content-body-mail col-8">  
<div class="placeholder-glow" >		
<span class=" col-12 placeholder_height" id="placeholder_veh" >     
			 <div id="load_vehicle_account">
			  
			  
			  </div>
			  </span>
			  </div>
			  </div>

        

      </div><!-- container -->
    </div><!-- az-content -->
<?php //include 'modal/income_exp.php';?>
 <!-- az-mail-compose -->
<span style="display:none;" id="route_name_update"><?php echo $_GET['route_name']?></span>
<span style="display:none;"id="id_update"><?php echo $_GET['id']?></span>
<span style="display:none;"id= "date_update"><?php echo $_GET['date']?></span>



    <div class="az-footer ht-40">
      <div class="container ht-100p pd-t-0-f">
        <span>&copy; 2024 Azia Responsive Bootstrap 5 Dashboard Template</span>
      </div><!-- container -->
    </div><!-- az-footer -->

  
	
	<script src="/accounts/js/template/sweatalert2.js"></script>
	

    <script src="/accounts/js/jquery.cookie.js" type="text/javascript"></script>
	<script src="/accounts/js/template/addon/currency_multiple.js"></script>
	    <script src="/accounts/js/template/check_out_jobcard.js"></script>

		    
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
    <script src="/accounts/js/template/update/update_jobcard.js"></script>
    <script src="/accounts/js/template/reload/job_card_reload.js"></script>
    <script src="/accounts/js/template/insert/jobcard_entries.js"></script>


  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>

<script>
 
  </script>
 <script>
 $(document).ready(function(){
	 
	 $(".add_exp").click(function(){
current_route = $(".current_route").val();
$("#route_name").val(current_route);
	 });
	 
	month = $("#month").text();
	year = $(".currency_year").val();
	route = $(".current_route").val();
	$("#route_name_modal").val(route);
	function myFunction(month,year,route) {
				
                        $.ajax(
                            {
                                url: 'search/job_card_details.php',
                                method: 'POST',
                                data: {
								   month:month,
								   year:year,
								   route:route
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
									

					$("#load_vehicle_account").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}
					myFunction(month,year,route) ;
	
	
	
	
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
    </script>
</body>
</html>
