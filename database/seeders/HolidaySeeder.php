<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use DB;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $schools = School::where('status', 1)->get();

        $holidays_2026 = [
            ['New Year', '2026-01-01', '2026-01-01'],
            ['Pongal', '2026-01-14', '2026-01-14'],
            ['Thiruvalluvar Day', '2026-01-15', '2026-01-15'],
            ['Uzhavar Thirunal', '2026-01-16', '2026-01-16'],
            ['Republic Day', '2026-01-26', '2026-01-26'],
            ['Maha Shivaratri', '2026-02-15', '2026-02-15'],
            ['Ramzan', '2026-03-20', '2026-03-20'],
            ['Ugadi', '2026-03-19', '2026-03-19'],
            ['Good Friday', '2026-04-03', '2026-04-03'],
            ['Tamil New Year', '2026-04-14', '2026-04-14'],
            ['Bakrid', '2026-05-28', '2026-05-28'],
            ['Muharram', '2026-08-17', '2026-08-17'],
            ['Krishna Jayanthi', '2026-09-04', '2026-09-04'],
            ['Vinayagar Chaturthi', '2026-09-13', '2026-09-13'],
            ['Gandhi Jayanthi', '2026-10-02', '2026-10-02'],
            ['Ayudha Pooja', '2026-10-19', '2026-10-19'],
            ['Vijaya Dasami', '2026-10-20', '2026-10-20'],
            ['Deepavali', '2026-11-08', '2026-11-08'],
            ['Christmas', '2026-12-25', '2026-12-25'],
        ];

        $holidays_2027 = [
            ['New Year', '2027-01-01', '2027-01-01'],
            ['Pongal', '2027-01-14', '2027-01-14'],
            ['Thiruvalluvar Day', '2027-01-15', '2027-01-15'],
            ['Uzhavar Thirunal', '2027-01-16', '2027-01-16'],
            ['Republic Day', '2027-01-26', '2027-01-26'],
            ['Maha Shivaratri', '2027-03-08', '2027-03-08'],
            ['Ramzan', '2027-02-09', '2027-02-09'],
            ['Ugadi', '2027-04-07', '2027-04-07'],
            ['Good Friday', '2027-03-26', '2027-03-26'],
            ['Tamil New Year', '2027-04-14', '2027-04-14'],
            ['Bakrid', '2027-05-18', '2027-05-18'],
            ['Muharram', '2027-08-06', '2027-08-06'],
            ['Krishna Jayanthi', '2027-08-25', '2027-08-25'],
            ['Vinayagar Chaturthi', '2027-09-02', '2027-09-02'],
            ['Gandhi Jayanthi', '2027-10-02', '2027-10-02'],
            ['Ayudha Pooja', '2027-10-08', '2027-10-08'],
            ['Vijaya Dasami', '2027-10-09', '2027-10-09'],
            ['Deepavali', '2027-10-29', '2027-10-29'],
            ['Christmas', '2027-12-25', '2027-12-25'],
        ];

        foreach ($schools as $school) {

            $academic_year = AcademicYear::where([
                ['school_id', $school->id],
                ['status', 1],
            ])->first();

            foreach ([$holidays_2026, $holidays_2027] as $yearHolidays) {
                foreach ($yearHolidays as $h) {
                    DB::table('events')->insert([
                        'school_id' => $school->id,
                        'academic_year_id' => $academic_year->id,
                        'select_type' => 'school',
                        'title' => $h[0],
                        'category' => 'holidays',
                        'start_date' => $h[1].' 00:00:00',
                        'end_date' => $h[2].' 00:00:00',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
