-- Route manager — import scheduling model (additive; does not alter route_day / Schedule).
-- Safe to re-run. Apply on ticket DB.

SET @db := DATABASE();

-- Optional acronym key on routes (import uses DL1SRC-style keys in route_schedules.route_id)
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'routes' AND COLUMN_NAME = 'route_acronym');
SET @sql := IF(@exists = 0, 'ALTER TABLE routes ADD COLUMN route_acronym VARCHAR(100) NULL DEFAULT NULL AFTER route_name', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

CREATE TABLE IF NOT EXISTS `stops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` varchar(200) NOT NULL DEFAULT '',
  `location` varchar(100) NOT NULL DEFAULT '',
  `coordinates` varchar(100) NOT NULL DEFAULT '',
  `detail` text NOT NULL,
  `tenant_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_stops_tenant` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `route_stops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stop_id` int(11) NOT NULL,
  `route_id` varchar(100) NOT NULL,
  `company_id` varchar(100) NOT NULL DEFAULT '1',
  `duration` varchar(50) NOT NULL DEFAULT '',
  `tenant_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_route_stops_route` (`route_id`),
  KEY `idx_route_stops_tenant` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `route_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_id` varchar(100) NOT NULL,
  `vehicle_name` varchar(100) NOT NULL,
  `schedule_type` enum('weekly','once') NOT NULL DEFAULT 'weekly',
  `day_of_week` varchar(20) DEFAULT NULL,
  `trip_date` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `reporting_time` time DEFAULT NULL,
  `notes` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `company_id` varchar(100) NOT NULL DEFAULT '1',
  `uploader` varchar(100) NOT NULL DEFAULT '',
  `tenant_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `weekly_bus` (`route_id`,`vehicle_name`,`day_of_week`,`company_id`,`tenant_id`),
  UNIQUE KEY `once_bus` (`route_id`,`vehicle_name`,`trip_date`,`company_id`,`tenant_id`),
  KEY `idx_route_schedules_tenant` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `route_fair` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(100) NOT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `currency` varchar(100) NOT NULL DEFAULT '',
  `currency_id` varchar(100) NOT NULL DEFAULT '1',
  `route_id` varchar(100) NOT NULL,
  `company_id` varchar(100) NOT NULL DEFAULT '1',
  `state` varchar(100) NOT NULL DEFAULT 'default',
  `class_id` int(11) NOT NULL DEFAULT 0,
  `tenant_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_route_fair_route` (`route_id`),
  KEY `idx_route_fair_tenant` (`tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Backfill tenant_id on new tables if columns existed without it
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'stops' AND COLUMN_NAME = 'tenant_id');
SET @sql := IF(@exists = 0, 'ALTER TABLE stops ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'route_stops' AND COLUMN_NAME = 'tenant_id');
SET @sql := IF(@exists = 0, 'ALTER TABLE route_stops ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'route_schedules' AND COLUMN_NAME = 'tenant_id');
SET @sql := IF(@exists = 0, 'ALTER TABLE route_schedules ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'route_fair' AND COLUMN_NAME = 'tenant_id');
SET @sql := IF(@exists = 0, 'ALTER TABLE route_fair ADD COLUMN tenant_id INT UNSIGNED NOT NULL DEFAULT 1', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE stops SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE route_stops SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE route_schedules SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
UPDATE route_fair SET tenant_id = 1 WHERE tenant_id = 0 OR tenant_id IS NULL;
