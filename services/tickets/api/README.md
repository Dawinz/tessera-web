# Mobile & POS booking API

JSON API for customer-mobile and admin-mobile.

**Contract (v1):** [[30 Booking API contract]] (`docs/30 Booking API contract.md`)

| Env | Base |
|-----|------|
| Production | `https://tessera.co.tz/tickets/api/` |
| Local XAMPP | `http://localhost/tickets/api/` |
| Android emulator | `http://10.0.2.2/tickets/api/` |

Every response includes header `X-Tessera-Booking-Api: 1` and JSON `api_version`.

## Endpoints

| Method | Path | Purpose |
|--------|------|---------|
| GET | `health.php` | Liveness + contract version |
| GET | `trips.php?from=&to=&date=` | Search trips |
| POST | `book_ticket.php` | Create booking |
| GET | `get_booking.php?ref=&phone=` | Lookup ticket |
| POST | `agent_login.php` | Staff login (POS) |
| POST | `payment_initiate.php` | Start / mock payment |
| POST | `payment_webhook.php` | Payment status webhook |

Public JSON never exposes payment-provider brand names. Provider credentials stay in server env only.

## Setup

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'Teccross@360' ticket < schema.sql
php seed_trips.php
```

## Smoke

```bash
BASE=https://tessera.co.tz/tickets/api ./smoke_contract.sh
```

## Env (optional)

- `SR_DB_HOST`, `SR_DB_NAME`, `SR_DB_USER`, `SR_DB_PASS`
- `PAYMENT_MOCK=1` — force mock pay (otherwise mock when collection rail not configured)
- `TESSERA_PUBLIC_URL` / `SR_PUBLIC_URL` — webhook base URL
