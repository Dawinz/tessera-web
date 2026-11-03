-- Tessera Control — onboarding + audit (platform owner console)
-- Apply after 001_tessera_tenants.sql

CREATE TABLE IF NOT EXISTS tessera_onboarding_cases (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  tenant_id INT UNSIGNED NULL,
  company_name VARCHAR(255) NOT NULL,
  slug VARCHAR(64) NULL,
  contact_name VARCHAR(255) NULL,
  contact_email VARCHAR(255) NULL,
  contact_phone VARCHAR(64) NULL,
  stage ENUM(
    'kickoff',
    'provisioning',
    'migration',
    'uat',
    'training',
    'golive',
    'hypercare',
    'done',
    'cancelled'
  ) NOT NULL DEFAULT 'kickoff',
  go_live_date DATE NULL,
  checklist_json JSON NULL,
  notes TEXT NULL,
  owner_label VARCHAR(128) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_onboarding_stage (stage),
  KEY idx_onboarding_tenant (tenant_id),
  CONSTRAINT fk_onboarding_tenant
    FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_platform_audit (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  actor VARCHAR(128) NOT NULL,
  action VARCHAR(64) NOT NULL,
  tenant_id INT UNSIGNED NULL,
  meta_json JSON NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_audit_created (created_at),
  KEY idx_audit_tenant (tenant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
