# NOK Kuwait - Testing Quick Reference

## 🎯 Most Common Commands

### Run All Tests
```bash
run-tests.bat                    # Full suite with detailed reports
run-quick-tests.bat              # Quick validation
php artisan test                 # Standard Laravel test runner
php artisan test --parallel      # Faster parallel execution
```

### Run Specific Test Types
```bash
php artisan test --testsuite=Unit           # Unit tests only
php artisan test --testsuite=Feature        # Feature tests only
php artisan dusk                            # Browser tests
```

### Run Specific Files or Methods
```bash
vendor\bin\phpunit tests\Feature\MemberAuthTest.php           # One file
vendor\bin\phpunit --filter test_member_can_login             # One test
vendor\bin\phpunit tests\Feature --testdox                    # With descriptions
```

### Health Check
```bash
php health-check.php             # System health verification
```

### Test Data
```bash
php artisan db:seed --class=AutomatedTestSeeder    # Seed test data
php artisan migrate:fresh --seed --seeder=AutomatedTestSeeder
```

---

## 📊 Test Coverage
```bash
vendor\bin\phpunit --coverage-html coverage        # Generate HTML report
vendor\bin\phpunit --coverage-text                 # Console report
vendor\bin\phpunit --coverage-clover coverage.xml  # XML for CI/CD
```

---

## 🧪 Test Credentials

### Admin Users
- `admin@nok-kuwait.com` / `AdminSecure123!`
- `test.admin@nok-kuwait.com` / `TestAdmin123!`

### Test Members (Civil ID / Password)
- Active: `20000000002` / `Test123!`
- Expired: `40000000004` / `Test123!`
- Expiring Soon: `30000000003` / `Test123!`
- Pending: `10000000001` / `Test123!`

---

## 🐛 Debugging Tests

### Run with Verbose Output
```bash
php artisan test --testdox                # Descriptive output
vendor\bin\phpunit --verbose              # Detailed output
vendor\bin\phpunit --debug                # Debug mode
```

### Stop on First Failure
```bash
vendor\bin\phpunit --stop-on-failure      # Stop at first error
```

### Profile Slow Tests
```bash
php artisan test --profile                # Show slowest tests
```

---

## 🔧 Common Fixes

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Fix Permissions (Windows - Run as Admin)
```bash
icacls storage /grant Users:(OI)(CI)F /T
icacls bootstrap\cache /grant Users:(OI)(CI)F /T
```

### Database Issues
```bash
php artisan migrate:fresh --env=testing   # Fresh test database
php artisan db:seed --env=testing         # Seed test data
```

### Browser Test Issues
```bash
php artisan dusk:chrome-driver --detect   # Install correct driver
vendor\laravel\dusk\bin\chromedriver-win.exe  # Start driver manually
```

---

## 📁 Important Files

### Test Files
- `tests/Unit/` - Unit tests
- `tests/Feature/` - Feature tests
- `tests/Browser/` - Dusk browser tests

### Configuration
- `phpunit.xml` - PHPUnit configuration
- `.github/workflows/automated-tests.yml` - CI/CD
- `database/seeders/AutomatedTestSeeder.php` - Test data

### Scripts
- `run-tests.bat` - Full test runner
- `run-quick-tests.bat` - Quick tests
- `health-check.php` - Health monitor

### Results
- `test-results/` - Test reports
- `coverage/` - Coverage reports
- `tests/Browser/screenshots/` - Failed test screenshots

---

## ⚡ Quick Workflows

### Before Committing Code
```bash
run-quick-tests.bat              # Quick validation (2-3 min)
```

### Before Deploying
```bash
php health-check.php             # Verify system health
run-tests.bat                    # Full test suite
```

### After Adding New Feature
```bash
# Create test file in tests/Feature/
vendor\bin\phpunit tests\Feature\YourNewFeatureTest.php
```

### Daily Health Check
```bash
php health-check.php && run-quick-tests.bat
```

---

## 🚨 Emergency Commands

### Database Corrupted
```bash
php artisan migrate:fresh --seed --force
```

### Tests Hanging
```bash
# Kill PHP processes
taskkill /F /IM php.exe          # Windows
killall php                      # Linux/Mac
```

### Storage Full/Corrupted
```bash
# Clear all storage
php artisan storage:link
rd /s /q storage\framework\cache  # Windows
rm -rf storage/framework/cache    # Linux/Mac
mkdir storage\framework\cache
```

---

## 📊 CI/CD Status

### GitHub Actions
- **Location:** `.github/workflows/automated-tests.yml`
- **Triggers:** Push, PR, Daily at 2 AM UTC
- **View:** GitHub → Actions tab

### Manual Trigger
1. Go to GitHub repository
2. Click "Actions"
3. Select "Automated Testing Suite"
4. Click "Run workflow"

---

## 📞 Quick Help

### Test Fails - What to Check?
1. Error message in console
2. Test report in `test-results/`
3. Application logs in `storage/logs/`
4. Database state
5. Environment variables

### Browser Test Fails?
1. Check `tests/Browser/screenshots/`
2. Check `tests/Browser/console/`
3. Verify Chrome driver installed
4. Check if server is running

### All Tests Fail?
1. Run `php health-check.php`
2. Check database connection
3. Verify `.env` file
4. Run `composer install`
5. Clear all caches

---

## 💡 Tips

- Use `--parallel` for faster test execution
- Use `--testdox` for readable output
- Use `--filter` to run specific tests
- Keep test database separate from development
- Run health check daily
- Review coverage reports monthly
- Update tests when code changes

---

## 📚 Full Documentation

For complete details, see: `AUTOMATED_TESTING_GUIDE.md`

---

**Last Updated:** 2025-10-26




