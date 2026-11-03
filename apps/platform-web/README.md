# Tessera Control — platform owner console (Dashonic + XAMPP)

SaaS owner dashboard. **Not** the operator staff console.

| | |
|--|--|
| Path | `apps/platform-web` |
| Local URL | http://localhost/control/ |
| Theme | Dashonic (assets from `/tickets/assets/`) |
| DB | MySQL `ticket` — `tessera_tenants`, onboarding, audit |

## Symlink

```bash
ln -sfn "/Users/mac/DAWSON WORKING/TESSERA/apps/platform-web" /Applications/XAMPP/xamppfiles/htdocs/control
```

## Login (v1)

Configure in `includes/control_config.php` or env:

- Email / username: `owner@tessera.co.tz` (default)
- Password: `tessera-control-dev` (default — change for production)

Session name: `tessera_control` (isolated from Tickets staff session).

## Migrations

```bash
# from repo root, against ticket DB
mysql … ticket < database/migrations/001_tessera_tenants.sql
mysql … ticket < database/migrations/002_tessera_platform_control.sql
```

## Nav (Control only)

Overview · Operators · Onboarding · Health  

Does **not** show Tickets | Cargo | Accounts (those are operator-only).
