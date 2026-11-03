<?php
/**
 * Shared Tessera booking API helpers (v1).
 * Contract: docs/30 Booking API contract.md
 */
declare(strict_types=1);

const TESSERA_BOOKING_API_VERSION = '1';

function booking_api_boot(string $allowMethods = 'GET, POST, OPTIONS'): void
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: ' . $allowMethods);
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json; charset=utf-8');
    header('X-Tessera-Booking-Api: ' . TESSERA_BOOKING_API_VERSION);
    header('X-Tessera-Booking-Api-Version: ' . TESSERA_BOOKING_API_VERSION);

    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    $tenantHelper = dirname(__DIR__) . '/includes/tessera_tenant.php';
    if (is_file($tenantHelper)) {
        require_once $tenantHelper;
        if (function_exists('tessera_abort_unknown_tenant')) {
            tessera_abort_unknown_tenant();
        }
    }
}

/**
 * @param array<string, mixed> $payload
 */
function booking_api_json(array $payload, int $status = 200): void
{
    if (!array_key_exists('api_version', $payload)) {
        $payload['api_version'] = TESSERA_BOOKING_API_VERSION;
    }
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function booking_api_error(string $error, string $message = '', int $status = 400): void
{
    booking_api_json([
        'success' => false,
        'error'   => $error,
        'message' => $message !== '' ? $message : $error,
    ], $status);
}

function booking_api_require_method(string $method): void
{
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($method)) {
        booking_api_error('method_not_allowed', 'Method not allowed', 405);
    }
}

/** Digits-only phone for tolerant lookups. */
function booking_api_phone_digits(string $phone): string
{
    return preg_replace('/\D+/', '', $phone) ?? '';
}

/**
 * Resolve tenant_id for this booking API request (host -> slug -> tessera_tenants.id).
 * Falls back to flagship id 1.
 */
function booking_api_tenant_id(): int
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $tenantHelper = dirname(__DIR__) . '/includes/tessera_tenant.php';
    if (is_file($tenantHelper)) {
        require_once $tenantHelper;
        if (function_exists('tessera_current_tenant_id')) {
            $cached = (int) tessera_current_tenant_id();
            return $cached;
        }
    }

    global $pdo;
    $slug = getenv('TESSERA_TENANT_SLUG') ?: 'sr-classic';
    if (isset($pdo) && $pdo instanceof PDO) {
        try {
            $st = $pdo->prepare('SELECT id FROM tessera_tenants WHERE slug = ? LIMIT 1');
            $st->execute([$slug]);
            $id = $st->fetchColumn();
            if ($id) {
                $cached = (int) $id;
                return $cached;
            }
        } catch (Throwable $e) {
            // ignore
        }
    }
    $cached = (int) (getenv('TESSERA_TENANT_ID') ?: 1);
    return $cached;
}

/**
 * Wave J3 - soft plan ticket/mo limit for booking API (0 = unlimited).
 */
function booking_api_enforce_plan_ticket_limit(PDO $pdo, int $tenantId): void
{
    try {
        $st = $pdo->prepare('SELECT plan FROM tessera_tenants WHERE id = ? LIMIT 1');
        $st->execute([$tenantId]);
        $plan = (string) ($st->fetchColumn() ?: 'flagship');
    } catch (Throwable $e) {
        return;
    }
    $limits = [
        'standard' => 5000,
        'flagship' => 50000,
        'enterprise' => 0,
    ];
    $max = $limits[$plan] ?? 50000;
    if ($max <= 0) {
        return;
    }
    $from = date('Y-m-01');
    $to = date('Y-m-d');
    try {
        $c = $pdo->prepare(
            "SELECT COUNT(*) FROM mobile_bookings
             WHERE tenant_id = ? AND DATE(booked_at) >= ? AND DATE(booked_at) <= ?"
        );
        $c->execute([$tenantId, $from, $to]);
        $used = (int) $c->fetchColumn();
    } catch (Throwable $e) {
        return;
    }
    if ($used >= $max) {
        booking_api_error(
            'plan_limit',
            "Monthly ticket limit reached for this plan ({$used}/{$max}). Upgrade in Tessera Control.",
            403
        );
    }
}

/** Secret for POS agent tokens (cash confirm). */
function booking_api_agent_secret(): string
{
    $secret = (string) (getenv('AGENT_POS_SECRET') ?: getenv('PLATFORM_VAULT_SECRET') ?: '');
    if ($secret !== '') {
        return $secret;
    }
    // Stable fallback so local/dev still works when env secrets are unset.
    return 'tessera-agent-pos-' . (string) (getenv('MYSQLDATABASE') ?: 'ticket');
}

/**
 * Issue a time-limited POS agent token after agent_login.
 * Format: base64(username|exp|hmac)
 */
function booking_api_issue_agent_token(string $username, int $ttlSeconds = 43200): string
{
    $username = trim($username);
    $exp = time() + max(300, $ttlSeconds);
    $payload = $username . '|' . $exp;
    $sig = hash_hmac('sha256', $payload, booking_api_agent_secret());
    return rtrim(strtr(base64_encode($payload . '|' . $sig), '+/', '-_'), '=');
}

/**
 * Validate POS agent token; returns username or null.
 */
function booking_api_verify_agent_token(string $token): ?string
{
    $token = trim($token);
    if ($token === '') {
        return null;
    }
    $raw = base64_decode(strtr($token, '-_', '+/'), true);
    if ($raw === false) {
        return null;
    }
    $parts = explode('|', $raw);
    if (count($parts) !== 3) {
        return null;
    }
    [$username, $exp, $sig] = $parts;
    if ($username === '' || !ctype_digit((string) $exp)) {
        return null;
    }
    if ((int) $exp < time()) {
        return null;
    }
    $payload = $username . '|' . $exp;
    $expect = hash_hmac('sha256', $payload, booking_api_agent_secret());
    if (!hash_equals($expect, $sig)) {
        return null;
    }
    return $username;
}

/**
 * Authorize immediate cash/POS confirmation.
 * Accepts agent_token (from agent_login) or agent_username + agent_password.
 *
 * @return string Authenticated agent username
 */
function booking_api_require_agent_for_cash(PDO $pdo, string $agentUsername, string $agentPassword, string $agentToken): string
{
    $tokenUser = booking_api_verify_agent_token($agentToken);
    if ($tokenUser !== null) {
        if ($agentUsername !== '' && strcasecmp($agentUsername, $tokenUser) !== 0) {
            booking_api_error('agent_auth_mismatch', 'agent_username does not match token', 401);
        }
        return $tokenUser;
    }

    $agentUsername = trim($agentUsername);
    $agentPassword = (string) $agentPassword;
    if ($agentUsername === '' || $agentPassword === '') {
        booking_api_error(
            'agent_auth_required',
            'Cash/POS confirmation requires agent_token (from agent_login) or agent_username + agent_password',
            401
        );
    }

    require_once dirname(__DIR__) . '/includes/tessera_password.php';
    $stmt = $pdo->prepare("
        SELECT id, username, password
        FROM users
        WHERE status = 'active'
          AND (username = ? OR email = ? OR number = ?)
        LIMIT 1
    ");
    $stmt->execute([$agentUsername, $agentUsername, $agentUsername]);
    $user = $stmt->fetch();
    if (!$user || !tessera_password_verify($agentPassword, (string) $user['password'])) {
        booking_api_error('agent_auth_failed', 'Invalid agent credentials', 401);
    }
    return (string) $user['username'];
}
