

<!doctype html>
<style>
.table{
	
	border:none;
}
#seat_no{
	font-size:13px;
	
	color:white;
	border-radius:11px;
	padding:2px;
	font-weight:bold;
}
#td{
	width: 18%;
}
.backseats{
	background:green;
}
</style>
<html lang="en">

     <head>
        
        <meta charset="utf-8" />
        <title>User Charges</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Pichforest" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">
 
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.css'><link rel="stylesheet" href="style.css">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	     <link href="css/alertify.min.css" rel="stylesheet" type="text/css" />

	  
        <!-- One of the following themes -->
   <!-- 'nano' theme -->

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

      <link rel="stylesheet" type="text/css" href="css/buttons.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />   
   </head>

    
    <body data-layout="horizontal"data-layout-scrollable="true"data-topbar="dark"data-layout-mode="dark">

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
     <?php 
	 session_start();
$username=$_SESSION['username'];
	 ?>
	         <?php
//index.php
$connect = mysqli_connect("localhost", "root", "Teccross@360", "ticket");
$start_route2 = '';
$query = "SELECT start_route FROM assigned_routes WHERE username='$username' GROUP BY start_route ORDER BY start_route ASC";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result))
{
 $start_route2 .= '<option value="'.$row["start_route"].'">'.$row["start_route"].'</option>';
}
 $seats=0;
?> 

            <div class="main-content">

                <div class="card">
                    <div class="container-fluid">

                        <!-- start page title -->
            
                  
                    </div> 
					                   <div class="row">
									        <div class="card">
										                               <div class="col-xl-3 col-sm-6"><br>
						  <a href="manifest.php"  class=" btn btn-soft-warning">Back to Manifest <i class="mdi mdi-arrow-left align-middle"></i></a>

                                                    </div>
														 <table>
		 <tr >
		 <td style="padding:10px;">  
 <label class="form-label" for="validationCustom02">Select Starting Point</label>
		  <select class="form-select shadow-none action" name="category"
                                                                    id="event-category country" required>
																	<option>Choose starting Point</option>
                                                                     <?php echo $start_route2; ?>
                                                                </select></td>
		 <td style="padding:10px;">				 <label class="form-label" for="validationCustom02">Select Ending Point</label>

                                            <select class="form-select shadow-none" name="category"
                                                                    id="state" required>
                                                                  
                                                                </select></td>
										<td style="padding:10px;">				 <label class="form-label" for="validationCustom02">Select Ending Point</label>

                                                                                                            <input class="form-control" type="text"  class="form-control dateselect" placeholder="date">
</td>
	
	
	<td>
	
	                                            <button style="margin-top:30px;" type="button" id="insert"class="btn btn-primary"><span class="search_text">Search</span></button>

	</td>
		 </tr>
		 
		 
		 </table>
                       <!-- end col --> 
                        </div>
                         <!-- end col --> 
                        </div>
			  <!-- end page title -->

        
                                   
				
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Dashonic.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://Pichforest.com/" target="_blank" class="text-reset">Pichforest</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
    
		                   
        <!-- /Right-bar -->

   <!-- end row -->

     <!-- end row -->

              <!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->


             
            </div>

        <!-- JAVASCRIPT -->
	
 	<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js'></script>
  
        <!-- apexcharts init -->

    </body>
</html>
  <script type="text/javascript" language="javascript" src="datatable/datatable.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/button.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/jsq.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker2.js"></script>
	<script type="text/javascript" language="javascript" src="datatable/pdf_maker.js"></script>
	<script type="text/javascript"  src="datatable/html.js"></script>
	<script type="text/javascript" src="datatable/print.js"></script>
	<script src="assets/js/alertify.min.js"></script>


        <!-- apexcharts init -->

    </body>
</html>
<script>
$(document).ready(function(){
 $('.action').change(function(){
  if($(this).val() != '')
  {
   var action = $(this).attr("id");
   var query = $(this).val();
   var result = '';
   if(action == "event-category country")
   {
    result = 'state';
   }
  
   $.ajax({
    url:"fetch_dependence.php",
    method:"POST",
    data:{action:action, query:query},
    success:function(data){
     $('#'+result).html(data);
    }
   })
  }
 });
  document.getElementById("example-date-input").min = new Date().getFullYear() + "-" +  parseInt(new Date().getMonth() + 1 ) + "-" + new Date().getDate()


	 $('.dateselect').datepicker({
		todayHighlight: true,
		 orientation: "bottom left",
		 format: "yyyy-mm-dd",
	
		//container: console.log($(this).datepicker())
}); 

});
</script>