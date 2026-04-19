@echo off
REM Queue Worker for RSM Multilink
REM This script processes email jobs in the background

:loop
php artisan queue:work --sleep=3 --tries=3 --max-time=3600 --timeout=60
timeout /t 5 /nobreak
goto loop
