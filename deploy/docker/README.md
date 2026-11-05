# Tessera — Docker packaging (Hetzner)

> Ops: `docs/15 Production ops.md` · `docs/41 Hetzner hosting.md`.

## Public domains

| URL | Role |
|-----|------|
| **https://tessera.co.tz/** | Marketing (Vercel) |
| **https://app.tessera.co.tz** · **https://{slug}.tessera.co.tz** | Operator console (Hetzner via Caddy) |
| **https://console.tessera.co.tz/control/** | Tessera Control |

## Hetzner runtime

| | |
|--|--|
| Host | `qwantum-prod-hel1` (`ssh hetzner`) |
| Path | `/opt/projects/tessera` |
| Stack | Docker Compose `web` + `mysql` → `127.0.0.1:3080` |
| Edge | AFRIPEI Caddy → Tessera hosts → `:3080` |

### Env (on host `.env`)

- `TESSERA_PUBLIC_URL` / `SR_PUBLIC_URL`
- `CONTROL_PUBLIC_HOST=console.tessera.co.tz`
- `CONTROL_ISOLATE=1`
- `CONTROL_ALLOWED_HOSTS=console.tessera.co.tz`
- `PLATFORM_ALERT_EMAIL=ops@…` (optional)
- `CONTROL_CRON_TOKEN=…` (optional for `/control/cron_alerts.php?token=`)
- `MYSQL*` / `SR_*` / ClickPesa / platform secrets

### Build / deploy

```bash
./deploy/docker/build-deploy-tree.sh /tmp/tessera-web-deploy
# sync tree → /opt/projects/tessera, then:
ssh hetzner 'cd /opt/projects/tessera && docker compose up -d --build web'
```

Root `Dockerfile` + this tree build the PHP Apache image (Tickets + Cargo + Accounts + Control).

Do **not** exclude `services/tickets/.../js/vendor` when syncing (breaks login jQuery).

## Public module paths

| Module | URL |
|--------|-----|
| Tickets | https://{slug}.tessera.co.tz/tickets/ |
| Cargo | https://{slug}.tessera.co.tz/cargo/ |
| Accounts | https://{slug}.tessera.co.tz/accounts/ |
| Control | https://console.tessera.co.tz/control/ |

Legacy `/ticket` and `/account_up` permanently redirect to `/tickets` and `/accounts`.

Apply Control DB migrations on the `ticket` database (see `database/migrations/`).
