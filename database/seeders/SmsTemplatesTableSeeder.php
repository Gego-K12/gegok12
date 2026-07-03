<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SmsTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sms_templates')->insert([
            'name' => 'Event',
            'content' => 'Hi..Your event has been scheduled on :date at :location. For more details log in to church social App. https://churchcms.appsexpress.net',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'Wishing you a happy birthday and a wonderful year.',
            'content' => 'Wishing you a happy birthday and a wonderful year.
                          Thanks & Regards 
                          Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'May this special day bring you endless joy and tons of precious memories!Happy birthday.',
            'content' => 'May this special day bring you endless joy and tons of precious memories!Happy birthday.
                          Thanks & Regards 
                          Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'Happy birthday! Here’s to a bright, healthy and exciting future!.',
            'content' => 'Happy birthday! Here’s to a bright, healthy and exciting future!
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'Wishing you a wonderful day and all the most amazing things on your Big Day!Happy birthday.',
            'content' => 'Wishing you a wonderful day and all the most amazing things on your Big Day!Happy birthday.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'Happy birthday! May your day be filled with lots of love and happiness.',
            'content' => 'Happy birthday! May your day be filled with lots of love and happiness.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'May this year surprise you with full of joy and happiness! Happy birthday!',
            'content' => 'May this year surprise you with full of joy and happiness! Happy birthday!
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'Sending you a birthday wish wrapped with all my love. Have a very happy birthday!',
            'content' => 'Sending you a birthday wish wrapped with all my love. Have a very happy birthday!
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'Many happy returns on your birthday today from all of us.We hope you have a wonderful day!',
            'content' => 'Many happy returns on your birthday today from all of us.We hope you have a wonderful day!
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'May your birthday be sprinkled with fun and laughter. Have a great day!',
            'content' => 'May your birthday be sprinkled with fun and laughter. Have a great day!
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday_message',
            'template' => 'Happy Birthday!I hope you have a great day today and the year ahead is full of many blessings.',
            'content' => 'Happy Birthday!I hope you have a great day today and the year ahead is full of many blessings.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'reset_password',
            // 'content'       => 'Your password has been reset.Enter this password to login :token .',
            'content' => 'Click this link :url to reset your password.',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'absent_message',
            'content' => ':message
            Thanks & Regards
            :school_name',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'birthday',
            'content' => ':message
            Thanks & Regards
            :school_name',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'Another year of excellence! Thanks for all the amazing work you do. Your effort and enthusiasm are much needed, and very much appreciated.',
            'content' => 'Another year of excellence! Thanks for all the amazing work you do. Your effort and enthusiasm are much needed, and very much appreciated.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'From all of us… happy anniversary! Thank you for your hard work, your generosity, and your contagious enthusiasm.',
            'content' => 'From all of us… happy anniversary! Thank you for your hard work, your generosity, and your contagious enthusiasm.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'Congratulations on your work anniversary! We appreciate your energy, your kindness, and all the work you do, but most of all, we just appreciate you!',
            'content' => 'Congratulations on your work anniversary! We appreciate your energy, your kindness, and all the work you do, but most of all, we just appreciate you!
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'Congratulations on your work anniversary. Working with a wonderful person like you was always a great experience.',
            'content' => 'Congratulations on your work anniversary. Working with a wonderful person like you was always a great experience.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'Sending heartiest wishes to the nicest employee! We are grateful to you for all the contributions that you afforded to make our company progressed.',
            'content' => 'Sending heartiest wishes to the nicest employee! We are grateful to you for all the contributions that you afforded to make our company progressed.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'Many congratulations on your happy work anniversary! May you accomplish more successful working years with this organization. Wish you good luck.',
            'content' => 'Many congratulations on your happy work anniversary! May you accomplish more successful working years with this organization. Wish you good luck.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'We feel lucky and glad to be a part of your team. Your exceptional leadership is beyond words. Happy work anniversary.',
            'content' => 'We feel lucky and glad to be a part of your team. Your exceptional leadership is beyond words. Happy work anniversary.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'A great employee like you is valuable for both the organization as well as co-workers. Well done and enjoy your happy work anniversary.',
            'content' => 'A great employee like you is valuable for both the organization as well as co-workers. Well done and enjoy your happy work anniversary.
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'This is to remind you that you have come a long way and your contributions have continued to inspire us. Wish you a very Happy Work Anniversary!',
            'content' => 'This is to remind you that you have come a long way and your contributions have continued to inspire us. Wish you a very Happy Work Anniversary!
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'work_anniversary_message',
            'template' => 'Everyone requires a person with an abundance of positive vibe and confidence to get things done in a flawless manner. Thank you for being that person. Warm wishes on your work anniversary!',
            'content' => 'Everyone requires a person with an abundance of positive vibe and confidence to get things done in a flawless manner. Thank you for being that person. Warm wishes on your work anniversary!
                              Thanks & Regards 
                              Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('sms_templates')->insert([
            'name' => 'admission_confirmation',
            'content' => 'Hi Sir\Madam
                            Your Application No. :application_no for the admission in :school_name has been approved.
                            Thanks & Regards
                            Administration Team',
            'status' => '1',
            'template_id' => '1207167890123456790',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
