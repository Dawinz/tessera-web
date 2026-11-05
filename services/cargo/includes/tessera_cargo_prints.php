<?php
/**
 * Cargo print documents (C-P2). Same parcel and manifest rows the classic print pages used.
 */
declare(strict_types=1);

if (!function_exists('tessera_cargo_prints_esc')) {
	function tessera_cargo_prints_esc(mysqli $db, string $value): string
	{
		return $db->real_escape_string($value);
	}
}

if (!function_exists('tessera_cargo_prints_rows')) {
	/**
	 * @return list<array<string,mixed>>
	 */
	function tessera_cargo_prints_rows(mysqli $db, string $sql): array
	{
		$out = [];
		$q = $db->query($sql);
		if (!$q) {
			return [];
		}
		while ($row = $q->fetch_assoc()) {
			$out[] = $row;
		}
		return $out;
	}
}

if (!function_exists('tessera_cargo_prints_company')) {
	/**
	 * @return array{name:string,logo_url:string}
	 */
	function tessera_cargo_prints_company(): array
	{
		$tickets = dirname(__DIR__, 2) . '/tickets/includes/tessera_prints.php';
		if (is_file($tickets)) {
			require_once $tickets;
			if (function_exists('tessera_prints_company')) {
				return tessera_prints_company();
			}
		}
		return ['name' => 'Operator', 'logo_url' => ''];
	}
}

if (!function_exists('tessera_cargo_prints_doc')) {
	/**
	 * @param list<array{label:string,value:string}> $meta
	 * @param list<string> $columns
	 * @param list<list<string>> $rows
	 * @param array<string,mixed> $extra
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_doc(
		string $kind,
		string $title,
		array $meta,
		array $columns,
		array $rows,
		string $notes = '',
		array $extra = []
	): array {
		return array_merge([
			'kind' => $kind,
			'title' => $title,
			'company' => tessera_cargo_prints_company(),
			'meta' => $meta,
			'columns' => $columns,
			'rows' => $rows,
			'row_ids' => [],
			'notes' => $notes,
			'empty' => $rows === [] && $meta === [],
			'selectable' => false,
		], $extra);
	}
}

if (!function_exists('tessera_cargo_prints_detail')) {
	function tessera_cargo_prints_detail(string $stored): string
	{
		$stored = trim($stored);
		if ($stored === '') {
			return '';
		}
		$decoded = base64_decode($stored, true);
		if ($decoded !== false && $decoded !== '' && preg_match('//u', $decoded)) {
			return $decoded;
		}
		return $stored;
	}
}

if (!function_exists('tessera_cargo_prints_parcel')) {
	/**
	 * @return array<string,mixed>|null
	 */
	function tessera_cargo_prints_parcel(mysqli $db, string $code): ?array
	{
		$code = trim($code);
		if ($code === '') {
			return null;
		}
		$c = tessera_cargo_prints_esc($db, $code);
		$tenant = function_exists('tessera_sql_tenant_eq') ? ' AND ' . tessera_sql_tenant_eq() : '';
		$rows = tessera_cargo_prints_rows(
			$db,
			"SELECT code, s_name, s_surname, s_no, r_name, r_surname, r_no, account, amount, due, paid, to_be,
				bus, trip_date, store, status, detail, question, date_insert, uploader
			 FROM parcel_info WHERE code='{$c}' {$tenant} LIMIT 1"
		);
		if ($rows === []) {
			$rows = tessera_cargo_prints_rows(
				$db,
				"SELECT code, s_name, s_surname, s_no, r_name, r_surname, r_no, account, amount, due, paid, to_be,
					bus, trip_date, store, status, detail, question, date_insert, uploader
				 FROM parcel_info WHERE code='{$c}' LIMIT 1"
			);
		}
		return $rows[0] ?? null;
	}
}

