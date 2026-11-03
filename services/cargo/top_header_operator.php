<?php
include 'connect.php';
$username=$_SESSION['username'];

if(isset($username)){
	
}else{
	header("Location: login.php");
}

$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $fullname =$get12['fullname']; 

		
	}
}
?>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,700;1,400;1,700&display=swap" rel="stylesheet"> 
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,700&display=swap" rel="stylesheet">
	<div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="logo-area">
                        <a href="#"style="font-family: 'Montserrat', sans-serif;color:white;">SR CLASSIC COACH</a> <br> <span style="color:white; font-weight:bold;font-size:16px;font-family: 'Crimson Text', serif;" > ~ <?php echo $fullname; ?> ~</span>
                    </div>
                </div>
				<!------ 
				notifcation
				/////

				-->
                          <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="header-top-menu">
                        <ul class="nav navbar-nav notika-top-nav">
                            <li class="nav-item dropdown">
                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><span><i class="notika-icon notika-search"></i></span></a>
                                <div role="menu" class="dropdown-menu search-dd animated flipInX">
                                    <div class="search-input">
                                        <i class="notika-icon notika-left-arrow"></i>
                                        <input type="text" id="searchBox22" />
                                    </div>
			 <div id="response"></div>

								</div>
                            </li>
                         
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	
	
	    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul class="mobile-menu-nav">
                                <li><a data-toggle="collapse" data-target="#Charts" href="#">Check out</a>
                                    <ul class="collapse dropdown-header-top">
                                        <li><a href="index.html">Charge Luggage</a></li>
                                        <li><a href="index-2.html">Dashboard Two</a></li>
                                        <li><a href="index-3.html">Dashboard Three</a></li>
                                        <li><a href="index-4.html">Dashboard Four</a></li>
                                        <li><a href="analytics.html">Analytics</a></li>
                                        <li><a href="widgets.html">Widgets</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demoevent" href="#">Email</a>
                                    <ul id="demoevent" class="collapse dropdown-header-top">
                                        <li><a href="inbox.html">Inbox</a></li>
                                        <li><a href="view-email.html">View Email</a></li>
                                        <li><a href="compose-email.html">Compose Email</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#democrou" href="#">Interface</a>
                                    <ul id="democrou" class="collapse dropdown-header-top">
                                        <li><a href="animations.html">Animations</a></li>
                                        <li><a href="google-map.html">Google Map</a></li>
                                        <li><a href="data-map.html">Data Maps</a></li>
                                        <li><a href="code-editor.html">Code Editor</a></li>
                                        <li><a href="image-cropper.html">Images Cropper</a></li>
                                        <li><a href="wizard.html">Wizard</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demolibra" href="#">Charts</a>
                                    <ul id="demolibra" class="collapse dropdown-header-top">
                                        <li><a href="flot-charts.html">Flot Charts</a></li>
                                        <li><a href="bar-charts.html">Bar Charts</a></li>
                                        <li><a href="line-charts.html">Line Charts</a></li>
                                        <li><a href="area-charts.html">Area Charts</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demodepart" href="#">Tables</a>
                                    <ul id="demodepart" class="collapse dropdown-header-top">
                                        <li><a href="normal-table.html">Normal Table</a></li>
                                        <li><a href="data-table.html">Data Table</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demo" href="#">Forms</a>
                                    <ul id="demo" class="collapse dropdown-header-top">
                                        <li><a href="form-elements.html">Form Elements</a></li>
                                        <li><a href="form-components.html">Form Components</a></li>
                                        <li><a href="form-examples.html">Form Examples</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Miscellaneousmob" href="#">App views</a>
                                    <ul id="Miscellaneousmob" class="collapse dropdown-header-top">
                                        <li><a href="notification.html">Notifications</a>
                                        </li>
                                        <li><a href="alert.html">Alerts</a>
                                        </li>
                                        <li><a href="modals.html">Modals</a>
                                        </li>
                                        <li><a href="buttons.html">Buttons</a>
                                        </li>
                                        <li><a href="tabs.html">Tabs</a>
                                        </li>
                                        <li><a href="accordion.html">Accordion</a>
                                        </li>
                                        <li><a href="dialog.html">Dialogs</a>
                                        </li>
                                        <li><a href="popovers.html">Popovers</a>
                                        </li>
                                        <li><a href="tooltips.html">Tooltips</a>
                                        </li>
                                        <li><a href="dropdown.html">Dropdowns</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pages</a>
                                    <ul id="Pagemob" class="collapse dropdown-header-top">
                                        <li><a href="contact.html">Contact</a>
                                        </li>
                                        <li><a href="invoice.html">Invoice</a>
                                        </li>
                                        <li><a href="typography.html">Typography</a>
                                        </li>
                                        <li><a href="color.html">Color</a>
                                        </li>
                                        <li><a href="login-register.html">Login Register</a>
                                        </li>
                                        <li><a href="404.html">404 Page</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<!---------------------------------------
	
	---------------------------------------------
	------------------------------------------->
    <div class="main-menu-area mg-tb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
                        <li 
						
						<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "store.php") OR ($this_page == "employee_update.php")OR ($this_page == "edit_luggage_all.php")OR ($this_page == "store_all_see_operator.php")OR ($this_page == "print_store.php")OR ($this_page == "my_charge.php")OR ($this_page == "user_store.php") ){
	echo 'class="active"';
}else{
	echo '';
}
?>><a data-toggle="tab" href="#Home"><i class="notika-icon notika-house"></i> Home</a>
                        </li>
                       
                
                        </li>
                
                                   <li
								   
								   <?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "vehicle_view_operators.php") OR ($this_page == "man_income.php")OR ($this_page == "man_exp.php") ){
	echo 'class="active"';
}else{
	echo '';
}
?>><a data-toggle="tab" href="#Interface"><i class="notika-icon notika-edit"></i> Manifest</a>
                        </li>
                         <li><a data-toggle="tab" href="#Forms"><i class="notika-icon notika-form"></i> Settings</a>
                        </li>
						 <li><a data-toggle="tab" href="#Tables"><i class="notika-icon notika-windows"></i> Office Expenses</a>
                        </li>
                    </ul>
                    <div class="tab-content custom-menu-content">
                        <div id="Home" 
						<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "store.php") OR ($this_page == "employee_update.php")OR ($this_page == "edit_luggage_all.php")OR ($this_page == "store_all_see_operator.php")OR ($this_page == "print_store.php")OR ($this_page == "my_charge.php")OR ($this_page == "user_store.php") ){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>
						>
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="store.php">All Charges</a>
                                </li><li><a href="employee_update.php">Charge luggages</a>
                                </li>
                                 <li><a href="edit_luggage_all.php">Edit Luggages</a>
                                </li>
                              	<li><a href="store_all_see_operator.php">Store Inventory</a>
                                </li> 
								<li><a href="print_store.php">Store Inventory Selecting</a>
                                </li> 	<li><a href="my_charge.php">My Charges</a>
                                </li><li><a href="user_store.php">Store Ammount</a>
                                </li>
                  
                                
                            </ul>
                        </div>
                 
			
                        <div id="Forms" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                  <li><a href="log_out.php">Logout</a>
                                </li>
                           
						 
                            </ul>
                        </div>
                   
                 	
                        <div id="Interface" 
							<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "vehicle_view_operators.php") OR ($this_page == "man_income.php")OR ($this_page == "man_exp.php") ){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>
						>
                            <ul class="notika-main-menu-dropdown">
                           
                                <li><a href="vehicle_view_operators.php"> Manifest List</a>
                                </li>    <li><a href="man_exp.php"> Insert Manifest Expenses</a>
                                </li>  <li><a href="man_income.php"> Insert Manifest Income</a>
                                </li>  
                            </ul>
                        </div>
						     <div id="Tables" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                               <li><a href="http://localhost/account/Cash_hand.php">Office Expenses </a>
                                </li>
                              
						
							
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
		<div id="loader_parcel"></div>
	<div id="loader_parcel"></div>
	<div id="loader_manifest"></div>
	<div id="delete_manifest"></div>
	<div id="delete_maninfest_change"></div>

	<script src="js/vendor/jquery-1.12.4.min.js"></script>

<script>

$(document).ready(function() {
  $(window).load(function(){
	  
	  
	    $("#loader_manifest").load('update_backup.php');
    $("#loader_parcel").load('parcel_info_backup.php');
	 $("#delete_manifest").load('delete_backup_manifest.php');
	   $("#delete_maninfest_change").load('update_backupd_parcel.php');

setInterval(
function()
{
    $("#loader_parcel").load('parcel_info_backup.php');
	 $("#loader_manifest").load('update_backup.php');
	   $("#delete_manifest").load('delete_backup_manifest.php');
	     $("#delete_maninfest_change").load('update_backupd_parcel.php');

}, 3000);




  
});

</script>