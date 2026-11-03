<?php
/**
 * Tessera tenant helpers - suspend, branding, entitlements, support sessions.
 * Registry lives in ticket DB (`tessera_tenants`).
 */
if (!function_exists('tessera_default_tenant_id')) {
	function tessera_default_tenant_id() {
		$id = getenv('TESSERA_TENANT_ID');
		return ($id !== false && $id !== '') ? (int) $id : 1;
	}
}

if (!function_exists('tessera_is_apex_host')) {
	function tessera_is_apex_host(?string $host = null): bool {
		$host = strtolower((string) ($host ?? ($_SERVER['HTTP_HOST'] ?? '')));
		$host = preg_replace('/:\\d+$/', '', $host) ?? '';
		return $host === 'tessera.co.tz' || $host === 'www.tessera.co.tz'
			|| $host === 'console.tessera.co.tz'
			|| str_ends_with($host, '.up.railway.app')
			|| $host === 'localhost' || $host === '127.0.0.1';
	}
}

if (!function_exists('tessera_resolve_tenant_slug')) {
	function tessera_resolve_tenant_slug() {
		$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
		$host = preg_replace('/:\\d+$/', '', $host) ?? '';

		/* Apex / Control / Railway preview / local -> flagship slug */
		if (tessera_is_apex_host($host)) {
			return 'sr-classic';
		}

		$reg = function_exists('tessera_registry_mysqli') ? tessera_registry_mysqli() : null;
		if ($reg && $host !== '') {
			$host_e = $reg->real_escape_string($host);
			/* Prefer exact domain match (e.g. srclassiccoach.tessera.co.tz -> sr-classic) */
			$q = @$reg->query(
				"SELECT slug FROM tessera_tenants
				 WHERE LOWER(primary_domain)='{$host_e}'
				    OR LOWER(custom_domain)='{$host_e}'
				 LIMIT 1"
			);
			if ($q && ($r = $q->fetch_assoc()) && !empty($r['slug'])) {
				return (string) $r['slug'];
			}

			/* {slug}.tessera.co.tz -> tenant with that slug */
			if (preg_match('/^([a-z0-9-]+)\\.tessera\\.co\\.tz$/', $host, $m)) {
				if ($m[1] !== 'www' && $m[1] !== 'console') {
					$cand = $reg->real_escape_string($m[1]);
					$sq = @$reg->query("SELECT slug FROM tessera_tenants WHERE slug='{$cand}' LIMIT 1");
					if ($sq && ($sr = $sq->fetch_assoc()) && !empty($sr['slug'])) {
						return (string) $sr['slug'];
					}
					/* Unknown operator subdomain - do not invent flagship */
					return (string) $m[1];
				}
			}
		} elseif (preg_match('/^([a-z0-9-]+)\\.tessera\\.co\\.tz$/', $host, $m)) {
			if ($m[1] !== 'www' && $m[1] !== 'console') {
				return (string) $m[1];
			}
		}

		$env = getenv('TESSERA_TENANT_SLUG');
		return ($env !== false && $env !== '') ? $env : 'sr-classic';
	}
}

if (!function_exists('tessera_current_tenant_id')) {
	/** Resolved tenant id for request host (defaults to flagship 1 only on apex hosts). */
	function tessera_current_tenant_id() {
		$row = tessera_tenant_row();
		if ($row && !empty($row['id'])) {
			return (int) $row['id'];
		}
		if (function_exists('tessera_is_apex_host') && tessera_is_apex_host()) {
			return tessera_default_tenant_id();
		}
		/* Unknown subdomain with no registry row - do not leak flagship data */
		return 0;
	}
}

if (!function_exists('tessera_sql_tenant_eq')) {
	/**
	 * SQL fragment for Wave H4 query scoping: "tenant_id = N" or "alias.tenant_id = N".
	 * Safe: tenant id is always an int.
	 */
	function tessera_sql_tenant_eq($alias = '') {
		$id = (int) tessera_current_tenant_id();
		$col = ($alias !== '' && $alias !== null) ? (preg_replace('/[^a-zA-Z0-9_]/', '', (string) $alias) . '.tenant_id') : 'tenant_id';
		return "{$col} = {$id}";
	}
}

