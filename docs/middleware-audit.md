# Middleware Audit Report

Date: 2026-03-26  
Scope: `app/Http/Middleware/*.php` and middleware registration in `app/Http/Kernel.php`

## Executive Summary

This codebase has **21 custom middleware classes** under `app/Http/Middleware` and additional framework/package middleware configured in the HTTP kernel.

Overall, middleware intent is clear (role-gating, request normalization, CSRF/cookie/proxy handling), but there are several consistency and security-hardening gaps:

- Multiple role middleware assume an authenticated user exists and immediately dereference `Auth::user()`, which can cause runtime errors if `auth` middleware is not applied first.
- `MustBeSiteAdmin` and `MustBeSiteSubAdmin` do not appear to allow their target role through (they only redirect/abort), suggesting possible logic defects.
- `MustBeOTP` can return `null` in at least one code path (admin with `mobile_verified == 1`), resulting in non-deterministic request handling.
- Redirect behavior is inconsistent across role middleware; some roles are handled in one middleware and omitted in others.
- Authorization failures mostly return `404`, which may be intentional for obscurity, but should be standardized and documented.

---

## Middleware Inventory

### Global middleware stack (`Kernel::$middleware`)

1. `App\Http\Middleware\CheckForMaintenanceMode`
2. `Illuminate\Foundation\Http\Middleware\ValidatePostSize`
3. `App\Http\Middleware\TrimStrings`
4. `Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull`
5. `App\Http\Middleware\TrustProxies`
6. `Illuminate\Session\Middleware\StartSession`
7. `Nckg\Impersonate\Impersonate`

### Middleware groups (`Kernel::$middlewareGroups`)

- `web`: Encrypt cookies, queue cookies, share errors, CSRF verification, route model binding.
- `api`: Sanctum stateful frontend middleware, throttle, route model binding.

### Route middleware aliases (`Kernel::$routeMiddleware`)

#### App middleware aliases

- `guest` => `RedirectIfAuthenticated`
- `siteadmin` => `MustBeSiteAdmin`
- `sitesubadmin` => `MustBeSiteSubAdmin`
- `schooladmin` => `MustBeSchoolAdmin`
- `schoolsubadmin` => `MustBeSchoolSubAdmin`
- `teacher` => `MustBeTeacher`
- `librarian` => `MustBeLibrarian`
- `student` => `MustBeStudent`
- `parent` => `MustBeParent`
- `receptionist` => `MustBeReceptionist`
- `accountant` => `MustBeAccountant`
- `stockkeeper` => `MustBeStockKeeper`
- `adminaccountant` => `AdminAccountant`
- `privilegeconditions` => `MustBePrivilege`
- `verifyotp` => `MustBeOTP`
- `alumni` => `MustBeAlumni`

#### Framework/package aliases

- `auth`, `auth.basic`, `bindings`, `cache.headers`, `can`, `signed`, `throttle`
- Laratrust: `role`, `permission`, `ability`

---

## Class-by-Class Audit Findings

## 1) Infrastructure/security baseline middleware

### `CheckForMaintenanceMode`
- **Status:** Standard extension with empty exception list.
- **Risk:** Low.
- **Notes:** Behavior is inherited from framework; no custom logic concerns.

### `EncryptCookies`
- **Status:** Standard extension with no cookie exclusions.
- **Risk:** Low.
- **Notes:** Conservative default.

### `TrimStrings`
- **Status:** Standard extension; excludes password fields.
- **Risk:** Low.

### `TrustProxies`
- **Status:** Trust-proxy headers configured to include common forwarding headers and AWS ELB.
- **Risk:** Medium (configuration-sensitive).
- **Notes:** `protected $proxies` is unspecified (dynamic trust behavior depends on environment/config). Validate deployment-level proxy trust strategy to avoid spoofing risks.

### `VerifyCsrfToken`
- **Status:** No URI exemptions.
- **Risk:** Low.
- **Notes:** Strong default posture.

---

## 2) Auth flow middleware

### `RedirectIfAuthenticated`
- **Behavior:** Redirects any authenticated user to `/admin/dashboard`.
- **Risk:** Medium.
- **Findings:** Not role-aware. Teacher/student/other authenticated users hitting guest-only routes may be redirected to an admin path that may not match their role.
- **Recommendation:** Make redirect role-aware or centralize using a single dashboard resolver.

---

## 3) Role/authorization middleware

> Common pattern concern across these middleware: direct `\Auth::user()->...` calls without null checks. If route ordering is wrong (missing `auth` first), this can throw errors.

**Why this matters (example):** if a route uses `teacher` middleware but does **not** include `auth`, then for a guest request `Auth::user()` is `null`. Calling `Auth::user()->isTeacher()` triggers an error (attempt to read property/method on null), which typically surfaces as a 500 instead of a controlled 401/403/redirect.

**How to prevent it:**
- Ensure role middleware is always attached after `auth` (e.g., `middleware(['auth', 'teacher'])`).
- Add a defensive guard at the top of each role middleware (`if (!Auth::check()) { return redirect(...); }` or abort 401/403 as policy dictates).
- Prefer a single shared base middleware/helper so this check cannot be forgotten in individual classes.

### `AdminAccountant`
- **Behavior:** Allows `usergroup_id` 3 or 11; redirects known other groups; otherwise `404`.
- **Risk:** Medium.
- **Findings:** Hard-coded group IDs and redirect matrix are brittle.
- **Recommendation:** Prefer named role checks and a shared redirect map.

### `MustBeAccountant`
- **Behavior:** Allows accountants, redirects admin/teacher/student/receptionist, otherwise `404`.
- **Risk:** Medium.
- **Findings:** Missing explicit handling for other roles (e.g., librarian, parent, stockkeeper, alumni).

