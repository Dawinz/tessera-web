-- Hybrid tenancy: shared platform registry + optional dedicated app DBs per operator.
-- Safe to re-run. Apply on ticket (registry) DB only.

SET @db := DATABASE();

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_tenants' AND COLUMN_NAME='db_mode');
SET @sql := IF(@exists=0,
  "ALTER TABLE tessera_tenants ADD COLUMN db_mode ENUM('shared','dedicated') NOT NULL DEFAULT 'shared' AFTER plan, ALGORITHM=INSTANT",
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_tenants' AND COLUMN_NAME='db_ticket');
SET @sql := IF(@exists=0,
  'ALTER TABLE tessera_tenants ADD COLUMN db_ticket VARCHAR(64) NULL AFTER db_mode, ALGORITHM=INSTANT',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_tenants' AND COLUMN_NAME='db_cargo');
SET @sql := IF(@exists=0,
  'ALTER TABLE tessera_tenants ADD COLUMN db_cargo VARCHAR(64) NULL AFTER db_ticket, ALGORITHM=INSTANT',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_tenants' AND COLUMN_NAME='db_account');
SET @sql := IF(@exists=0,
  'ALTER TABLE tessera_tenants ADD COLUMN db_account VARCHAR(64) NULL AFTER db_cargo, ALGORITHM=INSTANT',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=@db AND TABLE_NAME='tessera_tenants' AND COLUMN_NAME='db_provisioned_at');
SET @sql := IF(@exists=0,
  'ALTER TABLE tessera_tenants ADD COLUMN db_provisioned_at TIMESTAMP NULL DEFAULT NULL AFTER db_account, ALGORITHM=INSTANT',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Flagship stays on shared ticket/cargo/account.
UPDATE tessera_tenants
SET db_mode = 'shared', db_ticket = NULL, db_cargo = NULL, db_account = NULL
WHERE slug = 'sr-classic';

-- Every other operator: dedicated DBs (names filled by provisioner).
UPDATE tessera_tenants
SET db_mode = 'dedicated'
WHERE slug <> 'sr-classic' AND (db_mode = 'shared' OR db_mode IS NULL OR db_ticket IS NULL OR db_ticket = '');
