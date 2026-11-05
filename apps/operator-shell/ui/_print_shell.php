<?php
/**
 * Shared TDS print sheet. Caller sets $print* variables before including this file.
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']) || !empty($GLOBALS['tessera_accounts_spa_embed']);
$printTitle = $printTitle ?? 'Print';
$printLead = $printLead ?? '';
$printRoot = $printRoot ?? 'tds-print-doc';
$printSlice = $printSlice ?? '';
$printPage = $printPage ?? 'print_ticket.php';
$printFlag = $printFlag ?? 'print_ticket';
$printKind = $printKind ?? 'ticket';
$printModule = $printModule ?? 'tickets';
$printCrumb = $printCrumb ?? 'Prints';
$printApi = $printApi ?? '/tickets/api/console/print_document.php';
$printScript = $printScript ?? '/operator/ui/prints.js';
$printSelectable = !empty($printSelectable);
$printMode = $printMode ?? '';
$jsV = @filemtime(__DIR__ . '/prints.js') ?: time();
if (str_contains($printScript, 'physical_booking.js')) {
	$jsV = @filemtime(__DIR__ . '/tickets/physical_booking.js') ?: $jsV;
}
if (str_contains($printScript, 'transfer.js')) {
	$jsV = @filemtime(__DIR__ . '/tickets/transfer.js') ?: $jsV;
}

if (!$isSpa) {
	require_once dirname(__DIR__) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}

$legacyModule = $printModule === 'accounts' ? 'accounts' : $printModule;
$legacy = '/operator/app.php?module=' . rawurlencode($legacyModule)
	. '&page=' . rawurlencode($printPage)
	. '&ui_' . rawurlencode($printModule) . '_' . rawurlencode($printFlag) . '=legacy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo htmlspecialchars($printTitle, ENT_QUOTES, 'UTF-8'); ?></title>
<?php if (!$isSpa): ?>
	<base href="/<?php echo htmlspecialchars($printModule === 'accounts' ? 'accounts' : $printModule, ENT_QUOTES, 'UTF-8'); ?>/">
	<?php
	$headRoot = $__roots[$printModule] ?? ($__roots['tickets'] ?? '');
	$headFile = $printModule === 'accounts'
		? $headRoot . '/template/includes/dashonic_head.php'
		: $headRoot . '/includes/dashonic_head.php';
	if (!is_file($headFile) && $printModule !== 'accounts') {
		$headFile = $headRoot . '/dashonic_head.php';
	}
	if (is_file($headFile)) {
		include $headFile;
	}
	?>
	<link href="/operator/css/tessera-design-tokens.css" rel="stylesheet">
	<link href="/operator/css/tessera-design-components.css" rel="stylesheet">
<?php endif; ?>
	<style>
		@media print {
			.tds-print-chrome { display: none !important; }
			.tds-print-sheet { break-inside: avoid; }
		}
	</style>
</head>
<body <?php echo $isSpa ? '' : 'data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark" class="sr-platform-body sr-operator-shell"'; ?>>
<?php if (!$isSpa): ?>
<?php include dirname(__DIR__) . '/includes/shell_header.php'; ?>
<div class="main-content">
<?php endif; ?>
	<div class="page-content">
		<div class="container-fluid tds-page" id="<?php echo htmlspecialchars($printRoot, ENT_QUOTES, 'UTF-8'); ?>"
			data-print-root="1"
			data-ui-slice="<?php echo htmlspecialchars($printSlice, ENT_QUOTES, 'UTF-8'); ?>"
			data-kind="<?php echo htmlspecialchars($printKind, ENT_QUOTES, 'UTF-8'); ?>"
			data-api="<?php echo htmlspecialchars($printApi, ENT_QUOTES, 'UTF-8'); ?>"
			data-module="<?php echo htmlspecialchars($printModule, ENT_QUOTES, 'UTF-8'); ?>"
			data-mode="<?php echo htmlspecialchars($printMode, ENT_QUOTES, 'UTF-8'); ?>"
			data-selectable="<?php echo $printSelectable ? '1' : '0'; ?>"
			data-ready="0">
			<div class="tds-page-title-box tds-print-chrome">
				<div>
					<p class="tds-breadcrumb"><span><?php echo htmlspecialchars(ucfirst($printModule), ENT_QUOTES, 'UTF-8'); ?></span><span>·</span><span><?php echo htmlspecialchars($printCrumb, ENT_QUOTES, 'UTF-8'); ?></span></p>
					<h1 class="tds-page-title"><?php echo htmlspecialchars($printTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
					<p class="tds-page-lead"><?php echo htmlspecialchars($printLead, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-primary tds-btn-sm" id="<?php echo htmlspecialchars($printRoot, ENT_QUOTES, 'UTF-8'); ?>-print">Print</button>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="<?php echo htmlspecialchars($legacy, ENT_QUOTES, 'UTF-8'); ?>">Use classic</a>
				</div>
			</div>

			<div class="tds-alert tds-alert-danger tds-print-chrome" id="<?php echo htmlspecialchars($printRoot, ENT_QUOTES, 'UTF-8'); ?>-error" hidden role="alert"></div>
			<div id="<?php echo htmlspecialchars($printRoot, ENT_QUOTES, 'UTF-8'); ?>-loading" class="tds-empty tds-print-chrome">
				<p class="tds-empty-title">Loading document…</p>
			</div>
			<div id="<?php echo htmlspecialchars($printRoot, ENT_QUOTES, 'UTF-8'); ?>-empty" class="tds-empty" hidden>
				<p class="tds-empty-title">Nothing to print</p>
				<p class="tds-empty-text" id="<?php echo htmlspecialchars($printRoot, ENT_QUOTES, 'UTF-8'); ?>-empty-text">Open this page from the list that created it.</p>
			</div>

			<article class="tds-card tds-print-sheet" id="<?php echo htmlspecialchars($printRoot, ENT_QUOTES, 'UTF-8'); ?>-sheet" hidden style="padding:var(--tds-space-5);margin-top:var(--tds-space-4)"></article>
			<div class="tds-row tds-print-chrome" id="<?php echo htmlspecialchars($printRoot, ENT_QUOTES, 'UTF-8'); ?>-actions" style="margin-top:var(--tds-space-4);gap:var(--tds-space-2);flex-wrap:wrap"></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include dirname(__DIR__) . '/includes/shell_footer.php'; ?>
<?php endif; ?>
<script src="<?php echo htmlspecialchars($printScript, ENT_QUOTES, 'UTF-8'); ?>?v=<?php echo (int) $jsV; ?>"></script>
</body>
</html>
