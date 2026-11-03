# Tessera — Railway deployment

## Public domain

| URL | Role |
|-----|------|
| **https://tessera.co.tz/** | Marketing landing + self-serve signup (K1–K2) |
| **https://tessera.co.tz/tickets\|/cargo\|/accounts** | Operator console |
| **https://tessera.co.tz/signup.php** | Public signup → Control provisioning queue |
| **https://console.tessera.co.tz/control/** | Tessera Control (platform owners) |

Railway’s default `*.up.railway.app` service domain is **not** used publicly.

Set on `tessera-web`:

- `TESSERA_PUBLIC_URL=https://tessera.co.tz`
- `SR_PUBLIC_URL=https://tessera.co.tz`
- `CONTROL_PUBLIC_HOST=console.tessera.co.tz`
- `CONTROL_ISOLATE=1` (blocks Control on the operator hostname)
- `CONTROL_ALLOWED_HOSTS=console.tessera.co.tz` (localhost always allowed for XAMPP)
- `PLATFORM_ALERT_EMAIL=ops@…` (optional alert inbox)
- `CONTROL_CRON_TOKEN=…` (optional for `/control/cron_alerts.php?token=`)

### DNS (required for HTTPS certificate)

Railway expects traffic for `tessera.co.tz` to resolve to:

| Type | Name | Value |
|------|------|-------|
| **CNAME** (or ALIAS/ANAME at apex) | `@` | `skxqrmfg.up.railway.app` |

For Control (preferred host when plan allows a second custom domain):

| Type | Name | Value |
|------|------|-------|
| **CNAME** | `console` | same Railway DNS target as `tessera-web` |

**Note (2026-07-28):** `console.tessera.co.tz` is attached on Railway (Hobby). Add DNS at Spaceship:

| Type | Name | Value |
|------|------|-------|
| **CNAME** | `console` | `6l09q4c6.up.railway.app` |

If Railway asks for ownership verification:

| Type | Name | Value |
|------|------|-------|
| **TXT** | `_railway-verify.console` | `railway-verify=58a36b77b4b8532b0826fffeb981c65924f1466236a71e4a546d81dc8ec37f35` |

Until that propagates, Control stays available at **https://tessera.co.tz/control/** (`CONTROL_ALLOWED_HOSTS` still includes the operator host). After DNS is green, remove `tessera.co.tz` / `www.tessera.co.tz` from `CONTROL_ALLOWED_HOSTS`.

## Projects

| Railway project | Services |
|-----------------|----------|
| **tessera** | `tessera-web` (operator console), `MySQL` |
| **sr-classic-relay** *(when Free-plan capacity allows)* | `sr-classic-push-service`, `sr-classic-tracking-relay` |

Until a third project can be provisioned, the `sr-classic-*` relay services remain in **tessera** under those names.

## Deploy operator console

Root `Dockerfile` + `railway.toml` build the PHP Apache image (Tickets + Cargo + Accounts).

Lean uploads: `deploy/railway/build-deploy-tree.sh` then `railway up` from that tree.

## Env

See `sr_env.php` / connect configs: `MYSQL*`, `SR_*`, `TESSERA_PUBLIC_URL`.

## Public module paths

| Module | URL |
|--------|-----|
| Tickets | https://tessera.co.tz/tickets/ |
| Cargo | https://tessera.co.tz/cargo/ |
| Accounts | https://tessera.co.tz/accounts/ |
| Control | https://console.tessera.co.tz/control/ |

Legacy `/ticket` and `/account_up` permanently redirect to `/tickets` and `/accounts`.

Apply Control DB migrations `001`–`006` on the `ticket` database (see `database/migrations/`).
