<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Azia Responsive Bootstrap 5 Dashboard Template'; ?>
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
</style>

 <!-- az-header -->
<?php
include 'includes/top_header.php';

include 'modal/job_card_exp.php';
?>
 <div id ="upload_modal"class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
     <div class="container" > 
	 <div class="center" style="align-content: center">
    <div class="js-jobcard-dropzone">
        <div class="dz-message needsclick">
            <h1>Drop files here or click to upload.</h1>
        </div>
    </div>
    </div>
</div>
    </div>
  </div>
</div>
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
              
	<button class="btn btn-indigo btn-with-icon btn-block " data-toggle="modal" data-target=".bd-example-modal-lg"><i class="bx bx-plus"></i> Add Expenses</button>

            </nav>
 <br>


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
            <a href="particular.php?main_page=Registration&garage=yes" class="nav-link" ><i class="icon ion ion-md-today tx-success"></i> <div>Add Particulars</div></a>
         
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
    <script src="/accounts/js/template/update/update_jobcard.js"></script>

  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
<script>
(function () {
  if (window.Dropzone) Dropzone.autoDiscover = false;
  var routeName = <?php echo json_encode((string) ($_GET['route_name'] ?? ''), JSON_UNESCAPED_UNICODE); ?>;
  var tripDate = <?php echo json_encode((string) ($_GET['date'] ?? ''), JSON_UNESCAPED_UNICODE); ?>;
  var routeSlug = String(routeName).replace(/ /g, '_');
  var attachUrl = 'load/attachment_jobcard.php?route_name=' + encodeURIComponent(routeSlug) + '&date=' + encodeURIComponent(tripDate);
  var uploadUrl = 'upload/upload_jobcard.php?route_name=' + encodeURIComponent(routeName) + '&date=' + encodeURIComponent(tripDate);
  $(function () {
    if ($('#load_attachment').length) {
      $('#load_attachment').load(attachUrl);
    }
    if (typeof window.srSafeDropzone === 'function') {
      window.srSafeDropzone('.js-jobcard-dropzone', {
        url: uploadUrl,
        width: 300,
        height: 300,
        progressBarWidth: '100%',
        maxFileSize: '5MB',
        init: function () {
          this.on('complete', function () {
            if ($('#load_attachment').length) {
              $('#load_attachment').load(attachUrl);
            }
          });
        }
      });
    }
  });
})();
</script>
<script>
$(document).ready(function(){
	id=<?php echo json_encode((string) ($_GET['id'] ?? ''), JSON_UNESCAPED_UNICODE); ?>;
	route_name=<?php echo json_encode((string) ($_GET['route_name'] ?? ''), JSON_UNESCAPED_UNICODE); ?>;
	date=<?php echo json_encode((string) ($_GET['date'] ?? ''), JSON_UNESCAPED_UNICODE); ?>;
		function myFunction(id) {
				
                        $.ajax(
                            {
                                url: 'search/job_card_details.php',
                                method: 'POST',
                                data: {
                                   id:id,
								   route_name:route_name,
								   date:date
                                },
								  beforeSend: function(){

            
            },
                                success: function (data) {
									
									  $("#placeholder_veh").removeClass("placeholder");

					$("#load_vehicle_account").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}
					myFunction(id);

	   $("#signup").on('submit', function(e){
		   	id="<?php echo $_GET['id'];?>";

        e.preventDefault();
		
		count = 1;
		
error="";
	

Vehicle = $("#Vehicle option:selected").text();
	if(Vehicle=="Choose Vehicle"){
error += "<li>Choose Vehicle.</li>";
	}
	
	item = $("#item option:selected").text();
	if(item=="Choose Item"){
error += "<li>Choose Item.</li>";
	}
	
value_name = $("#qty").val();
	if(value_name==""){
error += "<li>Add Quantity.</li>";
	}
	
	amount = $("#amount").val();
	if(amount==""){
error += "<li>Add amount.</li>";
	}
	
	
	
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
									myFunction(id);

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