@echo off
git pull origin v7
call composer install
php artisan migrate
php artisan config:clear
php artisan cache:clear
echo ✅ Update selesai!
pause
