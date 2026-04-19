#!/bin/bash
# Queue Worker for RSM Multilink
# This script processes email jobs in the background

while true; do
    php artisan queue:work --sleep=3 --tries=3 --max-time=3600 --timeout=60
    sleep 5
done
