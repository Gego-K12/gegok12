<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB; 

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('settings')->insert([
            'key'           => 'sitetitle',
            'name'          => 'Site Title',
            'description'   => 'Site Title to show in Browser Bar',
            'value'         => 'School-Plus',
            'field'         => '{"name":"value","label":"Value", "title":"Site Title" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 

        ]);

        DB::table('settings')->insert([
            'key'           => 'sitename',
            'name'          => 'Site Name',
            'description'   => 'This site name is used in emails and copyrights',
            'value'         => 'School-Plus',
            'field'         => '{"name":"value","label":"Value", "title":"Site Title" ,"type":"text"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 

        ]);

        DB::table('settings')->insert([
            'key'           => 'sitelogo',
            'name'          => 'Site Logo',
            'description'   => 'Logo of the website. Recommended Size : 220px (w) x 45px (h)',
            'value'         => 'images/logo.png',
            'field'         => '{"name":"value","label":"Value" ,"type":"browse"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 

        ]);

       
    
        DB::table('settings')->insert(
        [
            'key'           => "favicon",
            'name'          => "Favicon",
            'description'   => "Site Favicon",
            'value'         => 'images/favicon.png',
            'field'         => '{"name":"value","label":"Value", "title":"Site Favicon" ,"type":"browse", "disk":"uploads"}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"),  
        ]);       
      
    
      DB::table('settings')->insert([
            'key'           => 'maintenance',
            'name'          => 'Maintenance',
            'description'   => 'Maintenance',
            'value'         => 0,
            'field'         => '{"name":"value","label":"Maintenance" ,"type":"radio", "options":{"1":"Active", "0":"Inactive"}}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 

        ]);
       DB::table('settings')->insert([
            'key'           => 'login_status',
            'name'          => 'login',
            'description'   => 'login',
            'value'         => 1,
            'field'         => '{"name":"value","label":"Userlogin" ,"type":"radio", "options":{"1":"Active", "0":"Inactive"}}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 

        ]);
        DB::table('settings')->insert([
            'key'           => 'register_status',
            'name'          => 'Register Status',
            'description'   => 'Register Status',
            'value'         => 1,
            'field'         => '{"name":"value","label":"Register Status" ,"type":"radio", "options":{"1":"Active", "0":"Inactive"}}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 

        ]);
        DB::table('settings')->insert([
            'key'           => 'assignment_status',
            'name'          => 'Assignment Status',
            'description'   => 'Assignment Status',
            'value'         => 1,
            'field'         => '{"name":"value","label":"Register Status" ,"type":"radio", "options":{"1":"Active", "0":"Inactive"}}',
            'active'        => 0,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 

        ]);
        DB::table('settings')->insert([
            'key'           => 'homework_status',
            'name'          => 'Homework Status',
            'description'   => 'Homework Status',
            'value'         => 0,
            'field'         => '{"name":"value","label":"Register Status" ,"type":"radio", "options":{"1":"Active", "0":"Inactive"}}',
            'active'        => 1,
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"), 

        ]);

      
    }
}
