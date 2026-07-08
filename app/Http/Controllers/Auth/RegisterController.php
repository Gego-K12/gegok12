<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\AdminNotifyNewUserMail;
use App\Mail\EmailVerification;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\SchoolDetail;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Userprofile;
use App\Traits\AuthenticationProcess;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use AuthenticationProcess;
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Create a new school and user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();
        try {
            $school = $this->createSchool($data);

            // $this->createSchoolDetails($school); //added in observer

            $user = $this->createSchoolAdmin($school, $data);

            $this->createUserProfile($user);

            $this->createSchoolSubscription($user);

            $this->createAcademicYear($school);

            $this->sendEmailVerification($user);

            $this->sendAdminNotifyMail($user);

            DB::commit();

            Log::channel('slack')->info('A new user registered.', [
                'Website' => env('APP_URL'),
                'User_id' => $user->id,
                'User_name' => $user->name,
                'School_id' => $user->school_id,
                'School_name' => $user->school->name,
            ]);

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Registration Failed');
        }
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($user->mobile_verified == 0) {
            $this->createAuthentication($user, $request, 'register');

            return redirect('/verifyotp');
        }

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    protected function guard()
    {
        return Auth::guard();
    }

    private function createSchool($data)
    {
        try {
            $school = School::create([
                'name' => $data['school_name'],
                'email' => $data['email'],
                'phone' => $data['mobile_no'],
                'slug' => Str::slug($data['school_name'], '-'),
                'status' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Log::info('New School Created. School Id : '.$school->id.' Name : '.$school->name);

            return $school;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function createUserProfile(User $user)
    {
        try {
            $userProfile = Userprofile::create([
                'user_id' => $user->id,
                'school_id' => $user->school_id,
                'usergroup_id' => '3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Log::info('User Profile created '.$userProfile->id.' for user'.$user->name);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function createSchoolSubscription(User $user)
    {
        try {
            Subscription::create([
                'school_id' => $user->school_id,
                'user_id' => $user->id,
                'plan_id' => '1',
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Log::info('School subscription created for School Id '.$user->school_id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function createSchoolAdmin($school, $data)
    {
        try {
            $user = User::create([
                'school_id' => $school->id,
                'usergroup_id' => '3',
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile_no' => $data['mobile_no'],
                'password' => Hash::make($data['password']),
                'email_verification_code' => Str::random(40),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Log::info('School Admin created for School Id '.$user->school_id);

            return $user;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function createSchoolDetails($school)
    {
        try {
            $keys = ['school_logo', 'moto', 'affiliated_by', 'affiliation_no', 'date_of_establishment', 'board', 'landline_no', 'about_us', 'website'];

            foreach ($keys as $key) {
                $detail = SchoolDetail::create([
                    'school_id' => $school->id,
                    'meta_key' => $key,
                    'meta_value' => '-',
                ]);
            }

            Log::info('School Details for School Id '.$school->id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function createAcademicYear($school)
    {
        try {
            $detail = AcademicYear::create([
                'school_id' => $school->id,
                'name' => '2020-2021',
                'description' => 'Current Academic Year',
                'start_date' => '2020-06-01',
                'end_date' => '2021-05-31',
                'status' => 1,
            ]);

            Log::info('Academic Year for School Id '.$school->id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function sendEmailVerification(User $user)
    {
        try {
            if (env('MAIL_STATUS') == 'on') {
                Mail::to($user->email)->queue(new EmailVerification($user));

                Log::info('Verification Email Sent');
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function sendAdminNotifyMail(User $user)
    {
        try {
            $admin = User::where('usergroup_id', 1)->first();
            if (env('MAIL_STATUS') == 'on') {
                Mail::to($admin->email)->queue(new AdminNotifyNewUserMail($user));

                Log::info('Verification Email Sent');
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
