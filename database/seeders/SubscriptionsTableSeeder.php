<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $schools = School::where('status', '1')->get();
        foreach ($schools as $school) {
            $admin = User::where([['school_id', $school->id], ['usergroup_id', 3]])->first();
            DB::table('subscriptions')->insert([
                'school_id' => $school->id,
                'user_id' => $admin->id,
                'plan_id' => '1',
                'status' => 'pending',
                'payment_details' => '{"merchant_key":"","txnid":"","amount":"2000.00","firstname":"","email":"","phone":"","hash":"","productinfo":"Subscription Amount","status":"","mode":"","error_Message":"No Error","addedon":""}',
                'end_date' => Carbon::now()->addMonth(1)->format('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
