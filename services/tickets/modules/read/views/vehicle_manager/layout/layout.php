<?php

include_once __DIR__ . '/../../../../connection/general_db.php';
include_once __DIR__ . '/../../../general/Read.php';

header('Content-Type: application/json');

$vehicle_id = (int) ($_GET['vehicle_id'] ?? $_POST['vehicle_id'] ?? 0);

if ($vehicle_id < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No vehicle was requested.']);
    exit;
}

if (!isset($pdo_general) || !($pdo_general instanceof PDO)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$select = ['id', 'vehicle_type_id', 'vehcile_category_id', 'name', 'location', 'seats', 'layout', 'detail'];
$where = ['id' => $vehicle_id];
$vehicle_record = new Read(['select' => $select, 'pdo' => $pdo_general, 'where' => $where, 'table' => "vehicle", 'order' => "", 'limit' => "LIMIT 1"]);

if ($vehicle_record->available_row == 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'This vehicle could not be found.']);
    exit;
}

$row = $vehicle_record->rows[0];

$type_name = 'Normal Bus';
$select = ['id', 'name'];
$where = ['id' => (int) $row['vehicle_type_id']];
$vehicle_type = new Read(['select' => $select, 'pdo' => $pdo_general, 'where' => $where, 'table' => "vehicle_type", 'order' => "", 'limit' => "LIMIT 1"]);
if ($vehicle_type->available_row != 0) {
    $type_name = $vehicle_type->rows[0]['name'];
}

$category_name = '';
$category_rating = 0;
$select = ['id', 'name', 'rating'];
$where = ['id' => (int) $row['vehcile_category_id']];
$vehicle_category = new Read(['select' => $select, 'pdo' => $pdo_general, 'where' => $where, 'table' => "vehcile_category", 'order' => "", 'limit' => "LIMIT 1"]);
if ($vehicle_category->available_row != 0) {
    $category_name = $vehicle_category->rows[0]['name'];
    $category_rating = (float) $vehicle_category->rows[0]['rating'];
}

$attributes = [];
$doors = [];
try {
    $select = ['id', 'attr_type', 'seat_no', 'row_no', 'col_no', 'attr_value', 'notes', 'status'];
    $where = ['vehicle_id' => $vehicle_id];
    $attribute_records = new Read(['select' => $select, 'pdo' => $pdo_general, 'where' => $where, 'table' => "vehicle_attribute", 'order' => "", 'limit' => ""]);
    foreach ($attribute_records->rows as $attr) {
        if (($attr['status'] ?? 'active') !== 'active') {
            continue;
        }
        $attributes[] = [
            'id'         => (int) $attr['id'],
            'attr_type'  => $attr['attr_type'],
            'seat_no'    => $attr['seat_no'],
            'row_no'     => $attr['row_no'] === null ? null : (int) $attr['row_no'],
            'col_no'     => $attr['col_no'] === null ? null : (int) $attr['col_no'],
            'attr_value' => $attr['attr_value'],
            'notes'      => $attr['notes'],
        ];
        if ($attr['attr_type'] === 'door' && $attr['row_no'] !== null && $attr['col_no'] !== null) {
            $doors[] = (int) $attr['row_no'] . ',' . (int) $attr['col_no'];
        }
    }
} catch (PDOException $e) {
    $attributes = [];
    $doors = [];
}

$layout = trim((string) $row['layout']);
if ($layout === '') {
    $layout = 'ii-ii';
}

echo json_encode([
    'success'    => true,
    'vehicle'    => [
        'id'                  => (int) $row['id'],
        'vehicle_type_id'     => (int) $row['vehicle_type_id'],
        'type'                => $type_name,
        'vehcile_category_id' => (int) $row['vehcile_category_id'],
        'category'            => $category_name,
        'rating'              => $category_rating,
        'name'                => $row['name'],
        'location'            => $row['location'],
        'seats'               => (int) $row['seats'],
        'layout'              => $layout,
        'detail'              => $row['detail'],
        'driver'              => $row['driver'] ?? '',
        'conductor'           => $row['conductor'] ?? '',
        'status'              => $row['status'] ?? 'active',
    ],
    'attributes' => $attributes,
    'doors'      => $doors,
]);
