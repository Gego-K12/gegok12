<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Database\Seeder;

/**
 * Realistic non-teaching-staff demo data.
 *
 * UsersSchoolAdminTableSeeder already creates one fixed-name login per role
 * (librarian1, receptionist1, ...) for manual QA — this seeder adds a
 * realistic-sized roster on top of that so the admin "Non-Teaching Staff"
 * list isn't just those four accounts. Counts are fixed per school rather
 * than tied to class count, since these roles scale with campus size, not
 * number of classes.
 */
class DemoNonTeachingStaffSeeder extends Seeder
{
    /**
     * Designation => [count, usergroup_id], mirroring the mapping in
     * StaffController::store().
     *
     * @var array<string, array{0: int, 1: int}>
     */
    private $roleCounts = [
        'accountant' => [2, User::ACCOUNTANT_USERGROUP_ID],
        'receptionist' => [2, User::RECEPTIONIST_USERGROUP_ID],
        'librarian' => [1, User::LIBRARIAN_USERGROUP_ID],
        'stock_keeper' => [2, User::STOCK_KEEPER_USERGROUP_ID],
        'lab_assistant' => [3, User::NON_TEACHING_USERGROUP_ID],
        'clerk' => [4, User::NON_TEACHING_USERGROUP_ID],
        'peon' => [5, User::NON_TEACHING_USERGROUP_ID],
        'driver' => [4, User::NON_TEACHING_USERGROUP_ID],
        'helpers' => [6, User::NON_TEACHING_USERGROUP_ID],
        'security' => [3, User::NON_TEACHING_USERGROUP_ID],
        'transport_coordinator' => [1, User::NON_TEACHING_USERGROUP_ID],
        'others' => [2, User::NON_TEACHING_USERGROUP_ID],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') != 'local' && env('APP_ENV') != 'development') {
            return;
        }

        $schools = School::where('status', 1)->get();

        foreach ($schools as $school) {
            $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();

            foreach ($this->roleCounts as $designation => [$count, $usergroup_id]) {
                for ($i = 0; $i < $count; $i++) {
                    $this->createStaff($school, $academic_year, $designation, $usergroup_id);
                }
            }
        }
    }

    /**
     * Create a non-teaching-staff user with profile and teacher profile.
     *
     * @param  School  $school
     * @param  AcademicYear  $academic_year
     * @param  string  $designation
     * @param  int  $usergroup_id
     * @return User
     */
    private function createStaff($school, $academic_year, $designation, $usergroup_id)
    {
        $staff = User::factory()->create([
            'school_id' => $school->id,
            'usergroup_id' => $usergroup_id,
        ]);

        Userprofile::factory()->create([
            'school_id' => $school->id,
            'user_id' => $staff->id,
            'usergroup_id' => $usergroup_id,
            'address' => 'Madurai,Tamilnadu,India',
            'pincode' => '625001',
            'date_of_birth' => now()->subYears(rand(22, 55)),
        ]);

        TeacherProfile::factory()->create([
            'school_id' => $school->id,
            'academic_year_id' => $academic_year->id,
            'user_id' => $staff->id,
            'status' => 1,
            'designation' => $designation,
            'specialization' => null,
        ]);

        return $staff;
    }
}
