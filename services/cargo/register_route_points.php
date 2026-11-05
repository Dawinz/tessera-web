<?php
/**
 * Menu alias — route points are not a separate cargo screen.
 * Locations and stops are registered on register_route.php.
 */
require_once __DIR__ . '/includes/tessera_staff_boot.php';
header('Location: register_route.php', true, 302);
exit;
