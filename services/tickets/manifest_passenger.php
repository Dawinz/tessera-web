<?php
  $trip_date = $_GET['trip_date'];
  $route_day_id = $_GET['id'];
  $route_id = $_GET['route_id'];

?>
  
  
  <style>
  input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
		  .dt-button.red {
   color: #fff;
  background:none;
   background-color: #0379BB;
   border-radius: 05px;
   border:none;
    }
  .dataTables_filter input {
        width: 250px;
        height: 32px;
        background: #fcfcfc;
        border: 1px solid #aaa;
        border-radius: 5px;
    
        text-indent: 10px;
    }
 
    .dataTables_filter .fa-search {
        position: absolute;
        top: 10px;
        left: auto;
        right: 10px;
    }
.dataTables_filter {
    display: none;
}
#myInputTextField
{
width:200px;
margin-bottom:4px;
display:none;
}#search_user_manifest
{
position: relative; 
left:705px;
}
 .complete_seat{
	 background:#DA1616;
	   border-radius: 50px;
	   padding:5px;
	   color:white;
	   box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
 } .half_complete_seat{
	 background:green;

	   border-radius: 50px;
	   padding:5px;
	   color:white;
	   box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
 }.booked_seat{
	 background:#FFE100;

	   border-radius: 50px;
	   padding:5px;
	   color:black;
box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; }
 </style>
 <div>
 <div class="col-lg-4"style="padding:8px;position:relative;right:220px;top:25px;">
						<form autocomplete="off" >
         <input type="text" id="search_user_manifest" class="form-control" placeholder="Search....">
		 </form>
      </div>
   <table class="table mb-0" id="passenger_info_manifest"style="width:100%;">
                                                              <thead class="table-light">
<tr>                 
<th>seat no</th>
<th>Ticket number</th>


<th>Fullname</th>
<th>Cost</th>
<th>Booking Date </th>

<th>Trip date </th>

</tr>
				
                          </thead>
						                 	 
                                          <!-- end tbody --> 
                                            </table>
			</div>
<script>
$(document).ready(function(){
				
route_day_id_modal_manifest = "<?php echo  $route_day_id;?>";
route_id_modal_manifest = "<?php echo  $route_id;?>";
trip_date_modal_manifest = "<?php echo  $trip_date;?>";
		
	   $('#passenger_info_manifest').DataTable({
				
                'processing': true,
                'serverSide': true,
				
                'serverMethod': 'post',
				"order": [[ 1, "desc" ]],
				
                'ajax': {
                'url':'fetch_user_booking_info_transfer.php',
				 type:"POST","data": {
        "route_id_modal_manifest":route_id_modal_manifest,"route_day_id_modal_manifest":route_day_id_modal_manifest,"trip_date_modal_manifest":trip_date_modal_manifest
    },
                },'columns': [
                    
					  { data: 'seat_no' },
					
                      { data: 'id' },
					
				
					  { data: 'fullname' }, 
					  { data: 'cost' },	
					   { data: 'book_date_time' },	
					  { data: 'trip_date' },
					
				
					
					
              
                ],
				"lengthMenu": [60, 90],
			dom: 'lfrti',
    



rowCallback: function(row, data, index) {
      if (data.status == "Due payment") {
    	
$(row).css('background-color','#ffe8f0');

      } 
	  if (data.status == "To be Payed"){
	$(row).css('background-color','#ffffff');	  

	  } if (data.status == "paid"){
	  	$(row).css('background-color','#99ff9c');


	  }
    },
            });
			
					 oTable2 = $('#passenger_info_manifest').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#search_user_manifest').keyup(function(){
      oTable2.search($(this).val()).draw() ;
}); 

});
											</script>