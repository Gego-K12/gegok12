<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPathSourceTypeToPluginsTable extends Migration
{
    /**
     * Widens source_type to add 'path' — a plugin scaffolded locally via
     * `gegok12:newPlugin` into custompackages/gegok12/{slug} and wired up
     * with a composer path repository, as opposed to a real git/zip install.
     *
     * MySQL-only syntax — invalid on the sqlite connection the test suite
     * runs against (see 2026_07_06_140000_add_uninstall_statuses_to_plugins_table.php
     * for why this guard exists: without it, this migration fatals on sqlite
     * and takes down every test that touches the plugins table).
     *
     * @return void
     */
    public function up()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE plugins MODIFY source_type ENUM('git', 'zip', 'path') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE plugins MODIFY source_type ENUM('git', 'zip') NOT NULL");
    }
}
