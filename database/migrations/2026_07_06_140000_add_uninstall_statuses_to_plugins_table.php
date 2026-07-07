<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddUninstallStatusesToPluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * The original enum only allowed the install-side statuses
     * (staged, installing, installed, failed). MySQL silently truncates
     * any value outside an enum's allowed list to '' in non-strict SQL
     * mode instead of erroring, which is exactly how the first uninstall
     * attempt silently lost its 'uninstall_staged' status. Using raw SQL
     * here rather than Schema::table()->enum() since changing an existing
     * enum's value list requires doctrine/dbal for the query-builder form.
     *
     * @return void
     */
    public function up()
    {
        // MySQL-only syntax — invalid on the sqlite connection the test
        // suite runs against; sqlite's enum is a CHECK constraint rather
        // than a widenable column type anyway, so this is a no-op there
        // (tests don't exercise the uninstall-side statuses added here).
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE plugins MODIFY status ENUM('staged', 'installing', 'installed', 'failed', 'uninstall_staged', 'uninstalling', 'uninstalled') NOT NULL DEFAULT 'staged'");
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

        DB::statement("ALTER TABLE plugins MODIFY status ENUM('staged', 'installing', 'installed', 'failed') NOT NULL DEFAULT 'staged'");
    }
}
