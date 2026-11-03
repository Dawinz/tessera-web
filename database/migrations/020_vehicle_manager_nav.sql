-- Vehicle manager nav: replace Register Vehicle / register_buss.php (020)
UPDATE category SET name = 'Vehicle manager', link = 'vehicle_manager.php'
  WHERE link IN ('register_buss.php', 'register_bus.php') OR name = 'Register Vehicle';

UPDATE roles SET name = 'Vehicle manager', file = 'vehicle_manager.php'
  WHERE file IN ('register_buss.php', 'register_bus.php') OR name = 'Register Vehicle';

UPDATE roles_assigned SET name = 'Vehicle manager', file = 'vehicle_manager.php'
  WHERE file IN ('register_buss.php', 'register_bus.php') OR name = 'Register Vehicle';
