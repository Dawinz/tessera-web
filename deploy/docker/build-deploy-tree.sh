#!/bin/bash
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
DEPLOY="${1:-/tmp/tessera-web-deploy}"
# Keep guided-tour + application-state assets in sync before packaging
if [[ -x "$ROOT/scripts/sync-tessera-tour.sh" ]]; then
  bash "$ROOT/scripts/sync-tessera-tour.sh"
fi
if [[ -x "$ROOT/scripts/sync-tessera-states.sh" ]]; then
  bash "$ROOT/scripts/sync-tessera-states.sh"
fi
rm -rf "$DEPLOY"
mkdir -p "$DEPLOY/apps" "$DEPLOY/services" "$DEPLOY/database" "$DEPLOY/deploy"
cp "$ROOT/Dockerfile" "$DEPLOY/"
[[ -f "$ROOT/.dockerignore" ]] && cp "$ROOT/.dockerignore" "$DEPLOY/"
# Never blanket-exclude "vendor" for tickets — js/vendor holds jQuery for login
rsync -a --delete --exclude '.git' --exclude 'node_modules' --exclude '*.zip' --exclude 'uploads' --exclude 'logs' \
  "$ROOT/services/tickets/" "$DEPLOY/services/tickets/"
rsync -a --delete --exclude '.git' --exclude 'node_modules' --exclude '*.zip' --exclude 'uploads' --exclude 'logs' \
  "$ROOT/apps/platform-web/" "$DEPLOY/apps/platform-web/"
rsync -a --delete --exclude '.git' --exclude 'node_modules' \
  "$ROOT/apps/operator-settings/" "$DEPLOY/apps/operator-settings/"
rsync -a --delete --exclude '.git' --exclude 'node_modules' \
  "$ROOT/apps/operator-login/" "$DEPLOY/apps/operator-login/"
rsync -a --delete --exclude '.git' --exclude 'node_modules' \
  "$ROOT/apps/operator-shell/" "$DEPLOY/apps/operator-shell/"
# Keep qrcode/vendor + barcode/vendor (receipt print). Only skip a top-level /vendor if present.
rsync -a --delete --exclude '.git' --exclude 'node_modules' --exclude 'uploads' --exclude 'logs' --exclude '*.zip' \
  --exclude '/vendor/' \
  "$ROOT/services/cargo/" "$DEPLOY/services/cargo/"
rsync -a --delete --exclude '.git' --exclude 'node_modules' --exclude 'uploads' --exclude 'logs' --exclude 'vendor' \
  "$ROOT/services/accounting/" "$DEPLOY/services/accounting/"
rsync -a --delete "$ROOT/deploy/docker/" "$DEPLOY/deploy/docker/"
mkdir -p "$DEPLOY/database/seed" "$DEPLOY/database/migrations"
rsync -a "$ROOT/database/seed/" "$DEPLOY/database/seed/" 2>/dev/null || true
rsync -a "$ROOT/database/migrations/" "$DEPLOY/database/migrations/" 2>/dev/null || true
echo "Deploy tree ready at $DEPLOY"
