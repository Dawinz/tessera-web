<?php
/**
 * Operator settings — self profile helpers (S-07).
 */
declare(strict_types=1);

require_once __DIR__ . '/tessera_rbac.php';
require_once __DIR__ . '/tessera_password.php';
require_once __DIR__ . '/tessera_staff_sync.php';

if (!function_exists('tessera_settings_profile_avatar_url')) {
	function tessera_settings_profile_avatar_url(string $image): string
	{
		$image = trim($image);
		if ($image === '' || $image === 'user.png') {
			return '/tickets/assets/images/users/user.png';
		}
		if (preg_match('#^https?://#i', $image)) {
			return $image;
		}
		return '/tickets/uploads/' . rawurlencode($image);
	}
}

if (!function_exists('tessera_settings_profile_public')) {
	/**
	 * @param array<string,mixed> $row
	 * @return array<string,mixed>
	 */
	function tessera_settings_profile_public(mysqli $db, array $row): array
	{
		$username = (string) ($row['username'] ?? '');
		$role = !empty($row['tessera_role'])
			? (string) $row['tessera_role']
			: tessera_map_legacy_role((string) ($row['role'] ?? ''));
		$labels = [
			'owner' => 'Owner',
			'manager' => 'Manager',
			'tickets_agent' => 'Tickets agent',
			'cargo_agent' => 'Cargo agent',
			'accountant' => 'Accountant',
		];
		$image = (string) ($row['image'] ?? '');
		return [
			'username' => $username,
			'firstname' => (string) ($row['firstname'] ?? ''),
			'surname' => (string) ($row['surname'] ?? ''),
			'fullname' => trim((string) ($row['firstname'] ?? '') . ' ' . (string) ($row['surname'] ?? '')),
			'email' => (string) ($row['email'] ?? ''),
			'number' => (string) ($row['number'] ?? ''),
			'legacy_role' => (string) ($row['role'] ?? ''),
			'role' => $role,
			'role_label' => $labels[$role] ?? $role,
			'status' => (string) ($row['status'] ?? ''),
			'image' => $image,
			'avatar_url' => tessera_settings_profile_avatar_url($image),
			'capabilities' => tessera_user_capabilities($db, $username),
		];
	}
}

if (!function_exists('tessera_settings_profile_fetch_row')) {
	/** @return array<string,mixed>|null */
	function tessera_settings_profile_fetch_row(mysqli $db, string $username): ?array
	{
		$u = $db->real_escape_string(trim($username));
		if ($u === '') {
			return null;
		}
		$hasCol = false;
		$c = @$db->query("SHOW COLUMNS FROM users LIKE 'tessera_role'");
		if ($c && $c->num_rows > 0) {
			$hasCol = true;
		}
		$sql = $hasCol
			? "SELECT id, username, firstname, surname, email, number, role, tessera_role, status, image, password FROM users WHERE username='{$u}' LIMIT 1"
			: "SELECT id, username, firstname, surname, email, number, role, status, image, password FROM users WHERE username='{$u}' LIMIT 1";
		$q = @$db->query($sql);
		$row = $q ? $q->fetch_assoc() : null;
		return $row ?: null;
	}
}

if (!function_exists('tessera_settings_profile_get')) {
	/**
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>}
	 */
	function tessera_settings_profile_get(mysqli $db, string $username): array
	{
		$row = tessera_settings_profile_fetch_row($db, $username);
		if (!$row) {
			return ['ok' => false, 'error' => 'Profile not found'];
		}
		return ['ok' => true, 'item' => tessera_settings_profile_public($db, $row)];
	}
}

