<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PageCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = ['english', 'general_knowledge', 'mathematics', 'others', 'science', 'social_studies', 'tamil'];

        foreach ($categories as $category) {

            DB::table('class_room_page_categories')->Insert([
                'school_id' => 1,
                'academic_year_id' => 1,
                'name' => $category,
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
