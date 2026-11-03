<?php
error_reporting(E_ERROR | E_PARSE);
include 'connect.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
require_once __DIR__ . '/includes/tessera_tenant.php';
tessera_abort_unknown_tenant();
tessera_deny_if_tenant_suspended('redirect');
tessera_require_module('cargo');
$username = $_SESSION['username'] ?? null;
if (!isset($username) || $username === '') {
	tessera_redirect('index.php');
}
$select2 = mysqli_query($db, "SELECT *  FROM  users  where username='$username' and status='active'");
$select_num2 = mysqli_num_rows($select2);
if ($select_num2 != 0) {
	while ($get12 = mysqli_fetch_array($select2)) {
		$firstname = $get12['firstname'];
		$surname = $get12['surname'];
		$system_user = $get12['username'];
		$firstStringCharacter = substr($surname, 0, 1);
		$fullname = "$firstname $firstStringCharacter ";
		$level = $get12['role'];
		$image = $get12['image'];
		$fullname_user = "$firstname $surname";


	}
} else {
	header("Location: log_out.php");
}

include 'logs.php';
$this_page = "" . basename($_SERVER['PHP_SELF'], ".php") . ".php";
$modification_time = filemtime("$this_page");
$url_time = "v=$modification_time";


if (!function_exists('modified_time')) {
	function modified_time($this_page)
	{
		$modification_time = filemtime("$this_page");
		$url_time = "v=$modification_time";
		return $url_time;
	}
}

?>

