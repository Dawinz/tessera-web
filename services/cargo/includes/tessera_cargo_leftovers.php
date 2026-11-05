<?php
/**
 * Cargo P3 leftovers: adaptor ledger (read) and cash-in-hand history (read).
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_adaptor_list')) {
	/**
	 * Day's luggage money lines. Replaces the classic adaptor grid that was
	 * hardcoded to one username. Opening a line goes to Update luggage.
	 *
	 * @return array{items:list<array<string,mixed>>,total:int,date:string,paid:float,to_be:float,amount:float}
	 */
	function tessera_cargo_adaptor_list(mysqli $db, string $username, string $date, string $q): array
	{
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
			$date = date('Y-m-d');
		}
		require_once __DIR__ . '/tessera_cargo_luggages_list.php';
		$payload = tessera_cargo_luggages_list($db, $username, [
			'date_from' => $date,
			'date_to' => $date,
			'q' => $q,
		], 1, 50);

		$paid = 0.0;
		$toBe = 0.0;
		$amount = 0.0;
		$items = [];
		foreach ($payload['items'] as $row) {
			$paid += (float) ($row['paid'] ?? 0);
			$toBe += (float) ($row['to_be'] ?? 0);
			$amount += (float) ($row['amount'] ?? 0);
			$id = (int) ($row['id'] ?? 0);
			$items[] = [
				'id' => $id,
				'code' => (string) ($row['code'] ?? ''),
				'sender' => (string) ($row['sender_name'] ?? ''),
				'receiver' => (string) ($row['receiver_name'] ?? ''),
				'route' => (string) ($row['route'] ?? ''),
				'paid' => (float) ($row['paid'] ?? 0),
				'to_be' => (float) ($row['to_be'] ?? 0),
				'amount' => (float) ($row['amount'] ?? 0),
				'detail' => (string) ($row['detail'] ?? ''),
				'edit_url' => '/operator/app.php?module=cargo&page=update_luggage.php&u=' . $id,
			];
		}

		return [
			'items' => $items,
			'total' => (int) ($payload['total'] ?? count($items)),
			'date' => $date,
			'paid' => $paid,
			'to_be' => $toBe,
			'amount' => $amount,
		];
	}
}

if (!function_exists('tessera_cargo_cash_hand_list')) {
	/**
	 * Recent cargo office-float rows. Spending them is Accounts daily.
	 *
	 * @return array{items:list<array<string,mixed>>,available:bool,note:string}
	 */
	function tessera_cargo_cash_hand_list(mysqli $db): array
	{
		$note = 'Record spending on Accounts daily expenses. This list is the cargo cash-in-hand history.';
		$q = $db->query('SELECT * FROM office_expenses ORDER BY id DESC LIMIT 30');
		if (!$q) {
			return [
				'items' => [],
				'available' => false,
				'note' => 'Cash in hand is kept with Accounts daily expenses.',
			];
		}
		$items = [];
		while ($row = $q->fetch_assoc()) {
			$opening = (float) ($row['opening_stock'] ?? 0);
			$amount = (float) ($row['amount'] ?? 0);
			$items[] = [
				'id' => (int) ($row['id'] ?? 0),
				'authorizer' => (string) ($row['authorizer'] ?? $row['username'] ?? ''),
				'date' => (string) ($row['open_date'] ?? $row['date'] ?? ''),
				'amount' => $opening != 0.0 ? $opening : $amount,
				'remain' => (float) ($row['amount_remain'] ?? 0),
				'voucher' => (string) ($row['stock_no'] ?? ''),
				'time' => (string) ($row['time'] ?? ''),
			];
		}
		return [
			'items' => $items,
			'available' => true,
			'note' => $note,
		];
	}
}
