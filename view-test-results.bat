@echo off
REM View latest test results
echo.
echo ========================================
echo NOK Kuwait - Test Results Viewer
echo ========================================
echo.

if not exist "test-results\" (
    echo No test results found. Run tests first: run-tests.bat
    pause
    exit /b 1
)

REM Find the latest test result file
for /f "delims=" %%a in ('dir /b /o-d "test-results\*.txt" 2^>nul') do (
    set "latest=%%a"
    goto :found
)

:found
if "%latest%"=="" (
    echo No test result files found.
    pause
    exit /b 1
)

echo Latest test results: %latest%
echo.
echo ========================================
echo.

type "test-results\%latest%"

echo.
echo ========================================
echo.
echo Press any key to view in notepad...
pause >nul

notepad "test-results\%latest%"




