<?php
/**
 * Dashonic shell for Accounts report pages (report_*.php).
 * Each report builds $report then includes this file.
 */
include_once __DIR__ . '/report_helpers.php';

if (empty($report) || !is_array($report)) {
	http_response_code(500);
	echo 'Report configuration missing.';
	exit;
}

$rp_currency = isset($report['currency']) ? $report['currency'] : 'TSh';

if (!function_exists('rp_bx_icon')) {
	function rp_bx_icon($fa_class) {
		$map = [
			'fa-coins' => 'bx bx-dollar-circle',
			'fa-gas-pump' => 'bx bx-gas-pump',
			'fa-chart-line' => 'bx bx-line-chart',
			'fa-balance-scale' => 'bx bx-scale',
			'fa-truck' => 'bx bx-bus',
			'fa-route' => 'bx bx-map',
			'fa-shopping-cart' => 'bx bx-cart',
			'fa-file-invoice' => 'bx bx-receipt',
		];
		$key = trim((string) $fa_class);
		return isset($map[$key]) ? $map[$key] : 'bx bx-bar-chart-alt-2';
	}
}

$__page_title = (string) $report['title'];
$__reports_css = '/accounts/css/tessera-reports.css?v=' . (@filemtime(dirname(__DIR__, 2) . '/css/tessera-reports.css') ?: time());
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include __DIR__ . '/dashonic_head.php'; ?>
	<link href="<?php echo htmlspecialchars($__reports_css, ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet">
</head>
<body data-layout="horizontal" data-topbar="dark" data-layout-mode="dark" class="sr-platform-body sr-accounts-reports">
<?php include __DIR__ . '/top_header.php'; ?>

