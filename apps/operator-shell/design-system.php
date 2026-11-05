<?php
/**
 * Tessera Operator Design System — live gallery (source of truth reference).
 * Signed-in staff only. Not linked in ops menus (bookmark / docs).
 */
require_once __DIR__ . '/includes/shell_bootstrap.php';

$module = 'tickets';
$page = 'home.php';
try {
	$initial = tessera_operator_spa_capture($module, $page, []);
} catch (Throwable $e) {
	$initial = ['topnav_html' => '', 'title' => 'Design system', 'html' => '', 'path' => 'home.php', 'styles' => '', 'scripts' => []];
}

$sr_active_module = $module;
$sr_topnav_html = $initial['topnav_html'] ?? '';
$__page_title = 'Design system';
$tessera_page_module = 'Tickets';
$tokensV = @filemtime(__DIR__ . '/css/tessera-design-tokens.css') ?: time();
$compV = @filemtime(__DIR__ . '/css/tessera-design-components.css') ?: time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="/tickets/">
	<?php
	require_once __DIR__ . '/includes/shell_paths.php';
	$__roots = tessera_operator_module_roots();
	include $__roots['accounts'] . '/template/includes/dashonic_head.php';
	?>
	<link href="/operator/css/tessera-design-tokens.css?v=<?php echo (int) $tokensV; ?>" rel="stylesheet">
	<link href="/operator/css/tessera-design-components.css?v=<?php echo (int) $compV; ?>" rel="stylesheet">
	<link href="/operator/css/tessera-operator-shell.css?v=<?php echo @filemtime(__DIR__ . '/css/tessera-operator-shell.css') ?: time(); ?>" rel="stylesheet">
	<title>Design system · Tessera</title>
	<style>
		.tds-gallery-section { margin-bottom: 2rem; }
		.tds-gallery-section h2 {
			font-size: 1rem;
			font-weight: 600;
			margin: 0 0 0.75rem;
			color: var(--tds-ink-secondary);
			text-transform: uppercase;
			letter-spacing: 0.04em;
		}
		.tds-swatch-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(9rem, 1fr));
			gap: 0.75rem;
		}
		.tds-swatch {
			border: 1px solid var(--tds-line-strong);
			border-radius: var(--tds-radius-md);
			overflow: hidden;
			background: var(--tds-surface);
		}
		.tds-swatch-chip { height: 3rem; }
		.tds-swatch-meta { padding: 0.5rem 0.65rem; font-size: 0.75rem; }
		.tds-swatch-meta code { font-size: 0.68rem; color: var(--tds-ink-muted); }
	</style>
