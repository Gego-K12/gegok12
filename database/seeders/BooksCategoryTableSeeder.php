<?php

namespace Database\Seeders;

use App\Models\School;
use DB;
use Illuminate\Database\Seeder;

class BooksCategoryTableSeeder extends Seeder
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
            $categories = ['Astro Physics', 'Arts', 'Comics', 'Computer Science', 'History', 'Music', 'Technology', 'Magazines', 'Question Bank', 'Projects'];
            foreach ($categories as $category) {
                DB::table('books_category')->Insert([
                    'school_id' => $school->id,
                    'category' => $category,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
