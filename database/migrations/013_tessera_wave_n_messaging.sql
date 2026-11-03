-- Wave N: tenant messaging credentials + event prefs + outbound queue
-- Apply to ticket DB

CREATE TABLE IF NOT EXISTS tessera_tenant_messaging (
  tenant_id INT UNSIGNED NOT NULL PRIMARY KEY,
  sms_enabled TINYINT(1) NOT NULL DEFAULT 0,
  sms_provider VARCHAR(40) NULL COMMENT 'nextsms|mshastra|other',
  sms_sender_id VARCHAR(32) NULL,
  sms_username VARCHAR(120) NULL,
  sms_api_key_enc MEDIUMTEXT NULL,
  sms_api_key_last4 VARCHAR(8) NULL,
  whatsapp_enabled TINYINT(1) NOT NULL DEFAULT 0,
  whatsapp_provider VARCHAR(40) NULL,
  whatsapp_api_key_enc MEDIUMTEXT NULL,
  whatsapp_api_key_last4 VARCHAR(8) NULL,
  whatsapp_instance VARCHAR(80) NULL,
  push_enabled TINYINT(1) NOT NULL DEFAULT 0,
  push_service_url VARCHAR(255) NULL,
  push_api_key_enc MEDIUMTEXT NULL,
  events_json JSON NULL COMMENT 'per-event channel toggles',
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  updated_by VARCHAR(120) NULL,
  CONSTRAINT fk_tm_tenant FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tessera_notification_outbox (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tenant_id INT UNSIGNED NOT NULL,
  channel ENUM('sms','whatsapp','push') NOT NULL,
  event_code VARCHAR(64) NOT NULL,
  recipient VARCHAR(160) NOT NULL,
  payload_json JSON NULL,
  status ENUM('pending','sent','failed','skipped') NOT NULL DEFAULT 'pending',
  attempts INT NOT NULL DEFAULT 0,
  last_error VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  sent_at DATETIME NULL,
  KEY idx_outbox_pending (status, id),
  KEY idx_outbox_tenant (tenant_id),
  CONSTRAINT fk_outbox_tenant FOREIGN KEY (tenant_id) REFERENCES tessera_tenants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