if (!function_exists('tessera_cargo_prints_parcel_meta')) {
	/**
	 * @param array<string,mixed> $row
	 * @return list<array{label:string,value:string}>
	 */
	function tessera_cargo_prints_parcel_meta(array $row): array
	{
		return [
			['label' => 'Code', 'value' => (string) ($row['code'] ?? '')],
			['label' => 'Sender', 'value' => trim((string) ($row['s_name'] ?? '') . ' ' . (string) ($row['s_surname'] ?? ''))],
			['label' => 'Sender phone', 'value' => (string) ($row['s_no'] ?? '')],
			['label' => 'Receiver', 'value' => trim((string) ($row['r_name'] ?? '') . ' ' . (string) ($row['r_surname'] ?? ''))],
			['label' => 'Receiver phone', 'value' => (string) ($row['r_no'] ?? '')],
			['label' => 'Route', 'value' => (string) ($row['account'] ?? '')],
			['label' => 'Vehicle', 'value' => (string) ($row['bus'] ?? '')],
			['label' => 'Trip date', 'value' => (string) ($row['trip_date'] ?? '')],
			['label' => 'Pieces', 'value' => (string) ($row['question'] ?? '')],
			['label' => 'Amount', 'value' => (string) ($row['amount'] ?? '')],
			['label' => 'Paid', 'value' => (string) ($row['paid'] ?? '')],
			['label' => 'Due', 'value' => (string) ($row['due'] ?? '')],
			['label' => 'Store', 'value' => (string) ($row['store'] ?? '')],
			['label' => 'Status', 'value' => (string) ($row['status'] ?? '')],
		];
	}
}

if (!function_exists('tessera_cargo_prints_ticket')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_ticket(mysqli $db, string $code): array
	{
		$row = tessera_cargo_prints_parcel($db, $code);
		if ($row === null) {
			return tessera_cargo_prints_doc('ticket', 'Luggage ticket', [], ['Field', 'Value'], [], 'No luggage matched this code.');
		}
		$detail = tessera_cargo_prints_detail((string) ($row['detail'] ?? ''));
		return tessera_cargo_prints_doc(
			'ticket',
			'Luggage ticket',
			tessera_cargo_prints_parcel_meta($row),
			['Field', 'Value'],
			[['Description', $detail]]
		);
	}
}

if (!function_exists('tessera_cargo_prints_multiple')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_multiple(mysqli $db, array $query): array
	{
		$codes = [];
		$id = trim((string) ($query['id'] ?? ''));
		$code = trim((string) ($query['code'] ?? ''));
		if ($id !== '') {
			$printCode = tessera_cargo_prints_esc($db, $id);
			$tenant = function_exists('tessera_sql_tenant_eq') ? ' AND ' . tessera_sql_tenant_eq() : '';
			$rows = tessera_cargo_prints_rows($db, "SELECT code FROM parcel_info WHERE print_code='{$printCode}' {$tenant}");
			if ($rows === []) {
				$rows = tessera_cargo_prints_rows($db, "SELECT code FROM parcel_info WHERE print_code='{$printCode}'");
			}
			foreach ($rows as $row) {
				$codes[] = (string) ($row['code'] ?? '');
			}
		}
		if ($codes === [] && $code !== '') {
			$codes[] = $code;
		}
		if ($codes === [] && $id !== '') {
			$codes[] = $id;
		}
		$columns = ['Code', 'Sender', 'Receiver', 'Route', 'Amount', 'Store'];
		$table = [];
		$meta = [];
		foreach ($codes as $one) {
			$row = tessera_cargo_prints_parcel($db, $one);
			if ($row === null) {
				continue;
			}
			$table[] = [
				(string) ($row['code'] ?? ''),
				trim((string) ($row['s_name'] ?? '') . ' ' . (string) ($row['s_surname'] ?? '')),
				trim((string) ($row['r_name'] ?? '') . ' ' . (string) ($row['r_surname'] ?? '')),
				(string) ($row['account'] ?? ''),
				(string) ($row['amount'] ?? ''),
				(string) ($row['store'] ?? ''),
			];
			if (count($codes) === 1) {
				$meta = tessera_cargo_prints_parcel_meta($row);
			}
		}
		if (count($table) > 1) {
			$meta = [['label' => 'Tickets', 'value' => (string) count($table)]];
		}
		$notes = $table === [] ? 'No luggage tickets matched this print.' : '';
		return tessera_cargo_prints_doc('multiple', 'Luggage tickets', $meta, $columns, $table, $notes);
	}
}

