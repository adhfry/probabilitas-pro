@echo off
echo ========================================
echo   PROBABILITAS PRO - Laravel Server
echo   by Ahda Firly Barori
echo ========================================
echo.
echo Starting Laravel development server...
echo Server will run at: http://localhost:8000
echo.
echo Press Ctrl+C to stop the server.
echo.

cd /d "%~dp0"
php artisan serve

pause
