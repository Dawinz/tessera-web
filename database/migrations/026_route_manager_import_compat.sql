-- Route manager import (23/24 zip) — additive columns for Tessera routes / sub_route.
SET @db := DATABASE();

-- locations_names.short (import route wizards)
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'locations_names' AND COLUMN_NAME = 'short');
SET @sql := IF(@exists = 0, 'ALTER TABLE locations_names ADD COLUMN short VARCHAR(20) NOT NULL DEFAULT \'\' AFTER name', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE locations_names SET short = UPPER(LEFT(name, 3)) WHERE short = '' OR short IS NULL;

-- vehcile_category (import typo; local fallback when transport_general is absent)
CREATE TABLE IF NOT EXISTS `vehcile_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `rating` float NOT NULL DEFAULT 0,
  `detail` text DEFAULT NULL,
  `partiuclar` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO vehcile_category (id, name, rating, detail, partiuclar) VALUES
(1, 'Luxury', 5, '', ''),
(2, 'Semi Luxury', 4, '', ''),
(3, 'Standard', 3, '', '');

-- routes: import metadata
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'routes' AND COLUMN_NAME = 'company_id');
SET @sql := IF(@exists = 0, "ALTER TABLE routes ADD COLUMN company_id VARCHAR(100) NOT NULL DEFAULT '1' AFTER tenant_id", 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'routes' AND COLUMN_NAME = 'route_initial_letters');
SET @sql := IF(@exists = 0, 'ALTER TABLE routes ADD COLUMN route_initial_letters VARCHAR(10) NULL DEFAULT NULL AFTER route_acronym', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'routes' AND COLUMN_NAME = 'journey_duration_value');
SET @sql := IF(@exists = 0, 'ALTER TABLE routes ADD COLUMN journey_duration_value INT UNSIGNED NOT NULL DEFAULT 0 AFTER route_initial_letters', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'routes' AND COLUMN_NAME = 'journey_duration_unit');
SET @sql := IF(@exists = 0, "ALTER TABLE routes ADD COLUMN journey_duration_unit VARCHAR(20) NOT NULL DEFAULT 'hrs' AFTER journey_duration_value", 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'routes' AND COLUMN_NAME = 'distance');
SET @sql := IF(@exists = 0, 'ALTER TABLE routes ADD COLUMN distance INT UNSIGNED NOT NULL DEFAULT 0 AFTER journey_duration_unit', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE routes SET route_initial_letters = UPPER(LEFT(route_name, 3))
WHERE (route_initial_letters IS NULL OR route_initial_letters = '') AND route_name <> '';

UPDATE routes SET route_acronym = CONCAT('R', id)
WHERE (route_acronym IS NULL OR route_acronym = '') AND route_type = 'Main Route';

-- sub_route: import leg model (keep legacy columns)
SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'parent_route');
SET @sql := IF(@exists = 0, 'ALTER TABLE sub_route ADD COLUMN parent_route VARCHAR(100) NOT NULL DEFAULT \'\' AFTER id', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'route_id');
SET @sql := IF(@exists = 0, 'ALTER TABLE sub_route ADD COLUMN route_id VARCHAR(100) NOT NULL DEFAULT \'\' AFTER parent_route', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'starting_point');
SET @sql := IF(@exists = 0, 'ALTER TABLE sub_route ADD COLUMN starting_point VARCHAR(200) NOT NULL DEFAULT \'\' AFTER route_id', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'ending_point');
SET @sql := IF(@exists = 0, 'ALTER TABLE sub_route ADD COLUMN ending_point VARCHAR(200) NOT NULL DEFAULT \'\' AFTER starting_point', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'journey_duration_value');
SET @sql := IF(@exists = 0, 'ALTER TABLE sub_route ADD COLUMN journey_duration_value INT UNSIGNED NOT NULL DEFAULT 0 AFTER ending_point', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'journey_duration_unit');
SET @sql := IF(@exists = 0, "ALTER TABLE sub_route ADD COLUMN journey_duration_unit VARCHAR(20) NOT NULL DEFAULT 'hrs' AFTER journey_duration_value", 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'distance');
SET @sql := IF(@exists = 0, 'ALTER TABLE sub_route ADD COLUMN distance INT UNSIGNED NOT NULL DEFAULT 0 AFTER journey_duration_unit', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'state');
SET @sql := IF(@exists = 0, 'ALTER TABLE sub_route ADD COLUMN state TINYINT UNSIGNED NOT NULL DEFAULT 1 AFTER distance', 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exists := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'sub_route' AND COLUMN_NAME = 'company_id');
SET @sql := IF(@exists = 0, "ALTER TABLE sub_route ADD COLUMN company_id VARCHAR(100) NOT NULL DEFAULT '1' AFTER state", 'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE sub_route s
INNER JOIN routes r ON r.id = s.main_route_no
SET s.starting_point = IF(s.starting_point = '', s.start_route, s.starting_point),
    s.ending_point = IF(s.ending_point = '', s.end_route, s.ending_point),
    s.parent_route = COALESCE(NULLIF(r.route_acronym, ''), CONCAT('R', r.id)),
    s.route_id = CONCAT('SR', s.id)
WHERE s.parent_route = '' OR s.starting_point = '';