<style>
 :root {
      --navy: #053a53;
      --navy-dark: #033249;
      --page: #474747;
      --muted: #7d8390;
      --line: #e7e9ee;
      --panel: #ffffff;
      --soft: #f6f7f9;
      --blue: #1698d8;
      --red: #ff4e4f;
      --green-row: #e7f5ea;
      --pink-row: #fcebf1;
      --shadow: 0 24px 50px rgba(0, 0, 0, 0.24);
    }

    .overlay {
      position: fixed;
      inset: 0;
      z-index: 1100;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 24px;
      background: rgba(14, 20, 27, 0.55);
    }

    .overlay.active {
      display: flex;
    }

    .calculator {
      width: min(360px, 100%);
      border-radius: 8px;
      background: #ffffff;
      box-shadow: 0 28px 80px rgba(0, 0, 0, 0.38);
      overflow: hidden;
    }

    .calculator-head {
      height: 58px;
      padding: 0 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--navy);
      color: #fff;
    }

    .calculator-head h2 {
      margin: 0;
      font-size: 17px;
    }

    .calculator-close {
      width: 34px;
      height: 34px;
      border: 0;
      border-radius: 50%;
      color: #fff;
      background: rgba(255, 255, 255, 0.12);
      font-size: 24px;
      line-height: 1;
      cursor: pointer;
    }

    .calculator-screen {
      min-height: 96px;
      padding: 18px;
      display: flex;
      align-items: flex-end;
      justify-content: flex-end;
      background: #f5f7fa;
      color: #111827;
      font-size: 36px;
      font-weight: 900;
      text-align: right;
      overflow-wrap: anywhere;
    }

    .calculator-keys {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1px;
      background: #d9dde5;
    }

    .calculator-keys button {
      min-height: 66px;
      border: 0;
      background: #fff;
      color: #1e2630;
      font-size: 20px;
      font-weight: 800;
      cursor: pointer;
    }

    .calculator-keys button:hover {
      background: #f1f5f9;
    }

    .calculator-keys .operator {
      color: var(--navy);
      background: #eef7fb;
    }

    .calculator-keys .danger {
      color: #fff;
      background: var(--red);
    }

    .calculator-keys .equals {
      color: #fff;
      background: var(--blue);
    }
  .idle-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(11,46,82,.55);
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }
    .idle-overlay.show { display: flex; }

    .idle-dialog {
        background: #fff;
        width: 340px;
        border-radius: 12px;
        padding: 28px 26px;
        text-align: center;
        box-shadow: 0 16px 40px rgba(0,0,0,.3);
    }
    .idle-dialog .icon {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #fff4e0;
        color: #c98a00;
        font-size: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-weight: 700;
    }
    .idle-dialog h3 { margin: 0 0 10px; color:#0b2e52; font-size:18px; }
    .idle-dialog p { margin:0 0 4px; color:#555; font-size:14px; line-height:1.5; }
    .idle-dialog .countdown { font-size:30px; font-weight:700; color:#c0392b; margin:14px 0 18px; }
    #stayBtn {
        width:100%;
        padding:11px 0;
        background:#2f86eb;
        color:#fff;
        border:none;
        border-radius:7px;
        font-size:15px;
        font-weight:600;
        cursor:pointer;
    }
    #stayBtn:hover { background:#2570cc; }
</style>
<?php include __DIR__ . '/includes/platform_chrome_head.php'; ?>
<?php
$tessera_tour_module = 'cargo';
$tessera_tour_role_label = $level ?? 'Staff';
include __DIR__ . '/includes/tessera_tour_boot.php';
$tessera_states_module = 'cargo';
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
<script src="js/vendor/jquery-1.12.4.min.js"></script>

<header id="page-topbar">
	<div class="navbar-header">
		<div class="d-flex">
			<!-- LOGO / brand -->
			<div class="navbar-brand-box">
				<?php echo tessera_brand_markup('home.php'); ?>
			</div>

			<span id="demo" style="display:none;"></span>
			<button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item sr-topnav-toggle" data-bs-toggle="collapse"
				data-bs-target="#topnav-menu-content" aria-label="Toggle navigation" aria-controls="topnav-menu-content" aria-expanded="false">
				<i class="bx bx-menu"></i>
			</button>

		</div>
		<?php
		$sr_active_module = 'cargo';
		include __DIR__ . '/includes/platform_modules.php';
		?>
		<h5 style="color:white; display:none;">
			<div class="clock" id="clock"> </div>
		</h5>

		<?php
		$sr_search_wide = true;
		$sr_logout_href = 'log_out.php?' . $url_time;
		$sr_profile_home = 'profile.php?u=' . urlencode((string) $username);
		unset($sr_avatar_src);
		include __DIR__ . '/includes/platform_header_actions.php';
		?>

	</div>
	<div class="topnav" data-tour="topnav">
		<div class="container-fluid">
			<nav class="navbar navbar-light navbar-expand-lg topnav-menu">

				<?php $this_page = basename($_SERVER['PHP_SELF']); ?>

				<div class="collapse navbar-collapse" id="topnav-menu-content">
					<ul class="navbar-nav">
						<?php
						$select2 = mysqli_query($db, "SELECT *  FROM  category  where category='$level' ORDER BY arrangement ASC ");
						$select_num2 = mysqli_num_rows($select2);
						if ($select_num2 != 0) {
							while ($get12 = mysqli_fetch_array($select2)) {
								$role_name = $get12['name'];

								$image = $get12['image'];
								$link = $get12['link'];

								/* Operator asked to drop Accounts Report from Cargo chrome (Accounts module covers money). */
								if (strcasecmp(trim((string) $role_name), 'Accounts Report') === 0) {
									continue;
								}

								/* Tickets | Cargo | Accounts pills already switch modules - skip duplicate cross-module links. */
								if (preg_match('#(?:^|/|\.\./)(?:cargo|tickets?|accounts?)/#i', (string) $link)) {
									continue;
								}


								?>


								<?php
								$select24 = mysqli_query($db, "SELECT *  FROM  roles_assigned  where file='$this_page' and category='$role_name' ");
								$select_num24 = mysqli_num_rows($select24);
								if ($select_num24 != 0) {
									?>

									<?php
									$select254 = mysqli_query($db, "SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name'");
									$select_num254 = mysqli_num_rows($select254);
									/* Home is a single destination - never a dropdown (Register Luggages lives under its own flows). */
									if (strcasecmp(trim((string) $role_name), 'Home') === 0 && $select_num254 > 1) {
										$select_num254 = 1;
									}
									if ($select_num254 != 0) {


									}
									?>

									<li class="nav-item<?php if ($select_num254 > 1) {
										echo " dropdown";
									} ?>" style="">
										<a class="nav-link<?php if ($select_num254 > 1) {
											echo " dropdown-toggle";
										} ?> arrow-none"
											href="<?php if ($select_num254 > 1) {
												echo "#";
											} else {
												echo "$link?$url_time";
											} ?>"
											id="<?php echo $image; ?>"<?php if ($select_num254 > 1) { ?> role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-haspopup="true"
											aria-expanded="false"<?php } ?>>
											<i class="icon nav-icon" data-feather="<?php echo $image; ?>"></i>
											<span data-key="t-horizontal"><?php echo $role_name ?></span>
										</a>
										<?php if ($select_num254 > 1) { ?>
										<div class="dropdown-menu" aria-labelledby="<?php echo $image; ?>">
											<?php
											$select25 = mysqli_query($db, "SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name' ORDER BY arrangement ASC ");
											$select_num25 = mysqli_num_rows($select25);
											if ($select_num25 != 0) {
												while ($get125 = mysqli_fetch_array($select25)) {
													$role_name = $get125['name'];
													$file = $get125['file'];
													$sub_url = $get125['sub_url'];


													if ($sub_url == "yes") {
														?>
														<div class="dropdown">
															<a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-pricing"
																role="button">
																<span data-key="t-pricing"><?php echo $role_name; ?></span>
																<div class="arrow-down"></div>
															</a>
															<div class="dropdown-menu" aria-labelledby="topnav-pricing">
																<?php

																$select253 = mysqli_query($db, "SELECT *  FROM  sub_category  where category='$role_name' ");
																$select_num253 = mysqli_num_rows($select253);
																if ($select_num253 != 0) {
																	while ($get1253 = mysqli_fetch_array($select253)) {
																		$role_name_assigned = $get1253['name'];
																		$url = $get1253['url'];




																		?>

																		<a href="<?php echo "$url?$url_time"; ?>" class="dropdown-item"
																			data-key="t-basic"><?php echo "$role_name_assigned "; ?></a>

																		<?php
																	}
																}

																?>
															</div>
														</div>
														<?php

													} else {
														?>
														<a href="<?php echo "$file?$url_time" ?>" class="dropdown-item"
															data-key="t-sales"><?php echo "$role_name "; ?></a>

														<?php
													}


													?>




													<?php
												}
											}

											?>

										</div>
										<?php } ?>
									</li>
									<?php
								} else {
									?>
									<?php
									$select254 = mysqli_query($db, "SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name'");
									$select_num254 = mysqli_num_rows($select254);
									if (strcasecmp(trim((string) $role_name), 'Home') === 0 && $select_num254 > 1) {
										$select_num254 = 1;
									}
									if ($select_num254 != 0) {


									}
									?>
									<li class="nav-item<?php if ($select_num254 > 1) {
										echo " dropdown";
									} ?>" style="">
										<a class="nav-link<?php if ($select_num254 > 1) {
											echo " dropdown-toggle";
										} ?> arrow-none"
											href="<?php if ($select_num254 > 1) {
												echo "#";
											} else {
												echo "$link?$url_time";
											} ?>"
											id="<?php echo $image; ?>"<?php if ($select_num254 > 1) { ?> role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-haspopup="true"
											aria-expanded="false"<?php } ?>>
											<i class="icon nav-icon" data-feather="<?php echo $image; ?>"></i>
											<span data-key="t-horizontal"><?php echo $role_name ?></span>
										</a>
										<?php if ($select_num254 > 1) { ?>
										<div class="dropdown-menu" aria-labelledby="<?php echo $image; ?>">
											<?php
											$select25 = mysqli_query($db, "SELECT *  FROM  roles_assigned  where username='$username' and category='$role_name' ORDER BY arrangement ASC ");
											$select_num25 = mysqli_num_rows($select25);
											if ($select_num25 != 0) {
												while ($get125 = mysqli_fetch_array($select25)) {
													$role_name = $get125['name'];
													$file = $get125['file'];
													$sub_url = $get125['sub_url'];


													if ($sub_url == "yes") {
														?>
														<div class="dropdown">
															<a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-pricing"
																role="button">
																<span data-key="t-pricing"><?php echo $role_name; ?></span>
																<div class="arrow-down"></div>
															</a>
															<div class="dropdown-menu" aria-labelledby="topnav-pricing">
																<?php

																$select253 = mysqli_query($db, "SELECT *  FROM  sub_category  where category='$role_name' ");
																$select_num253 = mysqli_num_rows($select253);
																if ($select_num253 != 0) {
																	while ($get1253 = mysqli_fetch_array($select253)) {
																		$role_name_assigned = $get1253['name'];
																		$url = $get1253['url'];




																		?>

																		<a href="<?php echo "$url?$url_time"; ?>" class="dropdown-item"
																			data-key="t-basic"><?php echo "$role_name_assigned "; ?></a>

																		<?php
																	}
																}

																?>
															</div>
														</div>
														<?php

													} else {
														?>
														<a href="<?php echo "$file?$url_time" ?>" class="dropdown-item"
															data-key="t-sales"><?php echo "$role_name "; ?></a>

														<?php
													}


													?>




													<?php
												}
											}
											$date_insert = date('Y-m-d', time());
											$time_insert = date('h:i:a', time());
											$url = $_SERVER['REQUEST_URI'];

											$sqlQ = "INSERT INTO  logs   (user, page , date,time) VALUES (?,?,?,?)";
											$stmt = $db->prepare($sqlQ);
											if ($stmt) {
												$stmt->bind_param("ssss", $system_user, $url, $date_insert, $time_insert);
												$stmt->execute();
												$stmt->close();
											}
											?>

										</div>
										<?php } ?>
									</li>


									<?php
								}
								?>

								<?php
							}
						}
						?>
					</ul>
				</div>
				 <button class="calculator-open btn btn-dark" id="openCalculator" type="button"><i class="fas fa-calculator"></i>
 Calculator</button>
 
			</nav>
		</div>
	</div>
