-- Tessera Control Wave E — in-app notifications
-- Apply after 005_tessera_control_wave_d.sql

CREATE TABLE IF NOT EXISTS tessera_notifications (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  type VARCHAR(64) NOT NULL,
  severity ENUM('info','warning','danger') NOT NULL DEFAULT 'info',
  title VARCHAR(255) NOT NULL,
  body TEXT NULL,
  tenant_id INT UNSIGNED NULL,
  href VARCHAR(512) NULL,
  dedupe_key VARCHAR(191) NULL,
  read_at DATETIME NULL,
  email_sent_at DATETIME NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_notify_dedupe (dedupe_key),
  KEY idx_notify_unread (read_at, created_at),
  KEY idx_notify_type (type),
  KEY idx_notify_tenant (tenant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tessera_ops_meta (meta_key, meta_value, updated_by)
VALUES
  ('notify_prefs', '{"email_suspend":true,"email_webhook_fail":true,"email_stuck_provisioning":true,"provisioning_days":7,"email_to":""}', 'system'),
  ('control_brand_subtitle', 'Control', 'system'),
  ('control_logo_url', '', 'system')
ON DUPLICATE KEY UPDATE meta_key = meta_key;
