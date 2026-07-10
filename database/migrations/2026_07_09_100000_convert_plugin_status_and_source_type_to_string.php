<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConvertPluginStatusAndSourceTypeToString extends Migration
{
    /**
     * Replaces the `status`/`source_type` enums with plain strings.
     *
     * The two prior "widen the enum" migrations
     * (2026_07_06_140000_add_uninstall_statuses_to_plugins_table.php,
     * 2026_07_08_110000_add_path_source_type_to_plugins_table.php) both had
     * to skip themselves entirely on sqlite, since sqlite enforces an enum
     * via an unmodifiable CHECK constraint baked in at CREATE TABLE time —
     * which meant the uninstall-side statuses ('uninstall_staged',
     * 'uninstalling', 'uninstalled') and the 'path' source_type were
     * completely untestable on the sqlite connection the test suite runs
     * against, and every future addition would need the same MySQL-only
     * workaround. A plain string sidesteps that class of problem on both
     * drivers going forward — valid-value checking already happens at the
     * application layer (Plugin's status is only ever set by
     * PluginInstaller/PluginConsole, never from user input directly).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->string('status', 20)->default('staged')->change();
            $table->string('source_type', 10)->change();
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
            $table->enum('status', ['staged', 'installing', 'installed', 'failed', 'uninstall_staged', 'uninstalling', 'uninstalled'])->default('staged')->change();
            $table->enum('source_type', ['git', 'zip', 'path'])->change();
        });
    }
}
