<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentHooksToPluginsTable extends Migration
{
    /**
     * Two new portal-wide hooks alongside has_menu/has_dashboard_widget/
     * has_tools_menu: a plugin with has_before_content/has_after_content=true
     * gets its resources/views/plugins/{slug}/{portal}/before-content.blade.php
     * / after-content.blade.php included on EVERY page of that portal,
     * wrapping the page's own content (@yield('content') in
     * layouts/{portal}/layout.blade.php). Unlike menu/dashboard-widget,
     * there's no separate "which page" field — a plugin scopes itself to a
     * specific page from inside its own view via request()->is('admin/teacher/show/*')
     * or similar, the same way any other Blade view would.
     *
     * Also widens profile_tab_scope to allow 'student' (closing the gap
     * where the Admin -> Students detail page had no plugin hook at all,
     * unlike Admin -> Teachers/Staff). Converted from enum to string in the
     * same migration that widens it, for the same reason
     * 2026_07_09_100000_convert_plugin_status_and_source_type_to_string.php
     * converted status/source_type: sqlite's enum is a CHECK constraint
     * baked in at CREATE TABLE time, not a widenable column type, so a
     * MySQL-only ALTER here would silently be untestable on sqlite again.
     * 'both' keeps its existing meaning (teacher + staff) — 'student' is a
     * new, separate value, not folded into 'both', so the one already-
     * installed plugin using profile_tab_scope='both' (workpermission)
     * keeps behaving exactly as it does today.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->boolean('has_before_content')->default(false)->after('has_profile_tab');
            $table->boolean('has_after_content')->default(false)->after('has_before_content');
        });

        Schema::table('plugins', function (Blueprint $table) {
            $table->string('profile_tab_scope', 20)->nullable()->change();
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
            $table->dropColumn(['has_before_content', 'has_after_content']);
        });

        Schema::table('plugins', function (Blueprint $table) {
            $table->enum('profile_tab_scope', ['teacher', 'staff', 'both'])->nullable()->change();
        });
    }
}
