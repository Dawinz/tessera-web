<?php
session_start();


function routes() {
 								  include 'connect.php';
									   $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='".$_SESSION['username']."' and preference='1' order by id desc");
$select_num12=mysqli_num_rows($select12);
if($select_num12 ==0){
echo "  <option>You Have no route</option>";
}else if($select_num12 ==1){
	
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
		}
}else if($select_num12 >=2){
	echo "<option>Choose Route</option>";
	   	WHILE($get132=mysqli_fetch_array($select12)){
$route_full_name =$get132['route_full_name']; 
echo "  <option>".$route_full_name."</option>";
}
}

}



?>                        

									<form id="rgform">
									 <h5>Receiver Information</h5>
									<table style="width:100%;">
									<tr>
									<td>
									
                                             
                                                    
                                                        <label class="form-label" for="validationCustom01">Fullname</label>
                                        <input  style="text-transform:uppercase" type="text" class="form-control" id="r_firstname"placeholder="Reciver's Fullname">
                                                   
                                               
                                                
									</td>
									
									<td>
									       <div >
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Phone Number</label>
                                        <input type="text" class="form-control" id="r_no"placeholder="phone number start with 255" value ="+243">
                                                        <div class="valid-feedback" id="s_no_response"style="display:none;position:absolute;background-color:white;z-index:1000;">
                                                        
                                                        </div>
                                                    </div>
                                                </div>
									</td>
									</tr>

									<tr>
									<td>
									 <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Route</label>
                                       <select class="form-select"id="trip"><?php

									  routes();
	
									   ?></select>                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                   
									</td>
									
									<td>
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
									</td>
									</tr>
											<tr>
									<td>
									
                                                <div >
                                                 
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Price</label>
                                        <input type="text" class="form-control" id="amount"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
									</td>
									
									<td>
									       <div >
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Loan Price</label>
                                        <input type="text" class="form-control" id="loan"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
									</td>
									</tr>	
									
									<tr>
									<td>
									
                            
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Loan Price</label>
                                        <input type="text" class="form-control" id="loan"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                
									</td>
									
								
									</tr>	
									<tr>
									<td colspan=2>
									
                            
                                         <div class="mb-3">
                                                            <label for="basicpill-address-input"
                                                                class="form-label">Detail</label>
                                                            <textarea id="summernote" class="form-control"
                                                                rows="2">NGUO</textarea>
                                                        </div>
                                                
									</td>
									
								
									</tr>
									
									</table>

   
									   
	
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

  <button type="submit" name="submit" id="submit_button"class="btn btn-primary btn-lg btn-block"id="savebtn">save</button> 

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
<script src="replace_no.js"></script>
<script src="due_pament.js"></script>
<script  src="insert_script.js"></script> 
 <script src="currency.js"></script>
         <script src="assets/libs/imask/imask.min.js"></script>

        <!-- form mask init -->
        <script src="assets/js/pages/form-mask.init.js"></script>
<script>
$(document).ready(function(){
		$("#close").click(function(){
								$("#rgform").trigger("reset");

			$("#load_register").load("load_register.php");
	});		$("#close2").click(function(){
							$("#rgform").trigger("reset");

			$("#load_register").load("load_register.php");
	});
});

$(document).ready(function(){
	
	var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

		html += '<tr style="">';

		html += '<td style="width:450px;padding:10px;"> <label class="form-label" for="validationCustom01">Fullname</label><input style="widht:1000px" style="text-transform:uppercase" type="text" class="form-control" id="r_firstname"placeholder="Reciver Fullname"></td>';
		html += '<td style="width:450px;padding:10px;"> <label class="form-label" for="validationCustom01">Phone Number</label><input style="widht:1000px" style="text-transform:uppercase" type="text" class="form-control" id="r_no"placeholder="Phone Number"></td>';
html += '</tr>';	

html += '<tr >';

		html += '<td style="padding:10px;width:450px"><label class="form-label" for="validationCustom01">Route </label> <select class="form-select"id="trip" style="widht:1000px" ><?php?></select></td>';
		html += '<td style="padding:10px;width:450px"> <label class="form-label" for="validationCustom01">Payment Option </label><select class="form-select"id="status_em"style="widht:1000px" ></select></td>';
html += '</tr>';	
		
			html += '<tr >';

		html += '<td style="padding:10px;width:450px"> <label class="form-label" for="validationCustom01">Price</label><input style="widht:1000px" class="form-control" id="amount"   placeholder="Transport fees"  value="" ></td>';
		html += '<td style="padding:10px;width:450px"> <label class="form-label" for="validationCustom01">Loan Price</label><input style="widht:1000px" class="form-control" id="amount2"placeholder="Transport fees" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"></td>';
html += '</tr>';	

	html += '<tr style="border-bottom:1px solid grey;" >';

		html += '<td  colspan=2 style="padding:10px;width:1000px"> <label class="form-label" for="validationCustom01">Detail</label> <textarea id="summernote" style="width:890px" class="form-control"rows="2">NGUO</textarea><hr></td>';
html += '</tr>';	


		return html;

	}
	
	$(document).on('click', '#btnradio1', function(){

		count++;

		$('#rgform').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');

	});

	
});
	$(document).on('keyup', '#amount', function(){
formatCurrency($(this));
	});
	function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  
  // get input value
  var input_val = input.val();
  
  // don't validate empty input
  if (input_val === "") { return; }
  
  // original length
  var original_len = input_val.length;

  // initial caret position 
  var caret_pos = input.prop("selectionStart");
    
  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);
    
    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }
    
    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = "" + left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = "" + input_val;
    
    // final formatting
    if (blur === "blur") {
      input_val += "";
    }
  }
  
  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}


</script>