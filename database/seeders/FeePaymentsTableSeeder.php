<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class FeePaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('fee_payments')->Insert([
            [
                'id' => '1',
                'fee_id' => '3',
                'user_id' => '9',
                'paid_on' => '2020-06-06',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 17:59:04',
                'updated_at' => '2020-06-08 17:59:04',
                'deleted_at' => null,
            ],
            [
                'id' => '2',
                'fee_id' => '1',
                'user_id' => '3',
                'paid_on' => '2020-06-03',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:02:26',
                'updated_at' => '2020-06-08 19:40:51',
                'deleted_at' => null,
            ],
            [
                'id' => '3',
                'fee_id' => '1',
                'user_id' => '4',
                'paid_on' => '2020-06-02',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:02:26',
                'updated_at' => '2020-06-08 18:02:26',
                'deleted_at' => null,
            ],
            [
                'id' => '4',
                'fee_id' => '1',
                'user_id' => '5',
                'paid_on' => '2020-06-02',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:02:26',
                'updated_at' => '2020-06-08 18:02:26',
                'deleted_at' => null,
            ],
            [
                'id' => '5',
                'fee_id' => '1',
                'user_id' => '6',
                'paid_on' => '2020-06-02',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:02:26',
                'updated_at' => '2020-06-08 18:02:26',
                'deleted_at' => null,
            ],
            [
                'id' => '6',
                'fee_id' => '1',
                'user_id' => '7',
                'paid_on' => '2020-06-02',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:02:26',
                'updated_at' => '2020-06-08 18:02:26',
                'deleted_at' => null,
            ],
            [
                'id' => '7',
                'fee_id' => '1',
                'user_id' => '9',
                'paid_on' => '2020-06-02',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:02:26',
                'updated_at' => '2020-06-08 18:02:26',
                'deleted_at' => null,
            ],
            [
                'id' => '8',
                'fee_id' => '1',
                'user_id' => '8',
                'paid_on' => '2020-06-02',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:02:26',
                'updated_at' => '2020-06-08 18:02:26',
                'deleted_at' => null,
            ],
            [
                'id' => '9',
                'fee_id' => '1',
                'user_id' => '10',
                'paid_on' => '2020-06-07',
                'notify_parent' => null,
                'status' => '1',
                'created_at' => '2020-06-08 18:14:18',
                'updated_at' => '2020-06-08 18:14:18',
                'deleted_at' => null,
            ],
            [
                'id' => '10',
                'fee_id' => '1',
                'user_id' => '15',
                'paid_on' => '2020-06-01',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:19:32',
                'updated_at' => '2020-06-08 18:19:32',
                'deleted_at' => null,
            ],
            [
                'id' => '11',
                'fee_id' => '1',
                'user_id' => '20',
                'paid_on' => '2020-06-04',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:20:53',
                'updated_at' => '2020-06-08 18:20:53',
                'deleted_at' => null,
            ],
            [
                'id' => '12',
                'fee_id' => '1',
                'user_id' => '21',
                'paid_on' => '2020-06-04',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:20:53',
                'updated_at' => '2020-06-08 18:20:53',
                'deleted_at' => null,
            ],
            [
                'id' => '13',
                'fee_id' => '1',
                'user_id' => '27',
                'paid_on' => '2020-06-05',
                'notify_parent' => '1',
                'status' => '1',
                'created_at' => '2020-06-08 18:50:02',
                'updated_at' => '2020-06-08 18:50:02',
                'deleted_at' => null,
            ],
        ]);
    }
}
