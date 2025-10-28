# 🚀 NOK Membership Management System - Setup & Testing Guide

**Version**: 1.0  
**Date**: October 24, 2025

---

## ⚠️ **CRITICAL: PHP Version Requirement**

Your project requires **PHP 8.2 or higher**. Currently running PHP 7.3.20.

### **Step 1: Upgrade PHP in Laragon**

1. **Open Laragon**
2. Right-click Laragon tray icon → **Menu** → **PHP** → **Version**
3. If PHP 8.2+ is not listed:
   - Right-click → **Menu** → **PHP** → **Quick add** → **php-8.2-x64**
   - Wait for download and installation
4. Select **PHP 8.2** or **PHP 8.3**
5. **Stop All** and **Start All** in Laragon
6. **Verify** in PowerShell:
   ```powershell
   php -v
   ```
   Should show: `PHP 8.2.x` or higher

---

## 📦 **Step 2: Database Setup**

### Run Migrations
```powershell
cd F:\laragon\www\nok-kuwait
php artisan migrate
```

### Seed Admin User
```powershell
php artisan db:seed
```

This creates the admin user with:
- **Email**: admin@gmail.com
- **Password**: secret

---

## 📧 **Step 3: Email Configuration**

Edit `.env` file and add/update:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="NOK Kuwait"
```

### Gmail App Password Setup:
1. Go to: https://myaccount.google.com/apppasswords
2. Generate a new app password
3. Copy and paste into `MAIL_PASSWORD`

### Test Email:
```powershell
php artisan tinker
```
Then run:
```php
Mail::raw('Test email from NOK System', function($msg) {
    $msg->to('your-test-email@gmail.com')->subject('Test');
});
exit
```

---

## 🎯 **Step 4: Clear Caches & Start Server**

```powershell
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan serve --host=127.0.0.1 --port=8000
```

Server will run at: **http://127.0.0.1:8000**

---

## 🔐 **Admin Panel Access**

### Login Details:
- **URL**: http://127.0.0.1:8000/admin
- **Email**: admin@gmail.com
- **Password**: secret

### Admin Panel Features:
1. **Dashboard** - Overview statistics and widgets
2. **New Registrations** - Approve/reject member applications
3. **Renewals** - Manage expired memberships
4. **Renewal Requests** - Handle member renewal requests
5. **Offers & Discounts** - Create and assign offers to members
6. **Events** - Manage events with WYSIWYG editor
7. **Contact Messages** - View enquiries from contact form

---

## 👤 **Member Portal Access**

### Test Member Login:
- **URL**: http://127.0.0.1:8000/member/login
- **Email**: samkrishna23@gmail.com
- **Civil ID**: (from database)
- **Password**: (check email after admin approval)

### Member Dashboard Features:
- View membership card
- Download PDF membership card
- Request renewal (when expired or expiring soon)
- View assigned exclusive offers
- Check renewal status

---

## 🧪 **Step 5: Testing All Features**

### A. Test Admin Workflow

#### 1. **Approve a New Member**
```powershell
# Login to admin panel
# Go to: New Registrations
# Click on a pending registration
# Click "Approve" button
# System will:
#   - Generate NOK ID (e.g., NOK001002)
#   - Generate random password (e.g., NOK1234)
#   - Generate QR code
#   - Send membership card email with login credentials
```

#### 2. **Create an Event**
```powershell
# Login to admin panel
# Go to: Events
# Click "Create" button
# Fill in:
#   - Title: "Annual Gala 2025"
#   - Description: "Short summary..."
#   - Body: (Use rich editor for full content)
#   - Event Date: Select date
#   - Event Time: "6:00 PM - 9:00 PM"
#   - Location: "NOK Community Hall"
#   - Banner Image: Upload image
#   - Category: Select category
#   - Published: Toggle ON
#   - Featured: Toggle ON (optional)
# Click "Create"
```

#### 3. **Create and Assign an Offer**
```powershell
# Login to admin panel
# Go to: Offers & Discounts
# Click "Create" button
# Fill in:
#   - Title: "15% Discount at Medical Stores"
#   - Body: "Show your NOK membership card..."
#   - Promo Code: "NOKMED15"
#   - Starts At: Today
#   - Ends At: 6 months from now
#   - Active: Toggle ON
#   - Assign to Members: Search and select approved members
# Click "Create"
```

#### 4. **Handle Renewal Request**
```powershell
# Member submits renewal request from dashboard
# Admin: Go to Renewal Requests
# Badge shows count of pending requests
# Click on a request
# Click "Approve" button
# System updates card_valid_until to 1 year from now
# Sends email with renewed membership card
```

---

### B. Test Member Workflow

#### 1. **Register as New Member**
```powershell
# Visit: http://127.0.0.1:8000/registration
# Fill in all required fields
# Submit form
# Receive confirmation email
# Wait for admin approval
```

#### 2. **Login to Member Dashboard**
```powershell
# Visit: http://127.0.0.1:8000/member/login
# Enter:
#   - Email
#   - Civil ID
#   - Password (from approval email)
# Click "Login"
```

#### 3. **View Assigned Offers**
```powershell
# After login, scroll to "Exclusive Offers for Members"
# See list of assigned offers with promo codes
```

#### 4. **Request Renewal**
```powershell
# When membership expires or is expiring soon
# Click "Request Renewal" button
# System submits request to admin
# Shows "Renewal request submitted" message
```

---

### C. Test Public Pages

#### 1. **Verify Membership**
```powershell
# Visit: http://127.0.0.1:8000/verify-membership
# Enter Civil ID or NOK ID
# Optionally enter email for double verification
# Click "Verify Membership"
# View member details and status
```

#### 2. **View Events**
```powershell
# Visit: http://127.0.0.1:8000/events
# See featured events (if any)
# See all published events with pagination
# Click "Read Details" to view single event
```

#### 3. **Contact Form**
```powershell
# Visit: http://127.0.0.1:8000/contact
# Fill in name, email, subject, message
# Submit form
# Admin can view in: Contact Messages
```

---

## 🤖 **Step 6: Automated Tasks**

### Setup Renewal Reminders (Cron Job)

The system sends automatic renewal reminder emails at:
- **30 days** before expiry
- **15 days** before expiry
- **7 days** before expiry
- **1 day** before expiry

#### Manual Test:
```powershell
php artisan members:send-renewal-reminders --days=30,15,7,1
```

#### Schedule (runs daily at 8 AM):
```powershell
# In production, add to Windows Task Scheduler:
# Program: C:\laragon\bin\php\php-8.2.x\php.exe
# Arguments: artisan schedule:run
# Start in: F:\laragon\www\nok-kuwait
# Trigger: Daily at 8:00 AM
```

Or use Laravel's built-in scheduler:
```powershell
php artisan schedule:work
```

---

## 🛠️ **Troubleshooting**

### Issue 1: Admin Panel Not Loading
```powershell
php artisan optimize:clear
php artisan config:clear
```

### Issue 2: Events Not Showing
```powershell
# Check if migration ran:
php artisan migrate:status

