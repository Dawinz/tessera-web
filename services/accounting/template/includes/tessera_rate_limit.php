<?php
/**
 * Simple file-based login rate limit (per IP).
 */
if (!function_exists('tessera_rate_limit_allow')) {
	function tessera_rate_limit_allow($bucket, $max = 20, $windowSec = 300) {
		$dir = sys_get_temp_dir() . '/tessera_rl';
		if (!is_dir($dir)) {
			@mkdir($dir, 0700, true);
		}
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
		$ip = trim(explode(',', (string) $ip)[0]);
		$key = preg_replace('/[^a-zA-Z0-9._-]/', '_', $bucket . '_' . $ip);
		$file = $dir . '/' . $key . '.json';
		$now = time();
		$data = ['start' => $now, 'count' => 0];
		if (is_file($file)) {
			$raw = @file_get_contents($file);
			$decoded = $raw ? json_decode($raw, true) : null;
			if (is_array($decoded)) {
				$data = $decoded;
			}
		}
		if (($now - (int) $data['start']) > $windowSec) {
			$data = ['start' => $now, 'count' => 0];
		}
		$data['count'] = (int) $data['count'] + 1;
		@file_put_contents($file, json_encode($data), LOCK_EX);
		return $data['count'] <= $max;
	}
}
