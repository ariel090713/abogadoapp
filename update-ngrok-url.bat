@echo off
echo ========================================
echo Ngrok URL Updater for Livewire
echo ========================================
echo.

REM Get ngrok URL from API
echo Fetching ngrok URL...
curl -s http://localhost:4040/api/tunnels > ngrok-temp.json

REM Check if ngrok is running
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Ngrok is not running!
    echo Please start ngrok first: ngrok http 8000
    pause
    exit /b 1
)

REM Parse JSON to get URL (simple method)
for /f "tokens=*" %%i in ('powershell -Command "(Get-Content ngrok-temp.json | ConvertFrom-Json).tunnels[0].public_url"') do set NGROK_URL=%%i

REM Clean up temp file
del ngrok-temp.json

REM Check if URL was found
if "%NGROK_URL%"=="" (
    echo ERROR: Could not get ngrok URL
    echo Make sure ngrok is running: ngrok http 8000
    pause
    exit /b 1
)

echo.
echo Found ngrok URL: %NGROK_URL%
echo.

REM Backup .env
copy .env .env.backup >nul
echo Created backup: .env.backup

REM Update .env using PowerShell
powershell -Command "(Get-Content .env) -replace 'APP_URL=.*', 'APP_URL=%NGROK_URL%' | Set-Content .env"

echo Updated APP_URL in .env
echo.

REM Clear Laravel cache
echo Clearing Laravel cache...
php artisan config:clear
php artisan cache:clear

echo.
echo ========================================
echo SUCCESS! Ngrok URL updated
echo ========================================
echo.
echo Your ngrok URL: %NGROK_URL%
echo.
echo You can now access your app at:
echo %NGROK_URL%
echo.
echo Press any key to exit...
pause >nul
