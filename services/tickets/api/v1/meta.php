<?php
require_once __DIR__ . '/_bootstrap.php';
$key = v1_require_key($db);
v1_json([
	'ok' => true,
	'api' => 'tessera-operator',
	'version' => 'v1',
	'tenant_id' => (int) $key['tenant_id'],
	'scopes' => $key['scopes'],
	'endpoints' => [
		'GET /tickets/api/v1/meta.php',
		'GET /tickets/api/v1/trips.php?from=&to=&date=',
		'GET /tickets/api/v1/booking.php?ref=&phone=',
		'GET /tickets/api/v1/cargo_track.php?code=',
		'GET /tickets/api/v1/export.php?kind=bookings|cargo&format=json|csv',
	],
	'payment' => [
		'note' => 'Rails: mpesa, tigopesa, airtel, halopesa, cash, pos_cash. Status is provider-neutral (pending|paid|failed).',
	],
]);
