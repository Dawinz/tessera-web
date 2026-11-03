<?php
require_once __DIR__ . '/includes/tessera_staff_boot.php';
session_start();
?>                        

	<?php
						$id= $_GET['u'];
											include'connect2.php';
											$select1=mysqli_query($db,"SELECT *  FROM parcel_info WHERE id='$id' ");
$select_num1=mysqli_num_rows($select1);

if($select_num1 !=0){
   	WHILE($get13=mysqli_fetch_array($select1)){
    	    $s_name=$get13['s_name'];   
		    $s_surname=$get13['s_surname']; 
			$s_no=$get13['s_no']; 
			$r_name=$get13['r_name']; 

            $r_surname=$get13['r_surname'];   
		    $r_no=$get13['r_no']; 
			$account=$get13['account']; 
			$date=$get13['date'];  			
			
			$detail=$get13['detail'];   
		    $status=$get13['status']; 
			$amount=$get13['amount']; 
			$code=$get13['code'];  			
		
		    $payment_status=$get13['payment_status'];   
		    $rec_status=$get13['status']; 
			$question=$get13['question']; 
			$answer=$get13['answer'];  			
			
			$store=$get13['store']; 
			$due=$get13['due'];  
					$loan=$get13['loan'];  

	}
}
	
											?>   
<span id="code_send"style="display:none;"><?php echo $code;?></span>									
			<div class="card card-h-100">
                                            <div class="card-body">
                                               <div class="row">
											     <h5>Sender Informations</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Fullname</label>
                                        <input type="text"  style="text-transform:uppercase"class="form-control" id="firstname"placeholder="Sender's fullname"value ="<?php echo "$s_name";?>">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Phone Number</label>
                                        <input type="text" class="form-control" id="s_no"placeholder="phone number start with 255"value ="<?php echo "$s_no";?>">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>       <div class="row">
									 <h5>Receiver Information</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Fullname</label>
                                        <input  style="text-transform:uppercase" type="text" class="form-control" id="r_firstname"placeholder="Reciver's Fullname"value ="<?php echo "$r_name";?>">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Phone Number</label>
                                        <input type="text" class="form-control" id="r_no"placeholder="phone number start with 255" value ="<?php echo "$r_no";?>">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>       <div class="row">
												 <h5>Trip Info</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Route</label>
                                       <select class="form-select"id="trip">
									    <option><?php echo "$account";?></option>
                                                    <?php

									  
									  include 'connect2.php';
									   $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='".$_SESSION['username']."' and  preference='1' order by id desc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 ==0){
echo "  <option>You Have no route</option>";
}else if($select_num12 ==1){
	
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
		}
}else if($select_num12 >=2){

	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
}
}

									   ?>
											
                                                        </select>                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Payment Option</label>
                                                   <select class="form-select"id="status_em">
												   	<option><?php echo "$status";?></option>
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
                                            </div>       <div class="row">
									<h5>Payment and Security</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Price</label>
                                        <input type="text" class="form-control" id="amount"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value ="<?php echo "$amount";?>" data-type="currency">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Quantity</label>
                                        <input type="text" class="form-control" id="question"placeholder="quantity"value ="<?php echo "$question";?>">
                                                        <div class="valid-feedback">
                                        <input type="text" class="form-control" id="answer"placeholder="due"value ="<?php echo $due;?>">
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                            </div>  
											
											<?php
											if(($status=="To be Payed") and ($account="Tunduma to Lubumbashi")){
												?>
																		<div class="row"id="loan_area" style="">
									<h5>Loan Information</h5>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Loan Price</label>
                                        <input type="text" class="form-control" id="loan"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php echo $loan;?>" data-type="currency">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div><!-- end col -->
                                         <!-- end col -->
                                            </div>  
												
												<?php
											}
											?>
											
											
											
  <div class="row">											     
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="basicpill-address-input"
                                                                class="form-label">Detail</label>
                                                            <textarea id="summernote" class="form-control"
                                                                rows="2"><?php echo base64_decode($detail); ?></textarea>
                                                        </div>
                                                    </div><!-- end col -->
                                                </div>		
                                    <button type="button" id="insert"class="btn btn-success"><i class="uil uil-check me-2"></i> <span id="saving">Save</span></button>
												</div>
		 <div class="modal fade exampleModalFullscreen" tabindex="-1"
                                            aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalFullscreenLabel">Print reciepts</h5>
                                                        <button type="button" class="btn-close close_load" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
<div id="container_print"style="background-color:white;"></div>                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary close_load"
                                                            data-bs-dismiss="modal">Close</button>
                                                   
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
   <div id="due_modal" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myModalLabel">Modal Heading</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">

                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <form>
  <div class="form-group">
   <label for="exampleInputEmail1">Due payment form</label><br>

        <input type="input" class="form-control" id="due_amount" aria-describedby="emailHelp" placeholder="Luggage Ammount"pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php echo $amount;?>" data-type="currency"> 

	<br>
    <input type="input" class="form-control" id="due_amount2" aria-describedby="emailHelp" placeholder="Paid Ammount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="<?php echo @$due;?>" data-type="currency"> <br>

  <button type="button" class="btn btn-primary btn-lg btn-block"id="savebtn">save</button> 

  </div>
  
  
  </form>
                                                    </div>
                                               

                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
												<script  src="update_script.js"></script> 
												 <script src="currency.js"></script>
												 <script src="due_pament.js"></script>
												 <script src="option.js"></script>