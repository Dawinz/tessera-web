<?php
session_start();

$username=$_SESSION['username'];
 if(isset($username)){
	 
 }else{
		 header("Location: log_out.php");
 
 }

?>
<style>
iframe{
	height:1000px;
	width:100%;
}
</style>
				<?php
				
				date_default_timezone_set('Africa/Dar_es_Salaam');
$date_now = date('Y-m-d', time());
$date_now2 = date('Y-m-d', time());
	$id= $_GET['u'];

	include 'connect.php';
$select2=mysqli_query($db,"SELECT *  FROM parcel_info where id = '$id' ");
$select_num2=mysqli_num_rows($select2);


$select1=mysqli_query($db,"SELECT *  FROM parcel_info where id = '$id'");
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
		  $status=$get13['status'];
		  $date=$get13['date_insert'];
		  $store=$get13['store']; 
		  $quantity=$get13['question'];
		  $scanned=$get13['scanned'];
		  $uploader=$get13['uploader'];
		  $amount=$get13['amount'];
		  $to_be=$get13['to_be'];  
		  $paid=$get13['paid'];
		  $time=$get13['time'];
		
	}
}
	?>
	


<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Check Manifest</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

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
                                
                                    <div class="card-body"> 

								
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                       
  <tbody style="margin:80px;">
    <tr >
      <th scope="row">.</th>
      <td>Sender Name</td>
      <td><?php echo "$s_name";?></td>
	  <td> <button id="printer"class="btn btn-sm btn-soft-primary">Print <i class="mdi mdi-printer-check align-middle"></i></button>
	  <a href="register_luggage.php"  class="btn btn-sm btn-soft-secondary">Home <i class="mdi mdi-arrow-right align-middle"></i></a>
</td>
    </tr>
    <tr>
      <th scope="row">.</th>
      <td>Sender's No</td>
      <td><?php echo $s_no;?></td>
	  <td></td>
    </tr>
    <tr>
      <th scope="row">.</th>
      <td>Reciver Name</td>
      <td><?php echo "$r_name ";?></td>
	  	  <td></td>

    </tr> 
	<tr>
      <th scope="row">.</th>
      <td>Reciver no</td>
      <td><?php echo "$r_no";?></td>
	  	  <td></td>

    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Trip</td>
      <td><?php echo  $trip_name ;?> </td>
	  	  <td></td>

    </tr>
	<tr>
      <th scope="row">.</th>
      <td><strong>Code</strong></td>
      <td ><span id="code"><strong><?php echo $code?></strong></span> 
	  </td>
	  	  <td></td>

    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Detail</td>
      <td><?php echo base64_decode($detail)?></td>
	  	  <td></td>

    </tr>	
	<tr>
      <th scope="row">.</th>
      <td>Quantity</td>
      <td><?php echo "$scanned /$quantity"?></td>
	  	  <td></td>

    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Status</td>
      <td><?php echo $status?></td>
	  	  <td></td>

    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Store</td>
      <td><?php echo $store?></td>
	  	  <td></td>

    </tr>
	<tr>
      <th scope="row">.</th>
      <td>Date</td>
      <td><?php echo date("l jS\-m-Y", strtotime($date));?> at <?php echo $time;?></td>
	  	  <td></td>

    </tr>		
	<tr>
      <th scope="row">.</th>
      <td>Charged by </td>
      <td><?php echo $uploader?></td>
	  	  <td></td>

    </tr><tr>
      <th scope="row">.</th>
      <td>Total Ammount </td>
      <td><?php echo number_format($amount,2)?></td>
	  	  <td></td>

    </tr><tr>
      <th scope="row">.</th>
      <td>Total To pay </td>
      <td><?php echo number_format($to_be,2)?></td>
	  	  <td></td>

    </tr>	<tr>
      <th scope="row">.</th>
      <td>Total paid </td>
      <td><?php echo number_format($paid,2)?></td>
	  	  <td></td>

    </tr>	

  </tbody>
  

</table>      

