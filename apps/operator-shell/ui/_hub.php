<?php
/**
 * Thin TDS page that points staff at the screen that replaced a leftover.
 *
 * @var array{
 *   id:string,
 *   slice:string,
 *   title:string,
 *   crumb:string,
 *   section:string,
 *   lead:string,
 *   paragraphs?:list<string>,
 *   links?:list<array{href:string,label:string,primary?:bool,id?:string}>,
 *   move?:string
 * } $hub
 */
declare(strict_types=1);

$hub = $hub ?? [];
$isSpa = !empty($GLOBALS['tessera_operator_spa_embed']);
$hubId = (string) ($hub['id'] ?? 'tds-hub');
$slice = (string) ($hub['slice'] ?? '');
$title = (string) ($hub['title'] ?? 'Moved');
$crumb = (string) ($hub['crumb'] ?? 'Operator');
$section = (string) ($hub['section'] ?? '');
$lead = (string) ($hub['lead'] ?? '');
$move = (string) ($hub['move'] ?? '');
$paragraphs = is_array($hub['paragraphs'] ?? null) ? $hub['paragraphs'] : [];
$links = is_array($hub['links'] ?? null) ? $hub['links'] : [];
$base = (string) ($hub['base'] ?? '/tickets/');

if (!$isSpa) {
	require_once dirname(__DIR__) . '/includes/shell_bootstrap.php';
	$__roots = tessera_operator_module_roots();
}

$h = static function (string $value): string {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $h($title); ?></title>
<?php if (!$isSpa): ?>
	<base href="<?php echo $h($base); ?>">
	<?php include $__roots['accounts'] . '/template/includes/dashonic_head.php'; ?>
	<link href="/operator/css/tessera-design-tokens.css" rel="stylesheet">
	<link href="/operator/css/tessera-design-components.css" rel="stylesheet">
<?php endif; ?>
</head>
<body <?php echo $isSpa ? '' : 'data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark" class="sr-platform-body sr-operator-shell"'; ?>>
<?php if (!$isSpa): ?>
<?php include dirname(__DIR__) . '/includes/shell_header.php'; ?>
<div class="main-content">
<?php endif; ?>
	<div class="page-content">
		<div class="container-fluid tds-page" id="<?php echo $h($hubId); ?>" data-ready="1" data-ui-slice="<?php echo $h($slice); ?>"<?php echo $move !== '' ? ' data-move="' . $h($move) . '"' : ''; ?>>
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span><?php echo $h($crumb); ?></span><?php if ($section !== ''): ?><span>·</span><span><?php echo $h($section); ?></span><?php endif; ?></p>
					<h1 class="tds-page-title"><?php echo $h($title); ?></h1>
					<?php if ($lead !== ''): ?><p class="tds-page-lead"><?php echo $h($lead); ?></p><?php endif; ?>
				</div>
			</div>
			<div class="tds-card">
				<div class="tds-card-body">
					<?php foreach ($paragraphs as $paragraph): ?>
						<p><?php echo $h((string) $paragraph); ?></p>
					<?php endforeach; ?>
					<div class="tds-row" style="margin-top:var(--tds-space-4)">
						<?php foreach ($links as $link): ?>
							<?php
							$href = (string) ($link['href'] ?? '#');
							$label = (string) ($link['label'] ?? 'Open');
							$primary = !empty($link['primary']);
							$linkId = (string) ($link['id'] ?? '');
							?>
							<a class="tds-btn <?php echo $primary ? 'tds-btn-primary' : 'tds-btn-secondary'; ?>"<?php echo $linkId !== '' ? ' id="' . $h($linkId) . '"' : ''; ?> href="<?php echo $h($href); ?>"><?php echo $h($label); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if (!$isSpa): ?>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
<?php endif; ?>
</body>
</html>
