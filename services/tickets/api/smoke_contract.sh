#!/usr/bin/env bash
# Smoke-test Tessera booking API contract v1.
# Usage: BASE=https://tessera.co.tz/tickets/api ./smoke_contract.sh
set -euo pipefail
BASE="${BASE:-https://tessera.co.tz/tickets/api}"
BASE="${BASE%/}"

echo "== health =="
curl -sS -D - "$BASE/health.php" -o /tmp/tessera_health.json | grep -iE 'HTTP/|X-Tessera-Booking-Api' || true
python3 - <<'PY'
import json
d=json.load(open("/tmp/tessera_health.json"))
assert d.get("api_version")=="1", d
assert d.get("ok") is True or d.get("success") is True, d
print("health ok", d.get("db"))
PY

echo "== trips =="
if date -v+1d +%Y-%m-%d >/dev/null 2>&1; then
  DAY=$(date -v+1d +%Y-%m-%d)
else
  DAY=$(date -d '+1 day' +%Y-%m-%d)
fi
curl -sS "$BASE/trips.php?from=Dar%20es%20Salaam&to=Arusha&date=$DAY" -o /tmp/tessera_trips.json
python3 - <<'PY'
import json
d=json.load(open("/tmp/tessera_trips.json"))
assert d.get("api_version")=="1", d
assert "trips" in d, d
print("trips", len(d["trips"]), "api_version", d["api_version"])
if not d["trips"]:
    raise SystemExit("no trips seeded for tomorrow — run seed_trips.php")
open("/tmp/tessera_trip_id.txt","w").write(str(d["trips"][0]["id"]))
PY

TRIP=$(cat /tmp/tessera_trip_id.txt)
SEAT=$((40 + RANDOM % 8))
echo "== book seat $SEAT trip $TRIP =="
curl -sS -X POST "$BASE/book_ticket.php" \
  -d "trip_id=$TRIP" \
  -d "seat_number=$SEAT" \
  -d "first_name=Contract" \
  -d "last_name=Smoke" \
  -d "phone=255711223344" \
  -d "boarding_stop=UBUNGO" \
  -d "dropping_stop=ARUSHA CENTRE" \
  -d "payment_method=mpesa" \
  -d "payment_phone=255711223344" \
  -d "fare=35000" \
  -d "levy=1000" \
  -d "total=36000" \
  -d "channel=customer_app" \
  -o /tmp/tessera_book.json
python3 - <<'PY'
import json
d=json.load(open("/tmp/tessera_book.json"))
assert d.get("success") is True, d
assert d.get("needs_payment") is True, d
assert d.get("api_version")=="1", d
open("/tmp/tessera_bref.txt","w").write(d["booking_ref"])
print("booked", d["booking_ref"])
PY

BREF=$(cat /tmp/tessera_bref.txt)
echo "== pay mock/live =="
curl -sS -X POST "$BASE/payment_initiate.php" \
  -d "booking_ref=$BREF" \
  -d "payment_phone=255711223344" \
  -d "payment_method=mpesa" \
  -o /tmp/tessera_pay.json
python3 - <<'PY'
import json
d=json.load(open("/tmp/tessera_pay.json"))
assert d.get("success") is True, d
assert d.get("api_version")=="1", d
print("pay", d.get("mode"), d.get("status"))
PY

echo "== get_booking =="
curl -sS "$BASE/get_booking.php?ref=$BREF&phone=255711223344" -o /tmp/tessera_get.json
python3 - <<'PY'
import json
d=json.load(open("/tmp/tessera_get.json"))
assert d.get("found") is True, d
assert d.get("success") is True, d
assert d.get("api_version")=="1", d
print("get", d.get("status"), d.get("payment_status"))
PY

echo "OK contract smoke against $BASE"
