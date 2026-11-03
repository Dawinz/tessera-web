<?php
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
session_start();
$username=$_SESSION['username'];
 if(isset($username)){
	 
 }else{
 header("Location: log_out.php");
 
 }
$select2=mysqli_query($db,"SELECT *  FROM  users  where username='$username' and status='active'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			$system_user =$get12['username'];
			$firstStringCharacter = substr($surname, 0, 1);
			$fullname= "$firstname $firstStringCharacter ";
			$level =$get12['role']; 
$image = $get12['image']; 

		
	}
}else{
		 header("Location: log_out.php");
}

include 'logs.php';
?>

	
		<script src="js/vendor/jquery-1.12.4.min.js"></script>

 <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="home.php" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="assets/images/logo-sm.png" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="assets/images/logo-dark.png" alt="" height="22">
                                </span>
                            </a>

                            <a href="home.php" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="images/sr3.png" alt="" height="40">
                                </span>
                                <span class="logo-lg">
                                    <img src="images/sr3.png" alt="" height="40">
                                </span>
                            </a>
                        </div>
						
<span id="demo"style="display:none;"></span>
                        <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

               

         
                    </div>
					<h5 style="color:white;"><div class="clock" id="clock"> </div></h5>

                    <div class="d-flex">
                        <div class="dropdown d-inline-block">

                            <button type="button" class="btn header-item"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="uil uil-search" style="font-size:20px;" ></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"style="width:850px;">
                                <form class="p-2">
                                    <div class="search-box">
                                        <div class="position-relative">
                                            <div class="display:inline-block;"><input type="text" id="searchBox22"class="sr-table-search form-control rounded bg-light border-0" placeholder="Search..."><span><img class="search_images_gif"style="display:none;"src="images/search.gif"/></span></div>
                                            <i class="mdi mdi-magnify search-icon"></i>
											<div id="response"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
       
  
         <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="
							<?php 
											if($image==""){
										echo "assets/images/users/user.png";

												
											}else{
												echo "uploads/$image";
											}
											?>"
                            alt="Header Avatar">
                            <span class="ms-2 d-none d-sm-block user-item-desc">
                                <span class="user-name"><?php echo $fullname;?></span>
                                <span class="user-sub-title"><?php echo $level;?></span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                          <div class="p-3 bg-primary border-bottom">
                                <h6 class="mb-0 text-white"><?php echo ''.$firstname.' '.$surname.'';?></h6>
                                <p class="mb-0 font-size-11 text-white-50 fw-semibold"><?php echo $level;?></p>
                            </div>
                            <a class="dropdown-item" href="log_out.php"><i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> <span class="align-middle">Logout</span></a>

                        </div>
                    </div>

                    </div>
				
                </div>
                <div class="topnav">
                    <div class="container-fluid">
                        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
    	<?php $this_page =  basename($_SERVER['PHP_SELF']); ?>

                            <div class="collapse navbar-collapse" id="topnav-menu-content">
                                <ul class="navbar-nav">
                      	<?php
	$select2=mysqli_query($db,"SELECT *  FROM  category  where category='$level' ORDER BY arrangement ASC ");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $role_name =$get12['name']; 
		
			$image =$get12['image'];
			$link =$get12['link'];
            			

	?>

                                 
									<?php 
									$select24=mysqli_query($db,"SELECT *  FROM  roles_assigned  where file='$this_page' and category='$role_name' ");
