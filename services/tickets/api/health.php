<?php
/**
 * GET /api/health.php - booking API liveness + contract version.
 */
require_once __DIR__ . '/booking_api.php';
booking_api_boot('GET, OPTIONS');
booking_api_require_method('GET');

$dbOk = false;
$dbDetail = '';
$paymentMode = 'unknown';
$paymentConfigured = false;
try {
    require_once __DIR__ . '/db.php';
    require_once dirname(__DIR__) . '/includes/clickpesa_client.php';
    $pdo->query('SELECT 1');
    $dbOk = true;
    $paymentConfigured = clickpesa_configured();
    $paymentMode = clickpesa_should_mock() ? 'mock' : 'live';
} catch (Throwable $e) {
    $dbDetail = 'unavailable';
}

booking_api_json([
    'success'     => $dbOk,
    'ok'          => $dbOk,
    'service'     => 'tessera-booking-api',
    'api_version' => TESSERA_BOOKING_API_VERSION,
    'db'          => $dbOk ? 'ok' : 'error',
    'detail'      => $dbDetail,
    'payment'     => [
        'mode'        => $paymentMode,
        'configured'  => $paymentConfigured,
        /* Never expose secrets or provider brand in public JSON */
    ],
    'endpoints'   => [
        'GET  health.php',
        'GET  trips.php',
        'POST book_ticket.php',
        'GET  get_booking.php',
        'POST payment_initiate.php',
        'POST payment_webhook.php',
        'POST agent_login.php',
    ],
], $dbOk ? 200 : 503);
