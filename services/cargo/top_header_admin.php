<?php
include 'connect.php';
$username=$_SESSION['username'];

if(isset($username)){
	
}else{
	header("Location: log_out.php");
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
                            <li class="nav-item dropdown">
                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><span><i class="notika-icon notika-close"></i></span></a>
                                <div role="menu" class="dropdown-menu message-dd animated zoomIn">
                                    <div class="hd-mg-tt">
                                        <h2>Delete Notification</h2>
                                    </div>
                                    <div class="hd-message-info">
                                   
                                        
								
									<div id="load_area">
									
									</div>
                                 
                               
                                 
                                    </div>
                                    <div class="hd-mg-va">
                                        <a href="delete_list.php">View All</a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item nc-al"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><span><i class="notika-icon notika-refresh"></i></span><div class="spinner4 spinner-4"></div><div class="ntd-ctn"><span class="delete"></span></div></a>
                                <div role="menu" class="dropdown-menu message-dd notification-dd animated zoomIn">
                                    <div class="hd-mg-tt">
                                        <h2>UPDATE NOTIFCATION</h2>
                                    </div>
                                    <div class="hd-message-info">
                   <div id="update_area">
				   
				   </div>
                                    </div>
                                    <div class="hd-mg-va">
                                        <a href="update_list.php">View All</a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><span><i class="notika-icon notika-menus"></i></span><div class="spinner4 spinner-4"></div><div class="ntd-ctn"><span class="update"></span></div></a>
                                <div role="menu" class="dropdown-menu message-dd task-dd animated zoomIn">
                                    <div class="hd-mg-tt">
                                        <h2>Manifest to delete</h2>
                                    </div>
                                      <div class="hd-message-info">
                                       
                                            <div class="hd-message-sn">
                                            <div id="update_area_manifest">
				   
				   </div>
                                            </div>
                                        
                                
                                 
                                    </div>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><span><div class="spinner4 spinner-4"></div><div class="ntd-ctn"><span class="manifest"></span></div></span></a>
                                <div role="menu" class="dropdown-menu message-dd chat-dd animated zoomIn">
              
                                
                                    
                                </div>
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
                                <li><a data-toggle="collapse" data-target="#Charts" href="#">Home</a>
                                    <ul class="collapse dropdown-header-top">
                                    <li><a href="charts.php">Home</a>
                                </li>
                                        <li><a href="employee_update.php">Charge luggages</a>
                                </li>
                                       
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demoevent" href="#">Reports</a>
                                    <ul id="demoevent" class="collapse dropdown-header-top">
                                    	<li><a href="store_all_see.php">Store Inventory</a>
                                </li>
								
								<li><a href="store.php">all Reports</a>
                                </li><li><a href="store_charge.php">User charge report</a>
                                </li>
								
                             <li><a href="manifest_charges_list.php">Manifest Expenses</a>
                                </li> 
								<li><a href="print_store.php">Manifest By Selection</a>
                                </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#democrou" href="#">Manifest</a>
                                    <ul id="democrou" class="collapse dropdown-header-top">
                                           <li><a href="vehicle_view.php"> Manifest List</a>
                                </li>  
                                <li><a href="manifest_per_user.php"> Manifest Per User</a>
                                </li>     <li><a href="man_exp.php"> Manifest Expenses</a>
                                </li>  <li><a href="man_income.php"> Manifest Income</a>
                                </li> 
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demolibra" href="#">Delete & Edit</a>
                                    <ul id="demolibra" class="collapse dropdown-header-top">
                                   <li><a href="delete_history.php"> Delete & Edit history</a>
                                </li>  <li><a href="delete_list.php"> Delete List</a>
                                </li> <li><a href="update_list.php"> Update List</a>
                                </li> <li><a href="delete_manifest_list.php"> Manifest List</a>
                                </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demodepart" href="#">Office Expenses</a>
                                    <ul id="demodepart" class="collapse dropdown-header-top">
                                     <li><a href="cash_hand.php">Office Expenses List</a>
                                </li>
                                <li><a href="office_exp.php">Expenses </a>
                                </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demo" href="#">Settings</a>
                                    <ul id="demo" class="collapse dropdown-header-top">
                                             <li><a href="log_out.php">Logout</a>
                                </li>
                                <li><a href="register.php">Insert Users</a>
                                </li>  
						 <li><a href="employees.php">Users list</a>
                                </li>  
                                    </ul>
                                </li>  
								<li><a data-toggle="collapse" data-target="#demo" href="#">Luggage check out</a>
                                    <ul id="demo" class="collapse dropdown-header-top">
                                             <li><a href="log_out.php">Logout</a>
                                </li>
                                <li><a href="register.php">Insert Users</a>
                                </li>  
						 <li><a href="employees.php">Users list</a>
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
if(($this_page == "charts.php") OR ($this_page == "employee_update.php")){
	echo 'class="active"';
}else{
	echo '';
}
?>><a data-toggle="tab" href="#Home"><i class="notika-icon notika-house"></i> Home</a>
                        </li>
                        <li
						<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "store_all_see.php") OR ($this_page == "store.php")OR ($this_page == "store_charge.php")OR ($this_page == "manifest_charges_list.php")OR ($this_page == "print_store.php") ){
echo 'class="active"';
}else{
	echo '';
}
?>><a data-toggle="tab" href="#mailbox"><i class="notika-icon notika-menus"></i> Reports</a>
                        </li>
                        <li
						<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "vehicle_view.php") OR ($this_page == "manifest_per_user.php")OR ($this_page == "man_exp.php")OR ($this_page == "man_income.php") ){
	echo 'class="active"';
}else{
	echo '';
}
?>
						><a data-toggle="tab" href="#Interface"><i class="notika-icon notika-edit"></i> Manifest</a>
                        </li>
