<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Insert manifest entries'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.placeholder_height { height: 600px; z-index: 1000; }
#ui-datepicker-div { z-index: 10000 !important; }
    </style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">
 <!-- az-header -->
<?php
include 'includes/top_header.php';
//include 'drop_down/drop_down.php';
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid sr-accounts-page sr-manifest-compose-page sr-manifest-workspace">
<?php
$__manifest_compose_mode = 'insert';
include __DIR__ . '/includes/manifest_compose_toolbar.php';
?>
      <div class="row g-3 sr-manifest-layout">
        <div class="col-lg-3 col-xl-2 sr-manifest-sidebar-col">
          <div class="sr-side-panel az-content-left-mail">
          <div class="az-mail-menu">
          
				  						   <div class="btn-group btn-group">

  <button class="btn btn-primary   show_btn" id="btnCompose"> Income</button>
            <button class="btn btn-dark show_btn_exp"> Expenses</button>

</div> <br>

            <nav class="nav az-nav-column mg-b-20">
                    <div id="azChatList" class="az-chat-list"style="margin-top:7px;">
			<?php
		$route_name = $_GET['route_name'];
		$trip_date = $_GET['trip_date'];
		$bus = $_GET['bus'];
		$checked = $_GET['checked'];
	
		$select_point=mysqli_query($db,"
		
		SELECT 
	ROW_NUMBER() OVER (
		ORDER BY id ASC 
	) row_num,
    point,id,main_route,route_name,end_point

FROM 
	assign_route WHERE   username='admin' AND main_route='$route_name'
ORDER BY 
	id ASC 
		
		");
$select_num_point=mysqli_num_rows($select_point);
if($select_num_point !=0){
   	WHILE($get_point=mysqli_fetch_array($select_point)){
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
$("#load_vehicle_account").load("load/vehicle_account.php?point=<?php echo str_replace(" " , "_",  $point);?>&route_name=<?php echo str_replace(" " , "_",  $route_name);?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&main_route=<?php echo str_replace(" " , "_",  $main_route);?>&current_route=<?php echo str_replace(" " , "_",  $current_route);?>&end_point=<?php echo str_replace(" " , "_",  $end_point);?>&row_num=<?php echo $row_num;?>");
$("#load_attachment").load("load/attachment.php?point=<?php echo str_replace(" " , "_",  $point);?>&route_name=<?php echo str_replace(" " , "_",  $route_name);?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&main_route=<?php echo str_replace(" " , "_",  $main_route);?>&current_route=<?php echo str_replace(" " , "_",  $current_route);?>&end_point=<?php echo str_replace(" " , "_",  $end_point);?>&row_num=<?php echo $row_num;?>");
$("#point_value").val("<?php echo $point;?>");
$("#point_name").text("<?php echo $point;?>");

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
$("#load_vehicle_account").load("load/vehicle_account.php?point=<?php echo $new_point?>&route_name=<?php echo str_replace(" " , "_",  $route_name);?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&main_route=<?php echo str_replace(" " , "_",  $main_route);?>&current_route=<?php echo str_replace(" " , "_",  $current_route);?>&end_point=<?php echo str_replace(" " , "_",  $end_point);?>&row_num=<?php echo $row_num;?>");
$("#point_value").val("<?php echo $point;?>");
$("#point_name").text("<?php echo $point;?>");
 $("#placeholder_veh").addClass("placeholder");
   $("#placeholder_veh").addClass("placeholder_height");
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
$("#load_vehicle_account").load("load/vehicle_account.php?point=&route_name=<?php echo str_replace(" " , "_",  $route_name);?>&trip_date=<?php echo $trip_date;?>&bus=<?php echo $bus;?>&main_route=<?php echo str_replace(" " , "_",  $main_route);?>&current_route=<?php echo str_replace(" " , "_",  $current_route);?>&end_point=<?php echo str_replace(" " , "_",  $end_point);?>&row_num=<?php echo $row_num;?>");
$("#point_value").val("<?php echo $point;?>");
$("#point_name").text("<?php echo $point;?>");
 $("#placeholder_veh").addClass("placeholder");
   $("#placeholder_veh").addClass("placeholder_height");
});
});
 </script>
          </div>
            </nav>

        <hr class="mg-y-30">

            <label class="az-content-label tx-13 mg-b-20">Attachment </label>
            <div class="az-profile-social-list">
			<div id="load_attachment">
			 
			
			</div>
              <!-- media -->
           <!-- media -->
            </div>    <hr class="mg-y-30">

            <label class="az-content-label tx-13 mg-b-20">Quick settings </label>
            <div class="az-profile-social-list">
	   <nav class="nav az-nav-column az-nav-calendar-event">
            <a href="" class="nav-link" target="_blank"><i class="icon ion ion-md-swap tx-primary"></i> <div>Currency Change</div></a>
            <a href="" class="nav-link" target="_blank"><i class="icon ion ion-md-today tx-success"></i> <div>Add Particulars</div></a>
         
          </nav>
            </div>
          </div><!-- az-mail-menu -->
        </div><!-- side panel -->
        </div><!-- col sidebar -->
        <div class="col-lg-9 col-xl-10 sr-manifest-main-col">
          <div class="sr-content-card az-content-body az-content-body-mail">  
            <div id="placeholder_veh" class="sr-ledger-host w-100">
              <div id="load_vehicle_account" class="sr-vehicle-ledger w-100"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php //include 'modal/income_exp.php';?>
 <!-- az-mail-compose -->

