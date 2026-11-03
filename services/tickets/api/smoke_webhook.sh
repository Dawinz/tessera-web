#!/usr/bin/env bash
# Simulate a nested provider webhook envelope against booking API.
# Usage: BASE=https://tessera.co.tz/tickets/api ./smoke_webhook.sh SRC-20260728-0001
set -euo pipefail
BASE="${BASE:-https://tessera.co.tz/tickets/api}"
BASE="${BASE%/}"
REF="${1:-}"

if [[ -z "$REF" ]]; then
  echo "Usage: $0 BOOKING_REF" >&2
  exit 1
fi

echo "== nested PAYMENT RECEIVED for $REF =="
curl -sS -X POST "$BASE/payment_webhook.php" \
  -H 'Content-Type: application/json' \
  -d "{\"event\":\"PAYMENT RECEIVED\",\"data\":{\"orderReference\":\"$REF\",\"status\":\"SUCCESS\",\"id\":\"SIM-F4-$REF\"}}" \
  | python3 -m json.tool

echo "== get_booking =="
curl -sS "$BASE/get_booking.php?ref=$REF&phone=255700000099" | python3 -m json.tool || true
