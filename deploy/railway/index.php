<?php
/**
 * Tessera apex / host router (Wave K1).
 * - console.* → Control
 * - {slug}.tessera.co.tz (operators) → /login/
 * - tessera.co.tz / www → marketing
 */
$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
$host = preg_replace('/:\\d+$/', '', $host) ?? '';
$controlHost = strtolower((string) (getenv('CONTROL_PUBLIC_HOST') ?: 'console.tessera.co.tz'));
if ($host !== '' && ($host === $controlHost || $host === 'console.tessera.co.tz')) {
	$dest = rtrim((string) (getenv('CONTROL_PUBLIC_URL') ?: 'https://console.tessera.co.tz/control'), '/');
	if (!str_ends_with($dest, '/control') && !str_contains($dest, '/control/')) {
		$dest .= '/control';
	}
	header('Location: ' . $dest . '/', true, 302);
	exit;
}

/* Operator tenant hosts: send staff to unified login, not the marketing hero */
$isOperatorSubdomain = (bool) preg_match('/^([a-z0-9-]+)\\.tessera\\.co\\.tz$/', $host, $__opHost);
if ($isOperatorSubdomain && !in_array($__opHost[1], ['www', 'console'], true)) {
	header('Location: /login/', true, 302);
	exit;
}
unset($__opHost, $isOperatorSubdomain);

