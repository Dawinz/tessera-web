<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Responsive Bootstrap 5 Dashboard Template">
    <meta name="author" content="BootstrapDash">

    <title>Azia Responsive Bootstrap 5 Dashboard Template</title>

    <!-- vendor css -->
    <link href="../lib/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="../lib/typicons.font/typicons.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css" rel="stylesheet">

<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- azia CSS -->
    <link rel="stylesheet" href="../css/azia.css">

    <script src="../lib/jquery/jquery.min.js"></script>
	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<link rel="stylesheet" type="text/css" href="dropzone/dropzone.css" />
  </head>
  <body>
  <style>
.placeholder_height{
	height:600px;
	z-index: 1000;
}
</style>
 <!-- az-header -->
<?php
include 'includes/top_header.php';
//include 'drop_down/drop_down.php';
?>
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
        </div><!-- az-content-left -->
		<div class="az-content-body az-content-body-mail col-8">  
<div class="placeholder-glow" >		
<span class="placeholder col-12 placeholder_height" id="placeholder_veh" >     
			 <div id="load_vehicle_account">
			  
			  
			  </div>
			  </span>
			  </div>
			  </div>

        

      </div><!-- container -->
    </div><!-- az-content -->
<?php //include 'modal/income_exp.php';?>
 <!-- az-mail-compose -->




    <div class="az-footer ht-40">
      <div class="container ht-100p pd-t-0-f">
        <span>&copy; 2024 Azia Responsive Bootstrap 5 Dashboard Template</span>
      </div><!-- container -->
    </div><!-- az-footer -->

    <script src="../lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="../lib/ionicons/ionicons.js"></script>
  
	
    <script src="../js/azia.js"></script>
	<script src="../js/template/sweatalert2.js"></script>
	

    <script src="../js/jquery.cookie.js" type="text/javascript"></script>
	
	
<script type="text/javascript" src="dropzone/dropzone.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js" type="text/javascript"></script>  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" type="text/javascript"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js" type="text/javascript"></script>
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
	
  </body>
</html>
