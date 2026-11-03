-- Route manager seed — stops + sample schedules/fares (import-style, additive).
-- Maps acronyms onto existing Tessera routes by corridor; safe to re-run.

INSERT INTO stops (id, name, category, location, coordinates, detail, tenant_id)
SELECT 1, 'Liverpool', 'Food break', 'Mombo', '', '', 1 FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM stops WHERE id = 1);

INSERT INTO stops (id, name, category, location, coordinates, detail, tenant_id)
SELECT 2, 'Mukambo Border', 'Border', 'Zambia', '', '', 1 FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM stops WHERE id = 2);

UPDATE routes SET route_acronym = 'DL1SRC'
WHERE (fullname LIKE '%Lubumbashi%' OR route_name LIKE '%Lbsh%')
  AND start_route LIKE '%Dar%'
  AND (route_acronym IS NULL OR route_acronym = '');

UPDATE routes SET route_acronym = 'DN2SRC'
WHERE (fullname LIKE '%Nairobi%' OR route_name LIKE '%nrb%' OR route_name LIKE '%Nrb%')
  AND start_route LIKE '%Dar%'
  AND (route_acronym IS NULL OR route_acronym = '');

UPDATE routes SET route_acronym = 'DH3SRC'
WHERE (fullname LIKE '%Harare%' OR route_name LIKE '%Hre%')
  AND start_route LIKE '%Dar%'
  AND (route_acronym IS NULL OR route_acronym = '');

INSERT INTO route_stops (stop_id, route_id, company_id, duration, tenant_id)
SELECT 1, r.route_acronym, '1', '20 mins', 1
FROM routes r
WHERE r.route_acronym = 'DL1SRC'
  AND NOT EXISTS (
    SELECT 1 FROM route_stops rs WHERE rs.route_id = r.route_acronym AND rs.stop_id = 1
  )
LIMIT 1;

INSERT INTO route_stops (stop_id, route_id, company_id, duration, tenant_id)
SELECT 2, r.route_acronym, '1', '15 mins', 1
FROM routes r
WHERE r.route_acronym = 'DH3SRC'
  AND NOT EXISTS (
    SELECT 1 FROM route_stops rs WHERE rs.route_id = r.route_acronym AND rs.stop_id = 2
  )
LIMIT 1;

-- fare_price (post-015) or legacy fair_price — dynamic SQL avoids parse errors when column absent
SET @db := DATABASE();
SET @fare_col := (
  SELECT IF(COUNT(*) > 0, 'fare_price', 'fair_price')
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = @db AND TABLE_NAME = 'routes' AND COLUMN_NAME = 'fare_price'
);
SET @sql := CONCAT(
  'INSERT INTO route_fair (class, amount, currency, currency_id, route_id, company_id, state, class_id, tenant_id) ',
  'SELECT ''Luxury'', COALESCE(NULLIF(r.', @fare_col, ', 0), 50000), COALESCE(NULLIF(r.currency_symbol, ''''), ''/=''), ''1'', r.route_acronym, ''1'', ''default'', 0, 1 ',
  'FROM routes r WHERE r.route_acronym IN (''DL1SRC'', ''DN2SRC'', ''DH3SRC'') ',
  'AND NOT EXISTS (SELECT 1 FROM route_fair rf WHERE rf.route_id = r.route_acronym AND rf.class = ''Luxury'')'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

INSERT INTO route_schedules (
  route_id, vehicle_name, schedule_type, day_of_week, trip_date,
  departure_time, arrival_time, reporting_time, notes, status, company_id, uploader, tenant_id
)
SELECT 'DH3SRC', v.name, 'weekly', 'Tue', NULL, '05:00:00', '20:00:00', '04:30:00', 'Long haul', 'active', '1', 'seed', 1
FROM vehicle v
WHERE EXISTS (SELECT 1 FROM routes WHERE route_acronym = 'DH3SRC')
  AND NOT EXISTS (
    SELECT 1 FROM route_schedules rs
    WHERE rs.route_id = 'DH3SRC' AND rs.vehicle_name = v.name AND rs.day_of_week = 'Tue' AND rs.schedule_type = 'weekly'
  )
ORDER BY v.id ASC
LIMIT 1;

INSERT INTO route_schedules (
  route_id, vehicle_name, schedule_type, day_of_week, trip_date,
  departure_time, arrival_time, reporting_time, notes, status, company_id, uploader, tenant_id
)
SELECT 'DH3SRC', v.name, 'weekly', 'Thu', NULL, '05:00:00', '20:00:00', '04:30:00', 'Long haul', 'active', '1', 'seed', 1
FROM vehicle v
WHERE EXISTS (SELECT 1 FROM routes WHERE route_acronym = 'DH3SRC')
  AND NOT EXISTS (
    SELECT 1 FROM route_schedules rs
    WHERE rs.route_id = 'DH3SRC' AND rs.vehicle_name = v.name AND rs.day_of_week = 'Thu' AND rs.schedule_type = 'weekly'
  )
ORDER BY v.id ASC
LIMIT 1;

INSERT INTO route_schedules (
  route_id, vehicle_name, schedule_type, day_of_week, trip_date,
  departure_time, arrival_time, reporting_time, notes, status, company_id, uploader, tenant_id
)
SELECT 'DL1SRC', v.name, 'weekly', 'Mon', NULL, '06:00:00', '14:00:00', '05:30:00', 'Morning luxury', 'active', '1', 'seed', 1
FROM vehicle v
WHERE EXISTS (SELECT 1 FROM routes WHERE route_acronym = 'DL1SRC')
  AND NOT EXISTS (
    SELECT 1 FROM route_schedules rs
    WHERE rs.route_id = 'DL1SRC' AND rs.vehicle_name = v.name AND rs.day_of_week = 'Mon' AND rs.schedule_type = 'weekly'
  )
ORDER BY v.id ASC
LIMIT 1;
