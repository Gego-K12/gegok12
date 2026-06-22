<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $schools = School::where('status',1)->get();

        foreach ($schools as $school) 
        {
            $keys = ['about_us' , 'admission_open' , 'admission_close_message' , 'admission_close_on' , 'affiliation_no' , 'affiliated_by' , 'date_of_establishment' , 'landline_no' , 'moto'  , 'website']; //'school_logo'

            foreach ($keys as $key)
            {
                DB::table('school_details')->Insert([
                    'school_id'     =>  $school->id,
                    'meta_key'      =>  $key,
                    'meta_value'    =>  '-',
                    'created_at'    =>  date("Y-m-d H:i:s"),
                    'updated_at'    =>  date("Y-m-d H:i:s"),
                ]);
            }
        }
        // DB::table('school_details')->Insert([
        //     'school_id'     =>  '1',
        //     'meta_key'      =>  'board',
        //     'meta_value'    =>  'CBSE',
        //     'created_at'    =>  date("Y-m-d H:i:s"),
        //     'updated_at'    =>  date("Y-m-d H:i:s"),
        // ]);

        DB::table('school_details')->Insert([
            'school_id'     =>  '1',
            'meta_key'      =>  'board',
            'meta_value'    =>  'matric',
            'created_at'    =>  date("Y-m-d H:i:s"),
            'updated_at'    =>  date("Y-m-d H:i:s"),
        ]);
        DB::table('school_details')->Insert([
            'school_id'     =>  '1',
            'meta_key'      =>  'school_logo',
            'meta_value'    =>  '/uploads/demologo.png',
            'created_at'    =>  date("Y-m-d H:i:s"),
            'updated_at'    =>  date("Y-m-d H:i:s"),
        ]);

        // DB::table('school_details')->Insert([
        //     'school_id'     =>  '2',
        //     'meta_key'      =>  'board',
        //     'meta_value'    =>  'State Board',
        //     'created_at'    =>  date("Y-m-d H:i:s"),
        //     'updated_at'    =>  date("Y-m-d H:i:s"),
        // ]);

        /*DB::table('school_details')->Insert([
            'school_id'     =>  '3',
            'meta_key'      =>  'board',
            'meta_value'    =>  'Matriculation',
            'created_at'    =>  date("Y-m-d H:i:s"),
            'updated_at'    =>  date("Y-m-d H:i:s"),
        ]);*/
    }
}