<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (!empty($_SESSION['control_email'])) {
	header('Location: overview.php');
	exit;
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Sign In | Tessera Control</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/tickets/assets/images/favicon.ico">
	<link href="/tickets/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="/tickets/assets/css/icons.min.css" rel="stylesheet" type="text/css">
	<link href="/tickets/assets/css/app.min.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div class="authentication-bg min-vh-100">
		<div class="bg-overlay bg-white"></div>
		<div class="container">
			<div class="d-flex flex-column min-vh-100 px-3 pt-4">
				<div class="row justify-content-center my-auto">
					<div class="col-md-8 col-lg-6 col-xl-4">
						<div class="text-center py-5">
							<div class="mb-4 mb-md-5">
								<a href="index.php" class="d-block auth-logo">
									<span class="tessera-brand" style="font-size:1.75rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#03314d;">Tessera</span>
								</a>
								<p class="mt-2 text-uppercase small fw-semibold" style="letter-spacing:.16em;color:#0c6b5c;">Control</p>
							</div>
							<div class="mb-4">
								<h5>Platform owner sign in</h5>
								<p class="text-muted">SaaS overview, operators, and onboarding — not the staff console.</p>
							</div>
							<div>
								<div class="form-floating form-floating-custom mb-3">
									<input type="text" class="form-control" id="username" placeholder="Email" autocomplete="username">
									<label for="username">Email</label>
									<div class="form-floating-icon"><i class="uil uil-envelope"></i></div>
								</div>
								<div class="form-floating form-floating-custom mb-3">
									<input type="password" class="form-control" id="password" placeholder="Password" autocomplete="current-password">
									<label for="password">Password</label>
									<div class="form-floating-icon"><i class="uil uil-padlock"></i></div>
								</div>
								<div class="mt-3">
									<button class="btn btn-info w-100" type="button" id="login">Sign in</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12">
						<div class="text-center text-muted p-4">
							<p class="mb-0">&copy; <script>document.write(new Date().getFullYear())</script> Tessera</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="/tickets/assets/libs/jquery/jquery.min.js"></script>
	<script src="/tickets/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="/tickets/js/sweetalert.js"></script>
	<script src="login.js"></script>
</body>
</html>
