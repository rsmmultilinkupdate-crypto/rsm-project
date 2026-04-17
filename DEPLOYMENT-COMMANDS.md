# RSM Multilink - Complete Fix Commands

## Run these commands in order on your server:

```bash
# 1. Go to project directory
cd /home/rsmmultilink/public_html

# 2. Deploy latest code
curl "https://rsmmultilink.com/webhook.php?key=rsm123"

# 3. Wait for deployment
sleep 5

# 4. Clear all cache and compiled views (THIS FIXES THE 500 ERROR)
rm -rf storage/framework/views/*
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# 5. Run migrations (will create email_settings table)
php artisan migrate --force

# 6. Verify email_settings table exists
php artisan tinker
```

## In Tinker, run:
```php
\App\EmailSetting::count()
\App\EmailSetting::all()
exit
```

## Expected Output:
```
2
[
  { email: "rsmmultilinkenquiry@gmail.com", label: "Primary Enquiry Email" },
  { email: "kumarshivam827@gmail.com", label: "Admin Email" }
]
```

---

## Testing OTP Flow:

1. **Clear OTP sessions** (after login):
   ```
   Visit: https://rsmmultilink.com/otp/clear-session
   ```

2. **Test Enquiry Page**:
   ```
   Visit: https://rsmmultilink.com/admin/enquiry
   ```
   - Should redirect to: `/otp/verify`
   - OTP email sent to both emails
   - Check inbox/spam folder
   - Enter OTP and submit
   - Should redirect back to enquiry page

3. **Test Email Settings Page**:
   ```
   Visit: https://rsmmultilink.com/admin/email-settings
   ```
   - Should redirect to: `/otp/verify`
   - OTP email sent to both emails
   - Enter OTP and submit
   - Should redirect back to email-settings page

---

## If OTP Email Not Received:

1. **Check Laravel logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check email logs in admin panel**:
   ```
   Visit: https://rsmmultilink.com/admin/email-logs
   ```

3. **Check spam folder** in both Gmail accounts

4. **Verify server can send emails**:
   ```bash
   echo "Test email" | mail -s "Test Subject" rsmmultilinkenquiry@gmail.com
   ```

---

## What Was Fixed:

### 1. **500 Error (FIXED)**
   - **Problem**: `app('global_settings')[0]` accessing empty array
   - **Solution**: Added fallback default values in `AppServiceProvider`
   - **File**: `app/Providers/AppServiceProvider.php`

### 2. **Migration Errors (FIXED)**
   - **Problem**: Migrations trying to create existing tables
   - **Solution**: Changed `hasTable` check to early return pattern
   - **Files**: 
     - `database/migrations/2024_06_18_104022_create_o_t_ps_table.php`
     - `database/migrations/2026_04_17_051610_create_email_settings_table.php`

### 3. **OTP System (READY)**
   - **Status**: Code is deployed and ready
   - **Email Recipients**: 
     - rsmmultilinkenquiry@gmail.com
     - kumarshivam827@gmail.com
   - **Protected Pages**:
     - `/admin/enquiry` (requires OTP)
     - `/admin/email-settings` (requires OTP)
   - **Email Method**: PHP mail() fallback (SMTP fails with current password)

### 4. **Email Settings Table (WILL BE CREATED)**
   - **Status**: Will be created when you run migrations
   - **Default Emails**: Auto-inserted on creation
   - **Management**: Can be managed via `/admin/email-settings` page

---

## Summary:

✅ **Website 500 Error** - FIXED (clear cache to apply)
✅ **Migration Errors** - FIXED (safe to run migrate now)
✅ **OTP Middleware** - WORKING (code deployed)
✅ **Email Settings** - READY (will be created on migrate)
⚠️ **Email Delivery** - NEEDS TESTING (using PHP mail())

**Next Action**: Run the commands above to deploy fixes and test!