if (!function_exists('tessera_registry_mysqli')) {
	/** Connection to ticket DB for tenant registry (may differ from cargo/accounts DB). */
	function tessera_registry_mysqli() {
		static $reg = null;
		static $failed = false;
		if ($failed) {
			return null;
		}
		if ($reg instanceof mysqli && !$reg->connect_error) {
			return $reg;
		}
		if (!function_exists('sr_db_config')) {
			$candidates = [
				__DIR__ . '/sr_env.php',
				dirname(__DIR__) . '/includes/sr_env.php',
				dirname(__DIR__, 2) . '/../../services/tickets/includes/sr_env.php',
			];
			foreach ($candidates as $c) {
				if (is_file($c)) {
					require_once $c;
					break;
				}
			}
		}
		if (!function_exists('sr_db_config')) {
			$failed = true;
			return null;
		}
		/* Registry is always the shared platform ticket DB - never tenant-routed. */
		$cfg = function_exists('sr_db_config_platform')
			? sr_db_config_platform(getenv('SR_DB_NAME') ?: 'ticket')
			: sr_db_config(getenv('SR_DB_NAME') ?: 'ticket', true);
		$reg = @mysqli_init();
		if (!$reg) {
			$failed = true;
			return null;
		}
		mysqli_report(MYSQLI_REPORT_OFF);
		@$reg->options(MYSQLI_OPT_CONNECT_TIMEOUT, 3);
		$ok = @$reg->real_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name'], $cfg['port']);
		if (!$ok || $reg->connect_error) {
			$reg = @mysqli_init();
			if ($reg) {
				@$reg->options(MYSQLI_OPT_CONNECT_TIMEOUT, 3);
				$ok = @$reg->real_connect($cfg['host'], 'root', $cfg['pass'], $cfg['name'], $cfg['port']);
			}
		}
		if (!$reg || $reg->connect_error) {
			$failed = true;
			return null;
		}
		$reg->set_charset('utf8mb4');
		return $reg;
	}
}

if (!function_exists('tessera_tenant_row')) {
	/**
	 * @return array<string,mixed>|null
	 */
	function tessera_tenant_row() {
		static $cached = false;
		static $row = null;
		if ($cached) {
			return $row;
		}
		$cached = true;
		$reg = tessera_registry_mysqli();
		if (!$reg) {
			return null;
		}
		$slug = tessera_resolve_tenant_slug();
		$slug_e = $reg->real_escape_string($slug);
		$q = @$reg->query("SELECT * FROM tessera_tenants WHERE slug='{$slug_e}' LIMIT 1");
		if ($q && ($r = $q->fetch_assoc())) {
			$row = [
				'id' => (int) $r['id'],
				'slug' => (string) $r['slug'],
				'status' => (string) $r['status'],
				'display_name' => (string) $r['display_name'],
				'legal_name' => (string) ($r['legal_name'] ?? $r['display_name']),
				'plan' => (string) ($r['plan'] ?? 'flagship'),
				'db_mode' => (string) ($r['db_mode'] ?? 'shared'),
				'db_ticket' => (string) ($r['db_ticket'] ?? ''),
				'db_cargo' => (string) ($r['db_cargo'] ?? ''),
				'db_account' => (string) ($r['db_account'] ?? ''),
				'primary_domain' => (string) ($r['primary_domain'] ?? ''),
				'domain_status' => (string) ($r['domain_status'] ?? 'pending'),
				'custom_domain' => (string) ($r['custom_domain'] ?? ''),
				'logo_url' => (string) ($r['logo_url'] ?? ''),
				'primary_color' => (string) ($r['primary_color'] ?? ''),
				'module_tickets' => (int) ($r['module_tickets'] ?? 1),
				'module_cargo' => (int) ($r['module_cargo'] ?? 1),
				'module_accounts' => (int) ($r['module_accounts'] ?? 1),
			];
		}
		return $row;
	}
}

if (!function_exists('tessera_tenant_db_name')) {
	/**
	 * Concrete MySQL database for the current host + module (ticket|cargo|account).
	 * Empty / shared -> caller uses platform defaults.
	 */
	function tessera_tenant_db_name($module = 'ticket') {
		$row = tessera_tenant_row();
		if (!$row) {
			return '';
		}
		$mode = strtolower((string) ($row['db_mode'] ?? 'shared'));
		if ($mode !== 'dedicated') {
			return '';
		}
		$mod = strtolower((string) $module);
		if ($mod === 'cargo') {
			return (string) ($row['db_cargo'] ?? '');
		}
		if ($mod === 'account' || $mod === 'accounts') {
			return (string) ($row['db_account'] ?? '');
		}
		return (string) ($row['db_ticket'] ?? '');
	}
}

