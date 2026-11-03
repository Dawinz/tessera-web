<?php
/**
 * Open authenticated Control page (Dashonic vertical dark).
 * Expects: $__page_title, $control_nav, $control_user (from auth_guard).
 */
?>
<!doctype html>
<html lang="en">
<head>
<?php include __DIR__ . '/control_chrome_head.php'; ?>
<?php if (!empty($control_extra_head)) { echo $control_extra_head; } ?>
</head>
<body data-sidebar="dark" data-topbar="dark" data-layout-mode="dark">
<div id="layout-wrapper">
	<?php include __DIR__ . '/control_header.php'; ?>
	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">
