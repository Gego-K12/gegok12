<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\LibraryCard;
use App\Models\RoleUser;
use App\Models\School;
use App\Models\Section;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Userprofile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTeacherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = School::where('status', 1)->get();
        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'development') {
            foreach ($schools as $school) {
                $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
                $uniSubjects = Subject::where([['school_id', $school->id], ['academic_year_id', $academic_year->id]])->where('status', 1)->pluck('name')->unique()->flatten()->toArray();

                $principal = User::factory()->create([
                    'school_id' => $school->id,
                    'usergroup_id' => 5,
                ]);

                Userprofile::factory()->create([
                    'school_id' => $school->id,
                    'user_id' => $principal->id,
                    'usergroup_id' => $principal->usergroup_id,
                    'address' => 'Madurai,Tamilnadu,India',
                    'pincode' => '625001',
                    'date_of_birth' => Carbon::now()->subYears(rand(35, 45)),
                ]);

                TeacherProfile::factory()->create([
                    'school_id' => $school->id,
                    'academic_year_id' => $academic_year->id,
                    'user_id' => $principal->id,
                    'status' => 1,
                    'designation' => 'principal',
                    'specialization' => $uniSubjects[array_rand($uniSubjects)],
                ]);

                LibraryCard::factory()->create([
                    'school_id' => $school->id,
                    'user_id' => $principal->id,
                ]);

                RoleUser::factory()->create([
                    'role_id' => 3,
                    'user_id' => $principal->id,
                ]);

                $standards = Standard::where('school_id', $school->id)->get();
                $sections = Section::where('school_id', $school->id)->get();
                $teacherCount = $standards->count() * $sections->count();

                $teachers = User::factory($teacherCount)->create([
                    'school_id' => $school->id,
                    'usergroup_id' => 5,
                ]);

                foreach ($teachers as $teacher) {
                    Userprofile::factory(1)->create([
                        'school_id' => $teacher->school_id,
                        'user_id' => $teacher->id,
                        'usergroup_id' => $teacher->usergroup_id,
                        'address' => 'Madurai,Tamilnadu,India',
                        'pincode' => '625001',
                        'date_of_birth' => Carbon::now()->subYears(rand(35, 45)),
                    ]);

                    TeacherProfile::factory()->create([
                        'school_id' => $teacher->school_id,
                        'academic_year_id' => $academic_year->id,
                        'user_id' => $teacher->id,
                        'status' => 1,
                        'designation' => 'teacher',
                        'specialization' => $uniSubjects[array_rand($uniSubjects, 1)],
                    ]);

                    LibraryCard::factory()->create([
                        'school_id' => $teacher->school_id,
                        'user_id' => $teacher->id,
                    ]);
                }

                $staffs = User::factory(150)->create([
                    'school_id' => $school->id,
                    'usergroup_id' => 5,
                ]);

                foreach ($staffs as $staff) {
                    Userprofile::factory(1)->create([
                        'school_id' => $staff->school_id,
                        'user_id' => $staff->id,
                        'usergroup_id' => $staff->usergroup_id,
                        'address' => 'Madurai,Tamilnadu,India',
                        'pincode' => '625002',
                        'date_of_birth' => Carbon::now()->subYears(rand(35, 45)),
                    ]);

                    TeacherProfile::factory()->create([
                        'school_id' => $staff->school_id,
                        'academic_year_id' => $academic_year->id,
                        'user_id' => $staff->id,
                        'status' => 1,
                    ]);

                    LibraryCard::factory()->create([
                        'school_id' => $staff->school_id,
                        'user_id' => $staff->id,
                    ]);
                }
            }
        }
    }
}
