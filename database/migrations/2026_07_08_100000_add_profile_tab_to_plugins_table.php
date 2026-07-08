<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileTabToPluginsTable extends Migration
{
    /**
     * A fourth hook type alongside has_menu/has_dashboard_widget/has_tools_menu:
     * a plugin with has_profile_tab=true gets a small Livewire-rendered tab
     * strip on the Admin teacher/staff profile pages, showing its published
     * resources/views/plugins/{slug}/profile-tab.blade.php for whichever
     * record is being viewed. profile_tab_scope controls which profile
     * page(s) it appears on.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->boolean('has_profile_tab')->default(false)->after('has_tools_menu');
            $table->string('profile_tab_label')->nullable()->after('has_profile_tab');
            $table->enum('profile_tab_scope', ['teacher', 'staff', 'both'])->nullable()->after('profile_tab_label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->dropColumn(['has_profile_tab', 'profile_tab_label', 'profile_tab_scope']);
        });
    }
}
