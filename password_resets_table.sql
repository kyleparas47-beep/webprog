-- Password Resets Table Setup
-- This table is required for the "Forgot Password" functionality
-- Run this SQL script in your MySQL database if you only need the password resets table

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL UNIQUE,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `token` (`token`),
  KEY `expires_at` (`expires_at`),
  FOREIGN KEY (`user_id`) REFERENCES `student` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS `idx_password_resets_token` ON `password_resets` (`token`, `expires_at`);

-- How it works:
-- 1. When a user requests a password reset, a unique token is generated and stored with an expiration time (1 hour)
-- 2. The user receives an email with a link containing this token
-- 3. When they click the link, the token is validated (must exist and not be expired)
-- 4. After successful password reset, the token is deleted from this table
-- 5. The UNIQUE constraint on user_id ensures only one active reset token per user
