-- Wave H2: tenant_id on cargo (cargo) business tables. DEFAULT 1 = sr-classic.
-- Apply on cargo DB. Safe to re-run. Column ADD prefers INSTANT; indexes separate.

SET @db := DATABASE();

-- parcel_info
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='parcel_info' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE parcel_info ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='parcel_info' AND INDEX_NAME='idx_parcel_info_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_parcel_info_tenant ON parcel_info (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- manifest
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='manifest' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE manifest ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='manifest' AND INDEX_NAME='idx_manifest_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_manifest_tenant ON manifest (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- manifest_entries (canonical) or legacy misspelling manifest_entires (pre-015)
SET @me_tbl := IF(
  (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA=@db AND TABLE_NAME='manifest_entries') > 0,
  'manifest_entries',
  IF(
    (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA=@db AND TABLE_NAME='manifest_entires') > 0,
    'manifest_entires',
    NULL
  )
);
SET @exists := IF(@me_tbl IS NULL, 1, (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME=@me_tbl AND COLUMN_NAME='tenant_id'));
SET @sql := IF(@exists=0, CONCAT('ALTER TABLE `', @me_tbl, '` ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT'), 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @idx := IF(@me_tbl = 'manifest_entries', 'idx_manifest_entries_tenant', 'idx_manifest_entires_tenant');
SET @exists := IF(@me_tbl IS NULL, 1, (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME=@me_tbl AND INDEX_NAME=@idx));
SET @sql := IF(@exists=0, CONCAT('CREATE INDEX `', @idx, '` ON `', @me_tbl, '` (tenant_id)'), 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- vehicle_name
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='vehicle_name' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE vehicle_name ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='vehicle_name' AND INDEX_NAME='idx_vehicle_name_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_vehicle_name_tenant ON vehicle_name (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- vehicles
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='vehicles' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE vehicles ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='vehicles' AND INDEX_NAME='idx_vehicles_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_vehicles_tenant ON vehicles (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- routes
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='routes' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE routes ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='routes' AND INDEX_NAME='idx_routes_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_routes_tenant ON routes (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- store
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='store' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE store ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='store' AND INDEX_NAME='idx_store_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_store_tenant ON store (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- expenses
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='expenses' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE expenses ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='expenses' AND INDEX_NAME='idx_expenses_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_expenses_tenant ON expenses (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE parcel_info SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE manifest SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
SET @sql := IF(@me_tbl IS NULL, 'SELECT 1', CONCAT('UPDATE `', @me_tbl, '` SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL'));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
UPDATE vehicle_name SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE vehicles SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE routes SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE store SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE expenses SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
