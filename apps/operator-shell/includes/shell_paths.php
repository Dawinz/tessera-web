<?php
/**
 * Resolve Tickets / Accounts / Cargo roots for monorepo vs Docker / XAMPP web root.
 *
 * Docker / XAMPP: DOCUMENT_ROOT has /tickets, /cargo, /accounts
 * Monorepo checkout: apps/operator-shell → ../../services/{tickets,cargo,accounting}
 */
if (!function_exists('tessera_operator_module_roots')) {
	function tessera_operator_module_roots(): array {
		static $cached = null;
		if ($cached !== null) {
			return $cached;
		}
		$doc = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), "/\\");
		if ($doc !== '' && is_file($doc . '/tickets/connect.php')) {
			$cached = [
				'tickets' => $doc . '/tickets',
				'cargo' => $doc . '/cargo',
				'accounts' => $doc . '/accounts',
				'web' => $doc,
			];
			return $cached;
		}
		/* Caller may be apps/operator-shell or apps/operator-shell/includes */
		$here = __DIR__;
		$repo = is_dir(dirname($here, 2) . '/services/tickets')
			? dirname($here, 2)
			: dirname($here, 3);
		$cached = [
			'tickets' => $repo . '/services/tickets',
			'cargo' => $repo . '/services/cargo',
			'accounts' => $repo . '/services/accounting',
			'web' => $repo,
		];
		return $cached;
	}
}