</header>
  <div class="overlay" id="calculatorOverlay" aria-hidden="true">
    <section class="calculator" role="dialog" aria-modal="true" aria-labelledby="calculatorTitle">
      <div class="calculator-head">
        <h2 id="calculatorTitle" style="color:white;">Calculator</h2>
        <button class="calculator-close" id="closeCalculator" type="button" aria-label="Close calculator">×</button>
      </div>
      <div class="calculator-screen" id="calculatorScreen">0</div>
      <div class="calculator-keys">
        <button class="danger" data-action="clear" type="button">C</button>
        <button class="operator" data-action="backspace" type="button">⌫</button>
        <button class="operator" data-value="%" type="button">%</button>
        <button class="operator" data-value="/" type="button">÷</button>
        <button data-value="7" type="button">7</button>
        <button data-value="8" type="button">8</button>
        <button data-value="9" type="button">9</button>
        <button class="operator" data-value="*" type="button">×</button>
        <button data-value="4" type="button">4</button>
        <button data-value="5" type="button">5</button>
        <button data-value="6" type="button">6</button>
        <button class="operator" data-value="-" type="button">−</button>
        <button data-value="1" type="button">1</button>
        <button data-value="2" type="button">2</button>
        <button data-value="3" type="button">3</button>
        <button class="operator" data-value="+" type="button">+</button>
        <button data-value="0" type="button">0</button>
        <button data-value="." type="button">.</button>
        <button class="equals" data-action="equals" type="button">=</button>
        <button class="operator" data-action="negate" type="button">±</button>
      </div>
    </section>
  </div>
  <div id="idleOverlay" class="idle-overlay">
    <div class="idle-dialog">
        <div class="icon">!</div>
        <h3>Are you still there?</h3>
        <p>You've been inactive for a while.</p>
        <div class="countdown"><span id="countdownNum">60</span>s</div>
        <button id="stayBtn" type="button">Continue Session</button>
    </div>
</div>
<div id="user_info" data-username="<?=base64_encode($system_user)?>" data-fullname="<?=base64_encode($fullname_user)?>"></div>
<script src="searching.js"></script>
<script src="clock.js"></script>
<script src="log_out_user.js<?php echo "?".modified_time("log_out_user.js");?>"></script>
<script src="calculator.js"></script>

