<?php
/**
 * Per-operator over-the-air channel. Store binaries stay on the flavor package id.
 * This endpoint only tells an installed app which channel and revision to pull.
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$flavor = strtolower(trim((string) ($_GET['flavor'] ?? 'sr_classic')));
$channels = [
    'sr_classic' => [
        'slug' => 'sr-classic',
        'channel' => 'sr-classic',
        'customer_application_id' => 'co.tz.srclassicoach.srclassiccaoch',
        'agent_application_id' => 'co.srclassic.sr_classic_admin',
    ],
    'sr-classic' => [
        'slug' => 'sr-classic',
        'channel' => 'sr-classic',
        'customer_application_id' => 'co.tz.srclassicoach.srclassiccaoch',
        'agent_application_id' => 'co.srclassic.sr_classic_admin',
    ],
    'pilot_coach' => [
        'slug' => 'pilot-coach',
        'channel' => 'pilot-coach',
        'customer_application_id' => 'co.tz.pilotcoach.customer',
        'agent_application_id' => 'co.tz.pilotcoach.agent',
    ],
    'pilot-coach' => [
        'slug' => 'pilot-coach',
        'channel' => 'pilot-coach',
        'customer_application_id' => 'co.tz.pilotcoach.customer',
        'agent_application_id' => 'co.tz.pilotcoach.agent',
    ],
];

if (!isset($channels[$flavor])) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'error' => 'unknown_flavor']);
    exit;
}

$row = $channels[$flavor];
echo json_encode([
    'ok' => true,
    'flavor' => $flavor,
    'slug' => $row['slug'],
    'channel' => $row['channel'],
    'ota_revision' => '2026.10.26',
    'min_native_version' => '1.0.0',
    'customer_application_id' => $row['customer_application_id'],
    'agent_application_id' => $row['agent_application_id'],
]);
