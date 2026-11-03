
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<title>DataTables example - Export titles and messages</title>
	<link rel="shortcut icon" type="image/png" href="/media/images/favicon.png">
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.datatables.net/rss.xml">
	<link rel="stylesheet" type="text/css" href="/media/css/site-examples.css?_=0602f7ec58abe00302963423bf7a8d5a">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
	<style type="text/css" class="init">
	
	</style>
	<script type="text/javascript" src="/media/js/site.js?_=30648b1410332bada11fa3ff8050ff88"></script>
	<script type="text/javascript" src="/media/js/dynamic.php?comments-page=extensions%2Fbuttons%2Fexamples%2Fhtml5%2FtitleMessage.html" async></script>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
	<script type="text/javascript" language="javascript" src="../../../../examples/resources/demo.js"></script>
	<script type="text/javascript" class="init">
	


$(document).ready(function() {
	var printCounter = 0;

	// Append a caption to the table before the DataTables initialisation
	$('#example').append('<caption style="caption-side: bottom">A fictional company\'s staff table.</caption>');

	$('#example').DataTable( {
		dom: 'Bfrtip',
		buttons: [
			'copy',
			{
				extend: 'excel',
				messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
			},
			{
				extend: 'pdf',
				messageBottom: null
			},
			{
				extend: 'print',
				messageTop: function () {
					printCounter++;

					if ( printCounter === 1 ) {
						return 'This is the first time you have printed this document.';
					}
					else {
						return 'You have printed this document '+printCounter+' times';
					}
				},
				messageBottom: null
			}
		]
	} );
} );



	</script>
</head>
<body class="wide comments example">
	<a name="top" id="top"></a>
	<div class="fw-background">
		<div></div>
	</div>
	<div class="fw-container">

		<div class="fw-body">
			<div class="content">
			
	
				<div class="demo-html">
					<table id="example" class="display" style="width:100%">
						<thead>
							<tr>
								<th>Name</th>
								<th>Position</th>
								<th>Office</th>
								<th>Age</th>
								<th>Start date</th>
								<th>Salary</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Tiger Nixon</td>
								<td>System Architect</td>
								<td>Edinburgh</td>
								<td>61</td>
								<td>2011/04/25</td>
								<td>$320,800</td>
							</tr>
							<tr>
								<td>Garrett Winters</td>
								<td>Accountant</td>
								<td>Tokyo</td>
								<td>63</td>
								<td>2011/07/25</td>
								<td>$170,750</td>
							</tr>
							<tr>
								<td>Ashton Cox</td>
								<td>Junior Technical Author</td>
								<td>San Francisco</td>
								<td>66</td>
								<td>2009/01/12</td>
								<td>$86,000</td>
							</tr>
							<tr>
								<td>Cedric Kelly</td>
								<td>Senior Javascript Developer</td>
								<td>Edinburgh</td>
								<td>22</td>
								<td>2012/03/29</td>
								<td>$433,060</td>
							</tr>
							<tr>
								<td>Airi Satou</td>
								<td>Accountant</td>
								<td>Tokyo</td>
								<td>33</td>
								<td>2008/11/28</td>
								<td>$162,700</td>
							</tr>
							<tr>
								<td>Brielle Williamson</td>
								<td>Integration Specialist</td>
								<td>New York</td>
								<td>61</td>
								<td>2012/12/02</td>
								<td>$372,000</td>
							</tr>
							<tr>
								<td>Herrod Chandler</td>
								<td>Sales Assistant</td>
								<td>San Francisco</td>
								<td>59</td>
								<td>2012/08/06</td>
								<td>$137,500</td>
							</tr>
						
						</tbody>
						<tfoot>
							<tr>
								<th>Name</th>
								<th>Position</th>
								<th>Office</th>
								<th>Age</th>
								<th>Start date</th>
								<th>Salary</th>
							</tr>
						</tfoot>
					</table>
				</div>
			


			</div>
		</div>
	</div>


</body>
</html>