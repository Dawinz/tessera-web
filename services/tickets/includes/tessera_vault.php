<?php
/** Shared AES vault (same key as Control PLATFORM_VAULT_SECRET). */
if (!function_exists('tessera_vault_key')) {
	function tessera_vault_key(): string {
		$s = (string) (getenv('PLATFORM_VAULT_SECRET') ?: getenv('PLATFORM_SESSION_SECRET') ?: 'tessera-local-vault-dev-key');
		return hash('sha256', $s, true);
	}
}
if (!function_exists('tessera_encrypt')) {
	function tessera_encrypt(string $plain): string {
		$iv = random_bytes(16);
		$ct = openssl_encrypt($plain, 'AES-256-CBC', tessera_vault_key(), OPENSSL_RAW_DATA, $iv);
		return $ct === false ? '' : base64_encode($iv . $ct);
	}
}
if (!function_exists('tessera_decrypt')) {
	function tessera_decrypt(string $encoded): string {
		$raw = base64_decode($encoded, true);
		if ($raw === false || strlen($raw) < 17) {
			return '';
		}
		$plain = openssl_decrypt(substr($raw, 16), 'AES-256-CBC', tessera_vault_key(), OPENSSL_RAW_DATA, substr($raw, 0, 16));
		return $plain === false ? '' : $plain;
	}
}
