-- Tickets Schedule nav: replace Routes & Trips with Schedule (018)
UPDATE category SET name = 'Schedule', link = 'schedule.php' WHERE name = 'Routes & Trips';
UPDATE roles SET name = 'Schedule', file = 'schedule.php' WHERE file IN ('routs.php', 'routes.php') OR name = 'Routes & Trips';
UPDATE roles_assigned SET name = 'Schedule', file = 'schedule.php' WHERE file IN ('routs.php', 'routes.php') OR name = 'Routes & Trips';
