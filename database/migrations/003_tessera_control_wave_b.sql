-- Tessera Control Wave B — platform admins + per-tenant payment vault
-- Apply after 002_tessera_platform_control.sql

CREATE TABLE IF NOT EXISTS tessera_platform_admins (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  role ENUM('owner', 'support', 'readonly') NOT NULL DEFAULT 'support',
  password_hash VARCHAR(255) NOT NULL,
  status ENUM('active', 'disabled') NOT NULL DEFAULT 'active',
  last_login_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_platform_admin_email (email),
  KEY idx_platform_admin_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_tenant_payments (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  tenant_id INT UNSIGNED NOT NULL,
  provider VARCHAR(32) NOT NULL DEFAULT 'clickpesa',
  client_id VARCHAR(255) NULL,
  api_key_enc TEXT NULL,
  api_key_last4 VARCHAR(8) NULL,
  base_url VARCHAR(255) NULL,
  webhook_url VARCHAR(512) NULL,
  webhook_last_at DATETIME NULL,
  webhook_last_status VARCHAR(32) NULL,
  payment_mock TINYINT(1) NOT NULL DEFAULT 1,
  credentials_status ENUM('missing', 'ok') NOT NULL DEFAULT 'missing',
  updated_by VARCHAR(128) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_tenant_provider (tenant_id, provider),
  KEY idx_tenant_payments_status (credentials_status),
  CONSTRAINT fk_tenant_payments_tenant
    FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
