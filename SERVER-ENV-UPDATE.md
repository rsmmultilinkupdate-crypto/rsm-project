# IMPORTANT: Server .env File Update Required

## Issue Found
Server par `.env` file mein **purani SMTP settings** hain:
- Port: 465 (OLD)
- Encryption: SSL (OLD)

## Required Changes on Server

Server par `/path/to/project/.env` file mein ye changes karo:

```env
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

### Current (Wrong):
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### Should Be (Correct):
```env
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

## How to Update on Server

### Via cPanel File Manager:
1. Login to cPanel
2. File Manager open karo
3. Project folder mein jao
4. `.env` file edit karo
5. `MAIL_PORT=465` ko `MAIL_PORT=587` karo
6. `MAIL_ENCRYPTION=ssl` ko `MAIL_ENCRYPTION=tls` karo
7. Save karo

### Via SSH:
```bash
cd /path/to/your/project
nano .env
# Change MAIL_PORT and MAIL_ENCRYPTION
# Press Ctrl+X, then Y, then Enter to save
```

## After Update

1. **Clear Config Cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test Form Submission**:
   - Website par form submit karo
   - Dashboard mein status "sent" hona chahiye
   - Email inbox check karo

## Current Status

- ✅ Code deployed with direct email sending
- ✅ Status tracking working (pending -> sent/failed)
- ⚠️ Server .env needs update for optimal performance
- ✅ Emails will work but TLS (port 587) is better than SSL (port 465)

## Why TLS is Better

- **TLS (port 587)**: Modern, secure, better compatibility
- **SSL (port 465)**: Deprecated, older protocol
- **Both work** but TLS is recommended

## Verification

After updating .env, check Laravel logs:
```
storage/logs/laravel.log
```

Should show:
```
Mail configured successfully {"host":"mail.rsmmultilink.com","port":"587","username":"query@rsmmultilink.com","encryption":"tls"}
```

Currently showing (wrong):
```
Mail configured successfully {"host":"mail.rsmmultilink.com","port":"465","username":"query@rsmmultilink.com","encryption":"ssl"}
```
