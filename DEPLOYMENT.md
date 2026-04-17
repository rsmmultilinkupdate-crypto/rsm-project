# 🚀 RSM Project - Auto Deployment Setup

## 📦 GitHub Repository
- **Repo URL**: https://github.com/rsmmultilinkupdate-crypto/rsm-project
- **ZIP Download**: https://github.com/rsmmultilinkupdate-crypto/rsm-project/archive/refs/heads/main.zip

---

## 🔧 Server Setup Instructions

### Step 1: Upload webhook.php to Server
Upload `webhook.php` file to your server's public_html directory:
```
/home/rsmmultilink/public_html/webhook.php
```

### Step 2: Set Proper Permissions
```bash
chmod 755 /home/rsmmultilink/public_html/webhook.php
chmod 755 /home/rsmmultilink/public_html
```

### Step 3: Configure GitHub Webhook
1. Go to your GitHub repository
2. Navigate to: **Settings → Webhooks → Add webhook**
3. Fill in the details:
   - **Payload URL**: `https://rsmmultilink.com/webhook.php?key=rsm123`
   - **Content type**: `application/json`
   - **Which events**: Select "Just the push event"
   - **Active**: ✅ Check this box
4. Click **Add webhook**

---

## 🎯 How It Works

1. **Push to GitHub**: When you push code to the `main` branch
2. **GitHub Triggers**: GitHub sends a webhook to your server
3. **Auto Deploy**: The webhook script automatically:
   - Downloads the latest code as ZIP
   - Extracts files to public_html
   - Moves files to correct locations
   - Clears Laravel cache
   - Logs all activities

---

## 📝 Manual Deployment (If Needed)

If you need to deploy manually, visit:
```
https://rsmmultilink.com/webhook.php?key=rsm123
```

---

## 🔍 Check Deployment Logs

View deployment logs at:
```
/home/rsmmultilink/public_html/deployment.log
```

Or via browser (if accessible):
```
https://rsmmultilink.com/deployment.log
```

---

## 🛠️ Server Requirements

- PHP 7.4+ with ZipArchive extension
- Write permissions on public_html directory
- Composer installed (for Laravel dependencies)
- MySQL database configured

---

## 🔐 Database Configuration

Update `.env` file on server with correct database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rsmmultilink_rsmupdate
DB_USERNAME=rsmmultilink_rsmupdate
DB_PASSWORD=your_password_here
```

**Note**: Make sure the database user has proper permissions and the password is correct.

---

## ⚠️ Important Notes

1. **Security Key**: The webhook uses `key=rsm123` for security. Change this in production!
2. **Backup**: Always backup your database before deployment
3. **Environment File**: The `.env` file is not included in the repository for security
4. **File Permissions**: Ensure storage and bootstrap/cache directories are writable

---

## 🐛 Troubleshooting

### Database Connection Error
If you see "Access denied" errors:
1. Verify database credentials in `.env`
2. Check if database user exists in cPanel/phpMyAdmin
3. Ensure user has proper permissions on the database

### Webhook Not Working
1. Check webhook.php file exists and is accessible
2. Verify GitHub webhook is active
3. Check deployment.log for errors
4. Ensure PHP ZipArchive extension is enabled

### Files Not Updating
1. Check file permissions (755 for directories, 644 for files)
2. Verify storage and cache directories are writable
3. Clear Laravel cache manually:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

---

## 📞 Support

For issues or questions, check the deployment logs first:
```bash
tail -f /home/rsmmultilink/public_html/deployment.log
```

---

## ✅ Deployment Checklist

- [ ] webhook.php uploaded to server
- [ ] File permissions set correctly
- [ ] GitHub webhook configured
- [ ] Database credentials verified
- [ ] .env file configured on server
- [ ] Test deployment by pushing to GitHub
- [ ] Check deployment.log for success

---

**Last Updated**: April 17, 2026