<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/jquery.cookie.js" type="text/javascript"></script>
    <script>

	

	

				

			$(document).on('click', '.remove', function(){

		$(this).closest('tr').remove();

	});	
	
      $(function(){
        'use strict'

        $('#checkAll').on('click', function(){
          if($(this).is(':checked')) {
            $('.az-mail-list .ckbox input').each(function(){
              $(this).closest('.az-mail-item').addClass('selected');
              $(this).attr('checked', true);
            });

            $('.az-mail-options .btn:not(:first-child)').removeClass('disabled');
          } else {
            $('.az-mail-list .ckbox input').each(function(){
              $(this).closest('.az-mail-item').removeClass('selected');
              $(this).attr('checked', false);
            });

            $('.az-mail-options .btn:not(:first-child)').addClass('disabled');
          }
        });

        $('.az-mail-item .az-mail-checkbox input').on('click', function(){
          if($(this).is(':checked')) {
            $(this).attr('checked', false);
            $(this).closest('.az-mail-item').addClass('selected');

            $('.az-mail-options .btn:not(:first-child)').removeClass('disabled');

          } else {
            $(this).attr('checked', true);
            $(this).closest('.az-mail-item').removeClass('selected');

            if(!$('.az-mail-list .selected').length) {
              $('.az-mail-options .btn:not(:first-child)').addClass('disabled');
            }
          }
        });

        $('.az-mail-star').on('click', function(e){
          $(this).toggleClass('active');
        });

      

        $('.az-mail-compose-header a:first-child').on('click', function(e){
          e.preventDefault();
          $('.az-mail-compose').toggleClass('az-mail-compose-minimize');
        })

        $('.az-mail-compose-header a:nth-child(2)').on('click', function(e){
          e.preventDefault();
          $(this).find('.fas').toggleClass('fa-compress');
          $(this).find('.fas').toggleClass('fa-expand');
          $('.az-mail-compose').toggleClass('az-mail-compose-compress');
          $('.az-mail-compose').removeClass('az-mail-compose-minimize');
        });

        $('.az-mail-compose-header a:last-child').on('click', function(e){
          e.preventDefault();
          $('.az-mail-compose').hide(100);
          $('.az-mail-compose').removeClass('az-mail-compose-minimize');
        });


      });
    </script>
	
  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
