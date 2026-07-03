<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class PayrollItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('payroll_items')->insert([

            'name' => 'Basic Salary',
            'key' => 'BA',
            'type' => 'defined',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);

        DB::table('payroll_items')->insert([

            'name' => 'Addition Allowance',
            'key' => 'AA',
            'type' => 'earning',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        DB::table('payroll_items')->insert([

            'name' => 'Deduction',
            'key' => 'DN',
            'type' => 'deduction',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        DB::table('payroll_items')->insert([

            'name' => 'Employees State Insurance',
            'key' => 'ESI',
            'type' => 'deduction',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        DB::table('payroll_items')->insert([

            'name' => 'Provident Fund',
            'key' => 'PF',
            'type' => 'deduction',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
        DB::table('payroll_items')->insert([

            'name' => 'Income tax',
            'key' => 'IT',
            'type' => 'deduction',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

        ]);
    }
}
