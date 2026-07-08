<?php

namespace Database\Seeders;

use App\Helpers\SiteHelper;
use App\Models\LibraryCard;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\StandardLink;
use App\Models\StudentAcademic;
use App\Models\StudentParentLink;
use App\Models\User;
use App\Models\Userprofile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersStudentTableSeeder extends Seeder
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

            $academic_year = SiteHelper::getAcademicyear($school->id);
            $classRooms = StandardLink::where([['school_id', $school->id], ['academic_year_id', $academic_year->id]])->get();
            $i = 10000;
            foreach ($classRooms as $classRoom) {
                $studentCountPerSection = rand(10, 12);

                User::factory($studentCountPerSection)
                    ->create([
                        'school_id' => $classRoom->school_id,
                        'usergroup_id' => 6,
                    ])
                    ->each(function ($student) use ($classRoom, &$i) {

                        $i++;
                        $ageRange = [
                            1 => [3, 4],
                            2 => [4, 5],
                            3 => [5, 6],
                            4 => [6, 7],
                            5 => [7, 8],
                            6 => [8, 9],
                            7 => [9, 10],
                            8 => [10, 11],
                            9 => [11, 12],
                            10 => [12, 13],
                            11 => [13, 14],
                            12 => [14, 15],
                            13 => [15, 16],
                            14 => [16, 17],
                            15 => [17, 18],

                        ];

                        $standardId = $classRoom->standard->id;
                        $minAge = 6;
                        $maxAge = 18;

                        if (isset($ageRange[$standardId])) {
                            $minAge = $ageRange[$standardId][0];
                            $maxAge = $ageRange[$standardId][1];
                        }
                        $age = rand($minAge, $maxAge);
                        $dob = Carbon::now()->subYears($age)->subDays(rand(0, 365));

                        Userprofile::factory(1)->create([
                            'school_id' => $student->school_id,
                            'user_id' => $student->id,
                            'usergroup_id' => $student->usergroup_id,
                            'date_of_birth' => $dob->format('Y-m-d'),
                        ]);
                        LibraryCard::factory()->create([
                            'school_id' => $student->school_id,
                            'user_id' => $student->id,
                        ]);

                        StudentAcademic::factory(1)->create([
                            'school_id' => $student->school_id,
                            'user_id' => $student->id,
                            'standardLink_id' => $classRoom->id,
                            'academic_year_id' => $classRoom->academic_year_id,
                            'roll_number' => $i,
                        ]);

                        // parent
                        User::factory(2)
                            ->create([
                                'school_id' => $student->school_id,
                                'usergroup_id' => 7,
                            ])
                            ->each(function ($parent) use ($student) {
                                Userprofile::factory()->create([
                                    'school_id' => $student->school_id,
                                    'user_id' => $parent->id,
                                    'usergroup_id' => $parent->usergroup_id,
                                    'date_of_birth' => Carbon::now()->subYears(rand(25, 45)),
                                ]);

                                StudentParentLink::factory()->create([
                                    'school_id' => $student->school_id,
                                    'parent_id' => $parent->id,
                                    'student_id' => $student->id,
                                ]);

                                ParentProfile::factory()->create([
                                    'school_id' => $parent->school_id,
                                    'user_id' => $parent->id,
                                ]);

                            });
                        // end parent
                    });
            }
        }
    }
}
