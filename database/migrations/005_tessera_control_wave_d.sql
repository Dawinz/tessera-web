-- Tessera Control Wave D — billing history, invoices stub, support tickets, ops meta
-- Apply after 004_tessera_control_wave_c.sql

CREATE TABLE IF NOT EXISTS tessera_plan_changes (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  tenant_id INT UNSIGNED NOT NULL,
  from_plan VARCHAR(64) NULL,
  to_plan VARCHAR(64) NOT NULL,
  effective_date DATE NOT NULL,
  notes VARCHAR(512) NULL,
  actor VARCHAR(128) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_plan_changes_tenant (tenant_id),
  KEY idx_plan_changes_effective (effective_date),
  CONSTRAINT fk_plan_changes_tenant
    FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_invoices (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  tenant_id INT UNSIGNED NOT NULL,
  period_label VARCHAR(64) NOT NULL,
  amount_tzs INT UNSIGNED NOT NULL DEFAULT 0,
  status ENUM('draft','sent','paid','void') NOT NULL DEFAULT 'draft',
  due_date DATE NULL,
  notes VARCHAR(512) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_invoices_tenant (tenant_id),
  KEY idx_invoices_status (status),
  CONSTRAINT fk_invoices_tenant
    FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_support_tickets (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  tenant_id INT UNSIGNED NULL,
  subject VARCHAR(255) NOT NULL,
  body TEXT NULL,
  status ENUM('open','pending','resolved','closed') NOT NULL DEFAULT 'open',
  priority ENUM('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  requester_email VARCHAR(255) NULL,
  assignee VARCHAR(128) NULL,
  sla_first_response_due DATETIME NULL,
  sla_resolve_due DATETIME NULL,
  first_responded_at DATETIME NULL,
  resolved_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_support_status (status),
  KEY idx_support_tenant (tenant_id),
  KEY idx_support_sla (sla_resolve_due),
  CONSTRAINT fk_support_tickets_tenant
    FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_ops_meta (
  meta_key VARCHAR(64) NOT NULL,
  meta_value TEXT NULL,
  updated_by VARCHAR(128) NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (meta_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tessera_ops_meta (meta_key, meta_value, updated_by)
VALUES ('deploy_status', 'unknown', 'system')
ON DUPLICATE KEY UPDATE meta_key = meta_key;

ALTER TABLE tessera_onboarding_cases
  ADD COLUMN hypercare_rota VARCHAR(255) NULL AFTER owner_label;
