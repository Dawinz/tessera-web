<?php
/**
 * S-05 API keys — TDS create / list / revoke.
 */
require_once __DIR__ . '/includes/boot.php';
tessera_require_capability($db, $username, 'api_keys');

$settings_page_title = 'API keys';
$settings_crumb = 'API keys';
$settings_nav_active = 'api';
$settings_extra_css = [
	'/operator/css/tessera-design-tokens.css',
	'/operator/css/tessera-design-components.css',
];
require_once __DIR__ . '/includes/layout.php';

$jsV = @filemtime(__DIR__ . '/api.js') ?: time();
?>
<div class="tds-page" id="tds-settings-api-root" data-ui-slice="S-05" style="padding:0">
	<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-4)">
		<div>
			<p class="tds-breadcrumb"><span>Settings</span><span>·</span><span>API keys</span></p>
			<h1 class="tds-page-title">API keys</h1>
			<p class="tds-page-lead">Authenticate requests to <code id="tds-api-base">/tickets/api/v1/</code> with <code id="tds-api-auth-hint">Authorization: Bearer tess_…</code>.</p>
		</div>
	</div>

	<div class="tds-alert tds-alert-danger" id="tds-settings-api-error" hidden role="alert"></div>
	<div class="tds-alert tds-alert-success" id="tds-settings-api-success" hidden role="status"></div>
	<div class="tds-alert tds-alert-warning" id="tds-settings-api-newkey" hidden role="status">
		<strong>New key (copy once):</strong>
		<code id="tds-settings-api-newkey-value" style="display:block;margin-top:var(--tds-space-2);word-break:break-all"></code>
	</div>

	<div class="tds-table-panel" style="margin-bottom:var(--tds-space-4)">
		<div class="tds-toolbar"><strong>Create key</strong></div>
		<form id="tds-settings-api-create" class="tds-dialog-body" style="padding:var(--tds-space-4)">
			<div class="tds-row" style="gap:var(--tds-space-3);flex-wrap:wrap;align-items:flex-end">
				<div class="tds-field" style="flex:1 1 12rem">
					<label class="tds-label" for="tds-api-name">Name</label>
					<input class="tds-input" id="tds-api-name" name="name" value="Website" required autocomplete="off">
				</div>
				<div class="tds-field" style="flex:2 1 18rem">
					<label class="tds-label">Scopes</label>
					<div class="tds-row" id="tds-api-scopes" style="gap:var(--tds-space-3);flex-wrap:wrap;padding-top:var(--tds-space-2)"></div>
				</div>
				<button type="submit" class="tds-btn tds-btn-primary" id="tds-api-create-btn">Create</button>
			</div>
		</form>
	</div>

	<div class="tds-table-panel">
		<div class="tds-toolbar"><strong>Keys</strong></div>
		<div class="table-responsive">
			<table class="tds-table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Prefix</th>
						<th>Scopes</th>
						<th>Status</th>
						<th>Last used</th>
						<th></th>
					</tr>
				</thead>
				<tbody id="tds-api-tbody">
					<tr><td colspan="6" class="tds-muted">Loading…</td></tr>
				</tbody>
			</table>
		</div>
		<p class="tds-muted" style="padding:var(--tds-space-3) var(--tds-space-4);margin:0">
			Also configure <a href="/settings/webhooks.php">Webhooks</a>.
		</p>
	</div>
</div>
<script src="/settings/api.js?v=<?php echo (int) $jsV; ?>"></script>
<?php settings_layout_end(); ?>
