<?php
/**
 * Accounts SPA - delegates to platform operator SPA capture.
 */
require_once dirname(__DIR__, 3) . '/tickets/includes/tessera_operator_spa.php';

if (!function_exists('tessera_accounts_spa_allowed')) {
	function tessera_accounts_spa_allowed(string $page): bool {
		return tessera_operator_spa_allowed('accounts', $page);
	}

	function tessera_accounts_spa_is_request(): bool {
		return tessera_operator_spa_is_request();
	}

	/** @param array<string,string|int> $query */
	function tessera_accounts_spa_capture(string $page, array $query = []): array {
		return tessera_operator_spa_capture('accounts', $page, $query);
	}

	function tessera_accounts_spa_parse_html(string $html, string $path, array $query = []): array {
		return tessera_operator_spa_parse_html($html, 'accounts', $path, $query);
	}
}
