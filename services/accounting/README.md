# Accounts service

Tessera accounting / manifests / cash PHP module.

| | |
|--|--|
| Path | `services/accounting` |
| URL | `/accounts/` |
| Database | `account` |
| Cargo bridge | `template/connect/connect_cargo.php` → DB `cargo` |

## Related DBs

- **Tickets** DB `ticket`
- **Cargo** DB `cargo` (vehicle / route context for some screens)
