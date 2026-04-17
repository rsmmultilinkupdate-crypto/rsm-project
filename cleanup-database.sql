-- ========================================
-- RSM Multilink - Database Cleanup
-- Remove OTP System Tables
-- ========================================

-- Drop OTP-related tables
DROP TABLE IF EXISTS `email_logs`;
DROP TABLE IF EXISTS `email_settings`;
DROP TABLE IF EXISTS `o_t_ps`;

-- Verify tables are removed
SHOW TABLES LIKE '%email%';
SHOW TABLES LIKE '%otp%';
SHOW TABLES LIKE '%o_t_p%';

-- Show success message
SELECT 'OTP tables removed successfully!' as Status;
