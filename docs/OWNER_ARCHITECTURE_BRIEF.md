# Owner Architecture Brief

## 1) Modules (business capability map)

This codebase is a **multi-role school ERP/SIS** built on Laravel, split by role-specific route files and controller namespaces.

### Platform and entry layers
- `routes/web.php` handles public/auth flows (login, impersonation, admissions, OTP).  
- `routes/api.php` handles mobile/API endpoints under `/api`, including a Sanctum-protected `v2` area for authenticated parent/student use cases.  
- `App\Providers\RouteServiceProvider` maps route groups to role-specific controller namespaces and middleware.

### Role modules
- **School Admin** (`routes/admin.php`, `App\Http\Controllers\Admin`) — the largest module: admissions, academics, standards/sections, school details, holidays, dashboards, fees reminders, etc.
- **Teacher** (`routes/teacher.php`, `App\Http\Controllers\Teacher`) — assignment/homework/leave/task and conversation flows, including approval subflows.
- **Student** (`routes/student.php`, `App\Http\Controllers\Student`) — tasks, assignments, homework submissions, classwall, notice/notification/feed access.
- **Librarian** (`routes/librarian.php`, `App\Http\Controllers\Librarian`) — catalog/category/lending/import operations.
- **Receptionist** (`routes/receptionist.php`, `App\Http\Controllers\Receptionist`) — visitor/call/postal logs + communication and task surfaces.
- **Accountant + Payroll** (`routes/accountant.php`, `routes/payroll.php`, `App\Http\Controllers\Accountant|Payroll`) — payroll templates/salary/payslips/transactions and related dashboard flows.
- **Stock/Inventory/Superadmin** routes are scaffolded but currently sparse/empty in the route files shown.

### Cross-cutting modules
- **Authorization**: policy + gate-heavy model scoping in `AuthServiceProvider`.
- **Async/Eventing**: event-listener matrix in `EventServiceProvider` for notifications, reminders, push, messaging.
- **Scheduling**: cron-like task orchestration in `Console\Kernel` (subscription checks, birthday/reminder, mail/sms/notifications, task checks).
- **Observers**: model observers registered in `AppServiceProvider` for side effects around Events, Bulletin, Homework, User, Task, AcademicYear, etc.
- **Livewire admin surfaces**: numerous Livewire components under `app/Livewire/Admin` and conversation UI components.

---

## 2) Flows (how the system actually moves)

## Request/response flow
1. Request hits global middleware (`App\Http\Kernel`) then role route group middleware (e.g., `schooladmin`, `teacher`, `student`).
2. RouteServiceProvider dispatches to role namespace + route file.
3. Controller writes/reads Eloquent models.
4. Model observers may run implicit side effects.
5. Domain events trigger listeners (mail/push/notification).

## Background flow
1. `php artisan schedule:run` triggers `Console\Kernel` schedule.
2. Commands such as `gego:checktask`, `gego:checksendmail`, `gego:checkwebnotification` run periodically.
3. Those commands likely emit events, queue work, or send outbound communications.

## Mobile/API flow
1. `/api/parent/login` issues token.
2. Authenticated calls in `/api/v2/*` use `auth:sanctum`.
3. API exposes school/event/notice/homework/assignment/task/feedback/attendance flows for parent/student clients.

---

## 3) Dependencies (what this product relies on)

## Core
- Laravel Framework 12, PHP 8.4.
- Sanctum for API auth, Livewire (v3), Filament tables.

## Communication + Integrations
- Twilio SDK (SMS), Firebase + FCM channel, Pusher websocket stack, AWS S3 Flysystem, Guzzle.

## Data/search/content ecosystem
- Laravel Scout + Algolia client; Excel import/export; Spatie media/activity packages; DOMPDF for printable documents.

## Front-end
- Laravel Mix build, Vue 3 ecosystem, Tailwind + Bootstrap 4, FullCalendar, CKEditor, charting, uploader widgets, rich component plugins.

## Dependency-risk profile
- Multiple `dev-master` / wildcard / alpha pins in production dependencies increase supply-chain and stability risk.

---

## 4) Weak areas (owner-level concerns to prioritize)

1. **Security semantics in routing**  
   Many destructive operations still use `GET` routes (delete/edit action endpoints), which increases accidental invocation and CSRF/cache/proxy misuse risk.

2. **Authorization provider defects/ambiguity**  
   In `AuthServiceProvider`, the same model key (`App\Models\User`) is mapped multiple times in `$policies` (only last mapping is effective), and one Gate closure references an undefined variable (`$post_reply`) — both suggest fragile auth behavior.

3. **Legacy/upgrade mismatch risk**  
   App targets Laravel 12/PHP 8.4 while still carrying legacy/unstable package choices (`laravel/helpers`, `legacy-factories`, `airlock` namespace usage, several `dev-master` constraints). This can create hidden breakage during deploys/upgrades.

4. **Operational noise masking**  
   `AppServiceProvider` explicitly suppresses warnings/notices in newer PHP versions, which can hide real production defects.

5. **Background load & coupling**  
   Scheduler runs multiple minute-level commands plus many event/listener hooks and observer side effects; without strict queue isolation/monitoring this can become a bottleneck under scale.

---

## Owner recommendation (90-day architecture posture)

- **Phase 1 (risk reduction):** normalize HTTP verbs + CSRF-safe patterns, fix policy map and Gate defects, remove warning suppression.  
- **Phase 2 (platform hardening):** lock dependency versions (remove `*`, `dev-master`, alpha where possible), migrate deprecated packages/namespaces.  
- **Phase 3 (scale):** separate heavy notifications into queues with metrics, add command-level observability (success/fail/runtime), and prune route duplication.
