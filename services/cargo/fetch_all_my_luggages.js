$(document).ready(function(){

    route_name = $("#route_name").text();
  start_date= "";
  end_date= "";
  username= $("#username_id").val();
    fetch_data(route_name,start_date,end_date,username);
	function fetch_data(route_name,start_date,end_date,username)
 {


 var myCargoLang = (window.TesseraStates && TesseraStates.listLanguage)
	? Object.assign(
		TesseraStates.listLanguage('No cargo yet', 'No cargo matches your search'),
		{
			searchPlaceholder: "Search...",
			search: "",
			processing: '<i style="position:absolute;left:50%;top:70%;color:black;"class="fa fa-spinner fa-spin fa-3x fa-fw"></i>Processing...'
		}
	)
	: {
	     searchPlaceholder: "Search...",
        search: "",
     processing: '<i style="position:absolute;left:50%;top:70%;color:black;"class="fa fa-spinner fa-spin fa-3x fa-fw"></i>Processing...'
};
 $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
								   "language": myCargoLang,                'ajax': {
                'url':'fetch_all_my_luggages.php',
							type:"POST","data": {
       route_name:route_name,
       start_date:start_date,
       end_date:end_date,
       username:username,
    },
				error: (window.TesseraStates && TesseraStates.loadError)
					? TesseraStates.loadError('Could not load cargo')
					: undefined
                },'columns': [
         
				 { data: 'code' },
                               { data: 'store' },
					{ data: 's_name' },
                    { data: 'r_name' },
                   
					   { data: 'date' },
					  { data: 'Weight' }, 
					  { data: 'detail' }, 
					  { data: 'quantity' },
					  { data: 'to_be' },
					
					  { data: 'paid' },
                      					   { data: 'amount' },

			    { data: 'edit' }, 
			    { data: 'print' }, 
			    { data: 'delete' }, 
                ],
				"lengthMenu": [[50]],
				dom: 'Bfrtip',
        buttons: [
		
                { extend: 'copyHtml5'},
            { extend: 'excelHtml5'},
            { extend: 'csvHtml5' },
			{ extend: 'print'},
            { extend: 'pdfHtml5' }
        ], 
				dom: 'Bfrtip',
        buttons: [
                { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
			{ extend: 'print', footer: true,

   autoPrint: true,
                title: ' ',

                //For repeating heading.
                repeatingHead: {
                    logo: 'images/sr.jpg',
                    logoPosition: 'right',
                    logoStyle: '',
                    title: '<h3>Sample Heading</h3>'
                },
			exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6,7,8,10 ]
                }, },
            { extend: 'pdfHtml5', footer: true }
        ], 	 columnDefs: [ 
{
       targets: 0,
      createdCell: function (td, cellData, rowData, row, col) {
		          $(td).css('font-weight', 'bold'); 
				
      }
    }
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
        }
         $("#route").change(function(){
route_name = $("#route").val();
start_date = $("#start_date").val();
end_date = $("#end_date").val();
route_name = "account='"+route_name+"' and  ";
username= $("#username_id").val();
 $('#empTable').DataTable().destroy();

fetch_data(route_name,start_date,end_date,username);	
	});
$("#start_date").change(function(){
start_date = $("#start_date").val();
route_name = $("#route").val();
end_date = $("#end_date").val();
route_name = "account='"+route_name+"' and ";
username= $("#username_id").val();

 $('#empTable').DataTable().destroy();
fetch_data(route_name,start_date,end_date,username);	

	});
$("#end_date").change(function(){
start_date = $("#start_date").val();
end_date = $("#end_date").val();
route_name = $("#route").val();
route_name = "account='"+route_name+"' and ";
username= $("#username_id").val();

 $('#empTable').DataTable().destroy();
fetch_data(route_name,start_date,end_date,username);	

	});
    
	 oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#search').keyup(function(){
      oTable.search($(this).val()).draw() ;
});
});