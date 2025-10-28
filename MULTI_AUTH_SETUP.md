# Multi-Auth Setup for Filament (Admin + Member Panels)

This document explains the multi-authentication setup implemented in the NOK Kuwait Membership Management System using Filament v3.

## 🎯 Overview

The system now has **two separate Filament panels**:
1. **Admin Panel** - For system administrators to manage the membership system
2. **Member Panel** - For approved members to view their membership details and offers

## 🔐 Authentication Guards

### Configuration (`config/auth.php`)

Two authentication guards are configured:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',  // For Admin users
    ],
    'members' => [
        'driver' => 'session',
        'provider' => 'members',  // For Member users
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,  // Admin model
    ],
    'members' => [
        'driver' => 'eloquent',
        'model' => App\Models\Member::class,  // Member model (registrations table)
    ],
],
```

## 📁 Panel Structure

### Admin Panel
- **Path:** `/admin`
- **Login:** `/admin/login`
- **Model:** `App\Models\User`
- **Guard:** `web` (default)
- **Provider:** `App\Providers\Filament\AdminPanelProvider`

### Member Panel
- **Path:** `/member/panel`
- **Login:** `/member/panel/login`
- **Model:** `App\Models\Member` (uses `registrations` table)
- **Guard:** `members`
- **Provider:** `App\Providers\Filament\MemberPanelProvider`

## 🎨 Panel Configurations

### Admin Panel Features
- **Brand:** 🌟 NOK Admin
- **Color Scheme:** Emerald Green (primary)
- **Font:** Poppins
- **Resources:**
  - Registrations
  - Renewals
  - Renewal Requests
  - Offers & Discounts
  - Events
  - Contact Messages
- **Widgets:**
  - Stats Overview (with gradient cards)
  - Recent Renewals
  - Expiring Soon
  - Verification Attempts Chart

### Member Panel Features
- **Brand:** 🎫 Member Portal
- **Color Scheme:** Sky Blue (primary)
- **Font:** Inter
- **Auth Guard:** `members`
- **Access Control:** Only approved members (`renewal_status === 'approved'`)
- **Widgets:**
  - Member Stats (Status, Join Date, Offers Count, Validity)
  - Membership Card (with download & renewal request)
  - Exclusive Offers

## 👥 Models Implementation

### User Model (Admin)
```php
class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin';
    }

    public function getFilamentName(): string
    {
        return $this->firstname && $this->lastname 
            ? $this->firstname . ' ' . $this->lastname 
            : $this->username ?? $this->email;
    }
}
```

### Member Model
```php
class Member extends Authenticatable implements FilamentUser
{
    protected $table = 'registrations';

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'member') {
            return $this->renewal_status === 'approved';
        }
        return false;
    }

    public function getFilamentName(): string
    {
        return $this->memberName ?? $this->email;
    }
}
```

## 🚀 Member Login Credentials

Members can log in to the **Member Panel** using:

### Option 1: Email + Password
- **Email:** Their registered email address
- **Password:** Auto-generated password sent via email on approval

### Option 2: Civil ID + Password (Recommended)
- **Civil ID:** Their Kuwait Civil ID
- **Password:** Same as above

> **Note:** Members must be **approved** (`renewal_status = 'approved'`) to access the panel.

## 📊 Member Panel Widgets

### 1. Member Stats Widget
Shows 4 key statistics:
- Membership Status (Approved/Pending/Rejected)
- Member Since (Join Date)
- Exclusive Offers (Count of assigned offers)
- Valid Until (Card expiry date)

### 2. Membership Card Widget
Displays:
- Member Name, NOK ID, Civil ID
- Card Validity Status (Active/Expired/Expiring Soon)
- Actions:
  - **Download Card** (PDF)
  - **Verify Card** (Public verification page)
  - **Request Renewal** (when expired/expiring)

### 3. Member Offers Widget
Lists all exclusive offers assigned to the member:
- Offer Title
- Offer Description
- Promo Code (if available)

## 🔄 Workflow

### For Admins:
1. Login at `/admin` using admin credentials
2. Manage registrations, renewals, offers, events
3. Approve/reject member applications
4. Assign offers to specific members
5. Monitor system stats and activity

### For Members:
1. Register through the public registration form
2. Wait for admin approval
3. Receive approval email with login credentials
4. Login at `/member/panel` using email or civil ID
5. View membership card and exclusive offers
6. Download membership card PDF
7. Request renewal when card expires

## 🛠️ Technical Implementation

### Directory Structure
```
app/
├── Filament/
│   ├── Resources/          # Admin resources
│   ├── Pages/              # Admin pages
│   ├── Widgets/            # Admin widgets
│   └── Member/             # Member panel
│       ├── Resources/      # Member resources (future)
│       ├── Pages/          # Member custom pages (future)
│       └── Widgets/        # Member widgets
│           ├── MemberStatsWidget.php
│           ├── MembershipCard.php
│           └── MemberOffersWidget.php
└── Providers/
    └── Filament/
        ├── AdminPanelProvider.php
        └── MemberPanelProvider.php
