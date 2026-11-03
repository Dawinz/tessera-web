<?php
error_reporting(E_ERROR | E_PARSE);

include 'connect.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
require_once __DIR__ . '/includes/tessera_tenant.php';
require_once __DIR__ . '/includes/tessera_rbac.php';
tessera_abort_unknown_tenant();
tessera_deny_if_tenant_suspended('redirect');
tessera_require_module('tickets');
$username = $_SESSION['username'] ?? null;
if (!isset($_SESSION['username'])) {
	tessera_redirect('index.php');
}
$select2=mysqli_query($db,"SELECT *  FROM  users  where username='".$_SESSION['username']."'");
$select_num2=mysqli_num_rows($select2);
if($select_num2 !=0){
   	WHILE($get12=mysqli_fetch_array($select2)){
    	    $firstname =$get12['firstname'];  
			$surname =$get12['surname'];
			$firstStringCharacter = substr($surname, 0, 1);
			$fullname= "$firstname.$firstStringCharacter.";
			$level =$get12['role']; 
$image = $get12['image']; 

		
	}
}else{
	 tessera_redirect('log_out.php');
}


?>
		
<?php include __DIR__ . '/includes/platform_chrome_head.php'; ?>
<?php
$tessera_tour_module = 'tickets';
$tessera_tour_role_label = $level ?? 'Staff';
include __DIR__ . '/includes/tessera_tour_boot.php';
$tessera_states_module = 'tickets';
include __DIR__ . '/includes/tessera_states_boot.php';
?>
<?php echo tessera_brand_css_vars(); ?>
<?php echo tessera_support_banner_html(); ?>
<script>
	document.body && document.body.classList.add('sr-platform-body');
	document.addEventListener('DOMContentLoaded', function () {
		document.body.classList.add('sr-platform-body');
	});
</script>

 <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <?php echo tessera_brand_markup('home.php'); ?>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item sr-topnav-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content" aria-label="Toggle navigation" aria-controls="topnav-menu-content" aria-expanded="false">
                            <i class="bx bx-menu"></i>
                        </button>

               
         
                    </div>

                    <?php
                    $sr_active_module = 'tickets';
                    include __DIR__ . '/includes/platform_modules.php';
                    ?>

                    <?php
                    $sr_profile_home = 'profile.php?u=' . urlencode($username);
                    $sr_logout_href = 'log_out.php';
                    include __DIR__ . '/includes/platform_header_actions.php';
                    ?>
				
                </div>
                <div class="topnav" data-tour="topnav">
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

			/* Operator asked to drop Reports from Tickets chrome. */
			if (strcasecmp(trim((string) $role_name), 'Reports') === 0) {
				continue;
			}

			/* Tickets | Cargo | Accounts pills already switch modules - skip duplicate cross-module links. */
			if (preg_match('#(?:^|/|\.\./)(?:cargo|tickets?|accounts?)/#i', (string) $link)) {
				continue;
			}

			$link_base = $link !== '' ? basename(parse_url($link, PHP_URL_PATH) ?: $link) : '';
			$nav_id = 'ops-' . preg_replace('/\W+/', '-', strtolower($role_name));
            			
				
						
					

	?>

                                 
									<?php 
									$select24=mysqli_query($db,"SELECT *  FROM  roles_assigned  where file='$this_page' and category='$role_name' ");
$select_num24=mysqli_num_rows($select24);
$nav_is_active = ($link_base !== '' && $link_base === $this_page) || ($select_num24 != 0);
if($select_num24 !=0){
?>

<?php
	$select254=mysqli_query($db,"SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name'");
$select_num254=mysqli_num_rows($select254);
if($select_num254 !=0){
	
	
	}	
	?>

   <li  class="nav-item<?php if($select_num254>1){ echo " dropdown";}?><?php if($nav_is_active){ echo " active sr-nav-current";}?>" style=""<?php echo function_exists('tessera_tour_nav_attr') ? tessera_tour_nav_attr((string) $link) : ''; ?>>
                                        <a class="nav-link<?php if($select_num254>1){ echo " dropdown-toggle";}?> arrow-none<?php if($nav_is_active){ echo " active";}?>" href="<?php if($select_num254>1){echo "#"; }else{ echo "$link";}?>" id="<?php echo htmlspecialchars($nav_id, ENT_QUOTES, 'UTF-8');?>"<?php if($select_num254>1){ ?> role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-haspopup="true" aria-expanded="false"<?php } ?><?php echo function_exists('tessera_tour_nav_attr') ? tessera_tour_nav_attr((string) $link) : ''; ?>>
                                            <i class="icon nav-icon" data-feather="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8');?>"></i>
                                            <span  data-key="t-horizontal"><?php echo $role_name?></span>
                                        </a>
										<?php if($select_num254>1){ ?>
										     <div class="dropdown-menu" aria-labelledby="<?php echo htmlspecialchars($nav_id, ENT_QUOTES, 'UTF-8');?>">
									 	<?php
	$select25=mysqli_query($db,"SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name'");
$select_num25=mysqli_num_rows($select25);
if($select_num25 !=0){
   	WHILE($get125=mysqli_fetch_array($select25)){
    	    $role_name_view =$get125['name']; 
		
			$file =$get125['file'];
			
			
	?>
			 
											 
                                            <a href="<?php echo "$file"?>" class="dropdown-item<?php echo ($file === $this_page) ? ' active' : '';?>" data-key="t-sales"<?php echo function_exists('tessera_tour_nav_attr') ? tessera_tour_nav_attr((string) $file) : ''; ?>><?php echo "$role_name_view";?></a>
                                          
											
											<?php
											}}

											?>
											
                                        </div>
										<?php } ?>
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
	  <li  class="nav-item<?php if($select_num254>1){ echo " dropdown";}?><?php if($nav_is_active){ echo " active sr-nav-current";}?>" style=""<?php echo function_exists('tessera_tour_nav_attr') ? tessera_tour_nav_attr((string) $link) : ''; ?>>
                                        <a class="nav-link<?php if($select_num254>1){ echo " dropdown-toggle";}?> arrow-none<?php if($nav_is_active){ echo " active";}?>" href="<?php if($select_num254>1){echo "#"; }else{ echo "$link";}?>" id="<?php echo htmlspecialchars($nav_id, ENT_QUOTES, 'UTF-8');?>"<?php if($select_num254>1){ ?> role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-haspopup="true" aria-expanded="false"<?php } ?><?php echo function_exists('tessera_tour_nav_attr') ? tessera_tour_nav_attr((string) $link) : ''; ?>>
                                            <i class="icon nav-icon" data-feather="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8');?>"></i>
                                            <span  data-key="t-horizontal"><?php echo $role_name?></span>
                                        </a>
										<?php if($select_num254>1){ ?>
										     <div class="dropdown-menu" aria-labelledby="<?php echo htmlspecialchars($nav_id, ENT_QUOTES, 'UTF-8');?>">
											 	<?php
	$select25=mysqli_query($db,"SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name'");
$select_num25=mysqli_num_rows($select25);
if($select_num25 !=0){
   	WHILE($get125=mysqli_fetch_array($select25)){
    	    $role_name_view =$get125['name']; 
		
			$file =$get125['file'];
			
			
	?>
											 
											 
                                            <a href="<?php echo "$file"?>" class="dropdown-item<?php echo ($file === $this_page) ? ' active' : '';?>" data-key="t-sales"><?php echo "$role_name_view ";?></a>
                                          
											
											<?php
											}}

											?>
											
                                        </div>
										<?php } ?>
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
