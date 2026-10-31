# Groups, personal learning links and teacher account

## Group organization

The administrator must choose both level and teacher before group cards are queried/displayed. Matching groups appear in a responsive grid, with meeting times, responsible teacher, collapsible assigned students and direct roster/edit actions. Teachers see only their own current-period groups, with an optional level filter. Student search supports assigned/unassigned filters. The group enrollment picker includes students assigned to OTHER groups and shows the current group name before a transfer; students already in this same group remain in its roster.

Times are displayed and entered with hours/minutes/AM-PM in Cairo time. Storage stays in 24-hour format for stable lesson uniqueness, ordering and backward compatibility.

## Personal Drive resources

Student registration and profile editing accept three optional HTTPS links: the personal homework upload folder, lecture recordings and report cards/Excel or Google Sheet. These links belong to the original student's journey record and appear to the student, linked parents and assigned staff. Access checks run before loading the record. URLs are escaped when displayed and unsafe schemes rejected. These are external links: no file upload to Drive, Google account connection or Drive permission change is performed by this application. The academy must share each destination with the intended people separately.

## Teacher leave and attendance

The teacher menu restores tasks, lesson plans, calendar, profile photo/password, private messages and notices, and adds native My leave requests and My attendance pages alongside lesson registers/student feedback.

Leave requests reuse the ORIGINAL `teacher_leave_applications` table. A teacher submits dates/reason, sees their own status and administrator comment, and may withdraw a pending request. Only an academy administrator can approve/reject; rejection requires a comment. Overlapping pending/approved requests are blocked. Decisions and withdrawals are logged. The original enum encodes rejection as `cancelled`; additive `academy_decision=rejected` makes it distinct in the new interface without rewriting historical records or modifying the enum. Older cancelled rows cannot be reliably inferred as rejected vs withdrawn, so their original status is retained.

Own attendance reads the ORIGINAL staff rows in `attendances`, restricted to the signed-in teacher and academy, and does not mix student/class registers. Administrators can record/correct teacher AM, PM or full-day attendance idempotently. A full day writes two original sessions; counters explicitly count recorded sessions, not invented days. Teachers cannot edit their attendance, inspect another teacher or approve leave. No synchronous SMTP delivery is added; decisions appear in the teacher account.

## Four connected test accounts

After updating, run inside the Codespace:

```bash
php artisan academy:demo-accounts --school=1
```

This prepares exactly four additional, connected test accounts and one Level 1 test group:

| Role | Email |
|---|---|
| Administrator | admin.demo@accelhub.test |
| Teacher | teacher.demo@accelhub.test |
| Student | student.demo@accelhub.test |
| Parent | parent.demo@accelhub.test |

The command prints a random initial password in the administrator's terminal. It never stores a known default password in source, deletes old accounts, resets old passwords, reseeds unrelated data or erases test progress. Repeating it keeps existing passwords. `--reset-passwords` explicitly rotates ONLY these four passwords. The `.test` addresses are login identifiers; they do not receive external email.

Optional `--test-only` writes `ACADEMY_TEST_ONLY=true` to this installation's `.env`: only the four reserved accounts may sign in; prior sessions in the academy are logged out, and the core student/group lists focus on the prepared cohort. Account registration is intentionally disabled in this limited mode. Existing accounts/data are retained. Set `ACADEMY_TEST_ONLY=false` and clear config to return to normal operation and test adding additional accounts. Without this option, the four accounts coexist with all existing users; select Level 1 and the demo teacher to view their group. Test-account preparation is NOT executed automatically during migrations/deployment.

## Update

```bash
git fetch origin && git show origin/feature/academy-teacher-workspace:scripts/preview-academy.sh | bash
```

The updater retains the local code stash, ignored `.env` and compiled assets, stops on conflicts and runs additive migrations/cache clears. Back up real data/uploads first. It cannot change GitHub forwarded-port visibility. Changes are source on GitHub until this is run in the actual Codespace. Native route/view and service tests cover the exposed academy flows and permission boundaries; no live browser verification or blanket guarantee for every historical school/add-on endpoint is implied.
