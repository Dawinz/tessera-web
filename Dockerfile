# Tessera — PHP 8.2 + Apache for Docker/Hetzner (operator console)
FROM php:8.2-apache-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        unzip curl default-mysql-client python3 \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql gd opcache \
    && rm -rf /var/lib/apt/lists/*

# Prefer mpm_prefork only (PHP requirement); avoid "More than one MPM loaded"
RUN a2dismod mpm_event || true; \
    a2dismod mpm_worker || true; \
    a2enmod mpm_prefork; \
    a2enmod rewrite headers


# Tessera SaaS module paths
COPY services/tickets/ /var/www/html/tickets/
COPY services/cargo/ /var/www/html/cargo/
COPY services/accounting/ /var/www/html/accounts/
COPY apps/platform-web/ /var/www/html/control/
COPY apps/operator-settings/ /var/www/html/settings/
COPY apps/operator-login/ /var/www/html/login/
COPY apps/operator-shell/ /var/www/html/operator/
COPY database/seed/ /opt/sr-seed/
COPY database/migrations/ /opt/sr-seed/migrations/
COPY deploy/docker/index.php /var/www/html/index.php
COPY deploy/docker/signup.php /var/www/html/signup.php
COPY deploy/docker/robots.txt /var/www/html/robots.txt
COPY deploy/docker/sitemap.xml /var/www/html/sitemap.xml
COPY deploy/docker/errors/ /var/www/html/errors/
COPY deploy/docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY deploy/docker/entrypoint.sh /entrypoint.sh
COPY deploy/docker/import-dbs.sh /opt/sr-seed/import-dbs.sh
COPY deploy/docker/apply-migrations.sh /opt/sr-seed/apply-migrations.sh
COPY deploy/docker/patch-db-hosts.py /opt/sr-seed/patch-db-hosts.py
COPY deploy/docker/sr_auto_prepend.php /var/www/html/sr_auto_prepend.php
COPY deploy/docker/sr_auto_prepend.php /opt/sr-seed/sr_auto_prepend.php

RUN chmod +x /entrypoint.sh /opt/sr-seed/import-dbs.sh /opt/sr-seed/apply-migrations.sh \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \;

ENV APACHE_DOCUMENT_ROOT=/var/www/html
EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
