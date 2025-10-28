@echo off
REM =================================================================
REM Quick Test Runner - Runs tests without detailed logging
REM =================================================================
echo.
echo Running Quick Test Suite...
echo.

php artisan test --parallel --compact

echo.
echo Test run complete!




