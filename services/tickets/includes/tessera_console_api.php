<?php
/**
 * Operator console JSON API helpers (staff session).
 * Contract: docs/51 Operator console JSON API.md
 */
declare(strict_types=1);

const TESSERA_CONSOLE_API_VERSION = '1';

if (!function_exists('tessera_console_boot')) {
	/**
	 * @param list<string>|string $allowMethods
	 */
	function tessera_console_boot(string $allowMethods = 'GET, OPTIONS'): void
	{
		if (is_string($allowMethods)) {
			$methods = $allowMethods;
		} else {
			$methods = implode(', ', $allowMethods);
		}

		header('Content-Type: application/json; charset=utf-8');
		header('Cache-Control: no-store');
		header('X-Tessera-Console-Api: ' . TESSERA_CONSOLE_API_VERSION);
		/* Same-origin staff APIs — do not emit Access-Control-Allow-Origin: * (breaks credentialed browsers). */

		if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
			http_response_code(204);
			exit;
		}

		$tenantHelper = __DIR__ . '/tessera_tenant.php';
		if (is_file($tenantHelper)) {
			require_once $tenantHelper;
			if (function_exists('tessera_abort_unknown_tenant')) {
				tessera_abort_unknown_tenant();
			}
		}
	}
}

if (!function_exists('tessera_console_meta')) {
	/**
	 * @param array<string, mixed> $extra
	 * @return array<string, mixed>
	 */
	function tessera_console_meta(array $extra = []): array
	{
		return array_merge([
			'api' => 'console',
			'api_version' => TESSERA_CONSOLE_API_VERSION,
		], $extra);
	}
}

if (!function_exists('tessera_console_json')) {
	/**
	 * @param mixed $data
	 * @param array{code?:string,message?:string,fields?:mixed}|null $error
	 * @param array<string, mixed> $meta
	 */
	function tessera_console_json($data, ?array $error, int $status = 200, array $meta = []): void
	{
		http_response_code($status);
		echo json_encode([
			'ok' => $error === null,
			'data' => $data,
			'error' => $error,
			'meta' => tessera_console_meta($meta),
		], JSON_UNESCAPED_UNICODE);
		exit;
	}
}

if (!function_exists('tessera_console_ok')) {
	/**
	 * @param mixed $data
	 * @param array<string, mixed> $meta
	 */
	function tessera_console_ok($data, array $meta = [], int $status = 200): void
	{
		tessera_console_json($data, null, $status, $meta);
	}
}

if (!function_exists('tessera_console_error')) {
	/**
	 * @param array<string, mixed> $extraError
	 * @param array<string, mixed> $meta
	 */
	function tessera_console_error(string $code, string $message, int $status = 400, array $extraError = [], array $meta = []): void
	{
		$error = array_merge([
			'code' => $code,
			'message' => $message !== '' ? $message : $code,
		], $extraError);
		tessera_console_json(null, $error, $status, $meta);
	}
}

if (!function_exists('tessera_console_require_method')) {
	function tessera_console_require_method(string $method): void
	{
		if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($method)) {
			tessera_console_error('method_not_allowed', 'Method not allowed', 405);
		}
	}
}

if (!function_exists('tessera_console_require_session')) {
	/**
	 * Staff session required. Returns username.
	 * If there is no session cookie, do not session_start() — that would Set-Cookie a
	 * new empty SID and wipe the browser’s real login cookie (SPA race).
	 */
	function tessera_console_require_session(): string
	{
		if (session_status() !== PHP_SESSION_ACTIVE) {
			$cookieName = session_name();
			if (empty($_COOKIE[$cookieName])) {
				tessera_console_error('unauthorized', 'Sign in required', 401);
			}
			session_start();
		}
		$user = trim((string) ($_SESSION['username'] ?? ''));
		if ($user === '') {
			tessera_console_error('unauthorized', 'Sign in required', 401);
		}
		return $user;
	}
}

if (!function_exists('tessera_console_require_capability')) {
	function tessera_console_require_capability(mysqli $db, string $username, string $capability): void
	{
		$rbac = __DIR__ . '/tessera_rbac.php';
		if (is_file($rbac)) {
			require_once $rbac;
		}
		if (!function_exists('tessera_can') || !tessera_can($db, $username, $capability)) {
			tessera_console_error('forbidden', 'Access denied for this role', 403);
		}
	}
}

if (!function_exists('tessera_console_pagination')) {
	/**
	 * @return array{page:int,per_page:int,offset:int}
	 */
	function tessera_console_pagination(int $defaultPerPage = 25, int $maxPerPage = 100): array
	{
		$page = max(1, (int) ($_GET['page'] ?? 1));
		$per = (int) ($_GET['per_page'] ?? $defaultPerPage);
		if ($per < 1) {
			$per = $defaultPerPage;
		}
		if ($per > $maxPerPage) {
			$per = $maxPerPage;
		}
		return [
			'page' => $page,
			'per_page' => $per,
			'offset' => ($page - 1) * $per,
		];
	}
}

if (!function_exists('tessera_console_sort')) {
	/**
	 * @param list<string> $allowed
	 * @return array{sort:string,order:string}
	 */
	function tessera_console_sort(array $allowed, string $defaultSort, string $defaultOrder = 'desc'): array
	{
		$sort = (string) ($_GET['sort'] ?? $defaultSort);
		if (!in_array($sort, $allowed, true)) {
			$sort = $defaultSort;
		}
		$order = strtolower((string) ($_GET['order'] ?? $defaultOrder));
		if ($order !== 'asc' && $order !== 'desc') {
			$order = $defaultOrder;
		}
		return ['sort' => $sort, 'order' => $order];
	}
}
