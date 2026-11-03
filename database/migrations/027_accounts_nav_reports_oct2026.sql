-- Accounts navigation reorganisation + Purchases menu (Oct 2026 Drive drop).
-- Target: account DB. Uses roles_assigned + arrangement (Tessera runtime spelling).
-- Safe to re-run.

-- ---------- Top-level categories (Super Administrator + Administrator) ----------

UPDATE category SET name = 'Manifests', image = 'clipboard', link = 'manifest.php', arrangement = 1
WHERE name IN ('Manifest', 'Manifests') AND category IN ('Super Administrator', 'Administrator');

UPDATE category SET name = 'Cash Book', image = 'credit-card', link = 'daily.php', arrangement = 2
WHERE name IN ('Daily Expenses', 'Cash Book') AND category IN ('Super Administrator', 'Administrator');

DELETE FROM category WHERE name = 'Withdraw' AND category IN ('Super Administrator', 'Administrator');

DELETE FROM category WHERE name = 'Registration' AND category IN ('Super Administrator', 'Administrator');

UPDATE category SET name = 'Financial Reports', image = 'trending-up', link = 'report_profit_loss.php', arrangement = 4
WHERE name IN ('Report', 'Financial Reports') AND category IN ('Super Administrator', 'Administrator');

UPDATE category SET name = 'Currency', image = 'dollar-sign', link = 'currency.php', arrangement = 6
WHERE name = 'Currency' AND category IN ('Super Administrator', 'Administrator');

INSERT INTO category (name, image, link, arrangement, category)
SELECT 'Fleet Reports', 'truck', 'report_trips.php', 5, lvl.level
FROM (SELECT 'Super Administrator' AS level UNION SELECT 'Administrator') lvl
WHERE NOT EXISTS (
  SELECT 1 FROM category c WHERE c.name = 'Fleet Reports' AND c.category = lvl.level
);

INSERT INTO category (name, image, link, arrangement, category)
SELECT 'Setup', 'settings', 'Register_routs.php', 7, lvl.level
FROM (SELECT 'Super Administrator' AS level UNION SELECT 'Administrator') lvl
WHERE NOT EXISTS (
  SELECT 1 FROM category c WHERE c.name = 'Setup' AND c.category = lvl.level
);

UPDATE category SET arrangement = arrangement + 1
WHERE arrangement >= 3 AND category IN ('Super Administrator', 'Administrator')
  AND NOT EXISTS (
    SELECT 1 FROM category c2 WHERE c2.name = 'Purchases' AND c2.category = category.category
  );

INSERT INTO category (name, image, link, arrangement, category)
SELECT 'Purchases', 'shopping-cart', 'jobcard.php', 3, lvl.level
FROM (SELECT 'Super Administrator' AS level UNION SELECT 'Administrator') lvl
WHERE NOT EXISTS (
  SELECT 1 FROM category c WHERE c.name = 'Purchases' AND c.category = lvl.level
);

-- Normalise order after inserts
UPDATE category SET arrangement = 1 WHERE name = 'Manifests' AND category IN ('Super Administrator', 'Administrator');
UPDATE category SET arrangement = 2 WHERE name = 'Cash Book' AND category IN ('Super Administrator', 'Administrator');
UPDATE category SET arrangement = 3 WHERE name = 'Purchases' AND category IN ('Super Administrator', 'Administrator');
UPDATE category SET arrangement = 4 WHERE name = 'Financial Reports' AND category IN ('Super Administrator', 'Administrator');
UPDATE category SET arrangement = 5 WHERE name = 'Fleet Reports' AND category IN ('Super Administrator', 'Administrator');
UPDATE category SET arrangement = 6 WHERE name = 'Currency' AND category IN ('Super Administrator', 'Administrator');
UPDATE category SET arrangement = 7 WHERE name = 'Setup' AND category IN ('Super Administrator', 'Administrator');

-- ---------- Rename menu groups on user assignments ----------

UPDATE roles_assigned SET category = 'Manifests' WHERE category IN ('Manifest', 'Manifests');
UPDATE roles_assigned SET category = 'Cash Book' WHERE category IN ('Daily Expenses', 'Cash Book');
UPDATE roles_assigned SET category = 'Financial Reports' WHERE category = 'Report';
UPDATE roles_assigned SET category = 'Setup' WHERE category = 'Registration';

UPDATE roles_assigned SET name = 'Manifests' WHERE file = 'manifest.php' AND category = 'Manifests';

INSERT INTO roles_assigned (name, level, file, username, category, arrangement, sub_route)
SELECT 'Daily Expenses', u.role, 'daily.php', u.username, 'Cash Book', 1, ''
FROM users u
WHERE u.role IN ('Super Administrator', 'Administrator')
  AND NOT EXISTS (
    SELECT 1 FROM roles_assigned ra WHERE ra.username = u.username AND ra.file = 'daily.php' AND ra.category = 'Cash Book'
  );

