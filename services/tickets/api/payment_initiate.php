<?php
/**
 * POST /api/payment_initiate.php
 * Start (or mock) mobile-money collection for a pending booking.
 * Provider details stay server-side - clients only see payment status.
 */
require_once __DIR__ . '/booking_api.php';
booking_api_boot('POST, OPTIONS');
booking_api_require_method('POST');
require_once __DIR__ . '/db.php';
require_once dirname(__DIR__) . '/includes/clickpesa_client.php';

$bookingRef    = strtoupper(trim($_POST['booking_ref'] ?? ''));
$paymentPhone  = trim($_POST['payment_phone'] ?? '');
$paymentMethod = strtolower(trim($_POST['payment_method'] ?? ''));

if ($bookingRef === '') {
    booking_api_error('booking_ref_required', 'booking_ref required', 400);
}

$allowedRails = ['mpesa', 'tigopesa', 'airtel', 'halopesa', 'cash', 'pos_cash'];
if ($paymentMethod !== '' && !in_array($paymentMethod, $allowedRails, true)) {
    booking_api_error('payment_method_invalid', 'Payment method is not available', 400);
}

$mock = clickpesa_should_mock() ? '1' : '0';

try {
    $tenantId = booking_api_tenant_id();
    $stmt = $pdo->prepare('SELECT * FROM mobile_bookings WHERE booking_ref = ? AND tenant_id = ? LIMIT 1');
    $stmt->execute([$bookingRef, $tenantId]);
    $booking = $stmt->fetch();
    if (!$booking) {
        booking_api_error('not_found', 'Booking not found', 404);
    }

    if ($booking['status'] === 'confirmed' && $booking['payment_status'] === 'paid') {
        booking_api_json([
            'success'     => true,
            'status'      => 'already_paid',
            'booking_ref' => $bookingRef,
        ]);
    }

    if ($mock === '1') {
        $providerRef = 'PAY-' . $bookingRef;
        $upd = $pdo->prepare("
            UPDATE mobile_bookings
            SET status = 'confirmed',
                payment_status = 'paid',
                clickpesa_id = ?,
                payment_phone = COALESCE(NULLIF(?, ''), payment_phone),
                payment_method = COALESCE(NULLIF(?, ''), payment_method)
            WHERE booking_ref = ? AND tenant_id = ?
        ");
        $upd->execute([$providerRef, $paymentPhone, $paymentMethod, $bookingRef, $tenantId]);

        booking_api_json([
            'success'      => true,
            'mode'         => 'mock',
            'status'       => 'paid',
            'booking_ref'  => $bookingRef,
            'payment_ref'  => $providerRef,
            'message'      => 'Payment completed',
        ]);
    }

    if (!clickpesa_configured()) {
        booking_api_json([
            'success' => false,
            'error'   => 'payment_provider_unavailable',
            'message' => 'Payment could not be started. Try again shortly.',
        ]);
    }

    $phone = preg_replace('/\D+/', '', $paymentPhone !== '' ? $paymentPhone : (string) ($booking['payment_phone'] ?? ''));
    if ($phone !== '' && strpos($phone, '0') === 0) {
        $phone = '255' . substr($phone, 1);
    }
    if ($phone === '' || strlen($phone) < 10) {
        booking_api_error('payment_phone_required', 'payment_phone required', 400);
    }

    // Schema column is `total` (fare + levy); keep legacy aliases for older rows.
    $amount = (string) ($booking['total'] ?? $booking['amount'] ?? $booking['total_amount'] ?? $booking['fare'] ?? '0');
    $webhook = clickpesa_webhook_url();

    $init = clickpesa_initiate_ussd_push([
        'amount' => $amount,
        'phone' => $phone,
        'order_reference' => $bookingRef,
        'currency' => 'TZS',
        'webhook_url' => $webhook,
    ]);

    if (empty($init['ok'])) {
        $providerMsg = '';
        if (!empty($init['response']['data']['message'])) {
            $providerMsg = is_array($init['response']['data']['message'])
                ? implode(' ', $init['response']['data']['message'])
                : (string) $init['response']['data']['message'];
        } elseif (!empty($init['response']['raw'])) {
            $rawDecoded = json_decode((string) $init['response']['raw'], true);
            if (is_array($rawDecoded) && !empty($rawDecoded['message'])) {
                $providerMsg = is_array($rawDecoded['message'])
                    ? implode(' ', $rawDecoded['message'])
                    : (string) $rawDecoded['message'];
            }
        }
        $friendly = 'Payment could not be started. Try again shortly.';
        if (stripos($providerMsg, 'insufficient') !== false) {
            $friendly = 'Mobile money wallet has insufficient funds. Top up and try again.';
        } elseif (stripos($providerMsg, 'already used') !== false) {
            $friendly = 'This payment attempt expired. Start payment again.';
        } elseif (stripos($providerMsg, 'checksum') !== false) {
            $friendly = 'Payment security check failed. Contact support.';
        }
        booking_api_json([
            'success' => false,
            'error'   => 'payment_provider_unavailable',
            'message' => $friendly,
        ]);
    }

    $providerId = $init['provider_id'] !== '' ? $init['provider_id'] : ('CP-' . $bookingRef);
    $upd = $pdo->prepare("
        UPDATE mobile_bookings
        SET payment_status = 'pending',
            clickpesa_id = ?,
            payment_phone = ?,
            payment_method = COALESCE(NULLIF(?, ''), payment_method)
        WHERE booking_ref = ? AND tenant_id = ?
    ");
    $upd->execute([$providerId, $phone, $paymentMethod, $bookingRef, $tenantId]);

    booking_api_json([
        'success'     => true,
        'mode'        => 'live',
        'status'      => 'pending',
        'booking_ref' => $bookingRef,
        'payment_ref' => $providerId,
        'message'     => 'Confirm the payment prompt on your phone',
    ]);
} catch (Exception $e) {
    booking_api_error('server_error', 'Server error', 500);
}
