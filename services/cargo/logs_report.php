   <meta charset="utf-8" />
        <title>Logs</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Tessera" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
        <!-- fullcalendar css -->
        <link href="assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">

        <!-- One of the following themes -->
        <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/classic.min.css"/> <!-- 'classic' theme -->
        <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/monolith.min.css"/> <!-- 'monolith' theme -->
        <link rel="stylesheet" href="assets/libs/@simonwep/pickr/themes/nano.min.css"/> <!-- 'nano' theme -->

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="assets/libs/gridjs/theme/mermaid.min.css">
   <link rel="stylesheet" href="css/datapicker/datepicker3.css">
      <link rel="stylesheet" type="text/css" href="css/buttons.css">
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

<link rel="stylesheet" type="text/css" href="datatable/datatable.css">
   <script src="js/datapicker/bootstrap-datepicker.js"></script>
    <script src="js/datapicker/datepicker-active.js"></script>

	<script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
<table class="table mb-0" id="empTable">
           <thead class="table-light">
                <tr>
                   
					<th>Username</th>               
                    <th>Receiver's name</th>
                   <th>Code</th>  
	
                </tr>
                </thead>
	
                                          <!-- end tbody --> 
                                            </table>
											
											
											 <script>
        $(document).ready(function(){
			


		
			
		
	
			 $('#empTable').DataTable({
				
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
				
                'ajax': {
                'url':'fetch_logs.php'

                },'columns': [
         
				
					{ data: 'user' },
                    { data: 'page' },
                    { data: 'date' },
				
                ],
				"lengthMenu": [[50]],
	
				dom: 'Bfrtip',


            });

});  

       
        </script>