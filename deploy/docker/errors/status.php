<?php
/**
 * Branded HTTP status page for Tessera (Apache ErrorDocument target).
 * Query: ?code=404  — or set via REDIRECT_STATUS.
 */
declare(strict_types=1);

$code = 500;
if (isset($_GET['code']) && ctype_digit((string) $_GET['code'])) {
    $code = (int) $_GET['code'];
} elseif (!empty($_SERVER['REDIRECT_STATUS']) && ctype_digit((string) $_SERVER['REDIRECT_STATUS'])) {
    $code = (int) $_SERVER['REDIRECT_STATUS'];
}
if ($code < 400 || $code > 599) {
    $code = 500;
}

$helpers = [
    __DIR__ . '/tessera_states.php', // next to this file when synced to deploy/docker/errors/
    dirname(__DIR__) . '/php/tessera_states.php', // package source layout
    '/var/www/html/tickets/includes/tessera_states.php',
];
$loaded = false;
foreach ($helpers as $path) {
    if (is_file($path)) {
        require_once $path;
        $loaded = true;
        break;
    }
}

http_response_code($code);
$assetCss = '/tickets/assets/tessera-states/css/tessera-states.css?v=20260803';
$assetJs = '/tickets/assets/tessera-states/js/tessera-states.js?v=20260803';
$home = '/';
$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
if (str_contains($host, 'console.')) {
    $home = '/control/';
} elseif (isset($_SERVER['REQUEST_URI'])) {
    $uri = (string) $_SERVER['REQUEST_URI'];
    if (str_starts_with($uri, '/cargo')) {
        $home = '/cargo/';
    } elseif (str_starts_with($uri, '/accounts')) {
        $home = '/accounts/';
    } elseif (str_starts_with($uri, '/tickets')) {
        $home = '/tickets/';
    } elseif (str_starts_with($uri, '/control')) {
        $home = '/control/';
    } elseif (str_starts_with($uri, '/settings')) {
        $home = '/settings/';
    }
}

$title = 'Tessera';
$body = $loaded
    ? tessera_http_status_page($code, [
        'home_href' => $home,
        'login_href' => '/login/',
    ])
    : '<div class="ts-page"><div class="ts-state ts-state--danger"><h3 class="ts-state__title">Error ' . (int) $code . '</h3><p class="ts-state__body">Something went wrong.</p><div class="ts-state__actions"><a class="btn btn-primary" href="' . htmlspecialchars($home, ENT_QUOTES, 'UTF-8') . '">Go home</a></div></div></div>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($title . ' · ' . $code, ENT_QUOTES, 'UTF-8'); ?></title>
  <link href="/tickets/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo htmlspecialchars($assetCss, ENT_QUOTES, 'UTF-8'); ?>" rel="stylesheet">
  <style>
    body { margin: 0; font-family: Roboto, system-ui, sans-serif; background: #f5f7f9; color: #0c1f2e; }
    .btn { display: inline-block; padding: .45rem 1rem; border-radius: .4rem; text-decoration: none; border: 1px solid transparent; }
    .btn-primary { background: #1f7a6c; color: #fff; }
    .btn-secondary { background: #fff; color: #03314d; border-color: #e4ebf0; }
  </style>
</head>
<body>
<?php echo $body; ?>
<script defer src="<?php echo htmlspecialchars($assetJs, ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>
