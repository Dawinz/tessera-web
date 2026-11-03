<?php
/**
 * Platform operator SPA - capture legacy module pages as JSON fragments.
 */
if (!function_exists('tessera_operator_spa_enabled')) {
	function tessera_operator_spa_enabled(): bool {
		$flag = getenv('TESSERA_OPERATOR_SHELL');
		if ($flag === '0' || $flag === 'false') {
			return false;
		}
		return true;
	}

	/** Map legacy redirector / alias entry pages to real shell pages. */
	function tessera_operator_spa_resolve_page(string $module, string $page): string {
		$page = basename(parse_url($page, PHP_URL_PATH) ?: $page);
		$module = strtolower($module);
		$aliases = [
			'tickets' => [
				'routes.php' => 'schedule.php',
				'routs.php' => 'schedule.php',
			],
		];
		return $aliases[$module][strtolower($page)] ?? $page;
	}

	function tessera_operator_shell_url(string $module, string $page = ''): string {
		$defaults = [
			'tickets' => 'home.php',
			'cargo' => 'home.php',
			'accounts' => 'manifest.php',
		];
		$module = strtolower($module);
		if ($page === '' || $page === '/') {
			$page = $defaults[$module] ?? 'home.php';
		}
		$page = tessera_operator_spa_resolve_page($module, $page);
		$base = function_exists('sr_public_base_url') ? sr_public_base_url() : '';
		return rtrim($base, '/') . '/operator/app.php?module=' . rawurlencode($module) . '&page=' . rawurlencode($page);
	}

	/** Redirect GET requests for full legacy pages into the operator shell. */
	function tessera_operator_shell_redirect_legacy_get(): void {
		if (!tessera_operator_spa_enabled()) {
			return;
		}
		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
			return;
		}
		if (!empty($_GET['full']) && (string) $_GET['full'] === '1') {
			return;
		}
		if (!empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed'])) {
			return;
		}
		if (!empty($_SERVER['HTTP_X_TESSERA_OPERATOR_SPA']) || !empty($_SERVER['HTTP_X_TESSERA_ACCOUNTS_SPA'])) {
			return;
		}
		// AJAX / fragment loads (route manager views, DataTables, etc.) must not enter the shell.
		$xhr = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
		if ($xhr === 'xmlhttprequest') {
			return;
		}
		$secDest = strtolower((string) ($_SERVER['HTTP_SEC_FETCH_DEST'] ?? ''));
		if ($secDest !== '' && $secDest !== 'document') {
			return;
		}
		$script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
		if (str_contains($script, '/operator/')) {
			return;
		}
		// Only top-level module entry pages - never nested AJAX views under /modules/.
		// Accounts is rewritten to /accounts/template/foo.php (see services/accounting/.htaccess).
		$isTopLevel = (bool) preg_match('#/(tickets|cargo)/[^/]+\.php$#i', $script)
			|| (bool) preg_match('#/accounts/(?:template/)?[^/]+\.php$#i', $script);
		if (!$isTopLevel) {
			return;
		}
		$base = basename($script);
		$baseLower = strtolower($base);
		$skip = [
			'index.php', 'spa.php', 'app.php', 'log_out.php', 'auth.php',
		];
		if (in_array($baseLower, $skip, true)) {
			return;
		}
		if (str_starts_with($baseLower, 'fetch_') || str_starts_with($baseLower, 'save_')
			|| str_starts_with($baseLower, 'update_') || str_starts_with($baseLower, 'delete_')
			|| str_starts_with($baseLower, 'insert_') || str_starts_with($baseLower, 'load_')) {
			return;
		}
		$module = tessera_operator_spa_module_from_script($script);
		if ($module === null) {
			return;
		}
		$page = tessera_operator_spa_resolve_page($module, $base);
		$query = $_GET;
		unset($query['full']);
		$url = tessera_operator_shell_url($module, $page);
		if ($query !== []) {
			$url .= '&' . http_build_query($query);
		}
		header('Location: ' . $url, true, 302);
		exit;
	}

	function tessera_operator_spa_module_from_script(string $script): ?string {
		$script = str_replace('\\', '/', $script);
		if (str_contains($script, '/tickets/')) {
			return 'tickets';
		}
		if (str_contains($script, '/cargo/')) {
			return 'cargo';
		}
		if (str_contains($script, '/accounts/')) {
			return 'accounts';
		}
		return null;
	}

	/** @return array<string,array{root:string,bootstrap?:callable():void}> */
	function tessera_operator_spa_modules(): array {
		$ticketsRoot = realpath(dirname(__DIR__));
		$cargoRoot = realpath(dirname(__DIR__, 2) . '/cargo');
		$accountsRoot = realpath(dirname(__DIR__, 2) . '/accounting/template');
		$out = [];
		if ($ticketsRoot) {
			$out['tickets'] = ['root' => $ticketsRoot];
		}
		if ($cargoRoot) {
			$out['cargo'] = ['root' => $cargoRoot];
		}
		if ($accountsRoot) {
			$out['accounts'] = [
				'root' => $accountsRoot,
				'bootstrap' => static function (): void {
					global $db, $username, $level, $firstname, $surname, $image, $footer, $fullname, $fullname_user, $url_time, $system_user, $firstStringCharacter, $shell_v;
					if (!isset($db) || !($db instanceof mysqli)) {
						require_once dirname(__DIR__, 2) . '/accounting/template/includes/auth_bootstrap.php';
					}
				},
			];
		}
		return $out;
	}

	function tessera_operator_spa_default_page(string $module): string {
		$defaults = [
			'tickets' => 'home.php',
			'cargo' => 'home.php',
			'accounts' => 'manifest.php',
		];
		return $defaults[strtolower($module)] ?? 'home.php';
	}

	function tessera_operator_spa_allowed(string $module, string $page): bool {
		$module = strtolower($module);
		$page = tessera_operator_spa_resolve_page($module, $page);
		if (!preg_match('/^[A-Za-z0-9_.-]+\.php$/', $page)) {
			return false;
		}
		$mods = tessera_operator_spa_modules();
		if (!isset($mods[$module])) {
			return false;
		}
		$root = $mods[$module]['root'];
		$file = realpath($root . DIRECTORY_SEPARATOR . $page);
		return $file !== false && str_starts_with($file, $root . DIRECTORY_SEPARATOR) && is_file($file);
	}

	function tessera_operator_spa_is_request(): bool {
		if (!empty($_SERVER['HTTP_X_TESSERA_OPERATOR_SPA'])) {
			return true;
		}
		return isset($_GET['spa']) && (string) $_GET['spa'] === '1';
	}

	/**
	 * @param array<string,string|int> $spaQuery
	 * @return array{module:string,path:string,title:string,html:string,styles:string,topnav_html:string,scripts:array<int,array{src?:string,inline?:string}>,main_page:string}
	 */
	function tessera_operator_spa_capture(string $module, string $page, array $spaQuery = []): array {
		$module = strtolower($module);
		$page = tessera_operator_spa_resolve_page($module, $page);
		if (!tessera_operator_spa_allowed($module, $page)) {
			throw new InvalidArgumentException('Page not allowed for SPA load.');
		}
		$mods = tessera_operator_spa_modules();
		$root = $mods[$module]['root'];
		$pageFile = $page;

		$backupGet = $_GET;
		foreach ($spaQuery as $k => $v) {
			if (is_string($k) && (is_string($v) || is_numeric($v))) {
				$_GET[$k] = (string) $v;
			}
		}

		$GLOBALS['tessera_operator_spa_embed'] = true;
		$GLOBALS['tessera_accounts_spa_embed'] = true;

		if (isset($mods[$module]['bootstrap']) && is_callable($mods[$module]['bootstrap'])) {
			($mods[$module]['bootstrap'])();
		}

		$cwd = getcwd();
		chdir($root);
		ob_start();
		try {
			include $root . DIRECTORY_SEPARATOR . $pageFile;
		} finally {
			if ($cwd !== false) {
				chdir($cwd);
			}
		}
		$html = (string) ob_get_clean();
		$html = tessera_operator_spa_relocate_trailing_scripts($html);

		$_GET = $backupGet;
		unset($GLOBALS['tessera_operator_spa_embed'], $GLOBALS['tessera_accounts_spa_embed']);

		$parsed = tessera_operator_spa_parse_html($html, $module, $pageFile, $spaQuery);
		$parsed['module'] = $module;
		return $parsed;
	}

	/** @param array<string,string> $query */
	function tessera_operator_spa_parse_html(string $html, string $module, string $path, array $query = []): array {
		// Extract scripts from raw HTML first - libxml corrupts inline JS that embeds HTML strings.
		$scripts = tessera_operator_spa_extract_scripts_from_html($html, $module);

		$dom = new DOMDocument();
		$prev = libxml_use_internal_errors(true);
		$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOWARNING | LIBXML_NOERROR);
		libxml_clear_errors();
		libxml_use_internal_errors($prev);

		$title = ucfirst($module);
		$titles = $dom->getElementsByTagName('title');
		if ($titles->length > 0) {
			$title = trim($titles->item(0)->textContent ?? $title);
		}

		$styles = '';
		$head = $dom->getElementsByTagName('head')->item(0);
		if ($head) {
			foreach ($head->childNodes as $node) {
				if ($node->nodeName === 'style') {
					$styles .= $dom->saveHTML($node);
				} elseif ($node->nodeName === 'link') {
					/** @var DOMElement $node */
					$rel = strtolower($node->getAttribute('rel') ?: '');
					$href = $node->getAttribute('href') ?: '';
					if ($rel === 'stylesheet' && $href !== '') {
						$node->setAttribute('href', tessera_operator_spa_normalize_asset_url($href, $module));
						if (tessera_operator_spa_stylesheet_allowed($module, $href)) {
							$styles .= $dom->saveHTML($node);
						}
					}
				}
			}
		}

		$topnavHtml = '';
		$topnav = $dom->getElementById('topnav-menu-content');
		if ($topnav instanceof DOMElement) {
			$topnavHtml = tessera_operator_spa_inner_html($dom, $topnav);
		}

		$body = $dom->getElementsByTagName('body')->item(0);
		$fragment = '';
		$ignoredScripts = [];
		if ($body) {
			foreach ($body->childNodes as $node) {
				if ($node->nodeType !== XML_ELEMENT_NODE) {
					continue;
				}
				/** @var DOMElement $node */
				if ($node->nodeName === 'header' && $node->getAttribute('id') === 'page-topbar') {
					continue;
				}
				if ($node->nodeName === 'script') {
					continue;
				}
				if ($node->nodeName === 'div' && str_contains($node->getAttribute('class') ?: '', 'main-content')) {
					$fragment .= tessera_operator_spa_inner_html($dom, $node, $ignoredScripts, $module);
					continue;
				}
				if ($node->nodeName === 'div' && $node->getAttribute('id') === 'layout-wrapper') {
					$fragment .= tessera_operator_spa_extract_layout_wrapper($dom, $node, $ignoredScripts, $module);
					continue;
				}
				$fragment .= $dom->saveHTML($node);
			}
		}

		$mainPage = isset($query['main_page']) ? (string) $query['main_page'] : '';
		if ($mainPage === '') {
			$mainPage = tessera_operator_spa_guess_main_page($module, $path);
		}

		$fragment = tessera_operator_spa_rewrite_relative_hrefs($fragment, $module);
		$topnavHtml = tessera_operator_spa_rewrite_relative_hrefs($topnavHtml, $module);
		$styles = tessera_operator_spa_fix_protocol_relative_assets($styles);
		$fragment = tessera_operator_spa_fix_protocol_relative_assets($fragment);
		$topnavHtml = tessera_operator_spa_fix_protocol_relative_assets($topnavHtml);
		$fragment = tessera_operator_spa_strip_script_tags($fragment);
		// Page CSS is written as `body.manifest-page`. The shell keeps its own body,
		// so copy that class onto a wrapper or the dark-theme rules never match.
		if ($body instanceof DOMElement && $fragment !== '') {
			$pageClass = trim($body->getAttribute('class') ?: '');
			if ($pageClass !== '') {
				$fragment = '<div class="' . htmlspecialchars($pageClass, ENT_QUOTES, 'UTF-8') . '">' . $fragment . '</div>';
			}
		}

		return [
			'path' => $path,
			'title' => $title,
			'html' => $fragment,
			'styles' => $styles,
			'topnav_html' => $topnavHtml,
			'scripts' => tessera_operator_spa_unique_scripts($scripts),
			'main_page' => $mainPage,
		];
	}

	/**
	 * Regex-extract body scripts in document order (libxml-safe for HTML-in-JS strings).
	 *
	 * @return array<int,array{src?:string,inline?:string}>
	 */
	function tessera_operator_spa_extract_scripts_from_html(string $html, string $module): array {
		$scripts = [];
		$bodyHtml = $html;
		if (preg_match('/<body\b[^>]*>([\s\S]*)<\/body>/i', $html, $m)) {
			$bodyHtml = $m[1];
		}
		if (!preg_match_all('/<script\b([^>]*)>([\s\S]*?)<\/script>/i', $bodyHtml, $matches, PREG_SET_ORDER)) {
			return $scripts;
		}
		foreach ($matches as $match) {
			$attrs = $match[1] ?? '';
			$src = '';
			if (preg_match('/\bsrc\s*=\s*([\'"])(.*?)\1/i', $attrs, $sm)) {
				$src = html_entity_decode($sm[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
			}
			if ($src !== '') {
				$src = tessera_operator_spa_normalize_script_src($src, $module);
				if (tessera_operator_spa_is_core_script($src)) {
					continue;
				}
				$scripts[] = ['src' => $src];
				continue;
			}
			$inline = trim($match[2] ?? '');
			if ($inline === '' || tessera_operator_spa_skip_inline_script($inline)) {
				continue;
			}
			$scripts[] = ['inline' => $inline];
		}
		return $scripts;
	}

	function tessera_operator_spa_skip_inline_script(string $inline): bool {
		if (str_contains($inline, 'srSyncChromeHeight') || str_contains($inline, 'TesseraTourConfig')
			|| str_contains($inline, "classList.add('sr-platform-body')")) {
			return true;
		}
		// document.write after DOM parse wipes the operator shell document.
		if (preg_match('/\bdocument\.write\s*\(/i', $inline)) {
			return true;
		}
		// Regex extraction can yield truncated attribute debris (e.g. `">`) - never inject it.
		if (strlen($inline) < 12 || !preg_match('/[a-zA-Z_$]/', $inline)) {
			return true;
		}
		if (preg_match('/^["\'>\s]+$/', $inline)) {
			return true;
		}
		return false;
	}

	function tessera_operator_spa_rewrite_relative_hrefs(string $html, string $module): string {
		if ($html === '') {
			return $html;
		}
		$prefix = rtrim(tessera_operator_spa_public_prefix($module), '/');
		return (string) preg_replace_callback(
			'/(\s(?:href|action|src)=["\'])((?!https?:|\/|#|mailto:|javascript:|data:)[^"\']*)/i',
			static function (array $m) use ($prefix): string {
				return $m[1] . $prefix . '/' . ltrim($m[2], '/');
			},
			$html
		);
	}

	function tessera_operator_spa_extract_layout_wrapper(DOMDocument $dom, DOMElement $wrapper, array &$scripts, string $module): string {
		$html = '';
		foreach ($wrapper->childNodes as $node) {
			if ($node->nodeType !== XML_ELEMENT_NODE) {
				continue;
			}
			/** @var DOMElement $node */
			if ($node->nodeName === 'header' && $node->getAttribute('id') === 'page-topbar') {
				continue;
			}
			if ($node->nodeName === 'script') {
				tessera_operator_spa_collect_script($node, $scripts, $module);
				continue;
			}
			if ($node->nodeName === 'div' && str_contains($node->getAttribute('class') ?: '', 'main-content')) {
				$html .= tessera_operator_spa_inner_html($dom, $node, $scripts, $module);
				continue;
			}
			$html .= $dom->saveHTML($node);
		}
		return $html;
	}

	function tessera_operator_spa_public_prefix(string $module): string {
		return match (strtolower($module)) {
			'accounts' => '/accounts/',
			'cargo' => '/cargo/',
			default => '/tickets/',
		};
	}

	function tessera_operator_spa_relocate_trailing_scripts(string $html): string {
		if (!preg_match('/<\/html>\s*(.+)$/is', $html, $m)) {
			return $html;
		}
		$tail = trim($m[1]);
		if ($tail === '' || !str_contains($tail, '<script')) {
			return $html;
		}
		$head = substr($html, 0, strlen($html) - strlen($m[1]));
		$head = preg_replace('/<\/body>\s*<\/html>\s*$/is', '', (string) $head) ?? $head;
		return rtrim($head) . "\n" . $tail . "\n</body></html>";
	}

	function tessera_operator_spa_normalize_asset_url(string $url, string $module): string {
		$url = trim($url);
		if ($url === '') {
			return $url;
		}
		if (str_starts_with($url, '//')) {
			return 'https:' . $url;
		}
		if (preg_match('#^https?://#i', $url) || str_starts_with($url, '/')) {
			return $url;
		}
		return tessera_operator_spa_public_prefix($module) . ltrim($url, '/');
	}

	function tessera_operator_spa_normalize_script_src(string $src, string $module): string {
		return tessera_operator_spa_normalize_asset_url($src, $module);
	}

	function tessera_operator_spa_strip_script_tags(string $html): string {
		if ($html === '') {
			return $html;
		}
		return (string) preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $html);
	}

	function tessera_operator_spa_fix_protocol_relative_assets(string $html): string {
		if ($html === '') {
			return $html;
		}
		return (string) preg_replace(
			'/(\s(?:href|src)=["\'])\/\/([^"\']+)/i',
			'$1https://$2',
			$html
		);
	}

	function tessera_operator_spa_stylesheet_allowed(string $module, string $href): bool {
		// Shell head already loads Dashonic core - skip duplicates from legacy page <head>.
		$coreSkip = [
			'bootstrap.min.css',
			'icons.min.css',
			'app.min.css',
			'sr-platform-shell.css',
			'tessera-operator-shell.css',
			'tessera-accounts-layout.css',
			'sweetalert2.min.css',
			'flatpickr.min.css',
			'jsvectormap.min.css',
		];
		foreach ($coreSkip as $skip) {
			if (str_contains($href, $skip)) {
				return false;
			}
		}
		$needles = [
			'/tickets/',
			'/cargo/',
			'/accounts/',
			'tessera-reports',
			'tessera-accounts',
			'home-dashboard',
			'booking-dark',
			'schedule.css',
			'datatable',
			'select_css',
			'buttons.css',
			'style.css',
			'gridjs',
			'alertify',
			'fullcalendar',
			'sr-platform',
		];
		foreach ($needles as $n) {
			if (str_contains($href, $n)) {
				return true;
			}
		}
		return str_starts_with($href, 'css/') || str_starts_with($href, 'assets/css/')
			|| str_starts_with($href, 'assets/libs/');
	}

	function tessera_operator_spa_inner_html(DOMDocument $dom, DOMElement $el, array &$scripts = [], string $module = 'tickets'): string {
		$html = '';
		foreach ($el->childNodes as $child) {
			if ($child->nodeType === XML_ELEMENT_NODE && strtolower($child->nodeName) === 'script') {
				tessera_operator_spa_collect_script($child, $scripts, $module);
				continue;
			}
			$html .= $dom->saveHTML($child);
		}
		return $html;
	}

	/** @param array<int,array{src?:string,inline?:string}> $scripts */
	function tessera_operator_spa_collect_script(DOMElement $script, array &$scripts, string $module = 'tickets'): void {
		$src = trim($script->getAttribute('src') ?: '');
		if ($src !== '') {
			$src = tessera_operator_spa_normalize_script_src($src, $module);
			if (tessera_operator_spa_is_core_script($src)) {
				return;
			}
			$scripts[] = ['src' => $src];
			return;
		}
		$inline = trim($script->textContent ?? '');
		if ($inline === '' || tessera_operator_spa_skip_inline_script($inline)) {
			return;
		}
		$scripts[] = ['inline' => $inline];
	}

	/** Collect every nested <script> under $root (depth-first document order). */
	function tessera_operator_spa_collect_scripts_under(DOMNode $root, array &$scripts, string $module = 'tickets'): void {
		if ($root instanceof DOMElement && strtolower($root->nodeName) === 'script') {
			tessera_operator_spa_collect_script($root, $scripts, $module);
			return;
		}
		if (!$root->hasChildNodes()) {
			return;
		}
		foreach ($root->childNodes as $child) {
			if ($child->nodeType !== XML_ELEMENT_NODE) {
				continue;
			}
			tessera_operator_spa_collect_scripts_under($child, $scripts, $module);
		}
	}

	function tessera_operator_spa_is_core_script(string $src): bool {
		$core = [
			'/tickets/assets/libs/bootstrap',
			'assets/libs/bootstrap',
			'bootstrap.bundle.min.js',
			'/tickets/assets/js/app.js',
			'/cargo/assets/js/app.js',
			'assets/js/app.js',
			'/tickets/assets/libs/jquery/',
			'assets/libs/jquery/',
			'js/vendor/jquery',
			'jquery-1.12.4',
			'jquery-1.12',
			'/tickets/assets/libs/metismenu',
			'assets/libs/metismenu',
			'/tickets/assets/libs/simplebar',
			'assets/libs/simplebar',
			'/tickets/assets/libs/feather',
			'assets/libs/feather',
			'/tickets/assets/libs/sweetalert',
			'assets/libs/sweetalert',
			'/tickets/assets/libs/flatpickr',
			'assets/libs/flatpickr',
			'/tickets/assets/js/bootstrap-jquery-bridge',
			'assets/js/bootstrap-jquery-bridge',
			'vendors/select2/',
			'/tickets/vendors/select2/',
			'cdn.datatables.net',
			'cdn.jsdelivr.net/npm/select2',
			'/accounts/dropzone/',
			'tessera-operator-shell.js',
			'tessera-accounts-spa.js',
			'assets/js/pages/calendar.init.js',
			'assets/js/pages/form-advanced.init.js',
			/* fullcalendar must load with schedule.php (and similar) - do not treat as shell core */
			'assets/libs/choices.js',
			'assets/libs/@simonwep/pickr',
			'assets/libs/leaflet',
			'assets/libs/apexcharts',
			'/tickets/assets/libs/apexcharts',
			'/cargo/assets/libs/apexcharts',
			/* Operator shell already ships DataTables + Buttons via dashonic; skip legacy local stack. */
			'datatable/datatable.js',
			'datatable/button.js',
			'datatable/jsq.js',
			'datatable/pdf_maker',
			'datatable/html.js',
			'datatable/print.js',
		];
		foreach ($core as $needle) {
			if (str_contains($src, $needle)) {
				return true;
			}
		}
		return false;
	}

	/** @param array<int,array{src?:string,inline?:string}> $scripts */
	function tessera_operator_spa_unique_scripts(array $scripts): array {
		$seen = [];
		$out = [];
		foreach ($scripts as $s) {
			$key = isset($s['src']) ? 's:' . $s['src'] : 'i:' . md5($s['inline'] ?? '');
			if (isset($seen[$key])) {
				continue;
			}
			$seen[$key] = true;
			$out[] = $s;
		}
		return $out;
	}

	function tessera_operator_spa_guess_main_page(string $module, string $path): string {
		$maps = [
			'accounts' => [
				'manifest.php' => 'Manifests',
				'daily.php' => 'Cash Book',
				'withdraw.php' => 'Cash Book',
				'jobcard.php' => 'Purchases',
				'report_profit_loss.php' => 'Financial Reports',
				'report_trips.php' => 'Fleet Reports',
				'currency.php' => 'Currency',
				'Register_routs.php' => 'Setup',
				'Register_routes.php' => 'Setup',
			],
			'cargo' => [
				'home.php' => 'Home',
				'vehicle_view.php' => 'Manifest',
				'deleted.php' => 'Deletes',
				'luggage_check_out.php' => 'Luggage check out',
			],
			'tickets' => [
				'home.php' => 'Home',
				'tickets.php' => 'Book list',
				'schedule.php' => 'Schedule',
				'manifest.php' => 'Manifest',
				'vehicle_manager.php' => 'Vehicle manager',
				'route_manager.php' => 'Route manager',
			],
		];
		return $maps[$module][$path] ?? '';
	}
}