<li
<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "delete_history.php") OR ($this_page == "delete_list.php")OR ($this_page == "update_list.php")OR ($this_page == "delete_manifest_list.php") ){
echo 'class="active"';}else{
	echo '';
}
?>
><a data-toggle="tab" href="#edit"> <i class="notika-icon notika-close"></i> Delete & Edit</a>
                        </li>
                        <li
<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "cash_hand.php")  ){
echo 'class="active"';
}else{
	echo '';
}
?>
						><a data-toggle="tab" href="#Tables"><i class="notika-icon notika-windows"></i> Office Expenses</a>
                        </li>
                        <li
						<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "log_out.php") OR ($this_page == "register.php") OR ($this_page == "employees.php") ){
echo 'class="active"';
}else{
	echo '';
}
?>
><a data-toggle="tab" href="#Forms"><i class="notika-icon notika-form"></i> Settings</a>
                        </li> 
						
						<?php
$select222=mysqli_query($db,"SELECT *  FROM  users  where username='$username'");
$select_num222=mysqli_num_rows($select222);
if($select_num222 !=0){
   	WHILE($get122=mysqli_fetch_array($select222)){
    	    $location =$get122['location']; 

	
	}
	if($location == "Dar es salaam"){

	}else{
		?>
		<li
		
		><a href="clear_vehicle_view.php"><i class="notika-icon notika-checked"></i> Luggage check out</a>
                        </li>
		<?php
	}
}
?>
						
                       
                       
                    </ul>
                    <div class="tab-content custom-menu-content">
                        <div id="Home"<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "charts.php") OR ($this_page == "employee_update.php")){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>>
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="charts.php">Home</a>
                                </li> <li><a href="employee_update.php">Charge luggages</a>
                                </li>
                               
                              
                             
                                
                            </ul>
                        </div>
                        <div id="mailbox" <?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "store_all_see.php") OR ($this_page == "store.php")OR ($this_page == "store_charge.php")OR ($this_page == "manifest_charges_list.php")OR ($this_page == "print_store.php")OR ($this_page == "edit_luggage_all.php") ){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>>
                            <ul class="notika-main-menu-dropdown">
                               
                             
								<li><a href="store_all_see.php">Store Inventory</a>
                                </li>
								<li><a href="edit_luggage_all.php">Edit Luggage</a>
                                </li>
								
								<li><a href="store.php">all Reports</a>
                                </li><li><a href="store_charge.php">User charge report</a>
                                </li>
								
                             <li><a href="manifest_charges_list.php">Manifest Expenses</a>
                                </li> 
								<li><a href="print_store.php">Manifest By Selection</a>
                                </li>
							
                            </ul>
                        </div>
						
                        <div id="Interface" 
						<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "vehicle_view.php") OR ($this_page == "manifest_per_user.php")OR ($this_page == "man_exp.php")OR ($this_page == "man_income.php") ){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>>
                            <ul class="notika-main-menu-dropdown">
                         
                                <li><a href="vehicle_view.php"> Manifest List</a>
                                </li>  
                                <li><a href="manifest_per_user.php"> Manifest Per User</a>
                                </li>     <li><a href="man_exp.php"> Manifest Expenses</a>
                                </li>  <li><a href="man_income.php"> Manifest Income</a>
                                </li>  
                            </ul>
                        </div> 
						<div id="edit"<?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "delete_history.php") OR ($this_page == "delete_list.php")OR ($this_page == "update_list.php")OR ($this_page == "delete_manifest_list.php") ){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>>
                            <ul class="notika-main-menu-dropdown">
                         
                                <li><a href="delete_history.php"> Edit history</a>
                                </li>  <li><a href="delete_list.php"> Delete List</a>
                                </li> <li><a href="update_list.php"> Update List</a>
                                </li> <li><a href="delete_manifest_list.php"> Manifest List</a>
                                </li>  
                            </ul>
                        </div>
                        <div id="Charts" <?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "cash_hand.php")  ){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>>
                            <ul class="notika-main-menu-dropdown">
                                    <li><a href="cash_hand.php">Office Expenses List</a>
                                </li>
                                
							
							
                            </ul>
                        </div>
                        <div id="Tables" <?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "cash_hand.php") OR ($this_page == "office_exp.php") ){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>>
                            <ul class="notika-main-menu-dropdown">
                           
                                <li><a href="office_exp.php">Expenses </a>
                                </li>
						
							
                            </ul>
                        </div>
                        <div id="Forms" <?php
$this_page =  basename($_SERVER['PHP_SELF']);
if(($this_page == "log_out.php") OR ($this_page == "register.php") OR ($this_page == "employees.php") ){
	echo 'class="tab-pane in active notika-tab-menu-bg animated flipInX"';
}else{
	echo 'class="tab-pane notika-tab-menu-bg animated flipInX"';
}
?>>
                            <ul class="notika-main-menu-dropdown">
                                  <li><a href="log_out.php">Logout</a>
                                </li>
                                <li><a href="register.php">Insert Users</a>
                                </li>  
						 <li><a href="employees.php">Users list</a>
                                </li>  
								<li><a href="https://217.29.139.126:443/sr_tunduma/charts.php"><img src ="images/switch.png"/> Switch to Tunduma Server</a>
                                </li>  
                            </ul>
                        </div>
                   
                 
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div id="loader_parcel"></div>
	<div id="loader_manifest"></div>
	<div id="delete_manifest"></div>
	<div id="delete_maninfest_change"></div>
	<div id="vehicle_backup"></div>
	<script src="js/vendor/jquery-1.12.4.min.js"></script>
