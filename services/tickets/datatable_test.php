 <div class="col-lg-4"style="padding:3px;">
						<form autocomplete="off" >
         <input type="text" id="search_user" class="form-control" placeholder="Search....">
		 </form>
      </div>
   <table class="table mb-0" id="passenger_info"style="width:100%;">
                                                              <thead class="table-light">
<tr>                 

<th>Ticket number</th>

<th>seat no</th>
<th>Fullname</th>
<th>Cost</th>
<th>Booking time </th>

<th>start_location </th>
<th>ending_location</th>
<th>Issure</th>

</tr>
				
                          </thead>
						                 	 
                                          <!-- end tbody --> 
                                            </table>
											
											<script>
											$(document).ready(function(){
	   $('#passenger_info').DataTable({
				
                'processing': true,
                'serverSide': true,
				
                'serverMethod': 'post',
				"order": [[ 0, "desc" ]],
				
                'ajax': {
                'url':'fetch_user_booking.php',
				 type:"POST"
                },'columns': [
                    
				
					
                      { data: 'id' },
					
					  { data: 'seat_no' },
					  { data: 'fullname' }, 
					  { data: 'cost' },	
					   { data: 'book_date_time' },	
					  { data: 'start_location' },
                     { data: 'ending_location' },
									  
					  { data: 'issuer' },
				
					
					
              
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