if (!function_exists('tessera_tenant_status')) {
	function tessera_tenant_status() {
		$r = tessera_tenant_row();
		/* No registry row -> treat as active (legacy single-tenant). */
		return $r['status'] ?? 'active';
	}
}

if (!function_exists('tessera_tenant_is_suspended')) {
	function tessera_tenant_is_suspended() {
		return tessera_tenant_status() === 'suspended';
	}
}

if (!function_exists('tessera_tenant_blocks_access')) {
	/** Suspended or stuck provisioning can optionally block - Wave A enforces suspended only. */
	function tessera_tenant_blocks_access() {
		return tessera_tenant_is_suspended();
	}
}

if (!function_exists('tessera_tenant_has_module')) {
	function tessera_tenant_has_module($module) {
		$module = strtolower((string) $module);
		$r = tessera_tenant_row();
		if (!$r) {
			return true; /* legacy */
		}
		$map = [
			'tickets' => 'module_tickets',
			'cargo' => 'module_cargo',
			'accounts' => 'module_accounts',
		];
		$key = $map[$module] ?? null;
		if ($key === null) {
			return true;
		}
		return !empty($r[$key]);
	}
}

if (!function_exists('tessera_require_module')) {
	/**
	 * Block access to a module the tenant is not entitled to.
	 * @param string $module tickets|cargo|accounts
	 */
	function tessera_require_module($module) {
		if (tessera_tenant_has_module($module)) {
			return;
		}
		$ticketsHome = '/tickets/home.php';
		if (function_exists('tessera_module_url')) {
			$ticketsHome = tessera_module_url('tickets', 'home.php');
		}
		header('Location: ' . $ticketsHome . '?module_denied=' . rawurlencode($module));
		exit;
	}
}

if (!function_exists('tessera_platform_audit_write')) {
	/**
	 * Write to tessera_platform_audit from operator apps (Tickets/Cargo/Accounts).
	 */
	function tessera_platform_audit_write($action, $meta = null, $tenantId = null) {
		$reg = tessera_registry_mysqli();
		if (!$reg) {
			return;
		}
		$tq = @$reg->query("SHOW TABLES LIKE 'tessera_platform_audit'");
		if (!$tq || $tq->num_rows < 1) {
			return;
		}
		$row = tessera_tenant_row();
		if ($tenantId === null && $row) {
			$tenantId = (int) $row['id'];
		}
		$actor = 'system';
		if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['username'])) {
			$actor = (string) $_SESSION['username'];
		} elseif (!empty($meta['username'])) {
			$actor = (string) $meta['username'];
		} elseif (!empty($_POST['username'])) {
			$actor = trim((string) $_POST['username']);
		}
		$actor_e = $reg->real_escape_string(substr($actor, 0, 128));
		$action_e = $reg->real_escape_string(substr((string) $action, 0, 64));
		$tid = $tenantId !== null ? (string) (int) $tenantId : 'NULL';
		$meta_e = $meta ? "'" . $reg->real_escape_string(json_encode($meta)) . "'" : 'NULL';
		@$reg->query(
			"INSERT INTO tessera_platform_audit (actor, action, tenant_id, meta_json)
			 VALUES ('{$actor_e}', '{$action_e}', {$tid}, {$meta_e})"
		);
	}
}

if (!function_exists('tessera_audit_blocked_access')) {
	/** Log blocked staff access while tenant is suspended. */
	function tessera_audit_blocked_access($source = 'session', $username = '') {
		$module = 'tickets';
		$dir = str_replace('\\', '/', __DIR__);
		if (strpos($dir, '/services/cargo/') !== false) {
			$module = 'cargo';
		} elseif (strpos($dir, '/services/accounting/') !== false) {
			$module = 'accounts';
		}
		tessera_platform_audit_write('tenant.login_blocked', [
			'source' => $source,
			'module' => $module,
			'username' => $username !== '' ? $username : (isset($_POST['username']) ? trim((string) $_POST['username']) : ''),
			'ip' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
		]);
	}
}