$select_num24=mysqli_num_rows($select24);
if($select_num24 !=0){
?>

<?php
	$select254=mysqli_query($db,"SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name'");
$select_num254=mysqli_num_rows($select254);
if($select_num254 !=0){
	
	
	}	
	?>

   <li  class="nav-item <?php if($select_num254>1){ echo "dropdown";}else{}?>" style="">
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?php if($select_num254>1){echo "#"; }else{ echo "$link";}?>" id="<?php echo $image;?>" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon nav-icon" data-feather="<?php echo $image;?>"></i>
                                            <span  data-key="t-horizontal"><?php echo $role_name?></span>
                                        </a>
											     <div class="dropdown-menu" aria-labelledby="<?php echo $image;?>">
											 	<?php
	$select25=mysqli_query($db,"SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name' ORDER BY arrangement ASC ");
$select_num25=mysqli_num_rows($select25);
if($select_num25 !=0){
   	WHILE($get125=mysqli_fetch_array($select25)){
    	    $role_name =$get125['name']; 
			$file =$get125['file'];
			$sub_url =$get125['sub_url'];
			
			
			if($sub_url =="yes"){
				?>
				      <div class="dropdown">
				        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-pricing"
                                                    role="button">
                                                    <span data-key="t-pricing"><?php echo $role_name;?></span> <div class="arrow-down"></div>
                                                </a>
												<div class="dropdown-menu" aria-labelledby="topnav-pricing">
				<?php
				
					$select253=mysqli_query($db,"SELECT *  FROM  sub_category  where category='$role_name' ");
$select_num253=mysqli_num_rows($select253);
if($select_num253 !=0){
   	WHILE($get1253=mysqli_fetch_array($select253)){
$role_name_assigned =$get1253['name']; 
$url =$get1253['url']; 


				
				
				?>
				
                                <a href="<?php echo $url;?>" class="dropdown-item" data-key="t-basic"><?php echo "$role_name_assigned ";?></a>

				<?php
				}}
				
				?>
				</div>	</div>
				<?php
				
			}else{
				?>
								                                            <a href="<?php echo "$file"?>" class="dropdown-item" data-key="t-sales"><?php echo "$role_name ";?></a>

				<?php
			}
			
			
	?>
											 
											 
                                          
											
											<?php
											}}

											?>
											
                                        </div>
                                    </li>
<?php
}else{
	?>
	<?php
	$select254=mysqli_query($db,"SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name'");
$select_num254=mysqli_num_rows($select254);
if($select_num254 !=0){

			
	}	
	?>
	  <li  class="nav-item <?php if($select_num254>1){ echo "dropdown";}else{}?>" style="">
                                        <a class="nav-link dropdown-toggle arrow-none" href="<?php if($select_num254>1){echo "#"; }else{ echo "$link";}?>" id="<?php echo $image;?>" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon nav-icon" data-feather="<?php echo $image;?>"></i>
                                            <span  data-key="t-horizontal"><?php echo $role_name?></span>
                                        </a>
										     <div class="dropdown-menu" aria-labelledby="<?php echo $image;?>">
											 	<?php
	$select25=mysqli_query($db,"SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name' ORDER BY arrangement ASC ");
$select_num25=mysqli_num_rows($select25);
if($select_num25 !=0){
   	WHILE($get125=mysqli_fetch_array($select25)){
    	    $role_name =$get125['name']; 
			$file =$get125['file'];
			$sub_url =$get125['sub_url'];
			
			
			if($sub_url =="yes"){
				?>
				      <div class="dropdown">
				        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-pricing"
                                                    role="button">
                                                    <span data-key="t-pricing"><?php echo $role_name;?></span> <div class="arrow-down"></div>
                                                </a>
												<div class="dropdown-menu" aria-labelledby="topnav-pricing">
				<?php
				
					$select253=mysqli_query($db,"SELECT *  FROM  sub_category  where category='$role_name' ");
$select_num253=mysqli_num_rows($select253);
if($select_num253 !=0){
   	WHILE($get1253=mysqli_fetch_array($select253)){
$role_name_assigned =$get1253['name']; 
$url =$get1253['url']; 


				
				
				?>
				
                                <a href="<?php echo $url;?>" class="dropdown-item" data-key="t-basic"><?php echo "$role_name_assigned ";?></a>

				<?php
				}}
				
				?>
				</div>	</div>
				<?php
				
			}else{
				?>
								                                            <a href="<?php echo "$file"?>" class="dropdown-item" data-key="t-sales"><?php echo "$role_name ";?></a>

				<?php
			}
			
			
	?>
											 
											 
                                          
											
											<?php
											}}

											?>
											
                                        </div>
                                    </li>
									
										
	<?php
}
?>
									
    <?php
	}}
	?>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </header>
			
				  <script src="searching.js"></script>
				  <script src="clock.js"></script>
				  <script src="log_out_user.js"></script>
		
	
