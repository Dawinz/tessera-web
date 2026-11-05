<?php
require_once __DIR__ . '/boot.php';

$next = tessera_login_safe_next($_GET['next'] ?? '');
/* Platform apex is marketing — operator staff sign in on their console host */
if (function_exists('tessera_redirect_platform_login_to_operator')
	&& tessera_redirect_platform_login_to_operator($next !== '' ? $next : '/tickets/')) {
	exit;
}
if (!empty($_SESSION['username'])) {
	$dest = $next !== '' ? $next : '/tickets/home.php';
	header('Location: ' . $dest);
	exit;
}

$tenant = function_exists('tessera_tenant_row') ? tessera_tenant_row() : null;
$brand = trim((string) ($tenant['display_name'] ?? 'Tessera'));
if ($brand === '') {
	$brand = 'Tessera';
}
$suspended = !empty($_GET['suspended']);
$nextAttr = htmlspecialchars($next, ENT_QUOTES, 'UTF-8');
$brandAttr = htmlspecialchars($brand, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php
    $op = htmlspecialchars(tessera_operator_display_name(), ENT_QUOTES, 'UTF-8');
    echo 'Sign in · ' . $op . ' | Tessera';
  ?></title>
  <?php echo tessera_favicon_link_tags('/tickets/assets/images'); ?>
  <meta name="description" content="Sign in to Tessera — Tickets, Cargo, and Accounts.">
  <style>
    :root {
      --ink: #0c1f2e;
      --navy: #03314d;
      --navy-deep: #022436;
      --mist: #d7e3ea;
      --accent: #1f7a6c;
      --accent-soft: #e6f3f0;
      --surface: rgba(255,255,255,.94);
      --muted: #5c6b76;
      --line: rgba(3,49,77,.12);
      --danger: #9b1c1c;
      --danger-bg: #fdecec;
      --font-ui: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      --font-body: Georgia, "Times New Roman", Times, serif;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }
    body {
      font-family: var(--font-body);
      color: var(--ink);
      background:
        radial-gradient(1200px 600px at 10% -10%, rgba(31,122,108,.28), transparent 55%),
        radial-gradient(900px 500px at 100% 0%, rgba(3,49,77,.55), transparent 50%),
        linear-gradient(160deg, var(--navy-deep) 0%, var(--navy) 48%, #0a4a42 100%);
      min-height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }
    .shell {
      width: min(440px, 100%);
      animation: rise .55s ease both;
    }
    @keyframes rise {
      from { opacity: 0; transform: translateY(14px); }
      to { opacity: 1; transform: none; }
    }
    .brand-block {
      text-align: center;
      color: #fff;
      margin-bottom: 1.5rem;
    }
    .brand-block .product {
      font-family: var(--font-ui);
      font-weight: 800;
      font-size: clamp(2rem, 5vw, 2.6rem);
      letter-spacing: .12em;
      text-transform: uppercase;
      line-height: 1;
      margin: 0 0 .55rem;
    }
    .brand-block .operator {
      margin: 0;
      font-size: 1.05rem;
      font-weight: 600;
      color: rgba(255,255,255,.88);
    }
    .brand-block .modules {
      margin: .85rem 0 0;
      font-family: var(--font-ui);
      font-size: .78rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(255,255,255,.55);
    }
    .panel {
      background: var(--surface);
      border: 1px solid rgba(255,255,255,.55);
      border-radius: 1rem;
      padding: 1.75rem 1.6rem 1.5rem;
      box-shadow: 0 24px 60px rgba(2, 20, 32, .28);
      backdrop-filter: blur(10px);
    }
    .panel h1 {
      font-family: var(--font-ui);
      font-size: 1.35rem;
      font-weight: 700;
      margin: 0 0 .35rem;
      color: var(--navy);
    }
    .panel p.lead {
      margin: 0 0 1.35rem;
      color: var(--muted);
      font-size: .98rem;
    }
    label {
      display: block;
      font-family: var(--font-ui);
      font-size: .78rem;
      font-weight: 600;
      letter-spacing: .04em;
      text-transform: uppercase;
      color: var(--navy);
      margin: 0 0 .4rem;
    }
    .field { margin-bottom: 1rem; }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      height: 48px;
      border: 1px solid var(--line);
      border-radius: .55rem;
      padding: 0 .95rem;
      font: inherit;
      font-size: 1rem;
      color: var(--ink);
      background: #fff;
      outline: none;
      transition: border-color .15s ease, box-shadow .15s ease;
    }
    input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(31,122,108,.18);
    }
    .btn {
      width: 100%;
      height: 50px;
      margin-top: .35rem;
      border: 0;
      border-radius: .55rem;
      background: var(--navy);
      color: #fff;
      font-family: var(--font-ui);
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: .02em;
      cursor: pointer;
      transition: transform .15s ease, background .15s ease;
    }
    .btn:hover { background: var(--navy-deep); }
    .btn:active { transform: translateY(1px); }
    .btn:disabled { opacity: .7; cursor: wait; }
    .alert {
      border-radius: .55rem;
      padding: .75rem .9rem;
      margin-bottom: 1rem;
      font-size: .92rem;
      background: var(--danger-bg);
      color: var(--danger);
      display: none;
    }
    .alert.show { display: block; animation: rise .35s ease both; }
    .foot {
      margin-top: 1.25rem;
      text-align: center;
      color: rgba(255,255,255,.55);
      font-size: .85rem;
    }
  </style>
</head>
<body>
  <main class="shell">
    <div class="brand-block">
      <p class="product">Tessera</p>
      <p class="operator"><?php echo $brandAttr; ?></p>
      <p class="modules">Tickets · Cargo · Accounts</p>
    </div>

    <section class="panel" aria-labelledby="signin-title">
      <h1 id="signin-title">Sign in</h1>
      <p class="lead">One login for your whole operator console.</p>

      <div id="alert" class="alert<?php echo $suspended ? ' show' : ''; ?>" role="alert">
        <?php echo $suspended ? 'This operator is suspended. Contact the Tessera platform owner.' : ''; ?>
      </div>

      <form id="loginForm" novalidate>
        <input type="hidden" id="next" value="<?php echo $nextAttr; ?>">
        <div class="field">
          <label for="username">Username, email, or phone</label>
          <input id="username" name="username" type="text" autocomplete="username" autofocus required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" autocomplete="current-password" required>
        </div>
        <button class="btn" type="submit" id="loginBtn">Sign in</button>
      </form>
    </section>

    <p class="foot">&copy; <?php echo date('Y'); ?> Tessera</p>
  </main>

  <script src="/tickets/assets/libs/jquery/jquery.min.js"></script>
  <script src="login.js"></script>
</body>
</html>
