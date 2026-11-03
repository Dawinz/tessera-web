-- Demo daily office expenses for SR Classic Coach (local QA).
-- Route must exist in assign_route + cash; month/year must match Daily Expenses filters.
-- Safe to re-run: removes prior demo rows tagged in detail.

DELETE FROM office_expenses
WHERE detail LIKE '[SR-DEMO]%';

SET @route := 'Dar es salaam to Lubumbashi';
SET @open_id := (SELECT id FROM cash WHERE route_name = @route ORDER BY id DESC LIMIT 1);
SET @cash_date := (SELECT open_date FROM cash WHERE id = @open_id);
SET @stock := (SELECT opening_stock FROM cash WHERE id = @open_id);
SET @year := '2026';
SET @user := 'admin';

INSERT INTO office_expenses (
  approved, cat_group, vehicle, date, detail, amount, amount_curr, username, time,
  insert_date_time, stock_amount, amount_remain, name, account, day, month, year,
  balance, account_from, icon, route_name, cash_hand, cash_date, cash_id, item, quantity, open_id, tenant_id
) VALUES
('', 'Border (Vehicle)', '5727AX05', '2026-01-03', '[SR-DEMO] Ushuru Tunduma border', 3750000, 0, @user, '08:15 AM', '2026-01-03', @stock, 13050000, 'Ushuru', 'Expenses', '03', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1),
('', 'Fuel(Vehicle)', '5727AX05', '2026-01-03', '[SR-DEMO] Diesel fill Dar depot', 850000, 0, @user, '09:40 AM', '2026-01-03', @stock, 12200000, 'Diesel', 'Expenses', '03', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, 'Diesel drum', 2, @open_id, 1),
('', 'Wages and salaries(Neutral)', '5727AX05', '2026-01-04', '[SR-DEMO] Driver allowance leg 1', 150000, 0, @user, '06:30 AM', '2026-01-04', @stock, 12050000, 'Allowance', 'Expenses', '04', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1),
('', 'Border (Vehicle)', '5727AX05', '2026-01-05', '[SR-DEMO] Toll gate Tunduma', 45000, 0, @user, '11:05 AM', '2026-01-05', @stock, 12005000, 'Toll gate', 'Expenses', '05', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1),
('', 'Wages and salaries(Neutral)', '5727AX05', '2026-01-06', '[SR-DEMO] Cargo loading Lubumbashi', 80000, 0, @user, '14:20 PM', '2026-01-06', @stock, 11925000, 'Cargo loading', 'Expenses', '06', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1),
('', 'Passenger(Vehicle)', '5727AX05', '2026-01-06', '[SR-DEMO] Crew lunch stop', 35000, 0, @user, '13:00 PM', '2026-01-06', @stock, 11890000, 'Lunch', 'Expenses', '06', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1),
('', 'Fuel(Vehicle)', '5727AX05', '2026-01-08', '[SR-DEMO] Diesel fill Mbeya', 620000, 0, @user, '07:55 AM', '2026-01-08', @stock, 11270000, 'Diesel', 'Expenses', '08', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, 'Diesel drum', 1, @open_id, 1),
('', 'Border (Vehicle)', '5727AX05', '2026-01-10', '[SR-DEMO] ZRA/DRG clearance', 280000, 0, @user, '10:30 AM', '2026-01-10', @stock, 10990000, 'ZRA/DRG', 'Expenses', '10', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1),
('', 'Insurance(Vehicle)', '5727AX05', '2026-01-12', '[SR-DEMO] Tanzania road licence', 120000, 0, @user, '08:00 AM', '2026-01-12', @stock, 10870000, 'Tanzania', 'Expenses', '12', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1),
('', 'Office(Neutral)', 'Choose Vehicle', '2026-01-15', '[SR-DEMO] Office stationery Dar', 45000, 0, @user, '04:15 PM', '2026-01-15', @stock, 10825000, 'Drawing', 'Expenses', '15', 'January', @year, 0, '', '', @route, 0, @cash_date, 0, 'Receipt book', 5, @open_id, 1),
('', 'Border (Vehicle)', '1248AC07', '2026-08-02', '[SR-DEMO] Ushuru return trip', 3750000, 0, @user, '09:10 AM', '2026-08-02', @stock, 7075000, 'Ushuru', 'Expenses', '02', 'August', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1),
('', 'Fuel(Vehicle)', '1248AC07', '2026-08-02', '[SR-DEMO] Diesel return leg', 920000, 0, @user, '11:45 AM', '2026-08-02', @stock, 6155000, 'Diesel', 'Expenses', '02', 'August', @year, 0, '', '', @route, 0, @cash_date, 0, 'Diesel drum', 2, @open_id, 1),
('', 'Wages and salaries(Neutral)', '1248AC07', '2026-08-03', '[SR-DEMO] Conductor allowance', 120000, 0, @user, '06:00 AM', '2026-08-03', @stock, 6035000, 'Allowance', 'Expenses', '03', 'August', @year, 0, '', '', @route, 0, @cash_date, 0, '-', 0, @open_id, 1);

UPDATE cash
SET amount_remain = (
  SELECT amount_remain FROM (
    SELECT amount_remain FROM office_expenses
    WHERE open_id = @open_id AND route_name = @route
    ORDER BY id DESC LIMIT 1
  ) t
)
WHERE id = @open_id;
