<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
require_once __DIR__ . '/includes/luggage_mysqli.php';

try {
	$db = luggage_mysqli();
} catch (RuntimeException $e) {
	http_response_code(503);
	echo '<p class="text-muted p-3">Cargo database unavailable. Try again shortly.</p>';
	exit;
}

$username = (string) ($_SESSION['username'] ?? '');
$assignedRoutes = luggage_assigned_routes_for_user($db, $username);
$select_num12 = count($assignedRoutes);
?>                        

									<form id="rgform">
                 						<div class="row">
  
											     <h5>Sender Informations</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Fullname</label>
                                        <input type="text"  style="text-transform:uppercase"class="form-control" id="firstname"placeholder="Sender's fullname">
                                                        <div class="valid-feedback">
                                                         
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Phone Number</label>
                                        <input type="text" class="form-control" id="s_no"placeholder="phone number start with 255"value ="+255">
                                                        <div class="valid-feedback "id="r_no_response"style="display:none;position:absolute;background-color:white;z-index:1000;">
                                                    
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>       <div class="row">
									 <h5>Receiver Information</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Fullname</label>
                                        <input  style="text-transform:uppercase" type="text" class="form-control" id="r_firstname"placeholder="Reciver's Fullname">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Phone Number</label>
                                        <input type="text" class="form-control" id="r_no"placeholder="phone number start with 255" value ="+243">
                                                        <div class="valid-feedback" id="s_no_response"style="display:none;position:absolute;background-color:white;z-index:1000;">
                                                        
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>       <div class="row">
												 <h5>Trip Info</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Route</label>
                                       <select class="form-select"id="trip"><?php
if ($select_num12 === 0) {
	echo '<option>You Have no route</option>';
} elseif ($select_num12 === 1) {
	$label = htmlspecialchars(luggage_route_label($assignedRoutes[0]), ENT_QUOTES, 'UTF-8');
	echo '<option>' . $label . '</option>';
} else {
	echo '<option>Choose Route</option>';
	foreach ($assignedRoutes as $routeRow) {
		$label = htmlspecialchars(luggage_route_label($routeRow), ENT_QUOTES, 'UTF-8');
		echo '<option>' . $label . '</option>';
	}
}
									   ?></select>                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Payment Option</label>
                                                   <select class="form-select"id="status_em">
                                                    <option>Payment Status</option>
											<option>To be Payed</option>
											<option>paid</option>
											<option style="color:red;" data-bs-toggle="modal"
                                            data-bs-target="#myModal">Due payment</option>
											
                                                        </select>                                                         <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>    
											<div class="row">
									<h5>Payment and Security</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Price</label>
                                        <input type="text" class="form-control" id="amount"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Quantity</label>
                                        <input type="number" class="form-control" id="question"placeholder="quantity">
                                                        <div class="valid-feedback">
                                        <input type="text" class="form-control" id="answer"placeholder="due">
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div> 	

											<div class="row"id="loan_area" style="display:none;">
									<h5>Loan Information</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Loan Price</label>
                                        <input type="text" class="form-control" id="loan"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                         <!-- end col -->
                                            </div>  
  <div class="row">											     
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="basicpill-address-input"
                                                                class="form-label">Detail</label>
                                                            <textarea id="summernote" class="form-control"
                                                                rows="2">NGUO</textarea>
                                                        </div>
                                                    </div><!-- end col -->
                                                </div>		
                                    <button type="button" id="insert"class="btn btn-primary"><i class="uil uil-check me-2"></i> <span id="saving">Save</span></button>
									   
   
									   <script  src="insert_script.js"></script> 
	
</form>
     <div id="due_modal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Due Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <form>
  <div class="form-group">
   <label for="exampleInputEmail1">Due payment form</label><br>

        <input type="input" class="form-control" id="due_amount" aria-describedby="emailHelp" placeholder="Luggage Ammount"pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"> 

	<br>
    <input type="input" class="form-control" id="due_amount2" aria-describedby="emailHelp" placeholder="Paid Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"> <br>

  <button type="button" class="btn btn-primary btn-lg btn-block"id="savebtn">save</button> 

  </div>
  
  
  </form>
                                                    </div>
                                               

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
									         <div class="modal fade exampleModalFullscreen" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"id="close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal"id="close2">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div> 
		<script src="js/sweetalert.js"></script>
<script src="option.js"></script>
<script src="due_pament.js"></script>

 <script src="currency.js"></script>
<script>
$(document).ready(function(){
		$("#close").click(function(){
			
			$("#load_register").load("load_register.php");
	});		$("#close2").click(function(){
			$("#load_register").load("load_register.php");
	});
});

</script>