if (!function_exists('tessera_payment_webhook_touch')) {
	/**
	 * Update vault webhook_last_* for the current/default tenant.
	 */
	function tessera_payment_webhook_touch($status, $ok = true) {
		$reg = tessera_registry_mysqli();
		if (!$reg) {
			return;
		}
		$tq = @$reg->query("SHOW TABLES LIKE 'tessera_tenant_payments'");
		if (!$tq || $tq->num_rows < 1) {
			return;
		}
		$row = tessera_tenant_row();
		$tid = $row ? (int) $row['id'] : 0;
		if ($tid < 1) {
			$slug = getenv('TESSERA_DEFAULT_TENANT_SLUG') ?: 'sr-classic';
			$slug_e = $reg->real_escape_string($slug);
			$sq = @$reg->query("SELECT id FROM tessera_tenants WHERE slug='{$slug_e}' LIMIT 1");
			if ($sq && ($s = $sq->fetch_assoc())) {
				$tid = (int) $s['id'];
			}
		}
		if ($tid < 1) {
			return;
		}
		$statusClean = preg_replace('/[^a-z0-9_\-]/i', '', (string) $status);
		$statusLabel = $ok ? 'ok' : substr(($statusClean !== '' ? $statusClean : 'failed'), 0, 32);
		$status_e = $reg->real_escape_string($statusLabel);
		@$reg->query(
			"UPDATE tessera_tenant_payments
			 SET webhook_last_at=NOW(), webhook_last_status='{$status_e}'
			 WHERE tenant_id={$tid} AND provider='clickpesa' LIMIT 1"
		);
	}
}

if (!function_exists('tessera_host_is_operator_subdomain')) {
	/** True for {slug}.tessera.co.tz excluding www/console. */
	function tessera_host_is_operator_subdomain(?string $host = null): bool {
		$host = strtolower((string) ($host ?? ($_SERVER['HTTP_HOST'] ?? '')));
		$host = preg_replace('/:\\d+$/', '', $host) ?? '';
		if (!preg_match('/^([a-z0-9-]+)\\.tessera\\.co\\.tz$/', $host, $m)) {
			return false;
		}
		return !in_array($m[1], ['www', 'console'], true);
	}
}

if (!function_exists('tessera_host_is_platform_marketing')) {
	/** Apex / www marketing hosts - not an operator console hostname. */
	function tessera_host_is_platform_marketing(?string $host = null): bool {
		$host = strtolower((string) ($host ?? ($_SERVER['HTTP_HOST'] ?? '')));
		$host = preg_replace('/:\\d+$/', '', $host) ?? '';
		return $host === 'tessera.co.tz' || $host === 'www.tessera.co.tz';
	}
}

if (!function_exists('tessera_operator_console_host')) {
	/**
	 * Preferred public hostname for an operator console (never apex tessera.co.tz).
	 * Prefer custom_domain, then non-apex primary_domain, then {slug}.tessera.co.tz.
	 */
	function tessera_operator_console_host(?array $tenant = null): string {
		$row = $tenant;
		if ($row === null) {
			/* Flagship row when called on apex */
			$reg = function_exists('tessera_registry_mysqli') ? tessera_registry_mysqli() : null;
			$slug = getenv('TESSERA_DEFAULT_TENANT_SLUG') ?: 'sr-classic';
			if ($reg) {
				$slug_e = $reg->real_escape_string($slug);
				$q = @$reg->query(
					"SELECT slug, primary_domain, custom_domain FROM tessera_tenants WHERE slug='{$slug_e}' LIMIT 1"
				);
				$row = ($q && ($r = $q->fetch_assoc())) ? $r : null;
			}
			if (!$row && function_exists('tessera_tenant_row')) {
				$row = tessera_tenant_row();
			}
		}
		$candidates = [];
		if (is_array($row)) {
			foreach (['custom_domain', 'primary_domain'] as $k) {
				$h = strtolower(trim((string) ($row[$k] ?? '')));
				$h = preg_replace('/:\\d+$/', '', $h) ?? '';
				if ($h !== '' && $h !== 'tessera.co.tz' && $h !== 'www.tessera.co.tz' && $h !== 'console.tessera.co.tz') {
					$candidates[] = $h;
				}
			}
			$slug = strtolower(trim((string) ($row['slug'] ?? '')));
			if ($slug !== '' && $slug !== 'www' && $slug !== 'console') {
				$candidates[] = $slug . '.tessera.co.tz';
			}
		}
		$env = getenv('TESSERA_FLAGSHIP_HOST');
		if ($env !== false && $env !== '') {
			array_unshift($candidates, strtolower(preg_replace('/:\\d+$/', '', $env) ?? $env));
		}
		foreach ($candidates as $h) {
			if ($h !== '' && preg_match('/^[a-z0-9.-]+$/', $h)) {
				return $h;
			}
		}
		return 'srclassiccoach.tessera.co.tz';
	}
}

