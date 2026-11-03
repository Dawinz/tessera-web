<?php

/**
 * Dedicated schedule times for boarding and drop-off points.
 *
 * Points stay in route_depature_points / route_arrival_points.
 * Times live here so a point can exist before a clock is set, and so
 * report_time / departure_time / arrival_time are real TIME values.
 *
 * Requires Read.php, Create.php and Update.php to be loaded first.
 */

if (!defined('ROUTE_DEPARTURE_TIMES_TABLE')) {
    define('ROUTE_DEPARTURE_TIMES_TABLE', 'route_departure_times');
}

if (!defined('ROUTE_ARRIVAL_TIMES_TABLE')) {
    define('ROUTE_ARRIVAL_TIMES_TABLE', 'route_arrival_times');
}

function ensure_route_point_tables(PDO $pdo)
{
    static $pointsReady = false;

    if ($pointsReady) {
        return;
    }

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS `route_depature_points` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `route_id` VARCHAR(100) NOT NULL,
            `name` VARCHAR(200) NOT NULL,
            `company_id` VARCHAR(100) NOT NULL DEFAULT \'1\',
            PRIMARY KEY (`id`),
            KEY `idx_route_dep_points` (`route_id`, `company_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS `route_arrival_points` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `route_id` VARCHAR(100) NOT NULL,
            `name` VARCHAR(200) NOT NULL,
            `company_id` VARCHAR(100) NOT NULL DEFAULT \'1\',
            PRIMARY KEY (`id`),
            KEY `idx_route_arr_points` (`route_id`, `company_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS `route_location_points` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(200) NOT NULL,
            `location` VARCHAR(200) NOT NULL DEFAULT \'\',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $pointsReady = true;
}

function ensure_route_point_time_tables(PDO $pdo)
{
    static $ready = false;

    if ($ready) {
        return;
    }

    ensure_route_point_tables($pdo);

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS `' . ROUTE_DEPARTURE_TIMES_TABLE . '` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `route_id` VARCHAR(100) NOT NULL,
            `point_id` INT(11) NOT NULL,
            `point_name` VARCHAR(200) NOT NULL,
            `report_time` TIME NULL DEFAULT NULL,
            `departure_time` TIME NULL DEFAULT NULL,
            `company_id` VARCHAR(100) NOT NULL,
            `uploader` VARCHAR(100) NOT NULL DEFAULT \'\',
            PRIMARY KEY (`id`),
            UNIQUE KEY `route_point` (`route_id`, `point_id`, `company_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS `' . ROUTE_ARRIVAL_TIMES_TABLE . '` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `route_id` VARCHAR(100) NOT NULL,
            `point_id` INT(11) NOT NULL,
            `point_name` VARCHAR(200) NOT NULL,
            `arrival_time` TIME NULL DEFAULT NULL,
            `company_id` VARCHAR(100) NOT NULL,
            `uploader` VARCHAR(100) NOT NULL DEFAULT \'\',
            PRIMARY KEY (`id`),
            UNIQUE KEY `route_point` (`route_id`, `point_id`, `company_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8'
    );

    $ready = true;
}

/**
 * Insert or update the clock for one boarding or drop-off point.
 *
 * @param string $kind departure|arrival
 *
 * @return array{status:int, body:array}
 */
function upsert_route_point_time(PDO $pdo, $kind, array $input, $uploader)
{
    ensure_route_point_time_tables($pdo);

    if ($kind === 'departure') {
        $table = ROUTE_DEPARTURE_TIMES_TABLE;
        $data = [
            'route_id'       => $input['route_id'],
            'point_id'       => $input['point_id'],
            'point_name'     => $input['point_name'],
            'report_time'    => $input['report_time'],
            'departure_time' => $input['point_time'],
            'company_id'     => $input['company_id'],
            'uploader'       => $uploader,
        ];
        $update = [
            'point_name'     => $input['point_name'],
            'report_time'    => $input['report_time'],
            'departure_time' => $input['point_time'],
            'uploader'       => $uploader,
        ];
    } else {
        $table = ROUTE_ARRIVAL_TIMES_TABLE;
        $data = [
            'route_id'     => $input['route_id'],
            'point_id'     => $input['point_id'],
            'point_name'   => $input['point_name'],
            'arrival_time' => $input['point_time'],
            'company_id'   => $input['company_id'],
            'uploader'     => $uploader,
        ];
        $update = [
            'point_name'   => $input['point_name'],
            'arrival_time' => $input['point_time'],
            'uploader'     => $uploader,
        ];
    }

    $existing = new Read([
        'select' => ['id'],
        'pdo'    => $pdo,
        'where'  => [
            'route_id'   => $input['route_id'],
            'point_id'   => $input['point_id'],
            'company_id' => $input['company_id'],
        ],
        'table'  => $table,
        'order'  => '',
        'limit'  => 'LIMIT 1',
    ]);

    if ($existing->available_row != 0) {
        new Update($pdo, $update, $table, ['id' => $existing->rows[0]['id']]);
    } else {
        new Create($pdo, $data, $table);
    }

    return [
        'status' => 200,
        'body'   => [
            'success' => true,
            'message' => 'Time updated.',
        ],
    ];
}
