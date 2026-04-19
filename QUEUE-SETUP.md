# Queue Worker Setup Instructions

## What Changed?
- Email sending is now done in background using Laravel Queue
- Forms submit instantly without waiting for email to send
- Emails are sent asynchronously by queue worker

## Server Setup Required

### For Linux/cPanel Server:

1. **Via SSH (Recommended)**:
   ```bash
   cd /path/to/your/project
   chmod +x queue-worker.sh
   nohup ./queue-worker.sh > /dev/null 2>&1 &
   ```

2. **Via Cron Job** (Add to crontab):
   ```
   * * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
   * * * * * cd /path/to/your/project && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
   ```

### For Windows/XAMPP (Local Testing):

1. Open Command Prompt as Administrator
2. Navigate to project directory:
   ```
   cd C:\xampp\htdocs\rsmupdatenew
   ```
3. Run queue worker:
   ```
   php artisan queue:work
   ```
   OR double-click `queue-worker.bat`

## How It Works

1. User submits form
2. Enquiry saved to database with status "pending"
3. Email job added to queue
4. User redirected to thank-you page immediately (FAST!)
5. Queue worker picks up job and sends email
6. Status updated to "sent" or "failed"

## Checking Queue Status

```bash
# Check pending jobs
php artisan queue:monitor

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

## Important Notes

- Queue worker MUST be running for emails to send
- If worker stops, emails will queue up and send when worker restarts
- Check Laravel logs for any email errors: `storage/logs/laravel.log`
- SMTP settings from .env are used: query@rsmmultilink.com

## Troubleshooting

If emails not sending:
1. Check if queue worker is running
2. Check `jobs` table in database for pending jobs
3. Check `failed_jobs` table for failed attempts
4. Check Laravel logs: `storage/logs/laravel.log`
5. Verify SMTP settings in `.env` file
