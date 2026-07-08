<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Database\Seeder;

class UsersSchoolAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = School::where('status', 1)->get();

        foreach ($schools as $school) {
            // admin
            $schoolAdmin = User::factory()->create([
                'school_id' => $school->id,
                'name' => 'demoschool',
                'email' => 'demoschool@mailinator.com',
                'mobile_no' => '2230456789',
                'usergroup_id' => 3,
            ]);

            Userprofile::factory()->create([
                'school_id' => $school->id,
                'user_id' => $schoolAdmin->id,
                'usergroup_id' => 3,
                'firstname' => 'demo',
                'lastname' => 'school',
                'profession' => 'admin',
                'address' => 'Namakkal,Tamilnadu,India',
                'country_id' => 7,
                'city_id' => 31,
                'state_id' => 24,
                'pincode' => '625001',
            ]);

            // librarian
            $librarian = User::factory()->create([
                'school_id' => $school->id,
                'name' => 'librarian'.$school->id,
                'email' => 'librarian'.$school->id.'@mailinator.com',
                'mobile_no' => '2230456701',
                'usergroup_id' => 8,
            ]);

            Userprofile::factory()->create([
                'school_id' => $librarian->school_id,
                'user_id' => $librarian->id,
                'usergroup_id' => $librarian->usergroup_id,
                'firstname' => 'librarian',
                'lastname' => 'librarian',
                'profession' => 'librarian',
                'address' => 'Namakkal,Tamilnadu,India',
                'country_id' => 7,
                'city_id' => 31,
                'state_id' => 24,
                'pincode' => '625001',
            ]);

            $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
            TeacherProfile::factory()->create([
                'school_id' => $librarian->school_id,
                'academic_year_id' => $academic_year->id,
                'user_id' => $librarian->id,
                'designation' => 'librarian',
                'status' => 1,
            ]);

            // receptionist
            $receptionist = User::factory()->create([
                'school_id' => $school->id,
                'name' => 'receptionist'.$school->id,
                'email' => 'receptionist'.$school->id.'@mailinator.com',
                'mobile_no' => '2230456702',
                'usergroup_id' => 10,
            ]);

            Userprofile::factory()->create([
                'school_id' => $receptionist->school_id,
                'user_id' => $receptionist->id,
                'usergroup_id' => $receptionist->usergroup_id,
                'firstname' => 'receptionist',
                'lastname' => 'receptionist',
                'profession' => 'others',
                'address' => 'Namakkal,Tamilnadu,India',
                'country_id' => 7,
                'city_id' => 31,
                'state_id' => 24,
                'pincode' => '625001',
            ]);

            $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
            TeacherProfile::factory()->create([
                'school_id' => $receptionist->school_id,
                'academic_year_id' => $academic_year->id,
                'user_id' => $receptionist->id,
                'designation' => 'receptionist',
                'status' => 1,
            ]);

            // accountant
            $accountant = User::factory()->create([
                'school_id' => $school->id,
                'name' => 'accountant'.$school->id,
                'email' => 'accountant'.$school->id.'@mailinator.com',
                'mobile_no' => '2230456703',
                'usergroup_id' => 11,
            ]);

            Userprofile::factory()->create([
                'school_id' => $accountant->school_id,
                'user_id' => $accountant->id,
                'usergroup_id' => $accountant->usergroup_id,
                'firstname' => 'accountant',
                'lastname' => 'accountant',
                'profession' => 'others',
                'address' => 'Namakkal,Tamilnadu,India',
                'country_id' => 7,
                'city_id' => 31,
                'state_id' => 24,
                'pincode' => '625001',
            ]);

            $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
            TeacherProfile::factory()->create([
                'school_id' => $accountant->school_id,
                'academic_year_id' => $academic_year->id,
                'user_id' => $accountant->id,
                'designation' => 'accountant',
                'status' => 1,
            ]);

            // stock_keeper
            $stock_keeper = User::factory()->create([
                'school_id' => $school->id,
                'name' => 'stock_keeper'.$school->id,
                'email' => 'stock_keeper'.$school->id.'@mailinator.com',
                'mobile_no' => '2230456704',
                'usergroup_id' => 12,
            ]);

            Userprofile::factory()->create([
                'school_id' => $stock_keeper->school_id,
                'user_id' => $stock_keeper->id,
                'usergroup_id' => $stock_keeper->usergroup_id,
                'firstname' => 'stock_keeper',
                'lastname' => 'stock_keeper',
                'profession' => 'others',
                'address' => 'Namakkal,Tamilnadu,India',
                'country_id' => 7,
                'city_id' => 31,
                'state_id' => 24,
                'pincode' => '625001',
            ]);

            $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
            TeacherProfile::factory()->create([
                'school_id' => $stock_keeper->school_id,
                'academic_year_id' => $academic_year->id,
                'user_id' => $stock_keeper->id,
                'designation' => 'stock_keeper',
                'status' => 1,
            ]);
        }
    }
}
