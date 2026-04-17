# 🖥️ Server Setup Guide - RSM Project

## 📋 Quick Server Setup Steps

### 1️⃣ Upload webhook.php
```bash
# Via FTP/cPanel File Manager, upload webhook.php to:
/home/rsmmultilink/public_html/webhook.php
```

### 2️⃣ Set Permissions
```bash
# Via SSH or cPanel Terminal
cd /home/rsmmultilink/public_html
chmod 755 webhook.php
chmod -R 755 storage bootstrap/cache
```

### 3️⃣ Configure Database
```bash
# In cPanel → MySQL Databases:
# 1. Create database: rsmmultilink_rsmupdate (if not exists)
# 2. Create user: rsmmultilink_rsmupdate
# 3. Set password: rsmupdate@@
# 4. Add user to database with ALL PRIVILEGES
```

### 4️⃣ Update .env File on Server
```bash
# Edit /home/rsmmultilink/public_html/.env
# Verify these lines:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rsmmultilink_rsmupdate
DB_USERNAME=rsmmultilink_rsmupdate
DB_PASSWORD=rsmupdate@@
```

### 5️⃣ Run Laravel Setup Commands
```bash
cd /home/rsmmultilink/public_html

# Install dependencies (if needed)
composer install --no-dev --optimize-autoloader

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Generate app key (if needed)
php artisan key:generate

# Run migrations (if needed)
php artisan migrate --force
```

### 6️⃣ Configure GitHub Webhook
1. Go to: https://github.com/rsmmultilinkupdate-crypto/rsm-project/settings/hooks
2. Click **Add webhook**
3. Enter:
   - **Payload URL**: `https://rsmmultilink.com/webhook.php?key=rsm123`
   - **Content type**: `application/json`
   - **Secret**: (leave empty)
   - **SSL verification**: Enable SSL verification
   - **Events**: Just the push event
   - **Active**: ✅
4. Click **Add webhook**

---

## 🧪 Test Deployment

### Test 1: Manual Trigger
```bash
# Visit in browser:
https://rsmmultilink.com/webhook.php?key=rsm123

# Should see:
{"status":"success","message":"Deployment completed successfully","timestamp":"2026-04-17 12:00:00"}
```

### Test 2: GitHub Push
```bash
# On your local machine:
git add .
git commit -m "Test deployment"
git push origin main

# Then check deployment log on server:
cat /home/rsmmultilink/public_html/deployment.log
```

---

## 🔍 Verify Setup

### Check 1: Database Connection
```bash
php artisan tinker
# Then run:
DB::connection()->getPdo();
# Should not throw error
```

### Check 2: File Permissions
```bash
ls -la storage/
ls -la bootstrap/cache/
# All should be writable (755 or 775)
```

### Check 3: Webhook Accessibility
```bash
curl https://rsmmultilink.com/webhook.php?key=rsm123
# Should return JSON response
```

---

## 🚨 Common Issues & Fixes

### Issue 1: Database Access Denied
**Error**: `Access denied for user 'rsmmultilink_rsmupdate'@'localhost'`

**Fix**:
```sql
-- In phpMyAdmin, run:
GRANT ALL PRIVILEGES ON rsmmultilink_rsmupdate.* TO 'rsmmultilink_rsmupdate'@'localhost';
FLUSH PRIVILEGES;
```

### Issue 2: Webhook Returns 403
**Error**: "Access denied"

**Fix**: Check URL has correct key parameter:
```
https://rsmmultilink.com/webhook.php?key=rsm123
```

### Issue 3: Files Not Extracting
**Error**: ZIP extraction fails

**Fix**:
```bash
# Check PHP ZipArchive extension
php -m | grep zip

# If not installed, enable in php.ini:
extension=zip
```

### Issue 4: Permission Denied
**Error**: Cannot write to directory

**Fix**:
```bash
# Set correct ownership
chown -R rsmmultilink:rsmmultilink /home/rsmmultilink/public_html

# Set correct permissions
find /home/rsmmultilink/public_html -type d -exec chmod 755 {} \;
find /home/rsmmultilink/public_html -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
```

---

## 📊 Monitor Deployment

### View Live Logs
```bash
# SSH into server
tail -f /home/rsmmultilink/public_html/deployment.log
```

### Check Last Deployment
```bash
tail -20 /home/rsmmultilink/public_html/deployment.log
```

### Clear Old Logs
```bash
# Keep only last 100 lines
tail -100 /home/rsmmultilink/public_html/deployment.log > temp.log
mv temp.log /home/rsmmultilink/public_html/deployment.log
```

---

## 🔐 Security Recommendations

1. **Change Webhook Key**: Update `rsm123` to a strong random string
2. **Restrict Access**: Add IP whitelist in webhook.php
3. **Enable HTTPS**: Ensure SSL certificate is active
4. **Backup Database**: Setup automatic daily backups
5. **Monitor Logs**: Check deployment.log regularly

---

## ✅ Final Checklist

- [ ] webhook.php uploaded and accessible
- [ ] File permissions set (755/644)
- [ ] Database created and user configured
- [ ] .env file updated with correct credentials
- [ ] Laravel caches cleared
- [ ] GitHub webhook configured and active
- [ ] Test deployment successful
- [ ] Deployment logs working
- [ ] Website accessible and working

---

## 🎉 You're Done!

Your auto-deployment is now active! Every push to GitHub will automatically deploy to your server.

**Test it now**:
1. Make a small change in your code
2. Commit and push to GitHub
3. Watch the magic happen! ✨

---

**Need Help?** Check deployment.log first, then review this guide.