if (!function_exists('tessera_operator_console_url')) {
	/** Absolute https URL on the operator console host. */
	function tessera_operator_console_url(string $path = '/login/', ?array $tenant = null): string {
		$host = tessera_operator_console_host($tenant);
		if ($path === '' || $path[0] !== '/') {
			$path = '/' . ltrim($path, '/');
		}
		return 'https://' . $host . $path;
	}
}

if (!function_exists('tessera_redirect_platform_login_to_operator')) {
	/**
	 * Apex tessera.co.tz must not host operator login - send staff to the flagship console host.
	 * Returns true if a redirect was sent.
	 */
	function tessera_redirect_platform_login_to_operator(?string $next = null): bool {
		if (!function_exists('tessera_host_is_platform_marketing') || !tessera_host_is_platform_marketing()) {
			return false;
		}
		$next = trim((string) $next);
		$path = '/login/';
		if ($next !== '' && str_starts_with($next, '/') && !str_contains($next, '//') && !str_contains($next, '..')) {
			$path .= '?next=' . rawurlencode($next);
		}
		header('Location: ' . tessera_operator_console_url($path), true, 302);
		return true;
	}
}

if (!function_exists('tessera_tenant_is_registered')) {
	function tessera_tenant_is_registered(): bool {
		$row = tessera_tenant_row();
		return is_array($row) && !empty($row['id']);
	}
}

if (!function_exists('tessera_redirect')) {
	/** Redirect even when the page already started HTML output (legacy Dashonic pages). */
	function tessera_redirect(string $url): void {
		if (!headers_sent()) {
			header('Location: ' . $url);
			exit;
		}
		$js = json_encode($url, JSON_UNESCAPED_SLASHES);
		$html = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
		echo '<!doctype html><html><head><meta charset="utf-8">';
		echo '<meta http-equiv="refresh" content="0;url=' . $html . '">';
		echo '<title>Redirecting...</title></head><body>';
		echo '<script>location.replace(' . $js . ');</script>';
		echo '<p class="p-3">Redirecting... <a href="' . $html . '">Continue</a></p>';
		echo '</body></html>';
		exit;
	}
}

if (!function_exists('tessera_require_staff_login')) {
	/** Call before or from top_header - sends unauthenticated users to login. */
	function tessera_require_staff_login(string $loginUrl = 'index.php'): void {
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}
		if (!empty($_SESSION['username'])) {
			return;
		}
		tessera_redirect($loginUrl);
	}
}

