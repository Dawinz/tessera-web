<?php
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
$vehicle= $_GET['vehicle'];

?>

				<?php
	$id =$_GET['code'];
	$select2=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$id' and store='out' and scanned='0'");
$select_num2=mysqli_num_rows($select2);

if($select_num2 !=0){

}
$select1=mysqli_query($db,"SELECT *  FROM parcel_info where code = '$id'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $s_name=$get13['s_name']; 
			$s_surname=$get13['s_surname'];
			$s_no=$get13['s_no'];
			$r_no=$get13['r_no']; 
			$r_name=$get13['r_name'];   
            $r_surname=$get13['r_surname'];   
		 $trip_name=$get13['account'];
		  $detail=$get13['detail'];
		  $code=$get13['code']; 
 $due=$get13['due'];
		  $scanned=$get13['scanned'];  
		  $cost=$get13['amount'];  
		  $s_q= $get13['scanned'];
		  $quantity= $get13['question'];
		  $amount_remain= $get13['amount_remain'];
		  $status=$get13['status']; 
			  $paid=$get13['paid']; 
			  	  $to_be=$get13['to_be']; 

				  $due_to_pay=$get13['answer']; 
			  	  $due_paid_s=$get13['payment_status']; 
		
	}
}
	?>
	<?php

$select1=mysqli_query($db,"SELECT *  FROM manifest where code = '$id'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    
		  $addcode=$get13['code'];  
		  $to_be_remain =$get13['to_be'];


	}
}
?>
	<span id="due"style="display:none;"><?php
	echo $due;
	?></span>
	<span id="date_insert"style="display:none;"><?php echo $_GET['date']; ?></span>
	<?php 
	
if(($quantity > $scanned) and ($scanned != 0)){
		echo '
<div id="equal"style="display:none;">equal</div>
	';
}else{

}
?>

<span id="quantity"style="display:none;"><?php echo $quantity;?></span>
<span id="scanned" style="display:none;"><?phpecho $scanned;?></span>
<span id="scan" style="display:none;"><?php echo $s_q;?></span>
<span id ="time_remain" style="display:none;"><?php echo $time_remain;?></span>
<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <?php $tessera_page_label = 'Check manifest'; include __DIR__ . '/includes/tessera_page_head.php'; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
<!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    </head>
<style>

</style>
    
    <body>

    <!-- <body data-layout="horizontal"> -->

        <div class="authentication-bg min-vh-100">
            <div class="bg-overlay bg-white"></div>
            <div class="container">
                <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                    <div class="row justify-content-center my-auto">
                           <div class="col-xxl-4 col-xl-6"style="width:60%; overflow-y: hidden; ">
                                <div class="card">
                                    <div class="card-header justify-content-between d-flex align-items-center">
                                        <h4 class="card-title"id="status_check"><?php echo $status;?></h4>
                                        <a href="vehicle_view.php" target="_blank" class="btn btn-sm btn-soft-secondary">Home <i class="mdi mdi-arrow-right align-middle"></i></a>
                                    </div><!-- end card header -->
                                    <div class="card-body"> 

			<h3 style="display:none;"><br> 
			<br>LEFT amount : <span id="amount_remain_new"><?php 
			if($status=="To be Payed"){
	  if($amount_remain==0){
		  echo $to_be;
	  }else{
echo $amount_remain;
	  }
	 
			}else if($status=="Due payment"){
				if($amount_remain==0){
 echo $to_be;
 }else{
echo $amount_remain;
}
			} else if($status=="paid"){
				  if($amount_remain==0){
					 echo $paid; 
				  }else{
					echo "$amount_remain";  
				  }
				
			}

			?></span>
			</h3>									
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                       
                                       <tbody>


  
	
	<tr>

      <td>Sender's  Name</td>
      <td><?php echo  $s_name ;?></td>
    </tr><tr>
      <td>Reciver's  Name</td>
      <td><?php echo  $r_name ;?></td>
    </tr><tr>
      <td>Trip</td>
      <td><?php echo  $trip_name ;?></td>
    </tr>
	<tr>
      <td>Code</td>
      <td id="code"><?php echo $code?></td>
    </tr>	<tr>
      <td>Ammount</td>
      <td id="code"><?php echo number_format($cost ,2)?></td>
    </tr><tr>
      <td>To Be Payed</td>
      <td id="code"><?php 
	  if($amount_remain==0){
		   echo number_format($to_be ,2);
	  }else{
		  if($status=="Due payment"){
			  		 echo number_format($due_to_pay ,2)  ;
		  }if($status=="To be Payed"){
			  		 echo number_format($amount_remain ,2)  ;
		  }

	  }
	 

	  ?></td>
    </tr><tr>
      <td>Paid</td>
      <td id="code"><?php 
	 
	  if($status=="Due payment"){
		  echo number_format($due_paid_s ,2)  ; 
		
	  }else if($status=="paid"){
		  	  if($amount_remain==0){
				   echo number_format($paid ,2);
	 }else{
		 	 echo number_format($amount_remain ,2)  ;

	  }
	
		  }else {
			  echo "0.00";
		  }
	  ?></td>
    </tr>
	  <?php
	  if($status !="Due payment"){
		  ?>
	<tr>
	
      <td>Amount Left</td>
      <td id="code">
	  <?php 
			if($status=="To be Payed"){
				if($amount_remain==0){
	echo $to_be;
}else{
	echo $cost - $to_be;
}
			}else if($status=="Due payment"){
				if($amount_remain==0){
	echo $s_surname;
}else{
	echo $s_surname - $to_be;
}
			} else if($status=="Payed"){
				
			}

			?>
	  </td>
	
    </tr>
	  <?php
	  }
	  ?>
	<tr>
      <td>Detail</td>
      <td><?php echo base64_decode($detail)?></td>
    </tr>	


  </tbody>
  

