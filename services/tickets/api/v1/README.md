# Tessera Operator API v1

Base: `https://{tenant-host}/tickets/api/v1/`

Auth: `Authorization: Bearer tess_…` (create keys in **Settings → API keys**).

| Endpoint | Scope | Notes |
|----------|-------|--------|
| `GET meta.php` | any | Version + scopes |
| `GET trips.php` | `tickets:read` | Same contract as booking API trips |
| `GET booking.php?ref=&phone=` | `tickets:read` | Lookup booking |
| `GET cargo_track.php?code=` | `cargo:read` | Parcel by code (tenant-scoped) |

Payment fields in booking responses use neutral statuses (`pending` / `paid` / `failed`) — no payment-rail brand names.

## Webhooks

Register HTTPS URLs in **Settings → Webhooks**.

Headers:

- `X-Tessera-Event`: event code
- `X-Tessera-Signature`: `sha256=` + HMAC-SHA256(raw body, secret)

Events: `booking.confirmed`, `booking.paid`, `booking.cancelled`, `cargo.status_changed`, `payout.sent`.
