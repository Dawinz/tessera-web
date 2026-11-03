-- Hide legacy Registration nav (routes/trips setup) until Route manager ships (022)
-- Pages remain reachable by URL; role rows kept for a future route manager migration.

DELETE FROM category WHERE name = 'Registration';