if (!function_exists('tessera_abort_unknown_tenant')) {
	/**
	 * Unknown operator subdomain (wildcard catch-all) -> clear 404, not a blank console.
	 */
	function tessera_abort_unknown_tenant(): void {
		if (!tessera_host_is_operator_subdomain()) {
			return;
		}
		if (tessera_tenant_is_registered()) {
			return;
		}

		$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
		$host = preg_replace('/:\\d+$/', '', $host) ?? $host;
		$uri = (string) ($_SERVER['REQUEST_URI'] ?? '');
		$wantsJson = str_contains($uri, '/api/')
			|| (isset($_SERVER['HTTP_ACCEPT']) && str_contains((string) $_SERVER['HTTP_ACCEPT'], 'application/json'));

		http_response_code(404);
		if ($wantsJson) {
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode([
				'ok' => false,
				'success' => false,
				'error' => 'tenant_not_found',
				'message' => 'No Tessera operator is registered for this domain.',
				'host' => $host,
			]);
			exit;
		}

		header('Content-Type: text/html; charset=utf-8');
		$h = htmlspecialchars($host, ENT_QUOTES, 'UTF-8');
		$home = 'https://tessera.co.tz/';
		echo '<!doctype html><html lang="en"><head><meta charset="utf-8">';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
		echo '<title>Operator not found | Tessera</title>';
		echo '<link href="/tickets/assets/css/bootstrap.min.css" rel="stylesheet">';
		echo '<link href="/tickets/assets/css/icons.min.css" rel="stylesheet">';
		echo '<link href="/tickets/assets/css/app.min.css" rel="stylesheet">';
		echo '</head><body>';
		echo '<div class="authentication-bg min-vh-100"><div class="bg-overlay"></div>';
		echo '<div class="container"><div class="d-flex flex-column min-vh-100 px-3 pt-4">';
		echo '<div class="row justify-content-center my-auto"><div class="col-md-8 col-lg-6 col-xl-5">';
		echo '<div class="card"><div class="card-body p-4 text-center">';
		echo '<div class="avatar-md mx-auto mb-3"><div class="avatar-title bg-soft-warning text-warning rounded-circle font-size-24">';
		echo '<i class="uil uil-exclamation-triangle"></i></div></div>';
		echo '<h4 class="mb-2">No operator at this address</h4>';
		echo '<p class="text-muted mb-3">There is no Tessera workspace for<br><strong>' . $h . '</strong></p>';
		echo '<p class="text-muted font-size-13 mb-4">Check the spelling (for example <code>srclassiccoach.tessera.co.tz</code>), or open the main site.</p>';
		echo '<a class="btn btn-primary w-100" href="' . htmlspecialchars($home, ENT_QUOTES, 'UTF-8') . '">Go to tessera.co.tz</a>';
		echo '</div></div></div></div>';
		echo '<div class="row"><div class="col-lg-12"><div class="text-center text-muted mb-4">Tessera</div></div></div>';
		echo '</div></div></div></body></html>';
		exit;
	}
}

if (!function_exists('tessera_deny_if_tenant_suspended')) {
	/**
	 * Call from login AJAX (echo plain) or page bootstrap (redirect).
	 * @param string $mode 'ajax' | 'redirect'
	 */
	function tessera_deny_if_tenant_suspended($mode = 'redirect') {
		if (!tessera_tenant_blocks_access()) {
			return;
		}
		tessera_audit_blocked_access($mode === 'ajax' ? 'login' : 'session');
		if ($mode === 'ajax') {
			header('Content-Type: text/plain; charset=utf-8');
			echo 'suspended';
			exit;
		}
		if (session_status() === PHP_SESSION_ACTIVE) {
			$_SESSION = [];
			session_destroy();
		}
		$login = 'index.php';
		tessera_redirect($login . '?suspended=1');
	}
}

if (!function_exists('tessera_support_session_active')) {
	function tessera_support_session_active() {
		if (empty($_SESSION['tessera_support'])) {
			return false;
		}
		$exp = (int) ($_SESSION['tessera_support_expires'] ?? 0);
		if ($exp > 0 && time() > $exp) {
			unset(
				$_SESSION['tessera_support'],
				$_SESSION['tessera_support_actor'],
				$_SESSION['tessera_support_expires'],
				$_SESSION['tessera_support_tenant_id']
			);
			return false;
		}
		return true;
	}
}

if (!function_exists('tessera_support_banner_html')) {
	function tessera_support_banner_html() {
		if (!tessera_support_session_active()) {
			return '';
		}
		$actor = htmlspecialchars((string) ($_SESSION['tessera_support_actor'] ?? 'platform'), ENT_QUOTES, 'UTF-8');
		$exp = (int) ($_SESSION['tessera_support_expires'] ?? 0);
		$until = $exp ? date('H:i', $exp) : 'soon';
		$endHref = 'support_end.php';
		$dir = str_replace('\\', '/', __DIR__);
		if (strpos($dir, '/services/cargo/') !== false || strpos($dir, '/services/accounting/') !== false) {
			$endHref = '/tickets/support_end.php';
		}
		$endHref = htmlspecialchars($endHref, ENT_QUOTES, 'UTF-8');
		return '<div class="tessera-support-banner" role="status">'
			. '<strong>Support session</strong> - viewing as operator staff (actor: ' . $actor . '). Ends ~' . htmlspecialchars($until, ENT_QUOTES, 'UTF-8') . '.'
			. ' <a href="' . $endHref . '">End session</a>'
			. '</div>';
	}
}

