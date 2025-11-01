# 🛠️ Deployment Commands Cheatsheet
**Quick Reference for NOK Kuwait Hostinger Deployment**

---

## 📦 **Pre-Deployment (Local)**

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Install production dependencies
composer install --optimize-autoloader --no-dev

# Compile assets
npm run build

# Export database
mysqldump -u root -p nok_kuwait > nok_kuwait_backup.sql
```

---

## 🚀 **On Hostinger Server**

### Initial Setup
```bash
# Connect via SSH
ssh u123456789@your-domain.com

# Navigate to project
cd ~/nok-kuwait

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Generate app key (if needed)
php artisan key:generate

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create storage link
php artisan storage:link

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### After ANY .env Changes
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Rebuild Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize
```

---

## 🗄️ **Database Commands**

```bash
# Import database via SSH
mysql -u u123456789_nok_user -p u123456789_nok_kuwait < nok_kuwait_backup.sql

# Run migrations (if needed)
php artisan migrate --force

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit
```

---

## 📧 **Email Testing**

```bash
# Test SMTP connection
php artisan tinker
>>> Mail::raw('Test email', function($message) {
...     $message->to('your-email@example.com')
...             ->subject('Test Email from NOK Kuwait');
... });
>>> exit

# Send test renewal reminder
php artisan members:send-renewal-reminders

# Check scheduled tasks
php artisan schedule:list
```

---

## ⏰ **Cron Job Setup**

**In Hostinger hPanel → Cron Jobs:**

```bash
* * * * * cd /home/u123456789/nok-kuwait && php artisan schedule:run >> /dev/null 2>&1
```

**Test cron manually:**
```bash
php artisan schedule:run
php artisan schedule:list
```

---

## 🔧 **Troubleshooting Commands**

### 500 Internal Server Error
```bash
chmod -R 755 storage bootstrap/cache
php artisan key:generate
php artisan config:clear
php artisan cache:clear
tail -f storage/logs/laravel.log
```

### Storage Link Issues
```bash
# Remove old link
rm public/storage

# Recreate link
php artisan storage:link

# Or manual copy
cp -r storage/app/public/* public/storage/
```

### Queue Issues
```bash
# Process queued jobs
php artisan queue:work --stop-when-empty

# Clear failed jobs
php artisan queue:flush

# Restart queue workers
php artisan queue:restart
```

### Cache Issues
```bash
# Clear everything
php artisan optimize:clear

# Then rebuild
php artisan optimize
```

### View Not Found
```bash
php artisan view:clear
php artisan view:cache
```

### Route Not Found
```bash
php artisan route:clear
php artisan route:cache
```

---

## 🔒 **File Permissions**

```bash
# Standard permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# If issues persist
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Secure .env file
chmod 644 .env
```

---

## 🔍 **Diagnostic Commands**

```bash
# Check Laravel version
php artisan --version

# Check PHP version
php -v

# Check installed packages
composer show

# Check environment
php artisan about

# Check database status
php artisan db:show

# Check migration status
php artisan migrate:status

# View routes
php artisan route:list

# Check scheduled commands
php artisan schedule:list

# Check queue status
php artisan queue:monitor

# Check storage disk
df -h

# Check PHP processes
ps aux | grep php

# View error log (last 50 lines)
tail -50 storage/logs/laravel.log

# Follow error log in real-time
tail -f storage/logs/laravel.log
```

---

## 🎨 **Asset Commands**

```bash
# Compile for production
npm run build

# If using mix
npm run production

# Clear compiled assets
rm -rf public/build/*
rm -rf public/css/*
rm -rf public/js/*
```

---

## 📊 **Database Maintenance**

```bash
# Backup database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Optimize database
php artisan db:optimize

# Seed database (only on first setup)
php artisan db:seed

# Fresh database (WARNING: deletes all data)
php artisan migrate:fresh --seed
```

---

## 🔄 **Update/Maintenance Mode**

```bash
# Enable maintenance mode
php artisan down --message="Updating system" --retry=60

# Perform updates
git pull
composer install --no-dev
npm run build
php artisan migrate --force
php artisan cache:clear
php artisan config:cache

# Disable maintenance mode
php artisan up
```

---

## 🧪 **Testing Commands**

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=RenewalFlowTest

# Test with coverage
php artisan test --coverage

# Test email sending
php artisan members:send-renewal-reminders --help
```

---

## 📝 **Logging**

```bash
# View Laravel log
tail -f storage/logs/laravel.log

# View last 100 lines
tail -100 storage/logs/laravel.log

# Search for errors
grep "ERROR" storage/logs/laravel.log

# Clear log file
> storage/logs/laravel.log

# Check log file size
ls -lh storage/logs/
```

---

## 🔐 **Security Commands**

```bash
# Generate new app key
php artisan key:generate

# Clear expired password reset tokens
php artisan auth:clear-resets

# Clear old sessions
php artisan session:table
php artisan migrate
php artisan schedule:run
```

---

## 🌐 **URL & Domain**

```bash
# Change app URL (in .env)
APP_URL=https://your-new-domain.com

# Then clear cache
php artisan config:clear
php artisan cache:clear
```

---

## 📦 **Composer Commands**

```bash
# Install dependencies
composer install --no-dev

# Update packages
composer update

# Dump autoload
composer dump-autoload --optimize

# Check for outdated packages
composer outdated

# Remove dev dependencies
composer install --no-dev --optimize-autoloader
```

---

## 🚨 **Emergency Recovery**

```bash
# Full system reset (after backup!)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📱 **Hostinger Specific**

```bash
# Check PHP version
php -v

# Switch PHP version (in hPanel)
# Advanced → PHP Configuration → Select version

# Check available disk space
df -h

# Check memory usage
free -h

# List active cron jobs
crontab -l

# Edit cron jobs
crontab -e
```

---

## ✅ **Post-Deployment Verification**

```bash
# Run all checks
php artisan about
php artisan route:list
php artisan schedule:list
php artisan queue:monitor
php artisan db:show

# Test email
php artisan tinker
>>> Mail::raw('System operational', function($m){$m->to('admin@example.com')->subject('Test');});

# Test database
php artisan tinker
>>> \App\Models\User::count()
>>> \App\Models\Registration::count()
```

---

## 🎯 **Most Used Commands**

```bash
# Daily use
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# After code changes
php artisan view:clear
php artisan route:clear
php artisan config:clear

# For deployments
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 💡 **Pro Tips**

1. **Always backup before major changes**
2. **Test in staging environment first**
3. **Keep .env file secure (never commit)**
4. **Monitor error logs regularly**
5. **Set up automatic backups**
6. **Document custom configurations**
7. **Keep packages updated**
8. **Use maintenance mode for updates**

---

**Quick Access:**
- Error Logs: `storage/logs/laravel.log`
- Environment: `.env`
- Cache: `storage/framework/cache/`
- Sessions: `storage/framework/sessions/`
- Views: `storage/framework/views/`

**Need Help?**
- Laravel Docs: https://laravel.com/docs
- Filament Docs: https://filamentphp.com/docs
- Hostinger Support: 24/7 Live Chat

---

*Copy and paste commands as needed. Always backup before major operations!*








