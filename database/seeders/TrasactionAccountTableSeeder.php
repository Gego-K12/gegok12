<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TrasactionAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('transaction_accounts')->insert([
            'school_id' => '1',
            'user_id' => '1',
            'account_number' => '348738747833',
            'ifsc_code' => 'CASH123',
            'name' => 'Cash Account',
            'key' => 'CA',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    }
}
