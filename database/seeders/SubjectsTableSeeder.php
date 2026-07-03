<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Section;
use App\Models\Standard;
use DB;
use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coreSubjects = [
            'prekg' => ['English', 'Mathematics', 'General Awareness', 'Environmental Science'],
            'lkg' => ['English', 'Mathematics', 'General Awareness', 'Environmental Science'],
            'ukg' => ['English', 'Mathematics', 'General Awareness', 'Environmental Science'],
            '1' => ['Skills', 'Tamil', 'English', 'Mathematics', 'Environmental Science'],
            '2' => ['Skills', 'Tamil', 'English', 'Mathematics', 'Environmental Science'],
            '3' => ['Skills', 'Tamil', 'English', 'Mathematics', 'Environmental Science'],
            '4' => ['Skills', 'English', 'Mathematics', 'Science', 'Social Science'],
            '5' => ['Skills', 'English', 'Mathematics', 'Science', 'Social Science'],
            '6' => ['English', 'Mathematics', 'Science', 'Social Science'],
            '7' => ['English', 'Mathematics', 'Science', 'Social Science'],
            '8' => ['English', 'Mathematics', 'Science', 'Social Science'],
            '9' => ['English', 'Mathematics', 'Science', 'Social Science'],
            '10' => ['English', 'Mathematics', 'Science', 'Social Science'],
            '11' => ['English'],
            '12' => ['English'],
        ];

        $elective = [
            'languages' => [
                'A' => 'Tamil',
                'B' => 'Sanskrit',
                'C' => 'Hindi',
                'D' => 'French',
                'E' => 'Spanish',
                'F' => 'German',
            ],
            'subjects' => [
                'A' => ['Maths', 'Physics', 'Chemistry', 'Biology'],
                'B' => ['Maths', 'Physics', 'Chemistry', 'Computer Sceince'],
                'C' => ['Maths', 'Physics', 'Chemistry', 'Biochemistry'],
                'D' => ['Business Maths', 'Commerce', 'Accounts', 'Economics'],
                'E' => ['Physics', 'Chemistry', 'Biology', 'BioChemistry'],
                'F' => ['Computer Science', 'Commerce', 'Accounts', 'Economics'],
            ],
        ];
        $schools = School::where('status', 1)->get();
        foreach ($schools as $school) {
            $standards = Standard::where('school_id', $school->id)->get();
            $sections = Section::where('school_id', $school->id)->get();

            foreach ($standards as $standard) {
                foreach ($sections as $section) {
                    $academic_year = AcademicYear::where([['school_id', $standard->school_id], ['status', 1]])->first();
                    $subjects = $coreSubjects[$standard->name];
                    foreach ($subjects as $subject) {
                        DB::table('subjects')->Insert([
                            'school_id' => $standard->school_id,
                            'academic_year_id' => $academic_year->id,
                            'standard_id' => $standard->id,
                            'section_id' => $section->id,
                            'name' => $subject,
                            'code' => strtoupper(substr($subject, 0, 3)).'-'.$section->name.'-'.$standard->name,
                            'type' => 'core',
                            'status' => '1',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                    $langElective = ['4', '5', '6', '7', '8', '9', '10', '11', '12'];
                    if (in_array($standard->name, $langElective)) {
                        DB::table('subjects')->Insert([
                            'school_id' => $standard->school_id,
                            'academic_year_id' => $academic_year->id,
                            'standard_id' => $standard->id,
                            'section_id' => $section->id,
                            'name' => $electiveLanguage = $elective['languages'][$section->name],
                            'code' => 'EL'.'-'.strtoupper(substr($electiveLanguage, 0, 3)).'-'.$section->name.'-'.$standard->name,
                            'type' => 'elective',
                            'status' => '1',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                    $subElective = ['11', '12'];
                    if (in_array($standard->name, $subElective)) {
                        $elSubjects = $elective['subjects'][$section->name];
                        foreach ($elSubjects as $subject) {
                            DB::table('subjects')->Insert([
                                'school_id' => $standard->school_id,
                                'academic_year_id' => $academic_year->id,
                                'standard_id' => $standard->id,
                                'section_id' => $section->id,
                                'name' => $subject,
                                'code' => strtoupper(substr($subject, 0, 5)).'-'.$section->name.'-'.$standard->name,
                                'type' => 'core',
                                'status' => '1',
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }
                }
            }
        }
    }
}
