<?php
require_once __DIR__ . '/includes/sr_env.php';
$connect = sr_pdo(getenv('SR_CARGO_DB') ?: 'cargo');