<div class="main-content">
	<div class="page-content">
		<div class="container-fluid sr-accounts-page sr-report-sample-page">

			<div class="sr-content-card rp-wrap">
				<div class="rp-title-box">
					<div>
						<div class="rp-breadcrumb"><?php echo rp_e(isset($report['group']) ? $report['group'] : 'Reports'); ?></div>
						<h4 class="mb-0"><?php echo rp_e($report['title']); ?></h4>
						<?php if (!empty($report['description'])) { ?>
							<p class="rp-description mb-0"><?php echo rp_e($report['description']); ?></p>
						<?php } ?>
					</div>
					<div class="rp-actions">
						<button type="button" class="btn btn-sm btn-outline-secondary" id="rp_export">
							<i class="bx bx-download" aria-hidden="true"></i> Export CSV
						</button>
						<button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print();">
							<i class="bx bx-printer" aria-hidden="true"></i> Print
						</button>
					</div>
				</div>

				<?php if (!isset($report['sample']) || $report['sample']) { ?>
					<div class="rp-notice" role="note">
						<i class="bx bx-info-circle" aria-hidden="true"></i>
						<span>Figures are taken from ticket sales, cargo collections, trip expenses, and office expenses for the selected month.</span>
					</div>
				<?php } ?>

				<?php if (!empty($report['filters'])) { ?>
					<div class="card rp-card rp-filter-card border-0 shadow-none">
						<div class="card-body">
							<form class="rp-filter" autocomplete="off" onsubmit="return false;">
								<?php foreach ($report['filters'] as $filter) {
									$fid = rp_e($filter['id']);
									?>
									<div class="rp-field">
										<label class="rp-label" for="<?php echo $fid; ?>"><?php echo rp_e($filter['label']); ?></label>
										<?php if ($filter['type'] == 'select') { ?>
											<select class="form-select form-select-sm rp-input" id="<?php echo $fid; ?>" name="<?php echo $fid; ?>">
												<?php foreach ($filter['options'] as $value => $text) { ?>
													<option value="<?php echo rp_e($value); ?>"<?php echo (isset($filter['value']) && $filter['value'] == $value) ? ' selected' : ''; ?>><?php echo rp_e($text); ?></option>
												<?php } ?>
											</select>
										<?php } elseif ($filter['type'] == 'date') { ?>
											<input class="form-control form-control-sm rp-input" type="date" id="<?php echo $fid; ?>" name="<?php echo $fid; ?>" value="<?php echo rp_e(isset($filter['value']) ? $filter['value'] : ''); ?>">
										<?php } else { ?>
											<div class="rp-search">
												<i class="bx bx-search" aria-hidden="true"></i>
												<input class="form-control form-control-sm rp-input" type="search" id="<?php echo $fid; ?>" name="<?php echo $fid; ?>" placeholder="<?php echo rp_e(isset($filter['placeholder']) ? $filter['placeholder'] : 'Search'); ?>">
											</div>
										<?php } ?>
									</div>
								<?php } ?>
								<div class="rp-field rp-field-apply">
									<button type="submit" class="btn btn-sm btn-primary" id="rp_apply">
										<i class="bx bx-filter-alt" aria-hidden="true"></i> Apply
									</button>
								</div>
							</form>
							<div class="rp-apply-note" id="rp_apply_note" hidden>Filters are not connected yet - sample figures stay the same.</div>
						</div>
					</div>
				<?php } ?>

				<?php if (!empty($report['kpis'])) { ?>
					<div class="rp-kpis">
						<?php foreach ($report['kpis'] as $kpi) {
							$tone = isset($kpi['tone']) ? $kpi['tone'] : 'primary';
							?>
							<div class="rp-kpi">
								<span class="rp-kpi-icon is-<?php echo rp_e($tone); ?>"><i class="<?php echo rp_e(rp_bx_icon(isset($kpi['icon']) ? $kpi['icon'] : 'fa-chart-line')); ?>" aria-hidden="true"></i></span>
								<div>
									<div class="rp-kpi-label"><?php echo rp_e($kpi['label']); ?></div>
									<div class="rp-kpi-value">
										<?php echo rp_kpi_value($kpi); ?>
										<?php if ((!isset($kpi['format']) || $kpi['format'] == 'money')) { ?><small><?php echo rp_e($rp_currency); ?></small><?php } ?>
									</div>
									<?php if (!empty($kpi['note'])) { ?><div class="rp-kpi-note"><?php echo rp_e($kpi['note']); ?></div><?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>

				<div class="rp-blocks">
					<?php foreach ($report['blocks'] as $block) { ?>
						<div class="card rp-card border-0 shadow-none <?php echo (isset($block['width']) && $block['width'] == 'half') ? 'rp-half' : 'rp-full'; ?>">
							<div class="card-body">
								<?php if (!empty($block['title'])) { ?>
									<div class="rp-block-head">
										<div class="rp-section-title"><?php echo rp_e($block['title']); ?></div>
										<?php if (!empty($block['note'])) { ?><div class="rp-block-note"><?php echo rp_e($block['note']); ?></div><?php } ?>
									</div>
								<?php } ?>

								<?php if ($block['type'] == 'statement') { ?>
									<div class="rp-table-box">
										<table class="rp-table rp-statement">
											<thead>
												<tr><th>Account</th><th class="rp-num">Amount (<?php echo rp_e($rp_currency); ?>)</th></tr>
											</thead>
											<tbody>
												<?php foreach ($block['rows'] as $line) {
													$kind = $line[0];
													?>
													<tr class="is-<?php echo rp_e($kind); ?>">
														<?php if ($kind == 'heading') { ?>
															<td colspan="2"><?php echo rp_e($line[1]); ?></td>
														<?php } else { ?>
															<td><?php echo rp_e($line[1]); ?></td>
															<td class="rp-num"><?php echo rp_money($line[2]); ?></td>
														<?php } ?>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								<?php } else { ?>
									<div class="rp-table-box">
										<table class="rp-table rp-data">
											<thead>
												<tr>
													<?php foreach ($block['columns'] as $col) {
														$numeric = isset($col['format']) && in_array($col['format'], ['money', 'number', 'percent'], true);
														$label = $col['label'] . ((isset($col['format']) && $col['format'] == 'money') ? ' (' . $rp_currency . ')' : '');
														?>
														<th class="<?php echo $numeric ? 'rp-num' : ''; ?>"><?php echo rp_e($label); ?></th>
													<?php } ?>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($block['rows'] as $row) { ?>
													<tr>
														<?php foreach ($block['columns'] as $col) {
															$format = isset($col['format']) ? $col['format'] : 'text';
															$numeric = in_array($format, ['money', 'number', 'percent'], true);
															?>
															<td class="<?php echo $numeric ? 'rp-num' : ''; ?>"><?php echo rp_cell($row[$col['key']], $format); ?></td>
														<?php } ?>
													</tr>
												<?php } ?>
												<?php if (empty($block['rows'])) { ?>
													<tr><td class="rp-empty" colspan="<?php echo count($block['columns']); ?>">Nothing to show for these filters.</td></tr>
												<?php } ?>
											</tbody>
											<?php if (!empty($block['footer'])) { ?>
												<tfoot>
													<tr>
														<?php foreach ($block['columns'] as $i => $col) {
															$format = isset($col['format']) ? $col['format'] : 'text';
															$numeric = in_array($format, ['money', 'number', 'percent'], true);
															$has = array_key_exists($col['key'], $block['footer']);
															?>
															<td class="<?php echo $numeric ? 'rp-num' : ''; ?>"><?php
															if ($i == 0) {
																echo rp_e(isset($block['footer']['_label']) ? $block['footer']['_label'] : 'Total');
															} elseif ($has) {
																echo rp_cell($block['footer'][$col['key']], $format);
															}
															?></td>
														<?php } ?>
													</tr>
												</tfoot>
											<?php } ?>
										</table>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>

		</div>
	</div>
</div>

<?php include __DIR__ . '/dashonic_foot.php'; ?>
<script>
(function ($) {
	$('#rp_apply').on('click', function () {
		$('#rp_apply_note').prop('hidden', false);
	});
	$('.rp-filter input[type=search]').on('input', function () {
		var value = $(this).val().toLowerCase();
		$('.rp-data tbody tr').each(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});
	$('#rp_export').on('click', function () {
		var table = document.querySelector('.rp-table');
		if (!table) {
		 return;
		}
		var lines = [];
		table.querySelectorAll('tr').forEach(function (tr) {
			if (tr.offsetParent === null) {
				return;
			}
			var cells = [];
			tr.querySelectorAll('th, td').forEach(function (cell) {
				var text = cell.innerText.replace(/\s+/g, ' ').trim();
				if (/^\(?[\d,]+(\.\d+)?\)?$/.test(text)) {
					text = (text.charAt(0) === '(' ? '-' : '') + text.replace(/[(),]/g, '');
				}
				cells.push('"' + text.replace(/"/g, '""') + '"');
			});
			lines.push(cells.join(','));
		});
		var blob = new Blob([lines.join('\r\n')], { type: 'text/csv;charset=utf-8' });
		var link = document.createElement('a');
		link.href = URL.createObjectURL(blob);
		link.download = <?php echo json_encode(strtolower(preg_replace('/[^A-Za-z0-9]+/', '_', $report['title'])) . '.csv'); ?>;
		document.body.appendChild(link);
		link.click();
		link.remove();
	});
})(jQuery);
</script>
</body>
</html>
