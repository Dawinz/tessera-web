<?php
/**
 * Shared TDS shell for Accounts setup screens.
 *
 * Expects: $setupTitle, $setupLead, $setupRoot, $setupSlice, $setupPage, $setupFlag
 * Optional: $setupCrumb, $setupScreen, $setupScripts, $setupLinks, $setupAttrs
 */
declare(strict_types=1);

$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$setupTitle = $setupTitle ?? 'Setup';
$setupLead = $setupLead ?? '';
$setupRoot = $setupRoot ?? 'tds-accounts-setup-root';
$setupSlice = $setupSlice ?? '';
$setupPage = $setupPage ?? 'manifest.php';
$setupFlag = $setupFlag ?? 'manifest';
$setupCrumb = $setupCrumb ?? 'Setup';
$setupScreen = $setupScreen ?? '';
$setupScripts = $setupScripts ?? [];
$setupLinks = $setupLinks ?? [];
$setupAttrs = $setupAttrs ?? '';

if (!$isSpa) {
	require_once dirname(__DIR__, 2) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}

$legacy = '/operator/app.php?module=accounts&page=' . rawurlencode($setupPage)
	. '&ui_accounts_' . rawurlencode($setupFlag) . '=legacy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo htmlspecialchars($setupTitle, ENT_QUOTES, 'UTF-8'); ?></title>
<?php if (!$isSpa): ?>
	<base href="/accounts/">
	<?php include $__roots['accounts'] . '/template/includes/dashonic_head.php'; ?>
	<link href="/operator/css/tessera-design-tokens.css" rel="stylesheet">
	<link href="/operator/css/tessera-design-components.css" rel="stylesheet">
<?php endif; ?>
</head>
<body <?php echo $isSpa ? '' : 'data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark" class="sr-platform-body sr-operator-shell"'; ?>>
<?php if (!$isSpa): ?>
<?php include dirname(__DIR__, 2) . '/includes/shell_header.php'; ?>
<div class="main-content">
<?php endif; ?>
	<div class="page-content">
		<div class="container-fluid tds-page" id="<?php echo htmlspecialchars($setupRoot, ENT_QUOTES, 'UTF-8'); ?>" data-ui-slice="<?php echo htmlspecialchars($setupSlice, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $setupScreen !== '' ? ' data-screen="' . htmlspecialchars($setupScreen, ENT_QUOTES, 'UTF-8') . '"' : ''; ?><?php echo $setupAttrs; ?>>
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Accounts</span><span>·</span><span><?php echo htmlspecialchars($setupCrumb, ENT_QUOTES, 'UTF-8'); ?></span></p>
					<h1 class="tds-page-title"><?php echo htmlspecialchars($setupTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
					<p class="tds-page-lead"><?php echo htmlspecialchars($setupLead, ENT_QUOTES, 'UTF-8'); ?></p>
				</div>
				<div class="tds-row">
<?php foreach ($setupLinks as $link): ?>
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="<?php echo htmlspecialchars((string) ($link['href'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars((string) ($link['label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></a>
<?php endforeach; ?>
					<a class="tds-btn tds-btn-ghost tds-btn-sm" href="<?php echo htmlspecialchars($legacy, ENT_QUOTES, 'UTF-8'); ?>">Use classic</a>
				</div>
			</div>
			<div id="<?php echo htmlspecialchars($setupRoot, ENT_QUOTES, 'UTF-8'); ?>-body"><p class="tds-muted">Loading…</p></div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
<?php foreach ($setupScripts as $js):
	$js = basename((string) $js);
	$v = @filemtime(__DIR__ . '/' . $js) ?: time();
?>
<script src="/operator/ui/accounts/<?php echo htmlspecialchars($js, ENT_QUOTES, 'UTF-8'); ?>?v=<?php echo (int) $v; ?>"></script>
<?php endforeach; ?>
</body>
</html>
