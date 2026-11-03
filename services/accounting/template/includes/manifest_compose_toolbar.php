<?php
/**
 * Top bar for manifest Insert / View workspace (compose_vehicle, manifest_view).
 * Set $__manifest_compose_mode to "insert" or "view" before include.
 */
$mc_main_page = basename((string) ($_GET['main_page'] ?? 'manifest.php'));
$mc_route = (string) ($_GET['route_name'] ?? '');
$mc_bus = (string) ($_GET['bus'] ?? '');
$mc_trip = (string) ($_GET['trip_date'] ?? '');
$mc_mode = ($__manifest_compose_mode ?? '') === 'view' ? 'View manifest' : 'Insert entries';
?>
<div class="sr-compose-toolbar d-flex flex-wrap align-items-center gap-2 mb-3">
  <a href="<?php echo htmlspecialchars($mc_main_page, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-soft-secondary btn-sm">
    <i class="bx bx-arrow-back"></i> Back to manifest
  </a>
  <span class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($mc_mode, ENT_QUOTES, 'UTF-8'); ?></span>
  <div class="sr-compose-meta ms-auto text-muted small text-end">
    <span class="d-block"><strong><?php echo htmlspecialchars($mc_route, ENT_QUOTES, 'UTF-8'); ?></strong></span>
    <span><?php echo htmlspecialchars($mc_bus, ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlspecialchars($mc_trip, ENT_QUOTES, 'UTF-8'); ?></span>
  </div>
</div>
