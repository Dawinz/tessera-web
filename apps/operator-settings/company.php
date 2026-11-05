<?php
/**
 * S-01 Company profile — TDS form inside settings shell.
 */
require_once __DIR__ . '/includes/boot.php';
tessera_require_capability($db, $username, 'settings');

$settings_page_title = 'Company profile';
$settings_crumb = 'Company';
$settings_nav_active = 'company';
$settings_extra_css = [
	'/operator/css/tessera-design-tokens.css',
	'/operator/css/tessera-design-components.css',
];
require_once __DIR__ . '/includes/layout.php';

$jsV = @filemtime(__DIR__ . '/company.js') ?: time();
?>
<div class="tds-page" id="tds-settings-company-root" data-ui-slice="S-01" style="padding:0">
	<div class="tds-page-title-box" style="margin-bottom:var(--tds-space-4)">
		<div>
			<p class="tds-breadcrumb"><span>Settings</span><span>·</span><span>Company</span></p>
			<h1 class="tds-page-title">Company profile</h1>
			<p class="tds-page-lead">How your bus company appears in the operator console. Domain and plan are managed by Tessera Control.</p>
		</div>
	</div>

	<div class="tds-alert tds-alert-danger" id="tds-settings-company-error" hidden role="alert"></div>
	<div class="tds-alert tds-alert-success" id="tds-settings-company-success" hidden role="status"></div>

	<div class="tds-table-panel">
		<div class="tds-toolbar"><strong>Brand</strong></div>
		<form id="tds-settings-company-form" class="tds-dialog-body" style="padding:var(--tds-space-4)">
			<div class="tds-row" style="gap:var(--tds-space-4);flex-wrap:wrap">
				<div class="tds-field" style="flex:1 1 14rem;min-width:12rem">
					<label class="tds-label" for="tds-settings-company-display">Display name</label>
					<input class="tds-input" id="tds-settings-company-display" name="display_name" required>
				</div>
				<div class="tds-field" style="flex:1 1 14rem;min-width:12rem">
					<label class="tds-label" for="tds-settings-company-legal">Legal name</label>
					<input class="tds-input" id="tds-settings-company-legal" name="legal_name" required>
				</div>
			</div>
			<div class="tds-row" style="gap:var(--tds-space-4);flex-wrap:wrap;margin-top:var(--tds-space-3)">
				<div class="tds-field" style="flex:1 1 8rem">
					<label class="tds-label" for="tds-settings-company-slug">Slug</label>
					<input class="tds-input" id="tds-settings-company-slug" disabled>
				</div>
				<div class="tds-field" style="flex:1 1 8rem">
					<label class="tds-label" for="tds-settings-company-status">Status</label>
					<input class="tds-input" id="tds-settings-company-status" disabled>
				</div>
				<div class="tds-field" style="flex:1 1 8rem">
					<label class="tds-label" for="tds-settings-company-plan">Plan</label>
					<input class="tds-input" id="tds-settings-company-plan" disabled>
				</div>
			</div>
			<div class="tds-row" style="gap:var(--tds-space-4);flex-wrap:wrap;margin-top:var(--tds-space-3)">
				<div class="tds-field" style="flex:0 0 10rem">
					<label class="tds-label" for="tds-settings-company-color">Primary colour</label>
					<input class="tds-input" id="tds-settings-company-color" name="primary_color" placeholder="#E53E3E">
				</div>
				<div class="tds-field" style="flex:1 1 16rem">
					<label class="tds-label" for="tds-settings-company-logo">Logo URL</label>
					<input class="tds-input" id="tds-settings-company-logo" name="logo_url" placeholder="https://…">
				</div>
			</div>
			<div class="tds-field" style="margin-top:var(--tds-space-3)">
				<label class="tds-label" for="tds-settings-company-domain">Primary domain</label>
				<input class="tds-input" id="tds-settings-company-domain" disabled>
				<p class="tds-muted" style="margin:var(--tds-space-2) 0 0">DNS and custom domains are set in Tessera Control.</p>
			</div>
			<div class="tds-row" style="justify-content:flex-end;gap:var(--tds-space-2);margin-top:var(--tds-space-4)">
				<button type="submit" class="tds-btn tds-btn-primary" id="tds-settings-company-save">Save company profile</button>
			</div>
		</form>
	</div>
</div>
<script src="/settings/company.js?v=<?php echo (int) $jsV; ?>"></script>
<?php settings_layout_end(); ?>
