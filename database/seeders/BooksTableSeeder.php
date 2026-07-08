<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
// use Faker\Generator as Faker;
use App\Models\Book;
use App\Models\School;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
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
            $academic_year = AcademicYear::where([['school_id', $school->id], ['status', 1]])->first();
            Book::factory(1000)->create([
                'school_id' => $school->id,
                'academic_year_id' => $academic_year->id,
            ]);
        }
    }
}
