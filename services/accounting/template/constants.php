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

<!-- az-header -->
<?php
include 'includes/top_header.php';
?>
    <div class="">
      <div class="">
     
	 <?php
	 //include 'includes/left_nav_constant.php';
	 ?>
	 <?php
include 'modal/constants.php';
?>
	 <!-- az-content-left -->
        <div class="">
       

<?php include 'canvas/update_constants.php';?>
 

 <div id="modaldemo2" class="modal " tabindex="-1">
      <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content modal-content-demo">
          <div class="modal-header">
            <h6 class="modal-title route_name_holder" ></h6>
            <button type="button" class="close " data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
		   <form id="insert_form" enctype="multipart/form-data">

          <div class="modal-body">
            <input type="hidden" name="currency_r" value ="" id="currency_r"/>
 	<div class="table-repsonsive">
							<span id="error"></span>
							<table class="table table-bordered" id="item_table">
								<tr>
									<th>Currency</th>
									
									<th><button type="button" name="add" class="btn btn-success btn-sm add "><i class="fas fa-plus"></i></button></th>
								</tr>
							</table>
						
						</div>
		  </div>
          <div class="modal-footer">
            <button type="button"class="btn btn-indigo" id="add_currency">Save changes</button>
            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div><!-- modal-dialog -->
    </div><!-- modal -->
</form>
        <div class="row" style="padding-top:10px;">
	  <div class="col-md-2 az-content-left-mail">
	           <div class="component-item" style="margin:30px;">
            <label>Add New Constant </label>
            <nav class="nav flex-column">
              
	<button class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#modaldemo1"><i class="bx bx-plus"></i> Add Constant</button>

            </nav>



          </div><!-- component-item -->

	  </div>
	  <div class="col-md-9">
	            <div class="az-content-label mg-b-5">Constants</div>
	      <table id="example1" class="table">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">Route Name</th>
                      <th class="wd-20p">Route Category</th>
					  <th class="wd-20p">Category</th>
                      <th class="wd-20p">Particular</th>
                      <th class="wd-20p">Currency</th>
                      <th class="wd-20p">Affect </th>
                       <th class="wd-20p">Value</th>
                       <th class="wd-20p"> Type</th>
                       <th class="wd-20p">Edit</th>
                       <th class="wd-20p">Delete</th>

                     
                  
                  </tr>
              </thead>
              
            </table>
	  </div>
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
<script src="/accounts/js/template/insert/constants.js"></script>
    <script src="/accounts/js/template/update/update_constant.js"></script>
	    <script src="/accounts/js/template/fetch/fetch_constant.js"></script>
	    <script src="/accounts/js/template/addon/currency_multiple.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
    
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
<script>

</script>