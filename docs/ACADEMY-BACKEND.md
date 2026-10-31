# Academy backend, existing frontend

This change starts from the restored original application. It makes no changes to
resources/, public/, Blade, Vue, CSS, menus, branding or page layouts. CI checks that
the frontend source matches commit 95b17a73cad638d5db367937f9350d338a899e73 exactly.

## What changes

- ACADEMY_MODE=true (default) uses one active teaching context per academy. No
  shared global academic-year selector affects another administrator or academy.
  A fresh academy gets a continuous teaching context and six course levels
  automatically. Existing records/levels/periods are not renamed or overwritten.
- Direct group creation can take group_name, level (1–6), teacher_id, with no
  school year or section setup. It creates the compatible original classroom
  relationship plus Unit 1/Unit 2 and teacher assignments in the original tables.
  The original group_name + standards_link_id payload and response fields remain.
- Adding students to a group updates the original student_academics relationship
  used by learning screens. A transfer soft-archives the previous active enrollment
  and different-class memberships for the same period, rather than deleting history.
  Requests are atomic and repeat enrollment does not duplicate membership.
- Group lists and mutations are academy-scoped. PATCH /admin/group/{id} renames
  a group; DELETE /admin/group/{id} soft-archives it and its memberships. This
  retains the classroom, lessons, attendance and enrollment history.
- Library, inventory, payroll, visitor/office administration, admissions, manual
  school-year changes, and pass/fail promotion actions are disabled server-side in
  academy mode. Existing navigation markup is untouched: an unused HTML action
  returns to the existing dashboard with a message; JSON requests receive 410.
  These modules' historical data and source are retained for compatibility.
- Core learning, homework, attendance, birthdays, messaging and student/teacher/
  parent/admin accounts remain. Core academy login roles are 3/5/6/7; the system
  owner role 1 remains available. Other school staff roles cannot start new academy
  sessions while academy mode is enabled.

The school_id field remains the academy/tenant boundary; it is not a requirement
to model a school. The old academic_year_id/standardLink_id fields are compatibility
relationships so the existing homework and attendance screens do not become a
separate disconnected application. New course creation is explicit. Existing
school-style demo records are not converted or deleted automatically.

The simplified group payload is a backend capability. This change does not add
level/teacher controls or rename fields in the original frontend.

## Learning reads for the four roles

Authenticated GET endpoints (web session and existing auth):

- /learning/students — paginated students visible to this account.
- /learning/students/{id} — current enrollment.
- /learning/students/{id}/homework — approved current-group work, 20 per page.
- /learning/students/{id}/attendance — the child's attendance history, 30 per page.

Administrators see their academy, teachers see their assigned active-context
students, students see themselves, parents see linked children. No impersonation
or sharing of credentials is introduced. Parent access is a read API; this change
does not add or redesign a parent dashboard or rewire the original UI to these APIs.
Existing assessment/feedback routes are retained; no new pass/fail system is added.

## Performance work

- Removes cache:clear, view:clear and config:clear from admin dashboard requests.
- Authentication loads the profile without eager-loading family collections.
  Academy login emails are off by default (ACADEMY_LOGIN_EMAILS=true opts in),
  avoiding synchronous SMTP delays/failures on every login while retaining audit logs.
  Login validation handles missing users/profiles safely with a generic failure.
- Caches application settings for five minutes; model saves/deletes invalidate it.
- Eager-loads student enrollment/group and parent IDs in bulk; avoids library and
  school HR queries while serializing the unchanged list response fields.
- Removes duplicate teacher dashboard loads and loads assignment relationships in
  bulk. Student attendance uses one aggregate query and notices stay tenant/group
  scoped; empty attendance/enrollment is handled in the backend.
- Adds indexes for account, enrollment, homework, attendance and teacher/group
  lookups. The original frontend may still fetch a full roster; changing pagination
  behavior without updating the frontend would hide students, so this does not
  silently truncate the original list.

Query-count tests cover authentication, settings reuse and roster serialization.
These are not live response-time benchmarks. Codespaces startup/network limits,
frontend bundle size and external storage may still affect observed speed.

## Deploy and rollback

Not applied to a running Codespace. Back up database and uploads, preserve local
AppServiceProvider URL fixes and .env, and review the code on a test copy first.
AppServiceProvider is changed for settings caching; merge those changes with the
local preview-URL fix rather than discarding either version.

```bash
composer install
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan test --filter=AcademyBackendTest
```

Do not rerun db:seed or migrate:fresh on an installed academy. The migration adds
indexes only and does not delete records. ACADEMY_MODE=false restores the school
setup/actions while retaining the independent performance fixes. Keep the previous
code release available. No frontend rebuild is required by this source change.
