<?php
/**
 * Cache-bust helper for page assets.
 * Under the operator SPA, PHP_SELF is spa.php/app.php - never filemtime that.
 */
$this_page = '';
if (!empty($_GET['page']) && is_string($_GET['page'])) {
	$candidate = basename(str_replace('\\', '/', $_GET['page']));
	if ($candidate !== '' && is_file(__DIR__ . '/' . $candidate)) {
		$this_page = $candidate;
	}
}
if ($this_page === '') {
	$self = isset($_SERVER['PHP_SELF']) ? (string) $_SERVER['PHP_SELF'] : '';
	$base = basename(str_replace('\\', '/', $self));
	if ($base !== '' && is_file(__DIR__ . '/' . $base)) {
		$this_page = $base;
	}
}
if ($this_page === '' && !empty($_SERVER['SCRIPT_FILENAME']) && is_file((string) $_SERVER['SCRIPT_FILENAME'])) {
	$this_page = basename((string) $_SERVER['SCRIPT_FILENAME']);
}

$modification_time = ($this_page !== '' && is_file(__DIR__ . '/' . $this_page))
	? (int) filemtime(__DIR__ . '/' . $this_page)
	: time();
$url_time = 'v=' . $modification_time;

if (!function_exists('modified_time')) {
	function modified_time($path) {
		$full = $path;
		if ($path !== '' && $path[0] !== '/' && !preg_match('#^[A-Za-z]:[/\\\\]#', $path)) {
			$full = __DIR__ . '/' . ltrim(str_replace('\\', '/', $path), '/');
		}
		$mtime = is_file($full) ? (int) filemtime($full) : time();
		return 'v=' . $mtime;
	}
}
