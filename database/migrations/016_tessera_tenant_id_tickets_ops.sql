-- Wave H follow-up: tenant_id on Tickets ops tables (vehicles / routes / trips).
-- Without this, every operator host sees the same registration data.
-- Safe to re-run. Apply on ticket DB.

SET @db := DATABASE();

-- vehicle
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='vehicle' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE vehicle ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='vehicle' AND INDEX_NAME='idx_vehicle_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_vehicle_tenant ON vehicle (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- routes
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='routes' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE routes ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='routes' AND INDEX_NAME='idx_routes_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_routes_tenant ON routes (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- route_day (trips)
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='route_day' AND COLUMN_NAME='tenant_id');
SET @sql := IF(@exists=0, 'ALTER TABLE route_day ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @exists := (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='route_day' AND INDEX_NAME='idx_route_day_tenant');
SET @sql := IF(@exists=0, 'CREATE INDEX idx_route_day_tenant ON route_day (tenant_id)', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Backfill: existing rows belong to flagship (sr-classic = 1)
UPDATE vehicle SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE routes SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE route_day SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
