<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_instructors', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            $table->integer('course_batch_id')->unsigned();
            $table->foreign('course_batch_id')->references('id')->on('course_batches');

            // Exactly one of these two is set — enforced in
            // CourseBatchService, not the database, since Laravel's schema
            // builder here targets MySQL versions without reliable CHECK
            // constraint support across this codebase's supported hosts.
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('external_instructor_id')->unsigned()->nullable();
            $table->foreign('external_instructor_id')->references('id')->on('course_external_instructors');

            $table->string('role')->default('lead');
            $table->string('status')->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index('course_batch_id');
            $table->index('user_id');
            $table->index('external_instructor_id');
        });

        Schema::table('course_batches', function (Blueprint $table) {
            $table->foreign('lead_instructor_id')->references('id')->on('course_instructors');
        });
    }

    public function down()
    {
        Schema::table('course_batches', function (Blueprint $table) {
            $table->dropForeign(['lead_instructor_id']);
        });

        Schema::dropIfExists('course_instructors');
    }
};
