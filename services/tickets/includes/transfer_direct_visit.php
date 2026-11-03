<?php
/**
 * Transfer all / multiple / selected seats are POST actions from a manifest.
 * A direct visit should explain that instead of running with empty fields.
 */
function tessera_transfer_direct_visit(string $title = 'Transfer'): bool {
	if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !empty($_POST)) {
		return false;
	}
	require_once __DIR__ . '/tessera_staff_boot.php';
	$safe = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
	echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>' . $safe . '</title>';
	echo '<link href="assets/css/bootstrap.min.css" rel="stylesheet"></head><body>';
	echo '<div class="container-fluid py-4"><div class="card"><div class="card-body">';
	echo '<h4 class="mb-2">' . $safe . '</h4>';
	echo '<p class="mb-3">This step runs after you choose a trip and passengers on a manifest. Opening it on its own is not a blank error.</p>';
	echo '<a class="btn btn-primary" href="manifest.php">Open Manifest</a>';
	echo '</div></div></div></body></html>';
	return true;
}
