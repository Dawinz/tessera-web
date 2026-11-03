-- Wave M: staff capability roles (tenant-scoped metadata on tickets users)
-- Apply to ticket DB

CREATE TABLE IF NOT EXISTS tessera_role_definitions (
  code VARCHAR(40) NOT NULL PRIMARY KEY,
  label VARCHAR(80) NOT NULL,
  description VARCHAR(255) NULL,
  capabilities_json JSON NOT NULL,
  sort_order INT NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO tessera_role_definitions (code, label, description, capabilities_json, sort_order) VALUES
('owner', 'Owner', 'Full operator access', JSON_ARRAY('settings','payouts','staff','tickets','cargo','accounts','notifications','api_keys','reports'), 10),
('manager', 'Manager', 'Operations across modules', JSON_ARRAY('settings','staff','tickets','cargo','accounts','notifications','reports'), 20),
('tickets_agent', 'Tickets agent', 'Booking and POS only', JSON_ARRAY('tickets'), 30),
('cargo_agent', 'Cargo agent', 'Cargo only', JSON_ARRAY('cargo'), 40),
('accountant', 'Accountant', 'Accounts, settlements, reports', JSON_ARRAY('accounts','reports','payouts'), 50)
ON DUPLICATE KEY UPDATE label=VALUES(label), description=VALUES(description), capabilities_json=VALUES(capabilities_json), sort_order=VALUES(sort_order);

-- Optional mapping: legacy users.role string → tessera role code (stored on users if column exists)
SET @col := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'tessera_role'
);
SET @sql := IF(@col = 0,
  'ALTER TABLE users ADD COLUMN tessera_role VARCHAR(40) NULL DEFAULT NULL AFTER role, ALGORITHM=INSTANT',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
