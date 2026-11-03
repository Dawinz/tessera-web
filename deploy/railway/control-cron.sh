#!/bin/sh
# Wave I5 — hit Control alert scanner then exit (Railway cron service).
set -eu
URL="${CONTROL_CRON_URL:-https://console.tessera.co.tz/control/cron_alerts.php}"
TOKEN="${CONTROL_CRON_TOKEN:?CONTROL_CRON_TOKEN required}"
curl -fsS -H "X-Control-Token: ${TOKEN}" "${URL}?token=${TOKEN}"
echo
