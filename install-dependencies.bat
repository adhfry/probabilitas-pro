@echo off
echo ========================================
echo   PROBABILITAS PRO - Dependency Installer
echo   by Ahda Firly Barori
echo ========================================
echo.

cd /d "%~dp0"

echo [1/4] Cleaning old installations...
if exist node_modules (
    echo Removing node_modules...
    rmdir /s /q node_modules
)
if exist package-lock.json (
    echo Removing package-lock.json...
    del /f /q package-lock.json
)

echo.
echo [2/4] Clearing npm cache...
call npm cache clean --force

echo.
echo [3/4] Installing dependencies...
call npm install --legacy-peer-deps

echo.
echo [4/4] Checking installation...
if exist node_modules\vite (
    echo.
    echo ========================================
    echo   SUCCESS! Dependencies installed.
    echo ========================================
    echo.
    echo Next steps:
    echo   1. Run: npm run dev
    echo   2. Run: php artisan serve
    echo   3. Open: http://localhost:8000
    echo.
) else (
    echo.
    echo ========================================
    echo   ERROR! Vite not found.
    echo   Try alternative methods in INSTRUCTIONS_FOR_AHDA.md
    echo ========================================
    echo.
)

pause
