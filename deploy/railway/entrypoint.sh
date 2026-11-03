#!/bin/bash
set -euo pipefail

# Map Railway MySQL plugin vars → SR_* used by connect.php
export SR_DB_HOST="${SR_DB_HOST:-${MYSQLHOST:-127.0.0.1}}"
export SR_DB_PORT="${SR_DB_PORT:-${MYSQLPORT:-3306}}"
export SR_DB_USER="${SR_DB_USER:-${MYSQLUSER:-root}}"
export SR_DB_PASS="${SR_DB_PASS:-${MYSQLPASSWORD:-}}"
export SR_DB_NAME="${SR_DB_NAME:-ticket}"
export SR_CARGO_DB="${SR_CARGO_DB:-cargo}"
export SR_ACCOUNT_DB="${SR_ACCOUNT_DB:-account}"

# Canonical public URL — prefer Tessera custom domain
if [ -n "${TESSERA_PUBLIC_URL:-}" ]; then
  export SR_PUBLIC_URL="${TESSERA_PUBLIC_URL}"
elif [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
  # Prefer Railway's current public domain (custom domain when attached)
  export SR_PUBLIC_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
elif [ -z "${SR_PUBLIC_URL:-}" ]; then
  export SR_PUBLIC_URL=""
fi
export TESSERA_PUBLIC_URL="${TESSERA_PUBLIC_URL:-${SR_PUBLIC_URL:-}}"

# Hide PHP warnings on the public demo (session whitespace etc.)
cat >/usr/local/etc/php/conf.d/sr-prod.ini <<'INI'
display_errors=Off
log_errors=On
session.cookie_secure=1
session.cookie_samesite=Lax
session.cookie_httponly=1
auto_prepend_file=/var/www/html/sr_auto_prepend.php
INI

# Shared session bootstrap (copied at image build; ensure present at runtime too)
if [ ! -f /var/www/html/sr_auto_prepend.php ] && [ -f /opt/sr-seed/sr_auto_prepend.php ]; then
  cp /opt/sr-seed/sr_auto_prepend.php /var/www/html/sr_auto_prepend.php
fi

# PHP Apache images require mpm_prefork — never load event/worker alongside it
a2dismod mpm_event 2>/dev/null || true
a2dismod mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true
a2enmod rewrite 2>/dev/null || true

# Railway sets PORT dynamically
PORT="${PORT:-80}"
printf 'Listen %s\n' "${PORT}" > /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \\*:[0-9]+>/<VirtualHost *:${PORT}>/" \
  /etc/apache2/sites-available/000-default.conf

grep -q '^ServerName ' /etc/apache2/apache2.conf || echo "ServerName localhost" >> /etc/apache2/apache2.conf

mkdir -p /var/www/html/tickets/uploads /var/www/html/cargo/uploads || true
chown -R www-data:www-data /var/www/html/tickets/uploads /var/www/html/cargo/uploads 2>/dev/null || true

# Point legacy hardcoded XAMPP connects at Railway MySQL
if [ -f /opt/sr-seed/patch-db-hosts.py ]; then
  python3 /opt/sr-seed/patch-db-hosts.py || true
fi

if [ -n "${MYSQLHOST:-}" ] && [ "${SR_SKIP_DB_IMPORT:-0}" != "1" ]; then
  chmod +x /opt/sr-seed/import-dbs.sh 2>/dev/null || true
  /opt/sr-seed/import-dbs.sh || echo "DB import deferred/failed — app will still start."
fi

# Always attempt idempotent schema migrations (Wave H tenant_id, etc.)
if [ -n "${MYSQLHOST:-}" ] && [ -x /opt/sr-seed/apply-migrations.sh ]; then
  /opt/sr-seed/apply-migrations.sh || echo "Migrations deferred/failed — app will still start."
fi

# Hybrid — ensure every non-flagship operator has dedicated ticket/cargo/account DBs
if [ -n "${MYSQLHOST:-}" ] && [ "${TESSERA_SKIP_DB_PROVISION:-0}" != "1" ]; then
  if [ -f /var/www/html/tickets/includes/tessera_db_provision.php ]; then
    php -r '
      require "/var/www/html/tickets/includes/tessera_tenant.php";
      require "/var/www/html/tickets/includes/tessera_db_provision.php";
      foreach (tessera_provision_all_dedicated_tenants(["copy_users"=>false]) as $r) {
        $slug = $r["slug"] ?? "?";
        if (!empty($r["ok"])) {
          echo "db-provision: {$slug} -> ".($r["db_ticket"] ?? "shared")."\n";
        } else {
          echo "db-provision: {$slug} FAILED ".($r["error"] ?? "")."\n";
        }
      }
    ' || echo "db-provision deferred/failed — app will still start."
  fi
fi

exec "$@"