if (!function_exists('tessera_settings_profile_update')) {
	/**
	 * @param array<string,mixed> $input
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>,message?:string}
	 */
	function tessera_settings_profile_update(mysqli $db, string $username, array $input): array
	{
		$row = tessera_settings_profile_fetch_row($db, $username);
		if (!$row) {
			return ['ok' => false, 'error' => 'Profile not found'];
		}
		$firstname = trim((string) ($input['firstname'] ?? $row['firstname'] ?? ''));
		$surname = trim((string) ($input['surname'] ?? $row['surname'] ?? ''));
		$email = trim((string) ($input['email'] ?? $row['email'] ?? ''));
		$number = trim((string) ($input['number'] ?? $row['number'] ?? ''));
		if ($firstname === '') {
			return ['ok' => false, 'error' => 'First name is required'];
		}

		$role = !empty($row['tessera_role'])
			? (string) $row['tessera_role']
			: tessera_map_legacy_role((string) ($row['role'] ?? ''));

		$res = tessera_apply_staff_role_everywhere($username, $role, [
			'firstname' => $firstname,
			'surname' => $surname,
			'email' => $email,
			'number' => $number,
			'status' => (string) ($row['status'] ?? 'active'),
		]);
		if (empty($res['ok'])) {
			/* Ticket-only fallback if cargo/account sync partial */
			$u = $db->real_escape_string($username);
			$fn = $db->real_escape_string($firstname);
			$sn = $db->real_escape_string($surname);
			$em = $db->real_escape_string($email);
			$num = $db->real_escape_string($number);
			@$db->query(
				"UPDATE users SET firstname='{$fn}', surname='{$sn}', email='{$em}', number='{$num}'
				 WHERE username='{$u}' LIMIT 1"
			);
		}

		$got = tessera_settings_profile_get($db, $username);
		$got['message'] = 'Profile saved';
		return $got;
	}
}

if (!function_exists('tessera_settings_profile_password')) {
	/**
	 * @return array{ok:bool,error?:string,message?:string}
	 */
	function tessera_settings_profile_password(mysqli $db, string $username, string $current, string $next): array
	{
		$row = tessera_settings_profile_fetch_row($db, $username);
		if (!$row) {
			return ['ok' => false, 'error' => 'Profile not found'];
		}
		if ($current === '' || $next === '') {
			return ['ok' => false, 'error' => 'Current and new password are required'];
		}
		if (strlen($next) < 8) {
			return ['ok' => false, 'error' => 'New password must be at least 8 characters'];
		}
		if (!tessera_password_verify($current, (string) ($row['password'] ?? ''))) {
			return ['ok' => false, 'error' => 'Current password is incorrect'];
		}

		$role = !empty($row['tessera_role'])
			? (string) $row['tessera_role']
			: tessera_map_legacy_role((string) ($row['role'] ?? ''));

		$res = tessera_apply_staff_role_everywhere($username, $role, [
			'firstname' => (string) ($row['firstname'] ?? ''),
			'surname' => (string) ($row['surname'] ?? ''),
			'email' => (string) ($row['email'] ?? ''),
			'number' => (string) ($row['number'] ?? ''),
			'status' => (string) ($row['status'] ?? 'active'),
			'password' => $next,
		]);
		if (empty($res['ok'])) {
			$u = $db->real_escape_string($username);
			$ph = $db->real_escape_string(tessera_password_hash($next));
			$ok = @$db->query("UPDATE users SET password='{$ph}' WHERE username='{$u}' LIMIT 1");
			if (!$ok) {
				return ['ok' => false, 'error' => 'Could not update password'];
			}
		}
		return ['ok' => true, 'message' => 'Password updated'];
	}
}

if (!function_exists('tessera_settings_profile_avatar')) {
	/**
	 * @param array<string,mixed> $file $_FILES entry
	 * @return array{ok:bool,error?:string,item?:array<string,mixed>,message?:string}
	 */
	function tessera_settings_profile_avatar(mysqli $db, string $username, array $file): array
	{
		$row = tessera_settings_profile_fetch_row($db, $username);
		if (!$row) {
			return ['ok' => false, 'error' => 'Profile not found'];
		}
		if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
			return ['ok' => false, 'error' => 'Upload failed'];
		}
		$size = (int) ($file['size'] ?? 0);
		if ($size < 1 || $size > 1000000) {
			return ['ok' => false, 'error' => 'Image must be under 1 MB'];
		}
		$tmp = (string) ($file['tmp_name'] ?? '');
		$name = (string) ($file['name'] ?? '');
		$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
			return ['ok' => false, 'error' => 'Only JPG, PNG, or WebP allowed'];
		}
		$uploads = dirname(__DIR__) . '/uploads';
		if (!is_dir($uploads)) {
			@mkdir($uploads, 0755, true);
		}
		$newName = 'IMG-' . bin2hex(random_bytes(8)) . '.' . ($ext === 'jpeg' ? 'jpg' : $ext);
		$dest = $uploads . '/' . $newName;
		if (!@move_uploaded_file($tmp, $dest)) {
			return ['ok' => false, 'error' => 'Could not store image'];
		}
		$u = $db->real_escape_string($username);
		$img = $db->real_escape_string($newName);
		@$db->query("UPDATE users SET image='{$img}' WHERE username='{$u}' LIMIT 1");

		$got = tessera_settings_profile_get($db, $username);
		$got['message'] = 'Photo updated';
		return $got;
	}
}
