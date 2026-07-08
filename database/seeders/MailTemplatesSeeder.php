<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class MailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mailtemplates')->insert([
            'id' => '1',
            'name' => 'login',
            'subject' => 'Logged In',
            'mail_content' => 'Hi :name <br> 
                                Successful Authorization. <br>
                                You have successfully logged into  your account. <br>
                                Don"t recognize this activity?  <br>
                                Please  change password  for your email immediately   <br>
                                Thanks & Regards <br>                         
                                Administration Team <br>',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '2',
            'name' => 'new_user_register',
            'subject' => 'New User Registration',
            'mail_content' => 'Hello ! <br> 
                                New user has registered - :mail. Please login to see details.
                                Thanks & Regards <br>                         
                                Administration Team <br>',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '3',
            'name' => 'reset_password',
            'subject' => 'Reset Password',
            'mail_content' => 'Hi :name <br> 
                                Please click below link to reset your password. <br>
                                <a href= " :resetlink" style="border: none;
                                    color: white; padding: 10px 15px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; background-color: #008CBA;">Reset Password</a> <br>
                                Thanks & Regards <br> 
                                Administration Team <br>',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '4',
            'name' => 'expired_approve_alert',
            'subject' => 'New Expired Alert',
            'mail_content' => 'Hi <br>
                            Subscription Expiration Details<br>

                            School Name - :school_name 
                            User Name   - :name 
                            End Date    - :end_date 
                      
                            New expired has been posted for this school 
                            If you want to continue subscription , Please click the below link
                            
                            :url 

                            Thanks & Regards 
                            Administration Team',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '5',
            'name' => 'site_expired_mail',
            'subject' => 'Site Expired Mail ',
            'mail_content' => 'Hi <br>
                            Subscription Expiration Details<br>

                            School Name - :school_name 
                            User Name   - :name 
                            End Date    - :end_date
                      
                            Your site going to expiry within a week 
                            Thanks & Regards 
                            Administration Team ',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '6',
            'name' => 'contact',
            'subject' => 'Contact',
            'mail_content' => 'Hi <br>
                            Contact Details. <br>
                                  
                            Name          - :fullname 
                            Email         - :emailid 
                            Serve         - :serve_at 
                            Role          - :role 
                            Phone number  - :contact_no
                            Select        - :select
                                  
                            Contact Details Created 
                            Thanks & Regards 
                            Administration Team ',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '7',
            'name' => 'send_mail',
            'subject' => ' :subject ',
            'mail_content' => 'Hi :name, <br>
            :message <br>
            <a href= ":attachments" style="border:none; color:white; padding:10px 15px; text-align:center; text-decoration:none; display:inline-block;  font-size:16px; margin:4px 2px; cursor: pointer; background-color:#008CBA;">Click Here</a> <br>
            Thanks & Regards 
            Administration Team',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '8',
            'name' => 'calendar_event',
            'subject' => 'Calendar Event',
            'mail_content' => 'Hi <br>
                            Event Details. <br>
                                  
                            Title       - :title 
                            Location    - :location 
                            Category    - :category 
                            Start Date  - :start_date 
                            End Date    - :end_date

                            New Event Created 
                            Thanks & Regards 
                            Administration Team ',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '9',
            'name' => 'event_reminder',
            'subject' => 'Event Reminder',
            'mail_content' => 'Hi <br>
                            Event Reminder Details<br>

                            School Name   - :school_name 
                            Title         - :title 
                            Description   - :description
                            Location      - :location
                            Start Date    - :start_date 
                            End Date      - :end_date
                      
                            New event has been posted for this church. 
                           
                            Thanks & Regards 
                            Administration Team ',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '10',
            'name' => 'birthday_reminder',
            'subject' => 'Birthday Wishes',
            'mail_content' => ':message 

                                Thanks & Regards 
                                Administration Team',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '11',
            'name' => 'email_verification',
            'subject' => 'Email Verification',
            'mail_content' => 'Hi :name <br>
            To verify your accout. <br>
            <a href= ":url" style="border: none;color: white; padding: 10px 15px;text-align: center; text-decoration: none; display: inline-block; font-size: 16px;margin: 4px 2px; cursor: pointer; background-color: #008CBA;">Click here to verify</a> <br>
            Thanks & Regards <br> 
            Administration Team <br>',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '12',
            'name' => 'new_message',
            'subject' => 'Send Mail to User',
            'mail_content' => 'Hi :name, <br> 
                                :message
                                Thanks & Regards <br> 
                                Administration Team <br>',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '13',
            'name' => 'room_invitation',
            'subject' => 'Room Invitation',
            'mail_content' => 'Hi :name, <br> 
                                Title - :title <br> 
                                Description - :description <br> 
                                :message <br> 
                                Thanks & Regards <br>
                                Thanks & Regards <br> 
                                Administration Team <br>',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '14',
            'name' => 'change_password',
            'subject' => 'Change Password',
            'mail_content' => 'Hi :name <br> 
                                Your Password is changed successfully. <br> 
                                Thanks & Regards <br> 
                                <p>Administration Team <br>',
            'status' => 'active',
        ]);

        DB::table('mailtemplates')->insert([
            'id' => '15',
            'name' => 'admission_confirmation',
            'subject' => 'Admission Confirmation Mail',
            'mail_content' => 'Hi Sir\Madam <br> 
                                Your Application No. :application_no for the admission in :school_name has been approved. <br> 
                                Thanks & Regards <br> 
                                <p>Administration Team <br>',
            'status' => 'active',
        ]);
    }
}
