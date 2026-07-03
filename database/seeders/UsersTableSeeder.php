<?php

namespace Database\Seeders;

use App\Models\TeacherProfile;
use App\Models\Userprofile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // factory(App\Models\User::class, 1)->create([
        //     'name'         =>   'siteadmin',
        //     'email'        =>   'siteadmin@gegok12.com',
        //     'password'     =>  bcrypt('password'),
        //     'mobile_no'    =>   '1230456789',
        //     'usergroup_id' =>   "1"
        // ]);

        // factory(\App\Models\Userprofile::class, 1)->create([
        //         'user_id'       =>  1,
        //         'usergroup_id'  =>  1,
        //         'firstname'     =>  'siteadmin',
        //         'lastname'      =>  'siteadmin',
        //         'profession'    =>  'admin',
        //         'address'       =>  'Madurai,Tamilnadu,India',
        //         'country_id'    =>  '7',
        //         'city_id'       =>  '31',
        //         'state_id'      =>  '24',
        //         'pincode'       =>  '625001'
        //     ]);

        // factory(App\Models\User::class, 1)->create([
        //     'school_id'    =>   '1',
        //     'name'         =>   'schooladmin',
        //     'email'        =>   'admin@gegok12.com',
        //     'mobile_no'    =>   '2230456789',
        //     'usergroup_id' =>   "3"
        //   ]);

        // factory(\App\Models\Userprofile::class, 1)->create([
        //         'school_id'     =>  1,
        //         'user_id'       =>  2,
        //         'usergroup_id'  =>  3,
        //         'firstname'     =>  'schooladmin',
        //         'lastname'      =>  'schooladmin',
        //         'profession'    =>  'admin',
        //         'address'       =>  'Madurai,Tamilnadu,India',
        //         'country_id'    =>  '7',
        //         'city_id'       =>  '31',
        //         'state_id'      =>  '24',
        //         'pincode'       =>  '625001'
        //         ]);

        // $students = factory(App\Models\User::class, 600)->create([
        //     'school_id'    =>   '1',
        //     'usergroup_id' =>   "6"
        // ]);

        // foreach ($students as $student) {
        //     factory(\App\Models\Userprofile::class, 1)->create([
        //         'school_id'     =>  $student->school_id,
        //         'user_id'       =>  $student->id,
        //         'usergroup_id'  =>  $student->usergroup_id
        //     ]);

        //     $father = factory(App\Models\User::class)->create([
        //         'school_id'     => '1',
        //         'usergroup_id'  => "7"
        //     ]);

        //     factory(\App\Models\Userprofile::class)->create([
        //             'school_id'     =>  $student->school_id,
        //             'user_id'       =>  $father->id,
        //             'usergroup_id'  =>  $father->usergroup_id,
        //             'address'       =>  'Madurai,Tamilnadu,India',
        //             'pincode'       =>  '625001',
        //             'gender'        =>  'male',
        //             'date_of_birth' =>  Carbon::now()->subYears(rand(25, 45))
        //     ]);

        //      factory(App\Models\StudentParentLink::class)->create([
        //             'school_id'  => $student->school_id,
        //             'parent_id'  => $father->id,
        //             'student_id' => $student->id
        //     ]);

        //     factory(App\Models\ParentProfile::class)->create([
        //             'school_id'  => $father->school_id,
        //             'user_id'    => $father->id
        //     ]);

        //     $mother = factory(App\Models\User::class)->create([
        //         'school_id'     => '1',
        //         'usergroup_id'  => "7"
        //     ]);

        //     factory(\App\Models\Userprofile::class)->create([
        //             'school_id'     =>  $student->school_id,
        //             'user_id'       =>  $mother->id,
        //             'usergroup_id'  =>  $mother->usergroup_id,
        //             'address'       =>  'Madurai,Tamilnadu,India',
        //             'pincode'       =>  '625001',
        //             'gender'        =>  'female',
        //             'date_of_birth' =>  Carbon::now()->subYears(rand(25, 40)),
        //     ]);

        //      factory(App\Models\StudentParentLink::class)->create([
        //             'school_id'  => $student->school_id,
        //             'parent_id'  => $mother->id,
        //             'student_id' => $student->id
        //     ]);

        //     factory(App\Models\ParentProfile::class)->create([
        //             'school_id'  => $mother->school_id,
        //             'user_id'    => $mother->id
        //     ]);

        //     factory(\App\Models\LibraryCard::class)->create([
        //             'school_id'  => $student->school_id,
        //             'user_id'  => $student->id,
        //     ]);

        // }

        // $teachers =      factory(App\Models\User::class, 60)->create([
        //     'school_id'    =>'1',
        //     'usergroup_id' => "5"
        // ]);

        // foreach ($teachers as $teacher) {
        //     factory(\App\Models\Userprofile::class, 1)->create([
        //             'school_id'    =>'1',
        //             'user_id'=>$teacher->id,
        //             'usergroup_id' => $teacher->usergroup_id,
        //             'address'=>'Madurai,Tamilnadu,India',
        //             'pincode'=>'625001',
        //             'date_of_birth' =>  Carbon::now()->subYears(rand(35, 45)),
        //     ]);

        //     factory(\App\Models\TeacherProfile::class)->create([
        //             'school_id'         =>  $teacher->school_id,
        //             'academic_year_id'  =>  1,
        //             'user_id'           =>  $teacher->id,
        //             'status'            =>  1,
        //         ]);

        //        factory(\App\Models\LibraryCard::class)->create([
        //             'school_id'  => $teacher->school_id,
        //             'user_id'  => $teacher->id,

        //         ]);
        // }

        $librarian = factory(App\Models\User::class)->create([
            'school_id' => '1',
            'name' => 'librarian',
            'email' => 'librarian@mailinator.com',
            'mobile_no' => '2230456701',
            'usergroup_id' => '8',
        ]);

        factory(Userprofile::class)->create([
            'school_id' => $librarian->school_id,
            'user_id' => $librarian->id,
            'usergroup_id' => $librarian->usergroup_id,
            'firstname' => 'librarian',
            'lastname' => 'librarian',
            'profession' => 'librarian',
            'address' => 'Namakkal,Tamilnadu,India',
            'country_id' => '7',
            'city_id' => '31',
            'state_id' => '24',
            'pincode' => '625001',
        ]);

        factory(TeacherProfile::class)->create([
            'school_id' => $librarian->school_id,
            'academic_year_id' => 1,
            'user_id' => $librarian->id,
            'status' => 1,
        ]);

    }
}
