<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Vehicle Category'; ?>
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
    <div class=" ">
      <div style="padding-right:10px;">
     

	 
	 <!-- az-content-left -->
        <div class="az-content-body pd-lg-l-40 d-flex flex-column">
      
<br>
          <div class="az-content-label mg-b-5">Vehcile Category</div>
		  	<button style="width:200px;"class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#modaldemo1"><i class="bx bx-plus"></i> Add Category</button>

 
<?php
include 'modal/vehicle_category_details.php';
include 'canvas/particular.php';
?>  



          <div><br>
		 
            <table id="example1" class="table">
              <thead>
                  <tr>
				   
                      
                     <th class="wd-20p">Category</th>
                      <th class="wd-20p">Vehicles</th> 
                      <th class="wd-20p">Delete</th>
                     
	
                     
                  
                  </tr>
              </thead>
              
            </table>
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
<script src="/accounts/js/template/insert/vehicle_partiuclar_category.js"></script>

    <script src="/accounts/js/template/update/particular.js"></script>
    <script src="/accounts/js/template/dependence/route_dependence.js"></script>
	    <script src="/accounts/js/template/fetch/fetch_vechile_category_details.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>

  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
