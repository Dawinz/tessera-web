<?php
/**
 * POST /api/agent_login.php
 * Authenticates staff against legacy `users` table (admin POS).
 */
require_once __DIR__ . '/booking_api.php';
booking_api_boot('POST, OPTIONS');
booking_api_require_method('POST');
require_once __DIR__ . '/db.php';
require_once dirname(__DIR__) . '/includes/tessera_password.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    booking_api_error('username_password_required', 'username and password required', 400);
}

try {
    $stmt = $pdo->prepare("
        SELECT id, username, firstname, surname, role, status, password
        FROM users
        WHERE status = 'active'
          AND (username = ? OR email = ? OR number = ?)
        LIMIT 1
    ");
    $stmt->execute([$username, $username, $username]);
    $user = $stmt->fetch();

    if (!$user || !tessera_password_verify($password, (string) $user['password'])) {
        booking_api_json([
            'success' => false,
            'error'   => 'invalid_credentials',
            'message' => 'Invalid credentials',
        ]);
    }

    // Upgrade legacy plain-text passwords on successful API login.
    if (!preg_match('/^\$(2[ay]|argon2)/', (string) $user['password'])) {
        $upd = $pdo->prepare('UPDATE users SET password = ? WHERE id = ? LIMIT 1');
        $upd->execute([tessera_password_hash($password), $user['id']]);
    }

    $token = booking_api_issue_agent_token((string) $user['username']);

    booking_api_json([
        'success'     => true,
        'username'    => $user['username'],
        'name'        => trim($user['firstname'] . ' ' . $user['surname']),
        'role'        => $user['role'],
        'agent_token' => $token,
        'token_ttl'   => 43200,
    ]);
} catch (Exception $e) {
    booking_api_error('server_error', 'Server error', 500);
}
