<?php
/**
 * AES-256-CBC helpers for Control secrets vault.
 */
if (!function_exists('control_vault_key')) {
	function control_vault_key(): string {
		$s = (string) (getenv('PLATFORM_VAULT_SECRET') ?: getenv('PLATFORM_SESSION_SECRET') ?: 'tessera-local-vault-dev-key');
		return hash('sha256', $s, true);
	}
}

if (!function_exists('control_encrypt')) {
	function control_encrypt(string $plain): string {
		$iv = random_bytes(16);
		$ct = openssl_encrypt($plain, 'AES-256-CBC', control_vault_key(), OPENSSL_RAW_DATA, $iv);
		if ($ct === false) {
			return '';
		}
		return base64_encode($iv . $ct);
	}
}

if (!function_exists('control_decrypt')) {
	function control_decrypt(string $encoded): string {
		$raw = base64_decode($encoded, true);
		if ($raw === false || strlen($raw) < 17) {
			return '';
		}
		$iv = substr($raw, 0, 16);
		$ct = substr($raw, 16);
		$plain = openssl_decrypt($ct, 'AES-256-CBC', control_vault_key(), OPENSSL_RAW_DATA, $iv);
		return $plain === false ? '' : $plain;
	}
}
