        <link href="assets/libs/alertifyjs/build/css/alertify.min.css" rel="stylesheet" type="text/css" />
<div id="result_holder"></div>
<?php
session_start();
include 'connect.php';
include 'page_info_reload.php';


function routes($db) {
 								  
									   $select12=mysqli_query($db,"SELECT *  FROM  assigned_routes where username='".$_SESSION['username']."'  order by id desc");
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

function country_code($db){
  $select_code=mysqli_query($db,"SELECT end_route,start_route  FROM  assigned_routes where username='".$_SESSION['username']."' and preference='1' order by id desc limit 1");
$select_num_code=mysqli_num_rows($select_code);
if($select_num_code !=0){
  	WHILE($get_code=mysqli_fetch_array($select_code)){
$end_route =$get_code['end_route']; 
$start_route =$get_code['start_route']; 


		}
}
$select_no=mysqli_query($db,"SELECT   code  FROM  country_code order by  FIELD(route_name,'$end_route') Desc");
$select_num_no=mysqli_num_rows($select_no);
if($select_num_no !=0){
  	WHILE($get_no=mysqli_fetch_array($select_no)){
$code_c =$get_no['code']; 
echo '<option value="'.$code_c.'">'.$code_c.'</option>';

    }}

     }

?>                        

									<form id="rgform">
									<input type="hidden" type ="text" id="s_name_new" name ="firstname"value="">
									<input type="hidden" type ="text" id="s_no_new"name="sender_no">
									
									<table style="width:100%;" id="item_table">
						
									
									</table>
									
						    <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                   <input type="submit" name="submit" id="submit_button" class="btn btn-primary" value="Register" />

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                       <button type="button" class="btn btn-danger" id="btnradio1"><i class="uil uil-plus me-2"></i> Add Reciver</button>
                                        </ol>
                                    </div>

                                </div>
                            </div>
									
									
<div >
								
							</div>
   
									   
	
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
													<div id="amount_id" style="display:none;"></div>
                                                    <form>
  <div class="form-group">
   <label for="exampleInputEmail1">Due payment form</label><br>

        <input type="input" class="form-control" id="due_amount" placeholder="Luggage Ammount" value="" data-type="currency"> 

	<br>
    <input type="input" class="form-control" id="due_amount2"  placeholder="Paid Ammount"  value="" data-type="currency"> <br>

  <button type="button" name="submit" class="btn btn-primary btn-lg btn-block"id="savebtn">save</button> 

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
		<script src="js/sweatalert2.js"></script>




<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.4/qz-tray.js"></script>
 <script src="currency.js"></script>
<script src="assets/libs/imask/imask.min.js"></script>
<script src="assets/libs/alertifyjs/build/alertify.min.js"></script>
<script src="assets/js/app.js"></script>
<script  src="insert_script.js<?php echo "?v=".modified_time("insert_script.js").""?>"></script> 
<script src="due_pament.js"></script>
	 <script src="replace_no.js"></script>
	 <script src="refresh_page.js"></script>
<script>

$(document).ready(function(){



	var count = 0;
	
	function add_input_field(count)
	{

		var html = '';

	html += '<tr id="tr1'+count+'">';

		html += '<td  colspan=2 style="padding:10px;">  <button type="button" class="btn btn-danger remove" id="remove'+count+'"><i class="uil uil-ban"></i></button></td>';
html += '</tr>';


		html += '<tr id="tr2'+count+'">';

		html += '<td style="width:450px;padding:10px;"> <label class="form-label" for="validationCustom01">Reciever Fullname</label><input style="widht:1000px" style="text-transform:uppercase" type="text" class="form-control r_firstname" id="r_firstname'+count+'"placeholder="Reciver Fullname"name="r_firstname[]"></td>';
		html += '<td ><label class="form-label" for="validationCustom01">Reciever Number</label><div class="input-group"><div class="input-group-text"> <select name="rcode[]" class="no_code"style="background:none;border:none;color:#7E93A0;" id="rcode'+count+'"><?php country_code($db);?></select></div><input style=""type="text" class="form-control r_no " id="r_no'+count+'"placeholder="Phone Number"name="r_no[]" placeholder=""></div><div style="z-index: 100;display:none;position: absolute;height:fill-content" class="card response_card"id="search_container'+count+'"></div></td>';
html += '</tr>';	

html += '<tr id="tr3'+count+'">';

		html += '<td style="padding:10px;width:450px"><label class="form-label" for="validationCustom01">Route </label> <select class="form-select  route_name route'+count+'"id="trip'+count+'" style="widht:1000px" name="routs[]"><?php routes($db);?></select></td>';
		html += '<td style="padding:10px;width:450px"> <label class="form-label" for="validationCustom01">Payment Option </label><select class="form-select status_em_open status_em'+count+'"id="status_em'+count+'" name="status_em[]" style="widht:1000px ;font-size:20px;" class="select_option" ><option>Payment Status</option><option>To be Payed</option><option>paid</option><option style="color:red;" data-bs-toggle="modal"data-bs-target="#myModal">Due payment</option></select></td>';
html += '</tr>';	
		
			html += '<tr  id="tr4'+count+'">';

		html += '<td style="padding:10px;width:450px"> <label class="form-label" for="validationCustom01">Price</label><input style="widht:1000px" class="form-control amount amount_fees" id="amount_new'+count+'"  name="amount[]" placeholder="Transport fees"   ></td>';
		html += '<td style="padding:10px;width:450px"> <label class="form-label" for="validationCustom01">Quantity</label><input style="widht:1000px" class="form-control quantity" id="quantity'+count+'"  name="quantity[]" placeholder="Quantity"   ></td>';
html += '</tr>';	

html += '</tr>';
html += '<tr  id="tr5'+count+'">';
		html += '<td style="padding:10px;width:450px"><label class="form-label" for="validationCustom01">Weight</label> <div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend2"><select class="weight_name'+count+' amount" name="weight_name[]"style="background:none;border:none;height:23px;outline: none;font-size:16px;"><option>kg</option><option>g</option></select></span>       </div><input type="text" name="weight_value[]"class="form-control" id="validationDefaultUsername" placeholder="weight" aria-describedby="inputGroupPrepend2" ></div><input style="widht:1000px;display:none;" class="form-control" name="amount_left[]" id="answer'+count+'"></td>';
		html += '<td style="padding:10px;width:450px"> <label class="form-label" for="validationCustom01">Luggage Value</label><input style="widht:1000px" class="form-control amount" id="luggage_value'+count+'"  name="Value[]" placeholder="Value"   ></td>';
html += '</tr>';	

	html += '<tr id="tr6'+count+'" >';

		html += '<td  colspan=2 style="padding:10px;width:700px"> <label class="form-label" for="validationCustom01">Detail</label> <textarea name="detail[]" style="width:690px" class="form-control"rows="2">NGUO</textarea></td>';
html += '</tr>';	


		return html;

	}
	$('#item_table').append(add_input_field(0));
	$(document).on('click', '#btnradio1', function(){

		count++;

		$('#item_table').append(add_input_field(count));

		$('.selectpicker').selectpicker('refresh');

	});

	
});

	$(document).on('click', '.remove', function(){

delete_id = $(this).attr('id');
delete_id = delete_id.replace(/\D/g,'');
$('#tr1'+delete_id+'').remove();
$('#tr2'+delete_id+'').remove();
$('#tr3'+delete_id+'').remove();
$('#tr4'+delete_id+'').remove();
$('#tr5'+delete_id+'').remove();
$('#tr6'+delete_id+'').remove();
	});



$(document).on('change','.status_em_open', function(){
my_advc_id = $(this).attr('id');
my_advc_id = my_advc_id.replace(/\D/g,'');
$("#amount_id").text(my_advc_id);
var status_em = $("#status_em"+my_advc_id+" option:selected" ).text();
	var trip = $("#trip"+my_advc_id+" option:selected" ).text();
   
  
   if(status_em=="To be Payed"){
	  
	 

	
   }else if(status_em=="paid"){
	     Swal.fire({
  title: "Payed",
  html: "You have select paid",
  width: 600,
  padding: "3em",
  color: "",
  backdrop: `rgba(2, 123, 5, 0.61)`
});
   }else if(status_em=="Due payment"){
	   	   $('#due_modal').modal('show');

	 	     Swal.fire({
  title: "Due payment",
  html: "You have select Due Payment",
  width: 600,
  padding: "3em",
  color: "",
  backdrop: `rgba(238, 63, 94, 0.45)`
});
   }


});






$( "body" ).click(function() {
$(".response_card").hide();
});


	$(document).on('keyup', '.amount', function(){
formatCurrency($(this));
	});		
	
	$(document).on('keyup', '#loan', function(){
formatCurrency($(this));
	});	
	
	
	$(document).on('keyup', '.quantity', function(){
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