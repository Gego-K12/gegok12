<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class StudentAssignmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('student_assignments')->Insert([
            [
                'id' => '1',
                'assignment_id' => '1',
                'user_id' => '3',
                'assignment_file' => 'uploads/file.pdf',
                'obtained_marks' => '18',
                'submitted_on' => '2020-04-20',
                'comments' => 'Good',
                'marks_given_by' => '103',
                'marks_given_on' => '2020-04-21',
                'status' => 'completed',
                'created_at' => '2020-04-20 14:34:12',
                'updated_at' => '2020-04-21 13:02:34',
                'deleted_at' => null,
            ],
            [
                'id' => '2',
                'assignment_id' => '1',
                'user_id' => '4',
                'assignment_file' => 'uploads/file.pdf',
                'obtained_marks' => null,
                'submitted_on' => '2020-04-20',
                'comments' => null,
                'marks_given_by' => null,
                'marks_given_on' => null,
                'status' => 'submitted',
                'created_at' => '2020-04-20 16:25:35',
                'updated_at' => '2020-04-20 16:25:35',
                'deleted_at' => null,
            ],
            [
                'id' => '3',
                'assignment_id' => '1',
                'user_id' => '5',
                'assignment_file' => 'uploads/file.pdf',
                'obtained_marks' => '10',
                'submitted_on' => '2020-04-20',
                'comments' => 'Finished',
                'marks_given_by' => '103',
                'marks_given_on' => '2020-04-20',
                'status' => 'completed',
                'created_at' => '2020-04-20 00:00:00',
                'updated_at' => '2020-04-20 00:00:00',
                'deleted_at' => null,
            ],
            [
                'id' => '4',
                'assignment_id' => '5',
                'user_id' => '3',
                'assignment_file' => 'uploads/file.pdf',
                'obtained_marks' => null,
                'submitted_on' => '2020-04-21',
                'comments' => null,
                'marks_given_by' => null,
                'marks_given_on' => null,
                'status' => 'submitted',
                'created_at' => '2020-04-21 11:02:51',
                'updated_at' => '2020-04-21 14:38:34',
                'deleted_at' => null,
            ],
        ]);
    }
}