</table>      
<br>
<div id="due_topay_man"style="display:none;"><?php echo $due_to_pay;?></div>
<div id="due_pay_man"style="display:none;"><?php echo $due_paid_s;?></div>
<?php
if($quantity != 0){
?>

            <h5><span style="color:red"> <?php echo $scanned;?></span>, Luggages are in the store</h5>

             <div class="row">
               
                            
                            
                                <div class="mb-3">
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="number"placeholder="QUANTITY"value = "<?php 	if (($quantity == 1) OR ($scanned=="1")){echo "1";}?>">
                                    </div>
                                </div>   <div class="form-group ic-cmp-int">
                                    <div class="form-ic-cmp">
                                     
                                    </div>
									
									<?php
									if($status=="paid"){
											if ($quantity == 1){
												?>
												   <div class="nk-int-st">
                                        <input type="text" style="display:none;" class="form-control" id="topaid" placeholder="Manifest Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php echo $paid;?>
										" data-type="currency">
                                    </div>		
												<?php
											
										}else{
											?>
											   <div class="nk-int-st">
                                        <input type="text" class="form-control" id="topaid" placeholder="Manifest Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php
										  if($amount_remain==0){
											  echo number_format($paid);
										  }else{
											echo number_format($amount_remain);  
										  }?>
										" data-type="currency">
                                    </div>
											<?php
											
										}
									}else{
									if($quantity == 1){
									?>
									      <div class="nk-int-st" style="d">
                                        <input type="number" style="display:none;"class="form-control" id="topaid"value="<?php
										if($status=="paid"){
											echo "$paid";
										}else if ($status=="To be Payed") {
											echo "$to_be";
										} else if($status == "Due payment"){
											echo "$s_surname";
										}
										?>
										">
                                    </div>
									<?php 	
									}else if($status=="Due payment"){
									?>
                                    <div class="nk-int-st">
                                        <input type="text" class="form-control" id="topaid" placeholder="To pay Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php
										  if($amount_remain==0){
											  echo number_format($to_be);
										  }else{
											echo number_format($due_to_pay);  
										  }?>
										" data-type="currency">
                                    </div>  <div class="nk-int-st"><br>
                                        <input type="text" class="form-control" id="due_topaid" placeholder="Payment Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php
										  if($amount_remain==0){
											  echo number_format($paid);
										  }else{
											echo number_format($due_paid_s);  
										  }?>
										" data-type="currency">
                                    </div>
									<?php
									}else if ($status=="To be Payed"){
										?>
				   <div class="nk-int-st">
                                        <input type="text" class="form-control" id="topaid" placeholder="Manifest Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php
										  if($amount_remain==0){
											  echo number_format($to_be);
										  }else{
											echo number_format($amount_remain);  
										  }?>
										" data-type="currency">
                                    </div>
										<?php
									}
									}
									?>
                                </div>
                          
                        </div>
						    <div id="choosen"style="display:none;"><?php echo $vehicle;?></div>
						<?php
}else{
	
}
?>
	<br>	

 <div class="col-md-6">	
<button id="send" class="btn btn-info w-100">Post</button>
            <br>  <br>
        </div> </div><!-- end table responsive -->
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div>
                    </div><!-- end row -->


                </div>
            </div><!-- end container -->
        </div>
        <!-- end authentication section -->
    
        <!-- JAVASCRIPT -->
				 <script src="js/vendor/jquery-1.12.4.min.js"></script>

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/bootstrap-jquery-bridge.js"></script>
        <script src="assets/libs/metismenujs/metismenujs.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
		    <script src="assets/js/alertify.min.js"></script>
        <!-- notification init -->
        <script src="assets/js/pages/notification.init.js"></script>
<script src="js/sweetalert.js"></script>
<script src="save.js"></script>
    </body>
</html>
<script>

$(document).ready(function(){
	$(function(){ $('#number').focus(); });
});
</script>
