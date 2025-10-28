@echo off
REM =================================================================
REM Automated Testing Suite Runner for NOK Kuwait
REM =================================================================
echo.
echo ========================================
echo NOK Kuwait - Automated Testing Suite
echo ========================================
echo.

REM Set color for output
color 0A

REM Check if vendor directory exists
if not exist "vendor\" (
    echo [ERROR] Vendor directory not found. Please run: composer install
    exit /b 1
)

REM Create test results directory
if not exist "test-results\" mkdir test-results

REM Set timestamp for report
for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set datetime=%%I
set timestamp=%datetime:~0,4%-%datetime:~4,2%-%datetime:~6,2%_%datetime:~8,2%-%datetime:~10,2%-%datetime:~12,2%

echo.
echo [1/7] Running Unit Tests...
echo ========================================
call vendor\bin\phpunit tests\Unit --testdox > test-results\unit-tests-%timestamp%.txt 2>&1
if %errorlevel% neq 0 (
    echo [FAILED] Unit tests failed. Check test-results\unit-tests-%timestamp%.txt
    set unit_failed=1
) else (
    echo [PASSED] Unit tests completed successfully
    set unit_failed=0
)

echo.
echo [2/7] Running Feature Tests...
echo ========================================
call vendor\bin\phpunit tests\Feature --testdox > test-results\feature-tests-%timestamp%.txt 2>&1
if %errorlevel% neq 0 (
    echo [FAILED] Feature tests failed. Check test-results\feature-tests-%timestamp%.txt
    set feature_failed=1
) else (
    echo [PASSED] Feature tests completed successfully
    set feature_failed=0
)

echo.
echo [3/7] Running Database Tests...
echo ========================================
call vendor\bin\phpunit tests\Feature\CompleteSystemTest.php --testdox > test-results\database-tests-%timestamp%.txt 2>&1
if %errorlevel% neq 0 (
    echo [FAILED] Database tests failed. Check test-results\database-tests-%timestamp%.txt
    set db_failed=1
) else (
    echo [PASSED] Database tests completed successfully
    set db_failed=0
)

echo.
echo [4/7] Running Authentication Tests...
echo ========================================
call vendor\bin\phpunit tests\Feature\MemberAuthTest.php tests\Feature\AdminAccessTest.php --testdox > test-results\auth-tests-%timestamp%.txt 2>&1
if %errorlevel% neq 0 (
    echo [FAILED] Authentication tests failed. Check test-results\auth-tests-%timestamp%.txt
    set auth_failed=1
) else (
    echo [PASSED] Authentication tests completed successfully
    set auth_failed=0
)

echo.
echo [5/7] Running Renewal System Tests...
echo ========================================
call vendor\bin\phpunit tests\Feature\RenewalFlowTest.php tests\Feature\RenewalRequestTest.php tests\Feature\RenewalReminderTest.php --testdox > test-results\renewal-tests-%timestamp%.txt 2>&1
if %errorlevel% neq 0 (
    echo [FAILED] Renewal tests failed. Check test-results\renewal-tests-%timestamp%.txt
    set renewal_failed=1
) else (
    echo [PASSED] Renewal tests completed successfully
    set renewal_failed=0
)

echo.
echo [6/7] Running Registration Tests...
echo ========================================
call vendor\bin\phpunit tests\Feature\RegistrationFormTest.php --testdox > test-results\registration-tests-%timestamp%.txt 2>&1
if %errorlevel% neq 0 (
    echo [FAILED] Registration tests failed. Check test-results\registration-tests-%timestamp%.txt
    set registration_failed=1
) else (
    echo [PASSED] Registration tests completed successfully
    set registration_failed=0
)

echo.
echo [7/7] Running Verification Tests...
echo ========================================
call vendor\bin\phpunit tests\Feature\VerificationTest.php tests\Feature\PublicVerificationTest.php --testdox > test-results\verification-tests-%timestamp%.txt 2>&1
if %errorlevel% neq 0 (
    echo [FAILED] Verification tests failed. Check test-results\verification-tests-%timestamp%.txt
    set verification_failed=1
) else (
    echo [PASSED] Verification tests completed successfully
    set verification_failed=0
)

REM Generate summary report
echo.
echo ========================================
echo          TEST SUMMARY REPORT
echo ========================================
echo Test Run: %timestamp%
echo.
echo Unit Tests:          %unit_failed:0=PASSED%
echo Feature Tests:       %feature_failed:0=PASSED%
echo Database Tests:      %db_failed:0=PASSED%
echo Authentication:      %auth_failed:0=PASSED%
echo Renewal System:      %renewal_failed:0=PASSED%
echo Registration:        %registration_failed:0=PASSED%
echo Verification:        %verification_failed:0=PASSED%
echo.

REM Calculate total failures
set /a total_failures=%unit_failed%+%feature_failed%+%db_failed%+%auth_failed%+%renewal_failed%+%registration_failed%+%verification_failed%

if %total_failures% equ 0 (
    echo [SUCCESS] All tests passed!
    echo ========================================
    echo.
    echo Full test reports saved to: test-results\
    exit /b 0
) else (
    echo [WARNING] %total_failures% test suite(s) failed
    echo ========================================
    echo.
    echo Please review the detailed reports in: test-results\
    exit /b 1
)




