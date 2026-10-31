# Connected academy learning workflow

Open `/learning/academy` after deploying this branch. In academy mode all four roles land here after login. Existing layouts, colors, JavaScript and compiled frontend files are retained. The sidebar now prioritizes academy tasks. The missing main CSS include is restored; this fixes the unstyled dashboard without introducing a redesign.

## Administrator

1. Add a student with name, login email, initial password and optional birthday (ages 6–15). Original `users` and `userprofiles` records are created; credentials are never written to the learning log.
2. Open their learning record. Mark placement pending, scheduled, completed, or waived. Completed placement needs a date; completed/waived placement sets the starting level (1–6) and unit (1–2). Existing students are not claimed to have taken a test.
3. Create or select a parent account and link it to the student. Parents immediately see linked children in their own account. Multiple children and multiple parents are supported.
4. Create an English group with a responsible teacher, course level and optional meeting times/link. Original classrooms, two unit subjects and teacher assignments remain the source used by homework/attendance.
5. Assign the student to a group at their current level. Original group membership and student enrollment update together; prior assignment rows remain archived as history. Existing school/demo groups need their course level set once under Groups & teachers before this workflow can assign them.
6. Search/filter students by placement, waiting for a group or course completion; inspect group, responsible teacher, current level/unit, group history, attendance, published homework and feedback from one record. View who registered accounts, changed placement, linked parents, assigned groups, edited groups and completed units in the learning activity log. Existing application audit logs remain intact.

## Teacher, parent and student

Teachers only see students/classes assigned in the current teaching period. A request-aware model scope protects original classroom, enrollment, homework, assignment and teacher-link queries. Shared class dropdown caches are bypassed for teachers. Original teacher student-detail routes also check assignment, including their JSON subresources; attendance writes verify each submitted student belongs to the selected assigned group. The original eager-loading 404 is fixed at the relationship and dashboard call site.

Teachers share feedback from the student's record. They may complete the current unit together with feedback: Unit 1 → Unit 2 → next level Unit 1, through Level 6 Unit 2 → completed. There is no pass/fail or automatic repeating. A move to the next level requires the administrator to assign a matching group before further progress. Stale resubmissions cannot advance twice. Feedback is visible inside student and linked-parent accounts; a parent can mark it read. This is in-app delivery, not an email/WhatsApp integration.

Parents see only linked children. Students see only themselves. Neither can edit placement, registration, groups or progress. Existing homework, attendance, birthdays and communications are retained. Group archiving requires moving enrolled students first and retains history.

## Deployment and limits

This is source code on GitHub, not an automatic update to a running Codespace. Back up database/uploads; preserve `.env` and the local Codespaces URL fix in `AppServiceProvider`. Stash local changes, fetch and merge this branch, then apply the stash and resolve any provider/dashboard conflicts before continuing. Keep the stash until verified. Run `composer install --no-interaction`, `php artisan migrate --force`, `php artisan config:clear`, `php artisan route:clear`, `php artisan view:clear`. Do not seed again, use `migrate:fresh`, overwrite local built assets or reset the working tree. No npm rebuild is required for these server-rendered forms.

The additive migration creates journey, group metadata, teacher feedback and learning-event tables. It does not rename/delete old classes or fabricate placement history. Group meeting times are free text; automated meeting scheduling/video hosting is not included. Student/parent accounts need real unique login emails. Set a strong initial password and share it privately. Native forms reuse original styling and CSRF protection; no new frontend runtime is required. Live Codespaces performance and browser behavior need a deployment check; automated tests run in isolated SQLite/MySQL databases.

## Try in the current Codespace

From `/var/www`, run:

```bash
git fetch origin && git show origin/feature/academy-student-journey:scripts/preview-academy.sh | bash
```

This updater creates/updates local branch `academy-connected`, saves tracked/untracked code changes to a retained stash, applies them to the new code, and stops on conflicts. It then installs dependencies, applies the additive migrations and clears Laravel caches. `.env` and ignored files stay in place. The known URL/dashboard patches from this session were checked for clean three-way merging. Back up your database/uploads before applying changes to real academy data. This script cannot start a stopped Codespace or change forwarded-port visibility. Once it succeeds, open `/learning/academy` on your existing forwarded URL.
