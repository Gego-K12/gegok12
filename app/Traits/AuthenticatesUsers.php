<?php

/**
 * Handles user authentication workflow including validation, throttling, and responses.
 */

namespace App\Traits;

use App\Models\School;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Handle a login request to the application.
     *
     * @return RedirectResponse|Response|JsonResponse
     *
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->normalizeEmailCase($request);

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Match the submitted email case-insensitively against the stored value
     * and substitute the stored casing into the request.
     *
     * Runs before validateLogin() so every downstream check (checkactive,
     * checkexit, checkschool, the credential attempt) sees a consistent,
     * correctly-cased email regardless of the database's collation - MySQL's
     * default collation matches case-insensitively already, but relying on
     * that implicitly is fragile and doesn't hold on every database.
     *
     * @return void
     */
    protected function normalizeEmailCase(Request $request)
    {
        $email = $request->input('email');

        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user = User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();

            if ($user) {
                $request->merge(['email' => $user->email]);
            }
        }
    }

    /**
     * Validate the user login request.
     *
     * Registers custom validators:
     * - checkschool: Validates that the school is active
     * - checkusers: Validates that the user exists
     * - checkactive: Validates that the user is not suspended (inactive status)
     * - checkexit: Validates that the user has not exited (exit status)
     *
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        /**
         * Validator: checkschool
         * Ensures the user's school is active (status = 1).
         * SuperAdmins (usergroup_id == 1) bypass school checks.
         *
         * @param  string  $attribute  The attribute being validated
         * @param  string  $value  The value being validated
         * @param  array  $parameters  Additional parameters
         * @param  \Illuminate\Validation\Validator  $validator  The validator instance
         * @return bool True if school is active or user is superadmin
         */
        Validator::extend('checkschool', function ($attribute, $value, $parameters, $validator) {
            $users = User::orWhere('email', request('email'))
                ->orWhere('mobile_no', request('email'))
                ->orWhere('name', request('email'))
                ->orWhere('registration_number', request('email'))
                ->first();

            if ($users->usergroup_id == 1) {
                return true;
            }

            $school = School::IsActive($users->school_id)->exists();

            return $school == true;
        }, 'Invalid Credentials. You are not in this school');

        /**
         * Validator: checkusers
         * Validates that the user exists in the system.
         *
         * @param  string  $attribute  The attribute being validated
         * @param  string  $value  The value being validated
         * @param  array  $parameters  Additional parameters
         * @param  \Illuminate\Validation\Validator  $validator  The validator instance
         * @return bool True if user exists
         */
        Validator::extend('checkusers', function ($attribute, $value, $parameters, $validator) {
            $users = User::where('email', request('email'))->with('userprofile')->first();

            return $users != null;
        }, 'Invalid Credentials');

        /**
         * Validator: checkactive
         * Validates that neither the user's own status nor their profile's
         * status is 'inactive' (suspended). Admin\UserController::updateStatus()
         * keeps both in sync in normal operation, but this checks both so a
         * user is blocked regardless of which one was actually updated.
         *
         * @param  string  $attribute  The attribute being validated
         * @param  string  $value  The value being validated
         * @param  array  $parameters  Additional parameters
         * @param  \Illuminate\Validation\Validator  $validator  The validator instance
         * @return bool True if user is active
         */
        Validator::extend('checkactive', function ($attribute, $value, $parameters, $validator) {
            $users = User::where('email', request('email'))->with('userprofile')->first();

            if (! $users) {
                return false;
            }

            if ($users->status == 'inactive') {
                return false;
            }

            return ! $users->userprofile || $users->userprofile->status != 'inactive';
        }, 'You are suspended by site admin');

        /**
         * Validator: checkexit
         * Validates that neither the user's own status nor their profile's
         * status is 'exit' (no longer works in school).
         *
         * @param  string  $attribute  The attribute being validated
         * @param  string  $value  The value being validated
         * @param  array  $parameters  Additional parameters
         * @param  \Illuminate\Validation\Validator  $validator  The validator instance
         * @return bool True if user status is not 'exit'
         */
        Validator::extend('checkexit', function ($attribute, $value, $parameters, $validator) {
            $users = User::where('email', request('email'))->with('userprofile')->first();

            if (! $users) {
                return false;
            }

            if ($users->status == 'exit') {
                return false;
            }

            return ! $users->userprofile || $users->userprofile->status != 'exit';
        }, 'You have exited this school');

        $this->validate($request, [
            'email' => 'required|string|checkactive|checkexit',
            'password' => 'bail|required|string|checkschool',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @return Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * Supports flexible login: users can login with either email or registration_number.
     * Validates the input to determine which field to use.
     *
     * @return string The field name ('email' or 'registration_number')
     */
    public function username()
    {
        $login = request()->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'registration_number';
        request()->merge([$field => $login]);

        return $field;
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
