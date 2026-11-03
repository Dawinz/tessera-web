<?php
include 'connect.php';

$username=$_SESSION['username'];

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	   
			$level =$get12['role']; 


		
	}
}?>
 
 <style>
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
 <div class="card sr-table-panel" data-sr-loading-label="Loading ticket records...">
 <div class="card-body p-0 pt-2">
 <div class="col-lg-4 px-3" style="padding:8px;">
						<form autocomplete="off" >
         <input type="text" id="search_user" class="form-control" placeholder="Search....">
		 </form>
      </div>
	  <div class="table-responsive px-2 pb-3">
   <table class="table mb-0" id="passenger_info"style="width:100%;">
                                                              <thead class="table-light">
<tr>                 
<th>Seat no</th>
<th>Ticket number</th>


<th>Fullname</th>


<th>Cost</th>
<th>Trip date </th>
<th>View</th>
<th>Edit </th>
<th>Reseverd</th>
<th>Clear</th>
<th>Print</th>
<th>Unlock</th>


<th>Transfer</th>
<th>Delete</th>


</tr>
				
                          </thead>
						                 	 
                                          <!-- end tbody --> 
                                            </table>
	  </div>
 </div>
 </div>
											
											<script>
											$(document).ready(function(){
									
	   $('#passenger_info').DataTable({
				
                'processing': true,
                'serverSide': true,
				
                'serverMethod': 'post',
				"order": [[ 1, "desc" ]],
				language: (window.TesseraStates && TesseraStates.listLanguage)
					? TesseraStates.listLanguage('No bookings yet', 'No bookings match your search')
					: undefined,
                'ajax': {
                'url':'fetch_user_booking_info.php',
				 type:"POST",
				 error: (window.TesseraStates && TesseraStates.loadError)
					? TesseraStates.loadError('Could not load bookings')
					: undefined
                },'columns': [
                    
					  { data: 'seat_no' },
					
                      { data: 'id' },
					
				
					  { data: 'fullname' }, 
					 
					  { data: 'cost' },	
					  
					  { data: 'trip_date' },
					  { data: 'view' },
					  					  { data: 'edit' },

                     { data: 'reserve' },
					 { data: 'clear' },
									  
					  { data: 'print' },
					  { data: 'lock' },
					  

  { data: 'transfer' },	 
					  { data: 'delete' },

					  
					
				
					
					
              
                ],
				"lengthMenu": [[5, 35, 45, -1], [30, 35, 45, "All"]],
			dom: 'Bfrtip',
          buttons: [
            'copy', 'csv', 'excel', 'pdf',

 { extend: 'print', footer: true,
       
   autoPrint: true,
                title: '',

                //For repeating heading.
                repeatingHead: {
                    logo: 'images/sr.jpg',
                    logoPosition: 'right',
                    logoStyle: '',
                    title: '<h3>Sample Heading</h3>'
                } },
        ], 



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
			
					 oTable2 = $('#passenger_info').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#search_user').keyup(function(){
      oTable2.search($(this).val()).draw() ;
}); 
});
											</script>