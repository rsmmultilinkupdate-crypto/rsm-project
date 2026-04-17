#!/bin/bash

echo "=========================================="
echo "RSM Multilink - Database Cleanup Script"
echo "Remove OTP System Tables"
echo "=========================================="
echo ""

cd /home/rsmmultilink/public_html

echo "⚠️  WARNING: This will delete the following tables:"
echo "  - o_t_ps (OTP codes)"
echo "  - email_settings (email configuration)"
echo "  - email_logs (email send history)"
echo ""
echo "Press Ctrl+C to cancel, or wait 5 seconds to continue..."
sleep 5

echo ""
echo "Step 1: Backup current database (recommended)..."
BACKUP_FILE="backup_before_otp_removal_$(date +%Y%m%d_%H%M%S).sql"
mysqldump -u rsmmultilink_rsmupdate -p rsmmultilink_rsmupdate > "/home/rsmmultilink/backups/$BACKUP_FILE" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✓ Database backed up to: /home/rsmmultilink/backups/$BACKUP_FILE"
else
    echo "⚠️  Backup failed or skipped (you may need to enter password manually)"
fi
echo ""

echo "Step 2: Dropping OTP-related tables..."
mysql -u rsmmultilink_rsmupdate -p rsmmultilink_rsmupdate <<EOF
DROP TABLE IF EXISTS email_logs;
DROP TABLE IF EXISTS email_settings;
DROP TABLE IF EXISTS o_t_ps;
SELECT 'Tables dropped successfully' as Status;
EOF

echo ""
echo "Step 3: Verify tables are removed..."
mysql -u rsmmultilink_rsmupdate -p rsmmultilink_rsmupdate <<EOF
SHOW TABLES LIKE '%email%';
SHOW TABLES LIKE '%otp%';
SHOW TABLES LIKE '%o_t_p%';
EOF

echo ""
echo "=========================================="
echo "✓ Database Cleanup Complete!"
echo "=========================================="
echo ""
echo "OTP system tables have been removed."
echo "Your database is now in the pre-OTP state."
echo ""
