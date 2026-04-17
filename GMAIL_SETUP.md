# Gmail SMTP Setup for OTP Emails

## Server Admin - Do This:

### Step 1: Get Gmail App Password

1. Login to Gmail: rsmmultilinkenquiry@gmail.com
2. Go to: https://myaccount.google.com/apppasswords
3. If "App passwords" not available:
   - Enable 2-Step Verification first
   - Then go back to App passwords
4. Create new app password:
   - App: Mail
   - Device: Other (Custom name) → "RSM Server"
5. Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)

### Step 2: Update .env on Server

Edit `/home/rsmmultilink/public_html/.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=rsmmultilinkenquiry@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=rsmmultilinkenquiry@gmail.com
MAIL_FROM_NAME="RSM Admin"
```

### Step 3: Clear Cache

```bash
cd /home/rsmmultilink/public_html
php artisan config:clear
php artisan cache:clear
```

### Step 4: Test

Visit: https://rsmmultilink.com/admin/enquiry

OTP will be sent to:
- rsmmultilinkenquiry@gmail.com
- kumarshivam827@gmail.com

## Done!

Emails will arrive in INBOX (not spam) because Gmail SMTP is authenticated.
