-- SQL script to create event_registrations table
-- Run this in your MySQL database to enable student pre-registration functionality

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
