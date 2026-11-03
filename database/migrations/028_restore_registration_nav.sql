-- Restore Tickets Registration ops-nav (reverses 022_hide_registration_nav.sql).
-- Target: ticket DB. Safe to re-run.

INSERT INTO category (name, image, link, arrangement, category)
SELECT 'Registration', 'plus-square', '', 5, lvl.level
FROM (SELECT 'Super Administrator' AS level UNION SELECT 'Administrator') lvl
WHERE NOT EXISTS (
  SELECT 1 FROM category c WHERE c.name = 'Registration' AND c.category = lvl.level
);
