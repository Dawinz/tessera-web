-- Dummy schedules for ticket.route_schedules
-- Uses existing routes (DL1SRC, DN2SRC, DH3SRC) and transport_general buses.

USE `ticket`;

SET NAMES utf8mb4;

INSERT INTO `route_schedules` (
  `route_id`, `vehicle_name`, `schedule_type`, `day_of_week`, `trip_date`,
  `departure_time`, `arrival_time`, `reporting_time`, `notes`,
  `status`, `company_id`, `uploader`
) VALUES
  -- DL1SRC Dar es salaam to Lubumbashi — weekly board (27 trips)
  -- Monday
  ('DL1SRC', '1101AB25', 'weekly', 'Mon', NULL, '06:00:00', '14:00:00', '05:30:00', 'Morning luxury service', 'active', '1', 'dummy'),
  ('DL1SRC', '1102AB25', 'weekly', 'Mon', NULL, '08:30:00', '16:30:00', '08:00:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1103AB25', 'weekly', 'Mon', NULL, '11:00:00', '19:00:00', '10:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1104AB25', 'weekly', 'Mon', NULL, '14:00:00', '22:00:00', '13:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '3301AC25', 'weekly', 'Mon', NULL, '21:00:00', '05:00:00', '20:30:00', 'Overnight VIP', 'active', '1', 'dummy'),
  -- Tuesday
  ('DL1SRC', '1101AB25', 'weekly', 'Tue', NULL, '06:00:00', '14:00:00', '05:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1103AB25', 'weekly', 'Tue', NULL, '11:00:00', '19:00:00', '10:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '3301AC25', 'weekly', 'Tue', NULL, '21:00:00', '05:00:00', '20:30:00', 'Overnight VIP', 'active', '1', 'dummy'),
  -- Wednesday
  ('DL1SRC', '1101AB25', 'weekly', 'Wed', NULL, '06:00:00', '14:00:00', '05:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1102AB25', 'weekly', 'Wed', NULL, '08:30:00', '16:30:00', '08:00:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1104AB25', 'weekly', 'Wed', NULL, '14:00:00', '22:00:00', '13:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '3301AC25', 'weekly', 'Wed', NULL, '21:00:00', '05:00:00', '20:30:00', 'Overnight VIP', 'active', '1', 'dummy'),
  -- Thursday
  ('DL1SRC', '1101AB25', 'weekly', 'Thu', NULL, '06:00:00', '14:00:00', '05:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1103AB25', 'weekly', 'Thu', NULL, '11:00:00', '19:00:00', '10:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '3301AC25', 'weekly', 'Thu', NULL, '21:00:00', '05:00:00', '20:30:00', 'Overnight VIP', 'active', '1', 'dummy'),
  -- Friday
  ('DL1SRC', '1101AB25', 'weekly', 'Fri', NULL, '06:00:00', '14:00:00', '05:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '3302AC25', 'weekly', 'Fri', NULL, '07:30:00', '15:30:00', '07:00:00', 'Extra Friday departure', 'active', '1', 'dummy'),
  ('DL1SRC', '1102AB25', 'weekly', 'Fri', NULL, '09:00:00', '17:00:00', '08:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1103AB25', 'weekly', 'Fri', NULL, '11:00:00', '19:00:00', '10:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1104AB25', 'weekly', 'Fri', NULL, '14:00:00', '22:00:00', '13:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '3301AC25', 'weekly', 'Fri', NULL, '21:00:00', '05:00:00', '20:30:00', 'Overnight VIP', 'active', '1', 'dummy'),
  -- Saturday
  ('DL1SRC', '1101AB25', 'weekly', 'Sat', NULL, '06:00:00', '14:00:00', '05:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1102AB25', 'weekly', 'Sat', NULL, '09:00:00', '17:00:00', '08:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '1104AB25', 'weekly', 'Sat', NULL, '14:00:00', '22:00:00', '13:30:00', '', 'active', '1', 'dummy'),
  ('DL1SRC', '3301AC25', 'weekly', 'Sat', NULL, '21:00:00', '05:00:00', '20:30:00', 'Overnight VIP', 'active', '1', 'dummy'),
  -- Sunday
  ('DL1SRC', '1101AB25', 'weekly', 'Sun', NULL, '08:00:00', '16:00:00', '07:30:00', 'Sunday morning', 'active', '1', 'dummy'),
  ('DL1SRC', '3301AC25', 'weekly', 'Sun', NULL, '20:00:00', '04:00:00', '19:30:00', 'Sunday overnight', 'active', '1', 'dummy'),

  -- DN2SRC Dar es salaam to Nairobi
  ('DN2SRC', '3302AC25', 'weekly', 'Mon', NULL, '06:30:00', '18:30:00', '06:00:00', '', 'active', '1', 'dummy'),
  ('DN2SRC', '3302AC25', 'weekly', 'Wed', NULL, '06:30:00', '18:30:00', '06:00:00', '', 'active', '1', 'dummy'),
  ('DN2SRC', '3302AC25', 'weekly', 'Fri', NULL, '06:30:00', '18:30:00', '06:00:00', '', 'active', '1', 'dummy'),
  ('DN2SRC', '1104AB25', 'weekly', 'Sat', NULL, '09:00:00', '21:00:00', '08:30:00', 'Weekend Nairobi', 'active', '1', 'dummy'),
  ('DN2SRC', '1104AB25', 'weekly', 'Sun', NULL, '09:00:00', '21:00:00', '08:30:00', 'Weekend Nairobi', 'active', '1', 'dummy'),

  -- DH3SRC Dar es salaam to Harare
  ('DH3SRC', '1103AB25', 'weekly', 'Tue', NULL, '05:00:00', '20:00:00', '04:30:00', 'Long haul', 'active', '1', 'dummy'),
  ('DH3SRC', '1103AB25', 'weekly', 'Thu', NULL, '05:00:00', '20:00:00', '04:30:00', 'Long haul', 'active', '1', 'dummy'),
  ('DH3SRC', '1103AB25', 'weekly', 'Sat', NULL, '05:00:00', '20:00:00', '04:30:00', 'Long haul', 'active', '1', 'dummy'),

  -- One-off trips
  ('DL1SRC', '1101AB25', 'once', NULL, '2026-09-20', '06:00:00', '14:00:00', '05:30:00', 'Public holiday extra', 'active', '1', 'dummy'),
  ('DL1SRC', '1102AB25', 'once', NULL, '2026-09-25', '08:00:00', '16:00:00', '07:30:00', 'Charter', 'active', '1', 'dummy'),
  ('DN2SRC', '1101AB25', 'once', NULL, '2026-10-01', '07:00:00', '19:00:00', '06:30:00', 'Independence extra', 'active', '1', 'dummy'),
  ('DH3SRC', '1101AB25', 'once', NULL, '2026-10-04', '07:00:00', '19:00:00', '06:30:00', 'Special Harare run', 'active', '1', 'dummy')
ON DUPLICATE KEY UPDATE
  `departure_time` = VALUES(`departure_time`),
  `arrival_time` = VALUES(`arrival_time`),
  `reporting_time` = VALUES(`reporting_time`),
  `notes` = VALUES(`notes`),
  `status` = VALUES(`status`),
  `uploader` = VALUES(`uploader`);
