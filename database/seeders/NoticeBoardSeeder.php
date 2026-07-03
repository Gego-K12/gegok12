<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\NoticeBoard;
use App\Models\School;
use App\Models\StandardLink;
use Illuminate\Database\Seeder;

class NoticeBoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (env('APP_ENV') == 'local' || env('APP_ENV') == 'development') {

            $schools = School::where('status', 1)->get();

            foreach ($schools as $school) {
                $academic_year = AcademicYear::where([
                    ['school_id', $school->id],
                    ['status', 1],
                ])->first();

                if (! $academic_year) {
                    continue;
                }

                $types = ['class', 'school', 'teacher'];

                foreach ($types as $type) {
                    if ($type == 'class') {
                        $standardLinks = StandardLink::where([
                            ['school_id', $school->id],
                            ['academic_year_id', $academic_year->id],
                        ])->get();

                        foreach ($standardLinks as $std) {

                            $noticeboard = NoticeBoard::factory(50)->create([
                                'school_id' => $school->id,
                                'academic_year_id' => $academic_year->id,
                                'standardLink_id' => $std->id,
                                'type' => $type,
                                'status' => 1,
                            ]);
                        }
                    } else {
                        $noticeboard = NoticeBoard::factory(100)->create([
                            'school_id' => $school->id,
                            'academic_year_id' => $academic_year->id,
                            'standardLink_id' => $std->id,
                            'type' => $type,
                            'status' => 1,
                        ]);

                    }

                }

            }
        }
    }
}
