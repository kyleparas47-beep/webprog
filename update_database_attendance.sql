-- UPDATE DATABASE FOR ATTENDANCE TRACKING SYSTEM
-- Run this SQL script in phpMyAdmin to add new fields

ALTER TABLE `event_registrations` 
ADD COLUMN `student_name` VARCHAR(255) NULL AFTER `student_id`,
ADD COLUMN `section` VARCHAR(100) NULL AFTER `student_name`,
ADD COLUMN `course` VARCHAR(255) NULL AFTER `section`,
ADD COLUMN `ticket_number` VARCHAR(20) UNIQUE NULL AFTER `course`,
ADD COLUMN `qr_code` TEXT NULL AFTER `ticket_number`,
ADD COLUMN `attended` TINYINT(1) DEFAULT 0 AFTER `qr_code`,
ADD COLUMN `attended_at` TIMESTAMP NULL AFTER `attended`,
ADD KEY `ticket_number` (`ticket_number`),
ADD KEY `attended` (`attended`);

