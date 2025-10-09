-- Database Setup for Events Calendar
-- Run this SQL in your XAMPP phpMyAdmin to create the events table

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `event_type` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `start_date` (`start_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample events data (optional)
INSERT INTO `events` (`title`, `description`, `event_type`, `start_date`, `end_date`, `location`, `created_by`) VALUES
('Freshmen Week', 'Welcome orientation for new students', 'Events', '2024-10-01 09:00:00', '2024-10-05 17:00:00', 'Main Campus', 1),
('Posisenation 2015', 'Annual position conference', 'Events', '2024-10-14 08:00:00', '2024-10-17 18:00:00', 'Conference Hall', 1);
