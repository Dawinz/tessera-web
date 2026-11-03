<?php
/**
 * Tessera password helpers — supports legacy plain-text + modern hashes.
 */
if (!function_exists('tessera_password_verify')) {
	function tessera_password_verify($plain, $stored) {
		$stored = (string) $stored;
		$plain = (string) $plain;
		if ($stored === '') {
			return false;
		}
		if (preg_match('/^\$(2[ay]|argon2)/', $stored)) {
			return password_verify($plain, $stored);
		}
		return hash_equals($stored, $plain);
	}
}

if (!function_exists('tessera_password_hash')) {
	function tessera_password_hash($plain) {
		return password_hash((string) $plain, PASSWORD_DEFAULT);
	}
}