if (!function_exists('tessera_cargo_prints_manifest')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_manifest(mysqli $db, array $query): array
	{
		$date = trim((string) ($query['date'] ?? ''));
		$name = trim((string) ($query['name'] ?? $query['bus'] ?? ''));
		if ($date === '' || $name === '') {
			return tessera_cargo_prints_doc('manifest', 'Cargo manifest', [], ['Uploader', 'Paid', 'To be'], [], 'Vehicle and date are required.');
		}
		$d = tessera_cargo_prints_esc($db, $date);
		$b = tessera_cargo_prints_esc($db, $name);
		$head = tessera_cargo_prints_rows($db, "SELECT Driver, to_pay, paid, due FROM vehicle_name WHERE date='{$d}' AND Name='{$b}' LIMIT 1");
		$meta = [
			['label' => 'Vehicle', 'value' => $name],
			['label' => 'Date', 'value' => $date],
			['label' => 'Driver', 'value' => (string) ($head[0]['Driver'] ?? '')],
			['label' => 'To pay', 'value' => (string) ($head[0]['to_pay'] ?? '')],
			['label' => 'Paid', 'value' => (string) ($head[0]['paid'] ?? '')],
			['label' => 'Due', 'value' => (string) ($head[0]['due'] ?? '')],
		];
		$groups = tessera_cargo_prints_rows(
			$db,
			"SELECT parcel_uploader, SUM(paid) AS paid, SUM(to_be) AS to_be
			 FROM manifest WHERE trip_date='{$d}' AND bus='{$b}'
			 GROUP BY parcel_uploader ORDER BY parcel_uploader ASC"
		);
		$rows = [];
		foreach ($groups as $group) {
			$rows[] = [
				(string) ($group['parcel_uploader'] ?? ''),
				(string) ($group['paid'] ?? ''),
				(string) ($group['to_be'] ?? ''),
			];
		}
		$notes = $rows === [] ? 'No cargo lines on this vehicle and date.' : '';
		return tessera_cargo_prints_doc('manifest', 'Cargo manifest', $meta, ['Uploader', 'Paid', 'To be'], $rows, $notes);
	}
}

if (!function_exists('tessera_cargo_prints_store')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_store(mysqli $db): array
	{
		$tenant = function_exists('tessera_sql_tenant_eq') ? tessera_sql_tenant_eq() . ' AND ' : '';
		$rows = tessera_cargo_prints_rows(
			$db,
			"SELECT code, s_name, s_surname, r_name, r_surname, account, amount, store, date_insert
			 FROM parcel_info WHERE {$tenant} (store='store' OR store='half_out')
			 ORDER BY date_insert DESC LIMIT 300"
		);
		if ($rows === []) {
			$rows = tessera_cargo_prints_rows(
				$db,
				"SELECT code, s_name, s_surname, r_name, r_surname, account, amount, store, date_insert
				 FROM parcel_info WHERE store='store' OR store='half_out'
				 ORDER BY date_insert DESC LIMIT 300"
			);
		}
		$table = [];
		foreach ($rows as $row) {
			$table[] = [
				(string) ($row['code'] ?? ''),
				trim((string) ($row['s_name'] ?? '') . ' ' . (string) ($row['s_surname'] ?? '')),
				trim((string) ($row['r_name'] ?? '') . ' ' . (string) ($row['r_surname'] ?? '')),
				(string) ($row['account'] ?? ''),
				(string) ($row['amount'] ?? ''),
				(string) ($row['store'] ?? ''),
			];
		}
		$notes = $table === [] ? 'The store is empty.' : count($table) . ' pieces still in store';
		return tessera_cargo_prints_doc('store', 'Store manifest', [['label' => 'In store', 'value' => (string) count($table)]], ['Code', 'Sender', 'Receiver', 'Route', 'Amount', 'Store'], $table, $notes);
	}
}

if (!function_exists('tessera_cargo_prints_receipt')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_receipt(mysqli $db, string $code): array
	{
		$row = tessera_cargo_prints_parcel($db, $code);
		if ($row === null) {
			return tessera_cargo_prints_doc('receipt', 'Checkout receipt', [], ['Field', 'Value'], [], 'No checkout matched this code.');
		}
		return tessera_cargo_prints_doc('receipt', 'Checkout receipt', tessera_cargo_prints_parcel_meta($row), ['Field', 'Value'], [
			['Description', tessera_cargo_prints_detail((string) ($row['detail'] ?? ''))],
		]);
	}
}

