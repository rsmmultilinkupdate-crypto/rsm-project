# 🗄️ Database Cleanup - Remove OTP Tables

## 📋 Tables To Remove:

OTP system ne ye 3 tables create kiye the:
1. **`o_t_ps`** - OTP codes storage
2. **`email_settings`** - Email recipients configuration
3. **`email_logs`** - Email send history

---

## 🚀 Method 1: Using Laravel Migration (RECOMMENDED)

```bash
cd /home/rsmmultilink/public_html

# Deploy latest code (includes cleanup migration)
curl "https://rsmmultilink.com/webhook.php?key=rsm123"
sleep 5

# Run the cleanup migration
php artisan migrate --force

# Verify tables are removed
php artisan tinker
```

In Tinker:
```php
DB::select("SHOW TABLES LIKE '%email%'");
DB::select("SHOW TABLES LIKE '%otp%'");
// Should return empty arrays
exit
```

---

## 🚀 Method 2: Direct SQL (FASTEST)

### Option A: Using MySQL Command Line

```bash
# Login to MySQL
mysql -u rsmmultilink_rsmupdate -p rsmmultilink_rsmupdate

# Run these commands:
DROP TABLE IF EXISTS email_logs;
DROP TABLE IF EXISTS email_settings;
DROP TABLE IF EXISTS o_t_ps;

# Verify
SHOW TABLES LIKE '%email%';
SHOW TABLES LIKE '%otp%';

# Exit
exit;
```

### Option B: Using SQL File

```bash
cd /home/rsmmultilink/public_html

# Run the cleanup SQL file
mysql -u rsmmultilink_rsmupdate -p rsmmultilink_rsmupdate < cleanup-database.sql
```

---

## 🚀 Method 3: Using phpMyAdmin (GUI)

1. Login to cPanel: https://your-cpanel-url.com
2. Open **phpMyAdmin**
3. Select database: `rsmmultilink_rsmupdate`
4. Find and delete these tables:
   - `email_logs`
   - `email_settings`
   - `o_t_ps`
5. Click each table → **Drop** → Confirm

---

## 🔍 Verification Commands

### Check if tables exist:
```bash
php artisan tinker
```

```php
// Check for OTP table
Schema::hasTable('o_t_ps')
// Should return: false

// Check for email_settings table
Schema::hasTable('email_settings')
// Should return: false

// Check for email_logs table
Schema::hasTable('email_logs')
// Should return: false

// List all tables
DB::select('SHOW TABLES');

exit
```

---

## 📊 Database State Comparison

### BEFORE (With OTP System):
```
Tables:
├── users
├── blogs
├── categories
├── products
├── ... (other original tables)
├── o_t_ps ❌ (OTP codes)
├── email_settings ❌ (Email config)
└── email_logs ❌ (Email history)
```

### AFTER (Clean State):
```
Tables:
├── users
├── blogs
├── categories
├── products
└── ... (only original tables)
```

---

## ⚠️ Important Notes:

### 1. **Backup First (Optional but Recommended)**
```bash
# Create backup directory
mkdir -p /home/rsmmultilink/backups

# Backup database
mysqldump -u rsmmultilink_rsmupdate -p rsmmultilink_rsmupdate > /home/rsmmultilink/backups/backup_$(date +%Y%m%d_%H%M%S).sql
```

### 2. **No Data Loss**
- Ye tables sirf OTP system ke liye the
- Koi important data nahi hai in tables me
- Users, blogs, products, etc. safe rahenge

### 3. **Migration Files**
OTP migration files rahenge but koi problem nahi:
- `2024_06_18_104022_create_o_t_ps_table.php`
- `2026_04_17_051610_create_email_settings_table.php`
- `2026_04_17_144738_create_email_logs_table.php`

Ye files harmless hain kyunki:
- Unme `hasTable()` check hai
- Agar table already deleted hai to skip kar denge
- Future me migrate:fresh run karne pe bhi safe hai

---

## 🎯 Complete Cleanup Commands (All-in-One)

```bash
# Go to project directory
cd /home/rsmmultilink/public_html

# Deploy latest code
curl "https://rsmmultilink.com/webhook.php?key=rsm123"
sleep 5

# Clear cache
rm -rf storage/framework/views/*
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Remove OTP tables using SQL
mysql -u rsmmultilink_rsmupdate -p rsmmultilink_rsmupdate <<EOF
DROP TABLE IF EXISTS email_logs;
DROP TABLE IF EXISTS email_settings;
DROP TABLE IF EXISTS o_t_ps;
SELECT 'OTP tables removed!' as Status;
EOF

echo "✓ Done! Database cleaned and website restored."
```

**Note:** Ye command password prompt karega. Database password enter karna hoga.

---

## 🔐 Database Credentials

Agar password nahi pata to `.env` file me dekho:

```bash
cat /home/rsmmultilink/public_html/.env | grep DB_
```

Output:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rsmmultilink_rsmupdate
DB_USERNAME=rsmmultilink_rsmupdate
DB_PASSWORD=your_password_here
```

---

## ✅ Success Checklist

After cleanup, verify:

- [ ] Website loads: https://rsmmultilink.com
- [ ] Admin login works
- [ ] `/admin/enquiry` accessible (no OTP)
- [ ] `/admin/email-settings` accessible (no OTP)
- [ ] No database errors in logs
- [ ] Tables removed: `o_t_ps`, `email_settings`, `email_logs`

---

## 🆘 Troubleshooting

### Error: "Table doesn't exist"
**Solution:** Tables already deleted, ignore this error.

### Error: "Access denied"
**Solution:** Check database credentials in `.env` file.

### Error: "Cannot drop table (foreign key constraint)"
**Solution:** Run in this order:
```sql
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS email_logs;
DROP TABLE IF EXISTS email_settings;
DROP TABLE IF EXISTS o_t_ps;
SET FOREIGN_KEY_CHECKS = 1;
```

---

## 📞 Quick Reference

| Task | Command |
|------|---------|
| Check if table exists | `php artisan tinker` → `Schema::hasTable('o_t_ps')` |
| Drop single table | `mysql> DROP TABLE IF EXISTS o_t_ps;` |
| List all tables | `mysql> SHOW TABLES;` |
| Backup database | `mysqldump -u user -p database > backup.sql` |

---

**Database cleanup ke baad website bilkul pehle jaisa ho jayega!** 🚀
