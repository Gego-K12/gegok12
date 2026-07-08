<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Which classes/standards may join a course, e.g. "only Class 9 and
        // 10". A plain pivot: no independent existence outside its parent
        // Course, which is already school-scoped, so it carries no
        // school_id of its own.
        Schema::create('course_eligible_standards', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses');

            $table->integer('standard_id')->unsigned();
            $table->foreign('standard_id')->references('id')->on('standards');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_eligible_standards');
    }
};
