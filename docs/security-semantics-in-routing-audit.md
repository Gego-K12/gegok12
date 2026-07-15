# Security Semantics in Routing Audit (Controllers)

## Scope
- Reviewed controller-backed routes across `routes/*.php` and routing bootstrap in `RouteServiceProvider`.
- Focused on routing-level security semantics:
  1. Missing/weak authentication/authorization middleware on sensitive routes.
  2. State-changing operations exposed via `GET` (unsafe method semantics, CSRF-prone).

## 1) Controllers needing stronger route middleware semantics

### Critical: missing auth on impersonation stop
- **Controller:** `Auth\ImpersonateController`
- **Route:** `GET /teacher/impersonate/stop` (`stopImpersonate`)
- **Issue:** Route has no explicit middleware while sibling impersonation routes require `auth` + role middleware.
- **Needed semantics:** add `auth` and appropriate role guard middleware (at minimum parity with other impersonation routes).

### Public operational endpoint
- **Controller:** `TestController`
- **Route:** `GET /checksms` (`checksms`)
- **Issue:** test/operational action is publicly reachable.
- **Needed semantics:** lock to admin/superadmin or remove in production.

## 2) Controllers needing HTTP method hardening in routing (state change via GET)

> These routes are generally inside authenticated groups, but still need security semantics hardening: destructive or mutating operations should not be `GET`.

### Admin / back-office controllers
- `AdmissionController@destroy`
- `AcademicYearController@destroy`
- `HolidaysController@destroy`
- `StandardsLinkController@destroy`
- `NotesController@delete`
- `DisciplineController@destroy`
- `TelephoneDirectoryController@destroy`
- `UserController@resetPassword`
- `DocumentsController@destroy`
- `SubjectController@destroy`
- `EventsController@destroy`
- `HomeWorkController@destroy`
- `Approval\HomeWorkController@destroy`
- `NoticeBoardController@destroy`
- `LeaveTypesController@destroy`
- `BulletinsController@destroy`
- `TaskController@destroy`
- `VisitorLogController@destroy`
- `CallLogController@destroy`
- `PostalRecordController@destroy`
- `PagesController@destroy`
- `PostsController@destroy`
- `PostCommentsController@destroy`
- `PostReplyCommentsController@destroy`

### Teacher / student / librarian / receptionist controllers
- `Teacher\AssignmentController@destroy`
- `Teacher\LeaveController@destroy`
- `Teacher\TaskController@destroy`
- `Teacher\LessonPlanController@destroy`
- `Teacher\HomeWorkController@destroy`
- `Teacher\VisitorLogController@destroy`
- `Teacher\CallLogController@destroy`
- `Teacher\PostalRecordController@destroy`
- `Teacher\PostsController@destroy`
- `Teacher\PostCommentsController@destroy`
- `Teacher\PostReplyCommentsController@destroy`
- `Student\TaskController@destroy`
- `Student\AssignmentController@destroy`
- `Student\HomeworkController@destroy`
- `Student\PostCommentsController@destroy`
- `Student\PostReplyCommentsController@destroy`
- `Librarian\BookCategoryController@destroy`
- `Librarian\BookController@destroy`
- `Librarian\BookLendingController@destroy`
- `Librarian\TaskController@destroy`
- `Receptionist\VisitorLogController@destroy`
- `Receptionist\CallLogController@destroy`
- `Receptionist\PostalRecordController@destroy`
- `Receptionist\TaskController@destroy`
- `Receptionist\TelephoneDirectoryController@destroy`
- `Receptionist\LeaveController@destroy`
- `Accountant\TaskController@destroy`

### API controllers (including teacher API)
- `Api\LeaveController@destroy`
- `Api\HomeworkController@destroy`
- `Api\AssignmentController@destroy`
- `Api\TaskController@destroy`
- `Api\Teacher\Approval\AssignmentController@destroy`
- `Api\Teacher\Approval\HomeworkController@destroy`
- `Api\Teacher\LeaveController@destroy`
- `Api\Teacher\TaskController@destroy`
- `Api\Teacher\DisciplineController@destroy`

## Recommended route-level fixes (doc checklist)
1. **Replace `GET` mutators** with `DELETE`/`POST`/`PATCH` as appropriate.
2. Add explicit `->middleware([...])` on sensitive standalone routes in `web.php`.
3. For API mutators, enforce both auth middleware and ability/policy middleware.
4. Add throttling for auth-adjacent/public endpoints (`login`, password reset, OTP, verification).
5. Ensure frontend calls use CSRF-protected non-GET requests for all destructive actions.

## Notes
- Role route files are mostly protected globally in `RouteServiceProvider` (e.g., admin/teacher/student route groups), but HTTP verb semantics still require hardening.
- This is a routing-semantics audit; controller-internal authorization (`Gate`/policy checks) should be reviewed separately.
