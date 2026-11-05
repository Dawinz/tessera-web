#!/bin/bash
# Import demo databases into server MySQL (idempotent-ish: skips if ticket.users exists).
set -euo pipefail

HOST="${SR_DB_HOST:-${MYSQLHOST}}"
PORT="${SR_DB_PORT:-${MYSQLPORT:-3306}}"
USER="${SR_DB_USER:-${MYSQLUSER}}"
PASS="${SR_DB_PASS:-${MYSQLPASSWORD}}"

if [ -z "${HOST:-}" ] || [ -z "${USER:-}" ]; then
  echo "Missing MySQL connection env (MYSQLHOST / MYSQLUSER)."
  exit 1
fi

MYSQL=(mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" --protocol=TCP)

echo "Waiting for MySQL at $HOST:$PORT ..."
for i in $(seq 1 60); do
  if "${MYSQL[@]}" -e "SELECT 1" &>/dev/null; then
    break
  fi
  sleep 2
done
"${MYSQL[@]}" -e "SELECT 1" >/dev/null

echo "Creating databases..."
"${MYSQL[@]}" -e "CREATE DATABASE IF NOT EXISTS ticket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS cargo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS account CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

HAS_USERS=$("${MYSQL[@]}" -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='ticket' AND table_name='users'" || echo 0)
if [ "$HAS_USERS" != "0" ]; then
  echo "ticket.users already present — skipping import."
  exit 0
fi

SEED="${SEED_DIR:-/opt/sr-seed}"
echo "Importing ticket..."
gunzip -c "$SEED/ticket.sql.gz" | "${MYSQL[@]}" ticket
echo "Importing account..."
gunzip -c "$SEED/account.sql.gz" | "${MYSQL[@]}" account
echo "Importing cargo schema + core..."
"${MYSQL[@]}" cargo < "$SEED/cargo_schema.sql"
"${MYSQL[@]}" cargo < "$SEED/cargo_core.sql"
echo "Import complete."