if (!function_exists('tessera_brand_markup')) {
	/** Operator white-label mark for staff chrome. */
	function tessera_brand_markup($homeHref = 'home.php') {
		$t = tessera_tenant_row();
		$name = $t['display_name'] ?? 'Tessera';
		$logo = $t['logo_url'] ?? '';
		$color = $t['primary_color'] ?? '';
		$style = $color !== '' ? ' style="color:' . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . '"' : '';
		$homeHref = htmlspecialchars($homeHref, ENT_QUOTES, 'UTF-8');
		$html = '<a href="' . $homeHref . '" class="logo logo-light">';
		if ($logo !== '') {
			$html .= '<span class="tessera-brand tessera-brand--logo">'
				. '<img src="' . htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" height="28">'
				. '</span>';
		} else {
			$html .= '<span class="tessera-brand"' . $style . '>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</span>';
		}
		$html .= '</a>';
		return $html;
	}
}

if (!function_exists('tessera_brand_css_vars')) {
	function tessera_brand_css_vars() {
		$t = tessera_tenant_row();
		$color = $t['primary_color'] ?? '';
		if ($color === '' || !preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
			return '';
		}
		$c = htmlspecialchars($color, ENT_QUOTES, 'UTF-8');
		return '<style id="tessera-tenant-brand">:root{--tessera-tenant-accent:' . $c . ';}'
			. '.sr-module-link.is-active,.sr-module-link.active{color:#fff!important;}'
			. '.sr-module-link.is-active::after,.sr-module-link.active::after{background:' . $c . '!important;}'
			. '.tessera-support-banner{background:' . $c . ';}</style>';
	}
}

if (!function_exists('tessera_detect_module_label')) {
	/** Infer module name from the current request path. */
	function tessera_detect_module_label(): string {
		$script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? ''));
		if (strpos($script, '/cargo/') !== false) {
			return 'Cargo';
		}
		if (strpos($script, '/accounts/') !== false) {
			return 'Accounts';
		}
		if (strpos($script, '/control/') !== false) {
			return 'Control';
		}
		if (preg_match('/settings(?:_|\.)/', $script)) {
			return 'Settings';
		}
		return 'Tickets';
	}
}

if (!function_exists('tessera_operator_display_name')) {
	function tessera_operator_display_name(): string {
		if (!function_exists('tessera_tenant_row')) {
			return 'Tessera';
		}
		$t = tessera_tenant_row();
		$name = trim((string) ($t['display_name'] ?? ''));
		return $name !== '' ? $name : 'Tessera';
	}
}

if (!function_exists('tessera_document_title')) {
	/**
	 * Professional browser tab title, e.g.
	 * "Dashboard - Tickets - SR Classic Coach | Tessera"
	 * Uses ASCII separators so tab titles never show tofu boxes in limited fonts.
	 */
	function tessera_document_title(string $pageLabel, ?string $module = null, ?bool $includeOperator = true): string {
		$pageLabel = trim($pageLabel);
		if ($pageLabel === '') {
			$pageLabel = 'Tessera';
		}

		$module = $module ?? tessera_detect_module_label();

		if ($module === 'Control') {
			return $pageLabel . ' - Tessera Control';
		}

		$segments = [$pageLabel, $module];
		if ($includeOperator) {
			$operator = tessera_operator_display_name();
			if ($operator !== '' && strcasecmp($operator, 'Tessera') !== 0) {
				$segments[] = $operator;
			}
		}

		return implode(' - ', $segments) . ' | Tessera';
	}
}

if (!function_exists('tessera_favicon_link_tags')) {
	function tessera_favicon_link_tags(string $assetBase = '/tickets/assets/images'): string {
		$base = rtrim($assetBase, '/');
		$ver = '20260906';
		$svg = htmlspecialchars($base . '/favicon.svg?v=' . rawurlencode($ver), ENT_QUOTES, 'UTF-8');
		$ico = htmlspecialchars($base . '/favicon.ico?v=' . rawurlencode($ver), ENT_QUOTES, 'UTF-8');
		return '<link rel="icon" href="' . $svg . '" type="image/svg+xml">' . "\n"
			. '<link rel="alternate icon" href="' . $ico . '">' . "\n"
			. '<link rel="apple-touch-icon" href="' . $svg . '">' . "\n"
			. '<meta name="theme-color" content="#03314d">';
	}
}
