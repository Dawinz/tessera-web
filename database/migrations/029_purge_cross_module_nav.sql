-- Remove leftover cross-module ops-nav rows.
-- Module switching is Tickets | Cargo | Accounts pills only — not second-row "Cargo"/"Ticket" links.
-- Legacy SR Classic used ../sr_up/index.php and http://localhost/ticket/.

DELETE FROM ticket.category
WHERE name IN ('Cargo', 'Ticket', 'Tickets', 'Accounts')
   OR link LIKE '%sr_up%'
   OR link LIKE '%../cargo%'
   OR link LIKE '%../ticket%'
   OR link LIKE 'http://localhost/%';

DELETE FROM cargo.category
WHERE name IN ('Ticket', 'Tickets', 'Cargo', 'Accounts', 'Accounts Report')
   OR link LIKE '%sr_up%'
   OR link LIKE '%../ticket%'
   OR link LIKE 'http://localhost/%';

DELETE FROM account.category
WHERE name IN ('Cargo', 'Ticket', 'Tickets')
   OR link LIKE '%sr_up%'
   OR link LIKE '%../ticket%'
   OR link LIKE '%../cargo%'
   OR link LIKE 'http://localhost/%';
