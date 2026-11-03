-- Wave L: operator payouts, platform collection mode stub, settlements
-- Apply to ticket DB (idempotent)

CREATE TABLE IF NOT EXISTS tessera_tenant_payouts (
  tenant_id INT UNSIGNED NOT NULL PRIMARY KEY,
  preferred_method ENUM('bank','mobile_money') NOT NULL DEFAULT 'mobile_money',
  bank_name VARCHAR(120) NULL,
  account_name VARCHAR(160) NULL,
  account_number VARCHAR(64) NULL,
  bank_branch VARCHAR(120) NULL,
  momo_network VARCHAR(40) NULL,
  momo_msisdn VARCHAR(32) NULL,
  momo_account_name VARCHAR(160) NULL,
  notes TEXT NULL,
  verified_status ENUM('unverified','pending','verified','rejected') NOT NULL DEFAULT 'unverified',
  verified_at DATETIME NULL,
  verified_by VARCHAR(120) NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  updated_by VARCHAR(120) NULL,
  CONSTRAINT fk_tessera_payouts_tenant FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Collection mode: platform (default) vs future operator-owned gateway
CREATE TABLE IF NOT EXISTS tessera_tenant_payment_methods (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id INT UNSIGNED NOT NULL,
  mode ENUM('platform','operator_gateway') NOT NULL DEFAULT 'platform',
  gateway_type VARCHAR(64) NULL COMMENT 'Neutral type code when mode=operator_gateway; unused for platform',
  status ENUM('active','disabled','pending') NOT NULL DEFAULT 'active',
  secrets_enc MEDIUMTEXT NULL COMMENT 'Encrypted JSON for operator_gateway only',
  secrets_last4 VARCHAR(8) NULL,
  meta_json JSON NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  updated_by VARCHAR(120) NULL,
  UNIQUE KEY uq_tenant_mode_type (tenant_id, mode, gateway_type),
  KEY idx_tpm_tenant (tenant_id),
  CONSTRAINT fk_tpm_tenant FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_settlements (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id INT UNSIGNED NOT NULL,
  period_label VARCHAR(64) NOT NULL DEFAULT '',
  amount_tzs DECIMAL(14,2) NOT NULL DEFAULT 0,
  currency CHAR(3) NOT NULL DEFAULT 'TZS',
  status ENUM('owed','processing','paid','cancelled') NOT NULL DEFAULT 'owed',
  payout_method VARCHAR(40) NULL,
  reference VARCHAR(120) NULL,
  notes TEXT NULL,
  paid_at DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  created_by VARCHAR(120) NULL,
  KEY idx_settle_tenant (tenant_id),
  KEY idx_settle_status (status),
  CONSTRAINT fk_settle_tenant FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ensure every active tenant has platform collection row
INSERT IGNORE INTO tessera_tenant_payment_methods (tenant_id, mode, gateway_type, status)
SELECT id, 'platform', NULL, 'active' FROM tessera_tenants;