# If not, run:
php artisan migrate
```

### Issue 3: Offers Not Appearing on Member Dashboard
```powershell
# Check if offer is:
# 1. Active (toggle ON)
# 2. Assigned to the member
# 3. Within start/end dates
# 4. Member is approved

# Create test offer:
php artisan test:assign-offer samkrishna23@gmail.com
```

### Issue 4: Emails Not Sending
```powershell
# Test mail configuration:
php artisan tinker
config('mail.default')  # Should show 'smtp'
config('mail.from')     # Should show your email
exit

# Check .env file has correct MAIL_* settings
```

### Issue 5: QR Code Not Showing
```powershell
# Check if storage is linked:
php artisan storage:link

# Ensure QrCode package is installed:
composer require simplesoftwareio/simple-qrcode
```

---

## 📊 **System Health Checklist**

- [ ] PHP 8.2+ installed and active
- [ ] All migrations run successfully
- [ ] Admin user seeded (admin@gmail.com / secret)
- [ ] Email configuration in `.env` complete
- [ ] Storage linked (`php artisan storage:link`)
- [ ] Admin panel accessible at `/admin`
- [ ] Member login works at `/member/login`
- [ ] Public verification works at `/verify-membership`
- [ ] Events page loads at `/events`
- [ ] Contact form works at `/contact`
- [ ] Test member approved and can login
- [ ] Test offer created and assigned
- [ ] Test event created and published
- [ ] Renewal reminder command works
- [ ] All caches cleared

---

## 🎓 **Quick Reference Commands**

```powershell
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start server
php artisan serve --host=127.0.0.1 --port=8000

# Create test offer for a member
php artisan test:assign-offer member@email.com

# Send renewal reminders manually
php artisan members:send-renewal-reminders

# Link storage
php artisan storage:link

# View routes
php artisan route:list

# Open Tinker (REPL)
php artisan tinker
```

---

## 📞 **Support & Documentation**

### Key Files:
- **System Audit Report**: `SYSTEM_AUDIT_REPORT.md`
- **Routes**: `routes/web.php`
- **Admin Panel Config**: `app/Providers/Filament/AdminPanelProvider.php`
- **Models**: `app/Models/`
- **Controllers**: `app/Http/Controllers/`
- **Views**: `resources/views/`
- **Migrations**: `database/migrations/`

### Admin Credentials (DO NOT SHARE):
```
URL: http://127.0.0.1:8000/admin
Email: admin@gmail.com
Password: secret
```

### Test Member Credentials:
```
URL: http://127.0.0.1:8000/member/login
Email: samkrishna23@gmail.com
Civil ID: (from database)
Password: (from approval email or reset password action)
```

---

**🎉 System is ready for testing after PHP upgrade!**

For detailed audit report, see: `SYSTEM_AUDIT_REPORT.md`

