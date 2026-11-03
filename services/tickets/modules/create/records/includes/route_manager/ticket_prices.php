<?php

/**
 * Route ticket price logic.
 *
 * Collects and validates the "Ticket Price" step of the route wizard
 * (the vehicle_type[] / fare_amount[] / fare_currency[] rows) and stores one
 * row per vehicle class in the `route_fair` table. Included by
 * modules/create/records/route_manager.php, which stays responsible for the
 * request/response handling only.
 *
 * Requires Read.php and Create.php to be loaded first.
 */

define('ROUTE_TICKET_PRICES_TABLE', 'route_fair');

/**
 * Read the ticket price rows out of the submitted form.
 *
 * @param array $post Usually $_POST.
 *
 * @return array List of rows keyed by vehicle_type, fare_amount, fare_currency.
 */
function collect_ticket_prices(array $post)
{
    $vehicle_types = $post['vehicle_type'] ?? [];
    $fare_amounts = $post['fare_amount'] ?? [];
    $fare_currencies = $post['fare_currency'] ?? [];

    if (!is_array($vehicle_types)) {
        $vehicle_types = [$vehicle_types];
    }

    if (!is_array($fare_amounts)) {
        $fare_amounts = [$fare_amounts];
    }

    if (!is_array($fare_currencies)) {
        $fare_currencies = [$fare_currencies];
    }

    $prices = [];

    foreach ($vehicle_types as $index => $vehicle_type) {
        $vehicle_type = trim((string) $vehicle_type);
        $fare_amount = trim((string) ($fare_amounts[$index] ?? ''));
        $fare_currency = trim((string) ($fare_currencies[$index] ?? ''));

        if ($vehicle_type === 'Choose Vehicle Type') {
            $vehicle_type = '';
        }

        if ($fare_currency === 'Choose Currency') {
            $fare_currency = '';
        }

        // Skip rows the user left completely untouched.
        if ($vehicle_type === '' && $fare_amount === '' && $fare_currency === '') {
            continue;
        }

        $prices[] = [
            'vehicle_type'  => $vehicle_type,
            'fare_amount'   => normalize_fare_amount($fare_amount),
            'fare_currency' => $fare_currency,
        ];
    }

    return $prices;
}

/**
 * Turn a typed amount such as "1,500.50" into "1500.50".
 *
 * @return string
 */
function normalize_fare_amount($fare_amount)
{
    return str_replace(',', '', trim((string) $fare_amount));
}

/**
 * Check the Ticket Price step values.
 *
 * @param array $prices Rows from collect_ticket_prices().
 *
 * @return array Human readable messages, empty when the input is valid.
 */
function validate_ticket_prices(array $prices)
{
    $errors = [];

    if (empty($prices)) {
        $errors[] = 'Please add at least one ticket price.';

        return $errors;
    }

    $seen_vehicle_types = [];

    foreach ($prices as $price) {
        if ($price['vehicle_type'] === '') {
            $errors[] = 'Please choose a vehicle type for every ticket price.';
        } elseif (in_array($price['vehicle_type'], $seen_vehicle_types, true)) {
            $errors[] = 'Each vehicle type can only have one ticket price.';
        } else {
            $seen_vehicle_types[] = $price['vehicle_type'];
        }

        if ($price['fare_amount'] === ''
            || !is_numeric($price['fare_amount'])
            || (float) $price['fare_amount'] <= 0
        ) {
            $errors[] = 'Please enter a valid fare amount for every ticket price.';
        }

        if ($price['fare_currency'] === '') {
            $errors[] = 'Please choose a currency for every ticket price.';
        }
    }

    return array_values(array_unique($errors));
}

/**
 * Read the readable name of a vehicle class from transport_general.
 *
 * @param PDO $pdo_general Connection to the shared transport_general database.
 *
 * @return string|null Null when the id does not exist.
 */
function get_vehicle_class_name(PDO $pdo_general, $vehicle_type_id)
{
    $vehicle_class = new Read([
        'select' => ['name'],
        'pdo'    => $pdo_general,
        'where'  => ['id' => $vehicle_type_id],
        'table'  => 'vehcile_category',
        'order'  => '',
        'limit'  => '',
    ]);

    return $vehicle_class->available_row != 0 ? $vehicle_class->rows[0]['name'] : null;
}

/**
 * Read the readable name of a currency.
 *
 * @param PDO $pdo_general Connection to the shared transport_general database.
 *
 * @return string|null Null when the id does not exist.
 */
function get_currency_name(PDO $pdo_general, $currency_id)
{
    $currency = new Read([
        'select' => ['name'],
        'pdo'    => $pdo_general,
        'where'  => ['id' => $currency_id],
        'table'  => 'currency',
        'order'  => '',
        'limit'  => '',
    ]);

    return $currency->available_row != 0 ? $currency->rows[0]['name'] : null;
}

/**
 * @return bool
 */
function ticket_price_exists(PDO $pdo, $route_id, $class, $company_id)
{
    $existing_price = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => [
            'route_id'   => $route_id,
            'class'      => $class,
            'company_id' => $company_id,
        ],
        'table'  => ROUTE_TICKET_PRICES_TABLE,
        'order'  => '',
        'limit'  => '',
    ]);

    return $existing_price->available_row != 0;
}

/**
 * Store the ticket prices of a route.
 *
 * @param PDO    $pdo_general Connection used to resolve the currency name.
 * @param string $route_id    The route_id of the route they belong to.
 * @param array  $prices      Validated rows from collect_ticket_prices().
 * @param int    $company_id  The company the route belongs to.
 *
 * @return array{status:int, body:array} Ready to be sent back to the browser.
 */
function create_ticket_prices(PDO $pdo, PDO $pdo_general, $route_id, array $prices, $company_id)
{
    $rows = [];

    foreach ($prices as $price) {
        $class = get_vehicle_class_name($pdo_general, $price['vehicle_type']);

        if ($class === null) {
            return [
                'status' => 422,
                'body'   => [
                    'success' => false,
                    'message' => 'One of the selected vehicle types could not be found.',
                ],
            ];
        }

        $currency = get_currency_name($pdo_general, $price['fare_currency']);

        if ($currency === null) {
            return [
                'status' => 422,
                'body'   => [
                    'success' => false,
                    'message' => 'One of the selected currencies could not be found.',
                ],
            ];
        }

        $rows[] = [
            'class'       => $class,
            'class_id'    => $price['vehicle_type'],
            'amount'      => $price['fare_amount'],
            'currency'    => $currency,
            'currency_id' => $price['fare_currency'],
        ];
    }

    foreach ($rows as $index => $row) {
        if (ticket_price_exists($pdo, $route_id, $row['class'], $company_id)) {
            continue;
        }

        $data = [
            'class'       => $row['class'],
            'class_id'    => $row['class_id'],
            'amount'      => $row['amount'],
            'currency'    => $row['currency'],
            'currency_id' => $row['currency_id'],
            'route_id'    => $route_id,
            'company_id'  => $company_id,
            'state'       => $index === 0 ? 'default' : '',
        ];

        new Create($pdo, $data, ROUTE_TICKET_PRICES_TABLE);
    }

    return [
        'status' => 200,
        'body'   => [
            'success' => true,
            'data'    => 'added successfully',
        ],
    ];
}