</head>
<body data-layout="horizontal" data-layout-scrollable="true" data-topbar="dark" data-layout-mode="dark" class="sr-platform-body sr-operator-shell">
<?php include __DIR__ . '/includes/shell_header.php'; ?>
<div class="main-content">
	<div class="page-content">
		<div class="container-fluid tds-page">
			<div class="tds-page-title-box">
				<div>
					<p class="tds-breadcrumb"><span>Tessera</span><span>·</span><span>Operator</span></p>
					<h1 class="tds-page-title">Operator design system</h1>
					<p class="tds-page-lead">Source of truth for Tickets, Cargo, Accounts, and Settings. Tokens and recipes live in <code>packages/tessera-design-system</code>. Doc: <code>docs/49</code>.</p>
				</div>
				<div class="tds-row">
					<a class="tds-btn tds-btn-secondary tds-btn-sm" href="/operator/app.php?module=tickets&amp;page=home.php">Back to console</a>
				</div>
			</div>

			<div class="tds-alert tds-alert-info tds-gallery-section" role="status">
				<div>
					<strong>Governance.</strong> Change tokens in the package first, run <code>./scripts/sync-tessera-design-system.sh</code>, then ship. New screens must use <code>--tds-*</code> / <code>.tds-*</code> — no one-off hex or fonts.
				</div>
			</div>

			<section class="tds-gallery-section tds-card">
				<div class="tds-card-header"><h2 class="tds-card-title" style="text-transform:none;letter-spacing:0">Colour tokens</h2></div>
				<div class="tds-card-body">
					<div class="tds-swatch-grid">
						<?php
						$swatches = [
							['Chrome', 'var(--tds-chrome)', '--tds-chrome'],
							['Chrome deep', 'var(--tds-chrome-deep)', '--tds-chrome-deep'],
							['Accent', 'var(--tds-accent)', '--tds-accent'],
							['Surface', 'var(--tds-surface)', '--tds-surface'],
							['Canvas', 'var(--tds-canvas)', '--tds-canvas'],
							['Success', 'var(--tds-success)', '--tds-success'],
							['Danger', 'var(--tds-danger)', '--tds-danger'],
							['Warning', 'var(--tds-warning)', '--tds-warning'],
						];
						foreach ($swatches as [$label, $bg, $token]):
						?>
						<div class="tds-swatch">
							<div class="tds-swatch-chip" style="background:<?php echo htmlspecialchars($bg, ENT_QUOTES, 'UTF-8'); ?>"></div>
							<div class="tds-swatch-meta">
								<strong><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></strong><br>
								<code><?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?></code>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<section class="tds-gallery-section">
				<h2>Buttons</h2>
				<div class="tds-row">
					<button type="button" class="tds-btn tds-btn-primary">Primary</button>
					<button type="button" class="tds-btn tds-btn-accent">Accent</button>
					<button type="button" class="tds-btn tds-btn-secondary">Secondary</button>
					<button type="button" class="tds-btn tds-btn-ghost">Ghost</button>
					<button type="button" class="tds-btn tds-btn-danger">Danger</button>
					<button type="button" class="tds-btn tds-btn-primary tds-btn-sm">Small</button>
					<button type="button" class="tds-btn tds-btn-primary" disabled>Disabled</button>
				</div>
			</section>

			<section class="tds-gallery-section">
				<h2>Badges</h2>
				<div class="tds-row">
					<span class="tds-badge tds-badge-paid">Paid</span>
					<span class="tds-badge tds-badge-due">Due</span>
					<span class="tds-badge tds-badge-warn">Pending</span>
					<span class="tds-badge tds-badge-info">Info</span>
					<span class="tds-badge tds-badge-neutral">Neutral</span>
				</div>
			</section>

			<section class="tds-gallery-section">
				<h2>Alerts</h2>
				<div class="tds-stack">
					<div class="tds-alert tds-alert-info">Informational note for operators.</div>
					<div class="tds-alert tds-alert-warning">Check dates before applying.</div>
					<div class="tds-alert tds-alert-danger">Action failed — try again.</div>
					<div class="tds-alert tds-alert-success">Saved successfully.</div>
				</div>
			</section>

			<section class="tds-gallery-section">
				<h2>Toolbar + date range</h2>
				<div class="tds-toolbar">
					<div class="tds-date-range">
						<div class="tds-field">
							<label class="tds-label" for="ds-from">From</label>
							<input class="tds-input" id="ds-from" type="date" value="<?php echo date('Y-m-01'); ?>">
						</div>
						<span class="tds-date-range-sep" aria-hidden="true">→</span>
						<div class="tds-field">
							<label class="tds-label" for="ds-to">To</label>
							<input class="tds-input" id="ds-to" type="date" value="<?php echo date('Y-m-d'); ?>">
						</div>
					</div>
					<div class="tds-field">
						<label class="tds-label" for="ds-route">Route</label>
						<select class="tds-select" id="ds-route">
							<option>All routes</option>
						</select>
					</div>
					<div class="tds-field" style="min-width:14rem;flex:1">
						<label class="tds-label" for="ds-q">Search</label>
						<input class="tds-search" id="ds-q" type="search" placeholder="Code or vehicle">
					</div>
					<button type="button" class="tds-btn tds-btn-primary">Apply</button>
				</div>
			</section>

			<section class="tds-gallery-section">
				<h2>Seat chart chrome</h2>
				<div class="tds-seat-chart">
					<div class="tds-seat-chart-legend">
						<span class="tds-seat-chart-legend-item"><button type="button" class="tds-seat tds-seat-available" tabindex="-1">12</button> Available</span>
						<span class="tds-seat-chart-legend-item"><button type="button" class="tds-seat tds-seat-selected" tabindex="-1">13</button> Selected</span>
						<span class="tds-seat-chart-legend-item"><button type="button" class="tds-seat tds-seat-taken" tabindex="-1" disabled>14</button> Taken</span>
						<span class="tds-seat-chart-legend-item"><button type="button" class="tds-seat tds-seat-blocked" tabindex="-1" disabled>—</button> Blocked</span>
					</div>
					<div class="tds-seat-grid" style="grid-template-columns:repeat(4,2.25rem)">
						<button type="button" class="tds-seat tds-seat-available">1</button>
						<button type="button" class="tds-seat tds-seat-available">2</button>
						<button type="button" class="tds-seat tds-seat-selected">3</button>
						<button type="button" class="tds-seat tds-seat-taken" disabled>4</button>
						<button type="button" class="tds-seat tds-seat-available">5</button>
						<button type="button" class="tds-seat tds-seat-blocked" disabled>—</button>
						<button type="button" class="tds-seat tds-seat-available">7</button>
						<button type="button" class="tds-seat tds-seat-available">8</button>
					</div>
				</div>
			</section>

			<section class="tds-gallery-section">
				<h2>KPI cards</h2>
				<div class="tds-kpi-grid">
					<div class="tds-kpi">
						<p class="tds-kpi-label">Trips</p>
						<p class="tds-kpi-value">128</p>
						<p class="tds-kpi-hint">Selected period</p>
					</div>
					<div class="tds-kpi">
						<p class="tds-kpi-label">Revenue</p>
						<p class="tds-kpi-value tds-mono">12.4M</p>
						<p class="tds-kpi-hint">Tickets and cargo</p>
					</div>
					<div class="tds-kpi">
						<p class="tds-kpi-label">Margin</p>
						<p class="tds-kpi-value">18%</p>
						<p class="tds-kpi-hint">After trip costs</p>
					</div>
				</div>
			</section>

			<section class="tds-gallery-section">
				<h2>Table</h2>
				<div class="tds-table-panel">
					<table class="tds-table">
						<thead>
							<tr>
								<th>Trip</th>
								<th>Route</th>
								<th>Status</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="tds-mono">TRP-1042</td>
								<td>Dar es Salaam → Lubumbashi</td>
								<td><span class="tds-badge tds-badge-paid">Paid</span></td>
								<td class="tds-mono">450,000</td>
							</tr>
							<tr>
								<td class="tds-mono">TRP-1043</td>
								<td>Nairobi → Dar es Salaam</td>
								<td><span class="tds-badge tds-badge-due">Due</span></td>
								<td class="tds-mono">210,000</td>
							</tr>
							<tr>
								<td class="tds-mono">TRP-1044</td>
								<td>Dar es Salaam → Harare</td>
								<td><span class="tds-badge tds-badge-warn">Pending</span></td>
								<td class="tds-mono">0</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>

			<section class="tds-gallery-section tds-card">
				<div class="tds-card-body tds-empty">
					<p class="tds-empty-title">No trips in this range</p>
					<p class="tds-muted">Adjust filters or register a departure on Schedule.</p>
					<p style="margin-top:1rem"><button type="button" class="tds-btn tds-btn-accent">Open schedule</button></p>
				</div>
			</section>

			<p class="tds-muted" style="margin:2rem 0 3rem;font-size:0.85rem">
				Package README: <code>packages/tessera-design-system/README.md</code> ·
				Obsidian: <code>docs/49 Tessera operator design system.md</code> ·
				Modernization: <code>docs/48 Operator UI modernization plan.md</code>
			</p>
		</div>
	</div>
</div>
<?php include $__roots['accounts'] . '/template/includes/dashonic_foot.php'; ?>
</body>
</html>
