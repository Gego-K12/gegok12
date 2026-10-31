# Groups, lesson registers and parent visibility

The administrator creates a named group with a responsible teacher, level, meeting weekdays and start/end times in Africa/Cairo. The old schedule text is retained as meeting notes/link. Existing groups are not assigned invented meeting times; edit their calendar once.

Open a group to add existing students using checkboxes and name/email search. Existing agreed levels must match the group. For students awaiting placement, an explicit administrator checkbox may agree a starting level at this group (Unit 1), recording placement as waived, not completed. Membership and original enrollment transfer atomically, preserving prior group history. Invalid/foreign students roll back the entire batch. This retains the single main course-enrollment model from the earlier workflow.

Teacher login/dashboard opens assigned groups only. Each group shows its meeting days/time, teacher and its own student roster. Open a dated lesson with start time, optional topic and whether homework was assigned. The original roster is snapshotted once. Mark each learner present/absent/excused/unrecorded, and homework done/not done/awaiting review. No homework assigned is a separate state. Reopening the same group/date/time updates the same register; duplicate clicks cannot inflate totals. Later transfers do not rewrite past registers. Teacher feedback messages are independent of unit completion and shared inside the student/linked-parent records.

The parent sees each linked child's absent/present/excused counts, homework done/missing/awaiting-review counts, dated group lesson history, meeting times, teacher messages and unit feedback. Only teacher-recorded statuses are counted: unrecorded is not absence, and homework awaiting review is not missing. Counts come from the new group registers only; earlier legacy attendance is displayed separately to avoid double-counting or inventing mappings between school half-day sessions and academy lessons. Original published homework/communication features remain available.

The additive migration creates lesson, lesson-student and teacher-message records and adds structured calendar fields. It does not reseed/delete/rename existing data. New lesson registers remain linked to original group/user/class IDs. Source compiled assets and existing styling remain unchanged.

To update the existing Codespace (after backing up real data/uploads), run inside `/var/www`:

```bash
git fetch origin && git show origin/feature/academy-group-lessons:scripts/preview-academy.sh | bash
```

The updater retains the local code stash, ignored `.env` and compiled assets, stops on conflicts and runs additive migrations/cache clears. It cannot change GitHub forwarded-port access. Open `/learning/academy/groups` afterwards. It does not automatically update a running Codespace until invoked.
