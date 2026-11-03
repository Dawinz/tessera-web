-- Vehicle manager: top-level Tickets nav (like Manifest), not buried in Registration (021)

INSERT INTO category (name, image, link, arrangement, category)
SELECT 'Vehicle manager', 'truck', 'vehicle_manager.php', 4, 'Super Administrator'
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM category WHERE name = 'Vehicle manager' AND category = 'Super Administrator'
);

INSERT INTO category (name, image, link, arrangement, category)
SELECT 'Vehicle manager', 'truck', 'vehicle_manager.php', 4, 'Administrator'
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM category WHERE name = 'Vehicle manager' AND category = 'Administrator'
);

INSERT INTO roles (name, level, file, username, category, image, arrangement)
SELECT 'Vehicle manager', 'Super Administrator', 'vehicle_manager.php', '', 'Vehicle manager', 'truck', 4
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM roles WHERE file = 'vehicle_manager.php' AND category = 'Vehicle manager' AND level = 'Super Administrator'
);

INSERT INTO roles (name, level, file, username, category, image, arrangement)
SELECT 'Vehicle manager', 'Administrator', 'vehicle_manager.php', '', 'Vehicle manager', 'truck', 4
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM roles WHERE file = 'vehicle_manager.php' AND category = 'Vehicle manager' AND level = 'Administrator'
);

INSERT INTO roles_assigned (name, level, file, username, category, arrangement)
SELECT 'Vehicle manager', u.role, 'vehicle_manager.php', u.username, 'Vehicle manager', 4
FROM users u
WHERE u.role IN ('Super Administrator', 'Administrator')
  AND NOT EXISTS (
    SELECT 1 FROM roles_assigned ra
    WHERE ra.username = u.username AND ra.category = 'Vehicle manager'
  );

INSERT INTO roles_assigned (name, level, file, username, category, arrangement)
SELECT 'Vehicle manager', ra.level, 'vehicle_manager.php', ra.username, 'Vehicle manager', 4
FROM roles_assigned ra
WHERE ra.category = 'Registration'
  AND (
    ra.file IN ('vehicle_manager.php', 'register_buss.php', 'register_bus.php')
    OR ra.name IN ('Register Vehicle', 'Vehicle manager')
  )
  AND NOT EXISTS (
    SELECT 1 FROM roles_assigned x
    WHERE x.username = ra.username AND x.category = 'Vehicle manager'
  );

DELETE FROM roles_assigned
WHERE category = 'Registration'
  AND file IN ('vehicle_manager.php', 'register_buss.php', 'register_bus.php');

UPDATE pages SET page = 'vehicle_manager.php', page_name = 'Vehicle manager', detail = 'Fleet registry, seat layout, and assignments'
WHERE page IN ('register_buss.php', 'register_bus.php');
