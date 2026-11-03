<?php require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Job Card'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
.error_class{
	border:1px solid red;
}

.select2-dropdown {  
  z-index: 10060 !important;/*1051;*/
}


#ui-datepicker-div {
z-index: 10000 !important;
}

</style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

<!-- az-header -->
<?php
include 'includes/top_header.php';
include 'modal/jobcard.php';

?>
 <div class="row"style="padding-top:10px;">
 <div class="col-md-2 az-content-left-mail">
 
          <div class="component-item "style="margin:30px;">
            <label>Add New Jobcard</label>
            <nav class="nav flex-column">
              
	<button class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-plus"></i> Add Jobcard</button>

            </nav>

           <br> <label>Select Route Option</label>
            <nav class="nav flex-column">
<button class="btn btn-indigo  btn-block change_r first_b " id="" data="">All Routes</button>
<?php
include 'connect/connect.php';
$select=mysqli_query($db,"SELECT fullname,name FROM   route   where status='main'  ");
$select_num=mysqli_num_rows($select);
if($select_num !=0){
   	WHILE($get=mysqli_fetch_array($select)){
    	    $route_name =$get['fullname']; 
    	    $route_name_int =$get['name']; 
?>

<button class="btn btn-dark  btn-block change_r first_b" id="route_name='<?php echo $route_name;?>' and" data="<?php echo $route_name;?>"><?php echo base64_decode($route_name_int);?></button>
<?php
    	  
	}
}
?>

            </nav>


          </div><!-- component-item -->

 </div>
 <div class="col-md-9">
 
      <div class="az-content-label mg-b-5 cname_route"style="position:absolute;color:grey;top:15px;">All Routes</div>


 <!-- modal -->

          <div><br>
		 
            <table id="example1" class="table">
              <thead>
                  <tr >
				   
                     
					  <th > Date</th>
                      
                       <th >Name</th>
					                          <th >Ammount</th>

                       <th >Insert</th>
                       <th >Edit</th>
                       <th >Delete</th>
					
                     
                  
                  </tr>
              </thead>
              
            </table>
          </div>
 </div>
 
 </div>
<script src="/accounts/js/template/insert/jobcard.js"></script>
    <script src="/accounts/js/template/update/update_jobcard_first.js"></script>

	    <script src="/accounts/js/template/fetch/fetch_jobcard.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
 
  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
