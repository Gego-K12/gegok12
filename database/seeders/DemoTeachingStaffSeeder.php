<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\LibraryCard;
use App\Models\RoleUser;
use App\Models\School;
use App\Models\Section;
use App\Models\Standard;
use App\Models\StandardLink;
use App\Models\Subject;
use App\Models\Teacherlink;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\Userprofile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Realistic teaching-staff demo data.
 *
 * Replaces UsersTeacherTableSeeder + StandardsLinkTableSeeder + TeacherTableSeeder
 * with a school hierarchy that mirrors a real school instead of random filler:
 * 1 Principal, 3 Vice Principals, 10 HODs, 20 Senior Teachers, 4 Physical
 * Education Teachers, plus a Class Teacher + Assistant Teacher + Co-ordinator
 * for every standard/section. Every subject in every class is covered by a
 * teacher, so nothing shows up as unassigned in the admin Teaching Staff list.
 */
class DemoTeachingStaffSeeder extends Seeder
{
    /**
     * Fixed, class-independent staff to create per school (designation => count).
     *
     * @var array<string, int>
     */
    private $roleCounts = [
        'vice_principal' => 3,
        'head_of_the_department' => 10,
        'senior_teacher' => 20,
        'physical_education_teacher' => 4,
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
            $uniSubjects = Subject::where([['school_id', $school->id], ['academic_year_id', $academic_year->id]])->where('status', 1)->pluck('name')->unique()->values()->toArray();

            $principal = $this->createStaff($school, $academic_year, 'principal', $uniSubjects[array_rand($uniSubjects)]);
            RoleUser::factory()->create(['role_id' => 3, 'user_id' => $principal->id]);

            // Fixed-role staff pool. Physical education teachers are kept out of the
            // "floater" pool below since none of the seeded subjects are PE-related.
            $floaterPool = [];
            foreach ($this->roleCounts as $designation => $count) {
                for ($i = 0; $i < $count; $i++) {
                    $specialization = $designation == 'physical_education_teacher' ? 'Physical Education' : $uniSubjects[array_rand($uniSubjects)];
                    $staff = $this->createStaff($school, $academic_year, $designation, $specialization);

                    if ($designation != 'physical_education_teacher') {
                        $floaterPool[] = ['teacher' => $staff, 'specialization' => $specialization];
                    }
                }
            }

            $standards = Standard::where('school_id', $school->id)->get();
            $sections = Section::where('school_id', $school->id)->get();
            $floaterIndex = 0;

            foreach ($standards as $standard) {
                foreach ($sections as $section) {
                    $subjects = Subject::where([
                        ['school_id', $school->id],
                        ['academic_year_id', $academic_year->id],
                        ['standard_id', $standard->id],
                        ['section_id', $section->id],
                    ])->get();

                    $classTeacher = $this->createStaff($school, $academic_year, 'teacher', $subjects[0]->name ?? null);
                    $assistantTeacher = $this->createStaff($school, $academic_year, 'assistant_teacher', $subjects[1]->name ?? null);
                    $coordinator = $this->createStaff($school, $academic_year, 'co_ordinator', $subjects[2]->name ?? null);

                    $standardLink = StandardLink::factory()->create([
                        'school_id' => $school->id,
                        'academic_year_id' => $academic_year->id,
                        'standard_id' => $standard->id,
                        'section_id' => $section->id,
                        'class_teacher_id' => $classTeacher->id,
                    ]);

                    RoleUser::factory()->create(['role_id' => 4, 'user_id' => $classTeacher->id]);

                    foreach ($subjects as $key => $subject) {
                        if ($key == 0) {
                            $teacher_id = $classTeacher->id;
                        } elseif ($key == 1) {
                            $teacher_id = $assistantTeacher->id;
                        } elseif ($key == 2) {
                            $teacher_id = $coordinator->id;
                        } else {
                            $teacher_id = $this->pickFloater($floaterPool, $subject->name, $floaterIndex);
                        }

                        Teacherlink::factory()->create([
                            'school_id' => $school->id,
                            'academic_year_id' => $academic_year->id,
                            'standardLink_id' => $standardLink->id,
                            'subject_id' => $subject->id,
                            'teacher_id' => $teacher_id,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Create a teaching-staff user with profile, teacher profile, and library card.
     *
     * @param  School  $school
     * @param  AcademicYear  $academic_year
     * @param  string  $designation
     * @param  string|null  $specialization
     * @return User
     */
    private function createStaff($school, $academic_year, $designation, $specialization)
    {
        $teacher = User::factory()->teacher()->create([
            'school_id' => $school->id,
        ]);

        Userprofile::factory()->create([
            'school_id' => $school->id,
            'user_id' => $teacher->id,
            'usergroup_id' => $teacher->usergroup_id,
            'address' => 'Madurai,Tamilnadu,India',
            'pincode' => '625001',
            'date_of_birth' => Carbon::now()->subYears(rand(28, 55)),
        ]);

        TeacherProfile::factory()->create([
            'school_id' => $school->id,
            'academic_year_id' => $academic_year->id,
            'user_id' => $teacher->id,
            'status' => 1,
            'designation' => $designation,
            'specialization' => $specialization,
        ]);

        LibraryCard::factory()->create([
            'school_id' => $school->id,
            'user_id' => $teacher->id,
        ]);

        return $teacher;
    }

    /**
     * Pick a teacher for a leftover subject: prefer a specialization match from
     * the floater pool, otherwise rotate through it round-robin so assignments
     * spread evenly instead of piling onto one random teacher.
     *
     * @param  array  $pool
     * @param  string  $subjectName
     * @param  int  $index
     * @return int
     */
    private function pickFloater($pool, $subjectName, &$index)
    {
        foreach ($pool as $entry) {
            if ($entry['specialization'] == $subjectName) {
                return $entry['teacher']->id;
            }
        }

        $entry = $pool[$index % count($pool)];
        $index++;

        return $entry['teacher']->id;
    }
}
