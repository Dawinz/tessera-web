$(document).ready(function(){

  route_name = $("#route_name").text();
  start_date= "";
  end_date= "";
  store= "";
    fetch_data(route_name,start_date,end_date,store);
	function fetch_data(route_name,start_date,end_date,store)
 {


 $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
								   "language": {
	     searchPlaceholder: "Search...",
        search: "",
     processing: '<i style="position:absolute;left:50%;top:50%;color:black;"class="fa fa-spinner fa-spin fa-3x fa-fw"></i>Processing...'
},               

'ajax': {
                'url':'fetch_store_luggages.php',
							type:"POST","data": {
       route_name:route_name,
       start_date:start_date,
       end_date:end_date,
       store:store,
    }
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
                ],
				"lengthMenu": [[100]],
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
                 title: '<h3 style="text-align:center;color:black;">SR CLASSICCOACH </h3> <br><u><h5 style="text-align:center;color:black;">STORE INVENTORY</h5></u>',
      messageTop: `
        <div style="margin-bottom:10px;font-weight:bold;font-size:14px;">
            <div></div>
            <div></div>
        </div>
    `,
                 customize: function (win) {

        // Page style
        $(win.document.body).css({
            'font-size': '10px',
            'padding': '10px'
        });

        // Make table fit page
        $(win.document.body).find('table')
            .addClass('compact')
            .css({
                'font-size': '10px',
                'width': '100%',
                'table-layout': 'fixed',
                'word-wrap': 'break-word'
            });

        // Reduce padding
        $(win.document.body).find('table th, table td')
            .css({
                'padding': '4px',
                'border': '1px solid #ddd'
            });

        // Landscape print
        var css = `
            <style>
                @page {
                    size: landscape;
                    margin: 10mm;
                }

                table {
                    width: 100% !important;
                }

                th, td {
                    white-space: normal !important;
                }
            </style>
        `;

        $(win.document.head).append(css);
    },
                //For repeating heading.
                repeatingHead: {
                    logo: 'images/sr.jpg',
                    logoPosition: 'right',
                    logoStyle: '',
                    title: '<h3>Sample Heading</h3>'
                },
			exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6,7,8,10 ]
                }, 
              
              
              },

            { extend: 'pdfHtml5', footer: true ,
customize: function (doc) {

    // Add HTML-like message at top
    doc.content.unshift({
      margin: [0, 0, 0, 10],
      stack: [
        {
          text: 'SR CLASSICCOACH',
          alignment: 'center',
          bold: true,
          fontSize: 14,
          margin: [0, 0, 0, 8]
        },
        {
          stack: [
            { text: '', bold: true },
            { text: '', bold: true }
          ],
          fontSize: 10
        }
      ]
    });

    doc.defaultStyle.fontSize = 7;
    doc.styles.tableHeader.fontSize = 8;
    doc.pageMargins = [10, 10, 10, 10];

    var tableNode = doc.content.find(function (node) {
      return node.table;
    });

    if (tableNode) {
      var colCount = tableNode.table.body[0].length;
      tableNode.table.widths = Array(colCount).fill('*');

      tableNode.layout = {
        hLineWidth: function () { return 0.5; },
        vLineWidth: function () { return 0.5; },
        paddingLeft: function () { return 2; },
        paddingRight: function () { return 2; },
        paddingTop: function () { return 2; },
        paddingBottom: function () { return 2; }
      };
    }
  }
              
            }
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
store = $("#store").val();

 $('#empTable').DataTable().destroy();

fetch_data(route_name,start_date,end_date,store);	
total_amount(route_name,start_date,end_date,store);
	});
         $("#store").change(function(){
route_name = $("#route").val();
start_date = $("#start_date").val();
end_date = $("#end_date").val();
store = $("#store").val();


 $('#empTable').DataTable().destroy();

fetch_data(route_name,start_date,end_date,store);	
	});
$("#start_date").change(function(){
start_date = $("#start_date").val();
route_name = $("#route").val();
end_date = $("#end_date").val();
store = $("#store").val();


 $('#empTable').DataTable().destroy();
fetch_data(route_name,start_date,end_date,store);	

	});
$("#end_date").change(function(){
start_date = $("#start_date").val();
end_date = $("#end_date").val();
route_name = $("#route").val();
store = $("#store").val();
 $('#empTable').DataTable().destroy();
fetch_data(route_name,start_date,end_date,store);	

	});
    
	 oTable = $('#empTable').DataTable();   //pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said
$('#search').keyup(function(){
      oTable.search($(this).val()).draw() ;
});



});