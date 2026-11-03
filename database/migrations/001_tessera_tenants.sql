-- Tessera multi-tenant foundation (Phase 3)
-- Apply on staging first. Flagship SR Classic = tenant_id 1.

CREATE TABLE IF NOT EXISTS tessera_tenants (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug VARCHAR(64) NOT NULL,
  legal_name VARCHAR(255) NOT NULL,
  display_name VARCHAR(255) NOT NULL,
  status ENUM('active','suspended','provisioning') NOT NULL DEFAULT 'provisioning',
  plan VARCHAR(64) NOT NULL DEFAULT 'flagship',
  primary_domain VARCHAR(255) NULL,
  logo_url VARCHAR(512) NULL,
  primary_color VARCHAR(16) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_tessera_tenants_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tessera_tenants (id, slug, legal_name, display_name, status, plan, primary_domain, primary_color)
VALUES (1, 'sr-classic', 'SR Classic Coach', 'SR Classic Coach', 'active', 'flagship', 'tessera.co.tz', '#E53E3E')
ON DUPLICATE KEY UPDATE display_name = VALUES(display_name);

-- Example: add tenant_id to a core table when migrating (run per table after audit)
-- ALTER TABLE users ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id;
-- ALTER TABLE users ADD KEY idx_users_tenant (tenant_id);
