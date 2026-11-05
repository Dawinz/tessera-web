#!/bin/bash
# Apply pending Tessera SQL migrations (idempotent). Used by container entrypoint.
set -euo pipefail
HOST="${MYSQLHOST:-${SR_DB_HOST:-}}"
PORT="${MYSQLPORT:-${SR_DB_PORT:-3306}}"
USER="${MYSQLUSER:-${SR_DB_USER:-root}}"
PASS="${MYSQLPASSWORD:-${SR_DB_PASS:-}}"
TICKET_DB="${SR_DB_NAME:-ticket}"
CARGO_DB="${SR_CARGO_DB:-cargo}"
ACCOUNT_DB="${SR_ACCOUNT_DB:-account}"
MIGDIR="${1:-/opt/sr-seed/migrations}"

# Local XAMPP fallback when MYSQL/SR env vars are unset (never used in container without XAMPP)
if [[ -z "$HOST" ]]; then
  if [[ -x /Applications/XAMPP/xamppfiles/bin/mysql ]]; then
    HOST="127.0.0.1"
    PASS="${PASS:-Teccross@360}"
    echo "migrate: MYSQLHOST unset — using local XAMPP $HOST"
  else
    echo "migrate: no MYSQLHOST — skip"
    exit 0
  fi
fi
if [[ ! -d "$MIGDIR" ]]; then
  echo "migrate: no $MIGDIR — skip"
  exit 0
fi

if [[ -x /Applications/XAMPP/xamppfiles/bin/mysql ]]; then
  MYSQL_CLI="${MYSQL_BIN:-/Applications/XAMPP/xamppfiles/bin/mysql}"
elif command -v mysql >/dev/null 2>&1; then
  MYSQL_CLI="${MYSQL_BIN:-mysql}"
else
  echo "migrate: mysql client not found — skip"
  exit 0
fi

export MYSQL_PWD="$PASS"

apply_file() {
  local db="$1"
  local f="$2"
  local base
  base=$(basename "$f")
  [[ -f "$f" ]] || return 0
  echo "migrate: applying $base → $db"
  "$MYSQL_CLI" -h "$HOST" -P "$PORT" -u "$USER" "$db" --protocol=TCP --connect-timeout=10 < "$f" \
    && echo "migrate: $base ok" \
    || echo "migrate: $base failed (non-fatal)"
}

table_exists() {
  local db="$1"
  local table="$2"
  "$MYSQL_CLI" -h "$HOST" -P "$PORT" -u "$USER" "$db" --protocol=TCP --connect-timeout=10 \
    -N -e "SHOW TABLES LIKE '${table}'" 2>/dev/null | grep -q "$table"
}

echo "migrate: host=$HOST"

# Platform Control migrations (ticket registry) — 001–006 + Wave J 010 + L–O 011–014
if table_exists "$TICKET_DB" "users" || table_exists "$TICKET_DB" "booking"; then
  for f in "$MIGDIR"/001_*.sql "$MIGDIR"/002_*.sql "$MIGDIR"/003_*.sql \
           "$MIGDIR"/004_*.sql "$MIGDIR"/005_*.sql "$MIGDIR"/006_*.sql \
           "$MIGDIR"/010_*.sql "$MIGDIR"/011_*.sql "$MIGDIR"/012_*.sql \
           "$MIGDIR"/013_*.sql "$MIGDIR"/014_*.sql; do
    [[ -f "$f" ]] || continue
    apply_file "$TICKET_DB" "$f"
  done
else
  echo "migrate: $TICKET_DB missing core tables — skip platform migrations"
fi

# Wave H1 — tickets (booking.tenant_id required even when mobile_trips is absent)
if table_exists "$TICKET_DB" "booking"; then
  for f in "$MIGDIR"/007_*.sql; do
    apply_file "$TICKET_DB" "$f"
  done
else
  echo "migrate: $TICKET_DB.booking missing — skip 007"
fi

# Wave H follow-up — tickets ops tables (vehicle / routes / route_day)
if table_exists "$TICKET_DB" "vehicle"; then
  for f in "$MIGDIR"/016_*.sql "$MIGDIR"/019_*.sql "$MIGDIR"/023_*.sql "$MIGDIR"/025_*.sql "$MIGDIR"/026_*.sql; do
    [[ -f "$f" ]] || continue
    apply_file "$TICKET_DB" "$f"
  done
else
  echo "migrate: $TICKET_DB.vehicle missing — skip 016/019/023"
fi

# Naming corrections before 018 (renames roles_assiged → roles_assigned)
if [[ -f "$MIGDIR/015_apply_naming.py" ]]; then
  echo "migrate: applying 015 naming corrections"
  SR_DB_NAME="$TICKET_DB" SR_CARGO_DB="$CARGO_DB" SR_ACCOUNT_DB="$ACCOUNT_DB" \
    MYSQLHOST="$HOST" MYSQLPORT="$PORT" MYSQLUSER="$USER" MYSQLPASSWORD="$PASS" \
    python3 "$MIGDIR/015_apply_naming.py" "$TICKET_DB" "$CARGO_DB" "$ACCOUNT_DB" \
    && echo "migrate: 015 ok" \
    || echo "migrate: 015 failed (non-fatal)"
fi

# Tickets Schedule nav — replace Routes & Trips with schedule.php (018)
if table_exists "$TICKET_DB" "category"; then
  for f in "$MIGDIR"/018_*.sql "$MIGDIR"/020_*.sql "$MIGDIR"/021_*.sql "$MIGDIR"/022_*.sql "$MIGDIR"/024_*.sql "$MIGDIR"/028_*.sql; do
    [[ -f "$f" ]] || continue
    apply_file "$TICKET_DB" "$f"
  done
else
  echo "migrate: $TICKET_DB.category missing — skip 018–022"
fi

# Hybrid — dedicated DB columns on tessera_tenants
if table_exists "$TICKET_DB" "tessera_tenants"; then
  for f in "$MIGDIR"/017_*.sql; do
    apply_file "$TICKET_DB" "$f"
  done
else
  echo "migrate: $TICKET_DB.tessera_tenants missing — skip 017"
fi

# Wave H2 — cargo
if table_exists "$CARGO_DB" "parcel_info"; then
  for f in "$MIGDIR"/008_*.sql; do
    apply_file "$CARGO_DB" "$f"
  done
else
  echo "migrate: $CARGO_DB.parcel_info missing — skip 008"
fi

# Wave H3 — accounts
if table_exists "$ACCOUNT_DB" "vehicle_account"; then
  for f in "$MIGDIR"/009_*.sql; do
    apply_file "$ACCOUNT_DB" "$f"
  done
else
  echo "migrate: $ACCOUNT_DB.vehicle_account missing — skip 009"
fi

# Accounts nav + sample reports (027)
if table_exists "$ACCOUNT_DB" "category"; then
  for f in "$MIGDIR"/027_*.sql; do
    [[ -f "$f" ]] || continue
    apply_file "$ACCOUNT_DB" "$f"
  done
else
  echo "migrate: $ACCOUNT_DB.category missing — skip 027"
fi

# 015 naming corrections run above (before 018)
