-- Wave H: tenant_id on Tickets booking tables (flagship-safe DEFAULT 1 = sr-classic).
-- Apply on ticket DB. Safe to re-run (checks information_schema).

SET @db := DATABASE();

-- mobile_trips.tenant_id (optional table)
SET @tbl := (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'mobile_trips');
SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'mobile_trips' AND COLUMN_NAME = 'tenant_id'
);
SET @sql := IF(@tbl > 0 AND @exists = 0,
  'ALTER TABLE mobile_trips ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id, ADD INDEX idx_mobile_trips_tenant (tenant_id), ADD INDEX idx_mobile_trips_tenant_route (tenant_id, from_city, to_city, trip_date)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- mobile_bookings.tenant_id (optional table)
SET @tbl := (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'mobile_bookings');
SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'mobile_bookings' AND COLUMN_NAME = 'tenant_id'
);
SET @sql := IF(@tbl > 0 AND @exists = 0,
  'ALTER TABLE mobile_bookings ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id, ADD INDEX idx_mobile_bookings_tenant (tenant_id), ADD INDEX idx_mobile_bookings_tenant_ref (tenant_id, booking_ref)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- legacy booking.tenant_id (staff HTML + Control metrics)
SET @tbl := (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'booking');
SET @exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'booking' AND COLUMN_NAME = 'tenant_id'
);
SET @sql := IF(@tbl > 0 AND @exists = 0,
  'ALTER TABLE booking ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1, ALGORITHM=INSTANT',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @tbl := (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'booking');
SET @exists := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'booking' AND INDEX_NAME = 'idx_booking_tenant'
);
SET @sql := IF(@tbl > 0 AND @exists = 0,
  'CREATE INDEX idx_booking_tenant ON booking (tenant_id)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @tbl := (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'booking');
SET @exists := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'booking' AND INDEX_NAME = 'idx_booking_tenant_date'
);
SET @sql := IF(@tbl > 0 AND @exists = 0,
  'CREATE INDEX idx_booking_tenant_date ON booking (tenant_id, book_date)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Backfill any unexpected zeros (no-op when DEFAULT 1 already applied)
SET @tbl := (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'mobile_trips');
SET @sql := IF(@tbl > 0, 'UPDATE mobile_trips SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @tbl := (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'mobile_bookings');
SET @sql := IF(@tbl > 0, 'UPDATE mobile_bookings SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @tbl := (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'booking');
SET @sql := IF(@tbl > 0, 'UPDATE booking SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
