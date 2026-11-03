<?php
/**
 * Browser tab title + favicon for operator pages.
 * Set $tessera_page_label before include (e.g. 'Dashboard', 'Book list').
 * Optional: $tessera_page_module ('Tickets', 'Cargo', 'Accounts', 'Settings').
 */
if (!function_exists('tessera_document_title')) {
	require_once __DIR__ . '/tessera_tenant.php';
}
$__tessera_label = isset($tessera_page_label) ? trim((string) $tessera_page_label) : 'Tessera';
$__tessera_module = isset($tessera_page_module) ? trim((string) $tessera_page_module) : 'Cargo';
$__tessera_title = tessera_document_title(
	$__tessera_label,
	$__tessera_module !== '' ? $__tessera_module : null
);
?>
<title><?php echo htmlspecialchars($__tessera_title, ENT_QUOTES, 'UTF-8'); ?></title>
<?php echo tessera_favicon_link_tags(); ?>
