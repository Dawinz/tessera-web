<?php
/**
 * POST /api/book_ticket.php
 * Creates a booking. Default status=pending until payment confirms,
 * unless channel=pos_cash (admin POS cash) -> confirmed immediately.
 */
require_once __DIR__ . '/booking_api.php';
booking_api_boot('POST, OPTIONS');
booking_api_require_method('POST');
require_once __DIR__ . '/db.php';

function req(string $key): string
{
    return trim($_POST[$key] ?? '');
}

$tripId        = req('trip_id');
$seatNumber    = strtoupper(req('seat_number'));
$firstName     = req('first_name');
$lastName      = req('last_name');
$phone         = req('phone');
$email         = req('email');
$gender        = req('gender') ?: 'Male';
$passengerType = req('passenger_type') ?: 'Adult';
$boardingStop  = req('boarding_stop');
$droppingStop  = req('dropping_stop');
$paymentMethod = req('payment_method');
$paymentPhone  = req('payment_phone');
$fare          = (float) req('fare');
$levy          = (float) req('levy');
$total         = (float) req('total');
$channel       = req('channel') ?: 'customer_app';
$agentUsername = req('agent_username');
$agentPassword = req('agent_password');
$agentToken    = req('agent_token');

if (!$tripId || !$seatNumber || !$firstName || !$lastName || !$phone || !$paymentMethod) {
    booking_api_error('missing_fields', 'Missing required fields', 400);
}

$allowedRails = ['mpesa', 'tigopesa', 'airtel', 'halopesa', 'cash', 'pos_cash'];
if (!in_array(strtolower($paymentMethod), $allowedRails, true)) {
    booking_api_error('payment_method_invalid', 'Payment method is not available', 400);
}

$confirmNow = in_array(strtolower($paymentMethod), ['cash', 'pos_cash'], true)
    || $channel === 'pos_cash';

try {
    $tenantId = booking_api_tenant_id();
    // Cash/POS must not confirm seats without a verified agent (public free-ticket abuse).
    if ($confirmNow) {
        $agentUsername = booking_api_require_agent_for_cash($pdo, $agentUsername, $agentPassword, $agentToken);
    }
    booking_api_enforce_plan_ticket_limit($pdo, $tenantId);
    $tripStmt = $pdo->prepare('SELECT * FROM mobile_trips WHERE id = ? AND tenant_id = ?');
    $tripStmt->execute([$tripId, $tenantId]);
    $trip = $tripStmt->fetch();
    if (!$trip) {
        booking_api_error('trip_not_found', 'Trip not found', 404);
    }

    $pdo->beginTransaction();

    $checkStmt = $pdo->prepare(
        "SELECT id FROM mobile_bookings
         WHERE trip_id = ? AND tenant_id = ? AND seat_number = ? AND status IN ('confirmed','pending')
         FOR UPDATE"
    );
    $checkStmt->execute([$tripId, $tenantId, $seatNumber]);
    if ($checkStmt->fetch()) {
        $pdo->rollBack();
        booking_api_error('seat_taken', 'Seat already taken', 409);
    }

    $datePart = date('Ymd');
    $countStmt = $pdo->prepare(
        "SELECT COUNT(*) FROM mobile_bookings WHERE tenant_id = ? AND booking_ref LIKE ?"
    );
    $countStmt->execute([$tenantId, "SRC-{$datePart}-%"]);
    $seq = (int) $countStmt->fetchColumn() + 1;
    $bookingRef = 'SRC-' . $datePart . '-' . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);

    $status = $confirmNow ? 'confirmed' : 'pending';
    $paymentStatus = $confirmNow ? 'paid' : 'pending';

    $insert = $pdo->prepare("
        INSERT INTO mobile_bookings
            (tenant_id, booking_ref, trip_id, seat_number, first_name, last_name, phone, email,
             gender, passenger_type, boarding_stop, dropping_stop,
             payment_method, payment_phone, payment_status, fare, levy, total,
             status, channel, agent_username)
        VALUES
            (:tenant_id, :ref, :trip_id, :seat, :fname, :lname, :phone, :email,
             :gender, :ptype, :board, :drop,
             :pmethod, :pphone, :pstatus, :fare, :levy, :total,
             :status, :channel, :agent)
    ");
    $insert->execute([
        ':tenant_id' => $tenantId,
        ':ref'     => $bookingRef,
        ':trip_id' => $tripId,
        ':seat'    => $seatNumber,
        ':fname'   => $firstName,
        ':lname'   => $lastName,
        ':phone'   => $phone,
        ':email'   => $email,
        ':gender'  => $gender,
        ':ptype'   => $passengerType,
        ':board'   => $boardingStop,
        ':drop'    => $droppingStop,
        ':pmethod' => $paymentMethod,
        ':pphone'  => $paymentPhone,
        ':pstatus' => $paymentStatus,
        ':fare'    => $fare,
        ':levy'    => $levy,
        ':total'   => $total,
        ':status'  => $status,
        ':channel' => $channel,
        ':agent'   => $agentUsername,
    ]);

    $pdo->commit();

    /* Wave N/O - notify + outbound webhooks (best-effort) */
    try {
        $mysqliNotify = function_exists('sr_mysqli') ? sr_mysqli() : null;
        if (!$mysqliNotify && isset($db) && $db instanceof mysqli) {
            $mysqliNotify = $db;
        }
        if (!$mysqliNotify) {
            require_once dirname(__DIR__) . '/connect.php';
            $mysqliNotify = $db ?? null;
        }
        if ($mysqliNotify instanceof mysqli) {
            require_once dirname(__DIR__) . '/includes/tessera_messaging.php';
            require_once dirname(__DIR__) . '/includes/tessera_operator_api.php';
            $event = $confirmNow ? 'ticket.paid' : 'ticket.booked';
            $msg = $confirmNow
                ? "Booking {$bookingRef} confirmed. Seat {$seatNumber}."
                : "Booking {$bookingRef} created. Complete payment for seat {$seatNumber}.";
            tessera_notify_event($mysqliNotify, (int) $tenantId, $event, $phone, [
                'message' => $msg,
                'booking_ref' => $bookingRef,
            ]);
            $whEvent = $confirmNow ? 'booking.paid' : 'booking.confirmed';
            tessera_webhook_dispatch($mysqliNotify, (int) $tenantId, $whEvent, [
                'booking_ref' => $bookingRef,
                'phone' => $phone,
                'status' => $status,
                'payment_status' => $paymentStatus,
            ]);
        }
    } catch (Throwable $e) {
        /* never fail booking on notify */
    }

    booking_api_json([
        'success'          => true,
        'tenant_id'        => $tenantId,
        'booking_ref'      => $bookingRef,
        'trip_id'          => (string) $tripId,
        'seat_number'      => $seatNumber,
        'passenger_name'   => trim("$firstName $lastName"),
        'phone'            => $phone,
        'route'            => $trip['route'],
        'trip_date'        => $trip['trip_date'],
        'dep_time'         => substr((string) $trip['departure_time'], 0, 5),
        'fare'             => (string) $fare,
        'levy'             => (string) $levy,
        'total'            => (string) $total,
        'payment_method'   => $paymentMethod,
        'payment_status'   => $paymentStatus,
        'status'           => $status,
        'needs_payment'    => !$confirmNow,
        'booked_at'        => date('Y-m-d H:i:s'),
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    booking_api_error('server_error', 'Server error', 500);
}