### `MustBeAlumni`
- **Behavior:** Allows alumni; redirects site admin/admin/teacher/librarian; otherwise `404`.
- **Risk:** Medium.
- **Findings:** Unused imports (`User`, `Auth`) and partial redirect coverage.

### `MustBeLibrarian`
- **Behavior:** Allows librarian; redirects admin/teacher/student; otherwise `404`.
- **Risk:** Medium.

### `MustBeParent`
- **Behavior:** Allows parent; redirects site admin/admin/teacher/student/librarian; otherwise `404`.
- **Risk:** Medium.
- **Findings:** Unused imports (`User`, `Auth`).

### `MustBePrivilege`
- **Behavior:** Requires active academic year and at least one standard for user school; redirects to setup pages if missing.
- **Risk:** Medium.
- **Findings:** Trailing `abort(404)` is unreachable dead code.

### `MustBeReceptionist`
- **Behavior:** Allows receptionist; redirects admin/teacher/student/accountant; otherwise `404`.
- **Risk:** Medium.
- **Findings:** Commented debug remnants.

### `MustBeSchoolAdmin`
- **Behavior:** Allows admin; redirects many non-admin roles to role dashboards; otherwise `404`.
- **Risk:** Medium.
- **Notes:** Most complete redirect matrix among role middleware.

### `MustBeSchoolSubAdmin`
- **Behavior:** Allows `usergroup_id == 4`; redirects several fixed IDs; otherwise `404`.
- **Risk:** Medium.
- **Findings:** Hard-coded IDs and partial coverage; unused imports (`User`, `Auth`).

### `MustBeSiteAdmin`
- **Behavior:** Redirects admin/teacher/student/librarian; otherwise `404`.
- **Risk:** **High**.
- **Critical finding:** No pass-through path (`return $next($request)`) exists for site admins. This middleware appears to block everyone.

### `MustBeSiteSubAdmin`
- **Behavior:** Redirects admin/teacher/student/librarian; otherwise `404`.
- **Risk:** **High**.
- **Critical finding:** Same as above—no allow path for target role.

### `MustBeStockKeeper`
- **Behavior:** Allows stockkeeper; redirects admin/teacher/student/receptionist/accountant; otherwise `404`.
- **Risk:** Medium.

### `MustBeStudent`
- **Behavior:** Allows student; redirects admin/teacher/librarian; otherwise `404`.
- **Risk:** Medium.
- **Findings:** Unused imports (`User`, `Auth`).

### `MustBeTeacher`
- **Behavior:** Allows teacher; redirects admin/student/parent/librarian; otherwise `404`.
- **Risk:** Medium.
- **Findings:** Unused imports (`User`, `Auth`).

### `MustBeOTP`
- **Behavior:** For admins with unverified mobile, allows only when extra auth record check passes; otherwise 403.
- **Risk:** **High**.
- **Critical finding:** If admin has `mobile_verified == 1`, method does not return `$next($request)` and falls through with `null` return.
- **Other findings:** Unused import (`Authentication`); repeated DB fetch for current user can be optimized.

---

## Cross-Cutting Issues

1. **Null-user dereference risk**  
   Many middleware assume `Auth::user()` exists. Enforce middleware order (`auth` before role middleware) and/or guard against null users with explicit `if (!Auth::check())` flows.

2. **Authorization semantics are inconsistent**  
   Redirect targets and role handling vary middleware-to-middleware. This can create confusing UX and maintenance overhead.

3. **Hard-coded role IDs vs capability checks**  
   Some classes use `usergroup_id` while others use `isRole()` methods. Prefer one consistent abstraction.

4. **Potential logic defects**  
   `MustBeSiteAdmin`, `MustBeSiteSubAdmin`, and `MustBeOTP` likely contain functional bugs due to missing success return paths.

5. **Dead code / hygiene**  
   Unreachable statements and unused imports are present in multiple files.

---

## Recommended Remediation Plan (Prioritized)

### Priority 0 (fix immediately)
1. Add explicit pass-through (`return $next($request)`) to `MustBeSiteAdmin` and `MustBeSiteSubAdmin` for their intended role users.
2. Fix `MustBeOTP` to always return a response and allow expected admin flow when already verified.

### Priority 1 (stability)
3. Add safe unauthenticated handling in all role middleware (or enforce strict route-level `auth` precondition consistently).
4. Standardize denial behavior (`403` vs `404` vs redirects) and document policy.

### Priority 2 (maintainability)
5. Consolidate role redirect logic into shared helper/trait/service to remove duplication.
6. Replace raw `usergroup_id` checks with canonical role helpers/enums.
7. Remove unused imports, stale comments, and unreachable code.

### Priority 3 (observability)
8. Add automated tests for each middleware:
   - unauthenticated request
   - authorized role
   - each redirected role
   - forbidden/unknown role

---

## Suggested Test Matrix (high-level)

For each role middleware alias in `Kernel::$routeMiddleware`:

- **Case A:** no authenticated user → expected redirect/login or 401/403 (as designed)
- **Case B:** correct role user → HTTP 200 and reaches controller
- **Case C:** known wrong role → expected dashboard redirect
- **Case D:** unsupported role/usergroup → expected forbidden/not found

For `verifyotp` specifically:

- Admin + mobile verified
- Admin + mobile unverified + valid auth record
- Admin + mobile unverified + invalid auth record
- Non-admin user

---

## Audit Conclusion

The middleware layer is structurally complete but has **several high-impact correctness issues** in authorization flow control and **moderate maintainability/security concerns** due to duplicated logic and inconsistent role handling. Addressing the Priority 0 and 1 items will materially improve reliability and reduce access-control regressions.
