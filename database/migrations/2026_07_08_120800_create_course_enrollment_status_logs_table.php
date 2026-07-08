<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Intentionally append-only — no soft-deletes, no edit/delete UI.
        // Needed because on_break/re_entered can cycle more than once and
        // a single status column on course_enrollments can't reconstruct
        // "how many times did this student pause and resume."
        Schema::create('course_enrollment_status_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            $table->integer('course_enrollment_id')->unsigned();
            $table->foreign('course_enrollment_id')->references('id')->on('course_enrollments');

            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->string('reason')->nullable();

            // Null = system-automated (e.g. auto-complete when a batch's
            // end_date passes), not a human action.
            $table->integer('changed_by')->unsigned()->nullable();
            $table->foreign('changed_by')->references('id')->on('users');

            $table->dateTime('changed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_enrollment_status_logs');
    }
};
