-- Route manager — top-level Tickets nav (additive; Schedule / Vehicle manager unchanged).

INSERT INTO category (name, image, link, arrangement, category)
SELECT 'Route manager', 'map', 'route_manager.php', 6, 'Super Administrator'
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM category WHERE name = 'Route manager' AND category = 'Super Administrator'
);

INSERT INTO category (name, image, link, arrangement, category)
SELECT 'Route manager', 'map', 'route_manager.php', 6, 'Administrator'
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM category WHERE name = 'Route manager' AND category = 'Administrator'
);

INSERT INTO roles (name, level, file, username, category, image, arrangement)
SELECT 'Route manager', 'Super Administrator', 'route_manager.php', '', 'Route manager', 'map', 6
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM roles WHERE file = 'route_manager.php' AND category = 'Route manager' AND level = 'Super Administrator'
);

INSERT INTO roles (name, level, file, username, category, image, arrangement)
SELECT 'Route manager', 'Administrator', 'route_manager.php', '', 'Route manager', 'map', 6
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM roles WHERE file = 'route_manager.php' AND category = 'Route manager' AND level = 'Administrator'
);

INSERT INTO roles_assigned (name, level, file, username, category, arrangement)
SELECT 'Route manager', u.role, 'route_manager.php', u.username, 'Route manager', 6
FROM users u
WHERE u.role IN ('Super Administrator', 'Administrator')
  AND NOT EXISTS (
    SELECT 1 FROM roles_assigned ra
    WHERE ra.username = u.username AND ra.category = 'Route manager'
  );

INSERT INTO pages (page, page_name, detail, image)
SELECT 'route_manager.php', 'Route manager', 'Routes, weekly schedules, stops, and fares', 'map'
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM pages WHERE page = 'route_manager.php'
);
