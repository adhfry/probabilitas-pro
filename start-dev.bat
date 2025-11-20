@echo off
echo ========================================
echo   PROBABILITAS PRO - Development Server
echo   by Ahda Firly Barori
echo ========================================
echo.
echo Starting Vite dev server...
echo Keep this window open while developing.
echo.
echo Press Ctrl+C to stop the server.
echo.

cd /d "%~dp0"
call npm run dev

pause
