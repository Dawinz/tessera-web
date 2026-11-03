-- Per-vehicle seat layout (JSON row patterns + optional extra seats).
-- Safe to re-run: skips if column already exists.

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'vehicle'
    AND COLUMN_NAME = 'seat_layout_json'
);
SET @ddl := IF(
  @col_exists = 0,
  'ALTER TABLE vehicle ADD COLUMN seat_layout_json TEXT NULL AFTER detail',
  'SELECT 1'
);
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
