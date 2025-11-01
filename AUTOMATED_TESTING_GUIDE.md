# NOK Kuwait - Automated Testing Guide

## 📋 Table of Contents
- [Overview](#overview)
- [Quick Start](#quick-start)
- [Test Types](#test-types)
- [Running Tests](#running-tests)
- [CI/CD Integration](#cicd-integration)
- [Health Monitoring](#health-monitoring)
- [Test Data Management](#test-data-management)
- [Troubleshooting](#troubleshooting)
- [Best Practices](#best-practices)

---

## 🎯 Overview

This automated testing suite ensures all functionality in the NOK Kuwait application works properly. It includes:

- **Unit Tests**: Test individual components and methods
- **Feature Tests**: Test complete user workflows and features
- **Browser Tests**: Test UI interactions using Laravel Dusk
- **Database Tests**: Verify data integrity and relationships
- **Security Tests**: Check authentication, validation, and security measures
- **API Tests**: Test all endpoints and routes

---

## ⚡ Quick Start

### Prerequisites
```bash
# Ensure you have PHP 8.2+ and Composer installed
php --version
composer --version

# Install dependencies if not already done
composer install
```

### Run All Tests
```bash
# Windows
run-tests.bat

# Linux/Mac
php artisan test
```

### Quick Test Run (No detailed logs)
```bash
# Windows
run-quick-tests.bat

# Linux/Mac
php artisan test --parallel
```

---

## 🧪 Test Types

### 1. Unit Tests (`tests/Unit/`)
Test individual components in isolation.

**Examples:**
- `ApprovalMailTest.php` - Tests email generation
- `ReminderCommandTest.php` - Tests scheduled commands

**Run:**
```bash
php artisan test --testsuite=Unit
```

### 2. Feature Tests (`tests/Feature/`)
Test complete features and workflows.

**Key Test Files:**
- `ApiEndpointsTest.php` - All public and authenticated endpoints
- `DatabaseIntegrityTest.php` - Database structure and relationships
- `SecurityValidationTest.php` - Security measures and validation
- `MemberAuthTest.php` - Member authentication system
- `RenewalFlowTest.php` - Membership renewal process
- `RegistrationFormTest.php` - Member registration
- `VerificationTest.php` - Public membership verification
- `CompleteSystemTest.php` - End-to-end system tests

**Run:**
```bash
php artisan test --testsuite=Feature
```

### 3. Browser Tests (`tests/Browser/`)
Test user interactions in real browser using Laravel Dusk.

**Run:**
```bash
php artisan dusk
```

**Prerequisites:**
```bash
# Install Chrome driver
php artisan dusk:chrome-driver

# Or detect and install automatically
php artisan dusk:chrome-driver --detect
```

---

## 🚀 Running Tests

### Run All Tests with Detailed Reports
```bash
# Windows
run-tests.bat

# This will:
# - Run all test suites
# - Generate detailed reports
# - Save results to test-results/ directory
# - Display a summary
```

### Run Specific Test Suites

#### Unit Tests Only
```bash
vendor\bin\phpunit tests\Unit --testdox
```

#### Feature Tests Only
```bash
vendor\bin\phpunit tests\Feature --testdox
```

#### Specific Test File
```bash
vendor\bin\phpunit tests\Feature\MemberAuthTest.php --testdox
```

#### Specific Test Method
```bash
vendor\bin\phpunit --filter test_member_can_login
```

### Run Tests in Parallel
```bash
php artisan test --parallel
```

### Run Tests with Coverage Report
```bash
vendor\bin\phpunit --coverage-html coverage
```
Then open `coverage/index.html` in your browser.

---

## 🔄 CI/CD Integration

### GitHub Actions

The repository includes automated testing via GitHub Actions.

**Configuration:** `.github/workflows/automated-tests.yml`

**Triggers:**
- Push to `main`, `develop`, or `staging` branches
- Pull requests to `main` or `develop`
- Daily at 2 AM UTC (scheduled)
- Manual trigger via GitHub Actions UI

**What It Tests:**
1. Unit tests
2. Feature tests
3. Browser tests (Dusk)
4. Code style (Laravel Pint)
5. Security vulnerabilities (Composer Audit)

**Viewing Results:**
1. Go to your GitHub repository
2. Click "Actions" tab
3. Select the workflow run
4. View test results and download artifacts

**Artifacts Generated:**
- Test coverage reports
- Browser screenshots (on failure)
- Browser console logs (on failure)

---

## 🏥 Health Monitoring

### System Health Check

Run comprehensive health checks to ensure all systems are operational:

```bash
php health-check.php
```

**This checks:**
- ✅ Database connectivity
- ✅ Required tables exist
- ✅ Critical data presence
- ✅ File permissions
- ✅ Environment variables
- ✅ Web server access
- ✅ Authentication systems
- ✅ Admin panel
- ✅ Public pages
- ✅ Email configuration
- ✅ Storage directories
- ✅ Dependencies
- ✅ Application key
- ✅ Debug mode settings
- ✅ Queue configuration

**Exit Codes:**
- `0` - All checks passed
- `1` - Critical failures detected

### Scheduling Health Checks

You can schedule automatic health checks:

**Windows Task Scheduler:**
```bash
schtasks /create /tn "NOK Health Check" /tr "php F:\laragon\www\nok-kuwait\health-check.php" /sc daily /st 02:00
```

**Linux/Mac Cron:**
```bash
0 2 * * * cd /path/to/nok-kuwait && php health-check.php
```

---

## 📊 Test Data Management

### Automated Test Seeder

The `AutomatedTestSeeder` creates comprehensive test data.

**Run Seeder:**
```bash
php artisan db:seed --class=AutomatedTestSeeder
```

**Test Data Created:**

#### Admin Users
- `admin@nok-kuwait.com` / `AdminSecure123!`
- `test.admin@nok-kuwait.com` / `TestAdmin123!`

#### Test Members
| Status | Civil ID | Email | Password |
|--------|----------|-------|----------|
| Pending | 10000000001 | pending@test.com | Test123! |
| Active | 20000000002 | active@test.com | Test123! |
| Expiring Soon | 30000000003 | expiring@test.com | Test123! |
| Expired | 40000000004 | expired@test.com | Test123! |
| Renewal Requested | 50000000005 | renewal.requested@test.com | Test123! |
| Rejected | 60000000006 | rejected@test.com | Test123! |

#### Other Test Data
- 4 Events (3 published, 1 draft)
- 5 Offers (3 active, 1 expired, 1 inactive)
- 3 Contact Messages
- 2 Renewal Records

### Fresh Test Database

**Recreate entire database with test data:**
```bash
php artisan migrate:fresh --seed --seeder=AutomatedTestSeeder
```

**For testing environment:**
```bash
php artisan migrate:fresh --seed --seeder=AutomatedTestSeeder --env=testing
```

---

## 🔧 Troubleshooting

### Common Issues

#### 1. Tests Fail Due to Database Connection

**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Solution:**
```bash
# Check database is running
# Verify .env settings:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nok_kuwait
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 2. Browser Tests (Dusk) Fail

**Error:** `Chrome driver not found`

**Solution:**
```bash
# Install correct Chrome driver version
php artisan dusk:chrome-driver --detect
```

**Error:** `Facebook\WebDriver\Exception\WebDriverCurlException`

**Solution:**
```bash
# Ensure Chrome driver is running
vendor\laravel\dusk\bin\chromedriver-win.exe

# In another terminal, run tests
php artisan dusk
```

#### 3. Permission Errors

**Error:** `The stream or file could not be opened`

**Solution:**
```bash
# Fix storage permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache

# Windows - Run as Administrator or check folder properties
```

#### 4. Memory Limit Errors

**Error:** `Allowed memory size exhausted`

**Solution:**
```bash
# Temporarily increase memory limit
php -d memory_limit=512M artisan test
```

Or update `php.ini`:
```ini
memory_limit = 512M
```

#### 5. Tests Run Slowly

**Solution:**
```bash
# Use parallel testing
php artisan test --parallel

# Or use SQLite for faster tests
# In phpunit.xml, change:
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

#### 6. Rate Limiting Issues in Tests

**Solution:**
```bash
# Clear rate limiter cache
php artisan cache:clear

# Or in test setup:
RateLimiter::clear('key-name');
```

---

## ✅ Best Practices

### 1. Run Tests Before Committing
```bash
# Always run tests before pushing code
run-quick-tests.bat
```

### 2. Write Tests for New Features
When adding new features, create corresponding tests:

```php
// tests/Feature/MyNewFeatureTest.php
class MyNewFeatureTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function new_feature_works_correctly()
    {
        // Arrange
        // Act
        // Assert
    }
}
```

### 3. Test Both Success and Failure Cases
```php
/** @test */
public function registration_succeeds_with_valid_data()
{
    // Test success case
}

/** @test */
public function registration_fails_with_invalid_email()
{
    // Test failure case
}
```

### 4. Use Descriptive Test Names
```php
// Good ✓
public function member_can_login_with_valid_credentials()

// Bad ✗
public function test1()
```

### 5. Keep Tests Independent
Each test should:
- Set up its own data
- Not depend on other tests
- Clean up after itself (use `RefreshDatabase`)

### 6. Use Test Factories for Complex Data
```php
// Instead of manually creating data
Registration::factory()->count(10)->create();
```

### 7. Mock External Services
```php
// Don't actually send emails in tests
Mail::fake();

// Don't actually call external APIs
Http::fake([
    'api.example.com/*' => Http::response(['status' => 'ok'], 200)
]);
```

### 8. Test Security Features
Always test:
- Authentication
- Authorization
- Input validation
- SQL injection prevention
- XSS prevention
- CSRF protection

### 9. Monitor Test Performance
```bash
# Use --profile to see slowest tests
php artisan test --profile
```

### 10. Keep Tests Updated
When code changes:
- Update relevant tests
- Add new tests for new scenarios
- Remove obsolete tests

---

## 📈 Test Coverage Goals

### Current Coverage
Run to see current coverage:
```bash
vendor\bin\phpunit --coverage-text
```

### Target Coverage
- **Overall:** 80%+
- **Models:** 90%+
- **Controllers:** 75%+
- **Critical Features:** 95%+

### Improving Coverage
1. Identify untested code:
   ```bash
   vendor\bin\phpunit --coverage-html coverage
   ```
2. Add tests for uncovered lines
3. Focus on critical paths first
4. Gradually increase coverage

---

## 🔍 Continuous Monitoring

### Daily Automated Checks
Set up daily automated testing:

**Option 1: GitHub Actions (Scheduled)**
Already configured to run daily at 2 AM UTC.

**Option 2: Local Cron/Task Scheduler**
```bash
# Windows Task Scheduler
schtasks /create /tn "NOK Daily Tests" /tr "F:\laragon\www\nok-kuwait\run-tests.bat" /sc daily /st 02:00

# Linux/Mac Cron
0 2 * * * cd /path/to/nok-kuwait && ./run-tests.bat
```

### Alert on Failures
Configure email notifications for test failures:

**In CI/CD:**
- GitHub Actions sends email on failure
- Check repository settings → Notifications

**Locally:**
Modify `run-tests.bat` to send email on failure:
```batch
if %total_failures% neq 0 (
    REM Send email alert
    echo Test failures detected | mail -s "NOK Kuwait Test Failure" admin@email.com
)
```

---

## 📞 Support & Reporting Issues

### Running Tests Summary

| Command | Purpose | Time |
|---------|---------|------|
| `run-tests.bat` | Full test suite with reports | 5-10 min |
| `run-quick-tests.bat` | Quick validation | 2-3 min |
| `php artisan test` | All tests | 3-5 min |
| `php artisan dusk` | Browser tests only | 3-4 min |
| `php health-check.php` | System health check | < 1 min |

### Test Results Location
- **Full Reports:** `test-results/`
- **Coverage Reports:** `coverage/`
- **Dusk Screenshots:** `tests/Browser/screenshots/`
- **Dusk Logs:** `tests/Browser/console/`

### When Tests Fail
1. Check the specific error message
2. Review the test report in `test-results/`
3. Look for screenshots if browser test failed
4. Check logs in `storage/logs/`
5. Run health check: `php health-check.php`
6. Run individual test to isolate issue
7. Check database state
8. Verify environment configuration

---

## 🎓 Learning Resources

### Laravel Testing Documentation
- [Laravel Testing Guide](https://laravel.com/docs/testing)
- [Database Testing](https://laravel.com/docs/database-testing)
- [HTTP Tests](https://laravel.com/docs/http-tests)
- [Browser Tests (Dusk)](https://laravel.com/docs/dusk)

### PHPUnit Documentation
- [PHPUnit Manual](https://phpunit.de/manual/current/en/index.html)
- [Assertions](https://phpunit.de/manual/current/en/assertions.html)

### Best Practices
- [TDD (Test-Driven Development)](https://en.wikipedia.org/wiki/Test-driven_development)
- [AAA Pattern](https://docs.microsoft.com/en-us/visualstudio/test/unit-test-basics) (Arrange, Act, Assert)

---

## 📝 Changelog

### Version 1.0 (Current)
- ✅ Complete test suite implemented
- ✅ CI/CD integration with GitHub Actions
- ✅ Automated test runner scripts
- ✅ Health check monitoring
- ✅ Comprehensive test data seeder
- ✅ API endpoint tests
- ✅ Database integrity tests
- ✅ Security validation tests
- ✅ Full documentation

---

## 🚀 Next Steps

1. **Run Initial Test:**
   ```bash
   run-quick-tests.bat
   ```

2. **Check System Health:**
   ```bash
   php health-check.php
   ```

3. **Seed Test Data:**
   ```bash
   php artisan db:seed --class=AutomatedTestSeeder
   ```

4. **Run Full Test Suite:**
   ```bash
   run-tests.bat
   ```

5. **Review Results:**
   - Check `test-results/` folder
   - Open coverage report: `coverage/index.html`

6. **Set Up Automation:**
   - Enable GitHub Actions
   - Schedule daily health checks
   - Configure email alerts

---

## ✨ Conclusion

Your NOK Kuwait application now has a comprehensive automated testing suite that ensures all functionality works properly. Tests run automatically on every push, and you can run them locally anytime.

**Remember:** 
- Run tests before deploying
- Keep tests updated with code changes
- Monitor test results regularly
- Aim for high test coverage on critical features

**Happy Testing! 🎉**