if (!function_exists('tessera_cargo_prints_pos')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_pos(mysqli $db, int $id): array
	{
		if ($id < 1) {
			return tessera_cargo_prints_doc('pos', 'Expense slip', [], ['Field', 'Value'], [], 'An expense id is required.');
		}
		$rows = tessera_cargo_prints_rows($db, "SELECT id, amount, detail, time, insert_date_time, date FROM expenses WHERE id={$id} LIMIT 1");
		if ($rows === []) {
			return tessera_cargo_prints_doc('pos', 'Expense slip', [], ['Field', 'Value'], [], 'Expense was not found.');
		}
		$row = $rows[0];
		$meta = [
			['label' => 'Slip', 'value' => (string) ($row['id'] ?? '')],
			['label' => 'Amount', 'value' => (string) ($row['amount'] ?? '')],
			['label' => 'Date', 'value' => (string) ($row['date'] ?? '')],
			['label' => 'Time', 'value' => (string) ($row['time'] ?? '')],
			['label' => 'Recorded', 'value' => (string) ($row['insert_date_time'] ?? '')],
		];
		return tessera_cargo_prints_doc('pos', 'Expense slip', $meta, ['Field', 'Value'], [
			['Detail', (string) ($row['detail'] ?? '')],
		]);
	}
}

if (!function_exists('tessera_cargo_prints_income')) {
	/**
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_income(mysqli $db, string $voucher): array
	{
		$voucher = trim($voucher);
		if ($voucher === '') {
			return tessera_cargo_prints_doc('income', 'Income slip', [], ['Field', 'Value'], [], 'A voucher number is required.');
		}
		$v = tessera_cargo_prints_esc($db, $voucher);
		$rows = tessera_cargo_prints_rows(
			$db,
			"SELECT v_no, income_amt, detail, time, insert_date, date, bus, trip_date FROM manifest_exp WHERE v_no='{$v}' LIMIT 1"
		);
		if ($rows === []) {
			return tessera_cargo_prints_doc('income', 'Income slip', [], ['Field', 'Value'], [], 'Income voucher was not found.');
		}
		$row = $rows[0];
		$meta = [
			['label' => 'Voucher', 'value' => (string) ($row['v_no'] ?? '')],
			['label' => 'Amount', 'value' => (string) ($row['income_amt'] ?? '')],
			['label' => 'Vehicle', 'value' => (string) ($row['bus'] ?? '')],
			['label' => 'Trip date', 'value' => (string) ($row['trip_date'] ?? '')],
			['label' => 'Date', 'value' => (string) ($row['date'] ?? '')],
			['label' => 'Time', 'value' => (string) ($row['time'] ?? '')],
		];
		return tessera_cargo_prints_doc('income', 'Income slip', $meta, ['Field', 'Value'], [
			['Detail', (string) ($row['detail'] ?? '')],
		]);
	}
}

if (!function_exists('tessera_cargo_prints_document')) {
	/**
	 * @param array<string,mixed> $query
	 * @return array<string,mixed>
	 */
	function tessera_cargo_prints_document(mysqli $db, string $kind, array $query): array
	{
		$kind = strtolower(trim($kind));
		return match ($kind) {
			'ticket' => tessera_cargo_prints_ticket($db, (string) ($query['id'] ?? $query['code'] ?? '')),
			'multiple' => tessera_cargo_prints_multiple($db, $query),
			'manifest' => tessera_cargo_prints_manifest($db, $query),
			'store' => tessera_cargo_prints_store($db),
			'receipt' => tessera_cargo_prints_receipt($db, (string) ($query['code'] ?? $query['id'] ?? '')),
			'pos' => tessera_cargo_prints_pos($db, (int) ($query['id'] ?? 0)),
			'income' => tessera_cargo_prints_income($db, (string) ($query['v_no'] ?? $query['id'] ?? '')),
			default => tessera_cargo_prints_doc($kind, 'Print', [], [], [], 'Unknown print.'),
		};
	}
}
