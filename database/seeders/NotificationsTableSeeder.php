<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('notifications')->insert([
            'id' => '22f33755-8db7-440b-a5ee-74d13120ac81',
            'type' => 'App\Notifications\NewMessageNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => 2,
            'data' => '{"data":"You Have Been Removed From Video Room"}',
            'read_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('notifications')->insert([
            'id' => '5a18e09a-48c7-4321-a67a-b598c0ca8a11',
            'type' => 'App\Notifications\NewMessageNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => 2,
            'data' => '{"data":"Briana Wiegand Disliked Comment In Your Post"}',
            'read_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('notifications')->insert([
            'id' => '943c310f-1535-42ab-8573-2aa3cb83b842',
            'type' => 'App\Notifications\NewMessageNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => 2,
            'data' => '{"data":"Briana Wiegand Removed Dislike For Comment In Your Post"}',
            'read_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
