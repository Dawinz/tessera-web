<?php
/**
 * Dashonic head — Vertical Dark (Tessera Control).
 * Assets from Tickets; no operator horizontal shell CSS.
 */
if (!function_exists('tessera_document_title')) {
	$tesseraTenantCandidates = [
		'/var/www/html/tickets/includes/tessera_tenant.php',
		__DIR__ . '/../../tickets/includes/tessera_tenant.php',
		dirname(__DIR__, 3) . '/services/tickets/includes/tessera_tenant.php',
	];
	foreach ($tesseraTenantCandidates as $tesseraTenantInc) {
		if (is_file($tesseraTenantInc)) {
			require_once $tesseraTenantInc;
			break;
		}
	}
}
if (!function_exists('tessera_document_title')) {
	function tessera_document_title(string $pageLabel, ?string $module = null, ?bool $includeOperator = true): string {
		$pageLabel = trim($pageLabel);
		if ($pageLabel === '') {
			$pageLabel = 'Control';
		}
		if ($module === 'Control') {
			return $pageLabel . ' - Tessera Control';
		}
		return $pageLabel . ' | Tessera';
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

$__page_label = $__page_title ?? 'Control';
/* Strip legacy suffixes like "Overview | Tessera Control" → "Overview". */
if (preg_match('/^(.+?)\s*\|\s*Tessera Control\s*$/i', (string) $__page_label, $__titleMatch)) {
	$__page_label = trim($__titleMatch[1]);
}
$__page_title_full = tessera_document_title((string) $__page_label, 'Control', false);
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Tessera Control — platform owner console" name="description">
<meta content="Tessera" name="author">
<title><?php echo control_h($__page_title_full); ?></title>
<?php echo tessera_favicon_link_tags('/tickets/assets/images'); ?>
<link href="/tickets/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
<link href="/tickets/assets/css/icons.min.css" rel="stylesheet" type="text/css">
<link href="/tickets/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">
<link href="/control/assets/css/control-dashboard.css?v=20260906c" rel="stylesheet" type="text/css">
<style>
/* Tessera Control brand in vertical sidebar */
.vertical-menu .navbar-brand-box .tessera-brand-lockup {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
  min-height: 70px;
  padding: 0 .25rem;
}
.vertical-menu .tessera-brand-lockup .brand {
  font-weight: 700;
  letter-spacing: .14em;
  text-transform: uppercase;
  color: #fff;
  font-size: 1.05rem;
  line-height: 1.2;
}
.vertical-menu .tessera-brand-lockup .product {
  margin-top: .15rem;
  font-size: .65rem;
  letter-spacing: .16em;
  text-transform: uppercase;
  color: rgba(255,255,255,.55);
  font-weight: 600;
}
#page-topbar .navbar-brand-box { display: none; }
body[data-sidebar-size="sm"] .vertical-menu .tessera-brand-lockup .product { display: none; }
body[data-sidebar-size="sm"] .vertical-menu .tessera-brand-lockup .brand { font-size: .85rem; letter-spacing: .08em; }
.control-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(85,110,230,.25);
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: .85rem;
}
.control-stat-icon {
  width: 32px;
  height: 32px;
  border-radius: 0.4rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.06);
  color: #adb5bd;
  box-shadow: none;
  filter: none;
}
.control-stat-icon.is-success { background: rgba(52,195,143,.12); color: #34c38f; }
.control-stat-icon.is-warning { background: rgba(241,180,76,.12); color: #f1b44c; }
.control-stat-icon.is-danger { background: rgba(244,106,106,.12); color: #f46a6a; }
.control-stat-icon.is-info { background: rgba(80,165,241,.12); color: #50a5f1; }
.control-stat-icon svg,
.control-stat-icon i {
  box-shadow: none;
  filter: none;
}
.table-control > :not(caption) > * > * { vertical-align: middle; }
</style>
<?php include __DIR__ . '/tessera_states_boot.php'; ?>
<script src="/tickets/assets/libs/jquery/jquery.min.js"></script>
