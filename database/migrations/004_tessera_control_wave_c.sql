-- Tessera Control Wave C — DNS, entitlements, support tokens
-- Apply after 003_tessera_control_wave_b.sql
-- Safe to re-run only if columns/table missing (manual check).

ALTER TABLE tessera_tenants
  ADD COLUMN domain_status ENUM('pending','dns_ok','ssl_ok','failed') NOT NULL DEFAULT 'pending' AFTER primary_domain,
  ADD COLUMN custom_domain VARCHAR(255) NULL AFTER domain_status,
  ADD COLUMN module_tickets TINYINT(1) NOT NULL DEFAULT 1 AFTER primary_color,
  ADD COLUMN module_cargo TINYINT(1) NOT NULL DEFAULT 1 AFTER module_tickets,
  ADD COLUMN module_accounts TINYINT(1) NOT NULL DEFAULT 1 AFTER module_cargo;

UPDATE tessera_tenants
SET domain_status = 'ssl_ok',
    module_tickets = 1,
    module_cargo = 1,
    module_accounts = 1
WHERE slug = 'sr-classic';

CREATE TABLE IF NOT EXISTS tessera_support_tokens (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  token_hash CHAR(64) NOT NULL,
  tenant_id INT UNSIGNED NOT NULL,
  actor VARCHAR(128) NOT NULL,
  target_username VARCHAR(78) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_support_token_hash (token_hash),
  KEY idx_support_expires (expires_at),
  KEY idx_support_tenant (tenant_id),
  CONSTRAINT fk_support_tenant
    FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
