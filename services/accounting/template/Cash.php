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


#ui-datepicker-div {
z-index: 10000 !important;
}

</style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

<!-- az-header -->
<?php
include 'includes/top_header.php';
?>
 <div class="row"style="padding-top:10px;">
 <div class="col-md-2 az-content-left-mail">
 
          <div class="component-item "style="margin:30px;">
            <nav class="nav flex-column">
              
	<button class="btn btn-indigo btn-with-icon btn-block" data-bs-toggle="modal" data-bs-target="#modaldemo1"><i class="bx bx-plus"></i> Add Cash</button>

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
 
<?php
include 'modal/add_cash.php';
?>

 <!-- modal -->

          <div><br>
		 
            <table id="example1" class="table">
              <thead>
                  <tr >
				   
                      <th  data-orderable="false">Authorizes </th>
					  <th >Effective Date</th>
					  <th >Cash </th>
					  <th >V no</th>
					  <th >Balance</th>
					  <th >Date insert</th>
                      
               
               
                  
                  </tr>
              </thead>
              
            </table>
          </div>
 </div>
 
 </div>
<script src="/accounts/js/template/insert/cash.js"></script>
	    <script src="/accounts/js/template/fetch/fetch_cash.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
    
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
 <script>
  $( function() {
    $( ".datepicker" ).datepicker();
	            $(".datepicker").attr("readonly", true);

  } );
  </script>