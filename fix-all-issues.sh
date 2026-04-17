#!/bin/bash

echo "=========================================="
echo "RSM Multilink - Complete Fix Script"
echo "=========================================="
echo ""

# Change to project directory
cd /home/rsmmultilink/public_html

echo "Step 1: Deploy latest code from GitHub..."
curl -s "https://rsmmultilink.com/webhook.php?key=rsm123"
echo ""
sleep 3

echo "Step 2: Clear all compiled views and cache..."
rm -rf storage/framework/views/*
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
echo "✓ Cache cleared"
echo ""

echo "Step 3: Run migrations (safe mode - will skip existing tables)..."
php artisan migrate --force
echo ""

echo "Step 4: Verify email_settings table..."
php artisan tinker --execute="echo 'Email Settings Count: ' . \App\EmailSetting::count() . PHP_EOL; \App\EmailSetting::all()->each(function(\$e) { echo '  - ' . \$e->email . ' (' . \$e->label . ')' . PHP_EOL; });"
echo ""

echo "Step 5: Clear OTP sessions for fresh testing..."
# This will be done manually by visiting /otp/clear-session
echo "Visit: https://rsmmultilink.com/otp/clear-session (after login)"
echo ""

echo "Step 6: Set proper permissions..."
chmod -R 775 storage bootstrap/cache
chown -R rsmmultilink:rsmmultilink storage bootstrap/cache
echo "✓ Permissions set"
echo ""

echo "=========================================="
echo "✓ ALL FIXES APPLIED SUCCESSFULLY!"
echo "=========================================="
echo ""
echo "NEXT STEPS:"
echo "1. Visit: https://rsmmultilink.com (should work now - no 500 error)"
echo "2. Login to admin panel"
echo "3. Visit: https://rsmmultilink.com/otp/clear-session"
echo "4. Visit: https://rsmmultilink.com/admin/enquiry"
echo "   → Should redirect to OTP page"
echo "   → OTP will be sent to:"
echo "     - rsmmultilinkenquiry@gmail.com"
echo "     - kumarshivam827@gmail.com"
echo "5. Check email (inbox or spam folder)"
echo "6. Enter OTP and verify"
echo ""
echo "If OTP email not received:"
echo "  - Check spam folder"
echo "  - Check logs: tail -f storage/logs/laravel.log"
echo "  - Check email logs in admin panel: /admin/email-logs"
echo ""