$base = rtrim(getenv('TESSERA_PUBLIC_URL') ?: getenv('SR_PUBLIC_URL') ?: '', '/');
if ($base === '') {
	$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		|| (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
	$base = ($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
}
/* Staff sign in on the flagship operator console — not the marketing apex */
$flagshipLogin = getenv('TESSERA_FLAGSHIP_HOST') ?: 'srclassiccoach.tessera.co.tz';
$flagshipLogin = preg_replace('/^https?:\\/\\//', '', strtolower(trim($flagshipLogin)));
$flagshipLogin = preg_replace('/:\\d+$/', '', $flagshipLogin) ?: 'srclassiccoach.tessera.co.tz';
$tickets = htmlspecialchars('https://' . $flagshipLogin . '/login/', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tessera — Bus operator platform</title>
  <link rel="icon" href="/tickets/assets/images/favicon.svg?v=20260906" type="image/svg+xml">
  <link rel="alternate icon" href="/tickets/assets/images/favicon.ico?v=20260906">
  <meta name="theme-color" content="#03314d">
  <meta name="description" content="Tickets, cargo, and accounts for African coach operators — one multi-tenant platform.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap" rel="stylesheet">
  <style>
    :root {
      --ink: #0e1a17;
      --mist: #c5d5d0;
      --brand: #0a4f45;
      --brand-deep: #063830;
      --sun: #e8b84a;
      --fog: rgba(245, 248, 246, 0.92);
      --line: rgba(14, 26, 23, 0.12);
    }
    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      margin: 0;
      color: var(--ink);
      font-family: "Source Serif 4", Georgia, serif;
      background: #0a1f1c;
    }
    .hero {
      position: relative;
      min-height: 100vh;
      min-height: 100dvh;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: clamp(1.25rem, 4vw, 2.5rem);
      padding-bottom: clamp(2.5rem, 8vh, 4.5rem);
      overflow: hidden;
      color: #f4f7f5;
    }
    .hero-bg {
      position: absolute;
      inset: 0;
      background:
        linear-gradient(180deg, rgba(6, 28, 24, 0.35) 0%, rgba(6, 28, 24, 0.55) 45%, rgba(6, 28, 24, 0.92) 100%),
        url("https://images.unsplash.com/photo-1544620341-11fe2bf7d70d?auto=format&fit=crop&w=2000&q=80") center / cover no-repeat;
      transform: scale(1.04);
      animation: drift 28s ease-in-out infinite alternate;
      z-index: 0;
    }
    @keyframes drift {
      from { transform: scale(1.04) translate3d(0, 0, 0); }
      to { transform: scale(1.08) translate3d(-1.2%, -0.6%, 0); }
    }
    .hero-inner { position: relative; z-index: 1; max-width: 40rem; }
    .brand {
      font-family: "Bricolage Grotesque", system-ui, sans-serif;
      font-weight: 800;
      font-size: clamp(2.75rem, 9vw, 5rem);
      letter-spacing: -0.03em;
      line-height: 0.95;
      margin: 0 0 1rem;
      opacity: 0;
      animation: rise 0.9s ease 0.1s forwards;
    }
    .headline {
      font-family: "Bricolage Grotesque", system-ui, sans-serif;
      font-weight: 700;
      font-size: clamp(1.35rem, 3.4vw, 1.85rem);
      letter-spacing: -0.02em;
      line-height: 1.2;
      margin: 0 0 0.75rem;
      max-width: 18ch;
      opacity: 0;
      animation: rise 0.9s ease 0.28s forwards;
    }
    .lede {
      margin: 0 0 1.75rem;
      font-size: clamp(1.05rem, 2.2vw, 1.2rem);
      line-height: 1.45;
      color: var(--mist);
      max-width: 34ch;
      opacity: 0;
      animation: rise 0.9s ease 0.42s forwards;
    }
    .ctas {
      display: flex;
      flex-wrap: wrap;
      gap: 0.85rem 1.25rem;
      align-items: center;
      opacity: 0;
      animation: rise 0.9s ease 0.55s forwards;
    }
    .cta-primary {
      display: inline-flex;
      align-items: center;
      font-family: "Bricolage Grotesque", system-ui, sans-serif;
      font-weight: 700;
      font-size: 1rem;
      text-decoration: none;
      color: var(--ink);
      background: var(--sun);
      padding: 0.85rem 1.35rem;
      border: 0;
      cursor: pointer;
      transition: transform 0.25s ease, background 0.25s ease;
    }
    .cta-primary:hover { transform: translateY(-2px); background: #f0c65a; }
    .cta-secondary {
      font-family: "Bricolage Grotesque", system-ui, sans-serif;
      font-weight: 600;
      font-size: 0.95rem;
      color: #f4f7f5;
      text-decoration: none;
      border-bottom: 1px solid transparent;
      padding-bottom: 2px;
      transition: border-color 0.25s ease, color 0.25s ease;
    }
    .cta-secondary:hover { border-color: var(--sun); color: var(--sun); }
    @keyframes rise {
      from { opacity: 0; transform: translateY(18px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .signup {
      background:
        radial-gradient(ellipse 80% 50% at 10% 0%, rgba(10, 79, 69, 0.18), transparent 55%),
        linear-gradient(165deg, #eef3f1 0%, #dfeae6 48%, #cfdcd7 100%);
      color: var(--ink);
      padding: clamp(3rem, 8vw, 5.5rem) clamp(1.25rem, 4vw, 2.5rem);
    }
    .signup-inner { max-width: 36rem; margin: 0 auto; }
    .signup h2 {
      font-family: "Bricolage Grotesque", system-ui, sans-serif;
      font-weight: 800;
      font-size: clamp(1.6rem, 4vw, 2.1rem);
      letter-spacing: -0.02em;
      margin: 0 0 0.5rem;
      color: var(--brand-deep);
    }
    .signup > .signup-inner > p {
      margin: 0 0 1.75rem;
      color: #3a4f49;
      line-height: 1.5;
    }
    label {
      display: block;
      font-family: "Bricolage Grotesque", system-ui, sans-serif;
      font-size: 0.78rem;
      font-weight: 600;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      margin: 0 0 0.35rem;
      color: var(--brand);
    }
    .field { margin-bottom: 1rem; }
    input, textarea {
      width: 100%;
      font: inherit;
      font-size: 1.05rem;
      padding: 0.75rem 0.85rem;
      border: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.72);
      color: var(--ink);
      outline: none;
      transition: border-color 0.2s ease, background 0.2s ease;
    }
    input:focus, textarea:focus {
      border-color: var(--brand);
      background: #fff;
    }
    .slug-hint {
      margin: 0.35rem 0 0;
      font-size: 0.9rem;
      color: #4a5e57;
    }
    .slug-hint strong { color: var(--brand-deep); font-weight: 600; }
    .hp { position: absolute; left: -9999px; height: 0; overflow: hidden; }
    .form-actions { margin-top: 1.35rem; display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; }
    button[type="submit"] {
      font-family: "Bricolage Grotesque", system-ui, sans-serif;
      font-weight: 700;
      font-size: 1rem;
      background: var(--brand);
      color: #f4f7f5;
      border: 0;
      padding: 0.9rem 1.4rem;
      cursor: pointer;
      transition: background 0.2s ease, transform 0.2s ease;
    }
    button[type="submit"]:hover { background: var(--brand-deep); transform: translateY(-1px); }
    button[type="submit"]:disabled { opacity: 0.65; cursor: wait; transform: none; }
    #signup-msg {
      margin: 1rem 0 0;
      min-height: 1.4em;
      font-size: 1rem;
      line-height: 1.4;
    }
    #signup-msg.ok { color: var(--brand-deep); }
    #signup-msg.err { color: #8a2e2e; }
    .foot {
      padding: 1.5rem clamp(1.25rem, 4vw, 2.5rem) 2rem;
      background: var(--brand-deep);
      color: var(--mist);
      font-size: 0.95rem;
    }
    .foot a { color: var(--sun); text-decoration: none; }
    .foot a:hover { text-decoration: underline; }
    @media (prefers-reduced-motion: reduce) {
      .hero-bg { animation: none; }
      .brand, .headline, .lede, .ctas { opacity: 1; animation: none; }
    }
  </style>
</head>
<body>
  <header class="hero">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="hero-inner">
      <p class="brand">Tessera</p>
      <h1 class="headline">Run your fleet on one platform.</h1>
      <p class="lede">Tickets, cargo, and accounts for coach operators — provisioned per company, not a shared passenger app.</p>
      <div class="ctas">
        <a class="cta-primary" href="#get-started">Get started</a>
        <a class="cta-secondary" href="<?php echo $tickets; ?>">Staff login</a>
      </div>
    </div>
  </header>

  <section class="signup" id="get-started">
    <div class="signup-inner">
      <h2>Request your operator workspace</h2>
      <p>We create a provisioning queue entry for your company. DNS and go-live stay with Tessera ops — you get a slug like <em>yourco.tessera.co.tz</em>.</p>
      <form id="signup-form" method="post" action="/signup.php" novalidate>
        <div class="hp" aria-hidden="true">
          <label for="website">Website</label>
          <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
        </div>
        <div class="field">
          <label for="company">Company name</label>
          <input id="company" name="company" required maxlength="120" autocomplete="organization">
        </div>
        <div class="field">
          <label for="slug">Preferred subdomain</label>
          <input id="slug" name="slug" required maxlength="40" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" placeholder="your-coach" autocomplete="off">
          <p class="slug-hint"><strong id="slug-preview">your-coach</strong>.tessera.co.tz</p>
        </div>
        <div class="field">
          <label for="contact_name">Contact name</label>
          <input id="contact_name" name="contact_name" required maxlength="80" autocomplete="name">
        </div>
        <div class="field">
          <label for="email">Work email</label>
          <input id="email" name="email" type="email" required maxlength="120" autocomplete="email">
        </div>
        <div class="field">
          <label for="phone">Phone</label>
          <input id="phone" name="phone" maxlength="40" autocomplete="tel">
        </div>
        <div class="field">
          <label for="notes">Notes (optional)</label>
          <textarea id="notes" name="notes" rows="3" maxlength="500" placeholder="Routes, fleet size, current tools…"></textarea>
        </div>
        <div class="form-actions">
          <button type="submit">Submit request</button>
        </div>
        <p id="signup-msg" role="status" aria-live="polite"></p>
      </form>
    </div>
  </section>

  <footer class="foot">
    Tessera · African multi-tenant transport SaaS ·
    <a href="https://console.tessera.co.tz/control/">Platform control</a>
  </footer>

  <script>
    (function () {
      var slug = document.getElementById('slug');
      var preview = document.getElementById('slug-preview');
      var form = document.getElementById('signup-form');
      var msg = document.getElementById('signup-msg');
      var btn = form.querySelector('button[type="submit"]');

      function cleanSlug(v) {
        return String(v || '').toLowerCase().replace(/[^a-z0-9-]/g, '').replace(/--+/g, '-').replace(/^-|-$/g, '');
      }
      slug.addEventListener('input', function () {
        var v = cleanSlug(slug.value);
        if (slug.value !== v) slug.value = v;
        preview.textContent = v || 'your-coach';
      });

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        msg.className = '';
        msg.textContent = '';
        btn.disabled = true;
        var body = new FormData(form);
        fetch('/signup.php', { method: 'POST', body: body, credentials: 'same-origin' })
          .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
          .then(function (res) {
            btn.disabled = false;
            if (res.ok && res.j && res.j.ok) {
              msg.className = 'ok';
              msg.textContent = 'Request queued for ' + (res.j.domain || 'your workspace') + '. Our team will continue provisioning — watch your email.';
              form.reset();
              preview.textContent = 'your-coach';
              return;
            }
            msg.className = 'err';
            var err = (res.j && res.j.error) || 'failed';
            var map = {
              slug_taken: 'That subdomain is already reserved.',
              slug_reserved: 'That subdomain is reserved. Try another.',
              slug: 'Use 3–40 characters: lowercase letters, numbers, hyphens.',
              email: 'Enter a valid work email.',
              company: 'Enter your company name.',
              contact: 'Enter a contact name.',
              rate_limit: 'Too many requests. Try again in a few minutes.',
              create_failed: 'Could not queue your request. Try again or email support.'
            };
            msg.textContent = map[err] || 'Something went wrong. Please try again.';
          })
          .catch(function () {
            btn.disabled = false;
            msg.className = 'err';
            msg.textContent = 'Network error. Please try again.';
          });
      });
    })();
  </script>
</body>
</html>
