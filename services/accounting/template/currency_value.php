<?php
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Tessera Accounts'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.error_class{
	border:1px solid red;
}
table.dataTable th:nth-child(1) {
  width:  250px;
  

}


</style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

<!-- az-header -->
<?php
include 'includes/top_header.php';
?>

 	<?php
$connect = sr_mysqli('account');
$route_full_name = '';
$query = "SELECT fullname FROM route";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $route_full_name .= '<option value="'.$row["fullname"].'">'.$row["fullname"].'</option>';
}

$name_account = '';
$query_account = "SELECT name FROM currency";
$result_account = mysqli_query($connect, $query_account);
while($row_account = mysqli_fetch_array($result_account))
{
 $name_account .= '<option value="'.$row_account["name"].'">'.$row_account["name"].'</option>';
}
?>
	
<?php
include 'modal/currency_value.php';
include 'canvas/currency_value.php';
?>
	

    <div class="">
      <div class="">
     
	 <?php
	 //include 'includes/left_nav.php';
	 ?>
	 <!-- az-content-left -->
        <div class="">
      
<div class="row" style="padding-top:10px;">
<div class="col-md-2 az-content-left-mail">


	           <div class="component-item" style="margin:30px;">
            <label>Add Currency Rate </label>
            <nav class="nav flex-column">
              
	<button class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#modaldemo1"><i class="bx bx-plus"></i> Add Rate</button>

            </nav>



          </div><!-- component-item -->


</div>
<div class="col-md-9">

            <div class="az-content-label mg-b-5">Add Currency Rate</div>
          <table id="example1" class="table">
              <thead>
                  <tr>
				   
                      <th class="wd-20p">Primary Currency </th>
					  <th class="wd-20p">Secondary Currency</th>
                      <th class="wd-20p">Value</th>
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
<script src="/accounts/js/template/insert/currency_value.js"></script>
    <script src="/accounts/js/template/update/currency_value.js"></script>
<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
<script src="/accounts/js/template/fetch/fetch_currency_value.js"></script>
    <script>

	 $('.select2').select2({
            
            searchInputPlaceholder: 'Search',
			dropdownParent: $("#modaldemo1"),
			dropdownParent: $("#offcanvasTop")
          });
    </script>
  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
