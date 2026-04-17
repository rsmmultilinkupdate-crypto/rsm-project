# ⚡ Quick Start - Auto Deployment Setup

## 🎯 What Was Done

✅ **Code pushed to GitHub**: https://github.com/rsmmultilinkupdate-crypto/rsm-project  
✅ **Webhook file created**: `webhook.php` (auto-deployment script)  
✅ **Database error fixed**: AppServiceProvider wrapped in try-catch  
✅ **Large file removed**: vite.config.zip removed from git history  
✅ **Documentation created**: Complete setup guides  

---

## 🚀 Server Setup (Do This Now)

### Step 1: Upload webhook.php
Upload `webhook.php` from your repo to server:
```
/home/rsmmultilink/public_html/webhook.php
```

### Step 2: Fix Database (IMPORTANT!)
The error in logs shows database connection issue. Fix it:

**Option A - Via cPanel:**
1. Go to cPanel → MySQL Databases
2. Find user: `rsmmultilink_rsmupdate`
3. Reset password to: `rsmupdate@@`
4. Add user to database with ALL PRIVILEGES

**Option B - Via phpMyAdmin:**
```sql
GRANT ALL PRIVILEGES ON rsmmultilink_rsmupdate.* 
TO 'rsmmultilink_rsmupdate'@'localhost' 
IDENTIFIED BY 'rsmupdate@@';
FLUSH PRIVILEGES;
```

### Step 3: Setup GitHub Webhook
1. Go to: https://github.com/rsmmultilinkupdate-crypto/rsm-project/settings/hooks
2. Click "Add webhook"
3. Enter:
   - **Payload URL**: `https://rsmmultilink.com/webhook.php?key=rsm123`
   - **Content type**: `application/json`
   - **Events**: Just the push event
4. Save

### Step 4: Test It
Visit: `https://rsmmultilink.com/webhook.php?key=rsm123`

Should see: `{"status":"success",...}`

---

## 📝 What Happens Now

1. **You push code** → GitHub receives it
2. **GitHub triggers webhook** → Calls your webhook.php
3. **Webhook downloads & deploys** → Latest code goes live
4. **Laravel caches cleared** → Site updated automatically

---

## 🔧 Files Created

| File | Purpose |
|------|---------|
| `webhook.php` | Auto-deployment script |
| `DEPLOYMENT.md` | Deployment documentation |
| `SERVER_SETUP.md` | Detailed server setup guide |
| `QUICK_START.md` | This file (quick reference) |

---

## 🐛 Current Error Fixed

**Error**: `Access denied for user 'rsmmultilink_rsmupdate'@'localhost'`

**Fix Applied**: 
- AppServiceProvider now has try-catch to prevent boot errors
- But you still need to fix database credentials on server (see Step 2 above)

---

## 📞 Need Help?

1. **Check logs**: `/home/rsmmultilink/public_html/deployment.log`
2. **Read guides**: `DEPLOYMENT.md` and `SERVER_SETUP.md`
3. **Test webhook**: Visit webhook URL with `?key=rsm123`

---

## ✅ Quick Checklist

- [ ] Upload webhook.php to server
- [ ] Fix database credentials
- [ ] Configure GitHub webhook
- [ ] Test webhook manually
- [ ] Push code to test auto-deployment
- [ ] Verify site is working

---

## 🎉 That's It!

Your auto-deployment is ready. Just do the server setup steps above and you're done! 🚀

**Next Push**: Will automatically deploy to your server!