```

### Provider Registration
Both panel providers are registered in `bootstrap/providers.php`:
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\MemberPanelProvider::class,
];
```

## 🎯 Access Control

### Admin Panel
- **Route:** `/admin/*`
- **Auth Guard:** `web`
- **Middleware:** `Authenticate`
- **Check:** User model's `canAccessPanel('admin')` returns true

### Member Panel
- **Route:** `/member/panel/*`
- **Auth Guard:** `members`
- **Middleware:** `Authenticate`
- **Check:** Member's `renewal_status === 'approved'` and `canAccessPanel('member')` returns true

## 🔒 Security Features

1. **Separate Guards:** Admin and Member sessions are completely isolated
2. **Access Control:** `FilamentUser` interface enforces panel-specific access
3. **Approval Required:** Members must be approved to access their panel
4. **Password Hashing:** All passwords are automatically bcrypt-hashed
5. **Session Management:** Each panel has its own session state

## 🎨 Customization

### Changing Panel Colors
Edit the respective `*PanelProvider.php` file:
```php
->colors([
    'primary' => Color::Blue,  // Change primary color
    'danger' => Color::Red,
    // ... more colors
])
```

### Adding Member Resources
Create resources in `app/Filament/Member/Resources/` to allow members to manage their own data.

### Custom Member Pages
Create pages in `app/Filament/Member/Pages/` for additional member functionality.

## 📧 Email Notifications

Members receive emails for:
1. **Approval:** Login credentials + membership card PDF
2. **Renewal:** Reminder emails (30, 15, 7, 1 day before expiry)
3. **Password Reset:** New password when reset by admin

## 🧪 Testing

### Test Admin Login
```bash
# Default admin credentials
Email: admin@gmail.com
Password: secret
URL: http://127.0.0.1:8000/admin
```

### Test Member Login
```bash
# Use an approved member's credentials
Email: samkrishna23@gmail.com (or any approved member)
Civil ID: 123459999999
Password: (generated password from email)
URL: http://127.0.0.1:8000/member/panel
```

## 🐛 Troubleshooting

### "Unauthenticated" Error
- Ensure the member is approved (`renewal_status = 'approved'`)
- Check that you're using the correct guard (`members` not `web`)
- Clear cache: `php artisan optimize:clear`

### Member Panel Not Showing
- Verify `MemberPanelProvider` is registered in `bootstrap/providers.php`
- Clear cache and rebuild: `php artisan optimize:clear`
- Check routes: `php artisan route:list --path=member`

### Widgets Not Displaying
- Ensure widget views exist in `resources/views/filament/member/widgets/`
- Check widget class methods (`getView()`, `getViewData()`)
- Verify widget is registered in `MemberPanelProvider`

## 📝 Future Enhancements

1. **Member Profile Editing:** Allow members to update their own information
2. **Event Registration:** Members can register for events through their panel
3. **Payment Integration:** Online payment for renewals
4. **Digital ID Card:** Display card directly in the member panel
5. **Member Directory:** Allow members to connect with each other
6. **Notifications:** Real-time notifications for offers and events

## 🎉 Benefits

✅ **Separation of Concerns:** Admin and member functionalities are completely isolated
✅ **Security:** Each user type has its own authentication system
✅ **Scalability:** Easy to add more panels (e.g., Staff, Volunteers)
✅ **User Experience:** Members have a clean, focused interface
✅ **Maintainability:** Clear file structure and organization
✅ **Professional:** Modern, responsive UI with dark mode support

---

**Last Updated:** October 24, 2025
**Version:** 1.0
**Filament Version:** 3.x
**Laravel Version:** 11.x





