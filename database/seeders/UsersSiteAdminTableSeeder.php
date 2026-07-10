<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Userprofile;
use Illuminate\Database\Seeder;

/**
 * Seeds the single platform-level SiteAdmin login (usergroup_id = 1) that
 * gates /plugins (App\Livewire\SiteAdmin\PluginConsole, "siteadmin"
 * middleware / MustBeSiteAdmin) and is the default fallback attribution
 * for gegok12:newPlugin's local-install prompts. Unlike
 * UsersSchoolAdminTableSeeder, this isn't looped per-school — a site
 * admin operates across every school, so school_id is left null.
 */
class UsersSiteAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $siteAdmin = User::factory()->create([
            'name' => 'siteadmin',
            'email' => 'siteadmin@mailinator.com',
            'mobile_no' => '2230456700',
            'usergroup_id' => User::SITEADMIN_USERGROUP_ID,
        ]);

        Userprofile::factory()->create([
            'user_id' => $siteAdmin->id,
            'usergroup_id' => $siteAdmin->usergroup_id,
            'firstname' => 'Site',
            'lastname' => 'Admin',
            'profession' => 'admin',
            'address' => 'Namakkal,Tamilnadu,India',
            'country_id' => 7,
            'city_id' => 31,
            'state_id' => 24,
            'pincode' => '625001',
        ]);
    }
}
