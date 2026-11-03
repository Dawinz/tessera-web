<?php
require_once __DIR__ . '/includes/sr_env.php';
require_once __DIR__ . '/includes/auth_bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php $__page_title = 'Azia Responsive Bootstrap 5 Dashboard Template'; ?>
    <?php include __DIR__ . '/includes/dashonic_head.php'; ?>
    <style>
ul { list-style: none outside none; margin:0; padding: 0; text-align: center }
li { display: inline; margin: 0 10px; }
.container.gallery-container {
    background-color: #fff;
    color: #35373a;
    min-height: 100vh;
    padding: 30px 50px;
}

.gallery-container h1 {
    text-align: center;
    margin-top: 50px;
    font-family: 'Droid Sans', sans-serif;
    font-weight: bold;
}

.gallery-container p.page-description {
    text-align: center;
    margin: 25px auto;
    font-size: 18px;
    color: #999;
}

.tz-gallery {
    padding: 40px;
}

/* Override bootstrap column paddings */
.tz-gallery .row > div {
    padding: 2px;
}

.tz-gallery .lightbox img {
    width: 100%;
    border-radius: 0;
    position: relative;
}

.tz-gallery .lightbox:before {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -13px;
    margin-left: -13px;
    opacity: 0;
    color: #fff;
    font-size: 26px;
    font-family: 'Glyphicons Halflings';
    content: '\e003';
    pointer-events: none;
    z-index: 9000;
    transition: 0.4s;
}


.tz-gallery .lightbox:after {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    background-color: rgba(46, 132, 206, 0.7);
    content: '';
    transition: 0.4s;
}

.tz-gallery .lightbox:hover:after,
.tz-gallery .lightbox:hover:before {
    opacity: 1;
}

.baguetteBox-button {
    background-color: transparent !important;
}

@media(max-width: 768px) {
    body {
        padding: 0;
    }
}
#ui-datepicker-div {
z-index: 10000 !important;
}
</style>
  </head>
  <body data-layout="horizontal" data-topbar="dark" class="sr-platform-body">

<!-- az-header -->
<?php
include 'includes/top_header.php';

$connect = sr_mysqli('account');
$route_full_name = '';
$query = "SELECT fullname FROM route";
$result = mysqli_query($connect, $query);
$select_route_num=mysqli_num_rows($result);
while($row = mysqli_fetch_array($result))
{
 $route_full_name .= '<option value="'.$row["fullname"].'">'.$row["fullname"].'</option>';
}

   $this_year = date('Y', time());
$connect_account = sr_mysqli('account');
$name_account = '';
$query_account = "SELECT first_date,month FROM job_card_detail where year='$this_year'";
$result_account = mysqli_query($connect_account, $query_account);
while($row_account = mysqli_fetch_array($result_account))
{
 $name_account .= '<option value="'.$row_account["first_date"].'">'.$row_account["month"].'</option>';
}
include 'modal/jobcard.php';

?>
      <div class="d-flex flex-column flex-md-row align-items-center"style="padding-left:30px;padding-right:30px;padding-top:15px;">
              <h4 class="mb-md-0 mb-4">Job Cards</h4>
              <div class="wrapper d-flex align-items-center">
               
                <button type="button" class="btn btn-primary ms-4" data-toggle="modal" data-target="#exampleModal"><i class="bx bx-plus" data-toggle="modal" data-target="#exampleModal"></i> Add Job Card</button>
       
			 </div>
              <div class="wrapper ms-md-auto d-flex flex-column flex-md-row kanban-toolbar ms-n2 ms-md-0 mt-4 mt-md-0">
                <div class="d-flex">
				 <select id="month"class="form-select select2-no-search"style="margin-right:3px;">
               <?php echo $name_account;?>
              </select>
              <input type="text" id ="start_date"class="form-control fc-datepicker start_date datepicker" placeholder="MM/DD/YYYY" style="margin-right:3px;">
              <input type="text" id ="end_date"class="form-control fc-datepicker end_date datepicker" placeholder="MM/DD/YYYY" style="margin-right:3px;">


                               <input style="margin-right:3px;"class="sr-table-search form-control" placeholder="Search" id="search_job" type="text" style="margin-right:3px;">

                  
                </div>
             
              </div>
            </div>

<div id ="response"></div>






<!-- az-content-body -->
      </div><!-- container -->
    </div><!-- az-content -->
<script src="/accounts/js/template/insert/job_card.js"></script>
    <script src="/accounts/js/template/insert/route_currency.js"></script>
    <script src="/accounts/js/template/dependence/route_dependence.js"></script>
	    <script src="/accounts/js/template/fetch/fetch_route.js"></script>

<script src="/accounts/js/template/sweatalert2.js"></script>
<script src="/accounts/js/template/currency.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script>
        $(document).ready(function(){
			var query = "";
			var start_date = "";
			var end_date = "";
            var first_date = $("#month").val();
			
			function myFunction(query,start_date,end_date,first_date) {
				
                        $.ajax(
                            {
                                url: 'search/job_card.php',
                                method: 'POST',
                                data: {
                                    search: 1,
                                    q: query,
                                    start_date: start_date,
                                    end_date: end_date,
                                    first_date: first_date
                                },
                                success: function (data) {
					$("#response").html(data);

                                },
                                dataType: 'text'
                            }
                        );
                    
			}


    $("#search_job").keyup(function () {
					var start_date = $("#start_date").val();
		var end_date = $("#end_date").val();
                    var query = $("#search_job").val();
                    var first_date = "";
				myFunction(query,start_date,end_date,first_date);

               
                });
				
$("#start_date").change(function(){
	var start_date = $("#start_date").val();
		var end_date = $("#end_date").val();
    var query = $("#search_job").val();
	                    var first_date = "";

				myFunction(query,start_date,end_date,first_date);
  });
  
  $("#end_date").change(function(){
	var end_date = $("#end_date").val();
	var start_date = $("#start_date").val();
    var query = $("#search_job").val();
		                    var first_date = "";

				myFunction(query,start_date,end_date,first_date);
  }); 

  $("#month").change(function(){
	 var first_date = $("#month").val();
	var end_date = "";
	var start_date = "";
    var query = "";
				myFunction(query,start_date,end_date,first_date);
  });
			
				myFunction(query,start_date,first_date,first_date);
          });
		  
	
		  
	
    </script>
	 <script>
  $( function() {
    $( ".datepicker" ).datepicker();
  } );
  </script>
  
<?php include __DIR__ . '/includes/dashonic_foot.php'; ?>
</body>
</html>
