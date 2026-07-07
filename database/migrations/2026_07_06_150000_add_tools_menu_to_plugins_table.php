<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToolsMenuToPluginsTable extends Migration
{
    /**
     * A third hook type alongside has_menu/has_dashboard_widget: a plugin
     * with has_tools_menu=true gets its resources/views/plugins/{slug}/
     * tools-menu.blade.php included inside the Admin portal's "Tools"
     * flyout submenu, rather than as a top-level sidebar item.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->boolean('has_tools_menu')->default(false)->after('has_dashboard_widget');
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
            $table->dropColumn('has_tools_menu');
        });
    }
}
