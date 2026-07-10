# GegoK12 Documentation Index

## 📚 Quick Links

### 🌐 View Documentation

-   **Interactive Viewer**: [docs-viewer.html](../docs-viewer.html) - Start here!
-   **Local Server**: http://localhost:8080/docs-viewer.html (Python server running)
-   **Terminal Command**: `./view-docs.sh`

### 🔧 Setup & Configuration

-   [DOCS_README.md](DOCS_README.md) - Complete documentation setup guide
-   [DOCUMENTATION_SETUP.md](DOCUMENTATION_SETUP.md) - Detailed setup instructions
-   [phpdoc.xml](../phpdoc.xml) - PHPDocumentor configuration

### 🔌 Plugin System

-   [PLUGIN_ARCHITECTURE.md](PLUGIN_ARCHITECTURE.md) - Full architecture, hook
    reference, and step-by-step guide for building a new plugin
    (`app/Models/Plugin.php`, `app/Services/PluginInstaller.php`,
    `gegok12:newPlugin`, `gegok12:extractPlugin`)

### 🚀 Scripts

-   `./view-docs.sh` - Open documentation in browser
-   `./generate-docs.sh` - Generate full PHPDocumentor documentation
-   `./verify_testing_setup.sh` - Verify test environment

---

## 📖 API Documentation

### Core Components

#### Traits

| Trait                  | Location                            | Purpose                                                 |
| ---------------------- | ----------------------------------- | ------------------------------------------------------- |
| **MSG91**              | `app/Traits/MSG91.php`              | SMS gateway integration (sendSMS, getOTP, emergencySMS) |
| **AuthenticatesUsers** | `app/Traits/AuthenticatesUsers.php` | Authentication validation with custom validators        |

#### Controllers

| Controller          | Location                                        | Purpose                                     |
| ------------------- | ----------------------------------------------- | ------------------------------------------- |
| **LoginController** | `app/Http/Controllers/Auth/LoginController.php` | Login form display & post-login redirection |

#### Middleware

| Middleware          | Location                                  | Purpose                                    |
| ------------------- | ----------------------------------------- | ------------------------------------------ |
| **AdminAccountant** | `app/Http/Middleware/AdminAccountant.php` | Access control for admin/accountant routes |

#### Models

| Model         | Location                   | Purpose                             |
| ------------- | -------------------------- | ----------------------------------- |
| **User**      | `app/Models/User.php`      | User authentication & relationships |
| **School**    | `app/Models/School.php`    | School management                   |
| **Usergroup** | `app/Models/Usergroup.php` | User group classification           |

---

## 🧪 Testing

### Test Files

-   `tests/Feature/AuthenticationTest.php` - Core authentication tests (8 tests)
-   `tests/Feature/TeacherStatusLifecycleTest.php` - Teacher status transitions (4 tests)
-   `tests/Feature/SchoolStatusLifecycleTest.php` - School status validation (5 tests)

### Running Tests

```bash
# All authentication tests
php artisan test tests/Feature/AuthenticationTest.php

# Teacher status lifecycle
php artisan test tests/Feature/TeacherStatusLifecycleTest.php

# School status validation
php artisan test tests/Feature/SchoolStatusLifecycleTest.php

# All tests
php artisan test
```

### Test Factories

-   `database/factories/UserFactory.php` - User creation with role-based states
    -   schoolAdmin() - Admin user
    -   librarian() - Librarian user
    -   student() - Student user
    -   teacher() - Teacher user
    -   accountant() - Accountant user
    -   parent() - Parent user
    -   receptionist() - Receptionist user
    -   stockKeeper() - Stock keeper user

---

## 🔐 Authentication System

### Login Flow

1. User submits credentials (email or registration_number + password)
2. `validateLogin()` runs custom validators:
    - `checkschool` - Verifies school is active (unless SuperAdmin)
    - `checkusers` - Validates user exists
    - `checkactive` - Prevents login if user is inactive
    - `checkexit` - Prevents login if user has exited
3. `attemptLogin()` authenticates credentials
4. `redirectTo()` routes based on role:
    - StockKeeper → `/stock/dashboard`
    - Others → `/admin/dashboard` (then middleware routes)

### User Roles

| ID  | Role         | Role ID      |
| --- | ------------ | ------------ |
| 3   | SchoolAdmin  | admin        |
| 5   | Teacher      | teacher      |
| 6   | Student      | student      |
| 7   | Parent       | parent       |
| 8   | Librarian    | librarian    |
| 10  | Receptionist | receptionist |
| 11  | Accountant   | accountant   |
| 12  | StockKeeper  | stock_keeper |

### User Status

-   `active` - Can login normally
-   `inactive` - Cannot login (suspended by admin)
-   `exit` - Cannot login (has exited school)

### School Status

-   `1` (active) - School is operational, users can login
-   `0` (inactive) - School is closed, non-admin users cannot login

---

## 📊 Database

### Key Tables

-   `users` - User accounts with status tracking
-   `schools` - School information with active/inactive status
-   `usergroups` - User role classifications
-   `userprofiles` - Extended user information
-   `reminders` - Scheduled messages and SMS tracking

### Important Enums

-   `users.status` - 'active', 'inactive', 'exit'
-   `schools.status` - 0 (inactive), 1 (active)

---

## 🛠️ Development

### Code Standards

-   PSR-12 coding standard (PHP-FIG)
-   Comprehensive phpDoc blocks on all public methods
-   Type hints for parameters and returns
-   Clean code principles

### Documentation

All code includes:

-   Class-level documentation explaining purpose
-   Method documentation with parameters and returns
-   Inline comments for complex logic
-   Exception documentation

### Git Workflow

-   Branch: `v1.1.1`
-   Repository: `gegok12-opensource`

---

## 📈 Recent Work

### Code Refactoring (Dec 11, 2025)

✅ **Completed:**

-   Cleaned up `AuthenticatesUsers` trait (315→261 lines)
-   Added comprehensive phpDoc to all methods
-   Removed deprecated `curl_close()` calls from MSG91 trait
-   Enhanced exception handling with try-finally blocks
-   Fixed inconsistent code formatting
-   Removed all commented debug code

### Testing Setup

✅ **Created:**

-   17 total test methods across 3 test files
-   5 factory files with auto-usergroup creation
-   Coverage for authentication flow, status validation, and lifecycle

### Documentation

✅ **Setup:**

-   Interactive documentation viewer
-   PHPDocumentor configuration
-   Documentation generation scripts
-   Comprehensive setup guides

---

## 🔗 Resources

### External Links

-   [Laravel Documentation](https://laravel.com/docs)
-   [PHPDocumentor Docs](https://docs.phpdoc.org/)
-   [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)
-   [PHPUnit Documentation](https://phpunit.de/)

### Internal Resources

-   [DOCS_README.md](DOCS_README.md) - Detailed setup guide
-   [DOCUMENTATION_SETUP.md](DOCUMENTATION_SETUP.md) - Multiple setup options
-   [docs-viewer.html](../docs-viewer.html) - Interactive API reference

---

## 📝 Summary

This documentation system provides:

-   📚 **Interactive API Reference** - Browse classes, methods, and documentation
-   🚀 **Quick Access** - One-command documentation viewing
-   🔄 **Auto-Generation** - Generate docs from phpDoc comments
-   📖 **Comprehensive Coverage** - All traits, controllers, middleware, and models documented
-   🧪 **Test Documentation** - Clear test suite with 17 test methods

**Status**: ✅ Production Ready
**Last Updated**: December 11, 2025
**Version**: 1.1.1

---

For questions or updates, refer to individual documentation files or regenerate with `./generate-docs.sh`
