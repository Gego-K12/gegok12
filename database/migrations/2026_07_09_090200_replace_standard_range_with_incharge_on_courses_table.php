<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['min_standard_id']);
            $table->dropForeign(['max_standard_id']);
            $table->dropColumn(['min_standard_id', 'max_standard_id']);

            $table->integer('primary_incharge_user_id')->unsigned()->nullable()->after('category_id');
            $table->foreign('primary_incharge_user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['primary_incharge_user_id']);
            $table->dropColumn('primary_incharge_user_id');

            $table->integer('min_standard_id')->unsigned()->nullable();
            $table->foreign('min_standard_id')->references('id')->on('standards');
            $table->integer('max_standard_id')->unsigned()->nullable();
            $table->foreign('max_standard_id')->references('id')->on('standards');
        });
    }
};
