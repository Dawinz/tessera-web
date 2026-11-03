<?php
require_once dirname(__DIR__) . '/includes/sr_env.php';
$conn = sr_mysqli(getenv('SR_CARGO_DB') ?: 'cargo');
