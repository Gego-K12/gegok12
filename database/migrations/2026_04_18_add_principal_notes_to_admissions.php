<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->text('principal_notes')->nullable()->after('remarks');
        });
    }

    public function down()
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn('principal_notes');
        });
    }
};
