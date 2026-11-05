<?php
/**
 * Tessera operator application states — PHP render helpers.
 *
 * Usage:
 *   require_once __DIR__ . '/tessera_states.php';
 *   echo tessera_states_assets('/tickets/assets/tessera-states');
 *   echo tessera_state_empty(['title' => 'No bookings yet', 'body' => 'Book your first trip.', 'cta' => ['label' => 'Book now', 'href' => '/tickets/']]);
 */

declare(strict_types=1);

if (!function_exists('tessera_states_h')) {
    function tessera_states_h(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('tessera_states_assets')) {
    /**
     * @param string $baseUrl URL prefix to synced css/js (no trailing slash)
     */
    function tessera_states_assets(string $baseUrl, string $version = '1'): string
    {
        $baseUrl = rtrim($baseUrl, '/');
        $v = rawurlencode($version);
        return <<<HTML
<link rel="stylesheet" href="{$baseUrl}/css/tessera-states.css?v={$v}">
<script defer src="{$baseUrl}/js/tessera-states.js?v={$v}"></script>
HTML;
    }
}

if (!function_exists('tessera_state_block')) {
    /**
     * @param array{
     *   kind?:string,tone?:string,icon?:string,title?:string,body?:string,
     *   flush?:bool,actions?:list<array{label:string,href?:string,class?:string}>
     * } $opts
     */
    function tessera_state_block(array $opts): string
    {
        $kind = tessera_states_h((string) ($opts['kind'] ?? 'empty'));
        $tone = tessera_states_h((string) ($opts['tone'] ?? 'info'));
        $icon = tessera_states_h((string) ($opts['icon'] ?? '📭'));
        $title = tessera_states_h((string) ($opts['title'] ?? 'Nothing here yet'));
        $body = (string) ($opts['body'] ?? '');
        $flush = !empty($opts['flush']) ? ' ts-state--flush' : '';
        $actionsHtml = '';
        foreach (($opts['actions'] ?? []) as $action) {
            $label = tessera_states_h((string) ($action['label'] ?? 'Continue'));
            $class = tessera_states_h((string) ($action['class'] ?? 'btn-primary'));
            if (!empty($action['href'])) {
                $href = tessera_states_h((string) $action['href']);
                $actionsHtml .= '<a class="btn ' . $class . '" href="' . $href . '">' . $label . '</a>';
            } else {
                $actionsHtml .= '<button type="button" class="btn ' . $class . '">' . $label . '</button>';
            }
        }
        if ($actionsHtml !== '') {
            $actionsHtml = '<div class="ts-state__actions">' . $actionsHtml . '</div>';
        }
        $bodyHtml = $body !== '' ? '<p class="ts-state__body">' . tessera_states_h($body) . '</p>' : '';

        return <<<HTML
<div class="ts-state ts-state--{$tone}{$flush}" data-ts-state="{$kind}">
  <div class="ts-state__icon" aria-hidden="true">{$icon}</div>
  <h3 class="ts-state__title">{$title}</h3>
  {$bodyHtml}
  {$actionsHtml}
</div>
HTML;
    }
}

if (!function_exists('tessera_state_empty')) {
    function tessera_state_empty(array $opts = []): string
    {
        $cta = $opts['cta'] ?? null;
        $actions = $opts['actions'] ?? [];
        if ($cta && empty($actions)) {
            $actions[] = [
                'label' => (string) ($cta['label'] ?? 'Get started'),
                'href' => (string) ($cta['href'] ?? '#'),
                'class' => (string) ($cta['class'] ?? 'btn-primary'),
            ];
        }
        return tessera_state_block([
            'kind' => 'empty',
            'tone' => 'info',
            'icon' => $opts['icon'] ?? '📭',
            'title' => $opts['title'] ?? 'No records yet',
            'body' => $opts['body'] ?? 'When data appears here, it will show up in this list.',
            'flush' => !empty($opts['flush']),
            'actions' => $actions,
        ]);
    }
}

if (!function_exists('tessera_state_error')) {
    function tessera_state_error(array $opts = []): string
    {
        $actions = $opts['actions'] ?? [[
            'label' => (string) ($opts['retry_label'] ?? 'Retry'),
            'href' => (string) ($opts['retry_href'] ?? 'javascript:location.reload()'),
            'class' => 'btn-primary',
        ]];
        return tessera_state_block([
            'kind' => $opts['kind'] ?? 'error',
            'tone' => 'danger',
            'icon' => $opts['icon'] ?? '⚠',
            'title' => $opts['title'] ?? 'Something went wrong',
            'body' => $opts['body'] ?? 'Please try again. If this keeps happening, contact support.',
            'flush' => !empty($opts['flush']),
            'actions' => $actions,
        ]);
    }
}

if (!function_exists('tessera_state_success')) {
    function tessera_state_success(array $opts = []): string
    {
        return tessera_state_block([
            'kind' => 'success',
            'tone' => 'ok',
            'icon' => $opts['icon'] ?? '✓',
            'title' => $opts['title'] ?? 'Done',
            'body' => $opts['body'] ?? '',
            'flush' => !empty($opts['flush']),
            'actions' => $opts['actions'] ?? [],
        ]);
    }
}

if (!function_exists('tessera_state_skeleton')) {
    function tessera_state_skeleton(int $rows = 4): string
    {
        $lines = '';
        for ($i = 0; $i < $rows; $i++) {
            $mod = $i % 3 === 0 ? 'sm' : ($i % 2 ? 'md' : 'lg');
            $lines .= '<div class="ts-skeleton__line ts-skeleton__line--' . $mod . '"></div>';
        }
        return '<div class="ts-skeleton" aria-busy="true" aria-label="Loading">' . $lines . '<div class="ts-skeleton__block"></div></div>';
    }
}

if (!function_exists('tessera_http_status_page')) {
    /**
     * Full branded HTTP status page body (caller supplies doctype/html shell if needed).
     */
    function tessera_http_status_page(int $code, array $opts = []): string
    {
        $presets = [
            400 => ['title' => 'Bad request', 'body' => 'The request could not be understood.', 'icon' => '⚠', 'tone' => 'danger'],
            401 => ['title' => 'Session expired', 'body' => 'Sign in again to continue.', 'icon' => '🔒', 'tone' => 'warn'],
            403 => ['title' => "You don't have permission", 'body' => 'Ask an administrator if you need access.', 'icon' => '⛔', 'tone' => 'warn'],
            404 => ['title' => 'Page not found', 'body' => 'This link may be broken or the page may have moved.', 'icon' => '🧭', 'tone' => 'info'],
            405 => ['title' => 'Method not allowed', 'body' => 'This action is not supported here.', 'icon' => '⚠', 'tone' => 'danger'],
            408 => ['title' => 'Request timed out', 'body' => 'The server took too long to respond.', 'icon' => '🌐', 'tone' => 'danger'],
            409 => ['title' => 'Conflict', 'body' => 'This action conflicts with the current state.', 'icon' => '⚠', 'tone' => 'warn'],
            410 => ['title' => 'Gone', 'body' => 'This resource is no longer available.', 'icon' => '🧭', 'tone' => 'info'],
            413 => ['title' => 'Payload too large', 'body' => 'The upload or request is too large.', 'icon' => '⚠', 'tone' => 'danger'],
            415 => ['title' => 'Unsupported media type', 'body' => 'This file type is not supported.', 'icon' => '⚠', 'tone' => 'danger'],
            422 => ['title' => 'Validation error', 'body' => 'Please check the form and try again.', 'icon' => '⚠', 'tone' => 'warn'],
            429 => ['title' => 'Too many requests', 'body' => 'Wait a moment, then try again.', 'icon' => '⚠', 'tone' => 'warn'],
            500 => ['title' => 'Server error', 'body' => 'Something failed on our side.', 'icon' => '⚠', 'tone' => 'danger'],
            502 => ['title' => 'Bad gateway', 'body' => 'Upstream service is unavailable.', 'icon' => '⚠', 'tone' => 'danger'],
            503 => ['title' => 'Service unavailable', 'body' => 'Tessera may be under maintenance.', 'icon' => '🛠', 'tone' => 'warn'],
            504 => ['title' => 'Gateway timeout', 'body' => 'The upstream service timed out.', 'icon' => '🌐', 'tone' => 'danger'],
        ];
        $preset = $presets[$code] ?? [
            'title' => 'Unexpected error',
            'body' => 'Error code ' . $code . '.',
            'icon' => '⚠',
            'tone' => 'danger',
        ];
        $home = (string) ($opts['home_href'] ?? '/');
        $login = (string) ($opts['login_href'] ?? '/login/');
        $actions = $opts['actions'] ?? [];
        if ($actions === []) {
            if ($code === 401) {
                $actions[] = ['label' => 'Sign in', 'href' => $login, 'class' => 'btn-primary'];
            } else {
                $actions[] = ['label' => 'Go home', 'href' => $home, 'class' => 'btn-primary'];
            }
            if (in_array($code, [408, 429, 500, 502, 503, 504], true)) {
                $actions[] = ['label' => 'Retry', 'href' => 'javascript:location.reload()', 'class' => 'btn-secondary'];
            }
        }
        $block = tessera_state_block([
            'kind' => 'http-' . $code,
            'tone' => $opts['tone'] ?? $preset['tone'],
            'icon' => $opts['icon'] ?? $preset['icon'],
            'title' => $opts['title'] ?? $preset['title'],
            'body' => $opts['body'] ?? $preset['body'],
            'actions' => $actions,
        ]);
        $codeLabel = tessera_states_h((string) $code);
        return <<<HTML
<div class="ts-page">
  <div>
    <p class="text-muted mb-2" style="text-align:center;font-family:Roboto,system-ui,sans-serif;letter-spacing:.08em;text-transform:uppercase;font-size:.75rem;color:#5c6b76;">HTTP {$codeLabel}</p>
    {$block}
  </div>
</div>
HTML;
    }
}
