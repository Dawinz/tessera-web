<?php
/**
 * Legacy name for cargo DB (sr_up). Reports and cross-module queries use this include.
 * Prefer connect_cargo.php in new code - both resolve to the tenant cargo database.
 */
require_once __DIR__ . '/connect_cargo.php';
// $db_up is set by connect_cargo.php for parity with legacy account templates.
