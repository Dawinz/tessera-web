-- Wave O: operator API keys + outbound webhooks
-- Apply to ticket DB

CREATE TABLE IF NOT EXISTS tessera_api_keys (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id INT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  key_prefix VARCHAR(12) NOT NULL,
  key_hash CHAR(64) NOT NULL,
  scopes_json JSON NOT NULL,
  status ENUM('active','revoked') NOT NULL DEFAULT 'active',
  last_used_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by VARCHAR(120) NULL,
  revoked_at DATETIME NULL,
  UNIQUE KEY uq_key_hash (key_hash),
  KEY idx_api_tenant (tenant_id),
  CONSTRAINT fk_api_tenant FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_webhooks (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id INT UNSIGNED NOT NULL,
  url VARCHAR(500) NOT NULL,
  secret_enc MEDIUMTEXT NULL,
  secret_last4 VARCHAR(8) NULL,
  events_json JSON NOT NULL,
  status ENUM('active','disabled') NOT NULL DEFAULT 'active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_by VARCHAR(120) NULL,
  KEY idx_wh_tenant (tenant_id),
  CONSTRAINT fk_wh_tenant FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_webhook_deliveries (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  webhook_id INT UNSIGNED NOT NULL,
  tenant_id INT UNSIGNED NOT NULL,
  event_code VARCHAR(64) NOT NULL,
  payload_json JSON NOT NULL,
  status ENUM('pending','delivered','failed') NOT NULL DEFAULT 'pending',
  attempts INT NOT NULL DEFAULT 0,
  response_code INT NULL,
  last_error VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  delivered_at DATETIME NULL,
  KEY idx_whd_pending (status, id),
  CONSTRAINT fk_whd_webhook FOREIGN KEY (webhook_id) REFERENCES tessera_webhooks(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