INSERT INTO roles_assigned (name, level, file, username, category, arrangement, sub_route)
SELECT 'Cash Withdrawals', u.role, 'withdraw.php', u.username, 'Cash Book', 2, ''
FROM users u
WHERE u.role IN ('Super Administrator', 'Administrator')
  AND NOT EXISTS (
    SELECT 1 FROM roles_assigned ra WHERE ra.username = u.username AND ra.file = 'withdraw.php' AND ra.category = 'Cash Book'
  );

UPDATE roles_assigned SET name = 'Vehicle Statement', category = 'Fleet Reports', arrangement = 1
WHERE file = 'vehicle_report.php';

UPDATE roles_assigned SET name = 'Fleet Account Summary', category = 'Fleet Reports', arrangement = 5
WHERE file IN ('account_vehciles.php', 'account_vehicles.php');

UPDATE roles_assigned SET name = 'Expenses by Category & Particular', category = 'Financial Reports', arrangement = 5
WHERE file = 'account_filter.php';

UPDATE roles_assigned SET name = 'Routes', category = 'Setup', arrangement = 1 WHERE file IN ('Register_routs.php', 'Register_routes.php');
UPDATE roles_assigned SET name = 'Route Assignment', category = 'Setup', arrangement = 2 WHERE file = 'assign_route.php';
UPDATE roles_assigned SET name = 'System Constants', category = 'Setup', arrangement = 3 WHERE file = 'constants.php';
UPDATE roles_assigned SET name = 'Account Categories', category = 'Setup', arrangement = 4 WHERE file = 'particular.php';
UPDATE roles_assigned SET name = 'Account Particulars', category = 'Setup', arrangement = 5 WHERE file = 'particular_detail.php';
UPDATE roles_assigned SET name = 'Vehicle Categories', category = 'Setup', arrangement = 6 WHERE file = 'vehicle_category.php';
UPDATE roles_assigned SET name = 'Vehicle Category Particulars', category = 'Setup', arrangement = 7 WHERE file = 'vehicle_category_details.php';

UPDATE roles_assigned SET name = 'Currencies', category = 'Currency', arrangement = 1 WHERE file = 'currency.php';
UPDATE roles_assigned SET name = 'Exchange Rates', category = 'Currency', arrangement = 2 WHERE file = 'currency_value.php';
UPDATE roles_assigned SET name = 'Route Currencies', category = 'Currency', arrangement = 3 WHERE file = 'route_currency.php';

-- New report + purchase pages (all admin users)

INSERT INTO roles_assigned (name, level, file, username, category, arrangement, sub_route)
SELECT specs.n, u.role, specs.f, u.username, specs.c, specs.a, specs.s
FROM users u
JOIN (
  SELECT 'Profit & Loss Statement' AS n, 'report_profit_loss.php' AS f, 'Financial Reports' AS c, 1 AS a, 'Report' AS s
  UNION SELECT 'Cash Flow Statement', 'report_cash_flow.php', 'Financial Reports', 2, 'Report'
  UNION SELECT 'Revenue Analysis', 'report_revenue.php', 'Financial Reports', 3, 'Report'
  UNION SELECT 'Expense Analysis', 'report_expenses.php', 'Financial Reports', 4, 'Report'
  UNION SELECT 'Outstanding Payables', 'report_payables.php', 'Financial Reports', 6, 'Report'
  UNION SELECT 'Currency Position', 'report_currency.php', 'Financial Reports', 7, 'Report'
  UNION SELECT 'Trip Profitability', 'report_trips.php', 'Fleet Reports', 2, 'Report'
  UNION SELECT 'Vehicle Performance', 'report_vehicles.php', 'Fleet Reports', 3, 'Report'
  UNION SELECT 'Route Profitability', 'report_routes.php', 'Fleet Reports', 4, 'Report'
  UNION SELECT 'Job Cards (Purchase Invoices)', 'jobcard.php', 'Purchases', 1, ''
  UNION SELECT 'Purchase History', 'report_purchase_history.php', 'Purchases', 2, 'Report'
  UNION SELECT 'Purchases by Vehicle', 'report_purchases_vehicle.php', 'Purchases', 3, 'Report'
  UNION SELECT 'Purchases by Supplier', 'report_purchases_supplier.php', 'Purchases', 4, 'Report'
  UNION SELECT 'Purchases by Item', 'report_purchases_items.php', 'Purchases', 5, 'Report'
) specs
WHERE u.role IN ('Super Administrator', 'Administrator')
  AND NOT EXISTS (
    SELECT 1 FROM roles_assigned ra WHERE ra.username = u.username AND ra.file = specs.f
  );
