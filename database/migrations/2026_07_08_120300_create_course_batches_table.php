<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_batches', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('school_id')->unsigned();
            $table->foreign('school_id')->references('id')->on('schools');

            $table->integer('academic_year_id')->unsigned();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses');

            $table->string('name');
            $table->string('code')->nullable();

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('enrollment_start_date')->nullable();
            $table->date('enrollment_end_date')->nullable();

            $table->smallInteger('capacity')->unsigned()->nullable();
            $table->smallInteger('min_enrollment')->unsigned()->nullable();

            $table->string('venue')->nullable();
            $table->string('timing_days')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            // Fixed to invite_only for Phase 1; column exists now so
            // Phase 3 self-enrollment/waitlist is additive, not a migration.
            $table->string('enrollment_mode')->default('invite_only');

            $table->string('status')->default('draft');

            // Denormalized convenience pointer to course_instructors.id for
            // list/roster display without a join; the authoritative
            // multi-instructor list is the course_instructors pivot.
            $table->integer('lead_instructor_id')->unsigned()->nullable();

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'academic_year_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_batches');
    }
};
