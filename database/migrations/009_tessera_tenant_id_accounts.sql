-- Wave H3: tenant_id on accounts (account) business tables. DEFAULT 1 = sr-classic.
-- Apply on account DB. Safe to re-run. Column ADD prefers INSTANT; indexes separate.

SET @db := DATABASE();

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='vehicle_account' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE vehicle_account ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='vehicle_account' AND INDEX_NAME='idx_vehicle_account_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_vehicle_account_tenant ON vehicle_account (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='job_card' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE job_card ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='job_card' AND INDEX_NAME='idx_job_card_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_job_card_tenant ON job_card (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='manifest' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE manifest ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='manifest' AND INDEX_NAME='idx_acc_manifest_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_acc_manifest_tenant ON manifest (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='route_manifest' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE route_manifest ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='route_manifest' AND INDEX_NAME='idx_route_manifest_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_route_manifest_tenant ON route_manifest (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='office_expenses' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE office_expenses ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='office_expenses' AND INDEX_NAME='idx_office_expenses_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_office_expenses_tenant ON office_expenses (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='cash' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE cash ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='cash' AND INDEX_NAME='idx_cash_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_cash_tenant ON cash (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='route' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE route ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='route' AND INDEX_NAME='idx_route_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_route_tenant ON route (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE vehicle_account SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE job_card SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE manifest SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE route_manifest SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE office_expenses SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE cash SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE route SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
