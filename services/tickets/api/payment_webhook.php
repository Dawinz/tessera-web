<?php
/**
 * POST /api/payment_webhook.php
 * Provider payment-status webhook -> confirm or fail booking.
 * Accepts ClickPesa nested {event,data} envelopes and flat form/JSON.
 */
require_once __DIR__ . '/booking_api.php';
booking_api_boot('POST, OPTIONS');
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    booking_api_error('method_not_allowed', 'Method not allowed', 405);
}
require_once __DIR__ . '/db.php';
require_once dirname(__DIR__) . '/includes/clickpesa_client.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: '', true);
if (!is_array($data)) {
    $data = $_POST;
}

/* Optional checksum when CLICKPESA_CHECKSUM_KEY is set */
$checksumKey = getenv('CLICKPESA_CHECKSUM_KEY');
if ($checksumKey !== false && $checksumKey !== '' && is_array($data) && !empty($data['checksum'])) {
    if (!clickpesa_verify_webhook_checksum($data, (string) $checksumKey)) {
        booking_api_error('invalid_checksum', 'Webhook checksum mismatch', 401);
    }
}

/* Unwrap ClickPesa application webhook: { "event": "...", "data": { ... } } */
$event = strtoupper(trim((string) ($data['event'] ?? '')));
$inner = (isset($data['data']) && is_array($data['data'])) ? $data['data'] : $data;

$bookingRef = strtoupper(trim(
    $inner['booking_ref']
    ?? $inner['orderReference']
    ?? $inner['order_reference']
    ?? $inner['reference']
    ?? $inner['merchantReference']
    ?? $data['booking_ref']
    ?? $data['orderReference']
    ?? $data['reference']
    ?? ''
));
$status = strtolower(trim(
    $inner['status']
    ?? $inner['payment_status']
    ?? $inner['paymentStatus']
    ?? $data['status']
    ?? $data['payment_status']
    ?? ''
));
$providerRef = trim(
    $inner['payment_ref']
    ?? $inner['id']
    ?? $inner['transactionId']
    ?? $inner['clickpesa_id']
    ?? $data['payment_ref']
    ?? $data['id']
    ?? ''
);

if ($bookingRef === '') {
    booking_api_error('booking_ref_required', 'booking_ref required', 400);
}

/* ClickPesa orderReference is alphanumeric (+ optional P****** attempt suffix) */
$bookingRefAlnum = preg_replace('/[^A-Za-z0-9]/', '', $bookingRef);
if (preg_match('/^(.*?)P[A-F0-9]{6}$/i', $bookingRefAlnum, $m)) {
    $bookingRefAlnum = $m[1];
}

$paid = in_array($status, ['success', 'successful', 'paid', 'completed', 'complete'], true)
    || $event === 'PAYMENT RECEIVED';
$failed = in_array($status, ['failed', 'failure', 'cancelled', 'canceled', 'rejected'], true)
    || $event === 'PAYMENT FAILED';

try {
    $find = $pdo->prepare("
        SELECT booking_ref FROM mobile_bookings
        WHERE booking_ref = ?
           OR REPLACE(REPLACE(booking_ref, '-', ''), '_', '') = ?
           OR ? LIKE CONCAT(REPLACE(REPLACE(booking_ref, '-', ''), '_', ''), '%')
        LIMIT 1
    ");
    $find->execute([$bookingRef, $bookingRefAlnum, preg_replace('/[^A-Za-z0-9]/', '', $bookingRef)]);
    if (!$find->fetch()) {
        booking_api_error('not_found', 'Booking not found', 404);
    }

    if ($paid) {
        $upd = $pdo->prepare("
            UPDATE mobile_bookings
            SET status = 'confirmed',
                payment_status = 'paid',
                clickpesa_id = COALESCE(NULLIF(?, ''), clickpesa_id)
            WHERE booking_ref = ?
               OR REPLACE(REPLACE(booking_ref, '-', ''), '_', '') = ?
               OR ? LIKE CONCAT(REPLACE(REPLACE(booking_ref, '-', ''), '_', ''), '%')
            LIMIT 1
        ");
        $upd->execute([$providerRef, $bookingRef, $bookingRefAlnum, preg_replace('/[^A-Za-z0-9]/', '', $bookingRef)]);
    } else {
        $upd = $pdo->prepare("
            UPDATE mobile_bookings
            SET payment_status = ?
            WHERE booking_ref = ?
               OR REPLACE(REPLACE(booking_ref, '-', ''), '_', '') = ?
               OR ? LIKE CONCAT(REPLACE(REPLACE(booking_ref, '-', ''), '_', ''), '%')
            LIMIT 1
        ");
        $upd->execute([
            $failed ? 'failed' : ($status ?: 'failed'),
            $bookingRef,
            $bookingRefAlnum,
            preg_replace('/[^A-Za-z0-9]/', '', $bookingRef),
        ]);
    }

    /* Touch Control payment vault webhook last-seen */
    $tenantHelper = dirname(__DIR__) . '/includes/tessera_tenant.php';
    if (is_file($tenantHelper)) {
        require_once $tenantHelper;
        if (function_exists('tessera_payment_webhook_touch')) {
            tessera_payment_webhook_touch($paid ? 'ok' : ($status ?: 'failed'), $paid);
        }
    }

    if ($paid) {
        try {
            require_once dirname(__DIR__) . '/connect.php';
            require_once dirname(__DIR__) . '/includes/tessera_messaging.php';
            require_once dirname(__DIR__) . '/includes/tessera_operator_api.php';
            require_once dirname(__DIR__) . '/includes/tessera_tenant.php';
            $tid = function_exists('tessera_current_tenant_id') ? (int) tessera_current_tenant_id() : 1;
            $phoneStmt = $pdo->prepare('SELECT phone, tenant_id FROM mobile_bookings WHERE booking_ref = ? LIMIT 1');
            $phoneStmt->execute([$bookingRef]);
            $brow = $phoneStmt->fetch(PDO::FETCH_ASSOC);
            if ($brow) {
                $tid = (int) ($brow['tenant_id'] ?: $tid);
                tessera_notify_event($db, $tid, 'ticket.paid', (string) $brow['phone'], [
                    'message' => "Payment confirmed for booking {$bookingRef}.",
                    'booking_ref' => $bookingRef,
                ]);
                tessera_webhook_dispatch($db, $tid, 'booking.paid', [
                    'booking_ref' => $bookingRef,
                    'payment_status' => 'paid',
                ]);
            }
        } catch (Throwable $e) {
            /* ignore */
        }
    }

    booking_api_json([
        'success'     => true,
        'booking_ref' => $bookingRef,
        'paid'        => $paid,
        'event'       => $event !== '' ? $event : null,
    ]);
} catch (Exception $e) {
    booking_api_error('server_error', 'Server error', 500);
}