<?php
  if($store == "store"){
	  echo '

	  ';
  }else{
	  
  
  ?>
      <?php
  $select1=mysqli_query($db,"SELECT *  FROM manifest where code = '$code'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 ==0){

}else{
	
?>

             <div class="flex-grow-1">
              <div class="card-header bg-transparent">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-0">Luggage Manifest <span style="color:#00C292"> Detail</span></h5>
                                            </div>
                                  
                                        </div>
                                    </div>
                                            </div>
		 <table class="table">

  <tbody>
 
	    <?php
		
			$username=$_SESSION['username'];
$select222=mysqli_query($db,"SELECT *  FROM  assigned_routes  where username='$username' and route_full_name='$trip_name'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $status2 =$get122['status']; 

}}
		
  $select1=mysqli_query($db,"SELECT *  FROM manifest where code = '$code'");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
		
    	    $trip_date=$get13['trip_date']; 
 $bus=$get13['bus'];
 $amount=$get13['amount']; 
 $id=$get13['id'];  
 $checked=$get13['checked']; 
 
   $select12=mysqli_query($db,"SELECT *  FROM vehicle_name where name = '$bus' and date='$trip_date'");
$select_num12=mysqli_num_rows($select12);

if($select_num12 !=0){
   	WHILE($get132=mysqli_fetch_array($select12)){
    	    $Driver=$get132['Driver']; 
 $Conductor=$get132['Conductor']; 
 $disable=$get132['disable']; 
	
	}
}
if($status2 != "sender"){
if($checked==""){
	?>
		
				<span id="id<?php echo $id?>"style="display:none;"><?php echo $id?></span>
				<span id="ct<?php echo $id?>"style="display:none;"><?php echo $checked?></span>
				<span id="checked<?php echo $id?>"style="display:none;"></span>
	   <tr style="padding:10px;">
      <th scope="row"></th>
	 <td id="first<?php echo $id?>"> 
	<span >Destination store</span><br>
	 Bus <br>Trip date<br>Total Ammount <br>Driver <br> Conductor</td>
      <td id="secon<?php echo $id?>">
	 
	  <span id="store<?php echo $id?>" >STORE</span><br>
	  <?php echo  $bus;?>,<br><?php echo date("l jS\-m-Y", strtotime($trip_date));?>,<br><?php echo number_format($amount,2)?>,<br><?php echo $Driver?>,<br><?php echo $Conductor?>
	  <br><br><button id="clear<?php echo $id?>"style="background:#C82333;"type="button" class="btn btn-danger"><i class="uil uil-exclamation-octagon me-2"></i> <span id="cText<?php echo $id?>">Clear</span></button></td>
	     </tr>
<script>
		$(document).ready(function(){
		$("#clear<?php echo $id?>").click(function(){
		var id=$("#id<?php echo $id?>").text();
		
		
		var checked = $("#checked<?php echo $id?>").text();
		var ct = $("#ct<?php echo $id?>").text();
		if (checked=="out"){
			swal({
  title: "Are you sure?",
   text: "You are about to uncheck the luggage!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
	  $("#cText<?php echo $id?>").text("clearing..");
		$("#clear<?php echo $id?>").prop("disabled", true);
   $.ajax({
				url:"check_recieved.php",
				method:"POST",
				data:{id:id},
				success:function(response){
				if($.trim(response)!=""){
						
						
									$("#cText<?php echo $id?>").text("Unclear");
$("#first<?php echo $id?>").css("color", "red");
						$("#second<?php echo $id?>").css("color", "red");
												$("#store<?php echo $id?>").text("OUT");
					
						}else{
			$("#first'.$id.'").css("color", "black");
						$("#second<?php echo $id?>").css("color", "black");
						$("#cText<?php echo $id?>").text("Clear");
						$("#store<?php echo $id?>").text("STORE");

						}
							$("#clear<?php echo $id?>").prop("disabled", false);
		$("#checked<?php echo $id?>").text(response);
	
	}
			
			});
  } else {
   
  }
});
		}else{
			$("#cText<?php echo $id?>").text("clearing..");
		$("#clear<?php echo $id?>").prop("disabled", true);
		  $.ajax({
				url:"check_recieved.php",
				method:"POST",
				data:{id:id},
				success:function(response){
				if($.trim(response)!=""){
						
						
									$("#cText<?php echo $id?>").text("Unclear");
$("#first<?php echo $id?>").css("color", "red");
						$("#second'.$id.'").css("color", "red");
												$("#store'.$id.'").text("OUT");
					
						}	else{
			$("#first<?php echo $id?>").css("color", "black");
						$("#second<?php echo $id?>").css("color", "black");
						$("#cText<?php echo $id?>").text("Clear");
						$("#store<?php echo $id?>").text("STORE");

						}
							$("#clear<?php echo $id?>").prop("disabled", false);
		$("#checked<?php echo $id?>").text(response);
	
	}
			
			});	
		}
	
			});	
			});
			
				</script>
	
	<?php
}else{
	?>
	
			
				<span id="id<?php echo $id?>"style="display:none;"><?php echo $id;?></span>
				<span id="ct<?php echo $id?>"style="display:none;"><?php echo $checked; ?></span>
				<span id="checked<?php echo $id?>"style="display:none;"></span>
				<tr>
				
				</tr>
	   <tr style="padding:10px;">
      <th scope="row"></th>
	 <td style="color:red;"id="first<?php echo $id?>"> 
	
	 <span >Destination store</span><br>
	 Bus <br>Trip date<br>Total Ammount <br>Driver <br> Conductor</td>
      <td id="second<?php echo $id?>"style="color:red;">
	 
	   <span id="store<?php echo $id?>" >OUT</span><br>
	  <?php echo $bus;?>,<br><?php echo date("l jS\-m-Y", strtotime($trip_date));?>,<br><?php echo number_format($amount,2)?>,<br> <?php echo $Driver ?>,<br><?php echo $Conductor?>
	  <br><br><button id="clear<?php echo $id?>"style="background:#C82333;"type="button" class="btn btn-danger"><i class="uil uil-exclamation-octagon me-2"></i> <span id="cText<?php echo $id?>">Unclear</span></button></td>
	     </tr>
<script>
		$(document).ready(function(){
		$("#clear<?php echo $id?>").click(function(){
		var id=$("#id<?php echo $id?>").text();
		
		var checked = $("#checked<?php echo $id?>").text();
		var ct = $("#ct<?php echo $id?>").text();
		if( (ct=="out") || (checked=="out")){
			swal({
  title: "Are you sure?",
   text: "You are about to uncheck the luggage!",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
$("#cText<?php echo $id?>").text("clearing..");
		$("#clear<?php echo $id?>").prop("disabled", true);
		  $.ajax({
				url:"check_recieved.php",
				method:"POST",
				data:{id:id},
				success:function(response){
				if($.trim(response)!=""){
						$("#cText<?php echo $id?>").text("Unclear");
$("#first<?php echo $id?>").css("color", "red");
						$("#second'<?php echo $id?>").css("color", "red");
							$("#store<?php echo $id?>").text("OUT");
						}	else{
					
							
								$("#first<?php echo $id?>").css("color", "black");
						$("#second<?php echo $id?>").css("color", "black");
						$("#cText<?php echo $id?>").text("Clear");
						$("#store<?php echo $id?>").text("STORE");
						$("#ct<?php echo $id?>").text("");
						
						}
							$("#clear<?php echo $id?>").prop("disabled", false);
		$("#checked<?php echo $id?>").text(response);
	
	}
			
			});
  } else {

  }
});
		}else{
			$("#cText<?php echo $id?>").text("clearing..");

			  $.ajax({
				url:"check_recieved.php",
				method:"POST",
				data:{id:id},
				success:function(response){
				if($.trim(response)!=""){

						$("#cText<?php echo $id?>").text("Unclear");
$("#first'<?php echo $id?>").css("color", "red");
						$("#second<?php echo $id?>").css("color", "red");
							$("#store<?php echo $id?>").text("OUT");
						}	else{
					
							
								$("#first<?php echo $id?>").css("color", "black");
						$("#second<?php echo $id?>").css("color", "black");
						$("#cText<?php echo $id?>").text("Clear");
						$("#store<?php echo $id?>").text("STORE");
						$("#ct<?php echo $id?>").text("");
						
						}
							$("#clear<?php echo $id?>").prop("disabled", false);
		$("#checked<?php echo $id?>").text(response);
	
	}
			
			});
		}
	
			});	
			});
			
				</script>

<?php	
}

	}else{
	
	
	if($checked==""){
		?>
		
	
	   <tr style="padding:10px;">
      <th scope="row"></th>
	 <td id="first<?php echo $id?>"> 
	  <span>Days left</span><br>
	 <span >Destination store</span><br>
	 Bus <br>Trip date<br>Total Ammount <br>Driver <br> Conductor</td>
      <td id="second<?php echo $id?>'">
	   <span id=""><?php
	  $new_trip_date = date('Y-m-d', strtotime($trip_date. ' + 4days'));
	  $trip_date2 = new DateTime($new_trip_date);
$date_now = new DateTime($date_now2);
	 $interval = $date_now->diff($trip_date2);
	 $interval=  $interval->format("%r%a");
	
	 	if($disable!=""){
			echo " Arrived succefull  <img src='images/lik_suc.png'>";
		}else{
	
		
	if($interval < 0){
	echo "Delevery exceed time ($interval)";
	}else{
		echo "$interval days left ";	
		}
		}
	  ?></span><br>
	  <span id="store<?php echo $id?>" >STORE</span><br>
	  <?php echo $bus?>,<br><?php echo date("l jS\-m-Y", strtotime($trip_date)); ?>,<br><?php echo number_format($amount,2)?>,<br><?php echo $Driver?><br><?php echo $Conductor?>

	  </td>
	     </tr>

	
	<?php

}else{
	
	?>
		
				
	   <tr style="padding:10px;">
      <th scope="row"></th>
	 <td style="color:red;"id="first<?php echo $id?>"> 
	  <span>Days left</span><br>
	 <span >Destination store</span><br>
	 Bus <br>Trip date<br>Total Ammount <br>Driver <br> Conductor</td>
      <td id="second<?php echo $id?>"style="color:red;">
	     <span id=""><?php
	  $new_trip_date = date('Y-m-d', strtotime($trip_date. ' + 4days'));
	  $trip_date2 = new DateTime($new_trip_date);
$date_now = new DateTime($date_now2);
	 $interval = $date_now->diff($trip_date2);
	 $interval=  $interval->format("%r%a");
	
	 	if($disable!=""){
			echo " Arrived succefull  <img src='images/lik_suc.png'>";
		}else{
	
		
	if($interval < 0){
	echo "Delevery exceed time ($interval)";
	}else{
		echo "$interval days left ";	
		}
		}
	  ?></span><br>
	   <span id="store<?php echo $id?>" >OUT</span><br>
	  <?php echo $bus?>,<br><?php echo date("l jS\-m-Y", strtotime($trip_date));?>,<br><?php echo number_format($amount,2)?>,<br><?php echo $Driver ?>,<br><?php echo $Conductor?>
	  
	  </td>
	     </tr>


	<?php
}

	
	
	
	}


	}
}
  ?>
  
  

 
 



<?php
}
  }
  ?>

  </tbody>
  
</table> 
</div><!-- end table responsive -->
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
		    <script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
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
               <div class="modal fade exampleModalFullscreen" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
										<script>
										
										$(document).ready(function(){
								$("#printer").click(function(){
									$('.exampleModalFullscreen').modal('show');
	var placeholder = document.createElement("div");
placeholder.innerHTML = "<strong>Loading...</strong>";

var iframe = document.createElement("iframe");
iframe.style.display = "none";

var сontainer =  document.getElementById("container_print");

// Прикреплять к контейнеру нужно сразу, так как "appendChild" после события "load" вызовет повторную загрузку и рекурсивное событие load.
сontainer.appendChild(placeholder);
сontainer.appendChild(iframe);

iframe.src = "print_ticket.php?id=<?php echo $code;?>";
iframe.addEventListener("load", function(event) {
  console.log("DOM fully loaded and parsed", event.target.src);
  
  сontainer.removeChild(placeholder);
  event.target.style.display = "";
});	

								});
										});
										</script>