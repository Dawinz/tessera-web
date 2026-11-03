-- Wave J: invoice lifecycle fields for Control billing (ClickPesa platform collect later).
-- Apply on ticket DB. Safe to re-run.

SET @db := DATABASE();

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_invoices' AND COLUMN_NAME='paid_at');
SET @sql := IF(@exists=0, 'ALTER TABLE tessera_invoices ADD COLUMN paid_at DATETIME NULL AFTER status', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_invoices' AND COLUMN_NAME='payment_link');
SET @sql := IF(@exists=0, 'ALTER TABLE tessera_invoices ADD COLUMN payment_link VARCHAR(512) NULL AFTER notes', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_invoices' AND COLUMN_NAME='external_ref');
SET @sql := IF(@exists=0, 'ALTER TABLE tessera_invoices ADD COLUMN external_ref VARCHAR(128) NULL AFTER payment_link', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_invoices' AND COLUMN_NAME='provider');
SET @sql := IF(@exists=0, "ALTER TABLE tessera_invoices ADD COLUMN provider VARCHAR(32) NOT NULL DEFAULT 'manual' AFTER external_ref", 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
