<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$session_check = '';
if (!function_exists('logoutUser')) {
    function logoutUser() {
        session_unset();
        session_destroy();
        return 'expired-session';
    }
}
if (!isset($_SESSION['username']) || trim($_SESSION['username']) === '') {
    $session_check = logoutUser();
}

