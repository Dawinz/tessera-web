$(document).ready(function(){
	   $('#passenger_info').DataTable({
				
                'processing': true,
                'serverSide': true,
				
                'serverMethod': 'post',
				"order": [[ 0, "desc" ]],
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
                    
				
					
                      { data: 'id' },
					  { data: 'bus' },
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