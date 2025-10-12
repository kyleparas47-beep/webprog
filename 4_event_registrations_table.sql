-- ============================================
-- NUEvents - Event Registrations Table
-- ============================================
-- This table enables students to pre-register for events
-- Import Order: Import this file LAST (after student and events tables)
-- Requires: student and events tables must exist (foreign key dependencies)

CREATE TABLE IF NOT EXISTS `event_registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_registration` (`student_id`, `event_id`),
  KEY `student_id` (`student_id`),
  KEY `event_id` (`event_id`),
  FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create index for better performance
CREATE INDEX IF NOT EXISTS `idx_registrations_student` ON `event_registrations` (`student_id`);

-- ============================================
-- How it works:
-- ============================================
-- 1. Students can view events in the calendar
-- 2. Students can click "Pre-Register" button
-- 3. Registration is stored here linking student_id to event_id
-- 4. The UNIQUE constraint prevents duplicate registrations
-- 5. Students can view their registered events in "My Registered Events" panel
