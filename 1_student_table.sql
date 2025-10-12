-- ============================================
-- NUEvents - Student/User Table
-- ============================================
-- This table stores all user accounts (both students and admins)
-- Import Order: Import this file FIRST before other tables

CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Default Admin Account
-- ============================================
-- Email: admin@nu.edu.ph
-- Password: admin123
-- ⚠️ IMPORTANT: Change this password after first login!
-- ============================================

INSERT INTO `student` (`name`, `email`, `password`, `role`) 
VALUES ('Admin User', 'admin@nu.edu.ph', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE id=id;
