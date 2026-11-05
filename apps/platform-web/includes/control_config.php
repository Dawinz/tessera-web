<?php
/**
 * Tessera Control config (platform owners only).
 * Override via environment on the server; defaults are for local XAMPP.
 */
function control_config(): array {
	$email = strtolower(trim((string) (getenv('PLATFORM_OWNER_EMAIL') ?: 'owner@tessera.co.tz')));
	$alert = getenv('PLATFORM_ALERT_EMAIL');
	return [
		'owner_email' => $email,
		'owner_password' => (string) (getenv('PLATFORM_OWNER_PASSWORD') ?: 'tessera-control-dev'),
		'owner_name' => (string) (getenv('PLATFORM_OWNER_NAME') ?: 'Platform owner'),
		'public_operator_base' => rtrim((string) (getenv('TESSERA_PUBLIC_URL') ?: 'https://tessera.co.tz'), '/'),
		'control_public_url' => rtrim((string) (getenv('CONTROL_PUBLIC_URL') ?: ('https://' . (getenv('CONTROL_PUBLIC_HOST') ?: 'console.tessera.co.tz') . '/control')), '/'),
		'alert_email' => ($alert !== false && $alert !== '') ? strtolower(trim((string) $alert)) : $email,
	];
}
